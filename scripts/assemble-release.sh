#!/usr/bin/env bash
set -euo pipefail

usage() {
  echo "Usage: WP_FILE_MANAGER_PRO_ZIP=/secure/plugin.zip WP_FILE_MANAGER_PRO_SHA256=<sha256> $0 /absolute/release-dir" >&2
  echo "   or: $0 --without-premium /absolute/recovery-release-dir" >&2
}

profile="full"
if (( $# == 1 )); then
  release_dir="$1"
elif (( $# == 2 )) && [[ "$1" == "--without-premium" ]]; then
  profile="without-premium"
  release_dir="$2"
else
  usage
  exit 64
fi

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
premium_zip="${WP_FILE_MANAGER_PRO_ZIP:-}"
premium_sha256="${WP_FILE_MANAGER_PRO_SHA256:-}"
package_dir="${WORDPRESS_PLUGIN_ARCHIVE_DIR:-}"

[[ "$release_dir" = /* ]] || { echo 'Release directory must be absolute.' >&2; exit 64; }
[[ ! -e "$release_dir" ]] || { echo "Refusing to overwrite $release_dir" >&2; exit 73; }
command -v unzip >/dev/null
command -v sha256sum >/dev/null
command -v php >/dev/null

if [[ -z "$package_dir" ]]; then
  command -v curl >/dev/null
else
  [[ -d "$package_dir" ]] || { echo 'WORDPRESS_PLUGIN_ARCHIVE_DIR must be a directory.' >&2; exit 66; }
fi

temp_dir="$(mktemp -d)"
trap 'rm -rf "$temp_dir"' EXIT
build_dir="$temp_dir/release"
manifest="$build_dir/RELEASE-MANIFEST.txt"
premium_dir=""
actual_premium_sha256=""

if [[ "$profile" == "full" ]]; then
  [[ -f "$premium_zip" ]] || { echo 'Full profile requires WP_FILE_MANAGER_PRO_ZIP for licensed version 8.4.3.' >&2; exit 66; }
  [[ "$premium_sha256" =~ ^[0-9a-fA-F]{64}$ ]] || { echo 'Full profile requires the approved WP_FILE_MANAGER_PRO_SHA256.' >&2; exit 65; }
  actual_premium_sha256="$(sha256sum "$premium_zip" | awk '{print $1}')"
  [[ "$actual_premium_sha256" == "${premium_sha256,,}" ]] || {
    echo 'Licensed WP File Manager Pro package checksum does not match the approved SHA-256.' >&2
    exit 65
  }
  unzip -tqq "$premium_zip"
  unzip -q "$premium_zip" -d "$temp_dir/premium"
  premium_dir="$(find "$temp_dir/premium" -type f -name file_folder_manager_pro.php -printf '%h\n' -quit)"
  [[ -n "$premium_dir" ]] || { echo 'Licensed ZIP does not contain WP File Manager Pro.' >&2; exit 65; }
  grep -Eq 'Version:[[:space:]]*8\.4\.3([[:space:]]|$)' "$premium_dir/file_folder_manager_pro.php" || {
    echo 'Licensed ZIP is not WP File Manager Pro 8.4.3.' >&2
    exit 65
  }
fi

mkdir -p "$build_dir"
git -C "$repo_root" archive HEAD | tar -x -C "$build_dir"
{
  echo 'manifest_version=1'
  printf 'release_profile=%s\n' "$profile"
  printf 'source_commit=%s\n' "$(git -C "$repo_root" rev-parse HEAD)"
} > "$manifest"

install_official_plugin() {
  local slug="$1" version="$2" main_file="$3" package_sha256 zip
  zip="$temp_dir/$slug.zip"
  if [[ -n "$package_dir" ]]; then
    cp "$package_dir/${slug}.${version}.zip" "$zip"
  else
    curl --fail --location --silent --show-error \
      "https://downloads.wordpress.org/plugin/${slug}.${version}.zip" -o "$zip"
  fi
  unzip -tqq "$zip"
  unzip -q "$zip" -d "$temp_dir/unpacked-$slug"
  [[ -f "$temp_dir/unpacked-$slug/$slug/$main_file" ]] || {
    echo "Official $slug archive has an unexpected directory structure." >&2
    exit 65
  }
  grep -Eq "Version:[[:space:]]*${version//./\\.}([[:space:]]|$)" "$temp_dir/unpacked-$slug/$slug/$main_file" || {
    echo "$slug archive does not contain expected version $version." >&2
    exit 65
  }
  package_sha256="$(sha256sum "$zip" | awk '{print $1}')"
  {
    printf 'plugin.%s.version=%s\n' "$slug" "$version"
    printf 'plugin.%s.sha256=%s\n' "$slug" "$package_sha256"
  } >> "$manifest"
  rm -rf "$build_dir/wp-content/plugins/$slug"
  mv "$temp_dir/unpacked-$slug/$slug" "$build_dir/wp-content/plugins/$slug"
}

install_official_plugin all-in-one-wp-migration 7.95 all-in-one-wp-migration.php
install_official_plugin wordfence 8.1.0 wordfence.php
install_official_plugin wordpress-seo 25.3.1 wp-seo.php
install_official_plugin google-site-kit 1.155.0 google-site-kit.php

rm -rf "$build_dir/wp-content/plugins/wp-file-manager-pro"
if [[ "$profile" == "full" ]]; then
  mv "$premium_dir" "$build_dir/wp-content/plugins/wp-file-manager-pro"
  {
    echo 'plugin.wp-file-manager-pro.status=included'
    echo 'plugin.wp-file-manager-pro.version=8.4.3'
    printf 'plugin.wp-file-manager-pro.sha256=%s\n' "$actual_premium_sha256"
  } >> "$manifest"
else
  echo 'plugin.wp-file-manager-pro.status=excluded' >> "$manifest"
fi

"$build_dir/scripts/check-plugin-integrity.sh" --profile "$profile" "$build_dir"
mv "$build_dir" "$release_dir"
echo "Release assembled at $release_dir (profile: $profile)"
