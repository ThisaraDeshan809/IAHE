#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
checker="$repo_root/scripts/check-plugin-integrity.sh"
fixture_parent="$(mktemp -d)"
trap 'rm -rf "$fixture_parent"' EXIT
full_fixture="$fixture_parent/full"

if PHP_BIN="${PHP_BIN:-php}" "$checker" --profile full "$repo_root" >/dev/null 2>&1; then
  echo 'Expected the source-only checkout to fail full-profile validation.' >&2
  exit 1
fi

while IFS= read -r relative_path; do
  mkdir -p "$full_fixture/$(dirname "$relative_path")"
  printf 'fixture\n' > "$full_fixture/$relative_path"
done < <(sed -n 's/^  "\(wp-content[^\"]*\)"$/\1/p' "$checker")

write_plugin_header() {
  local relative_path="$1" version="$2"
  printf '<?php\n/*\nVersion: %s\n*/\n' "$version" > "$full_fixture/$relative_path"
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
  > "$full_fixture/wp-content/plugins/wp-file-manager-pro/file_folder_manager_pro.php"
printf '<?php class wp_file_manager_pro_updates {}\n' \
  > "$full_fixture/wp-content/plugins/wp-file-manager-pro/classes/updates.php"

for relative_path in \
  wp-content/plugins/google-site-kit/includes/vendor/composer/autoload_classmap.php \
  wp-content/plugins/google-site-kit/third-party/vendor/composer/autoload_classmap.php \
  wp-content/plugins/google-site-kit/third-party/vendor/autoload_files.php; do
  printf '<?php return array();\n' > "$full_fixture/$relative_path"
done

hash="aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"
cat > "$full_fixture/RELEASE-MANIFEST.txt" <<EOF
manifest_version=1
release_profile=full
source_commit=aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
plugin.all-in-one-wp-migration.version=7.95
plugin.all-in-one-wp-migration.sha256=$hash
plugin.wordfence.version=8.1.0
plugin.wordfence.sha256=$hash
plugin.wordpress-seo.version=25.3.1
plugin.wordpress-seo.sha256=$hash
plugin.google-site-kit.version=1.155.0
plugin.google-site-kit.sha256=$hash
plugin.wp-file-manager-pro.status=included
plugin.wp-file-manager-pro.version=8.4.3
plugin.wp-file-manager-pro.sha256=$hash
EOF

PHP_BIN="${PHP_BIN:-php}" "$checker" --profile full "$full_fixture"

missing_map="$full_fixture/wp-content/plugins/google-site-kit/third-party/vendor/composer/autoload_classmap.php"
mv "$missing_map" "$missing_map.saved"
if PHP_BIN="${PHP_BIN:-php}" "$checker" --profile full "$full_fixture" >/dev/null 2>&1; then
  echo 'Expected a missing Site Kit class map to fail.' >&2
  exit 1
fi
mv "$missing_map.saved" "$missing_map"

printf '<?php return true;\n' > "$missing_map"
if PHP_BIN="${PHP_BIN:-php}" "$checker" --profile full "$full_fixture" >/dev/null 2>&1; then
  echo 'Expected a non-array Site Kit class map to fail.' >&2
  exit 1
fi
printf '<?php return array();\n' > "$missing_map"

premium_class="$full_fixture/wp-content/plugins/wp-file-manager-pro/classes/updates.php"
mv "$premium_class" "$premium_class.saved"
if PHP_BIN="${PHP_BIN:-php}" "$checker" --profile full "$full_fixture" >/dev/null 2>&1; then
  echo 'Expected an incomplete premium plugin to fail full validation.' >&2
  exit 1
fi
mv "$premium_class.saved" "$premium_class"

recovery_fixture="$fixture_parent/recovery"
cp -a "$full_fixture" "$recovery_fixture"
rm -rf "$recovery_fixture/wp-content/plugins/wp-file-manager-pro"
sed -i 's/release_profile=full/release_profile=without-premium/' "$recovery_fixture/RELEASE-MANIFEST.txt"
sed -i '/^plugin\.wp-file-manager-pro\./d' "$recovery_fixture/RELEASE-MANIFEST.txt"
echo 'plugin.wp-file-manager-pro.status=excluded' >> "$recovery_fixture/RELEASE-MANIFEST.txt"
PHP_BIN="${PHP_BIN:-php}" "$checker" --profile without-premium "$recovery_fixture"

mkdir -p "$recovery_fixture/wp-content/plugins/wp-file-manager-pro"
if PHP_BIN="${PHP_BIN:-php}" "$checker" --profile without-premium "$recovery_fixture" >/dev/null 2>&1; then
  echo 'Expected recovery profile to reject a shipped premium directory.' >&2
  exit 1
fi
rm -rf "$recovery_fixture/wp-content/plugins/wp-file-manager-pro"

sed -i 's/plugin.google-site-kit.version=1.155.0/plugin.google-site-kit.version=0.0.0/' "$recovery_fixture/RELEASE-MANIFEST.txt"
if PHP_BIN="${PHP_BIN:-php}" "$checker" --profile without-premium "$recovery_fixture" >/dev/null 2>&1; then
  echo 'Expected an invalid manifest version to fail.' >&2
  exit 1
fi

echo 'Release integrity profile tests passed.'
