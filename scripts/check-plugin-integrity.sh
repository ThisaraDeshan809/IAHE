#!/usr/bin/env bash
set -euo pipefail

root="${1:-$(pwd)}"
failed=0

required_files=(
  "wp-content/plugins/all-in-one-wp-migration/lib/vendor/bandar/bandar/lib/Bandar.php"
  "wp-content/plugins/all-in-one-wp-migration/lib/vendor/servmask/filesystem/class-ai1wm-file.php"
  "wp-content/plugins/all-in-one-wp-migration/lib/vendor/servmask/archiver/class-ai1wm-archiver.php"
  "wp-content/plugins/all-in-one-wp-migration/lib/vendor/servmask/database/class-ai1wm-database.php"
  "wp-content/plugins/wordfence/vendor/wordfence/wf-waf/src/init.php"
  "wp-content/plugins/wordfence/vendor/autoload.php"
  "wp-content/plugins/wordfence/crypto/vendor/paragonie/sodium_compat/autoload.php"
  "wp-content/plugins/wordpress-seo/vendor/autoload.php"
  "wp-content/plugins/wp-file-manager-pro/lib/php/autoload.php"
  "wp-content/plugins/health-check/build/health-check.asset.php"
  "wp-content/plugins/google-site-kit/includes/vendor/composer/autoload_classmap.php"
  "wp-content/plugins/google-site-kit/third-party/vendor/autoload_files.php"
  "wp-content/plugins/wps-hide-login/autoload.php"
)

for relative_path in "${required_files[@]}"; do
  if [[ ! -s "$root/$relative_path" ]]; then
    printf 'MISSING required runtime file: %s\n' "$relative_path" >&2
    failed=1
  fi
done

if (( failed )); then
  echo 'Plugin integrity check failed; do not deploy or activate these packages.' >&2
  exit 1
fi

echo 'Plugin integrity check passed.'
