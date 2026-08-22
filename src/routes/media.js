const express = require('express');
const router = express.Router();
const path = require('path');
const fs = require('fs');
const multer = require('multer');
const db = require('../config/db');
const { requireAuth } = require('../middleware/auth');
const { logActivity } = require('../middleware/activityLogger');

// Ensure upload directory exists
const uploadDir = path.join(__dirname, '../../public/uploads');
if (!fs.existsSync(uploadDir)) {
  fs.mkdirSync(uploadDir, { recursive: true });
}

// Multer Storage Setup
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, uploadDir);
  },
  filename: (req, file, cb) => {
    const ext = path.extname(file.originalname).toLowerCase();
    const sanitizedBase = path.basename(file.originalname, ext).replace(/[^a-zA-Z0-9_-]/g, '_');
    const uniqueName = `file_${Date.now()}_${Math.floor(Math.random() * 1000)}_${sanitizedBase}${ext}`;
    cb(null, uniqueName);
  }
});

// File filter validation
const fileFilter = (req, file, cb) => {
  const allowedMimeTypes = [
    'image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif',
    'application/pdf'
  ];
  if (allowedMimeTypes.includes(file.mimetype)) {
    cb(null, true);
  } else {
    cb(new Error(`Invalid file type '${file.mimetype}'. Only images and PDFs are allowed.`));
  }
};

const upload = multer({
  storage,
  fileFilter,
  limits: { fileSize: 10 * 1024 * 1024 } // 10MB limit
});

/**
 * GET /api/media
 * Retrieve media library list. Publicly accessible for asset selection or protected for admin.
 */
router.get('/', async (req, res, next) => {
  try {
    const { search, mime_type, limit = 50, offset = 0 } = req.query;
    let queryStr = 'SELECT * FROM media WHERE 1=1';
    const params = [];

    if (search) {
      queryStr += ' AND (file_name LIKE ? OR alt_text LIKE ? OR caption LIKE ?)';
      const pattern = `%${search}%`;
      params.push(pattern, pattern, pattern);
    }

    if (mime_type) {
      queryStr += ' AND mime_type LIKE ?';
      params.push(`%${mime_type}%`);
    }

    queryStr += ' ORDER BY id DESC LIMIT ? OFFSET ?';
    params.push(parseInt(limit, 10), parseInt(offset, 10));

    const mediaList = await db.query(queryStr, params);

    res.json({
      success: true,
      count: mediaList.length,
      data: mediaList
    });
  } catch (error) {
    next(error);
  }
});

/**
 * POST /api/media/upload
 * PROTECTED - Upload new file.
 */
router.post('/upload', requireAuth, (req, res, next) => {
  upload.single('file')(req, res, async (err) => {
    if (err) {
      return res.status(400).json({ success: false, error: 'Upload Error', details: err.message });
    }

    if (!req.file) {
      return res.status(400).json({ success: false, error: 'Validation Error', details: 'No file provided in request.' });
    }

    try {
      const { alt_text, caption, description } = req.body;
      const fileName = req.file.filename;
      const filePath = `/uploads/${fileName}`;
      const mimeType = req.file.mimetype;
      const fileSize = req.file.size;

      const result = await db.query(
        `INSERT INTO media (file_name, file_path, mime_type, file_size, alt_text, caption, description)
         VALUES (?, ?, ?, ?, ?, ?, ?)`,
        [fileName, filePath, mimeType, fileSize, alt_text || null, caption || null, description || null]
      );

      const [newMedia] = await db.query('SELECT * FROM media WHERE id = ?', [result.insertId]);

      logActivity(req.user ? req.user.id : null, 'Uploaded Media', 'media', newMedia.id, { fileName, filePath });

      res.status(201).json({
        success: true,
        message: 'File uploaded successfully',
        data: newMedia
      });
    } catch (dbErr) {
      next(dbErr);
    }
  });
});

/**
 * PUT /api/media/:id
 * PROTECTED - Update media metadata (alt_text, caption, description).
 */
router.put('/:id', requireAuth, async (req, res, next) => {
  try {
    const { alt_text, caption, description } = req.body;
    const mediaId = req.params.id;

    const existing = await db.query('SELECT * FROM media WHERE id = ?', [mediaId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Media file not found' });
    }

    await db.query(
      `UPDATE media SET alt_text = ?, caption = ?, description = ? WHERE id = ?`,
      [alt_text !== undefined ? alt_text : existing[0].alt_text,
       caption !== undefined ? caption : existing[0].caption,
       description !== undefined ? description : existing[0].description,
       mediaId]
    );

    const [updated] = await db.query('SELECT * FROM media WHERE id = ?', [mediaId]);
    logActivity(req.user ? req.user.id : null, 'Updated Media Metadata', 'media', mediaId, { alt_text });

    res.json({
      success: true,
      message: 'Media metadata updated successfully',
      data: updated
    });
  } catch (error) {
    next(error);
  }
});

/**
 * DELETE /api/media/:id
 * PROTECTED - Delete file from disk & DB.
 */
router.delete('/:id', requireAuth, async (req, res, next) => {
  try {
    const mediaId = req.params.id;
    const existing = await db.query('SELECT * FROM media WHERE id = ?', [mediaId]);

    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Media file not found' });
    }

    const item = existing[0];
    const fullPath = path.join(__dirname, '../../public', item.file_path);

    if (fs.existsSync(fullPath)) {
      try {
        fs.unlinkSync(fullPath);
      } catch (e) {
        console.warn('[Media Delete Warning] Could not remove file from disk:', e.message);
      }
    }

    await db.query('DELETE FROM media WHERE id = ?', [mediaId]);
    logActivity(req.user ? req.user.id : null, 'Deleted Media', 'media', mediaId, { fileName: item.file_name });

    res.json({
      success: true,
      message: 'Media deleted successfully'
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
