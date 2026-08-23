const mysql = require('mysql2/promise');
require('dotenv').config();
const dbConfig = require('./db.config');

// Prefer the full platform-provided env set when DB_HOST is present; otherwise
// fall back to the committed free-DB config. We never mix the two (a hybrid of
// localhost host + remote password would fail to connect).
const envProvided = !!(process.env.DB_HOST && process.env.DB_HOST.trim());
const cfg = envProvided
  ? {
      host: process.env.DB_HOST,
      port: parseInt(process.env.DB_PORT, 10) || 3306,
      user: process.env.DB_USER,
      password: process.env.DB_PASSWORD,
      database: process.env.DB_NAME
    }
  : dbConfig;

const pool = mysql.createPool({
  host: cfg.host,
  port: parseInt(cfg.port, 10) || 3306,
  user: cfg.user,
  password: cfg.password,
  database: cfg.database,
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
      code: error.code
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
