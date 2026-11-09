#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
fixture="$(mktemp -d)"
trap 'rm -rf "$fixture"' EXIT
site="$fixture/site"
bin="$fixture/bin"
mkdir -p "$site/wp-includes" "$bin"
touch "$site/index.php" "$site/wp-load.php" "$site/wp-settings.php" "$site/wp-includes/version.php"
cat > "$site/.htaccess" <<'EOF'
# BEGIN WordPress
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
# END WordPress
EOF

cat > "$bin/wp" <<'EOF'
#!/usr/bin/env bash
case "$*" in
  *'option get permalink_structure'*) echo '/%postname%/' ;;
  *'option get home'*) echo 'https://example.test/subsite' ;;
  *'option get siteurl'*) echo 'https://example.test/subsite' ;;
  *'plugin is-installed wps-hide-login'*) exit 0 ;;
  *'plugin is-active wps-hide-login'*) exit 0 ;;
  *'option get whl_page'*) echo 'private-login-fixture' ;;
  *'post list'*) printf 'about-us\ncontact\nprograms-qualifications\n' ;;
  *) exit 1 ;;
esac
EOF
cat > "$bin/curl" <<'EOF'
#!/usr/bin/env bash
printf '200'
EOF
cat > "$bin/php" <<'EOF'
#!/usr/bin/env bash
printf '8.3.0\n'
EOF
chmod +x "$bin/wp" "$bin/curl" "$bin/php"

output="$(PATH="$bin:/usr/bin:/bin" "$repo_root/scripts/diagnose-wordpress-routing.sh" \
  --site-path "$site" --site-url 'https://example.test/subsite')"
grep -Fq 'No repository-observable routing findings were detected.' <<< "$output"
grep -Fq 'WPS Hide Login: installed and active (configured slug intentionally hidden).' <<< "$output"
if grep -Fq 'private-login-fixture' <<< "$output"; then
  echo 'Diagnostic output exposed the configured login slug.' >&2
  exit 1
fi

rm "$site/.htaccess"
if PATH="$bin:/usr/bin:/bin" "$repo_root/scripts/diagnose-wordpress-routing.sh" \
  --site-path "$site" --site-url 'https://example.test/subsite' > "$fixture/missing.out"; then
  echo 'Expected a missing .htaccess to produce an actionable result.' >&2
  exit 1
fi
grep -Fq 'ACTION: .htaccess is absent.' "$fixture/missing.out"

echo 'Routing diagnostic fixture tests passed.'
