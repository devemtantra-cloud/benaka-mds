# Benaka Motor Driving School — benakamds.in

Source for the live site **https://benakamds.in** (Hostinger shared hosting, PHP 8.2 + mysqli, MariaDB, no framework).
This repo holds the code as it was live on **2026-11-24** (deploy_clean website + UI v2 admin panel).

> **No secrets or customer data in this repo.** Real database passwords, the Google OAuth secret,
> SQL dumps, zips and `json_rem.txt` (reminder export with customer phone numbers) are git-ignored.

## Folder structure

```
public_html/                    = the server's public_html folder
  *.php                         public website (index, about_us, contact, service pages *_d.php, feedback)
  database.example.php          template -> copy to database.php
  css/ js/ fonts/ images/ prettyphoto/
  admin/
    database.example.php        template -> copy to admin/database.php
    app/                        RTO admin + branch panel (login.php, dashboard_view.php, customers_view.php, rto.php, ...)
      auth.php  ui.php          shared login guard + UI/DB helpers
    css/benaka-ui.css js/benaka-ui.js   UI v2 theme (light/dark)
    img/slider img/service      uploaded images (scripts blocked by .htaccess)
    lib/                        vendor JS/CSS
  google_drive_upload/
    config.example.php          template -> copy to config.php (Google OAuth client id/secret)
docs/
  HANDOFF_CONTEXT.md            full history, DB tables, gotchas, open items
```

## Config files (not in git)

| Real file (server only) | Template in repo |
|---|---|
| `public_html/database.php` | `public_html/database.example.php` |
| `public_html/admin/database.php` | `public_html/admin/database.example.php` |
| `public_html/google_drive_upload/config.php` | `public_html/google_drive_upload/config.example.php` |

The live server already has the real files. **Never overwrite them** when deploying, and never upload the `*.example.php` values to live.

## How to deploy a change (hPanel File Manager)

1. Make the change here and note exactly which files changed.
2. Build a zip that contains **only the changed files**, with paths relative to `public_html`
   (e.g. `admin/app/rto.php`). Use forward-slash paths. On Windows, do not use PowerShell 5.1
   `ZipFile.CreateFromDirectory` (it writes backslashes and Hostinger extracts them as literal file names);
   7-Zip or `git archive` are fine:
   ```
   git archive -o update.zip HEAD:public_html admin/app/rto.php admin/css/benaka-ui.css
   ```
3. hPanel → **File Manager** → open `public_html` → Upload the zip.
4. Right-click the zip → **Extract** → destination `public_html` (not a sub-folder), allow overwrite.
   **Never empty a folder before extracting.**
5. **Delete the zip** from `public_html` immediately. Never leave `.zip` / `.sql` files on the server.
6. If the change has SQL, run it in hPanel → Databases → **phpMyAdmin** (SQL tab).
7. Check the site online: home page, `/admin/app/login.php`, and the page you changed.
   For view-only checks on the dashboard, remember any date filter is saved; open `?reset=1` afterwards.

## Local test (Docker)

```
# DB (use a local copy of the dump; it holds customer data, keep it out of this repo)
docker run -d --name bkdb -e MARIADB_ROOT_PASSWORD=root -e MARIADB_DATABASE=benaka_driving -p 3307:3306 mariadb:10.11
# import the dump, then the schema migrations (deploy_clean/migration.sql, ui_v2/migration_ui_v2.sql)

# Web: PHP 8.2 + Apache with mysqli
docker run -d --name bkweb -p 8080:80 -v "$PWD/public_html:/var/www/html" php:8.2-apache \
  sh -c "docker-php-ext-install mysqli && a2enmod rewrite && apache2-foreground"
```

Copy the three `*.example.php` files to their real names and point both `database.php` files at the test DB
(host = `host.docker.internal`, port 3307, or put both containers on one Docker network).
Add test logins (one `utype='MA'`, one branch user) with `password_hash()`. Lint with `php -l` on PHP 7.4 and 8.2.

## Notes

- Live DB is non-strict: inserts into `client` must set `v_name`, `Job_data`, `remark` (`''`) and `exp_date` (`'0000-00-00'` if blank).
- `branch_id` is varchar: compare with `=`, never `LIKE`.
- `admin/js/widgets.js` is an unused template demo file; its demo Google Maps key was replaced with a placeholder.
- See `docs/HANDOFF_CONTEXT.md` for everything else.
