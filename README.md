# Prince Art Packages — Custom PHP Dashboard Starter

A working starting point: no Node.js anywhere, runs on any standard PHP + MySQL shared
hosting (including StackCP). Public pages and the admin dashboard share one database, so
edits in the dashboard show up on the live site immediately — no rebuild step.

## What's included and working right now
- **Admin login** with hashed passwords, session security, CSRF protection
- **Leads dashboard** — view every RFQ submission, filter by status, update status inline
- **Page content editor** — edit hero text, section copy, and SEO title/description per page
- **RFQ form** on the public contact page that writes straight into the `leads` table
- **SEO settings** — GA4 ID, Search Console tag, default meta description
- **Products page** — table scaffold ready for you to extend with the same add/edit pattern

## What's scaffolded but needs you to extend (same pattern, more typing)
- Products add/edit form (copy the structure from `admin/content.php`)
- Case studies and blog admin screens (same CRUD pattern again)
- Public templates for About, Products, Quality & Compliance, Innovation, etc. — `index.php`
  and `contact.php` show the pattern; repeat it per page

## Local setup (test this before touching the live server)
1. Install XAMPP or Laragon (gives you PHP + MySQL + phpMyAdmin locally)
2. Put this whole folder in your local server's root (e.g. `htdocs/pap-dashboard`)
3. Open phpMyAdmin, create a database called `pap_dashboard`, then import `database/schema.sql`
4. Open `includes/db.php` and confirm the DB_HOST/DB_NAME/DB_USER/DB_PASS match your local setup
   (XAMPP defaults are usually `localhost` / `pap_dashboard` / `root` / empty password)
5. Visit `http://localhost/pap-dashboard/setup.php` in your browser — create your admin username
   and password there (this generates a real hashed password, never hand-write one)
6. **Delete `setup.php` immediately after** — it's a one-time-use file and a security risk if left on the server
7. Log in at `http://localhost/pap-dashboard/admin/login.php`
8. Visit `http://localhost/pap-dashboard/public_site/` to see the public homepage pulling live content
9. Try: edit the homepage hero text in the dashboard → refresh the public homepage → see it change instantly

## Deploying to StackCP (once you're happy with it locally)
1. In StackCP: create a MySQL database + database user, note the credentials it gives you
2. Import `database/schema.sql` via phpMyAdmin (available inside StackCP)
3. Upload all files via FTP or StackCP's File Manager — **public_site/ contents should be your
   web root** (or merge public_site/ into the root, whichever your host expects), with
   `admin/`, `includes/`, and `database/` sitting alongside it, NOT inside a publicly browsable
   folder if you can help it
4. Update `includes/db.php` with the real StackCP database credentials
5. Visit `yourdomain.com/setup.php`, create your live admin account, then delete `setup.php` again
6. Confirm SSL is active, then uncomment the HTTPS-force lines in `.htaccess`
7. Test the RFQ form end-to-end on the live site and confirm the lead appears in `/admin/leads.php`

## Security notes — don't skip these before going live
- Change the default local DB password placeholder in `includes/db.php` to a real one
- Make sure `database/schema.sql` is not sitting in a publicly accessible web folder on the live server
- Delete `setup.php` after each use (local AND live — it's a separate action each time)
- Every SQL query in this starter uses PDO prepared statements — keep that pattern for anything
  you add; never concatenate user input directly into SQL

## Extending this to match the real Antigravity design
Replace `public_site/assets/css/site.css` with your actual exported CSS from Antigravity, and
rebuild `public_site/index.php` (and the other page templates) to match its actual HTML
structure — just keep using `get_content()`, `get_products()`, etc. wherever the Antigravity
design has text/images that should be dashboard-editable rather than hardcoded.
