#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
checker="$repo_root/scripts/check-plugin-integrity.sh"
fixture="$(mktemp -d)"
trap 'rm -rf "$fixture"' EXIT

if PHP_BIN="${PHP_BIN:-php}" "$checker" "$repo_root" >/dev/null 2>&1; then
  echo 'Expected the source-only checkout to fail integrity validation.' >&2
  exit 1
fi

while IFS= read -r relative_path; do
  mkdir -p "$fixture/$(dirname "$relative_path")"
  printf 'fixture\n' > "$fixture/$relative_path"
done < <(sed -n 's/^  "\(wp-content[^\"]*\)"$/\1/p' "$checker")

write_plugin_header() {
  local relative_path="$1" version="$2"
  printf '<?php\n/*\nVersion: %s\n*/\n' "$version" > "$fixture/$relative_path"
}

write_plugin_header wp-content/plugins/all-in-one-wp-migration/all-in-one-wp-migration.php 7.95
write_plugin_header wp-content/plugins/wordfence/wordfence.php 8.1.0
write_plugin_header wp-content/plugins/wordpress-seo/wp-seo.php 25.3.1
write_plugin_header wp-content/plugins/google-site-kit/google-site-kit.php 1.155.0

printf '%s\n' \
  '<?php' \
  '/* Version: 8.4.3 */' \
  "require_once FILEMANEGERPROPATH . 'classes/updates.php';" \
  "require_once FILEMANEGERPROPATH . 'classes/common-functions.php';" \
  > "$fixture/wp-content/plugins/wp-file-manager-pro/file_folder_manager_pro.php"
printf '<?php class wp_file_manager_pro_updates {}\n' \
  > "$fixture/wp-content/plugins/wp-file-manager-pro/classes/updates.php"

for relative_path in \
  wp-content/plugins/google-site-kit/includes/vendor/composer/autoload_classmap.php \
  wp-content/plugins/google-site-kit/third-party/vendor/composer/autoload_classmap.php \
  wp-content/plugins/google-site-kit/third-party/vendor/autoload_files.php; do
  printf '<?php return array();\n' > "$fixture/$relative_path"
done

PHP_BIN="${PHP_BIN:-php}" "$checker" "$fixture"

site_kit_map="$fixture/wp-content/plugins/google-site-kit/third-party/vendor/composer/autoload_classmap.php"
rm "$site_kit_map"
if PHP_BIN="${PHP_BIN:-php}" "$checker" "$fixture" >/dev/null 2>&1; then
  echo 'Expected a missing Site Kit class map to fail integrity validation.' >&2
  exit 1
fi
printf '<?php return array();\n' > "$site_kit_map"

printf '<?php return true;\n' > "$site_kit_map"
if PHP_BIN="${PHP_BIN:-php}" "$checker" "$fixture" >/dev/null 2>&1; then
  echo 'Expected a non-array Site Kit class map to fail integrity validation.' >&2
  exit 1
fi
printf '<?php return array();\n' > "$site_kit_map"

rm "$fixture/wp-content/plugins/wp-file-manager-pro/classes/updates.php"
if PHP_BIN="${PHP_BIN:-php}" "$checker" "$fixture" >/dev/null 2>&1; then
  echo 'Expected a missing WP File Manager Pro class to fail integrity validation.' >&2
  exit 1
fi

echo 'Release integrity regression tests passed.'
