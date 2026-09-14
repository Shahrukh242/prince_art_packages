<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

$currentUserId = (int)($_SESSION['admin_id'] ?? 0);
$currentUserRole = $_SESSION['admin_role'] ?? 'admin';

$saved = '';
$error = '';

$tab = $_GET['tab'] ?? 'seo';

/* -----------------------------------------------------------------------
   POST Handlers
----------------------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify($_POST['csrf_token'] ?? null)) {
        $error = 'Security token mismatch. Please try again.';
    } else {
        $action = $_POST['action'] ?? '';

        // 1. ADD NEW USER
        if ($action === 'add_user') {
            $username = trim($_POST['username'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $role     = in_array($_POST['role'] ?? '', ['admin', 'editor']) ? $_POST['role'] : 'editor';
            $password = $_POST['password'] ?? '';

            if (strlen($username) < 3) {
                $error = 'Username must be at least 3 characters long.';
            } elseif (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters long.';
            } else {
                $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = ? LIMIT 1");
                $stmt->execute([$username]);
                if ($stmt->fetch()) {
                    $error = 'Username "' . htmlspecialchars($username) . '" is already taken.';
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $ins = $pdo->prepare("INSERT INTO admin_users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
                    $ins->execute([$username, $email ?: null, $hash, $role]);
                    $roleLabel = ($role === 'admin') ? 'Administrator' : 'Subadmin (Editor)';
                    $saved = 'User "' . htmlspecialchars($username) . '" created successfully as ' . $roleLabel . '.';
                }
            }
        }

        // 2. CHANGE PASSWORD
        elseif ($action === 'change_password') {
            $userId   = (int)($_POST['user_id'] ?? 0);
            $password = $_POST['new_password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

            if (strlen($password) < 6) {
                $error = 'New password must be at least 6 characters long.';
            } elseif ($password !== $confirm) {
                $error = 'Passwords do not match.';
            } else {
                $stmt = $pdo->prepare("SELECT username FROM admin_users WHERE id = ? LIMIT 1");
                $stmt->execute([$userId]);
                $user = $stmt->fetch();

                if (!$user) {
                    $error = 'User not found.';
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $upd = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
                    $upd->execute([$hash, $userId]);
                    $saved = 'Password for "' . htmlspecialchars($user['username']) . '" has been updated successfully.';
                }
            }
        }

        // 3. UPDATE USER ROLE / EMAIL
        elseif ($action === 'update_user') {
            $userId = (int)($_POST['user_id'] ?? 0);
            $email  = trim($_POST['email'] ?? '');
            $role   = in_array($_POST['role'] ?? '', ['admin', 'editor']) ? $_POST['role'] : 'editor';

            if ($userId === $currentUserId && $role !== 'admin') {
                $adminCount = $pdo->query("SELECT COUNT(*) as c FROM admin_users WHERE role = 'admin'")->fetch()['c'];
                if ($adminCount <= 1) {
                    $error = 'Cannot downgrade your own account. At least one Administrator must remain.';
                }
            }

            if (!$error) {
                $upd = $pdo->prepare("UPDATE admin_users SET email = ?, role = ? WHERE id = ?");
                $upd->execute([$email ?: null, $role, $userId]);
                $saved = 'User details updated successfully.';
            }
        }

        // 4. DELETE USER
        elseif ($action === 'delete_user') {
            $userId = (int)($_POST['user_id'] ?? 0);

            if ($userId === $currentUserId) {
                $error = 'You cannot delete your own currently logged-in account.';
            } else {
                $userToDelete = $pdo->prepare("SELECT username, role FROM admin_users WHERE id = ? LIMIT 1");
                $userToDelete->execute([$userId]);
                $target = $userToDelete->fetch();

                if (!$target) {
                    $error = 'User not found.';
                } else {
                    if ($target['role'] === 'admin') {
                        $adminCount = $pdo->query("SELECT COUNT(*) as c FROM admin_users WHERE role = 'admin'")->fetch()['c'];
                        if ($adminCount <= 1) {
                            $error = 'Cannot delete this user. At least one Administrator account must exist.';
                        }
                    }

                    if (!$error) {
                        $del = $pdo->prepare("DELETE FROM admin_users WHERE id = ?");
                        $del->execute([$userId]);
                        $saved = 'User "' . htmlspecialchars($target['username']) . '" has been removed.';
                    }
                }
            }
        }

        // 5. SAVE GENERAL & NOTIFICATIONS SETTINGS
        elseif ($action === 'save_general_settings') {
            $allowedSettings = [
                'notification_email',
                'company_phone',
                'company_email',
                'facebook_url',
                'instagram_url',
                'linkedin_url'
            ];

            foreach ($allowedSettings as $key) {
                if (isset($_POST[$key])) {
                    $val = trim($_POST[$key]);
                    $stmt = $pdo->prepare(
                        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
                    );
                    $stmt->execute([$key, $val]);
                }
            }
            $saved = 'General contact & notification settings updated successfully.';
        }

        // 6. SAVE SMTP SETTINGS
        elseif ($action === 'save_smtp_settings') {
            $smtpSettings = [
                'smtp_host',
                'smtp_port',
                'smtp_username',
                'smtp_password',
                'smtp_security',
                'smtp_from_email',
                'smtp_from_name'
            ];

            foreach ($smtpSettings as $key) {
                if (isset($_POST[$key])) {
                    $val = trim($_POST[$key]);
                    $stmt = $pdo->prepare(
                        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
                    );
                    $stmt->execute([$key, $val]);
                }
            }
            $saved = 'SMTP email settings updated successfully.';
        }

        // 7. SAVE SEO, SITEMAP, ROBOTS & LLMS.TXT SETTINGS
        elseif ($action === 'save_seo_crawlers') {
            $seoKeys = [
                'site_base_url',
                'robots_txt_content',
                'llms_txt_content',
                'custom_sitemap_urls',
                'ga4_measurement_id',
                'gsc_verification_tag',
                'default_meta_description'
            ];

            foreach ($seoKeys as $key) {
                if (isset($_POST[$key])) {
                    $val = trim($_POST[$key]);
                    $stmt = $pdo->prepare(
                        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
                    );
                    $stmt->execute([$key, $val]);
                }
            }

            // Automatically regenerate and write physical static files
            write_seo_crawler_files();
            $saved = 'SEO, XML Sitemap, Robots.txt & LLMs.txt files updated and synchronized successfully!';
        }

        // 8. ONE-CLICK REGENERATE STATIC CRAWLER FILES
        elseif ($action === 'regenerate_crawler_files') {
            $written = write_seo_crawler_files();
            $saved = 'Rebuilt and wrote sitemap.xml, robots.txt, and llms.txt successfully to ' . count($written) . ' directory locations.';
        }
    }
}

/* -----------------------------------------------------------------------
   Load Data
----------------------------------------------------------------------- */
$users = $pdo->query("SELECT id, username, email, role, created_at FROM admin_users ORDER BY role ASC, id ASC")->fetchAll();

