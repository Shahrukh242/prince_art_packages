/**
 * Prince Art Packages (Pvt) Ltd - CMS Admin Studio Application Script (Phase 1, 2, 3 & 4 Redirects)
 */

document.addEventListener('DOMContentLoaded', () => {
  AdminApp.init();
});

const AdminApp = {
  token: localStorage.getItem('pap_admin_token') || '',
  currentUser: null,
  submissionsCache: [],
  productsCache: [],
  pagesCache: [],
  blogsCache: [],
  categoriesCache: [],
  mediaCache: [],
  redirectsCache: [],

  async init() {
    this.bindLogin();
    this.bindNavigation();
    this.bindModal();
    this.bindProductEditor();
    this.bindBlogEditor();
    this.bindCategoryEditor();
    this.bindMediaUploader();
    this.bindSEOEditor();
    this.bindRedirectEditor();
    this.bindLiveClock();

    if (this.token) {
      await this.verifyAuth();
    } else {
      this.showLogin();
    }
  },

  showLogin() {
    document.getElementById('admin-login-screen').style.display = 'flex';
    document.getElementById('admin-app').style.display = 'none';
  },

  showApp() {
    document.getElementById('admin-login-screen').style.display = 'none';
    document.getElementById('admin-app').style.display = 'flex';
    this.loadDashboardData();
  },

  async verifyAuth() {
    try {
      const res = await fetch('/api/auth/me', {
        headers: { 'Authorization': `Bearer ${this.token}` }
      });
      const json = await res.json();

      if (json.success && json.user) {
        this.currentUser = json.user;
        this.updateUserUI(json.user);
        this.showApp();
      } else {
        this.logout();
      }
    } catch (err) {
      console.warn('[Auth Verify Error]:', err.message);
      this.logout();
    }
  },

  updateUserUI(user) {
    document.getElementById('user-display-name').textContent = user.name || user.email;
    document.getElementById('user-display-role').textContent = user.role || 'Admin';
    document.getElementById('user-avatar-initials').textContent = (user.name || user.email).charAt(0).toUpperCase();
  },

  bindLogin() {
    const form = document.getElementById('admin-login-form');
    const alertBox = document.getElementById('login-alert');

    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      alertBox.style.display = 'none';

      const email = form.email.value.trim();
      const password = form.password.value.trim();

      try {
        const res = await fetch('/api/auth/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, password })
        });
        const json = await res.json();

        if (json.success && json.token) {
          this.token = json.token;
          localStorage.setItem('pap_admin_token', json.token);
          this.currentUser = json.user;
          this.updateUserUI(json.user);
          form.reset();
          this.showApp();
        } else {
          alertBox.textContent = json.details || json.error || 'Invalid email or password.';
          alertBox.style.display = 'block';
        }
      } catch (err) {
        alertBox.textContent = 'Connection error. Please try again.';
        alertBox.style.display = 'block';
      }
    });

    document.getElementById('btn-admin-logout').addEventListener('click', () => this.logout());
  },

  logout() {
    this.token = '';
    this.currentUser = null;
    localStorage.removeItem('pap_admin_token');
    this.showLogin();
  },

  bindNavigation() {
    const navItems = document.querySelectorAll('.sidebar-nav a[data-view]');
    navItems.forEach(item => {
      item.addEventListener('click', (e) => {
        e.preventDefault();
        const viewId = item.dataset.view;
        const filterType = item.dataset.filterType;

        navItems.forEach(i => i.classList.remove('active'));
        item.classList.add('active');

        this.switchView(viewId, filterType);

        // Auto close mobile sidebar on navigation click
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) sidebar.classList.remove('mobile-open');
      });
    });

    const toggleSidebarBtn = document.getElementById('btn-toggle-sidebar');
    const sidebar = document.querySelector('.sidebar');
    if (toggleSidebarBtn && sidebar) {
      toggleSidebarBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('mobile-open');
      });

      document.addEventListener('click', (e) => {
        if (!e.target.closest('.sidebar') && !e.target.closest('#btn-toggle-sidebar')) {
          sidebar.classList.remove('mobile-open');
        }
      });
    }

    document.querySelectorAll('.metric-card').forEach(card => {
      card.addEventListener('click', () => {
        const viewTarget = card.dataset.viewTarget;
        const filter = card.dataset.filter;
        if (viewTarget) this.switchView(viewTarget, filter);
      });
    });

    document.getElementById('btn-view-all-leads').addEventListener('click', () => {
      this.switchView('submissions', 'all');
    });

    // View specific controls
    document.getElementById('submissions-search').addEventListener('input', () => this.filterSubmissionsTable());
    document.getElementById('submissions-status-select').addEventListener('change', () => this.filterSubmissionsTable());
    document.getElementById('btn-refresh-submissions').addEventListener('click', () => this.loadSubmissionsData());

    document.getElementById('btn-add-product').addEventListener('click', () => this.openProductModal());
    document.getElementById('products-search').addEventListener('input', () => this.renderProductsTable());

    document.getElementById('btn-add-blog').addEventListener('click', () => this.openBlogModal());
    document.getElementById('blogs-search').addEventListener('input', () => this.renderBlogsTable());

    document.getElementById('btn-add-category').addEventListener('click', () => this.openCategoryModal());

    document.getElementById('btn-refresh-media').addEventListener('click', () => this.loadMediaData());

    document.getElementById('seo-search-input')?.addEventListener('input', () => this.renderSEOTable());
    document.getElementById('seo-filter-type')?.addEventListener('change', () => this.renderSEOTable());
    document.getElementById('btn-refresh-seo')?.addEventListener('click', () => this.loadSEOData());

    document.getElementById('btn-add-redirect')?.addEventListener('click', () => this.openRedirectModal());
    document.getElementById('redirects-search')?.addEventListener('input', () => this.renderRedirectsTable());
    document.getElementById('btn-refresh-redirects')?.addEventListener('click', () => this.loadRedirectsData());
  },

  switchView(viewId, filterType = 'all') {
    document.querySelectorAll('.content-view').forEach(v => v.classList.remove('active'));
    
    const targetView = document.getElementById(`view-${viewId}`);
    if (targetView) targetView.classList.add('active');

    const titleMap = {
      'dashboard': 'Dashboard Overview',
      'submissions': 'Leads & Form Submissions',
      'pages': 'Pages Management',
      'products': 'Product Catalog Management',
      'blogs': 'Blog & Insights Management',
      'categories': 'Category Management',
      'media': 'Media Assets Library',
      'seo': 'SEO Optimization Workspace',
      'redirects': '301 / 302 URL Redirect Management'
    };
    document.getElementById('view-title').textContent = titleMap[viewId] || 'Management Studio';

    if (viewId === 'submissions') this.loadSubmissionsData();
    else if (viewId === 'products') this.loadProductsData();
    else if (viewId === 'blogs') this.loadBlogsData();
    else if (viewId === 'pages') this.loadPagesData();
    else if (viewId === 'categories') this.loadCategoriesData();
    else if (viewId === 'media') this.loadMediaData();
    else if (viewId === 'seo') this.loadSEOData();
    else if (viewId === 'redirects') this.loadRedirectsData();
    else if (viewId === 'dashboard') this.loadDashboardData();
  },

  async loadDashboardData() {
    await Promise.all([
      this.loadSubmissionsData(),
      this.loadProductsData(),
      this.loadBlogsData(),
      this.loadPagesData()
    ]);
    this.updateDashboardMetrics();
  },

  async loadSubmissionsData() {
    try {
      const res = await fetch('/api/forms/submissions', {
        headers: { 'Authorization': `Bearer ${this.token}` }
      });
      const json = await res.json();

      if (json.success && Array.isArray(json.data)) {
        this.submissionsCache = json.data;
        this.updateDashboardMetrics();
        this.renderRecentSubmissionsTable();
        this.filterSubmissionsTable();
      }
    } catch (err) {
      console.warn('[Load Submissions Error]:', err.message);
    }
  },

  updateDashboardMetrics() {
    const posts = this.submissionsCache;
    const newLeads = posts.filter(p => p.status === 'New').length;
    const total = posts.length;

    document.getElementById('stat-new-leads').textContent = newLeads;
    document.getElementById('stat-total-submissions').textContent = total;
    document.getElementById('badge-new-leads-count').textContent = newLeads;

    const pubProducts = this.productsCache.filter(p => p.status === 'published').length;
    const pubBlogs = this.blogsCache.filter(b => b.status === 'published').length;

    if (document.getElementById('stat-products-count')) {
      document.getElementById('stat-products-count').textContent = pubProducts;
    }
    if (document.getElementById('stat-blogs-count')) {
      document.getElementById('stat-blogs-count').textContent = pubBlogs;
    }
  },

  renderRecentSubmissionsTable() {
    const tbody = document.getElementById('recent-submissions-tbody');
    if (!tbody) return;

    const recent = this.submissionsCache.slice(0, 5);

    if (recent.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" class="text-center padding-lg">No lead submissions received yet.</td></tr>`;
      return;
    }

    tbody.innerHTML = recent.map(item => `
      <tr>
        <td><strong>#${item.id}</strong></td>
        <td><span class="status-pill status-read">${(item.form_type || 'Contact').toUpperCase()}</span></td>
        <td><strong>${item.name || 'Anonymous'}</strong></td>
        <td>${item.company ? item.company + '<br>' : ''}<span style="color:var(--admin-text-muted); font-size:0.8rem;">${item.email || ''}</span></td>
        <td>${new Date(item.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
        <td><span class="status-pill status-${(item.status || 'New').toLowerCase()}">${item.status || 'New'}</span></td>
        <td>
          <button class="admin-btn admin-btn-sm admin-btn-outline btn-view-lead" data-id="${item.id}">View Lead</button>
        </td>
      </tr>
    `).join('');

    tbody.querySelectorAll('.btn-view-lead').forEach(btn => {
      btn.addEventListener('click', () => this.openLeadModal(parseInt(btn.dataset.id, 10)));
    });
  },

  filterSubmissionsTable() {
    const tbody = document.getElementById('submissions-tbody');
    if (!tbody) return;

    const activeTab = document.querySelector('.tab-btn.active');
    const formFilter = activeTab ? activeTab.dataset.formFilter : 'all';
    const statusFilter = document.getElementById('submissions-status-select').value;
    const searchQuery = document.getElementById('submissions-search').value.toLowerCase().trim();

    let filtered = this.submissionsCache;

    if (formFilter !== 'all') {
      filtered = filtered.filter(item => (item.form_type || '').toLowerCase() === formFilter.toLowerCase());
    }

    if (statusFilter !== 'all') {
      filtered = filtered.filter(item => (item.status || '').toLowerCase() === statusFilter.toLowerCase());
    }

    if (searchQuery) {
      filtered = filtered.filter(item => 
        (item.name || '').toLowerCase().includes(searchQuery) ||
        (item.email || '').toLowerCase().includes(searchQuery) ||
        (item.company || '').toLowerCase().includes(searchQuery) ||
        (item.message || '').toLowerCase().includes(searchQuery)
      );
    }

    if (filtered.length === 0) {
      tbody.innerHTML = `<tr><td colspan="8" class="text-center padding-lg">No matching form submissions found.</td></tr>`;
      return;
    }

    tbody.innerHTML = filtered.map(item => `
      <tr>
        <td><strong>#${item.id}</strong></td>
        <td><span class="status-pill status-read">${(item.form_type || 'Contact').toUpperCase()}</span></td>
        <td><strong>${item.name || 'N/A'}</strong></td>
        <td>${item.email || 'N/A'}<br><span style="color:var(--admin-text-muted); font-size:0.8rem;">${item.phone || ''}</span></td>
        <td>${item.company || '-'}</td>
        <td>${new Date(item.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
        <td><span class="status-pill status-${(item.status || 'New').toLowerCase()}">${item.status || 'New'}</span></td>
        <td>
          <button class="admin-btn admin-btn-sm admin-btn-primary btn-view-lead" data-id="${item.id}">Inspect &rarr;</button>
        </td>
      </tr>
    `).join('');

    tbody.querySelectorAll('.btn-view-lead').forEach(btn => {
      btn.addEventListener('click', () => this.openLeadModal(parseInt(btn.dataset.id, 10)));
    });
  },

  // ==================== PRODUCTS MANAGEMENT ====================
  async loadProductsData() {
    try {
      const res = await fetch('/api/products');
      const json = await res.json();
      if (json.success && Array.isArray(json.data)) {
        this.productsCache = json.data;
        this.renderProductsTable();
      }
    } catch (err) {
      console.warn('[Load Products Error]:', err.message);
    }
  },

  renderProductsTable() {
    const tbody = document.getElementById('products-tbody');
    if (!tbody) return;

    const query = (document.getElementById('products-search')?.value || '').toLowerCase().trim();
    const filtered = query ? this.productsCache.filter(p => p.name.toLowerCase().includes(query)) : this.productsCache;

    if (filtered.length === 0) {
      tbody.innerHTML = `<tr><td colspan="5" class="text-center padding-lg">No products found.</td></tr>`;
      return;
    }

    tbody.innerHTML = filtered.map(p => `
      <tr>
        <td><img src="${p.featured_image || 'images/prod_cartons.jpg'}" alt="${p.name}" style="width:48px; height:48px; object-fit:cover; border-radius:4px;"></td>
        <td><strong>${p.name}</strong><br><span style="color:var(--admin-text-muted); font-size:0.8rem;">/${p.slug}</span></td>
        <td>${p.category || 'General'}</td>
        <td><span class="status-pill status-${p.status === 'published' ? 'published' : 'draft'}">${p.status}</span></td>
        <td>
          <button class="admin-btn admin-btn-sm admin-btn-outline btn-edit-product" data-id="${p.id}">Edit</button>
          <button class="admin-btn admin-btn-sm admin-btn-outline btn-toggle-prod-status" data-id="${p.id}" data-status="${p.status}">${p.status === 'published' ? 'Unpublish' : 'Publish'}</button>
        </td>
      </tr>
    `).join('');

    tbody.querySelectorAll('.btn-edit-product').forEach(b => {
      b.addEventListener('click', () => this.openProductModal(parseInt(b.dataset.id, 10)));
    });

    tbody.querySelectorAll('.btn-toggle-prod-status').forEach(b => {
      b.addEventListener('click', async () => {
        const id = b.dataset.id;
        const current = b.dataset.status;
        const nextStatus = current === 'published' ? 'draft' : 'published';
        await fetch(`/api/products/${id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${this.token}` },
          body: JSON.stringify({ status: nextStatus })
        });
        await this.loadProductsData();
      });
    });
  },

  bindProductEditor() {
    const modal = document.getElementById('product-modal');
    const closeBtn = document.getElementById('product-modal-close');
    const form = document.getElementById('product-edit-form');
    const deleteBtn = document.getElementById('btn-delete-product');

    if (!modal) return;

    closeBtn.addEventListener('click', () => modal.style.display = 'none');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('prod-id').value;
      const payload = {
        name: document.getElementById('prod-name').value.trim(),
        slug: document.getElementById('prod-slug').value.trim(),
        category: document.getElementById('prod-category').value.trim(),
        status: document.getElementById('prod-status').value,
        featured_image: document.getElementById('prod-image').value.trim(),
        short_description: document.getElementById('prod-short-desc').value.trim(),
        full_description: document.getElementById('prod-full-desc').value.trim()
      };

      const url = id ? `/api/products/${id}` : '/api/products';
      const method = id ? 'PUT' : 'POST';

      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${this.token}` },
        body: JSON.stringify(payload)
      });
      const json = await res.json();

      if (json.success) {
        modal.style.display = 'none';
        await this.loadProductsData();
      }
    });

    deleteBtn.addEventListener('click', async () => {
      const id = document.getElementById('prod-id').value;
      if (!id || !confirm('Delete product permanently?')) return;
      await fetch(`/api/products/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${this.token}` }
      });
      modal.style.display = 'none';
      await this.loadProductsData();
    });
  },

  openProductModal(id = null) {
    const modal = document.getElementById('product-modal');
    document.getElementById('product-modal-title').textContent = id ? 'Edit Product' : 'Add New Product';
    document.getElementById('btn-delete-product').style.display = id ? 'inline-flex' : 'none';

    if (id) {
      const p = this.productsCache.find(x => x.id === id);
      if (p) {
        document.getElementById('prod-id').value = p.id;
        document.getElementById('prod-name').value = p.name || '';
        document.getElementById('prod-slug').value = p.slug || '';
        document.getElementById('prod-category').value = p.category || '';
        document.getElementById('prod-status').value = p.status || 'published';
        document.getElementById('prod-image').value = p.featured_image || '';
        document.getElementById('prod-short-desc').value = p.short_description || '';
        document.getElementById('prod-full-desc').value = p.full_description || '';
      }
    } else {
      document.getElementById('product-edit-form').reset();
      document.getElementById('prod-id').value = '';
    }

    modal.style.display = 'flex';
  },

  // ==================== BLOGS MANAGEMENT ====================
  async loadBlogsData() {
    try {
      const res = await fetch('/api/blog-posts');
      const json = await res.json();
      if (json.success && Array.isArray(json.data)) {
        this.blogsCache = json.data;
        this.renderBlogsTable();
      }
    } catch (err) {
      console.warn('[Load Blogs Error]:', err.message);
    }
  },

  renderBlogsTable() {
    const tbody = document.getElementById('blogs-tbody');
    if (!tbody) return;

    const query = (document.getElementById('blogs-search')?.value || '').toLowerCase().trim();
    const filtered = query ? this.blogsCache.filter(b => b.title.toLowerCase().includes(query)) : this.blogsCache;

    if (filtered.length === 0) {
      tbody.innerHTML = `<tr><td colspan="6" class="text-center padding-lg">No blog articles found.</td></tr>`;
      return;
    }

    tbody.innerHTML = filtered.map(b => `
      <tr>
        <td><img src="${b.featured_image || 'images/facility.jpg'}" alt="${b.title}" style="width:48px; height:48px; object-fit:cover; border-radius:4px;"></td>
        <td><strong>${b.title}</strong><br><span style="color:var(--admin-text-muted); font-size:0.8rem;">/${b.slug}</span></td>
        <td>${b.category || 'Quality'}</td>
        <td>${b.author || 'Prince Art Team'}</td>
        <td><span class="status-pill status-${b.status === 'published' ? 'published' : 'draft'}">${b.status}</span></td>
        <td>
          <button class="admin-btn admin-btn-sm admin-btn-outline btn-edit-blog" data-id="${b.id}">Edit</button>
        </td>
      </tr>
    `).join('');

    tbody.querySelectorAll('.btn-edit-blog').forEach(b => {
      b.addEventListener('click', () => this.openBlogModal(parseInt(b.dataset.id, 10)));
    });
  },

  bindBlogEditor() {
    const modal = document.getElementById('blog-modal-editor');
    const closeBtn = document.getElementById('blog-modal-editor-close');
    const form = document.getElementById('blog-edit-form');
    const deleteBtn = document.getElementById('btn-delete-blog');

    if (!modal) return;

    closeBtn.addEventListener('click', () => modal.style.display = 'none');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('blog-id').value;
      const payload = {
        title: document.getElementById('blog-title').value.trim(),
        slug: document.getElementById('blog-slug').value.trim(),
        category: document.getElementById('blog-category').value.trim(),
        author: document.getElementById('blog-author').value.trim(),
        status: document.getElementById('blog-status').value,
        featured_image: document.getElementById('blog-image').value.trim(),
        excerpt: document.getElementById('blog-excerpt').value.trim(),
        content: document.getElementById('blog-content').value.trim()
      };

      const url = id ? `/api/blog-posts/${id}` : '/api/blog-posts';
      const method = id ? 'PUT' : 'POST';

      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${this.token}` },
        body: JSON.stringify(payload)
      });
      const json = await res.json();

      if (json.success) {
        modal.style.display = 'none';
        await this.loadBlogsData();
      }
    });

    deleteBtn.addEventListener('click', async () => {
      const id = document.getElementById('blog-id').value;
      if (!id || !confirm('Delete article permanently?')) return;
      await fetch(`/api/blog-posts/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${this.token}` }
      });
      modal.style.display = 'none';
      await this.loadBlogsData();
    });
  },

  openBlogModal(id = null) {
    const modal = document.getElementById('blog-modal-editor');
    document.getElementById('blog-modal-title').textContent = id ? 'Edit Article' : 'Publish New Article';
    document.getElementById('btn-delete-blog').style.display = id ? 'inline-flex' : 'none';

    if (id) {
      const b = this.blogsCache.find(x => x.id === id);
      if (b) {
        document.getElementById('blog-id').value = b.id;
        document.getElementById('blog-title').value = b.title || '';
        document.getElementById('blog-slug').value = b.slug || '';
        document.getElementById('blog-category').value = b.category || '';
        document.getElementById('blog-author').value = b.author || '';
        document.getElementById('blog-status').value = b.status || 'published';
        document.getElementById('blog-image').value = b.featured_image || '';
        document.getElementById('blog-excerpt').value = b.excerpt || '';
        document.getElementById('blog-content').value = b.content || '';
      }
    } else {
      document.getElementById('blog-edit-form').reset();
      document.getElementById('blog-id').value = '';
    }

    modal.style.display = 'flex';
  },

  // ==================== PAGES & CATEGORIES ====================
  async loadPagesData() {
    try {
      const res = await fetch('/api/pages');
      const json = await res.json();
      if (json.success && Array.isArray(json.data)) {
        this.pagesCache = json.data;
        const tbody = document.getElementById('pages-tbody');
        if (tbody) {
          tbody.innerHTML = json.data.map(p => `
            <tr>
              <td>#${p.id}</td>
              <td><strong>${p.title}</strong></td>
              <td>/${p.slug}</td>
              <td><span class="status-pill status-${p.status === 'published' ? 'published' : 'draft'}">${p.status}</span></td>
              <td>${new Date(p.created_at).toLocaleDateString()}</td>
              <td><span style="font-size:0.8rem; color:var(--admin-teal);">Protected Design Page</span></td>
            </tr>
          `).join('');
        }
      }
    } catch (e) {}
  },

  async loadCategoriesData() {
    try {
      const res = await fetch('/api/categories');
      const json = await res.json();
      if (json.success && Array.isArray(json.data)) {
        this.categoriesCache = json.data;
        const tbody = document.getElementById('categories-tbody');
        if (tbody) {
          tbody.innerHTML = json.data.map(c => `
            <tr>
              <td>#${c.id}</td>
              <td><strong>${c.name}</strong></td>
              <td>${c.slug}</td>
              <td>${c.description || '-'}</td>
              <td>
                <button class="admin-btn admin-btn-sm admin-btn-outline btn-edit-cat" data-id="${c.id}">Edit</button>
                <button class="admin-btn admin-btn-sm admin-btn-danger btn-delete-cat" data-id="${c.id}">Delete</button>
              </td>
            </tr>
          `).join('');

          tbody.querySelectorAll('.btn-edit-cat').forEach(b => {
            b.addEventListener('click', () => this.openCategoryModal(parseInt(b.dataset.id, 10)));
          });
          tbody.querySelectorAll('.btn-delete-cat').forEach(b => {
            b.addEventListener('click', async () => {
              if (!confirm('Delete category permanently?')) return;
              await fetch(`/api/categories/${b.dataset.id}`, {
                method: 'DELETE',
                headers: { 'Authorization': `Bearer ${this.token}` }
              });
              await this.loadCategoriesData();
            });
          });
        }
      }
    } catch (e) {}
  },

  bindCategoryEditor() {
    const modal = document.getElementById('category-modal');
    const closeBtn = document.getElementById('category-modal-close');
    const form = document.getElementById('category-edit-form');
    const deleteBtn = document.getElementById('btn-delete-category');

    if (!modal) return;

    closeBtn.addEventListener('click', () => modal.style.display = 'none');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('cat-id').value;
      const payload = {
        name: document.getElementById('cat-name').value.trim(),
        slug: document.getElementById('cat-slug').value.trim(),
        description: document.getElementById('cat-description').value.trim()
      };

      const url = id ? `/api/categories/${id}` : '/api/categories';
      const method = id ? 'PUT' : 'POST';

      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${this.token}` },
        body: JSON.stringify(payload)
      });
      const json = await res.json();

      if (json.success) {
        modal.style.display = 'none';
        await this.loadCategoriesData();
      } else {
        alert(json.details || json.error || 'Failed to save category.');
      }
    });

    deleteBtn.addEventListener('click', async () => {
      const id = document.getElementById('cat-id').value;
      if (!id || !confirm('Delete category permanently?')) return;
      await fetch(`/api/categories/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${this.token}` }
      });
      modal.style.display = 'none';
      await this.loadCategoriesData();
    });
  },

  openCategoryModal(id = null) {
    const modal = document.getElementById('category-modal');
    document.getElementById('category-modal-title').textContent = id ? 'Edit Category' : 'Create Category';
    document.getElementById('btn-delete-category').style.display = id ? 'inline-flex' : 'none';

    if (id) {
      const c = this.categoriesCache.find(x => x.id === id);
      if (c) {
        document.getElementById('cat-id').value = c.id;
        document.getElementById('cat-name').value = c.name || '';
        document.getElementById('cat-slug').value = c.slug || '';
        document.getElementById('cat-description').value = c.description || '';
      }
    } else {
      document.getElementById('category-edit-form').reset();
      document.getElementById('cat-id').value = '';
    }

    modal.style.display = 'flex';
  },

  // ==================== MEDIA LIBRARY ====================
  async loadMediaData() {
    try {
      const res = await fetch('/api/media');
      const json = await res.json();
      if (json.success && Array.isArray(json.data)) {
        this.mediaCache = json.data;
        const grid = document.getElementById('media-grid-container');
        if (grid) {
          grid.innerHTML = json.data.map(m => `
            <div class="media-item-card">
              <div class="media-img-box">
                <img src="${m.file_path}" alt="${m.alt_text || m.filename}">
              </div>
              <div class="media-item-info">
                <div class="media-item-title">${m.filename}</div>
                <button class="admin-btn admin-btn-sm admin-btn-outline btn-copy-url" data-url="${m.file_path}">Copy URL</button>
              </div>
            </div>
          `).join('');

          grid.querySelectorAll('.btn-copy-url').forEach(b => {
            b.addEventListener('click', () => {
              navigator.clipboard.writeText(window.location.origin + b.dataset.url);
              alert(`Copied URL: ${b.dataset.url}`);
            });
          });
        }
      }
    } catch (e) {}
  },

  bindMediaUploader() {
    const form = document.getElementById('media-upload-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const fileInput = document.getElementById('media-file-input');
      if (!fileInput.files.length) return;

      const formData = new FormData();
      formData.append('file', fileInput.files[0]);

      try {
        const res = await fetch('/api/media/upload', {
          method: 'POST',
          headers: { 'Authorization': `Bearer ${this.token}` },
          body: formData
        });
        const json = await res.json();
        if (json.success) {
          form.reset();
          await this.loadMediaData();
        }
      } catch (err) {
        alert('File upload failed.');
      }
    });
  },

  // ==================== PHASE 3: SEO CHECKLIST & WORKSPACE ====================
  evaluateSEO(item = {}) {
    const kw = (item.focus_keyword || '').toLowerCase().trim();
    const title = (item.meta_title || item.name || item.title || '').trim();
    const desc = (item.meta_description || item.short_description || item.excerpt || '').trim();
    const content = (item.full_description || item.content || '').toLowerCase();
    const canonical = (item.canonical_url || '').trim();
    const ogImg = (item.og_image || item.featured_image || '').trim();
    const isNoindex = item.noindex === 1 || item.noindex === true;

    const checks = [];

    if (kw) {
      checks.push({ rule: 'Focus Keyword Configured', status: 'PASS', text: `Focus Keyword: "${kw}"` });
    } else {
      checks.push({ rule: 'Focus Keyword Configured', status: 'ATTENTION', text: 'Focus Keyword is missing.' });
    }

    if (title.length >= 30 && title.length <= 60) {
      checks.push({ rule: 'SEO Title Length', status: 'PASS', text: `Good SEO Title length (${title.length} chars)` });
    } else if (title.length > 0) {
      checks.push({ rule: 'SEO Title Length', status: 'WARNING', text: `SEO Title length (${title.length} chars) - recommended 30–60 chars` });
    } else {
      checks.push({ rule: 'SEO Title Length', status: 'ATTENTION', text: 'SEO Title is missing.' });
    }

    if (desc.length >= 70 && desc.length <= 160) {
      checks.push({ rule: 'Meta Description Length', status: 'PASS', text: `Good Meta Description length (${desc.length} chars)` });
    } else if (desc.length > 0) {
      checks.push({ rule: 'Meta Description Length', status: 'WARNING', text: `Meta Description length (${desc.length} chars) - recommended 70–160 chars` });
    } else {
      checks.push({ rule: 'Meta Description Length', status: 'ATTENTION', text: 'Meta Description is missing.' });
    }

    if (kw && title.toLowerCase().includes(kw)) {
      checks.push({ rule: 'Keyword in Title', status: 'PASS', text: 'Focus keyword appears in SEO Title.' });
    } else if (kw) {
      checks.push({ rule: 'Keyword in Title', status: 'WARNING', text: 'Focus keyword does not appear in SEO Title.' });
    } else {
      checks.push({ rule: 'Keyword in Title', status: 'ATTENTION', text: 'Focus keyword missing.' });
    }

    if (kw && desc.toLowerCase().includes(kw)) {
      checks.push({ rule: 'Keyword in Meta Description', status: 'PASS', text: 'Focus keyword appears in Meta Description.' });
    } else if (kw) {
      checks.push({ rule: 'Keyword in Meta Description', status: 'WARNING', text: 'Focus keyword does not appear in Meta Description.' });
    } else {
      checks.push({ rule: 'Keyword in Meta Description', status: 'ATTENTION', text: 'Focus keyword missing.' });
    }

    if (kw && content.includes(kw)) {
      checks.push({ rule: 'Keyword in Content', status: 'PASS', text: 'Focus keyword appears in main content.' });
    } else if (kw) {
      checks.push({ rule: 'Keyword in Content', status: 'WARNING', text: 'Focus keyword does not appear in content text.' });
    } else {
      checks.push({ rule: 'Keyword in Content', status: 'ATTENTION', text: 'Focus keyword missing.' });
    }

    if (canonical) {
      checks.push({ rule: 'Canonical URL', status: 'PASS', text: 'Canonical URL is explicitly set.' });
    } else {
      checks.push({ rule: 'Canonical URL', status: 'WARNING', text: 'Canonical URL defaulting to page location.' });
    }

    if (ogImg) {
      checks.push({ rule: 'Open Graph Image', status: 'PASS', text: 'Social preview Open Graph image configured.' });
    } else {
      checks.push({ rule: 'Open Graph Image', status: 'WARNING', text: 'Open Graph image missing.' });
    }

    if (!isNoindex) {
      checks.push({ rule: 'Indexing Status', status: 'PASS', text: 'Configured for Index, Follow.' });
    } else {
      checks.push({ rule: 'Indexing Status', status: 'WARNING', text: 'Set to Noindex, Nofollow.' });
    }

    const passedCount = checks.filter(c => c.status === 'PASS').length;
    const totalCount = checks.length;
    const scorePct = Math.round((passedCount / totalCount) * 100);

    return {
      scorePct,
      passedCount,
      totalCount,
      checks
    };
  },

  async loadSEOData() {
    await Promise.all([
      this.loadPagesData(),
      this.loadProductsData(),
      this.loadBlogsData()
    ]);
    this.renderSEOTable();
  },

  renderSEOTable() {
    const tbody = document.getElementById('seo-tbody');
    if (!tbody) return;

    const pagesItems = this.pagesCache.map(p => ({ ...p, type: 'page', titleName: p.title, endpoint: `/api/pages/${p.id}` }));
    const prodItems = this.productsCache.map(p => ({ ...p, type: 'product', titleName: p.name, endpoint: `/api/products/${p.id}` }));
    const blogItems = this.blogsCache.map(b => ({ ...b, type: 'blog', titleName: b.title, endpoint: `/api/blog-posts/${b.id}` }));

    let allItems = [...pagesItems, ...prodItems, ...blogItems];

    const typeFilter = document.getElementById('seo-filter-type')?.value || 'all';
    const searchQuery = (document.getElementById('seo-search-input')?.value || '').toLowerCase().trim();

    if (typeFilter !== 'all') {
      allItems = allItems.filter(i => i.type === typeFilter);
    }

    if (searchQuery) {
      allItems = allItems.filter(i => 
        i.titleName.toLowerCase().includes(searchQuery) ||
        (i.focus_keyword || '').toLowerCase().includes(searchQuery) ||
        (i.meta_title || '').toLowerCase().includes(searchQuery)
      );
    }

    let totalItems = allItems.length;
    let optCount = 0;
    let missingTitleCount = 0;
    let missingDescCount = 0;

    allItems.forEach(i => {
      const evalRes = this.evaluateSEO(i);
      if (evalRes.scorePct >= 80) optCount++;
      if (!i.meta_title) missingTitleCount++;
      if (!i.meta_description) missingDescCount++;
    });

    if (document.getElementById('stat-seo-total')) document.getElementById('stat-seo-total').textContent = totalItems;
    if (document.getElementById('stat-seo-optimized')) document.getElementById('stat-seo-optimized').textContent = optCount;
    if (document.getElementById('stat-seo-missing-title')) document.getElementById('stat-seo-missing-title').textContent = missingTitleCount;
    if (document.getElementById('stat-seo-missing-desc')) document.getElementById('stat-seo-missing-desc').textContent = missingDescCount;

    if (allItems.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" class="text-center padding-lg">No content items found for SEO evaluation.</td></tr>`;
      return;
    }

    tbody.innerHTML = allItems.map(i => {
      const evalRes = this.evaluateSEO(i);
      const scoreBadgeClass = evalRes.scorePct >= 80 ? 'status-published' : (evalRes.scorePct >= 50 ? 'status-contacted' : 'status-new');
      return `
        <tr>
          <td><strong>${i.titleName}</strong><br><span style="color:var(--admin-text-muted); font-size:0.8rem;">/${i.slug}</span></td>
          <td><span class="status-pill status-read">${i.type.toUpperCase()}</span></td>
          <td>${i.focus_keyword ? `<code>${i.focus_keyword}</code>` : '<span style="color:#ef4444;">Missing</span>'}</td>
          <td>${i.meta_title ? i.meta_title : '<span style="color:var(--admin-text-muted);">Default Title</span>'}</td>
          <td>
            <div style="display:flex; align-items:center; gap:0.5rem;">
              <div style="flex:1; height:6px; background:#e2e8f0; border-radius:3px; overflow:hidden;">
                <div style="width:${evalRes.scorePct}%; height:100%; background:${evalRes.scorePct >= 80 ? '#166534' : (evalRes.scorePct >= 50 ? '#d97706' : '#dc2626')};"></div>
              </div>
              <strong style="font-size:0.82rem;">${evalRes.scorePct}%</strong>
            </div>
          </td>
          <td><span class="status-pill ${scoreBadgeClass}">${evalRes.scorePct >= 80 ? 'Optimized' : 'Needs Attention'}</span></td>
          <td>
            <button class="admin-btn admin-btn-sm admin-btn-primary btn-quick-seo" data-type="${i.type}" data-id="${i.id}">Optimize SEO &rarr;</button>
          </td>
        </tr>
      `;
    }).join('');

    tbody.querySelectorAll('.btn-quick-seo').forEach(btn => {
      btn.addEventListener('click', () => {
        const type = btn.dataset.type;
        const id = parseInt(btn.dataset.id, 10);
        let item = null;
        if (type === 'page') item = this.pagesCache.find(x => x.id === id);
        else if (type === 'product') item = this.productsCache.find(x => x.id === id);
        else if (type === 'blog') item = this.blogsCache.find(x => x.id === id);

        if (item) this.openSEOModal(item, type);
      });
    });
  },

  bindSEOEditor() {
    const modal = document.getElementById('seo-modal');
    const closeBtn = document.getElementById('seo-modal-close');
    const form = document.getElementById('seo-edit-form');

    if (!modal) return;

    closeBtn.addEventListener('click', () => modal.style.display = 'none');

    const titleInput = document.getElementById('seo-meta-title');
    const descInput = document.getElementById('seo-meta-desc');
    const kwInput = document.getElementById('seo-focus-keyword');

    const triggerLiveUpdate = () => {
      document.getElementById('seo-title-count').textContent = titleInput.value.length;
      document.getElementById('seo-desc-count').textContent = descInput.value.length;

      const liveItem = {
        focus_keyword: kwInput.value,
        meta_title: titleInput.value,
        meta_description: descInput.value,
        canonical_url: document.getElementById('seo-canonical').value,
        og_image: document.getElementById('seo-og-image').value,
        noindex: parseInt(document.getElementById('seo-noindex').value, 10)
      };

      const evalRes = this.evaluateSEO(liveItem);
      this.renderChecklistPreview(evalRes);
    };

    titleInput.addEventListener('input', triggerLiveUpdate);
    descInput.addEventListener('input', triggerLiveUpdate);
    kwInput.addEventListener('input', triggerLiveUpdate);

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('seo-item-id').value;
      const type = document.getElementById('seo-item-type').value;

      const payload = {
        focus_keyword: kwInput.value.trim(),
        meta_title: titleInput.value.trim(),
        meta_description: descInput.value.trim(),
        canonical_url: document.getElementById('seo-canonical').value.trim(),
        og_image: document.getElementById('seo-og-image').value.trim(),
        noindex: parseInt(document.getElementById('seo-noindex').value, 10)
      };

      let endpoint = '';
      if (type === 'page') endpoint = `/api/pages/${id}`;
      else if (type === 'product') endpoint = `/api/products/${id}`;
      else if (type === 'blog') endpoint = `/api/blog-posts/${id}`;

      const res = await fetch(endpoint, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${this.token}` },
        body: JSON.stringify(payload)
      });
      const json = await res.json();

      if (json.success) {
        modal.style.display = 'none';
        await this.loadSEOData();
      }
    });
  },

  openSEOModal(item, type) {
    const modal = document.getElementById('seo-modal');
    document.getElementById('seo-item-id').value = item.id;
    document.getElementById('seo-item-type').value = type;
    document.getElementById('seo-modal-type').textContent = type.toUpperCase();
    document.getElementById('seo-modal-title').textContent = `SEO Metadata — ${item.title || item.name}`;

    document.getElementById('seo-focus-keyword').value = item.focus_keyword || '';
    document.getElementById('seo-meta-title').value = item.meta_title || '';
    document.getElementById('seo-meta-desc').value = item.meta_description || '';
    document.getElementById('seo-canonical').value = item.canonical_url || '';
    document.getElementById('seo-og-image').value = item.og_image || item.featured_image || '';
    document.getElementById('seo-noindex').value = item.noindex ? '1' : '0';

    document.getElementById('seo-title-count').textContent = (item.meta_title || '').length;
    document.getElementById('seo-desc-count').textContent = (item.meta_description || '').length;

    const evalRes = this.evaluateSEO(item);
    this.renderChecklistPreview(evalRes);

    modal.style.display = 'flex';
  },

  renderChecklistPreview(evalRes) {
    const scoreElem = document.getElementById('seo-checklist-score');
    const itemsElem = document.getElementById('seo-checklist-items');

    if (scoreElem) {
      scoreElem.textContent = `Optimization Completion: ${evalRes.scorePct}%`;
      scoreElem.className = `status-pill ${evalRes.scorePct >= 80 ? 'status-published' : (evalRes.scorePct >= 50 ? 'status-contacted' : 'status-new')}`;
    }

    if (itemsElem) {
      itemsElem.innerHTML = evalRes.checks.map(c => {
        const icon = c.status === 'PASS' ? '<i class="ri-checkbox-circle-fill" style="color:#166534;"></i>' : (c.status === 'WARNING' ? '<i class="ri-alert-fill" style="color:#d97706;"></i>' : '<i class="ri-close-circle-fill" style="color:#dc2626;"></i>');
        return `
          <div style="display:flex; align-items:flex-start; gap:0.5rem; line-height:1.4;">
            ${icon}
            <div>
              <strong>${c.rule}:</strong> ${c.text}
            </div>
          </div>
        `;
      }).join('');
    }
  },

  // ==================== PHASE 4: REDIRECTS MANAGEMENT ====================
  async loadRedirectsData() {
    try {
      const res = await fetch('/api/redirects', {
        headers: { 'Authorization': `Bearer ${this.token}` }
      });
      const json = await res.json();
      if (json.success && Array.isArray(json.data)) {
        this.redirectsCache = json.data;
        this.renderRedirectsTable();
      }
    } catch (err) {
      console.warn('[Load Redirects Error]:', err.message);
    }
  },

  renderRedirectsTable() {
    const tbody = document.getElementById('redirects-tbody');
    if (!tbody) return;

    const query = (document.getElementById('redirects-search')?.value || '').toLowerCase().trim();
    const filtered = query ? this.redirectsCache.filter(r => 
      r.source_url.toLowerCase().includes(query) || 
      r.target_url.toLowerCase().includes(query)
    ) : this.redirectsCache;

    if (filtered.length === 0) {
      tbody.innerHTML = `<tr><td colspan="6" class="text-center padding-lg">No URL redirect rules configured.</td></tr>`;
      return;
    }

    tbody.innerHTML = filtered.map(r => `
      <tr>
        <td><code>${r.source_url}</code></td>
        <td><code>${r.target_url}</code></td>
        <td><span class="status-pill status-read">${r.status_code || 301} ${r.status_code === 301 ? 'Permanent' : 'Temporary'}</span></td>
        <td>
          <button class="admin-btn admin-btn-sm ${r.is_active ? 'admin-btn-outline' : 'admin-btn-danger'} btn-toggle-redir" data-id="${r.id}" data-active="${r.is_active}">
            ${r.is_active ? 'Active' : 'Disabled'}
          </button>
        </td>
        <td>${new Date(r.created_at).toLocaleDateString()}</td>
        <td>
          <button class="admin-btn admin-btn-sm admin-btn-outline btn-edit-redir" data-id="${r.id}">Edit</button>
          <button class="admin-btn admin-btn-sm admin-btn-danger btn-delete-redir" data-id="${r.id}">Delete</button>
        </td>
      </tr>
    `).join('');

    tbody.querySelectorAll('.btn-edit-redir').forEach(b => {
      b.addEventListener('click', () => this.openRedirectModal(parseInt(b.dataset.id, 10)));
    });

    tbody.querySelectorAll('.btn-toggle-redir').forEach(b => {
      b.addEventListener('click', async () => {
        const id = b.dataset.id;
        const currentActive = parseInt(b.dataset.active, 10);
        await fetch(`/api/redirects/${id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${this.token}` },
          body: JSON.stringify({ is_active: currentActive ? 0 : 1 })
        });
        await this.loadRedirectsData();
      });
    });

    tbody.querySelectorAll('.btn-delete-redir').forEach(b => {
      b.addEventListener('click', async () => {
        const id = b.dataset.id;
        if (!confirm('Delete redirect rule permanently?')) return;
        await fetch(`/api/redirects/${id}`, {
          method: 'DELETE',
          headers: { 'Authorization': `Bearer ${this.token}` }
        });
        await this.loadRedirectsData();
      });
    });
  },

  bindRedirectEditor() {
    const modal = document.getElementById('redirect-modal');
    const closeBtn = document.getElementById('redirect-modal-close');
    const form = document.getElementById('redirect-edit-form');
    const deleteBtn = document.getElementById('btn-delete-redirect');

    if (!modal) return;

    closeBtn.addEventListener('click', () => modal.style.display = 'none');

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const id = document.getElementById('redir-id').value;
      const payload = {
        source_url: document.getElementById('redir-source').value.trim(),
        target_url: document.getElementById('redir-target').value.trim(),
        status_code: parseInt(document.getElementById('redir-status-code').value, 10),
        is_active: parseInt(document.getElementById('redir-active').value, 10),
        notes: document.getElementById('redir-notes').value.trim()
      };

      const url = id ? `/api/redirects/${id}` : '/api/redirects';
      const method = id ? 'PUT' : 'POST';

      const res = await fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${this.token}` },
        body: JSON.stringify(payload)
      });
      const json = await res.json();

      if (json.success) {
        modal.style.display = 'none';
        await this.loadRedirectsData();
      } else {
        alert(json.details || json.error || 'Failed to save redirect rule.');
      }
    });

    deleteBtn.addEventListener('click', async () => {
      const id = document.getElementById('redir-id').value;
      if (!id || !confirm('Delete redirect rule permanently?')) return;
      await fetch(`/api/redirects/${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${this.token}` }
      });
      modal.style.display = 'none';
      await this.loadRedirectsData();
    });
  },

  openRedirectModal(id = null) {
    const modal = document.getElementById('redirect-modal');
    document.getElementById('redirect-modal-title').textContent = id ? 'Edit URL Redirect Rule' : 'Create URL Redirect Rule';
    document.getElementById('btn-delete-redirect').style.display = id ? 'inline-flex' : 'none';

    if (id) {
      const r = this.redirectsCache.find(x => x.id === id);
      if (r) {
        document.getElementById('redir-id').value = r.id;
        document.getElementById('redir-source').value = r.source_url || '';
        document.getElementById('redir-target').value = r.target_url || '';
        document.getElementById('redir-status-code').value = r.status_code || 301;
        document.getElementById('redir-active').value = r.is_active ? '1' : '0';
        document.getElementById('redir-notes').value = r.notes || '';
      }
    } else {
      document.getElementById('redirect-edit-form').reset();
      document.getElementById('redir-id').value = '';
    }

    modal.style.display = 'flex';
  },

  bindModal() {
    const modal = document.getElementById('lead-modal');
    const closeBtn = document.getElementById('lead-modal-close');
    const form = document.getElementById('lead-update-form');
    const deleteBtn = document.getElementById('btn-delete-lead');
    const alertBox = document.getElementById('lead-update-alert');

    if (!modal) return;

    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    modal.addEventListener('click', (e) => {
      if (e.target === modal) modal.style.display = 'none';
    });

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      alertBox.style.display = 'none';
      const leadId = document.getElementById('lead-modal-id').value;
      const status = document.getElementById('lead-status-select').value;
      const notes = document.getElementById('lead-notes-textarea').value.trim();

      const res = await fetch(`/api/forms/submissions/${leadId}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${this.token}` },
        body: JSON.stringify({ status, notes })
      });
      const json = await res.json();
      if (json.success) {
        alertBox.textContent = 'Lead status & notes updated successfully!';
        alertBox.style.display = 'block';
        await this.loadSubmissionsData();
      }
    });

    deleteBtn.addEventListener('click', async () => {
      const leadId = document.getElementById('lead-modal-id').value;
      if (!confirm('Delete lead submission permanently?')) return;
      await fetch(`/api/forms/submissions/${leadId}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${this.token}` }
      });
      modal.style.display = 'none';
      await this.loadSubmissionsData();
    });
  },

  openLeadModal(id) {
    const item = this.submissionsCache.find(s => s.id === id);
    if (!item) return;

    document.getElementById('lead-modal-id').value = item.id;
    document.getElementById('lead-modal-form-type').textContent = (item.form_type || 'Contact').toUpperCase();
    document.getElementById('lead-modal-title').textContent = `Submission #${item.id} — ${item.name || 'Inquiry'}`;
    document.getElementById('lead-modal-name').textContent = item.name || 'N/A';
    document.getElementById('lead-modal-company').textContent = item.company || 'N/A';
    document.getElementById('lead-modal-email').textContent = item.email || 'N/A';
    document.getElementById('lead-modal-phone').textContent = item.phone || 'N/A';

    document.getElementById('lead-status-select').value = item.status || 'New';
    document.getElementById('lead-notes-textarea').value = item.notes || '';
    document.getElementById('lead-update-alert').style.display = 'none';

    const payloadContainer = document.getElementById('lead-modal-payload');
    let parsedPayload = {};

    try {
      if (typeof item.submitted_data === 'string') parsedPayload = JSON.parse(item.submitted_data);
      else if (item.submitted_data) parsedPayload = item.submitted_data;
    } catch (e) {
      parsedPayload = { message: item.message };
    }

    if (Object.keys(parsedPayload).length === 0) {
      parsedPayload = { name: item.name, email: item.email, phone: item.phone, company: item.company, message: item.message };
    }

    const formatLabel = (key) => {
      const labels = {
        'company_name': 'Company Name',
        'contact_name': 'Contact Person',
        'name': 'Full Name',
        'email': 'Business Email',
        'phone': 'Phone / WhatsApp',
        'product_type': 'Product Category',
        'estimated_quantity': 'Estimated Quantity',
        'specifications': 'Technical Specifications (GSM/Size)',
        'message': 'Additional Notes / Requirements',
        'form_type': 'Form Category'
      };
      return labels[key] || key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
    };

    payloadContainer.innerHTML = Object.entries(parsedPayload).map(([k, v]) => `
      <div class="payload-row" style="display:flex; margin-bottom:0.5rem; padding-bottom:0.4rem; border-bottom:1px solid #edf2f7;">
        <span class="payload-key" style="width:200px; font-weight:600; color:var(--admin-navy); font-size:0.85rem;">${formatLabel(k)}:</span>
        <span class="payload-value" style="flex:1; color:var(--admin-text-dark); font-size:0.88rem; white-space:pre-wrap;">${v || '-'}</span>
      </div>
    `).join('');

    document.getElementById('lead-modal').style.display = 'flex';
  },

  bindLiveClock() {
    const clock = document.getElementById('live-clock');
    if (!clock) return;
    const updateClock = () => clock.textContent = new Date().toLocaleTimeString();
    updateClock();
    setInterval(updateClock, 1000);
  }
};
