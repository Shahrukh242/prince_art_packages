# Deployment readiness checklist

The project is ready to deploy after the following one-time operational steps.

1. Create a MySQL database and import `database/schema.sql`.
2. For an existing installation, back up MySQL and run `database/migrations/20260905_hardening.sql` once.
3. Set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` as hosting environment variables. Do not commit production credentials.
4. Point the domain document root at this project root (the root `.htaccess` routes public requests to `public_site`). Confirm HTTPS is active before public launch.
5. Visit `setup.php` only when the `admin_users` table is empty, create the first administrator, then permanently delete `setup.php` from the server.
6. Configure SMTP in the admin settings and submit a real RFQ test. Check both the lead record and notification email.
7. Confirm the host has PHP 8.1+, PDO MySQL, Fileinfo, OpenSSL, and Apache modules `mod_rewrite`, `mod_headers`, `mod_expires`, and `mod_deflate` enabled.
8. Do not deploy `database/production_schema_and_data.sql`; it contains seeded production-like content. Keep database dumps outside the public document root.

## Post-deployment smoke test

- Open `/`, `/products`, `/product/printed-cartons`, `/blog`, and one `/blog/{slug}` URL. Detail pages must load CSS, images, navigation, and the RFQ dialog.
- Log in as an editor and verify `/admin/settings.php` returns access denied; log in as an administrator and verify account management works.
- Upload and delete a test image in Media Library.
- Submit RFQs from the contact page and the global modal, then verify the lead appears in Admin → Leads.
