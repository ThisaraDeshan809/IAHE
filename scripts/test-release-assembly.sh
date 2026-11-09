#!/usr/bin/env bash
set -euo pipefail

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
test_root="$(mktemp -d)"
trap 'rm -rf "$test_root"' EXIT
archive_dir="$test_root/archives"
package_stage="$test_root/package-stage"
mkdir -p "$archive_dir"

make_archive() {
  local slug="$1" version="$2" main_file="$3"
  shift 3
  rm -rf "$package_stage"
  mkdir -p "$package_stage/$slug"
  printf '<?php\n/* Version: %s */\n' "$version" > "$package_stage/$slug/$main_file"
  for relative_path in "$@"; do
    mkdir -p "$package_stage/$slug/$(dirname "$relative_path")"
    printf '<?php /* fixture */\n' > "$package_stage/$slug/$relative_path"
  done
  (cd "$package_stage" && zip -qr "$archive_dir/${slug}.${version}.zip" "$slug")
}

make_archive all-in-one-wp-migration 7.95 all-in-one-wp-migration.php \
  lib/vendor/bandar/bandar/lib/Bandar.php \
  lib/vendor/servmask/filesystem/class-ai1wm-file.php \
  lib/vendor/servmask/archiver/class-ai1wm-archiver.php \
  lib/vendor/servmask/database/class-ai1wm-database.php
make_archive wordfence 8.1.0 wordfence.php \
  vendor/wordfence/wf-waf/src/init.php vendor/autoload.php \
  crypto/vendor/paragonie/sodium_compat/autoload.php
make_archive wordpress-seo 25.3.1 wp-seo.php vendor/autoload.php
make_archive google-site-kit 1.155.0 google-site-kit.php \
  includes/vendor/composer/autoload_classmap.php \
  third-party/vendor/composer/autoload_classmap.php \
  third-party/vendor/autoload_files.php

for relative_path in \
  includes/vendor/composer/autoload_classmap.php \
  third-party/vendor/composer/autoload_classmap.php \
  third-party/vendor/autoload_files.php; do
  unzip -q "$archive_dir/google-site-kit.1.155.0.zip" -d "$test_root/site-kit-edit"
  printf '<?php return array();\n' > "$test_root/site-kit-edit/google-site-kit/$relative_path"
  rm "$archive_dir/google-site-kit.1.155.0.zip"
  (cd "$test_root/site-kit-edit" && zip -qr "$archive_dir/google-site-kit.1.155.0.zip" google-site-kit)
  rm -rf "$test_root/site-kit-edit"
done

premium_stage="$test_root/premium-stage/wp-file-manager-pro"
mkdir -p "$premium_stage/lib/php" "$premium_stage/classes"
printf '%s\n' \
  '<?php' '/* Version: 8.4.3 */' \
  "require_once FILEMANEGERPROPATH . 'classes/updates.php';" \
  "require_once FILEMANEGERPROPATH . 'classes/common-functions.php';" \
  > "$premium_stage/file_folder_manager_pro.php"
printf '<?php /* fixture */\n' > "$premium_stage/lib/php/autoload.php"
for class_file in common-functions files-restore files-backup db-restore db-backup; do
  printf '<?php /* fixture */\n' > "$premium_stage/classes/$class_file.php"
done
printf '<?php class wp_file_manager_pro_updates {}\n' > "$premium_stage/classes/updates.php"
(cd "$test_root/premium-stage" && zip -qr "$test_root/wp-file-manager-pro.8.4.3.zip" wp-file-manager-pro)
premium_sha="$(sha256sum "$test_root/wp-file-manager-pro.8.4.3.zip" | awk '{print $1}')"

recovery_release="$test_root/recovery-release"
WORDPRESS_PLUGIN_ARCHIVE_DIR="$archive_dir" \
  "$repo_root/scripts/assemble-release.sh" --without-premium "$recovery_release"
