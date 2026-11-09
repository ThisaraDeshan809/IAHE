# Shared-hosting deployment and rollback

## Build a complete release

This repository must not be uploaded directly. Obtain the licensed WP File
Manager Pro 8.4.3 ZIP from the account owner and keep it outside Git. Then run:

```bash
WP_FILE_MANAGER_PRO_ZIP=/secure/wp-file-manager-pro-8.4.3.zip \
  scripts/assemble-release.sh /absolute/path/iahe-release
```

The assembler downloads version-pinned official WordPress.org packages for
All-in-One WP Migration, Wordfence, Yoast, and Site Kit; replaces the incomplete source
directories, and runs `scripts/check-plugin-integrity.sh`. A missing licensed ZIP
or runtime file stops the build. Re-run the integrity check immediately before
uploading. Do not commit the assembled plugin directories or premium archive.

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
5. Upload changed application files atomically. Exclude `wp-config.php`,
   `wp-content/uploads`, backups, caches, logs, `.user.ini`, and WAF bootstrap
   unless a reviewed Wordfence path change is intentional.
6. On staging, activate one repaired plugin at a time and inspect PHP logs after
   each: Yoast, All-in-One WP Migration, Wordfence, then WP File Manager Pro.
   Do not run a migration import against valued data.
7. Verify homepage, representative pages/templates, assets, navigation, contact
   form, admin login/dashboard, configured WPS Hide Login URL, Yoast with the
   plugin enabled and disabled, Wordfence firewall status, and file-manager
   administrator authorization/AJAX/upload restrictions.
8. Confirm production settings hide errors: `WP_DEBUG`, `WP_DEBUG_DISPLAY`, and
   `WP_DEBUG_LOG` should normally be false. Use a private server log only during
   the maintenance window. Check files are typically 0644 and directories 0755,
   adjusted only for provider requirements.
9. Deploy the staging-approved release during the window. Clear LiteSpeed/other
   caches and PHP OPcache through supported controls. Repeat integrity, frontend,
   login, dashboard, and log checks.

## Rollback

If any fatal error, login failure, WAF failure, or regression occurs, stop and
restore the complete predeployment file backup (including the matching WAF
bootstrap and PHP setting). Restore the database backup only if a database change
actually occurred and the hosting owner authorizes it. Clear cache/OPcache, run
`wp db check`, and repeat the smoke checks. Keep the failed release for diagnosis;
do not run plugin uninstallers or WordPress installation routines.
