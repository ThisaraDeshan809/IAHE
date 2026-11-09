# Shared-hosting deployment and rollback

## Diagnose routing before changing files

The repository cannot enable Apache modules, change `AllowOverride`, edit an
Nginx virtual host, flush production permalinks, or prove that a page exists in
the production database. Run the read-only helper first; it deliberately does
not print the WPS Hide Login slug:

```bash
site=/home/ACCOUNT/public_html
release=/home/ACCOUNT/releases/iahe-release
"$release/scripts/diagnose-wordpress-routing.sh" \
  --site-path "$site" --site-url 'https://iahsaward.org.uk'
```

Interpret the results separately:

- A homepage that works while every published pretty URL returns 404 usually
  indicates missing web-server rewrites.
- One missing slug while other pretty URLs work usually indicates a missing,
  draft, trashed, or differently named database page. Do not create a fake page
  or redirect it to the homepage.
- An unauthenticated `/wp-admin/` 404 can be intentional when WPS Hide Login is
  active. Test the confidential configured login URL through the approved
  password manager, then confirm the authenticated dashboard loads.
- A page that returns 200 is not necessarily healthy. Inspect its title/body,
  assets, redirects, and private PHP error log.

List the database-defined routes without changing them:

```bash
wp --path="$site" option get home
wp --path="$site" option get siteurl
wp --path="$site" option get permalink_structure
wp --path="$site" post list --post_type=page --post_status=publish \
  --fields=ID,post_name,post_title,post_status --format=table
wp --path="$site" menu list
wp --path="$site" menu item list primary-menu --fields=db_id,title,url --format=table
```

The last command requires the real menu slug from `wp menu list`. Divi header,
mobile, and footer menu assignments are database settings; verify each rendered
menu rather than assuming a template file controls it.

### Apache or LiteSpeed root installation

`.htaccess` is intentionally ignored by Git and must be preserved during
deployment. Back it up, inspect existing security/redirect/WAF directives, and
add or repair only the WordPress-managed block. A standard root-installation
block is:

```apache
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
```

Do not replace the whole file. Apache needs `mod_rewrite` and an applicable
`AllowOverride FileInfo` (or `AllowOverride All`) setting. On shared hosting,
the provider must enable those server settings if they are unavailable in the
control panel. LiteSpeed must also be configured to honor `.htaccess`.

After backing up the existing file, WordPress can regenerate only its managed
rewrite block without changing database content:

```bash
cp -p "$site/.htaccess" "$backup/htaccess.before-permalink-flush"
wp --path="$site" rewrite structure "$(wp --path="$site" option get permalink_structure)" --hard
wp --path="$site" rewrite flush --hard
```

Stop if the permalink structure is empty or `--hard` reports that rewrite rules
could not be written. Review the `.htaccess` diff and restore the backup if any
hosting security or redirect rule was lost.

### Nginx

Nginx does not read `.htaccess`. The hosting provider must put an equivalent
rule in the site server block, adjusted if WordPress is installed below `/`:

```nginx
location / {
    try_files $uri $uri/ /index.php?$args;
}
```

Shared-hosting users normally cannot reload Nginx without provider support. Do
not claim this change is deployed until the provider has applied it and known
published URLs have been retested.

## WPS Hide Login access and recovery

Do not hardcode, commit, log, or include the custom login slug in tickets. The
plugin filters WordPress-generated `wp_login_url()` and password-reset URLs;
theme code must continue using WordPress URL APIs rather than `wp-login.php`.
With pretty permalinks enabled, rewrite routing must work for the confidential
login endpoint too. Verify login, logout, password reset, and an authenticated
`/wp-admin/` visit during staging and production validation.

If the confidential URL is lost or routing prevents all administrator access,
take a database backup first and use WP-CLI to temporarily deactivate the plugin:

