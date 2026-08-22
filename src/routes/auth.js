const express = require('express');
const router = express.Router();
const bcrypt = require('bcryptjs');
const db = require('../config/db');
const { generateToken, requireAuth } = require('../middleware/auth');
const { logActivity } = require('../middleware/activityLogger');

/**
 * POST /api/auth/register
 */
router.post('/register', async (req, res, next) => {
  try {
    const { name, email, password, role } = req.body;

    if (!name || !email || !password) {
      return res.status(400).json({
        success: false,
        error: 'Validation Error',
        details: 'Name, email, and password are required.'
      });
    }

    const existing = await db.query('SELECT id FROM users WHERE email = ?', [email]);
    if (existing.length > 0) {
      return res.status(409).json({
        success: false,
        error: 'Duplicate Email',
        details: 'A user with this email already exists.'
      });
    }

    const salt = await bcrypt.genSalt(10);
    const passwordHash = await bcrypt.hash(password, salt);
    const validRoles = ['admin', 'editor', 'seo_manager'];
    const userRole = role && validRoles.includes(role.toLowerCase()) ? role.toLowerCase() : 'editor';

    const result = await db.query(
      'INSERT INTO users (name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?)',
      [name, email, passwordHash, userRole, 'active']
    );

    const [newUser] = await db.query(
      'SELECT id, name, email, role, status, created_at, updated_at FROM users WHERE id = ?',
      [result.insertId]
    );

    const token = generateToken(newUser);

    logActivity(newUser.id, 'User Registered', 'users', newUser.id, { email, role: userRole });

    res.status(201).json({
      success: true,
      message: 'User registered successfully',
      token,
      user: newUser
    });
  } catch (error) {
    next(error);
  }
});

/**
 * POST /api/auth/login
 */
router.post('/login', async (req, res, next) => {
  try {
    const { email, password } = req.body;

    if (!email || !password) {
      return res.status(400).json({
        success: false,
        error: 'Validation Error',
        details: 'Email and password are required.'
      });
    }

    const users = await db.query(
      'SELECT id, name, email, password_hash, role, status, created_at, updated_at FROM users WHERE email = ?',
      [email]
    );

    if (users.length === 0) {
      return res.status(401).json({
        success: false,
        error: 'Invalid Credentials',
        details: 'Invalid email or password.'
      });
    }

    const user = users[0];

    const isMatch = await bcrypt.compare(password, user.password_hash);
    if (!isMatch) {
      return res.status(401).json({
        success: false,
        error: 'Invalid Credentials',
        details: 'Invalid email or password.'
      });
    }

    delete user.password_hash;
    const token = generateToken(user);

    logActivity(user.id, 'User Login', 'users', user.id, { email: user.email });

    res.json({
      success: true,
      message: 'Login successful',
      token,
      user
    });
  } catch (error) {
    next(error);
  }
});

/**
 * GET /api/auth/me
 */
router.get('/me', requireAuth, async (req, res, next) => {
  try {
    const users = await db.query(
      'SELECT id, name, email, role, status, created_at, updated_at FROM users WHERE id = ?',
      [req.user.id]
    );

    if (users.length === 0) {
      return res.status(404).json({ success: false, error: 'User not found' });
    }

    res.json({
      success: true,
      user: users[0]
    });
  } catch (error) {
    next(error);
  }
});

/**
 * POST /api/auth/logout
 */
router.post('/logout', (req, res) => {
  res.json({
    success: true,
    message: 'Logged out successfully'
  });
});

module.exports = router;
