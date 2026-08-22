const express = require('express');
const router = express.Router();
const bcrypt = require('bcryptjs');
const db = require('../config/db');
const { requireAuth } = require('../middleware/auth');

// SQL projection that excludes password_hash
const USER_FIELDS = 'id, name, email, role, status, created_at, updated_at';

/**
 * GET /api/users - List all users (excluding password_hash)
 */
router.get('/', async (req, res, next) => {
  try {
    const users = await db.query(`SELECT ${USER_FIELDS} FROM users ORDER BY id DESC`);
    res.json({ success: true, count: users.length, data: users });
  } catch (error) {
    next(error);
  }
});

/**
 * GET /api/users/:id - Get single user by ID
 */
router.get('/:id', async (req, res, next) => {
  try {
    const users = await db.query(`SELECT ${USER_FIELDS} FROM users WHERE id = ?`, [req.params.id]);
    if (users.length === 0) {
      return res.status(404).json({ success: false, error: 'User not found' });
    }
    res.json({ success: true, data: users[0] });
  } catch (error) {
    next(error);
  }
});

/**
 * POST /api/users - Create new user
 */
router.post('/', requireAuth, async (req, res, next) => {
  try {
    const { name, email, password, role, status } = req.body;

    if (!name || !email || !password) {
      return res.status(400).json({
        success: false,
        error: 'Validation Error',
        details: 'Name, email, and password are required.'
      });
    }

    const salt = await bcrypt.genSalt(10);
    const passwordHash = await bcrypt.hash(password, salt);
    const userRole = role || 'editor';
    const userStatus = status || 'active';

    const result = await db.query(
      'INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)',
      [name, email, passwordHash, userRole, userStatus]
    );

    const [newUser] = await db.query(`SELECT ${USER_FIELDS} FROM users WHERE id = ?`, [result.insertId]);

    res.status(201).json({
      success: true,
      message: 'User created successfully',
      data: newUser
    });
  } catch (error) {
    next(error);
  }
});

/**
 * PUT /api/users/:id - Update existing user
 */
router.put('/:id', requireAuth, async (req, res, next) => {
  try {
    const userId = req.params.id;
    const { name, email, password, role, status } = req.body;

    // Check existing
    const existing = await db.query('SELECT id FROM users WHERE id = ?', [userId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'User not found' });
    }

    let queryStr = 'UPDATE users SET ';
    const params = [];
    const fieldsToUpdate = [];

    if (name !== undefined) {
      fieldsToUpdate.push('name = ?');
      params.push(name);
    }
    if (email !== undefined) {
      fieldsToUpdate.push('email = ?');
      params.push(email);
    }
    if (role !== undefined) {
      fieldsToUpdate.push('role = ?');
      params.push(role);
    }
    if (status !== undefined) {
      fieldsToUpdate.push('status = ?');
      params.push(status);
    }
    if (password !== undefined && password !== '') {
      const salt = await bcrypt.genSalt(10);
      const passwordHash = await bcrypt.hash(password, salt);
      fieldsToUpdate.push('password_hash = ?');
      params.push(passwordHash);
    }

    if (fieldsToUpdate.length === 0) {
      return res.status(400).json({ success: false, error: 'No fields provided for update' });
    }

    queryStr += fieldsToUpdate.join(', ') + ' WHERE id = ?';
    params.push(userId);

    await db.query(queryStr, params);

    const [updatedUser] = await db.query(`SELECT ${USER_FIELDS} FROM users WHERE id = ?`, [userId]);

    res.json({
      success: true,
      message: 'User updated successfully',
      data: updatedUser
    });
  } catch (error) {
    next(error);
  }
});

/**
 * DELETE /api/users/:id - Delete user
 */
router.delete('/:id', requireAuth, async (req, res, next) => {
  try {
    const userId = req.params.id;
    const existing = await db.query('SELECT id FROM users WHERE id = ?', [userId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'User not found' });
    }

    await db.query('DELETE FROM users WHERE id = ?', [userId]);

    res.json({
      success: true,
      message: 'User deleted successfully',
      id: parseInt(userId, 10)
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
