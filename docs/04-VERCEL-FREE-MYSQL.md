# Make the Admin Login Work on Vercel (100% Free)

## The problem
You deployed the Node + MySQL CMS to Vercel. The login form loads, but login
fails with `connect ECONNREFUSED 127.0.0.1:3306`. That means the deployed API
tries to reach a MySQL database at `localhost:3306` — your home XAMPP — which
does not exist on Vercel. Vercel is serverless and has **no MySQL**, so the
API can never verify your password and the admin panel never opens.

Fix: give the deployed app a **real remote MySQL** it can reach over the
internet, for free.

## What you need (free)
A free MySQL host that allows external connections:
- **FreeSQLDatabase.com** (https://www.freemysqldatabase.com) — plain MySQL,
  port 3306, no SSL required. Best for this app.
- **db4free.net** (https://db4free.net) — alternative, same idea.

Both are 100% free. (PlanetScale free tier was retired; Supabase/Aiven free
tiers exist but add SSL/Postgres complexity — skip for now.)

The app only needs these 5 env vars (it reads them via `process.env`):
```
DB_HOST      = <remote host, e.g. db.freemysqldatabase.com>
DB_PORT      = 3306
DB_USER      = <remote user>
DB_PASSWORD  = <remote password>
DB_NAME      = website_cms
JWT_SECRET   = <any long random string, e.g. princeart-jwt-2026>
```
`PORT` and `NODE_ENV` are handled by Vercel automatically.

## Step-by-step

### 1. Create the free MySQL database
1. Go to https://www.freemysqldatabase.com → fill the form → submit.
2. You receive an email with: **MySQL Host**, **Database Name**, **Username**,
   **Password**. Copy these somewhere safe.

### 2. Put your data into it (one time, on your laptop)
1. In this repo, copy the template:
   `tools/.env.remote.example` → `.env.remote`
2. Fill `.env.remote` with the 4 values from the email
   (`REMOTE_DB_HOST`, `REMOTE_DB_PORT`, `REMOTE_DB_USER`,
   `REMOTE_DB_PASSWORD`, `REMOTE_DB_NAME`).
3. Make sure your XAMPP MySQL is running, then run:
   ```
   node tools/dump-db.js      # already done once -> tools/dump-website_cms.sql
   node tools/import-db.js    # pushes your data to the free host
   ```
   `import-db.js` reads `.env.remote` (gitignored, never committed).

### 3. Tell Vercel the database location
1. Vercel dashboard → your project → **Settings → Environment Variables**.
2. Add the 5 vars above (DB_HOST, DB_PORT, DB_USER, DB_PASSWORD, DB_NAME,
   plus JWT_SECRET). Use the remote host values, NOT localhost.
3. **Redeploy**: push a commit, or click **Deployments → Redeploy**.

### 4. Verify
Open https://prince-art-packages.vercel.app/admin.html and log in with
`admin@princeartpackages.com` / `AdminPassword2026!`. It now connects to the
free remote MySQL and the dashboard opens.

## Notes
- The dump file (`tools/dump-website_cms.sql`) and `.env.remote` are
  gitignored — your data and passwords never get committed.
- If you later add content in the admin panel, it lives on the free host.
  Re-run `dump-db.js` locally only if you want a backup copy on your laptop.
- Free hosts may sleep or have size limits; fine for a brochure B2B site.
