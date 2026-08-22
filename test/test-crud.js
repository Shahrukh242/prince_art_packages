const http = require('http');
const app = require('../src/server');
const db = require('../src/config/db');

const PORT = 3099; // Test server port
let server;

function makeRequest(method, path, data = null, headers = {}) {
  return new Promise((resolve, reject) => {
    const payload = data ? JSON.stringify(data) : null;
    const reqHeaders = {
      'Content-Type': 'application/json',
      ...headers
    };
    if (payload) {
      reqHeaders['Content-Length'] = Buffer.byteLength(payload);
    }

    const req = http.request(
      {
        host: 'localhost',
        port: PORT,
        path,
        method,
        headers: reqHeaders
      },
      (res) => {
        let body = '';
        res.on('data', (chunk) => (body += chunk));
        res.on('end', () => {
          try {
            const parsed = JSON.parse(body);
            resolve({ statusCode: res.statusCode, body: parsed });
          } catch (e) {
            resolve({ statusCode: res.statusCode, body: body });
          }
        });
      }
    );

    req.on('error', reject);
    if (payload) req.write(payload);
    req.end();
  });
}

function assert(condition, message) {
  if (!condition) {
    throw new Error(`[Assertion Failed] ${message}`);
  }
}

async function runTests() {
  console.log('====================================================');
  console.log('     WEBSITE_CMS BACKEND & CRUD AUTOMATED TESTS     ');
  console.log('====================================================\n');

  // Start test server instance
  server = app.listen(PORT);
  console.log(`[Test Suite] Temporary test server running on port ${PORT}`);

  const testResults = [];
  const timestamp = Date.now();

  try {
    // 1. Health Check
    console.log('\n[1/9] Testing /api/health Endpoint & DB Connection...');
    const health = await makeRequest('GET', '/api/health');
    assert(health.statusCode === 200, `Health status code expected 200, got ${health.statusCode}`);
    assert(health.body.success === true, 'Health check success flag must be true');
    assert(health.body.status === 'healthy', 'Health status must be healthy');
    assert(health.body.database.name === 'website_cms', 'Database name must be website_cms');
    console.log('   ✓ Health check passed! Connected to database website_cms.');
    testResults.push({ module: 'Health & DB Ping', status: 'PASSED' });

    // 2. Users Table CRUD Lifecycle
    console.log('\n[2/9] Testing Users CRUD Lifecycle...');
    let testUserId = null;
    try {
      // Create User
      const userPayload = {
        name: `Test User ${timestamp}`,
        email: `test_user_${timestamp}@example.com`,
        password: `SecretPass123!`,
        role: 'editor'
      };
      const createRes = await makeRequest('POST', '/api/users', userPayload);
      assert(createRes.statusCode === 201, `Create user status expected 201, got ${createRes.statusCode}`);
      assert(createRes.body.data.id, 'Created user must have id');
      assert(createRes.body.data.password_hash === undefined, 'password_hash MUST NOT be exposed in response');
      testUserId = createRes.body.data.id;
      console.log(`   ✓ Created test user (ID: ${testUserId})`);

      // Read User
      const getRes = await makeRequest('GET', `/api/users/${testUserId}`);
      assert(getRes.statusCode === 200, 'Get user status expected 200');
      assert(getRes.body.data.email === userPayload.email, 'User email match');
      assert(getRes.body.data.password_hash === undefined, 'password_hash MUST NOT be in GET user response');
      console.log('   ✓ Read test user verified (password_hash hidden)');

      // Update User
      const updateRes = await makeRequest('PUT', `/api/users/${testUserId}`, { name: `Updated Name ${timestamp}` });
      assert(updateRes.statusCode === 200, 'Update user expected 200');
      assert(updateRes.body.data.name === `Updated Name ${timestamp}`, 'Updated user name match');
      assert(updateRes.body.data.password_hash === undefined, 'password_hash hidden on update');
      console.log('   ✓ Updated test user name');

      // Delete User
      const deleteRes = await makeRequest('DELETE', `/api/users/${testUserId}`);
      assert(deleteRes.statusCode === 200, 'Delete user expected 200');
      console.log('   ✓ Deleted test user');

      // Verify deletion
      const verifyRes = await makeRequest('GET', `/api/users/${testUserId}`);
      assert(verifyRes.statusCode === 404, 'Deleted user GET should return 404');
      testUserId = null;
      testResults.push({ module: 'users', status: 'PASSED' });
    } finally {
      if (testUserId) {
        await db.query('DELETE FROM users WHERE id = ?', [testUserId]);
        console.log(`   [Teardown] Force cleaned test user ID ${testUserId}`);
      }
    }

    // 3. Categories Table CRUD Lifecycle
    console.log('\n[3/9] Testing Categories CRUD Lifecycle...');
    let testCatId = null;
    try {
      const catRes = await makeRequest('POST', '/api/categories', {
        name: `Test Cat ${timestamp}`,
        slug: `test-cat-${timestamp}`,
        description: 'Test category description'
      });
      assert(catRes.statusCode === 201, 'Create category status 201');
      testCatId = catRes.body.data.id;
      console.log(`   ✓ Created category (ID: ${testCatId})`);

      const getCat = await makeRequest('GET', `/api/categories/${testCatId}`);
      assert(getCat.body.data.name === `Test Cat ${timestamp}`, 'Category name match');

      const updateCat = await makeRequest('PUT', `/api/categories/${testCatId}`, { description: 'Updated desc' });
      assert(updateCat.body.data.description === 'Updated desc', 'Category update match');

      const delCat = await makeRequest('DELETE', `/api/categories/${testCatId}`);
      assert(delCat.statusCode === 200, 'Delete category 200');
      testCatId = null;
      console.log('   ✓ Category CRUD & Teardown complete');
      testResults.push({ module: 'categories', status: 'PASSED' });
    } finally {
      if (testCatId) await db.query('DELETE FROM categories WHERE id = ?', [testCatId]);
    }

    // 4. Pages Table CRUD Lifecycle
    console.log('\n[4/9] Testing Pages CRUD Lifecycle...');
    let testPageId = null;
    try {
      const pageRes = await makeRequest('POST', '/api/pages', {
        title: `Test Page ${timestamp}`,
        slug: `test-page-${timestamp}`,
        content: '<p>Test page content</p>',
        status: 'draft'
      });
      assert(pageRes.statusCode === 201, 'Create page status 201');
      testPageId = pageRes.body.data.id;
      console.log(`   ✓ Created page (ID: ${testPageId})`);

      const getPage = await makeRequest('GET', `/api/pages/${testPageId}`);
      assert(getPage.body.data.slug === `test-page-${timestamp}`, 'Page slug match');

      const updatePage = await makeRequest('PUT', `/api/pages/${testPageId}`, { status: 'published' });
      assert(updatePage.body.data.status === 'published', 'Page update match');

      const delPage = await makeRequest('DELETE', `/api/pages/${testPageId}`);
      assert(delPage.statusCode === 200, 'Delete page 200');
      testPageId = null;
      console.log('   ✓ Page CRUD & Teardown complete');
      testResults.push({ module: 'pages', status: 'PASSED' });
    } finally {
      if (testPageId) await db.query('DELETE FROM pages WHERE id = ?', [testPageId]);
    }

    // 5. Products Table CRUD Lifecycle
    console.log('\n[5/9] Testing Products CRUD Lifecycle...');
    let testProdId = null;
    try {
      const prodRes = await makeRequest('POST', '/api/products', {
        name: `Test Product ${timestamp}`,
        slug: `test-prod-${timestamp}`,
        sku: `SKU-${timestamp}`,
        price: 49.99,
        stock: 100,
        status: 'published'
      });
      assert(prodRes.statusCode === 201, 'Create product status 201');
      testProdId = prodRes.body.data.id;
      console.log(`   ✓ Created product (ID: ${testProdId})`);

      const getProd = await makeRequest('GET', `/api/products/${testProdId}`);
      assert(parseFloat(getProd.body.data.price) === 49.99, 'Price match');

      const updateProd = await makeRequest('PUT', `/api/products/${testProdId}`, { stock: 150 });
      assert(updateProd.body.data.stock === 150, 'Stock update match');

      const delProd = await makeRequest('DELETE', `/api/products/${testProdId}`);
      assert(delProd.statusCode === 200, 'Delete product 200');
      testProdId = null;
      console.log('   ✓ Product CRUD & Teardown complete');
      testResults.push({ module: 'products', status: 'PASSED' });
    } finally {
      if (testProdId) await db.query('DELETE FROM products WHERE id = ?', [testProdId]);
    }

    // 6. Blog Posts Table CRUD Lifecycle
    console.log('\n[6/9] Testing Blog Posts CRUD Lifecycle...');
    let testPostId = null;
    try {
      const postRes = await makeRequest('POST', '/api/blog-posts', {
        title: `Test Blog Post ${timestamp}`,
        slug: `test-post-${timestamp}`,
        content: 'Test post body content',
        excerpt: 'Short summary',
        status: 'published'
      });
      assert(postRes.statusCode === 201, 'Create blog post status 201');
      testPostId = postRes.body.data.id;
      console.log(`   ✓ Created blog post (ID: ${testPostId})`);

      const getPost = await makeRequest('GET', `/api/blog-posts/${testPostId}`);
      assert(getPost.body.data.title === `Test Blog Post ${timestamp}`, 'Title match');

      const updatePost = await makeRequest('PUT', `/api/blog-posts/${testPostId}`, { excerpt: 'Updated excerpt' });
      assert(updatePost.body.data.excerpt === 'Updated excerpt', 'Excerpt update match');

      const delPost = await makeRequest('DELETE', `/api/blog-posts/${testPostId}`);
      assert(delPost.statusCode === 200, 'Delete post 200');
      testPostId = null;
      console.log('   ✓ Blog Post CRUD & Teardown complete');
      testResults.push({ module: 'blog_posts', status: 'PASSED' });
    } finally {
      if (testPostId) await db.query('DELETE FROM blog_posts WHERE id = ?', [testPostId]);
    }

    // 7. Media Table CRUD Lifecycle
    console.log('\n[7/9] Testing Media CRUD Lifecycle...');
    let testMediaId = null;
    try {
      const mediaRes = await makeRequest('POST', '/api/media', {
        filename: `test_file_${timestamp}.png`,
        original_name: `original_test_${timestamp}.png`,
        file_path: `/uploads/2026/08/test_file_${timestamp}.png`,
        file_type: 'image/png',
        mime_type: 'image/png',
        file_size: 10240,
        alt_text: 'Test alt image'
      });
      assert(mediaRes.statusCode === 201, 'Create media status 201');
      testMediaId = mediaRes.body.data.id;
      console.log(`   ✓ Created media entry (ID: ${testMediaId})`);

      const getMedia = await makeRequest('GET', `/api/media/${testMediaId}`);
      assert(getMedia.body.data.filename === `test_file_${timestamp}.png`, 'Filename match');

      const updateMedia = await makeRequest('PUT', `/api/media/${testMediaId}`, { alt_text: 'Updated alt text' });
      assert(updateMedia.body.data.alt_text === 'Updated alt text', 'Alt text update match');

      const delMedia = await makeRequest('DELETE', `/api/media/${testMediaId}`);
      assert(delMedia.statusCode === 200, 'Delete media 200');
      testMediaId = null;
      console.log('   ✓ Media CRUD & Teardown complete');
      testResults.push({ module: 'media', status: 'PASSED' });
    } finally {
      if (testMediaId) await db.query('DELETE FROM media WHERE id = ?', [testMediaId]);
    }

    // 8. Redirects Table CRUD Lifecycle
    console.log('\n[8/9] Testing Redirects CRUD Lifecycle...');
    let testRedirectId = null;
    try {
      const redRes = await makeRequest('POST', '/api/redirects', {
        source_url: `/old-path-${timestamp}`,
        destination_url: `/new-path-${timestamp}`,
        status_code: 301,
        active: 1
      });
      assert(redRes.statusCode === 201, 'Create redirect status 201');
      testRedirectId = redRes.body.data.id;
      console.log(`   ✓ Created redirect (ID: ${testRedirectId})`);

      const getRed = await makeRequest('GET', `/api/redirects/${testRedirectId}`);
      assert(getRed.body.data.source_url === `/old-path-${timestamp}`, 'Source URL match');

      const updateRed = await makeRequest('PUT', `/api/redirects/${testRedirectId}`, { status_code: 302 });
      assert(updateRed.body.data.status_code === 302, 'Status code update match');

      const delRed = await makeRequest('DELETE', `/api/redirects/${testRedirectId}`);
      assert(delRed.statusCode === 200, 'Delete redirect 200');
      testRedirectId = null;
      console.log('   ✓ Redirect CRUD & Teardown complete');
      testResults.push({ module: 'redirects', status: 'PASSED' });
    } finally {
      if (testRedirectId) await db.query('DELETE FROM redirects WHERE id = ?', [testRedirectId]);
    }

    // 9. Site Settings Table CRUD Lifecycle
    console.log('\n[9/9] Testing Site Settings CRUD Lifecycle...');
    let testSettingId = null;
    try {
      const setRes = await makeRequest('POST', '/api/site-settings', {
        setting_key: `test_key_${timestamp}`,
        setting_value: 'Test Setting Value',
        setting_type: 'text'
      });
      assert(setRes.statusCode === 201, 'Create site setting status 201');
      testSettingId = setRes.body.data.id;
      console.log(`   ✓ Created site setting (ID: ${testSettingId})`);

      const getSet = await makeRequest('GET', `/api/site-settings/${testSettingId}`);
      assert(getSet.body.data.setting_key === `test_key_${timestamp}`, 'Setting key match');

      const updateSet = await makeRequest('PUT', `/api/site-settings/${testSettingId}`, { setting_value: 'New Updated Value' });
      assert(updateSet.body.data.setting_value === 'New Updated Value', 'Setting value update match');

      const delSet = await makeRequest('DELETE', `/api/site-settings/${testSettingId}`);
      assert(delSet.statusCode === 200, 'Delete site setting 200');
      testSettingId = null;
      console.log('   ✓ Site Setting CRUD & Teardown complete');
      testResults.push({ module: 'site_settings', status: 'PASSED' });
    } finally {
      if (testSettingId) await db.query('DELETE FROM site_settings WHERE id = ?', [testSettingId]);
    }

    console.log('\n====================================================');
    console.log('                ALL TESTS PASSED 100%               ');
    console.log('====================================================');
    console.table(testResults);

  } catch (err) {
    console.error('\n❌ TEST RUN FAILED:', err.message);
    process.exitCode = 1;
  } finally {
    if (server) {
      server.close();
      console.log('\n[Test Suite] Server shut down.');
    }
    // Close DB pool
    await db.pool.end();
  }
}

runTests();
