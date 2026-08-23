/**
 * dump-db.js
 * -----------
 * Dumps the LOCAL website_cms database (running on your XAMPP MySQL) into a
 * single SQL file that can be imported into a FREE remote MySQL host
 * (FreeSQLDatabase.com / db4free.net) so the Vercel-deployed app can log in.
 *
 * Run locally:   node tools/dump-db.js
 * Output:        tools/dump-website_cms.sql   (gitignored - contains your data)
 *
 * This script reads the SAME credentials the app uses (from .env), so it
 * connects to your local XAMPP MySQL automatically. No secrets are printed.
 */
const fs = require('fs');
const path = require('path');
const mysql = require('mysql2/promise');
require('dotenv').config();

const DB_NAME = process.env.DB_NAME || 'website_cms';
const OUT = path.join(__dirname, 'dump-website_cms.sql');

async function main() {
  const conn = await mysql.createConnection({
    host: process.env.DB_HOST || 'localhost',
    port: parseInt(process.env.DB_PORT || '3306', 10),
    user: process.env.DB_USER || 'root',
    password: process.env.DB_PASSWORD || '',
    database: DB_NAME,
    multipleStatements: true
  });

  const [tables] = await conn.query('SHOW TABLES');
  const tableKey = Object.keys(tables[0])[0];
  const tableNames = tables.map(t => t[tableKey]);

  let sql = `-- Prince Art Packages CMS dump\n-- Source DB: ${DB_NAME}\n-- Generated: ${new Date().toISOString()}\n\n`;
  sql += `CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;\nUSE \`${DB_NAME}\`;\n\n`;

  for (const tbl of tableNames) {
    const [[createRow]] = await conn.query(`SHOW CREATE TABLE \`${tbl}\``);
    sql += `DROP TABLE IF EXISTS \`${tbl}\`;\n`;
    sql += createRow['Create Table'] + ';\n\n';

    const [rows] = await conn.query(`SELECT * FROM \`${tbl}\``);
    if (rows.length) {
      const cols = Object.keys(rows[0]);
      const escapedCols = cols.map(c => `\`${c}\``).join(', ');
      sql += `INSERT INTO \`${tbl}\` (${escapedCols}) VALUES\n`;
      const valueRows = rows.map(r => {
        const vals = cols.map(c => conn.escape(r[c]));
        return `  (${vals.join(', ')})`;
      });
      sql += valueRows.join(',\n') + ';\n\n';
    }
    console.log(`  dumped ${tbl} (${rows.length} rows)`);
  }

  fs.writeFileSync(OUT, sql);
  console.log(`\n✅ Dump written to ${OUT} (${(sql.length / 1024).toFixed(1)} KB)`);
  await conn.end();
}

main().catch(e => {
  console.error('Dump failed:', e.message);
  process.exit(1);
});
