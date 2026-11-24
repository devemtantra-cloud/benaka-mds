# Benaka MDS (benakamds.in) — handoff context

Written 2026-11-24 to continue this work in a new (cloud) session. Covers everything done from
2026-10-16 to 2026-10-18 in the first session. **No passwords or secrets are in this file** — they live
only in the site's config files on the server and in the local copies listed below.

---

## 1. What the project is

- **Client:** Benaka Motor Driving School (Benaka MDS), Kodagu/Karnataka — driving school + RTO
  (vehicle documents) services, several branches (Somwarpet, Madapura, Hebbale, Kodlipete,
  Shanivarasanthe, Virajpet, BANTWAL, "Sticker customers"/Kodagu, …).
- **Live site:** https://benakamds.in on **Hostinger** shared hosting (hPanel, File Manager,
  phpMyAdmin). Server: **PHP 8.2.34**, MariaDB ~10.11, Hostinger CDN `hcdn` in front.
- **Plain PHP + mysqli, no framework, no git.** All code is edited as files and deployed by uploading
  zips through hPanel File Manager.
- **Three parts (only two matter):**
  1. **Public website** — `public_html/*.php` (index, about_us, contact, service, five service pages
     `auto_c_d.php`, `driving_school_d.php`, `emission_d.php`, `v_insurance_d.php`, `v_loan_d.php`
     whose text comes from the DB `rules` table; home slider from `slider` table; font from `font` table).
  2. **RTO admin + branch panel** — `public_html/admin/app/`. Two roles in table `login`:
     main admin = `utype 'MA'` (branch empty, sees all branches) → `admin_index.php`;
     branch users = other utype with `branch` id → `index.php`. Also the website content manager
     (slider, service page content, services, font, WhatsApp message templates, branches, users).
  3. ~~Courier app "Atlant Tracker"~~ — a different client's project found in the backup; **removed**
     from the site (archived, see §3). Ignore it.