$settingsRows = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll();
$settings = [];
foreach ($settingsRows as $row) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

$siteBaseUrl = get_site_base_url();
$robotsTxt   = generate_robots_txt();
$llmsTxt     = generate_llms_txt();
?>

<div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem;">
  <div>
    <h1><i class="ri-settings-3-line"></i> System Settings &amp; SEO Tools</h1>
    <p class="page-subtitle">Configure search engine crawling, XML sitemaps, AI LLMs.txt, email notifications, and administrative users.</p>
  </div>
</div>

<!-- Tabs -->
<div class="page-tabs">
  <a href="settings.php?tab=seo"     class="<?= $tab === 'seo'     ? 'active' : '' ?>"><i class="ri-search-eye-line"></i> SEO, Sitemaps &amp; AI Crawlers</a>
  <a href="settings.php?tab=general" class="<?= $tab === 'general' ? 'active' : '' ?>"><i class="ri-global-line"></i> General &amp; Contact Info</a>
  <a href="settings.php?tab=smtp"    class="<?= $tab === 'smtp'    ? 'active' : '' ?>"><i class="ri-mail-settings-line"></i> SMTP Email Server</a>
  <a href="settings.php?tab=users"   class="<?= $tab === 'users'   ? 'active' : '' ?>"><i class="ri-user-settings-line"></i> User Management</a>
