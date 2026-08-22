const express = require('express');
const cors = require('cors');
require('dotenv').config();

const db = require('./config/db');
const { authenticateToken } = require('./middleware/auth');
const errorHandler = require('./middleware/errorHandler');
const redirectMiddleware = require('./middleware/redirectMiddleware');

const healthRoutes = require('./routes/health');
const authRoutes = require('./routes/auth');
const userRoutes = require('./routes/users');
const pageRoutes = require('./routes/pages');
const productRoutes = require('./routes/products');
const blogPostRoutes = require('./routes/blogPosts');
const categoryRoutes = require('./routes/categories');
const mediaRoutes = require('./routes/media');
const redirectRoutes = require('./routes/redirects');
const siteSettingRoutes = require('./routes/siteSettings');
const rfqRoutes = require('./routes/rfq');
const formSubmissionRoutes = require('./routes/formSubmissions');
const publicContentRoutes = require('./routes/publicContent');
const technicalSeoRoutes = require('./routes/technicalSeo');

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(authenticateToken);

// Technical SEO Routes (sitemap.xml & robots.txt)
app.use('/', technicalSeoRoutes);

// Database-driven URL Redirect Execution Middleware
app.use(redirectMiddleware);

// Serve static dev interface if exists
app.use(express.static('public'));

// API Routes
app.use('/api/health', healthRoutes);
app.use('/api/auth', authRoutes);
app.use('/api/users', userRoutes);
app.use('/api/pages', pageRoutes);
app.use('/api/products', productRoutes);
app.use('/api/blog-posts', blogPostRoutes);
app.use('/api/categories', categoryRoutes);
app.use('/api/media', mediaRoutes);
app.use('/api/redirects', redirectRoutes);
app.use('/api/site-settings', siteSettingRoutes);
app.use('/api/rfq', rfqRoutes);
app.use('/api/forms', formSubmissionRoutes);
app.use('/api/public', publicContentRoutes);

// Root route
app.get('/', (req, res) => {
  res.json({
    message: 'Website CMS Backend API Service',
    database: process.env.DB_NAME || 'website_cms',
    endpoints: {
      sitemap: '/sitemap.xml',
      robots: '/robots.txt',
      health: '/api/health',
      auth: '/api/auth',
      users: '/api/users',
      pages: '/api/pages',
      products: '/api/products',
      blogPosts: '/api/blog-posts',
      categories: '/api/categories',
      media: '/api/media',
      redirects: '/api/redirects',
      siteSettings: '/api/site-settings'
    }
  });
});

// 404 Handler
app.use((req, res) => {
  res.status(404).json({ success: false, error: 'Endpoint not found' });
});

// Global Error Handler
app.use(errorHandler);

// Start server
if (require.main === module) {
  app.listen(PORT, async () => {
    console.log(`[CMS Server] Server running on http://localhost:${PORT}`);
    const conn = await db.checkConnection();
    if (conn.status === 'connected') {
      console.log(`[CMS Server] Connected to MySQL database "${conn.database}" at ${conn.host}:${conn.port}`);
    } else {
      console.error(`[CMS Server] Database connection warning: ${conn.error}`);
    }
  });
}

module.exports = app;
