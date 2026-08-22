const express = require('express');
const router = express.Router();
const db = require('../config/db');
const { requireAuth } = require('../middleware/auth');
const { logActivity } = require('../middleware/activityLogger');

/**
 * GET /api/redirects — Fetch all redirects
 */
router.get('/', requireAuth, async (req, res, next) => {
  try {
    const redirects = await db.query('SELECT * FROM redirects ORDER BY id DESC');
    const normalized = redirects.map(r => ({
      ...r,
      target_url: r.target_url || r.destination_url,
      is_active: r.is_active !== undefined ? r.is_active : (r.active !== undefined ? r.active : 1)
    }));
    res.json({ success: true, count: normalized.length, data: normalized });
  } catch (error) {
    next(error);
  }
});

/**
 * GET /api/redirects/:id — Fetch redirect by ID
 */
router.get('/:id', requireAuth, async (req, res, next) => {
  try {
    const rows = await db.query('SELECT * FROM redirects WHERE id = ?', [req.params.id]);
    if (rows.length === 0) {
      return res.status(404).json({ success: false, error: 'Redirect rule not found' });
    }
    const r = rows[0];
    r.target_url = r.target_url || r.destination_url;
    r.is_active = r.is_active !== undefined ? r.is_active : (r.active !== undefined ? r.active : 1);
    res.json({ success: true, data: r });
  } catch (error) {
    next(error);
  }
});

/**
 * POST /api/redirects — Create new URL redirect
 */
router.post('/', requireAuth, async (req, res, next) => {
  try {
    const { source_url, target_url, destination_url, status_code = 301, is_active = 1, active = 1, notes } = req.body;
    const target = target_url || destination_url;
    const activeState = is_active !== undefined ? is_active : active;

    if (!source_url || !target) {
      return res.status(400).json({
        success: false,
        error: 'Validation Error',
        details: 'source_url and target_url are required.'
      });
    }

    const cleanSource = source_url.startsWith('/') ? source_url : `/${source_url}`;

    const result = await db.query(
      'INSERT INTO redirects (source_url, target_url, destination_url, status_code, is_active, active, notes) VALUES (?, ?, ?, ?, ?, ?, ?)',
      [cleanSource, target, target, parseInt(status_code, 10), activeState ? 1 : 0, activeState ? 1 : 0, notes || null]
    );

    const [newRedirect] = await db.query('SELECT * FROM redirects WHERE id = ?', [result.insertId]);
    newRedirect.target_url = newRedirect.target_url || newRedirect.destination_url;
    newRedirect.is_active = newRedirect.is_active !== undefined ? newRedirect.is_active : newRedirect.active;

    await logActivity(req.user ? req.user.id : null, 'create_redirect', 'redirect', result.insertId, {
      source_url: cleanSource,
      target_url: target,
      status_code
    });

    res.status(201).json({
      success: true,
      message: 'URL Redirect created successfully',
      data: newRedirect
    });
  } catch (error) {
    next(error);
  }
});

/**
 * PUT /api/redirects/:id — Update URL redirect
 */
router.put('/:id', requireAuth, async (req, res, next) => {
  try {
    const redirectId = req.params.id;
    const { source_url, target_url, destination_url, status_code, is_active, active, notes } = req.body;
    const target = target_url || destination_url;
    const activeState = is_active !== undefined ? is_active : active;

    const existing = await db.query('SELECT id FROM redirects WHERE id = ?', [redirectId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Redirect rule not found' });
    }

    const fieldsToUpdate = [];
    const params = [];

    if (source_url !== undefined) {
      const cleanSource = source_url.startsWith('/') ? source_url : `/${source_url}`;
      fieldsToUpdate.push('source_url = ?');
      params.push(cleanSource);
    }
    if (target !== undefined) {
      fieldsToUpdate.push('target_url = ?', 'destination_url = ?');
      params.push(target, target);
    }
    if (status_code !== undefined) {
      fieldsToUpdate.push('status_code = ?');
      params.push(parseInt(status_code, 10));
    }
    if (activeState !== undefined) {
      fieldsToUpdate.push('is_active = ?', 'active = ?');
      params.push(activeState ? 1 : 0, activeState ? 1 : 0);
    }
    if (notes !== undefined) {
      fieldsToUpdate.push('notes = ?');
      params.push(notes);
    }

    if (fieldsToUpdate.length === 0) {
      return res.status(400).json({ success: false, error: 'No fields provided for update' });
    }

    const queryStr = `UPDATE redirects SET ${fieldsToUpdate.join(', ')} WHERE id = ?`;
    params.push(redirectId);

    await db.query(queryStr, params);

    const [updatedRedirect] = await db.query('SELECT * FROM redirects WHERE id = ?', [redirectId]);
    updatedRedirect.target_url = updatedRedirect.target_url || updatedRedirect.destination_url;
    updatedRedirect.is_active = updatedRedirect.is_active !== undefined ? updatedRedirect.is_active : updatedRedirect.active;

    await logActivity(req.user ? req.user.id : null, 'update_redirect', 'redirect', redirectId, req.body);

    res.json({
      success: true,
      message: 'URL Redirect updated successfully',
      data: updatedRedirect
    });
  } catch (error) {
    next(error);
  }
});

/**
 * DELETE /api/redirects/:id — Delete URL redirect
 */
router.delete('/:id', requireAuth, async (req, res, next) => {
  try {
    const redirectId = req.params.id;
    const existing = await db.query('SELECT id FROM redirects WHERE id = ?', [redirectId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Redirect rule not found' });
    }

    await db.query('DELETE FROM redirects WHERE id = ?', [redirectId]);

    await logActivity(req.user ? req.user.id : null, 'delete_redirect', 'redirect', redirectId, {});

    res.json({
      success: true,
      message: 'URL Redirect deleted successfully',
      id: parseInt(redirectId, 10)
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
