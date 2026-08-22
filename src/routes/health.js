const express = require('express');
const router = express.Router();
const db = require('../config/db');

/**
 * GET /api/health
 * Returns status of database connection, server time, table count, and system metrics.
 */
router.get('/', async (req, res, next) => {
  try {
    const dbStatus = await db.checkConnection();
    
    if (dbStatus.status !== 'connected') {
      return res.status(503).json({
        success: false,
        status: 'unhealthy',
        database: dbStatus
      });
    }

    // Retrieve list of tables in website_cms
    const tables = await db.query(
      `SELECT TABLE_NAME, TABLE_ROWS 
       FROM INFORMATION_SCHEMA.TABLES 
       WHERE TABLE_SCHEMA = ?`,
      [dbStatus.database || 'website_cms']
    );

    res.json({
      success: true,
      status: 'healthy',
      database: {
        name: dbStatus.database,
        host: dbStatus.host,
        port: dbStatus.port,
        serverTime: dbStatus.serverTime
      },
      tables: tables.map(t => ({
        name: t.TABLE_NAME,
        approximateRows: t.TABLE_ROWS
      })),
      timestamp: new Date().toISOString()
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
