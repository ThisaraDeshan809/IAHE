#!/usr/bin/env bash
set -euo pipefail

if (( $# != 1 )); then
  echo "Usage: WP_FILE_MANAGER_PRO_ZIP=/secure/path/plugin.zip $0 /absolute/release-dir" >&2
  exit 64
fi

repo_root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
release_dir="$1"
premium_zip="${WP_FILE_MANAGER_PRO_ZIP:-}"

[[ "$release_dir" = /* ]] || { echo 'Release directory must be absolute.' >&2; exit 64; }
[[ ! -e "$release_dir" ]] || { echo "Refusing to overwrite $release_dir" >&2; exit 73; }
[[ -f "$premium_zip" ]] || { echo 'WP_FILE_MANAGER_PRO_ZIP must name the licensed 8.4.3 distribution.' >&2; exit 66; }
command -v curl >/dev/null
command -v unzip >/dev/null

mkdir -p "$release_dir"
git -C "$repo_root" archive HEAD | tar -x -C "$release_dir"
temp_dir="$(mktemp -d)"
trap 'rm -rf "$temp_dir"' EXIT

install_official_plugin() {
  local slug="$1" version="$2" zip="$temp_dir/$slug.zip"
  curl --fail --location --silent --show-error \
    "https://downloads.wordpress.org/plugin/${slug}.${version}.zip" -o "$zip"
  unzip -q "$zip" -d "$temp_dir/unpacked-$slug"
  rm -rf "$release_dir/wp-content/plugins/$slug"
  mv "$temp_dir/unpacked-$slug/$slug" "$release_dir/wp-content/plugins/$slug"
}

install_official_plugin all-in-one-wp-migration 7.95
install_official_plugin wordfence 8.1.0
install_official_plugin wordpress-seo 25.3.1
install_official_plugin google-site-kit 1.155.0

unzip -q "$premium_zip" -d "$temp_dir/premium"
premium_dir="$(find "$temp_dir/premium" -type f -name file_folder_manager_pro.php -printf '%h\n' -quit)"
[[ -n "$premium_dir" ]] || { echo 'Licensed ZIP does not contain WP File Manager Pro.' >&2; exit 65; }
grep -q 'Version:[[:space:]]*8\.4\.3' "$premium_dir/file_folder_manager_pro.php" || {
  echo 'Licensed ZIP is not WP File Manager Pro 8.4.3.' >&2
  exit 65
}
rm -rf "$release_dir/wp-content/plugins/wp-file-manager-pro"
mv "$premium_dir" "$release_dir/wp-content/plugins/wp-file-manager-pro"

"$release_dir/scripts/check-plugin-integrity.sh" "$release_dir"
echo "Release assembled at $release_dir"