test ! -e "$recovery_release/wp-content/plugins/wp-file-manager-pro"
grep -qx 'release_profile=without-premium' "$recovery_release/RELEASE-MANIFEST.txt"
PHP_BIN="${PHP_BIN:-php}" "$recovery_release/scripts/check-plugin-integrity.sh" \
  --profile without-premium "$recovery_release"
rm -rf "$recovery_release"

full_release="$test_root/full-release"
WORDPRESS_PLUGIN_ARCHIVE_DIR="$archive_dir" \
WP_FILE_MANAGER_PRO_ZIP="$test_root/wp-file-manager-pro.8.4.3.zip" \
WP_FILE_MANAGER_PRO_SHA256="$premium_sha" \
  "$repo_root/scripts/assemble-release.sh" "$full_release"
test -d "$full_release/wp-content/plugins/wp-file-manager-pro"
grep -qx 'release_profile=full' "$full_release/RELEASE-MANIFEST.txt"
PHP_BIN="${PHP_BIN:-php}" "$full_release/scripts/check-plugin-integrity.sh" --profile full "$full_release"
rm -rf "$full_release"

if WORDPRESS_PLUGIN_ARCHIVE_DIR="$archive_dir" \
  "$repo_root/scripts/assemble-release.sh" "$test_root/missing-premium" >/dev/null 2>&1; then
  echo 'Expected strict default mode to reject a missing premium package.' >&2
  exit 1
fi
test ! -e "$test_root/missing-premium"

corrupt_dir="$test_root/corrupt-archives"
cp -a "$archive_dir" "$corrupt_dir"
printf 'corrupt\n' > "$corrupt_dir/wordfence.8.1.0.zip"
if WORDPRESS_PLUGIN_ARCHIVE_DIR="$corrupt_dir" \
  "$repo_root/scripts/assemble-release.sh" --without-premium "$test_root/corrupt-release" >/dev/null 2>&1; then
  echo 'Expected a corrupt plugin ZIP to fail.' >&2
  exit 1
fi
test ! -e "$test_root/corrupt-release"

invalid_dir="$test_root/invalid-version-archives"
cp -a "$archive_dir" "$invalid_dir"
unzip -q "$invalid_dir/google-site-kit.1.155.0.zip" -d "$test_root/invalid-site-kit"
sed -i 's/Version: 1.155.0/Version: 0.0.0/' "$test_root/invalid-site-kit/google-site-kit/google-site-kit.php"
rm "$invalid_dir/google-site-kit.1.155.0.zip"
(cd "$test_root/invalid-site-kit" && zip -qr "$invalid_dir/google-site-kit.1.155.0.zip" google-site-kit)
if WORDPRESS_PLUGIN_ARCHIVE_DIR="$invalid_dir" \
  "$repo_root/scripts/assemble-release.sh" --without-premium "$test_root/invalid-version-release" >/dev/null 2>&1; then
  echo 'Expected an invalid plugin version to fail.' >&2
  exit 1
fi
test ! -e "$test_root/invalid-version-release"

incomplete_premium="$test_root/incomplete-premium.zip"
mkdir -p "$test_root/incomplete-premium/wp-file-manager-pro"
printf '<?php /* Version: 8.4.3 */\n' > "$test_root/incomplete-premium/wp-file-manager-pro/file_folder_manager_pro.php"
(cd "$test_root/incomplete-premium" && zip -qr "$incomplete_premium" wp-file-manager-pro)
incomplete_sha="$(sha256sum "$incomplete_premium" | awk '{print $1}')"
if WORDPRESS_PLUGIN_ARCHIVE_DIR="$archive_dir" \
  WP_FILE_MANAGER_PRO_ZIP="$incomplete_premium" WP_FILE_MANAGER_PRO_SHA256="$incomplete_sha" \
  "$repo_root/scripts/assemble-release.sh" "$test_root/incomplete-premium-release" >/dev/null 2>&1; then
  echo 'Expected an incomplete premium package to fail.' >&2
  exit 1
fi
test ! -e "$test_root/incomplete-premium-release"

echo 'Release assembly profile tests passed.'
