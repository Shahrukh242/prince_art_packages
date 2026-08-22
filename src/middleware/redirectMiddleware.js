const db = require('../config/db');

/**
 * Express Middleware to execute database-driven URL redirects.
 * Intercepts incoming requests before 404 / static assets.
 */
async function redirectMiddleware(req, res, next) {
  // Skip API routes, static asset files, sitemap, robots, and admin interface
  if (
    req.path.startsWith('/api') ||
    req.path.startsWith('/admin') ||
    req.path === '/sitemap.xml' ||
    req.path === '/robots.txt' ||
    req.path.match(/\.(css|js|jpg|jpeg|png|webp|svg|pdf|ico|woff|woff2|ttf|eot)$/i)
  ) {
    return next();
  }

  try {
    const rows = await db.query(
      'SELECT target_url, destination_url, status_code FROM redirects WHERE source_url = ? AND (is_active = 1 OR active = 1) LIMIT 1',
      [req.path]
    );

    if (rows.length > 0) {
      const { target_url, destination_url, status_code } = rows[0];
      const target = target_url || destination_url;
      const code = parseInt(status_code, 10) || 301;
      if (target) {
        return res.redirect(code, target);
      }
    }
  } catch (err) {
    console.warn('[Redirect Middleware Warning]:', err.message);
  }

  next();
}

module.exports = redirectMiddleware;
