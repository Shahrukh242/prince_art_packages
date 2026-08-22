const express = require('express');
const router = express.Router();
const db = require('../config/db');
const { requireAuth } = require('../middleware/auth');

/**
 * GET /api/site-settings
 */
router.get('/', async (req, res, next) => {
  try {
    const settings = await db.query('SELECT * FROM site_settings ORDER BY id DESC');
    res.json({ success: true, count: settings.length, data: settings });
  } catch (error) {
    next(error);
  }
});

/**
 * GET /api/site-settings/:id
 */
router.get('/:id', async (req, res, next) => {
  try {
    const settings = await db.query('SELECT * FROM site_settings WHERE id = ?', [req.params.id]);
    if (settings.length === 0) {
      return res.status(404).json({ success: false, error: 'Site setting not found' });
    }
    res.json({ success: true, data: settings[0] });
  } catch (error) {
    next(error);
  }
});

/**
 * POST /api/site-settings
 */
router.post('/', requireAuth, async (req, res, next) => {
  try {
    const { setting_key, setting_value, setting_type } = req.body;

    if (!setting_key) {
      return res.status(400).json({
        success: false,
        error: 'Validation Error',
        details: 'setting_key is required.'
      });
    }

    const type = setting_type || 'text';

    const result = await db.query(
      'INSERT INTO site_settings (setting_key, setting_value, setting_type) VALUES (?, ?, ?)',
      [setting_key, setting_value || null, type]
    );

    const [newSetting] = await db.query('SELECT * FROM site_settings WHERE id = ?', [result.insertId]);

    res.status(201).json({
      success: true,
      message: 'Site setting created successfully',
      data: newSetting
    });
  } catch (error) {
    next(error);
  }
});

/**
 * PUT /api/site-settings/:id
 */
router.put('/:id', requireAuth, async (req, res, next) => {
  try {
    const settingId = req.params.id;
    const { setting_key, setting_value, setting_type } = req.body;

    const existing = await db.query('SELECT id FROM site_settings WHERE id = ?', [settingId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Site setting not found' });
    }

    const fieldsToUpdate = [];
    const params = [];

    if (setting_key !== undefined) { fieldsToUpdate.push('setting_key = ?'); params.push(setting_key); }
    if (setting_value !== undefined) { fieldsToUpdate.push('setting_value = ?'); params.push(setting_value); }
    if (setting_type !== undefined) { fieldsToUpdate.push('setting_type = ?'); params.push(setting_type); }

    if (fieldsToUpdate.length === 0) {
      return res.status(400).json({ success: false, error: 'No fields provided for update' });
    }

    const queryStr = `UPDATE site_settings SET ${fieldsToUpdate.join(', ')} WHERE id = ?`;
    params.push(settingId);

    await db.query(queryStr, params);

    const [updatedSetting] = await db.query('SELECT * FROM site_settings WHERE id = ?', [settingId]);

    res.json({
      success: true,
      message: 'Site setting updated successfully',
      data: updatedSetting
    });
  } catch (error) {
    next(error);
  }
});

/**
 * DELETE /api/site-settings/:id
 */
router.delete('/:id', requireAuth, async (req, res, next) => {
  try {
    const settingId = req.params.id;
    const existing = await db.query('SELECT id FROM site_settings WHERE id = ?', [settingId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Site setting not found' });
    }

    await db.query('DELETE FROM site_settings WHERE id = ?', [settingId]);

    res.json({
      success: true,
      message: 'Site setting deleted successfully',
      id: parseInt(settingId, 10)
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
