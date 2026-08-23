const db = require('./db');

/**
 * Migration-safe, idempotent database initialization for website_cms.
 * Safe to execute repeatedly without dropping data, duplicating tables, or duplicating columns.
 */
async function initCmsTables() {
  console.log('[Migration] Starting idempotent CMS database schema initialization...');

  try {
    // 1. form_submissions table
    await db.query(`
      CREATE TABLE IF NOT EXISTS form_submissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        form_type VARCHAR(50) NOT NULL DEFAULT 'contact',
        name VARCHAR(191) NULL,
        email VARCHAR(191) NULL,
        phone VARCHAR(50) NULL,
        company VARCHAR(191) NULL,
        message TEXT NULL,
        submitted_data JSON NULL,
        status VARCHAR(50) NOT NULL DEFAULT 'New',
        notes TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_form_type (form_type),
        INDEX idx_status (status),
        INDEX idx_created_at (created_at)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    `);

    // 2. activity_logs table
    await db.query(`
      CREATE TABLE IF NOT EXISTS activity_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NULL,
        action VARCHAR(100) NOT NULL,
        entity_type VARCHAR(50) NULL,
        entity_id INT NULL,
        details JSON NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_user_id (user_id),
        INDEX idx_entity (entity_type, entity_id)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    `);

    // 3. media table
    await db.query(` 
      CREATE TABLE IF NOT EXISTS media (
        id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        original_name VARCHAR(255) NULL,
        file_path VARCHAR(255) NOT NULL,
        file_type VARCHAR(100) NULL,
        mime_type VARCHAR(100) NULL,
        file_size INT NULL,
        alt_text VARCHAR(255) NULL,
        uploaded_by INT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_filename (filename)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    `);

    // 4. seo_keywords table
    await db.query(`
      CREATE TABLE IF NOT EXISTS seo_keywords (
        id INT AUTO_INCREMENT PRIMARY KEY,
        keyword VARCHAR(191) NOT NULL,
        target_page VARCHAR(191) NULL,
        search_intent VARCHAR(50) DEFAULT 'Commercial',
        content_type VARCHAR(50) DEFAULT 'Page',
        status VARCHAR(50) DEFAULT 'Planned',
        priority VARCHAR(20) DEFAULT 'Medium',
        notes TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY idx_keyword (keyword)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    `);

    // 5. seo_tasks table
    await db.query(`
      CREATE TABLE IF NOT EXISTS seo_tasks (
        id INT AUTO_INCREMENT PRIMARY KEY,
        task_name VARCHAR(191) NOT NULL,
        page_id INT NULL,
        page_type VARCHAR(50) DEFAULT 'page',
        task_type VARCHAR(50) DEFAULT 'On-Page SEO',
        priority VARCHAR(20) DEFAULT 'Medium',
        status VARCHAR(50) DEFAULT 'To Do',
        assigned_user_id INT NULL,
        notes TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        completed_at DATETIME NULL,
        INDEX idx_status (status),
        INDEX idx_priority (priority)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    `);

    // 6. redirects table
    await db.query(` 
      CREATE TABLE IF NOT EXISTS redirects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        source_url VARCHAR(191) NOT NULL,
        target_url VARCHAR(255) NOT NULL,
        destination_url VARCHAR(255) NULL,
        status_code INT NOT NULL DEFAULT 301,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        active TINYINT(1) NOT NULL DEFAULT 1,
        notes TEXT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY idx_source_url (source_url),
        INDEX idx_is_active (is_active)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    `);

    // Helper function to safely add column if missing
    async function ensureColumnExists(tableName, columnName, columnDefinition) {
      const dbName = process.env.DB_NAME || 'website_cms';
      const rows = await db.query(
        `SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
         WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?`,
        [dbName, tableName, columnName]
      );
      if (rows.length === 0) {
        await db.query(`ALTER TABLE ${tableName} ADD COLUMN ${columnName} ${columnDefinition}`);
        console.log(`   ✓ Added missing column "${columnName}" to table "${tableName}"`);
      }
    }

    // Ensure missing product & blog_posts fields
    await ensureColumnExists('products', 'category_id', 'INT NULL AFTER slug');
    await ensureColumnExists('products', 'category', 'VARCHAR(191) NULL');
    await ensureColumnExists('products', 'gallery', 'JSON NULL');
    await ensureColumnExists('products', 'short_description', 'TEXT NULL');
    await ensureColumnExists('products', 'full_description', 'LONGTEXT NULL');
    await ensureColumnExists('pages', 'sections_data', 'JSON NULL');
    await ensureColumnExists('blog_posts', 'category', 'VARCHAR(191) NULL');
    await ensureColumnExists('blog_posts', 'author', 'VARCHAR(191) NULL');
    await ensureColumnExists('blog_posts', 'excerpt', 'TEXT NULL');
    // Ensure redirects table compatibility columns
    await ensureColumnExists('redirects', 'target_url', 'VARCHAR(255) NULL AFTER source_url');
    await ensureColumnExists('redirects', 'is_active', 'TINYINT(1) DEFAULT 1');
    await ensureColumnExists('redirects', 'notes', 'TEXT NULL');

    // Ensure SEO columns on content tables
    const contentTables = ['pages', 'products', 'blog_posts'];
    for (const tbl of contentTables) {
      const tblCheck = await db.query(
        `SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?`,
        [process.env.DB_NAME || 'website_cms', tbl]
      );

      if (tblCheck.length > 0) {
        await ensureColumnExists(tbl, 'focus_keyword', 'VARCHAR(191) NULL');
        await ensureColumnExists(tbl, 'meta_title', 'VARCHAR(191) NULL');
        await ensureColumnExists(tbl, 'meta_description', 'TEXT NULL');
        await ensureColumnExists(tbl, 'canonical_url', 'VARCHAR(191) NULL');
        await ensureColumnExists(tbl, 'og_title', 'VARCHAR(191) NULL');
        await ensureColumnExists(tbl, 'og_description', 'TEXT NULL');
        await ensureColumnExists(tbl, 'og_image', 'VARCHAR(191) NULL');
        await ensureColumnExists(tbl, 'noindex', 'TINYINT(1) DEFAULT 0');
      }
    }

    console.log('[Migration] Database schema initialization completed successfully!');
    return true;
  } catch (err) {
    console.error('[Migration Error] Database schema initialization failed:', err.message);
    throw err;
  }
}

if (require.main === module) {
  initCmsTables().then(() => process.exit(0)).catch(() => process.exit(1));
}

module.exports = initCmsTables;
