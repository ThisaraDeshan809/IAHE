#!/usr/bin/env bash
set -euo pipefail

root="${1:-$(pwd)}"
failed=0
php_bin="${PHP_BIN:-php}"

required_files=(
  "wp-content/plugins/all-in-one-wp-migration/all-in-one-wp-migration.php"
  "wp-content/plugins/all-in-one-wp-migration/lib/vendor/bandar/bandar/lib/Bandar.php"
  "wp-content/plugins/all-in-one-wp-migration/lib/vendor/servmask/filesystem/class-ai1wm-file.php"
  "wp-content/plugins/all-in-one-wp-migration/lib/vendor/servmask/archiver/class-ai1wm-archiver.php"
  "wp-content/plugins/all-in-one-wp-migration/lib/vendor/servmask/database/class-ai1wm-database.php"
  "wp-content/plugins/wordfence/wordfence.php"
  "wp-content/plugins/wordfence/vendor/wordfence/wf-waf/src/init.php"
  "wp-content/plugins/wordfence/vendor/autoload.php"
  "wp-content/plugins/wordfence/crypto/vendor/paragonie/sodium_compat/autoload.php"
  "wp-content/plugins/wordpress-seo/wp-seo.php"
  "wp-content/plugins/wordpress-seo/vendor/autoload.php"
  "wp-content/plugins/wp-file-manager-pro/file_folder_manager_pro.php"
  "wp-content/plugins/wp-file-manager-pro/lib/php/autoload.php"
  "wp-content/plugins/wp-file-manager-pro/classes/updates.php"
  "wp-content/plugins/wp-file-manager-pro/classes/common-functions.php"
  "wp-content/plugins/wp-file-manager-pro/classes/files-restore.php"
  "wp-content/plugins/wp-file-manager-pro/classes/files-backup.php"
  "wp-content/plugins/wp-file-manager-pro/classes/db-restore.php"
  "wp-content/plugins/wp-file-manager-pro/classes/db-backup.php"
  "wp-content/plugins/health-check/health-check.php"
  "wp-content/plugins/health-check/build/health-check.asset.php"
  "wp-content/plugins/google-site-kit/google-site-kit.php"
  "wp-content/plugins/google-site-kit/includes/vendor/composer/autoload_classmap.php"
  "wp-content/plugins/google-site-kit/third-party/vendor/composer/autoload_classmap.php"
  "wp-content/plugins/google-site-kit/third-party/vendor/autoload_files.php"
  "wp-content/plugins/wps-hide-login/wps-hide-login.php"
  "wp-content/plugins/wps-hide-login/autoload.php"
)

for relative_path in "${required_files[@]}"; do
  if [[ ! -s "$root/$relative_path" ]]; then
    printf 'MISSING required runtime file: %s\n' "$relative_path" >&2
    failed=1
  fi
done

check_version() {
  local relative_path="$1" expected="$2"
  if [[ -s "$root/$relative_path" ]] && ! grep -Eq "Version:[[:space:]]*${expected}([[:space:]]|$)" "$root/$relative_path"; then
    printf 'VERSION mismatch: %s (expected %s)\n' "$relative_path" "$expected" >&2
    failed=1
  fi
}

check_version "wp-content/plugins/all-in-one-wp-migration/all-in-one-wp-migration.php" "7\\.95"
check_version "wp-content/plugins/wordfence/wordfence.php" "8\\.1\\.0"
check_version "wp-content/plugins/wordpress-seo/wp-seo.php" "25\\.3\\.1"
check_version "wp-content/plugins/wp-file-manager-pro/file_folder_manager_pro.php" "8\\.4\\.3"
check_version "wp-content/plugins/google-site-kit/google-site-kit.php" "1\\.155\\.0"

wp_file_manager_main="$root/wp-content/plugins/wp-file-manager-pro/file_folder_manager_pro.php"
if [[ -s "$wp_file_manager_main" ]]; then
  for class_file in updates common-functions; do
    if ! grep -Eq "require_once[[:space:]]+(FILEMANEGERPROPATH[[:space:]]*\\.[[:space:]]*['\"]classes/${class_file}\\.php['\"]|__DIR__[[:space:]]*\\.[[:space:]]*['\"]/classes/${class_file}\\.php['\"])" "$wp_file_manager_main"; then
      printf 'UNSAFE WP File Manager class load: classes/%s.php is not required from an absolute plugin path\n' "$class_file" >&2
      failed=1
    fi
  done
  if grep -Eq "(include|include_once|require|require_once)[[:space:]]*\\(?[[:space:]]*['\"](classes|controller|inc|lib)/" "$wp_file_manager_main"; then
    echo 'UNSAFE WP File Manager relative include remains in the plugin entry point.' >&2
    failed=1
  fi
fi

if [[ -s "$root/wp-content/plugins/wp-file-manager-pro/classes/updates.php" ]] &&
   ! grep -Eq 'class[[:space:]]+wp_file_manager_pro_updates([[:space:]]|\\{)' "$root/wp-content/plugins/wp-file-manager-pro/classes/updates.php"; then
  echo 'INVALID WP File Manager updates class.' >&2
  failed=1
fi

if ! command -v "$php_bin" >/dev/null 2>&1; then
  echo "PHP interpreter not found: $php_bin" >&2
  failed=1
else
  for relative_path in \
    "wp-content/plugins/google-site-kit/includes/vendor/composer/autoload_classmap.php" \
    "wp-content/plugins/google-site-kit/third-party/vendor/composer/autoload_classmap.php" \
    "wp-content/plugins/google-site-kit/third-party/vendor/autoload_files.php"; do
    if [[ -s "$root/$relative_path" ]] &&
       ! "$php_bin" -r '$value = include $argv[1]; exit(is_array($value) ? 0 : 1);' "$root/$relative_path"; then
      printf 'INVALID Site Kit generated array: %s\n' "$relative_path" >&2
      failed=1
    fi
  done
fi

if (( failed )); then
  echo 'Plugin integrity check failed; do not deploy or activate these packages.' >&2
  exit 1
fi

echo 'Plugin integrity check passed.'