- **Database (admin & site share one DB, name ends in `benaka_driving`):** main tables
  `client` (~33.6k customers: name, v_no, w_number (WhatsApp, "91…"), sr_type (service), doc_number,
  exp_date (DATE), branch_id (varchar), wt_status ('no' = no WhatsApp), Job_data, remark, d_inward
  (varchar date), case_worker, v_name), `branch` (branch_id, name, branch_name, address, phone,
  whatsapp_no), `login` (uname, password **hashed now**, utype, branch, fm/too = saved dashboard range),
  `msg` (WhatsApp templates: type 2 = reminder keyed by Name=service; type 1 = RTO keyed by
  Name=job status + srtype), `history` (RTO change log), `rules`, `service`, `slider`, `service_pht`,
  `font`, `datef` (main admin's saved range), `feeback` (sic), `drive_files`, `days`.
- **WhatsApp templates** use placeholders `#name #vn #newDate #bname #add #ph #wtapp #doc #jobdata #sr`
  (+ `#rem`, `#nn` in RTO) and are stored URL-style (`%0a` = new line).

## 2. Folder map (Google Drive "My Drive (dev.emtantra@gmail.com)")

| Path | What |
|---|---|
| `benaka/backup/public_html/` | Original backup supplied by the user (Oct 2026), cleaned of junk. Pre-hotfix code. |
| `benaka_archive/` (sibling of `benaka`) | Everything moved out of the backup: courier app, SQL dumps (**contain customer data — keep private**), old v1 admin `driving/`, backup copies, template leftovers. `README.md` lists every path. |
| `benaka/hotfix_admin/` | Security + bug-fix files (rounds 1–4) and `DEPLOY.md`, `migration.sql`. Superseded by deploy_clean/ui_v2 but DEPLOY.md documents every fix. |
| `benaka/deploy_clean/` | Full clean site (500 files) = backup + all hotfixes; `benaka_public_html_clean_2026-10-17.zip`, `DEPLOY_CLEAN.md`. **This was deployed live on 2026-10-17 as a full replacement.** |
| `benaka/ui_v2/` | **Current live version of the admin panel.** `public_html/` (full tested site), `benaka_admin_ui_v2_update.zip` (54 changed admin files), `changed_files.txt`, `migration_ui_v2.sql` (indexes), `DEPLOY_UI_V2.md`, `screenshots/`. Deployed 2026-10-18. |
| `benaka/HANDOFF_CONTEXT.md` | This file. |

**Source of truth for the live site today = `benaka/ui_v2/public_html/`** (website files identical to
deploy_clean + the 54 UI v2 admin files). Edit there for future work.

## 3. Timeline — what was done

1. **Review (Oct 16):** 5,469 files/374 MB → mostly duplicate courier copies, AdminLTE vendor files,
   zips, SQL dumps, backup copies. Found critical issues: plaintext DB creds in code, a private key
   (`courier/appsurfair.pem`) and full-source zips publicly downloadable, SQL injection everywhere,
   login checks that redirected without `exit` (admin pages leaked full customer data to logged-out
   visitors, `dashboard.php` dumped 16 MB), unrestricted image upload, planted-looking `lol.php`/
   `blank.php`/`404.php` slider rows in the March-2025 dump (files not present; scan found no webshells).
2. **Cleanup:** extras moved to `benaka_archive/` (site went 5,469 → ~500 files, 374 → 27 MB).
3. **Hotfix rounds 1–4 (Oct 17)**, all tested locally in Docker (PHP 8.2 + MariaDB 10.11 + the real
   March-2025 dump):
   - Shared `admin/app/auth.php` guard: `require_login()`, `require_admin()`, `user_branch()`,
     `client_access_ok()/require_client_access()` (branch users only touch their branch),
     `save_client_job()`; redirects always `exit`.
   - Login rewritten (`main.php`): prepared lookup, `password_hash/verify`, plaintext passwords
     auto-upgraded on next login; `login.password` widened to 255 + unique `uname` (`migration.sql`).
   - Fixed: client add failing on PHP 8.2 for doc numbers with letters; branch search leak
     (`LIKE '%6%'` matched 16/26); cross-branch edit/delete by URL; apostrophes crashing saves;
     "Delete rule" deleting a WhatsApp template (`delete_point.php` now takes `?t=`); "Add branch"
     saving the word "Phone"; uploads restricted to real images with safe names; slide delete removes
     the file; Services page linked to static pages (now 301 to editable `*_d.php`); breadcrumbs,
     "Veichle" typo, template demo leftovers, mailto, http:// jQuery/Google Font (mixed content);
     public `feedback.php` (SQLi, escaping, server-side name/branch, comment ≤222 chars);
     Google Drive upload (`google_drive_upload/`) now requires login and uses the admin DB connection
     (it pointed at the old hosting account's DB → 500); `jsonreminder.php` ("Upload Drive") writes
     valid JSON then redirects; one shared ordered script include `admin_scripts.php`;
     tablet sidebar overlap. `.htaccess` files block downloads of dumps/zips/keys and script
     execution in `admin/img`.
4. **Clean deploy (Oct 17):** user emptied `public_html` and extracted the deploy_clean zip; migration.sql
   run; verified online (65/66 checks; the 1 was a not-yet-generated file). Font link http→https fixed
   after deploy (root `header.php` re-uploaded).
5. **Live tests (Oct 17):** as main admin: added/verified/deleted a test rule, slide and client (all
   cleaned up). As branch user (Somwarpet, branch id 6): isolation confirmed.
6. **UI v2 (Oct 18)** — user's choices: redesign **admin + branch panel only**; theme **follows device
   light/dark + toggle**. Built:
   - `admin/css/benaka-ui.css` (tokens on `html[data-theme]`), `admin/js/benaka-ui.js` (theme toggle,
     toasts, confirm modal via `data-confirm`, loading bar, inline row save), new `header.php`
     (grouped menu, active item, page title, theme toggle, user chip, logout; theme set before paint),
     `ui.php` helpers (h(), flash()/done(), back_to()/safe_return(), CSRF, db_query/db_value,
     client_where(), paginate()/render_pager(), branches_map(), message_templates(),
     fill_template()/wa_url()/dashboard_wa()/rto_wa(), expiry badges).
   - Rebuilt lists: `dashboard_view.php` (index.php + admin_index.php wrappers), `customers_view.php`
     (crud.php + admin_crud.php), `rto.php`. One COUNT + one LIMIT query, compact pager, filters in
     GET, admin branch dropdown. Inline Save → `api_job.php` (JSON, CSRF, branch check, RTO history).
   - `customer_form.php` shared by `add_cust.php`/`Update_data.php` (Cancel no longer saves; case worker +
     No-WhatsApp editable). All `alert()`/new-window flows replaced with notices + same-tab navigation.
     New `login.php` (themed, inline errors), `logout.php` (`?out=1` message).
   - Results (local, same data): admin customers 106 ms/319 KB/1,681 page links → ~25 ms/31 KB;
     admin RTO Work 1.5 s/7 MB → ~20 ms/10 KB; dashboards ~25 ms. 58/58 functional checks.
   - `migration_ui_v2.sql`: indexes `client(branch_id,exp_date)`, `(exp_date)`, `(branch_id,d_inward)`,
     `(d_inward)`, `(w_number)`, `msg(Name,srtype)`.
7. **UI v2 deploy (Oct 18):** user accidentally emptied `public_html/admin` before extracting the
   54-file update → panel 500 + home slider broken. Fixed by extracting their `admin.zip` backup, then the
   update zip again. Both zips then deleted (they were briefly publicly downloadable — see §5).
   Index SQL ran successfully. Live view-only check as Somwarpet user: 0 PHP/JS errors, correct paging
   (e.g. "Showing 121–140 of 14,745", `‹ 1 … 5 6 7 8 9 … 738 ›`), isolation OK.

## 4. Current state (as of 2026-10-18, re-confirmed login page up on 2026-11-24)

- Live = deploy_clean website + UI v2 admin. Everything above is deployed and verified.
- **Not tested on live (only locally):** inline Save on rows, customer add/edit via new form,
  WhatsApp buttons, Upload to Drive end-to-end (needs the user's Google sign-in).
- Local Docker test environment (containers `bkdb`, `bkweb`, `bkui`, image `bkphp`) may still exist on
  the user's PC; the temp copies they served from were cleared. Rebuild if needed (see §7).

## 5. Open items / to-do (priority order)

1. **Rotate exposed secrets (user action, urgent):** MySQL password (then update `$password` in
   `public_html/database.php` AND `public_html/admin/database.php`) — `admin.zip` containing
   `database.php` was publicly downloadable on 2026-10-18; Google OAuth client secret
   (`google_drive_upload/config.php`); revoke `appsurfair.pem`; SQL Server `sa` password (old courier
   connector); weak staff passwords (e.g. `admin`).
2. **Upload to Drive:** user to click it once and complete Google sign-in; confirm file appears.
3. **Investigate `.htaccess` zip blocking:** `admin.zip` returned 200 while `benaka_admin_ui_v2_update.zip`
   returned 403 at the same time; root rule is `<FilesMatch "\.(sql|zip|…)$"> Require all denied`.
   Unknown why it missed one file. Policy: never leave zips/sql in `public_html`; prefer hPanel → Backups.
4. Optional cleanups: `Add slider` admin list shows two old slides with stored `http://` URLs (displayed
   fine; could normalise like `index.php` does); `u_index.php`/`u_rto.php` kept only for old links.
5. **Feature list from the user is still pending** (they asked for "new features" at the start).
   Ideas raised: RTO Work default range (now last 30 days), show service photos (`service_pht`, uploaded
   but not displayed anywhere on the website).

## 6. Gotchas learned (important for whoever continues)

- **Dashboard saves the date range** on any GET with `from_date`/`to_date` (per branch in `login.fm/too`,
  main admin in `datef`). Testing live with wide ranges changes what staff see → use `?reset=1`
  afterwards (sets today → +30 days). RTO Work keeps its range in the session only.
- **Live DB is non-strict; local MariaDB was strict.** Inserts must still set `v_name`, `Job_data`,
  `remark` ('' ) and use `'0000-00-00'` for blank `exp_date` (no defaults on those columns).
- **PHP 8.2 throws mysqli exceptions**; many old pages use `error_reporting(0)` → blank pages instead of
  errors. Use prepared statements (`db_query`) for anything touched.
- **Zips made on Windows PowerShell 5.1** with `ZipFile.CreateFromDirectory` contain backslash paths —
  Hostinger extracts them as literal filenames. Build zips entry-by-entry with `/` (done for all zips here)
  and verify by unzipping in Linux (busybox) + md5 compare.
- **Upload zips contain only changed files** — never empty a folder before extracting an update.
- Extract in File Manager with destination `public_html` (not a sub-folder), allow overwrite, then delete
  the zip.
- Message templates are URL-encoded text: `fill_template()` does `rawurldecode(strtr(...))` once and
  `wa_url()` encodes once (fixes `%0a` showing literally).
- `branch_id` is varchar; compare exactly (`=`), never `LIKE '%x%'`.
- Shell editing pitfall: perl/sed inside bash double quotes ate `$var` names twice — use file editors.

## 7. How to test locally again (Docker)

- DB: `mariadb:10.11` with the March-2025 dump from
  `benaka_archive/2_data_dumps_SENSITIVE/public_html/<hosting-account>-driving (1)-sql-EXTRACTED-ARCHIVE/`
  (customer data — keep local). Run `deploy_clean/migration.sql` and `ui_v2/migration_ui_v2.sql`.
  Add test logins (one `MA`, one branch `6`) with `password_hash`.
- Web: `php:8.2-apache` + `docker-php-ext-install mysqli`, `AllowOverride All`, mount a copy of
  `ui_v2/public_html` with both `database.php` files pointed at the test DB.
- Lint with `php:7.4-cli` and `php:8.2-cli` (`php -l`). Desktop screenshots via headless Edge
  (`msedge --headless=new --screenshot`, `--force-dark-mode` / `--blink-settings=preferredColorScheme=1`)
  need a test-only auto-login page — never put such a file in the deploy folders.

## 8. People / preferences

- The user (developer at EmTantra) deploys by hand through hPanel and prefers step-by-step instructions,
  one step at a time, with online verification after each deploy.
- They asked for view-only checks on live unless they explicitly approve test writes.
