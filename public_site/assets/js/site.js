/**
 * Prince Art Packages (Pvt) Ltd - B2B Website Script
 */

document.addEventListener('DOMContentLoaded', () => {
  setupPageRouting();
  setupRFQForm();
  setupNewsletterForm();
  setupProductTabs();
  setupCapAnimations();
  setupBlogsPage();
});

// Capabilities Statistics reveal animation
function setupCapAnimations() {
  const statNumbers = document.querySelectorAll('.cap-stat-number, .cap-infra-row');
  if (!statNumbers.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.15 });

  statNumbers.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(15px)';
    el.style.transition = 'all 0.5s cubic-bezier(0.16, 1, 0.3, 1)';
    observer.observe(el);
  });
}

// Live Public Meta Tag Injector
function updatePageSEO(seoData = {}) {
  const brandSuffix = ' | Prince Art Packages (Private) Limited';
  const defaultDesc = 'ISO 9001:2015 and FSC Chain of Custody certified cGMP manufacturer of pharmaceutical secondary packaging in Karachi, Pakistan.';
  
  const title = seoData.meta_title || (seoData.title ? `${seoData.title}${brandSuffix}` : document.title);
  const desc = seoData.meta_description || seoData.description || defaultDesc;
  const canonical = seoData.canonical_url || window.location.href;
  const robots = seoData.noindex ? 'noindex, nofollow' : 'index, follow';
  const ogTitle = seoData.og_title || seoData.meta_title || title;
  const ogDesc = seoData.og_description || desc;
  const ogImg = seoData.og_image || seoData.featured_image || 'http://localhost:3000/images/facility.jpg';

  document.title = title;

  const descMeta = document.getElementById('meta-desc') || document.querySelector('meta[name="description"]');
  if (descMeta) descMeta.setAttribute('content', desc);

  const canonicalLink = document.getElementById('meta-canonical') || document.querySelector('link[rel="canonical"]');
  if (canonicalLink) canonicalLink.setAttribute('href', canonical);

  const robotsMeta = document.getElementById('meta-robots') || document.querySelector('meta[name="robots"]');
  if (robotsMeta) robotsMeta.setAttribute('content', robots);

  const ogTitleMeta = document.getElementById('og-title') || document.querySelector('meta[property="og:title"]');
  if (ogTitleMeta) ogTitleMeta.setAttribute('content', ogTitle);

  const ogDescMeta = document.getElementById('og-desc') || document.querySelector('meta[property="og:description"]');
  if (ogDescMeta) ogDescMeta.setAttribute('content', ogDesc);

  const ogImgMeta = document.getElementById('og-image') || document.querySelector('meta[property="og:image"]');
  if (ogImgMeta) ogImgMeta.setAttribute('content', ogImg);
}

// Page Navigation & CTA Click Handling
function setupPageRouting() {
  // Mobile Menu Drawer Handler
  const mobileNavBtn = document.getElementById('btn-mobile-nav');
  const navLinks = document.querySelector('.nav-links');

  if (mobileNavBtn && navLinks) {
    mobileNavBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = navLinks.classList.toggle('mobile-open');
      mobileNavBtn.innerHTML = isOpen ? '<i class="ri-close-line"></i>' : '<i class="ri-menu-3-line"></i>';
    });

    document.addEventListener('click', (e) => {
      if (!e.target.closest('.main-header')) {
        navLinks.classList.remove('mobile-open');
        mobileNavBtn.innerHTML = '<i class="ri-menu-3-line"></i>';
      }
    });
  }

  // Global Event Listener for Mobile Drawer Closing & In-page Smooth Anchors
  document.addEventListener('click', (e) => {
    // Auto-close mobile drawer when any link is clicked
    if (navLinks && navLinks.classList.contains('mobile-open')) {
      navLinks.classList.remove('mobile-open');
      if (mobileNavBtn) mobileNavBtn.innerHTML = '<i class="ri-menu-3-line"></i>';
    }

    const link = e.target.closest('a');
    if (!link) return;

    const href = link.getAttribute('href') || '';

    // Handle In-page Anchor Links (e.g. href="#contact-form")
    if (href.startsWith('#') && href.length > 1) {
      const targetElement = document.getElementById(href.substring(1));
      if (targetElement) {
        e.preventDefault();
        targetElement.scrollIntoView({ behavior: 'smooth' });
      }
    }
  });
}

