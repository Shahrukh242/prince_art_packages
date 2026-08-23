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

  const sqlRaw = fs.readFileSync(dumpPath, 'utf8');
  // The dump was generated for the local DB name (website_cms). The free host
  // fixes the DB name to REMOTE_DB_NAME, so strip CREATE DATABASE / USE lines
  // and connect directly to the remote DB to avoid permission errors.
  let sql = sqlRaw
    .split('\n')
    .filter(l => !/^\s*CREATE DATABASE/i.test(l) && !/^\s*USE\s+/i.test(l))
    .join('\n');

  // Free hosts cap index keys at 767 bytes. The app's UNIQUE keys sit on
  // utf8mb4 varchar columns wider than 191 chars (e.g. slug/email = 255,
  // source_url = 500) which exceed the limit, and the engine rejects prefix
  // keys. Shrink only the keyed columns to VARCHAR(191) (764 bytes < 767).
  // Remote-import only; local schema unchanged.
  const keyedCols = ['slug', 'email', 'setting_key', 'source_url', 'keyword'];
  const keyedRe = new RegExp('^(\\s*`(' + keyedCols.join('|') + ')`\\s+varchar)\\(\\d+\\)', 'gim');
  sql = sql.replace(keyedRe, '$1(191)');

  // Free hosts also reject `DEFAULT CURRENT_TIMESTAMP` (and `ON UPDATE
  // CURRENT_TIMESTAMP`) as column defaults. The INSERTs supply explicit
  // created_at/updated_at values, so dropping these default clauses is safe.
  sql = sql
    .replace(/DEFAULT\s+CURRENT_TIMESTAMP\s*\([^)]*\)/gi, '')
    .replace(/ON\s+UPDATE\s+CURRENT_TIMESTAMP\s*\([^)]*\)/gi, '')
    .replace(/DEFAULT\s+CURRENT_TIMESTAMP/gi, '')
    .replace(/ON\s+UPDATE\s+CURRENT_TIMESTAMP/gi, '');

  const conn = await mysql.createConnection({
    host: process.env.REMOTE_DB_HOST,
    port: parseInt(process.env.REMOTE_DB_PORT || '3306', 10),
    user: process.env.REMOTE_DB_USER,
    password: process.env.REMOTE_DB_PASSWORD,
    database: process.env.REMOTE_DB_NAME,
    multipleStatements: true
  });

  // Free hosts may default to the legacy 767-byte index limit. Enabling
  // innodb_large_prefix (session-scoped) lets the app's VARCHAR(255) UNIQUE
  // keys import. Wrapped in try/catch for hosts where it's already default/removed.
  try {
    await conn.query("SET SESSION innodb_large_prefix = 1");
    await conn.query("SET SESSION innodb_file_format = 'Barracuda'");
  } catch (e) {
    console.log('  (note: could not set innodb_large_prefix — continuing)');
  }

  // The dump creates tables in SHOW TABLES order, not dependency order, so
  // foreign keys would fail (errno 150). Disable FK checks for the bulk import
  // and re-enable after. Data integrity is preserved because all referenced
  // rows are inserted in the same file.
  await conn.query("SET FOREIGN_KEY_CHECKS = 0");

  console.log(`Importing into ${process.env.REMOTE_DB_HOST}...`);
  await conn.query(sql);
  await conn.query("SET FOREIGN_KEY_CHECKS = 1");
  console.log('✅ Import complete. Your CMS data is now on the remote MySQL.');
  await conn.end();
}

main().catch(e => {
  console.error('Import failed:', e.message);
  process.exit(1);
});