```bash
site=/home/ACCOUNT/public_html
recovery=/home/ACCOUNT/private-backups/login-recovery-$(date +%Y%m%d-%H%M%S)
mkdir -p "$recovery"
wp --path="$site" db export "$recovery/database.sql"
wp --path="$site" plugin status wps-hide-login
wp --path="$site" plugin deactivate wps-hide-login
wp --path="$site" rewrite flush --hard
```

Use the normal WordPress login only during the controlled recovery window. Fix
the rewrite or configuration problem, reactivate the plugin, verify its existing
setting in the WordPress UI, and test the confidential URL. Deactivation does not
erase the configured slug; never run the plugin uninstaller. This procedure does
not bypass authentication—valid administrator credentials are still required.

## Select and build a release profile

This repository must not be uploaded directly. The default `full` profile remains
strict: obtain the licensed WP File Manager Pro 8.4.3 ZIP from the account owner,
keep it outside Git, record its approved SHA-256, and run:

```bash
WP_FILE_MANAGER_PRO_ZIP=/secure/wp-file-manager-pro-8.4.3.zip \
WP_FILE_MANAGER_PRO_SHA256=approved_64_character_sha256 \
  scripts/assemble-release.sh /absolute/path/iahe-release
```

If WP File Manager Pro is intentionally disabled and no approved package is
available, build the explicit recovery profile:

```bash
scripts/assemble-release.sh --without-premium /absolute/path/iahe-recovery-release
```

The recovery artifact contains no `wp-content/plugins/wp-file-manager-pro`
directory. It does not deactivate the plugin in WordPress. An administrator must
deactivate it before deployment and confirm it is absent from the active plugin
list. Never use recovery mode as an implicit fallback for a failed full build.

The assembler downloads version-pinned official WordPress.org packages for
All-in-One WP Migration, Wordfence, Yoast, and Site Kit; replaces the incomplete source
directories, verifies archive structure and declared versions, records the
profile, source commit, pinned versions, premium status, and package hashes in
`RELEASE-MANIFEST.txt`, and runs `scripts/check-plugin-integrity.sh` with the
matching profile. The checker executes all three generated
Site Kit maps to require array results and verifies WP File Manager Pro's update,
backup, and restore class files plus safe absolute update-class loading. A bad
ZIP, hash mismatch, unsafe premium package, version mismatch, or missing runtime
file stops the build. Assembly stays in a temporary directory, and the requested
release path appears only after validation succeeds. Re-run the integrity check immediately before uploading.
Do not commit the assembled plugin directories or premium archive.

For an approved offline cache of the same pinned WordPress.org archives, set
`WORDPRESS_PLUGIN_ARCHIVE_DIR=/secure/official-plugin-zips`. Each archive must be
named `<slug>.<version>.zip`; its actual SHA-256 is recorded in the manifest.

## Staging and production procedure

1. Put the site in a provider-approved maintenance window. Back up the database
   and all files, including hidden `.user.ini`, `.htaccess`, `wordfence-waf.php`,
   `wp-config.php`, uploads, migration backups, and Wordfence logs. Verify the
   backup can be listed/extracted and record its timestamp.
2. Restore the backup to an access-controlled staging site. Do not change or
   reinstall the production database.
3. Record `php -v`, `wp core version`, `wp db check`, active plugins, theme, and
   the Wordfence `auto_prepend_file` path. Target PHP 8.1/8.2; never use <7.4.
4. Run the integrity check against the assembled release. Keep WP File Manager
   Pro disabled unless the licensed package and administrator-only access are
   verified.
   If the package still contains relative `classes/updates.php` or
   `classes/common-functions.php` includes, request a corrected vendor package;
   do not bypass the release gate.
   For a recovery release, run
   `scripts/check-plugin-integrity.sh --profile without-premium /path/to/release`
   and verify `wp plugin is-active wp-file-manager-pro` returns nonzero before
   copying files. For a full release, use `--profile full`.