// Product Capabilities Spec Sheet Tabs
function setupProductTabs() {
  const tabBtns = document.querySelectorAll('.spec-tab-btn');
  const specPanes = document.querySelectorAll('.spec-tab-pane');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const tabTarget = btn.dataset.tab;

      tabBtns.forEach(b => b.classList.remove('active'));
      specPanes.forEach(p => p.classList.remove('active'));

      btn.classList.add('active');
      const targetPane = document.getElementById(`tab-${tabTarget}`);
      if (targetPane) targetPane.classList.add('active');
    });
  });
}

// RFQ Multi-field Form Submission
function setupRFQForm() {
  const form = document.getElementById('b2b-rfq-form');
  const alertBox = document.getElementById('rfq-alert');

  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Submitting Proposal Request...';

    alertBox.style.display = 'none';

    const payload = {
      form_type: 'rfq',
      company_name: form.company_name.value.trim(),
      contact_name: form.contact_name.value.trim(),
      name: form.contact_name.value.trim(),
      email: form.email.value.trim(),
      phone: form.phone.value.trim(),
      product_type: form.product_type.value,
      estimated_quantity: form.estimated_quantity.value.trim(),
      specifications: form.specifications.value.trim(),
      message: form.message.value.trim()
    };

    try {
      const res = await fetch('/api/forms/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const json = await res.json();

      if (json.success) {
        alertBox.className = 'alert alert-success';
        alertBox.innerHTML = `<i class="ri-checkbox-circle-fill"></i> <strong>Thank you!</strong> ${json.message} (Reference ID: <code>${json.referenceId}</code>)`;
        alertBox.style.display = 'block';
        form.reset();
      } else {
        alertBox.className = 'alert alert-danger';
        alertBox.innerHTML = `<i class="ri-error-warning-fill"></i> <strong>Submission Error:</strong> ${json.details || json.error}`;
        alertBox.style.display = 'block';
      }
    } catch (err) {
      alertBox.className = 'alert alert-danger';
      alertBox.innerHTML = `<i class="ri-error-warning-fill"></i> <strong>Network Error:</strong> ${err.message}`;
      alertBox.style.display = 'block';
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
    }
  });
}

// Newsletter Subscription Form Submission
function setupNewsletterForm() {
  const form = document.getElementById('newsletter-form');
  const alertBox = document.getElementById('newsletter-alert');

  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Subscribing...';

    if (alertBox) alertBox.style.display = 'none';

    const payload = {
      form_type: 'newsletter',
      email: form.email.value.trim()
    };

    try {
      const res = await fetch('/api/forms/submit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });

      const json = await res.json();

      if (json.success) {
        if (alertBox) {
          alertBox.className = 'alert alert-success';
          alertBox.innerHTML = `<i class="ri-checkbox-circle-fill"></i> Subscribed successfully!`;
          alertBox.style.display = 'block';
        } else {
          alert('Subscribed successfully!');
        }
        form.reset();
      } else {
        if (alertBox) {
          alertBox.className = 'alert alert-danger';
          alertBox.innerHTML = `<i class="ri-error-warning-fill"></i> ${json.details || json.error}`;
          alertBox.style.display = 'block';
        } else {
          alert(json.details || json.error);
        }
      }
    } catch (err) {
      if (alertBox) {
        alertBox.className = 'alert alert-danger';
        alertBox.innerHTML = `<i class="ri-error-warning-fill"></i> Connection error.`;
        alertBox.style.display = 'block';
      }
    } finally {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
    }
  });
}

// Dynamic Blog Posts Fetching, Category Filtering & Modal Reader
let allBlogPosts = [];

async function setupBlogsPage() {
  const container = document.getElementById('blogs-grid-container');
  if (!container) return;

  try {
    const res = await fetch('/api/public/blog-posts');
    const json = await res.json();

    if (json.success && Array.isArray(json.data) && json.data.length > 0) {
      allBlogPosts = json.data;
    } else {
      allBlogPosts = getFallbackBlogPosts();
    }
  } catch (err) {
    console.warn('[Blogs Fetch Warn] API fallback active:', err.message);
    allBlogPosts = getFallbackBlogPosts();
  }

  renderBlogGrid(allBlogPosts);
  setupBlogFilterBtns();
  setupBlogModalHandlers();
}

