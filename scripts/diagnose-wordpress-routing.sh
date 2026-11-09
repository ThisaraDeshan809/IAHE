#!/usr/bin/env bash
set -u

usage() {
  cat <<'EOF'
Usage: scripts/diagnose-wordpress-routing.sh --site-path /absolute/wordpress/path --site-url https://example.test

Performs read-only WordPress routing checks. It does not change WordPress,
.htaccess, plugins, or the database.
EOF
}

site_path=''
site_url=''
while (($#)); do
  case "$1" in
    --site-path)
      [[ $# -ge 2 ]] || { echo 'Missing value for --site-path.' >&2; exit 2; }
      site_path="$2"
      shift 2
      ;;
    --site-url)
      [[ $# -ge 2 ]] || { echo 'Missing value for --site-url.' >&2; exit 2; }
      site_url="${2%/}"
      shift 2
      ;;
    -h|--help)
      usage
      exit 0
      ;;
    *)
      echo "Unknown argument: $1" >&2
      usage >&2
      exit 2
      ;;
  esac
done

[[ -n "$site_path" && -n "$site_url" ]] || { usage >&2; exit 2; }
[[ "$site_path" = /* ]] || { echo '--site-path must be absolute.' >&2; exit 2; }
[[ "$site_url" =~ ^https?://[^/]+(/.*)?$ ]] || { echo '--site-url must be an HTTP(S) URL.' >&2; exit 2; }

findings=0
section() { printf '\n== %s ==\n' "$1"; }
finding() { printf 'ACTION: %s\n' "$1"; findings=$((findings + 1)); }

section 'WordPress root'
if [[ ! -d "$site_path" ]]; then
  echo "ERROR: WordPress root does not exist: $site_path" >&2
  exit 1
fi
for file in index.php wp-load.php wp-settings.php wp-includes/version.php; do
  if [[ -f "$site_path/$file" ]]; then
    echo "OK: $file"
  else
    finding "Missing required WordPress file: $file"
  fi
done

section 'PHP CLI'
if command -v php >/dev/null 2>&1; then
  php -r 'echo PHP_VERSION, PHP_EOL;' 2>/dev/null || finding 'PHP CLI exists but its version could not be read.'
else
  finding 'PHP CLI is unavailable; ask the host which PHP version serves this site.'
fi

section 'Apache/LiteSpeed rewrite file'
htaccess="$site_path/.htaccess"
if [[ ! -f "$htaccess" ]]; then
  finding '.htaccess is absent. Apache/LiteSpeed pretty permalinks require reviewed WordPress rewrite rules.'
else
  echo 'OK: .htaccess exists (contents are not printed).'
  if grep -Eq '^[[:space:]]*# BEGIN WordPress' "$htaccess" \
    && grep -Eq '^[[:space:]]*RewriteEngine[[:space:]]+On' "$htaccess" \
    && grep -Eq '^[[:space:]]*RewriteRule[[:space:]]+\.([[:space:]]|\*)' "$htaccess"; then
    echo 'OK: A standard WordPress rewrite block appears to be present.'
  else
    finding '.htaccess does not contain a recognizable complete WordPress rewrite block; review it without overwriting host rules.'
  fi
fi

wp_available=false
if command -v wp >/dev/null 2>&1; then
  wp_available=true
fi

section 'WP-CLI and WordPress settings'
page_slugs=()
hidden_login_slug=''
if [[ "$wp_available" == true ]]; then
  echo 'OK: WP-CLI is available.'
  permalink="$(wp --path="$site_path" option get permalink_structure 2>/dev/null || true)"
  home_value="$(wp --path="$site_path" option get home 2>/dev/null || true)"
  siteurl_value="$(wp --path="$site_path" option get siteurl 2>/dev/null || true)"
  printf 'Permalink structure: %s\n' "${permalink:-<empty>}"
  printf 'Home URL: %s\n' "${home_value:-<unavailable>}"
  printf 'Site URL: %s\n' "${siteurl_value:-<unavailable>}"
  [[ -n "$permalink" ]] || finding 'Permalink structure is empty; confirm this is intentional before expecting pretty URLs.'

  if wp --path="$site_path" plugin is-installed wps-hide-login >/dev/null 2>&1; then
    if wp --path="$site_path" plugin is-active wps-hide-login >/dev/null 2>&1; then
      echo 'WPS Hide Login: installed and active (configured slug intentionally hidden).'
      hidden_login_slug="$(wp --path="$site_path" option get whl_page 2>/dev/null || true)"
      [[ -n "$hidden_login_slug" ]] || finding 'WPS Hide Login is active but its configured login slug could not be read.'
    else
      echo 'WPS Hide Login: installed but inactive.'
    fi
  else
    echo 'WPS Hide Login: not installed.'
  fi

  mapfile -t page_slugs < <(wp --path="$site_path" post list --post_type=page --post_status=publish --field=post_name 2>/dev/null | sed '/^[[:space:]]*$/d' | head -n 20)
  if ((${#page_slugs[@]})); then
    echo 'Published page slugs:'
    printf '  %s\n' "${page_slugs[@]}"
  else
    finding 'No published page slugs were returned; verify database access and published pages.'
  fi
else
  finding 'WP-CLI is unavailable; permalink, database page, and WPS Hide Login checks require manual verification.'
fi

section 'Public HTTP checks'
http_code() {
  local label="$1" url="$2" code
  if ! command -v curl >/dev/null 2>&1; then
    finding "curl is unavailable; could not test $label."
    return
  fi
  # Feed the URL over stdin so a confidential WPS Hide Login slug is not
  # visible in curl's process arguments.
  code="$(printf 'url = "%s"\n' "$url" | curl --silent --show-error --max-time 15 --output /dev/null --write-out '%{http_code}' --config - 2>/dev/null || true)"
  printf '%s: %s\n' "$label" "${code:-request failed}"
  [[ "$code" =~ ^(2|3)[0-9][0-9]$ ]] || finding "$label did not return a 2xx/3xx response."
}

http_code 'Homepage' "$site_url/"
if ((${#page_slugs[@]})); then
  for slug in "${page_slugs[@]:0:3}"; do
    http_code "Published page /$slug/" "$site_url/$slug/"
  done
else
  for slug in programs-qualifications contact about-us; do
    http_code "Candidate page /$slug/" "$site_url/$slug/"
  done
fi
http_code 'Unauthenticated /wp-admin/ (404 may be intentional with WPS Hide Login)' "$site_url/wp-admin/"
if [[ -n "$hidden_login_slug" ]]; then
  http_code 'Configured WPS Hide Login endpoint (URL hidden)' "$site_url/$hidden_login_slug/"
fi

section 'Interpretation'
echo 'Apache/LiteSpeed needs mod_rewrite and permission to honor .htaccess; Nginx needs a server-level try_files rule.'
echo 'A published slug returning 404 can indicate missing rewrites or a database/page mismatch; this script does not alter either.'
if ((findings)); then
  printf 'Completed with %d actionable finding(s).\n' "$findings"
  exit 1
fi
echo 'No repository-observable routing findings were detected. Server logs and authenticated browser checks are still required.'