</div>

<?php if ($saved): ?><div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:0.85rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;"><i class="ri-checkbox-circle-fill" style="font-size:1.2rem;"></i> <?= $saved ?></div><?php endif; ?>
<?php if ($error):  ?><div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:0.85rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;"><i class="ri-error-warning-fill" style="font-size:1.2rem;"></i> <?= h($error) ?></div><?php endif; ?>

<!-- ====================================================================
     TAB 1: SEO, SITEMAPS & AI CRAWLERS (ROBOTS.TXT, SITEMAP.XML, LLMS.TXT)
===================================================================== -->
<?php if ($tab === 'seo'): ?>

<form method="post" class="content-form">
  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
  <input type="hidden" name="action"     value="save_seo_crawlers">

  <!-- CARD 1: CANONICAL BASE URL -->
  <div class="panel" style="margin-bottom:1.5rem; border-left:4px solid var(--teal);">
    <h3 style="display:flex; align-items:center; gap:0.5rem; margin-top:0;">
      <i class="ri-global-line text-teal"></i> Canonical Live Website URL
    </h3>
    <p style="color:var(--muted); font-size:0.85rem; margin-bottom:1rem;">
      When you take the website live to production (e.g. <code>https://princeartpackages.com</code>), set this URL so that <strong>Sitemap.xml</strong>, <strong>Robots.txt</strong>, and <strong>LLMs.txt</strong> generate full canonical links to your live domain.
    </p>

    <label style="font-weight:700;">Live Site Base URL
      <input type="url" name="site_base_url" value="<?= h($settings['site_base_url'] ?? '') ?>" placeholder="https://princeartpackages.com" style="font-size:0.95rem;">
      <span class="field-hint">Current detected runtime base: <code><?= h($siteBaseUrl) ?></code></span>
    </label>
  </div>

  <!-- CARD 2: XML SITEMAP (SITEMAP.XML / XITEMAP) -->
  <div class="panel" style="margin-bottom:1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
      <div>
        <h3 style="display:flex; align-items:center; gap:0.5rem; margin:0;">
          <i class="ri-node-tree text-teal"></i> XML Sitemap (sitemap.xml)
        </h3>
        <span style="font-size:0.82rem; color:var(--muted);">Automatically indexes all 13 Core Pages, 8 Products, and 6 Technical Blog Articles</span>
      </div>
      <div style="display:flex; gap:0.5rem;">
        <a href="../public_site/sitemap.php" target="_blank" class="btn" style="background:#0b2545; color:#fff; font-size:0.82rem; padding:0.4rem 0.8rem; text-decoration:none; border-radius:5px; display:inline-flex; align-items:center; gap:0.35rem;">
          <i class="ri-external-link-line"></i> View Live Sitemap.xml
        </a>
      </div>
    </div>

    <p style="font-size:0.85rem; color:var(--text-body); margin-bottom:1rem;">
      Search engines (Google, Bing, Yahoo) use this XML sitemap to discover and index your packaging product catalogs and technical articles.
    </p>

    <label style="font-weight:700;">Additional Custom URLs to Append (Optional)
      <textarea name="custom_sitemap_urls" rows="3" placeholder="one URL per line, e.g.&#10;https://princeartpackages.com/special-dossier.php&#10;https://princeartpackages.com/iso-certificate.pdf"><?= h($settings['custom_sitemap_urls'] ?? '') ?></textarea>
      <span class="field-hint">Enter custom landing page URLs or PDF brochures (one per line) to include in the sitemap.</span>
    </label>
  </div>

  <!-- CARD 3: ROBOTS.TXT MANAGER -->
  <div class="panel" style="margin-bottom:1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
      <div>
        <h3 style="display:flex; align-items:center; gap:0.5rem; margin:0;">
          <i class="ri-robot-2-line text-teal"></i> Robots.txt Crawler Directives
        </h3>
        <span style="font-size:0.82rem; color:var(--muted);">Instructs Googlebot, Bingbot, and web crawlers which areas to index</span>
      </div>
      <div>
        <a href="../public_site/robots.php" target="_blank" class="btn" style="background:#0b2545; color:#fff; font-size:0.82rem; padding:0.4rem 0.8rem; text-decoration:none; border-radius:5px; display:inline-flex; align-items:center; gap:0.35rem;">
          <i class="ri-external-link-line"></i> View Live Robots.txt
        </a>
      </div>
    </div>

    <label style="font-weight:700;">Robots.txt Content
      <textarea name="robots_txt_content" rows="8" style="font-family:monospace; font-size:0.9rem; line-height:1.5; background:#f8fafc; border:1.5px solid #cbd5e1;"><?= h($settings['robots_txt_content'] ?? $robotsTxt) ?></textarea>
      <span class="field-hint">Defines permissions for web crawlers and specifies the Sitemap location.</span>
    </label>
  </div>

  <!-- CARD 4: LLMS.TXT (AI & LLM CRAWLER KNOWLEDGE FILE) -->
  <div class="panel" style="margin-bottom:1.5rem; border-left:4px solid #8b5cf6;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
      <div>
        <h3 style="display:flex; align-items:center; gap:0.5rem; margin:0;">
          <i class="ri-brain-line" style="color:#8b5cf6;"></i> LLMs.txt (AI Search &amp; Agent Knowledge File)
        </h3>
        <span style="font-size:0.82rem; color:var(--muted);">Emerging web standard used by ChatGPT Search, Perplexity, Gemini &amp; Claude AI agents</span>
      </div>
      <div>
        <a href="../public_site/llms.php" target="_blank" class="btn" style="background:#8b5cf6; color:#fff; font-size:0.82rem; padding:0.4rem 0.8rem; text-decoration:none; border-radius:5px; display:inline-flex; align-items:center; gap:0.35rem;">
          <i class="ri-external-link-line"></i> View Live LLMs.txt
        </a>
      </div>
    </div>

    <p style="font-size:0.85rem; color:var(--text-body); margin-bottom:1rem;">
      When AI crawlers scan your domain, <code>/llms.txt</code> provides a structured summary of Prince Art Packages' ISO 9001:2015 certifications, cGMP plant units in Korangi Creek, and complete pharmaceutical secondary packaging capabilities.
    </p>

    <label style="font-weight:700;">LLMs.txt Markdown Content
      <textarea name="llms_txt_content" rows="12" style="font-family:monospace; font-size:0.88rem; line-height:1.55; background:#f8fafc; border:1.5px solid #cbd5e1;"><?= h($settings['llms_txt_content'] ?? $llmsTxt) ?></textarea>
      <span class="field-hint">Markdown format describing company expertise, certifications, and product links for AI search engines.</span>
    </label>
  </div>

  <!-- CARD 5: GOOGLE ANALYTICS & SEARCH CONSOLE -->
  <div class="panel" style="margin-bottom:1.5rem;">
    <h3 style="display:flex; align-items:center; gap:0.5rem; margin-top:0; margin-bottom:1rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
      <i class="ri-line-chart-line text-teal"></i> Google Analytics &amp; Search Console Tags
    </h3>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1rem;">
      <label>Google Analytics 4 (GA4) Measurement ID
        <input type="text" name="ga4_measurement_id" value="<?= h($settings['ga4_measurement_id'] ?? '') ?>" placeholder="G-XXXXXXXXXX">
        <span class="field-hint">e.g. <code>G-ABC123XYZ</code></span>
      </label>

      <label>Google Search Console Verification Tag / Code
        <input type="text" name="gsc_verification_tag" value="<?= h($settings['gsc_verification_tag'] ?? '') ?>" placeholder="google-site-verification=XXXXXXXXXXXX">
        <span class="field-hint">Meta tag verification provided by Google Search Console.</span>
      </label>
    </div>

    <label>Site-wide Fallback Meta Description
      <textarea name="default_meta_description" rows="2" placeholder="ISO 9001:2015 & FSC certified pharmaceutical secondary packaging manufacturer in Karachi, Pakistan."><?= h($settings['default_meta_description'] ?? '') ?></textarea>
      <span class="field-hint">Used for dynamic pages that lack individual meta descriptions.</span>
    </label>
  </div>

  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
    <button type="submit" style="background:var(--teal); color:#fff; font-size:1rem; font-weight:700; padding:0.75rem 2rem; border-radius:6px; cursor:pointer; box-shadow:0 4px 14px rgba(0,168,150,0.3);">
      <i class="ri-save-line"></i> Save SEO Settings &amp; Sync Crawler Files
    </button>
  </div>
</form>

<?php endif; ?>

<!-- ====================================================================
     TAB 2: GENERAL & NOTIFICATIONS
===================================================================== -->
<?php if ($tab === 'general'): ?>

<div class="panel">
  <h3><i class="ri-mail-line"></i> RFQ Lead Notifications &amp; Inquiries</h3>
  <p style="color:var(--muted);font-size:0.85rem;margin-bottom:1.5rem;">
    RFQ inquiries submitted from the website modal and contact page are recorded in the database and sent to the address below.
  </p>

  <form method="post" class="content-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action"     value="save_general_settings">

    <label style="margin-bottom:1.25rem;">Lead Notification Email <sup style="color:var(--teal);">*</sup>
      <input type="email" name="notification_email" value="<?= h($settings['notification_email'] ?? 'princeartpackages@gmail.com') ?>" placeholder="princeartpackages@gmail.com">
      <span class="field-hint">Where automated quotation notifications are delivered.</span>
    </label>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
      <label>Official Company Phone
        <input type="text" name="company_phone" value="<?= h($settings['company_phone'] ?? '+92 21-38893400-3') ?>" placeholder="+92 21-38893400-3">
        <span class="field-hint">Displayed in the header and footer.</span>
      </label>

      <label>Official Company Email
        <input type="email" name="company_email" value="<?= h($settings['company_email'] ?? 'info@princeartpackages.com') ?>" placeholder="info@princeartpackages.com">
        <span class="field-hint">Displayed in the footer and contact sections.</span>
      </label>
    </div>

    <h4 style="margin:1.5rem 0 0.75rem 0;font-size:0.95rem;color:var(--navy);"><i class="ri-share-line"></i> Social Media Profiles</h4>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:1.25rem;margin-bottom:1.5rem;">
      <label>Facebook URL
        <input type="url" name="facebook_url" value="<?= h($settings['facebook_url'] ?? '') ?>" placeholder="https://facebook.com/princeartpackages">
      </label>
      <label>LinkedIn URL
        <input type="url" name="linkedin_url" value="<?= h($settings['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/company/princeartpackages">
      </label>
      <label>Instagram URL
        <input type="url" name="instagram_url" value="<?= h($settings['instagram_url'] ?? '') ?>" placeholder="https://instagram.com/princeartpackages">
      </label>
    </div>

    <button type="submit" style="background:var(--teal);cursor:pointer;"><i class="ri-save-line"></i> Save General Settings</button>
  </form>
</div>

<?php endif; ?>

<!-- ====================================================================
     TAB 3: SMTP EMAIL SERVER
===================================================================== -->
<?php if ($tab === 'smtp'): ?>

<div class="panel" style="margin-bottom:1.5rem;">
  <h3><i class="ri-mail-send-line"></i> SMTP Mail Server Credentials</h3>
  <p style="color:var(--muted);font-size:0.85rem;margin-bottom:1.5rem;">
    Configure SMTP to guarantee reliable email delivery for RFQ lead notifications.
  </p>

  <form method="post" class="content-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action"     value="save_smtp_settings">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
      <label>SMTP Host Server <sup style="color:var(--teal);">*</sup>
        <input type="text" name="smtp_host" value="<?= h($settings['smtp_host'] ?? '') ?>" placeholder="smtp.gmail.com">
        <span class="field-hint">Gmail: <code>smtp.gmail.com</code> &nbsp;|&nbsp; cPanel: <code>mail.princeartpackages.com</code></span>
      </label>
      <label>SMTP Port <sup style="color:var(--teal);">*</sup>
        <input type="number" name="smtp_port" value="<?= h($settings['smtp_port'] ?? '587') ?>" placeholder="587">
        <span class="field-hint">TLS: <code>587</code> &nbsp;|&nbsp; SSL: <code>465</code> &nbsp;|&nbsp; Non-secure: <code>25</code></span>
      </label>
      <label>SMTP Username <sup style="color:var(--teal);">*</sup>
        <input type="text" name="smtp_username" value="<?= h($settings['smtp_username'] ?? '') ?>" placeholder="info@princeartpackages.com">
        <span class="field-hint">Usually your full email address.</span>
      </label>
      <label>SMTP Password <sup style="color:var(--teal);">*</sup>
        <input type="password" name="smtp_password" value="<?= h($settings['smtp_password'] ?? '') ?>" placeholder="••••••••••••">
        <span class="field-hint">For Gmail, use an App Password.</span>
      </label>
      <label>Security Protocol
        <select name="smtp_security">
          <option value="tls" <?= ($settings['smtp_security'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS (Recommended — Port 587)</option>
          <option value="ssl" <?= ($settings['smtp_security'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL (Port 465)</option>
          <option value="none" <?= ($settings['smtp_security'] ?? '') === 'none' ? 'selected' : '' ?>>None (Not Recommended)</option>
        </select>
      </label>
      <label>From Email Address
        <input type="email" name="smtp_from_email" value="<?= h($settings['smtp_from_email'] ?? 'info@princeartpackages.com') ?>" placeholder="info@princeartpackages.com">
        <span class="field-hint">Sender address appearing in recipient inbox.</span>
      </label>
    </div>

    <label style="margin-bottom:1.5rem;">From Name (Sender Display Name)
      <input type="text" name="smtp_from_name" value="<?= h($settings['smtp_from_name'] ?? 'Prince Art Packages') ?>" placeholder="Prince Art Packages">
      <span class="field-hint">e.g. <em>Prince Art Packages &lt;info@princeartpackages.com&gt;</em></span>
    </label>

    <button type="submit" style="background:var(--teal);cursor:pointer;"><i class="ri-save-line"></i> Save SMTP Settings</button>
  </form>
</div>

<?php endif; ?>

<!-- ====================================================================
     TAB 4: USER MANAGEMENT
===================================================================== -->
<?php if ($tab === 'users'): ?>

<div class="panel" style="margin-bottom:1.5rem;">
  <h3>
    <i class="ri-team-line"></i> Existing Administrators &amp; Subadmins
    <span style="margin-left:auto;font-size:0.8rem;font-weight:400;color:var(--muted);"><?= count($users) ?> account<?= count($users) !== 1 ? 's' : '' ?></span>
  </h3>
  <p style="color:var(--muted);font-size:0.85rem;margin-bottom:1.25rem;">
    <strong>Admin</strong> has full system permissions. <strong>Subadmin (Editor)</strong> can update page content and view leads without system configuration access.
  </p>

  <table class="data-table">
    <thead>
      <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $u): ?>
      <?php $isCurrent = ($u['id'] == $currentUserId); ?>
      <tr>
        <td style="font-weight:700;">
          <?= h($u['username']) ?>
          <?php if ($isCurrent): ?>
            <span style="font-size:0.72rem;background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:20px;margin-left:0.4rem;font-weight:600;">You</span>
          <?php endif; ?>
        </td>
        <td><?= h($u['email'] ?: '—') ?></td>
        <td>
          <?php if ($u['role'] === 'admin'): ?>
            <span style="background:#fef3c7;color:#92400e;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;display:inline-flex;align-items:center;gap:0.25rem;">
              <i class="ri-shield-star-line"></i> Administrator
            </span>
          <?php else: ?>
            <span style="background:#e0f2fe;color:#0369a1;padding:3px 10px;border-radius:20px;font-size:0.75rem;font-weight:700;display:inline-flex;align-items:center;gap:0.25rem;">
              <i class="ri-user-line"></i> Subadmin (Editor)
            </span>
          <?php endif; ?>
        </td>
        <td style="color:var(--muted);font-size:0.82rem;"><?= h(date('d M Y', strtotime($u['created_at']))) ?></td>
        <td>
          <button type="button" onclick="openPasswordModal(<?= (int)$u['id'] ?>, '<?= addslashes(h($u['username'])) ?>')"
                  style="padding:0.35rem 0.75rem;font-size:0.8rem;background:#f3f4f6;color:var(--navy);border:1px solid var(--border);cursor:pointer;">
            <i class="ri-key-2-line"></i> Change Password
          </button>
          
          <?php if (!$isCurrent): ?>
            <form method="post" style="display:inline;" onsubmit="return confirm('Permanently remove user \'<?= addslashes(h($u['username'])) ?>\'?');">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
              <input type="hidden" name="action"     value="delete_user">
              <input type="hidden" name="user_id"    value="<?= (int)$u['id'] ?>">
              <button type="submit" style="padding:0.35rem 0.65rem;font-size:0.8rem;background:#fee2e2;color:#b91c1c;border:1px solid #fecaca;margin-left:0.25rem;cursor:pointer;">
                <i class="ri-delete-bin-line"></i>
              </button>
            </form>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>

<div class="panel">
  <h3><i class="ri-user-add-line"></i> Create New User Account</h3>
  <form method="post" class="content-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action"     value="add_user">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">
      <label>Username <sup style="color:var(--teal);">*</sup>
        <input type="text" name="username" required placeholder="e.g. tariq_admin" minlength="3">
      </label>
      <label>Email Address
        <input type="email" name="email" placeholder="user@company.com">
      </label>
      <label>Password <sup style="color:var(--teal);">*</sup>
        <input type="password" name="password" required placeholder="Minimum 6 characters" minlength="6">
      </label>
      <label>Account Role
        <select name="role">
          <option value="editor">Subadmin (Editor) — Content &amp; Leads only</option>
          <option value="admin">Administrator — Full System Access</option>
        </select>
      </label>
    </div>

    <button type="submit" style="background:var(--teal);cursor:pointer;"><i class="ri-user-add-line"></i> Create User Account</button>
  </form>
</div>

<!-- Password Modal -->
<div id="passwordModal" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.55);z-index:9999;align-items:center;justify-content:center;padding:1.5rem;">
  <div style="background:#fff;border-radius:10px;max-width:440px;width:100%;box-shadow:var(--shadow-lg);overflow:hidden;">
    <div style="background:var(--navy);color:#fff;padding:1.1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;">
      <h3 style="margin:0;font-size:1.05rem;color:#fff;"><i class="ri-key-2-line"></i> Change Password: <span id="modalUsername" style="color:var(--gold);"></span></h3>
      <button type="button" onclick="closePasswordModal()" style="background:transparent;border:none;color:#fff;font-size:1.2rem;cursor:pointer;"><i class="ri-close-line"></i></button>
    </div>
    <form method="post" style="padding:1.5rem;" class="content-form">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <input type="hidden" name="action"     value="change_password">
      <input type="hidden" name="user_id"    id="modalUserId" value="">

      <label style="margin-bottom:1rem;">New Password
        <input type="password" name="new_password" required placeholder="Minimum 6 characters" minlength="6">
      </label>

      <label style="margin-bottom:1.5rem;">Confirm New Password
        <input type="password" name="confirm_password" required placeholder="Repeat new password" minlength="6">
      </label>

      <div style="display:flex;justify-content:flex-end;gap:0.75rem;">
        <button type="button" onclick="closePasswordModal()" style="background:#f3f4f6;color:var(--text);border:1px solid var(--border);cursor:pointer;">Cancel</button>
        <button type="submit" style="background:var(--teal);cursor:pointer;"><i class="ri-check-line"></i> Update Password</button>
      </div>
    </form>
  </div>
</div>

<script>
function openPasswordModal(id, username) {
  document.getElementById('modalUserId').value = id;
  document.getElementById('modalUsername').textContent = username;
  document.getElementById('passwordModal').style.display = 'flex';
}
function closePasswordModal() {
  document.getElementById('passwordModal').style.display = 'none';
}
</script>

<?php endif; ?>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
