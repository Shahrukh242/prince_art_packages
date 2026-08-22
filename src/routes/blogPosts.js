const express = require('express');
const router = express.Router();
const db = require('../config/db');
const { requireAuth } = require('../middleware/auth');
const { logActivity } = require('../middleware/activityLogger');

/**
 * GET /api/blog-posts (Admin Management list)
 */
router.get('/', async (req, res, next) => {
  try {
    const { status, category, search } = req.query;
    let queryStr = 'SELECT * FROM blog_posts WHERE 1=1';
    const params = [];

    if (status && status !== 'all') {
      queryStr += ' AND status = ?';
      params.push(status);
    }

    if (category && category !== 'all') {
      queryStr += ' AND category = ?';
      params.push(category);
    }

    if (search) {
      queryStr += ' AND (title LIKE ? OR excerpt LIKE ? OR content LIKE ?)';
      const pattern = `%${search}%`;
      params.push(pattern, pattern, pattern);
    }

    queryStr += ' ORDER BY id DESC';
    const posts = await db.query(queryStr, params);
    res.json({ success: true, count: posts.length, data: posts });
  } catch (error) {
    next(error);
  }
});

/**
 * GET /api/blog-posts/:id
 */
router.get('/:id', async (req, res, next) => {
  try {
    const posts = await db.query('SELECT * FROM blog_posts WHERE id = ? OR slug = ?', [req.params.id, req.params.id]);
    if (posts.length === 0) {
      return res.status(404).json({ success: false, error: 'Blog post not found' });
    }
    res.json({ success: true, data: posts[0] });
  } catch (error) {
    next(error);
  }
});

/**
 * POST /api/blog-posts (Protected)
 */
router.post('/', requireAuth, async (req, res, next) => {
  try {
    const { title, slug, excerpt, content, featured_image, category, author, status, meta_title, meta_description, focus_keyword, published_at } = req.body;

    if (!title || !slug) {
      return res.status(400).json({
        success: false,
        error: 'Validation Error',
        details: 'Title and slug are required.'
      });
    }

    const postStatus = status || 'draft';
    const publishedAt = published_at || (postStatus === 'published' ? new Date() : null);

    const result = await db.query(
      `INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, category, author, status, meta_title, meta_description, focus_keyword, published_at) 
       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
      [title, slug, excerpt || null, content || '', featured_image || null, category || null, author || null, postStatus, meta_title || null, meta_description || null, focus_keyword || null, publishedAt]
    );

    const [newPost] = await db.query('SELECT * FROM blog_posts WHERE id = ?', [result.insertId]);

    logActivity(req.user ? req.user.id : null, 'Created Blog Post', 'blog_posts', newPost.id, { title, status: postStatus });

    res.status(201).json({
      success: true,
      message: 'Blog post created successfully',
      data: newPost
    });
  } catch (error) {
    next(error);
  }
});

/**
 * PUT /api/blog-posts/:id (Protected)
 */
router.put('/:id', requireAuth, async (req, res, next) => {
  try {
    const postId = req.params.id;
    const { title, slug, excerpt, content, featured_image, category, author, status, meta_title, meta_description, focus_keyword, published_at } = req.body;

    const existing = await db.query('SELECT id FROM blog_posts WHERE id = ?', [postId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Blog post not found' });
    }

    const fieldsToUpdate = [];
    const params = [];

    if (title !== undefined) { fieldsToUpdate.push('title = ?'); params.push(title); }
    if (slug !== undefined) { fieldsToUpdate.push('slug = ?'); params.push(slug); }
    if (excerpt !== undefined) { fieldsToUpdate.push('excerpt = ?'); params.push(excerpt); }
    if (content !== undefined) { fieldsToUpdate.push('content = ?'); params.push(content); }
    if (featured_image !== undefined) { fieldsToUpdate.push('featured_image = ?'); params.push(featured_image); }
    if (category !== undefined) { fieldsToUpdate.push('category = ?'); params.push(category); }
    if (author !== undefined) { fieldsToUpdate.push('author = ?'); params.push(author); }
    if (status !== undefined) { fieldsToUpdate.push('status = ?'); params.push(status); }
    if (meta_title !== undefined) { fieldsToUpdate.push('meta_title = ?'); params.push(meta_title); }
    if (meta_description !== undefined) { fieldsToUpdate.push('meta_description = ?'); params.push(meta_description); }
    if (focus_keyword !== undefined) { fieldsToUpdate.push('focus_keyword = ?'); params.push(focus_keyword); }
    if (published_at !== undefined) { fieldsToUpdate.push('published_at = ?'); params.push(published_at); }

    if (fieldsToUpdate.length === 0) {
      return res.status(400).json({ success: false, error: 'No fields provided for update' });
    }

    const queryStr = `UPDATE blog_posts SET ${fieldsToUpdate.join(', ')} WHERE id = ?`;
    params.push(postId);

    await db.query(queryStr, params);

    const [updatedPost] = await db.query('SELECT * FROM blog_posts WHERE id = ?', [postId]);

    logActivity(req.user ? req.user.id : null, 'Updated Blog Post', 'blog_posts', postId, { title, status });

    res.json({
      success: true,
      message: 'Blog post updated successfully',
      data: updatedPost
    });
  } catch (error) {
    next(error);
  }
});

/**
 * DELETE /api/blog-posts/:id (Protected)
 */
router.delete('/:id', requireAuth, async (req, res, next) => {
  try {
    const postId = req.params.id;
    const existing = await db.query('SELECT id FROM blog_posts WHERE id = ?', [postId]);
    if (existing.length === 0) {
      return res.status(404).json({ success: false, error: 'Blog post not found' });
    }

    await db.query('DELETE FROM blog_posts WHERE id = ?', [postId]);
    logActivity(req.user ? req.user.id : null, 'Deleted Blog Post', 'blog_posts', postId);

    res.json({
      success: true,
      message: 'Blog post deleted successfully',
      id: parseInt(postId, 10)
    });
  } catch (error) {
    next(error);
  }
});

module.exports = router;
