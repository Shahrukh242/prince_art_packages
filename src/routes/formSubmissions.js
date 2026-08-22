const express = require('express');
const router = express.Router();
const db = require('../config/db');
const { requireAuth } = require('../middleware/auth');
const { logActivity } = require('../middleware/activityLogger');

/**
 * Basic email syntax validation helper
 */
function isValidEmail(email) {
  if (!email || typeof email !== 'string') return false;
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim());
}

/**
 * POST /api/forms/submit
 * PUBLIC ENDPOINT - No admin auth required.
 */
router.post('/submit', async (req, res, next) => {
  try {
    const { form_type, name, contact_name, email, phone, company, company_name, message, ...extraFields } = req.body;

    const normalizedFormType = (form_type || 'contact').toLowerCase();
    const finalName = (name || contact_name || '').trim();
    const finalEmail = (email || '').trim();
    const finalPhone = (phone || '').trim();
    const finalCompany = (company || company_name || '').trim();
    const finalMessage = (message || '').trim();

    // 1. Form-Type Specific Validation
    if (normalizedFormType === 'newsletter') {
      if (!finalEmail || !isValidEmail(finalEmail)) {
        return res.status(400).json({
          success: false,
          error: 'Validation Error',
          details: 'A valid email address is required for newsletter subscription.'
        });
      }
    } else if (normalizedFormType === 'rfq' || normalizedFormType === 'quote') {
      if (!finalEmail || !isValidEmail(finalEmail)) {
        return res.status(400).json({
          success: false,
          error: 'Validation Error',
          details: 'A valid email address is required.'
        });
      }
      if (!finalName && !finalCompany) {
        return res.status(400).json({
          success: false,
          error: 'Validation Error',
          details: 'Company name or contact person is required for quotation requests.'
        });
      }
    } else {
      // Default / Contact form validation
      if (!finalEmail || !isValidEmail(finalEmail)) {
        return res.status(400).json({
          success: false,
          error: 'Validation Error',
          details: 'A valid email address is required.'
        });
      }
      if (!finalName && normalizedFormType === 'contact') {
        return res.status(400).json({
          success: false,
          error: 'Validation Error',
          details: 'Name is required for contact inquiries.'
        });
      }
    }

    // Preserve complete raw body in submitted_data JSON column
    const submittedData = JSON.stringify(req.body);

    const result = await db.query(
      `INSERT INTO form_submissions (form_type, name, email, phone, company, message, submitted_data, status) 
       VALUES (?, ?, ?, ?, ?, ?, ?, 'New')`,
      [normalizedFormType, finalName || null, finalEmail || null, finalPhone || null, finalCompany || null, finalMessage || null, submittedData]
    );

    const submissionId = result.insertId;

    res.status(201).json({
      success: true,
      message: 'Submission received successfully',
      submissionId,
      referenceId: `SUB-${submissionId}`
    });
  } catch (error) {
    next(error);
  }
});

/**
 * GET /api/forms/submissions
 * PROTECTED - Requires admin/editor auth.
 */
router.get('/submissions', requireAuth, async (req, res, next) => {
  try {
    const { form_type, status, search, limit = 50, offset = 0 } = req.query;

    let queryStr = 'SELECT * FROM form_submissions WHERE 1=1';
    const params = [];

    if (form_type && form_type !== 'all') {
      queryStr += ' AND form_type = ?';
      params.push(form_type);
    }

    if (status && status !== 'all') {
      queryStr += ' AND status = ?';
      params.push(status);
    }

    if (search) {
      queryStr += ' AND (name LIKE ? OR email LIKE ? OR company LIKE ? OR message LIKE ?)';
      const searchPattern = `%${search}%`;
      params.push(searchPattern, searchPattern, searchPattern, searchPattern);
    }

    queryStr += ' ORDER BY id DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit, 10), parseInt(offset, 10));

    const submissions = await db.query(queryStr, params);

    // Get total count
    const [countResult] = await db.query('SELECT COUNT(*) as total FROM form_submissions');
    const totalCount = countResult ? countResult.total : submissions.length;

    res.json({
      success: true,
      count: submissions.length,
      total: totalCount,
      data: submissions
    });
  } catch (error) {
    next(error);
  }
});

/**
 * GET /api/forms/submissions/:id
 * PROTECTED - Requires auth.
 */
router.get('/submissions/:id', requireAuth, async (req, res, next) => {
  try {
    const rows = await db.query('SELECT * FROM form_submissions WHERE id = ?', [req.params.id]);
    if (rows.length === 0) {
      return res.status(404).json({ success: false, error: 'Submission not found' });
    }
    res.json({ success: true, data: rows[0] });
  } catch (error) {
    next(error);
  }
});

/**
 * PUT /api/forms/submissions/:id
 * PROTECTED - Requires auth. Update status / notes.
 */
router.put('/submissions/:id', requireAuth, async (req, res, next) => {
  try {
    const { status, notes } = req.body;
    const subId = req.params.id;

    const rows = await db.query('SELECT * FROM form_submissions WHERE id = ?', [subId]);
    if (rows.length === 0) {
      return res.status(404).json({ success: false, error: 'Submission not found' });
    }

    const updates = [];
    const params = [];

    if (status !== undefined) {
      const validStatuses = ['New', 'Read', 'Contacted', 'Qualified', 'Closed'];
      const finalStatus = validStatuses.find(s => s.toLowerCase() === status.toLowerCase()) || status;
      updates.push('status = ?');
      params.push(finalStatus);
    }

    if (notes !== undefined) {
      updates.push('notes = ?');
      params.push(notes);
    }

    if (updates.length === 0) {
      return res.status(400).json({ success: false, error: 'No fields provided for update' });
    }

    params.push(subId);
    await db.query(`UPDATE form_submissions SET ${updates.join(', ')} WHERE id = ?`, params);

    const [updatedRow] = await db.query('SELECT * FROM form_submissions WHERE id = ?', [subId]);

    // Log activity gracefully
    logActivity(req.user ? req.user.id : null, 'Updated Lead Status', 'form_submissions', subId, { status, notes });

    res.json({
      success: true,
      message: 'Submission updated successfully',
      data: updatedRow
    });
  } catch (error) {
    next(error);
  }
});

/**
 * DELETE /api/forms/submissions/:id
 * PROTECTED - Requires auth.
 */
router.delete('/submissions/:id', requireAuth, async (req, res, next) => {
  try {
    const subId = req.params.id;
    const rows = await db.query('SELECT id FROM form_submissions WHERE id = ?', [subId]);
    if (rows.length === 0) {
      return res.status(404).json({ success: false, error: 'Submission not found' });
    }

    await db.query('DELETE FROM form_submissions WHERE id = ?', [subId]);
    logActivity(req.user ? req.user.id : null, 'Deleted Submission', 'form_submissions', subId);

    res.json({
      success: true,
      message: 'Submission deleted successfully'
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
