  </main>

  <!-- SECTION 15 — FOOTER -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <!-- Brand & SEO Overview Column -->
        <div class="footer-col-brand">
          <a href="index" class="footer-logo-wrap">
            <img src="assets/images/logo.png" alt="Prince Art Packages (Private) Limited" class="footer-logo">
          </a>
          <p class="footer-tagline">
            <strong>Prince Art Packages (Private) Limited</strong><br>
            <span style="font-size:0.78rem; opacity:0.8;">(Formerly Prince Art Press)</span><br>
            Specialized pharmaceutical secondary packaging manufacturer in Karachi, Pakistan, engineered for global audit compliance.
          </p>
          <div class="footer-cert-tags">
            <span class="footer-cert-tag">ISO 9001:2015</span>
            <span class="footer-cert-tag">cGMP COMPLIANT</span>
            <span class="footer-cert-tag">WHO-GMP</span>
            <span class="footer-cert-tag">FSC&reg; C222205</span>
          </div>
          <div class="footer-socials" style="margin-top: 1rem;">
            <a href="<?= h(get_setting('facebook_url', '#')) ?>" target="_blank" rel="noopener" aria-label="Facebook" title="Facebook" class="social-btn"><i class="ri-facebook-fill"></i></a>
            <a href="<?= h(get_setting('instagram_url', '#')) ?>" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram" class="social-btn"><i class="ri-instagram-line"></i></a>
            <a href="<?= h(get_setting('linkedin_url', '#')) ?>" target="_blank" rel="noopener" aria-label="LinkedIn" title="LinkedIn" class="social-btn"><i class="ri-linkedin-fill"></i></a>
          </div>
        </div>

        <!-- Column 1: Company -->
        <div>
          <h4 class="footer-title">Company</h4>
          <ul class="footer-links">
            <li><a href="about">About Us</a></li>
            <li><a href="quality">Quality</a></li>
            <li><a href="capabilities">Capabilities</a></li>
            <li><a href="innovation">Innovation</a></li>
          </ul>
        </div>

        <!-- Column 2: Products -->
        <div>
          <h4 class="footer-title">Products</h4>
          <ul class="footer-links">
            <li><a href="products#product-folding-cartons">Printed Cartons</a></li>
            <li><a href="products#product-leaf-inserts">Leaflets &amp; Outserts</a></li>
            <li><a href="products#product-printed-labels">Printed Labels</a></li>
            <li><a href="innovation">ColdSeal Blister Wallets</a></li>
            <li><a href="innovation">Anti-Counterfeit Packaging</a></li>
          </ul>
        </div>

        <!-- Column 3: Industries -->
        <div>
          <h4 class="footer-title">Industries</h4>
          <ul class="footer-links">
            <li><a href="industries">Pharmaceutical</a></li>
            <li><a href="industries">Healthcare</a></li>
            <li><a href="industries">Medical</a></li>
            <li><a href="industries">Other Regulated Industries</a></li>
          </ul>
        </div>

        <!-- Column 4: Contact & Manufacturing Footprint -->
        <div>
          <h4 class="footer-title">Contact</h4>
          <div class="footer-contact-list">
            <?php
              $footerPhone = get_setting('company_phone', '+92 21-38893400-3');
              $footerEmail = get_setting('company_email', 'info@princeartpackages.com');
            ?>
            <a href="tel:<?= preg_replace('/[^0-9+]/', '', $footerPhone) ?>" class="footer-contact-item" title="Phone">
              <i class="ri-phone-fill"></i>
              <span><strong>Phone:</strong> <?= h($footerPhone) ?></span>
            </a>
            <a href="mailto:<?= h($footerEmail) ?>" class="footer-contact-item" title="Email">
              <i class="ri-mail-fill"></i>
              <span><strong>Email:</strong> <?= h($footerEmail) ?></span>
            </a>
            <div class="footer-contact-item" title="Manufacturing Address">
              <i class="ri-map-pin-2-fill"></i>
              <span><strong>Location:</strong> Korangi Creek Industrial Park, Karachi, Pakistan</span>
            </div>
            <div class="footer-contact-item">
              <i class="ri-building-line"></i>
              <span><strong>Unit 1:</strong> WH-17-A8, ST-1, Sector 38, Korangi Creek Industrial Park</span>
            </div>
            <div class="footer-contact-item">
              <i class="ri-building-2-line"></i>
              <span><strong>Unit 2:</strong> Plot 239, Opp. Masco, Main Korangi Creek Road</span>
            </div>
            <button type="button" class="btn btn-gold btn-sm footer-cta-btn open-rfq-modal" data-source-button="Footer CTA — Request a Quote" style="margin-top:0.65rem; cursor:pointer; width:100%; justify-content:center;">
              <i class="ri-file-list-3-line"></i> Request a Quote
            </button>
          </div>
        </div>
      </div>

      <!-- Footer Bottom Bar -->
      <div class="footer-bottom">
        <div>&copy; <?= date('Y') ?> Prince Art Packages (Private) Limited. All Rights Reserved.</div>
        <div class="footer-legal">
          <a href="privacy">Privacy Policy</a>
          <span class="sep">&middot;</span>
          <a href="terms">Terms &amp; Conditions</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- ================================================================ -->
  <!-- GLOBAL RFQ QUOTATION POPUP MODAL                                 -->
  <!-- ================================================================ -->
  <div id="rfqModal" class="rfq-modal-overlay" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(10,37,64,0.78); z-index:999999; backdrop-filter:blur(6px); overflow-y:auto; padding:2rem 1rem; align-items:center; justify-content:center;">
    <div class="rfq-modal-dialog" style="background:#ffffff; border-radius:12px; max-width:680px; width:100%; margin:auto; box-shadow:0 24px 60px rgba(0,0,0,0.35); overflow:hidden; position:relative; animation:modalFadeIn 0.25s ease-out;">
      
      <!-- Modal Header -->
      <div style="background:linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color:#ffffff; padding:1.5rem 2rem; display:flex; align-items:center; justify-content:space-between; border-bottom:3px solid var(--teal-brand);">
        <div style="display:flex; align-items:center; gap:0.85rem;">
          <div style="width:42px; height:42px; border-radius:8px; background:rgba(0,168,150,0.25); display:flex; align-items:center; justify-content:center; font-size:1.4rem; color:var(--teal-brand);">
            <i class="ri-file-list-3-line"></i>
          </div>
          <div>
            <h3 style="margin:0; font-size:1.35rem; color:#ffffff; font-weight:800;">Request a Formal Quotation</h3>
            <span style="font-size:0.82rem; color:rgba(255,255,255,0.85);">Direct Technical Inquiry &bull; 24-Hour Response Guarantee</span>
          </div>
        </div>
        <button type="button" id="closeRfqModalBtn" style="background:rgba(255,255,255,0.15); border:none; color:#ffffff; width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.25rem; cursor:pointer; transition:background 0.2s;">
          <i class="ri-close-line"></i>
        </button>
      </div>

      <!-- Modal Form Body -->
      <form id="globalRfqForm" action="rfq-submit.php" method="POST" style="padding:2rem;">
        <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
        <input type="hidden" name="source_button" id="rfqSourceButton" value="Request a Quote Button">
        <input type="hidden" name="source_page" id="rfqSourcePage" value="<?= h($_SERVER['REQUEST_URI'] ?? '') ?>">

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy-dark); margin-bottom:0.4rem;">
              Full Name <span style="color:#ef4444;">*</span>
            </label>
            <input type="text" name="contact_name" required placeholder="e.g. Dr. Tariq Mahmood" class="form-input" style="width:100%; padding:0.75rem 1rem; border:1px solid var(--border-color); border-radius:6px; font-size:0.92rem;">
          </div>

          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy-dark); margin-bottom:0.4rem;">
              Company / Pharma Plant <span style="color:#ef4444;">*</span>
            </label>
            <input type="text" name="company_name" required placeholder="e.g. Searle / Getz / Martin Dow" class="form-input" style="width:100%; padding:0.75rem 1rem; border:1px solid var(--border-color); border-radius:6px; font-size:0.92rem;">
          </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy-dark); margin-bottom:0.4rem;">
              Business Email <span style="color:#ef4444;">*</span>
            </label>
            <input type="email" name="email" required placeholder="name@company.com" class="form-input" style="width:100%; padding:0.75rem 1rem; border:1px solid var(--border-color); border-radius:6px; font-size:0.92rem;">
          </div>

          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy-dark); margin-bottom:0.4rem;">
              Phone / WhatsApp Number
            </label>
            <input type="tel" name="phone" placeholder="+92 300 1234567" class="form-input" style="width:100%; padding:0.75rem 1rem; border:1px solid var(--border-color); border-radius:6px; font-size:0.92rem;">
          </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy-dark); margin-bottom:0.4rem;">
              Product of Interest
            </label>
            <select name="product_type" id="rfqProductType" class="form-input" style="width:100%; padding:0.75rem 1rem; border:1px solid var(--border-color); border-radius:6px; font-size:0.92rem; background:#ffffff;">
              <option value="Printed Cartons">Printed Cartons</option>
              <option value="Leaf-Inserts">Leaf-Inserts (PIL &amp; Outserts)</option>
              <option value="Printed Labels">Printed Labels (Rolls)</option>
              <option value="Honeycomb Separators">Honeycomb Separators</option>
              <option value="Pill-Folders">Pill-Folders (Dose Adherence)</option>
              <option value="Temper Evident Cartons">Temper Evident Cartons &amp; Labels</option>
              <option value="3D-ENGRAVIX™">3D-ENGRAVIX™ Optical Cartons</option>
              <option value="Cold-seal Wallet">Cold-seal Blister Wallet</option>
              <option value="General Secondary Packaging">General Secondary Packaging</option>
            </select>
          </div>

          <div>
            <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy-dark); margin-bottom:0.4rem;">
              Estimated Batch Quantity
            </label>
            <input type="text" name="estimated_quantity" placeholder="e.g. 50,000 units" class="form-input" style="width:100%; padding:0.75rem 1rem; border:1px solid var(--border-color); border-radius:6px; font-size:0.92rem;">
          </div>
        </div>

        <div style="margin-bottom:1.5rem;">
          <label style="display:block; font-size:0.85rem; font-weight:700; color:var(--navy-dark); margin-bottom:0.4rem;">
            Technical Specifications / Packaging Notes
          </label>
          <textarea name="specifications" rows="3" placeholder="Enter board caliper (GSM), dimensions, folding type, or specific cGMP compliance requirements..." class="form-input" style="width:100%; padding:0.75rem 1rem; border:1px solid var(--border-color); border-radius:6px; font-size:0.92rem; resize:vertical;"></textarea>
        </div>

        <div id="rfqFormAlert" style="display:none; padding:0.75rem 1rem; border-radius:6px; margin-bottom:1rem; font-size:0.88rem;"></div>

        <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; border-top:1px solid var(--border-color); padding-top:1.25rem;">
          <div style="font-size:0.8rem; color:var(--text-muted); display:flex; align-items:center; gap:0.4rem;">
            <i class="ri-shield-check-fill text-teal" style="font-size:1.1rem;"></i> 100% Confidential &bull; cGMP Regulated QA Desk
          </div>
          <div style="display:flex; gap:0.75rem;">
            <button type="button" id="cancelRfqBtn" class="btn btn-outline-navy btn-sm" style="cursor:pointer;">Cancel</button>
            <button type="submit" id="submitRfqBtn" class="btn btn-gold btn-lg" style="box-shadow:0 4px 14px rgba(212,175,55,0.35); cursor:pointer;">
              <i class="ri-send-plane-fill"></i> Submit RFQ Inquiry &rarr;
            </button>
          </div>
        </div>
      </form>

    </div>
  </div>

  <style>
  @keyframes modalFadeIn {
    from { opacity: 0; transform: scale(0.96) translateY(-10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
  }
  </style>

  <!-- ================================================================ -->
  <!-- SMART POPUP & BUTTON TRACKING SCRIPT                             -->
  <!-- ================================================================ -->
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('rfqModal');
    var closeBtn = document.getElementById('closeRfqModalBtn');
    var cancelBtn = document.getElementById('cancelRfqBtn');
    var sourceBtnInput = document.getElementById('rfqSourceButton');
    var sourcePageInput = document.getElementById('rfqSourcePage');
    var productTypeSelect = document.getElementById('rfqProductType');

    function openRfqModal(sourceButtonLabel, preselectedProduct) {
      if (!modal) return;
      
      // 1. Set source button tracking label
      if (sourceBtnInput) {
        sourceBtnInput.value = sourceButtonLabel || 'Request a Quote Button';
      }
      
      // 2. Set source page tracking URL
      if (sourcePageInput) {
        sourcePageInput.value = window.location.href;
      }
      
      // 3. Preselect product if specified or detected from page context
      if (productTypeSelect && preselectedProduct) {
        for (var i = 0; i < productTypeSelect.options.length; i++) {
          if (productTypeSelect.options[i].value.toLowerCase().includes(preselectedProduct.toLowerCase()) ||
              preselectedProduct.toLowerCase().includes(productTypeSelect.options[i].value.toLowerCase())) {
            productTypeSelect.selectedIndex = i;
            break;
          }
        }
      }

      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }

    function closeRfqModal() {
      if (!modal) return;
      modal.style.display = 'none';
      document.body.style.overflow = '';
    }

    if (closeBtn) closeBtn.addEventListener('click', closeRfqModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeRfqModal);

    if (modal) {
      modal.addEventListener('click', function(e) {
        if (e.target === modal) {
          closeRfqModal();
        }
      });
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
        closeRfqModal();
      }
    });

    // Smart Click Handler: ONLY intercepts explicit "Request a Quote" buttons and elements with .open-rfq-modal / data-popup="true".
    // Regular navigation links (like Contact in navbar or footer) navigate normally to contact.php.
    document.addEventListener('click', function(e) {
      var target = e.target.closest('a, button');
      if (!target) return;

      var text = (target.textContent || '').trim().toLowerCase();
      var isExplicitModalBtn = target.classList.contains('open-rfq-modal') 
                            || target.classList.contains('header-cta-btn') 
                            || target.classList.contains('footer-cta-btn')
                            || target.getAttribute('data-popup') === 'true'
                            || (target.closest('.product-rfq-card') && text.includes('request a quote'))
                            || (target.closest('.product-hero-section') && text.includes('request a formal quote'));

      if (isExplicitModalBtn) {
        e.preventDefault();

        // Determine clear contextual button source label
        var customSource = target.getAttribute('data-source-button');
        var sourceLabel = customSource;
        if (!sourceLabel) {
          var pageTitle = document.title.split('|')[0].trim();
          if (target.classList.contains('header-cta-btn')) {
            sourceLabel = 'Header Navbar — Request a Quote Button';
          } else if (target.classList.contains('footer-cta-btn')) {
            sourceLabel = 'Footer — Request a Quote Button';
          } else if (target.closest('.product-hero-section')) {
            sourceLabel = pageTitle + ' — Hero Quote Button';
          } else if (target.closest('.product-rfq-card')) {
            sourceLabel = pageTitle + ' — Sidebar Direct Inquiry Card';
          } else {
            sourceLabel = pageTitle + ' — ' + (target.textContent || 'Request a Quote').trim();
          }
        }

        // Determine product from context or URL query
        var customProduct = target.getAttribute('data-product');
        var prod = customProduct;
        if (!prod) {
          var urlParams = new URLSearchParams(window.location.search);
          var slug = urlParams.get('slug');
          if (slug) {
            prod = slug.replace(/-/g, ' ');
          }
        }

        openRfqModal(sourceLabel, prod);
      }
    });

    // Handle AJAX Form Submission with instant validation feedback
    var rfqForm = document.getElementById('globalRfqForm');
    var alertBox = document.getElementById('rfqFormAlert');
    var submitBtn = document.getElementById('submitRfqBtn');

    if (rfqForm) {
      rfqForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Submitting...';
        }
        if (alertBox) alertBox.style.display = 'none';

        var formData = new FormData(rfqForm);

        fetch('rfq-submit.php', {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          },
          body: formData
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
          if (data.success) {
            if (alertBox) {
              alertBox.style.display = 'block';
              alertBox.style.background = '#dcfce7';
              alertBox.style.color = '#15803d';
              alertBox.style.border = '1px solid #bbf7d0';
              alertBox.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Thank you! Your RFQ inquiry (' + data.ref_no + ') has been received. Redirecting to confirmation...';
            }
            setTimeout(function() {
              window.location.href = data.redirect || 'thank-you.php';
            }, 800);
          } else {
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = '<i class="ri-send-plane-fill"></i> Submit RFQ Inquiry &rarr;';
            }
            if (alertBox) {
              alertBox.style.display = 'block';
              alertBox.style.background = '#fee2e2';
              alertBox.style.color = '#b91c1c';
              alertBox.style.border = '1px solid #fecaca';
              alertBox.innerHTML = '<i class="ri-error-warning-fill"></i> ' + (data.error || 'Submission failed. Please try again.');
            }
          }
        })
        .catch(function(err) {
          // Fallback to standard form submit if fetch fails
          rfqForm.submit();
        });
      });
    }
  });
  </script>

  <!-- SEO Structured Data: Organization & Local Business Schema -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Prince Art Packages (Private) Limited",
    "alternateName": "Prince Art Press",
    "url": "https://princeartpackages.com",
    "logo": "https://princeartpackages.com/assets/images/logo.png",
    "telephone": "+92-21-38893400",
    "email": "info@princeartpackages.com",
    "address": [
      {
        "@type": "PostalAddress",
        "name": "Manufacturing Unit 1",
        "streetAddress": "WH-17-A8, ST-1, Sector 38, Korangi Creek Industrial Park",
        "addressLocality": "Karachi",
        "addressRegion": "Sindh",
        "postalCode": "74900",
        "addressCountry": "PK"
      },
      {
        "@type": "PostalAddress",
        "name": "Manufacturing Unit 2",
        "streetAddress": "Plot 239, Opposite Masco, Main Korangi Creek Road",
        "addressLocality": "Karachi",
        "addressRegion": "Sindh",
        "postalCode": "74900",
        "addressCountry": "PK"
      }
    ]
  }
  </script>

  <!-- SEO Structured Data: WebSite Schema with Sitelinks Searchbox -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Prince Art Packages (Private) Limited",
    "url": "https://princeartpackages.com",
    "potentialAction": {
      "@type": "SearchAction",
      "target": {
        "@type": "EntryPoint",
        "urlTemplate": "https://princeartpackages.com/products?q={search_term_string}"
      },
      "query-input": "required name=search_term_string"
    }
  }
  </script>

  <script src="assets/js/site.js?v=<?= file_exists(__DIR__ . '/../assets/js/site.js') ? filemtime(__DIR__ . '/../assets/js/site.js') : time() ?>" defer></script>
</body>
</html>
