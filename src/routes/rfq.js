const express = require('express');
const router = express.Router();
const db = require('../config/db');

/**
 * POST /api/rfq
 * Receives a Request For Quotation (RFQ) from prospective pharmaceutical clients.
 * Integrates with form_submissions table for centralized lead capture.
 */
router.post('/', async (req, res, next) => {
  try {
    const { company_name, contact_name, email, phone, product_type, estimated_quantity, specifications, message } = req.body;

    if (!company_name || !contact_name || !email || !product_type) {
      return res.status(400).json({
        success: false,
        error: 'Validation Error',
        details: 'Company Name, Contact Name, Email, and Product Type are required.'
      });
    }

    const submittedData = JSON.stringify(req.body);

    const result = await db.query(
      `INSERT INTO form_submissions (form_type, name, email, phone, company, message, submitted_data, status) 
       VALUES ('rfq', ?, ?, ?, ?, ?, ?, 'New')`,
      [contact_name, email, phone || null, company_name, message || specifications || null, submittedData]
    );

    const submissionId = result.insertId;

    res.status(201).json({
      success: true,
      message: 'Quotation request submitted successfully. Our technical sales team will contact you within 24 business hours with a formal proposal.',
      submissionId,
      referenceId: `RFQ-${submissionId}`
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
