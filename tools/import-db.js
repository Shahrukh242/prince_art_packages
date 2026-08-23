/**
 * import-db.js
 * -------------
 * Imports tools/dump-website_cms.sql into your FREE remote MySQL host so the
 * Vercel-deployed app can reach a real database and the admin login works.
 *
 * 1. Create a free MySQL at https://www.freemysqldatabase.com (or db4free.net)
 * 2. Copy .env.remote.example -> .env.remote and fill in YOUR remote creds
 * 3. Run:  node tools/import-db.js
 *
 * The .env.remote file is gitignored and NEVER committed (it holds your
 * password). This script only runs on YOUR machine.
 */
const fs = require('fs');
const path = require('path');
const mysql = require('mysql2/promise');
require('dotenv').config({ path: path.join(__dirname, '..', '.env.remote') });

const dumpPath = path.join(__dirname, 'dump-website_cms.sql');

async function main() {
  if (!fs.existsSync(dumpPath)) {
    console.error('❌ dump-website_cms.sql not found. Run: node tools/dump-db.js');
    process.exit(1);
  }

  const sql = fs.readFileSync(dumpPath, 'utf8');
  const conn = await mysql.createConnection({
    host: process.env.REMOTE_DB_HOST,
    port: parseInt(process.env.REMOTE_DB_PORT || '3306', 10),
    user: process.env.REMOTE_DB_USER,
    password: process.env.REMOTE_DB_PASSWORD,
    multipleStatements: true
  });

  console.log(`Importing into ${process.env.REMOTE_DB_HOST}...`);
  await conn.query(sql);
  console.log('✅ Import complete. Your CMS data is now on the remote MySQL.');
  await conn.end();
}

main().catch(e => {
  console.error('Import failed:', e.message);
  process.exit(1);
});
