const mysql = require('mysql2/promise');
require('dotenv').config();

const pool = mysql.createPool({
  host: process.env.DB_HOST || 'localhost',
  port: parseInt(process.env.DB_PORT, 10) || 3306,
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME || 'website_cms',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
  timezone: '+00:00'
});

/**
 * Tests database connectivity and returns connection details.
 */
async function checkConnection() {
  try {
    const connection = await pool.getConnection();
    const [rows] = await connection.query('SELECT 1 + 1 AS solution, DATABASE() as db_name, NOW() as server_time');
    connection.release();
    return {
      status: 'connected',
      database: rows[0].db_name,
      serverTime: rows[0].server_time,
      host: process.env.DB_HOST,
      port: process.env.DB_PORT
    };
  } catch (error) {
    return {
      status: 'disconnected',
      error: error.message,
      code: error.code,
      seenHost: process.env.DB_HOST || '(empty)',
      seenPort: process.env.DB_PORT || '(empty)'
    };
  }
}

/**
 * Executes a parameterized SQL query safely.
 * @param {string} sql - Parameterized SQL string
 * @param {Array} params - Array of parameters
 */
async function query(sql, params = []) {
  try {
    const [results] = await pool.execute(sql, params);
    return results;
  } catch (error) {
    console.error('[DB Query Error]:', error.message, { sql, params });
    throw error;
  }
}

module.exports = {
  pool,
  query,
  checkConnection
};