5. Upload changed application files atomically. Exclude `wp-config.php`,
   `wp-content/uploads`, backups, caches, logs, `.user.ini`, and WAF bootstrap
   unless a reviewed Wordfence path change is intentional. Also exclude and
   preserve `.htaccess`; routing changes require a separate reviewed operation.
6. On staging, activate one repaired plugin at a time and inspect PHP logs after
   each: Yoast, All-in-One WP Migration, Wordfence, then WP File Manager Pro.
   Do not run a migration import against valued data.
7. Verify the homepage and at least three slugs confirmed as published by
   WP-CLI. Inspect actual page content, desktop/mobile header and footer links,
   CSS/JS/images, expected redirects, contact form, confidential WPS Hide Login
   URL, password reset, and the authenticated dashboard. Also verify Yoast with
   the plugin enabled and disabled, Wordfence firewall status, and file-manager
   administrator authorization/AJAX/upload restrictions. Review the private PHP
   log for new warnings, parse errors, or fatal errors; HTTP 200 alone is not a
   pass.
8. Confirm production settings hide errors: `WP_DEBUG`, `WP_DEBUG_DISPLAY`, and
   `WP_DEBUG_LOG` should normally be false. Use a private server log only during
   the maintenance window. Check files are typically 0644 and directories 0755,
   adjusted only for provider requirements.
9. Deploy the staging-approved release during the window. Clear LiteSpeed/other
   caches and PHP OPcache through supported controls. Repeat integrity, frontend,
   login, dashboard, and log checks.

## Recovery profile deployment over SSH (no sudo)

Set explicit account paths and stop on any failed command:

```bash
set -euo pipefail
site=/home/ACCOUNT/public_html
release=/home/ACCOUNT/releases/iahe-recovery-release
backup=/home/ACCOUNT/private-backups/iahe-before-recovery

"$release/scripts/check-plugin-integrity.sh" --profile without-premium "$release"
wp --path="$site" plugin deactivate wp-file-manager-pro
if wp --path="$site" plugin is-active wp-file-manager-pro; then
  echo 'WP File Manager Pro is still active; stop deployment.' >&2
  exit 1
fi

mkdir -p "$backup"
wp --path="$site" db export "$backup/database.sql"
tar -C "$site" -czf "$backup/site-files.tar.gz" .

rsync -a --dry-run --itemize-changes \
  --exclude='wp-config.php' --exclude='.htaccess' --exclude='.user.ini' \
  --exclude='wordfence-waf.php' \
  --exclude='wp-content/uploads/' --exclude='wp-content/ai1wm-backups/' \
  --exclude='wp-content/wflogs/' "$release/" "$site/"
```

After reviewing the dry run, repeat the same `rsync` without `--dry-run`. Remove
the old `wp-content/plugins/wp-file-manager-pro` directory only through a
recoverable rename after deactivation; do not run its uninstaller:

```bash
mv "$site/wp-content/plugins/wp-file-manager-pro" \
  "$backup/wp-file-manager-pro.disabled" 2>/dev/null || true
"$site/scripts/check-plugin-integrity.sh" --profile without-premium "$site"
wp --path="$site" db check
```

The exclusions preserve credentials, WAF configuration, uploads, migration
backups, and Wordfence state. The release process never activates plugins or
changes the database.

Before diagnosing a continuing Health Check translation warning, record a fresh
log timestamp and inspect `wp-content/mu-plugins/health-check-troubleshooting-mode.php`.
A stale copied MU plugin is hosting state and is not updated merely by replacing
the normal plugin. Disable Troubleshooting Mode through its supported UI before
removing an orphaned MU-plugin file.

## Rollback

If any fatal error, login failure, WAF failure, or regression occurs, stop and
restore the complete predeployment file backup (including the matching WAF
bootstrap and PHP setting). Restore the database backup only if a database change
actually occurred and the hosting owner authorizes it. Clear cache/OPcache, run
`wp db check`, and repeat the smoke checks. Keep the failed release for diagnosis;
do not run plugin uninstallers or WordPress installation routines.
