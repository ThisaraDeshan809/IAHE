#!/usr/bin/env bash
set -euo pipefail

if (( $# != 1 )); then
  echo "Usage: WP_FILE_MANAGER_PRO_ZIP=/secure/path/plugin.zip $0 /absolute/release-dir" >&2
  exit 64
fi

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
release_dir="$1"
premium_zip="${WP_FILE_MANAGER_PRO_ZIP:-}"
premium_sha256="${WP_FILE_MANAGER_PRO_SHA256:-}"

[[ "$release_dir" = /* ]] || { echo 'Release directory must be absolute.' >&2; exit 64; }
[[ ! -e "$release_dir" ]] || { echo "Refusing to overwrite $release_dir" >&2; exit 73; }
[[ -f "$premium_zip" ]] || { echo 'WP_FILE_MANAGER_PRO_ZIP must name the licensed 8.4.3 distribution.' >&2; exit 66; }
[[ "$premium_sha256" =~ ^[0-9a-fA-F]{64}$ ]] || { echo 'WP_FILE_MANAGER_PRO_SHA256 must be the approved package SHA-256.' >&2; exit 65; }
command -v curl >/dev/null
command -v unzip >/dev/null
command -v sha256sum >/dev/null
command -v php >/dev/null

temp_dir="$(mktemp -d)"
trap 'rm -rf "$temp_dir"' EXIT
build_dir="$temp_dir/release"
checksum_manifest="$build_dir/PLUGIN-PACKAGE-SHA256SUMS.txt"

actual_premium_sha256="$(sha256sum "$premium_zip" | awk '{print $1}')"
[[ "$actual_premium_sha256" == "${premium_sha256,,}" ]] || {
  echo 'Licensed WP File Manager Pro package checksum does not match the approved SHA-256.' >&2
  exit 65
}
unzip -tqq "$premium_zip"
unzip -q "$premium_zip" -d "$temp_dir/premium"
premium_dir="$(find "$temp_dir/premium" -type f -name file_folder_manager_pro.php -printf '%h\n' -quit)"
[[ -n "$premium_dir" ]] || { echo 'Licensed ZIP does not contain WP File Manager Pro.' >&2; exit 65; }
grep -q 'Version:[[:space:]]*8\.4\.3' "$premium_dir/file_folder_manager_pro.php" || {
  echo 'Licensed ZIP is not WP File Manager Pro 8.4.3.' >&2
  exit 65
}
mkdir -p "$build_dir"
git -C "$repo_root" archive HEAD | tar -x -C "$build_dir"

install_official_plugin() {
  local slug="$1" version="$2" main_file="$3" zip="$temp_dir/$slug.zip" package_sha256
  curl --fail --location --silent --show-error \
    "https://downloads.wordpress.org/plugin/${slug}.${version}.zip" -o "$zip"
  unzip -tqq "$zip"
  unzip -q "$zip" -d "$temp_dir/unpacked-$slug"
  [[ -f "$temp_dir/unpacked-$slug/$slug/$main_file" ]] || {
    echo "Official $slug archive has an unexpected directory structure." >&2
    exit 65
  }
  grep -Eq "Version:[[:space:]]*${version//./\\.}([[:space:]]|$)" "$temp_dir/unpacked-$slug/$slug/$main_file" || {
    echo "Official $slug archive does not contain expected version $version." >&2
    exit 65
  }
  package_sha256="$(sha256sum "$zip" | awk '{print $1}')"
  printf '%s  %s.%s.zip\n' "$package_sha256" "$slug" "$version" >> "$checksum_manifest"
  rm -rf "$build_dir/wp-content/plugins/$slug"
  mv "$temp_dir/unpacked-$slug/$slug" "$build_dir/wp-content/plugins/$slug"
}

install_official_plugin all-in-one-wp-migration 7.95 all-in-one-wp-migration.php
install_official_plugin wordfence 8.1.0 wordfence.php
install_official_plugin wordpress-seo 25.3.1 wp-seo.php
install_official_plugin google-site-kit 1.155.0 google-site-kit.php

printf '%s  wp-file-manager-pro.8.4.3.zip\n' "$actual_premium_sha256" >> "$checksum_manifest"
rm -rf "$build_dir/wp-content/plugins/wp-file-manager-pro"
mv "$premium_dir" "$build_dir/wp-content/plugins/wp-file-manager-pro"

"$build_dir/scripts/check-plugin-integrity.sh" "$build_dir"
mv "$build_dir" "$release_dir"
echo "Release assembled at $release_dir"
