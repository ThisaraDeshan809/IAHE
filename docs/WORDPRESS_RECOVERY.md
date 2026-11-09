# WordPress critical-error recovery

## Root causes and safe disposition

The Git checkout is **source-only**, not a deployable WordPress tree. A duplicated
global `vendor/` ignore rule removed runtime files bundled in official plugin
distributions. Never activate the affected repository copies.

| Component | Repository version | Requirements | Finding and disposition |
|---|---:|---|---|
| All-in-One WP Migration | 7.95 | WP 3.3+, PHP 5.3+ | `Bandar.php` and the rest of `lib/vendor` are absent. Replace the whole directory with the official 7.95 package during release assembly. Preserve `wp-content/ai1wm-backups` separately. |
| Wordfence | 8.1.0 | WP 4.7+, PHP 7.0+ | `vendor/wordfence/wf-waf/src/init.php` is absent. Replace the whole directory with the official 8.1.0 package. Validate the WAF bootstrap before activation. |
| Yoast SEO | 25.3.1 | WP 6.6+, PHP 7.4+ | `vendor/autoload.php` is absent. Replace the whole directory with the official package. The child theme no longer calls the internal `WPSEO_Frontend` class or disables schema. |
| WP File Manager Pro | 8.4.3 | WP 3.4+; effective PHP 7.4+ site baseline | The arrow function caused the reported parse error on PHP <7.4 and relative class includes caused update/backup/restore startup failures when the working directory changed. The source copy now uses absolute plugin-root paths and loads update classes before registering the callback. Production still requires an approved licensed package with the same corrections; the release gate rejects an unsafe package. Keep disabled until it passes. |
| WPS Hide Login | 1.9.17.2 | WP 4.1+, PHP 7.0+ | Entry autoloader is present. Preserve the configured login slug and test it on staging. |
| Health Check | 1.7.1 | WP 4.4+, PHP 5.6+ | The class is constructed during `plugins_loaded` but originally registered its translation callback on that already-fired hook. The callback runs at `init` priority 0 and CI verifies that order. No normal-bootstrap translation call remains before `init`. If a fresh warning persists, inspect the installed `wp-content/mu-plugins/health-check-troubleshooting-mode.php`, disable Troubleshooting Mode normally, and retest with timestamped logs. Prefer a compatible official update rather than patching core. |
| Google Site Kit | 1.155.0 | WP 5.2+, PHP 7.4+ | Required generated class maps under `includes/vendor` and `third-party/vendor` are absent from source. Release assembly replaces the directory with the official package. Integrity validation requires both class maps and `autoload_files.php` and executes each file to confirm it returns an array. |
| User Role Editor | 4.64.5 | WP 4.4+, PHP 7.3+ | No missing entry dependency found statically. Review roles after staging activation. |
| Coming Soon (SeedProd) | 6.18.16 | Header does not declare minimums | No missing entry dependency found statically. Confirm intended production status. |
| LiteSpeed Cache | 7.2 | WP 5.3+, PHP 7.2+ | Stored under `litespeed-cache-disabled`; leave disabled unless the host uses LiteSpeed and staging validation succeeds. |

The site-wide supported runtime baseline is PHP 7.4 because Yoast and Site Kit
require it. PHP 8.1 or 8.2 is recommended after staging compatibility checks.
The actual hosting PHP version remains a hosting-only check.

## Database error

No `wp-config.php` or database credential is stored in Git, by design. On the
server, verify values without printing secrets:

```bash
php -v
wp config get DB_NAME >/dev/null && wp config get DB_USER >/dev/null
wp config get DB_HOST
wp db check
wp core is-installed
```

Confirm the configured host and port with the provider; a Unix socket used by
`localhost` is not equivalent to TCP at `127.0.0.1`. Do not run `wp core install`.

## Wordfence WAF checks

Before changing Wordfence, inspect `.user.ini`, the PHP control panel, and
`phpinfo()` for `auto_prepend_file`. If it references root `wordfence-waf.php`,
confirm that file's path and `WFWAF_LOG_PATH` are valid after deployment. Do not
delete the directive blindly. If restoration fails, disable the prepend directive
and plugin together using the host's documented procedure, record the temporary
protection gap, and restore them after installing the complete official package.

## Verification status

Static lint and the integrity guard are automated. Plugin activation, homepage,
admin, contact form, WPS Hide Login, WAF initialization, PHP logs, database, and
import/export are **not run** without a staging database and hosting configuration.
Never test migration import against production data.

Credential rotation cannot be proved from Git. The hosting owner must confirm in
the provider control panel that any previously exposed database password was
rotated, update only the private `wp-config.php`, and test `wp db check`. Never
paste the credential into an issue, CI variable output, deployment log, or chat.
