const db = require('../config/db');

/**
 * Log admin activity gracefully.
 * Failure to write an activity log will never throw an error or block core operations.
 */
async function logActivity(userId, action, entityType = null, entityId = null, details = null) {
  try {
    const detailsJson = details ? JSON.stringify(details) : null;
    await db.query(
      `INSERT INTO activity_logs (user_id, action, entity_type, entity_id, details) VALUES (?, ?, ?, ?, ?)`,
      [userId || null, action, entityType || null, entityId || null, detailsJson]
    );
  } catch (err) {
    console.warn('[ActivityLog Graceful Warning] Failed to log activity:', err.message);
  }
}

module.exports = {
  logActivity
};