function renderBlogGrid(posts) {
  const container = document.getElementById('blogs-grid-container');
  if (!container) return;

  if (posts.length === 0) {
    container.innerHTML = `<div style="grid-column: 1 / -1; text-align:center; padding:3rem; color:var(--text-muted);">No articles found in this category.</div>`;
    return;
  }

  container.innerHTML = posts.map(post => `
    <div class="blog-card">
      <div class="blog-img-box">
        <img src="${post.featured_image || 'images/facility.jpg'}" alt="${post.title}">
        <span class="blog-badge-tag">${post.category || 'Article'}</span>
      </div>
      <div class="blog-card-body">
        <div class="blog-date-meta">
          <i class="ri-calendar-line"></i> ${new Date(post.published_at || Date.now()).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}
        </div>
        <h3 class="blog-card-title">${post.title}</h3>
        <p class="blog-card-excerpt">${post.excerpt || ''}</p>
        <button class="btn btn-outline-navy btn-sm btn-read-blog" data-post-id="${post.id}" style="margin-top:auto; align-self:flex-start;">Read Full Article &rarr;</button>
      </div>
    </div>
  `).join('');

  container.querySelectorAll('.btn-read-blog').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const postId = parseInt(e.currentTarget.getAttribute('data-post-id'), 10);
      const post = allBlogPosts.find(p => p.id === postId);
      if (post) openBlogModal(post);
    });
  });
}

function setupBlogFilterBtns() {
  const filterBtns = document.querySelectorAll('.blog-filter-btn');
  filterBtns.forEach(btn => {
    btn.addEventListener('click', (e) => {
      filterBtns.forEach(b => {
        b.classList.remove('active', 'btn-gold');
        b.classList.add('btn-outline-navy');
      });

      e.currentTarget.classList.add('active', 'btn-gold');
      e.currentTarget.classList.remove('btn-outline-navy');

      const selectedCat = e.currentTarget.getAttribute('data-category');
      if (selectedCat === 'all') {
        renderBlogGrid(allBlogPosts);
      } else {
        const filtered = allBlogPosts.filter(p => p.category === selectedCat);
        renderBlogGrid(filtered);
      }
    });
  });
}

function setupBlogModalHandlers() {
  const modal = document.getElementById('blog-modal');
  const closeBtn = document.getElementById('blog-modal-close');
  if (!modal || !closeBtn) return;

  closeBtn.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  modal.addEventListener('click', (e) => {
    if (e.target === modal) modal.style.display = 'none';
  });
}

function openBlogModal(post) {
  const modal = document.getElementById('blog-modal');
  const content = document.getElementById('blog-modal-content');
  if (!modal || !content) return;

  content.innerHTML = `
    <span class="cert-pill" style="margin-bottom:0.75rem; display:inline-block;">${post.category || 'Article'}</span>
    <h2 style="color:var(--navy-dark); font-size:1.8rem; line-height:1.25; margin-bottom:0.75rem;">${post.title}</h2>
    <div style="font-size:0.85rem; color:var(--text-muted); margin-bottom:1.5rem; display:flex; gap:1.5rem; flex-wrap:wrap;">
      <span><i class="ri-calendar-line"></i> ${new Date(post.published_at || Date.now()).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</span>
      <span><i class="ri-user-3-line"></i> ${post.author || 'Prince Art Packages Technical Team'}</span>
    </div>
    <div style="width:100%; height:320px; overflow:hidden; border-radius:var(--radius-sm); margin-bottom:1.5rem;">
      <img src="${post.featured_image || 'images/facility.jpg'}" alt="${post.title}" style="width:100%; height:100%; object-fit:cover;">
    </div>
    <div style="color:var(--text-body); font-size:0.95rem; line-height:1.7;">
      ${post.content || post.excerpt}
    </div>
    <div style="margin-top:2rem; padding-top:1.5rem; border-top:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
      <span style="font-size:0.88rem; color:var(--text-muted);">Have questions regarding this packaging specification?</span>
      <a href="#contact" class="btn btn-gold btn-sm" data-action="quote" onclick="document.getElementById('blog-modal').style.display='none';">Request a Proposal &rarr;</a>
    </div>
  `;

  modal.style.display = 'block';
}

function getFallbackBlogPosts() {
  return [
    {
      id: 1,
      title: 'ISO 9001:2015 & cGMP Compliance in Pharmaceutical Secondary Packaging',
      category: 'Quality & Compliance',
      excerpt: 'How strict Quality Assurance protocols, electronic line clearances, and ISO 9001:2015 standards safeguard medicine cartons against mix-ups.',
      content: '<p>In pharmaceutical secondary packaging, quality assurance is a critical imperative...</p>',
      featured_image: 'images/prod_cartons.jpg',
      published_at: '2026-02-15'
    },
    {
      id: 2,
      title: 'Anti-Counterfeiting Innovations: From Tamper-Evident Seals to 3D-ENGRAVIX',
      category: 'Anti-Counterfeiting',
      excerpt: 'Exploring modern packaging security technologies designed to combat counterfeit medicines.',
      content: '<p>Counterfeit pharmaceuticals pose a growing global threat to public health...</p>',
      featured_image: 'images/engravix.jpg',
      published_at: '2026-02-10'
    }
  ];
}
