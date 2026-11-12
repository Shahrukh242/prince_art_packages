<?php
require __DIR__ . '/includes/layout_top.php';
$pdo = get_db();

// Ensure email column exists in admin_users
try {
    $pdo->exec("ALTER TABLE admin_users ADD COLUMN email VARCHAR(150) NULL AFTER username");
} catch (\Throwable $e) {
    // Column may already exist
}

$currentUserId = (int)($_SESSION['admin_id'] ?? 0);
$currentUserRole = $_SESSION['admin_role'] ?? 'admin';

$saved = '';
$error = '';

$tab = $_GET['tab'] ?? 'seo';

/* -----------------------------------------------------------------------
   POST Handlers
----------------------------------------------------------------------- */
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
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

        // 9b. SAVE QUICK PAGE SEO META
        elseif ($action === 'save_page_seo_quick') {
            $pageId   = (int)($_POST['page_id'] ?? 0);
            $metaTitle = trim($_POST['meta_title'] ?? '');
            $metaDesc  = trim($_POST['meta_description'] ?? '');
            if ($pageId > 0) {
                $stmt = $pdo->prepare("UPDATE pages SET meta_title = ?, meta_description = ? WHERE id = ?");
                $stmt->execute([$metaTitle, $metaDesc, $pageId]);
                $saved = 'SEO metadata updated successfully.';
            }
        }
        // 9. SAVE SECURITY & CAPTCHA SETTINGS
        elseif ($action === 'save_security_captcha') {
            $secKeys = [
                'admin_captcha_type',
                'turnstile_site_key',
                'turnstile_secret_key',
                'recaptcha_site_key',
                'recaptcha_secret_key'
            ];

            foreach ($secKeys as $key) {
                if (isset($_POST[$key])) {
                    $val = trim($_POST[$key]);
                    $stmt = $pdo->prepare(
                        "INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)
                         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
                    );
                    $stmt->execute([$key, $val]);
                }
            }
            $saved = 'Admin Login Security &amp; CAPTCHA settings updated successfully.';
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

$allPagesSeo = $pdo->query("SELECT id, slug, title, meta_title, meta_description FROM pages ORDER BY FIELD(slug, 'home', 'about', 'products', 'capabilities', 'innovation', 'quality', 'industries', 'case-studies', 'contact', 'blog', 'privacy', 'terms'), title ASC")->fetchAll();
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
  <a href="settings.php?tab=seo"      class="<?= $tab === 'seo'      ? 'active' : '' ?>"><i class="ri-search-eye-line"></i> SEO, Sitemaps &amp; AI Crawlers</a>
  <a href="settings.php?tab=general"  class="<?= $tab === 'general'  ? 'active' : '' ?>"><i class="ri-global-line"></i> General &amp; Contact Info</a>
  <a href="settings.php?tab=security" class="<?= $tab === 'security' ? 'active' : '' ?>"><i class="ri-shield-keyhole-line"></i> Admin CAPTCHA &amp; Security</a>
  <a href="settings.php?tab=backup"   class="<?= $tab === 'backup'   ? 'active' : '' ?>"><i class="ri-database-2-line"></i> Database Backup &amp; Sync</a>
  <a href="settings.php?tab=smtp"     class="<?= $tab === 'smtp'     ? 'active' : '' ?>"><i class="ri-mail-settings-line"></i> SMTP Email Server</a>
  <a href="settings.php?tab=users"    class="<?= $tab === 'users'    ? 'active' : '' ?>"><i class="ri-user-settings-line"></i> User Management</a>
</div>

<?php if ($saved): ?><div class="alert alert-success" style="background:#d1fae5;color:#065f46;padding:0.85rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;"><i class="ri-checkbox-circle-fill" style="font-size:1.2rem;"></i> <?= $saved ?></div><?php endif; ?>
<?php if ($error):  ?><div class="alert alert-danger" style="background:#fee2e2;color:#991b1b;padding:0.85rem 1.25rem;border-radius:8px;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;"><i class="ri-error-warning-fill" style="font-size:1.2rem;"></i> <?= h($error) ?></div><?php endif; ?>

<!-- ====================================================================
     TAB 1: SEO, SITEMAPS & AI CRAWLERS (ROBOTS.TXT, SITEMAP.XML, LLMS.TXT)
===================================================================== -->
<?php if ($tab === 'seo'): ?>

<!-- CARD 0: PAGE-BY-PAGE SEO META TITLES & DESCRIPTIONS -->
<div class="panel" style="margin-bottom:1.75rem; border-left:4px solid var(--teal); background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.75rem; margin-bottom:1rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
    <div>
      <h3 style="display:flex; align-items:center; gap:0.5rem; margin:0; font-size:1.15rem; color:var(--navy);">
        <i class="ri-pages-line text-teal" style="font-size:1.3rem;"></i> Page-by-Page SEO Meta Titles &amp; Meta Descriptions
      </h3>
      <span style="font-size:0.83rem; color:var(--muted);">View and edit the exact HTML &lt;title&gt; and &lt;meta name="description"&gt; tags for all <?= count($allPagesSeo) ?> core website pages.</span>
    </div>
    <div>
      <a href="content.php" class="btn" style="background:#0b2545; color:#fff; font-size:0.82rem; padding:0.4rem 0.85rem; border-radius:5px; text-decoration:none; display:inline-flex; align-items:center; gap:0.35rem;">
        <i class="ri-file-text-line"></i> Open Full Page Content Editor
      </a>
    </div>
  </div>

  <div style="display:flex; flex-direction:column; gap:0.65rem;">
    <?php foreach ($allPagesSeo as $pSeo): ?>
      <details style="border:1px solid var(--border); border-radius:8px; background:#fafbfc; overflow:hidden;">
        <summary style="padding:0.75rem 1rem; cursor:pointer; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.5rem; user-select:none;">
          <div style="display:flex; align-items:center; gap:0.6rem;">
            <strong style="color:var(--navy); font-size:0.92rem;"><?= h($pSeo['title']) ?></strong>
            <code style="font-size:0.78rem; background:#e2e8f0; color:#334155; padding:2px 6px; border-radius:4px;">/<?= h($pSeo['slug'] === 'home' ? '' : $pSeo['slug']) ?></code>
          </div>
          <div style="display:flex; align-items:center; gap:0.75rem;">
            <?php if (empty($pSeo['meta_title'])): ?>
              <span style="font-size:0.72rem; background:#fee2e2; color:#991b1b; padding:2px 6px; border-radius:4px; font-weight:600;">Missing Title</span>
            <?php else: ?>
              <span style="font-size:0.72rem; background:#d1fae5; color:#065f46; padding:2px 6px; border-radius:4px; font-weight:600;">Title: <?= mb_strlen($pSeo['meta_title']) ?> chars</span>
            <?php endif; ?>
            <span style="font-size:0.82rem; color:var(--teal); font-weight:600; display:inline-flex; align-items:center; gap:2px;"><i class="ri-edit-line"></i> Edit SEO</span>
            <i class="ri-arrow-down-s-line" style="color:var(--muted);"></i>
          </div>
        </summary>

        <div style="padding:1rem 1.25rem; background:#fff; border-top:1px solid var(--border);">
          <form method="post" style="margin:0;">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="action"     value="save_page_seo_quick">
            <input type="hidden" name="page_id"    value="<?= (int)$pSeo['id'] ?>">

            <div style="margin-bottom:0.85rem;">
              <div style="display:flex; justify-content:space-between; margin-bottom:0.25rem;">
                <label style="font-size:0.84rem; font-weight:700; color:var(--navy); margin:0;">
                  SEO Meta Title <span style="font-weight:400; color:var(--muted);">(Title Tag in browser &amp; Google)</span>
                </label>
                <span style="font-size:0.75rem; color:var(--muted);"><?= mb_strlen($pSeo['meta_title'] ?? '') ?> / 60 recommended</span>
              </div>
              <input type="text" name="meta_title" value="<?= h($pSeo['meta_title']) ?>" placeholder="e.g. <?= h($pSeo['title']) ?> | Prince Art Packages" maxlength="160" style="width:100%; font-size:0.9rem; padding:0.55rem 0.75rem; border:1px solid var(--border); border-radius:5px;">
            </div>

            <div style="margin-bottom:0.85rem;">
              <div style="display:flex; justify-content:space-between; margin-bottom:0.25rem;">
                <label style="font-size:0.84rem; font-weight:700; color:var(--navy); margin:0;">
                  SEO Meta Description <span style="font-weight:400; color:var(--muted);">(Search snippet, 140–160 chars)</span>
                </label>
                <span style="font-size:0.75rem; color:var(--muted);"><?= mb_strlen($pSeo['meta_description'] ?? '') ?> / 160 recommended</span>
              </div>
              <textarea name="meta_description" rows="2" placeholder="Brief summary describing <?= h($pSeo['title']) ?>..." maxlength="300" style="width:100%; font-size:0.88rem; padding:0.55rem 0.75rem; border:1px solid var(--border); border-radius:5px; line-height:1.5;"><?= h($pSeo['meta_description']) ?></textarea>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
              <button type="submit" style="background:var(--teal); color:#fff; font-size:0.85rem; font-weight:700; padding:0.45rem 1.1rem; border-radius:5px; cursor:pointer; display:inline-flex; align-items:center; gap:0.35rem;">
                <i class="ri-save-line"></i> Save SEO for <?= h($pSeo['title']) ?>
              </button>
              <a href="content.php?page=<?= urlencode($pSeo['slug']) ?>" style="font-size:0.82rem; color:var(--navy); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:0.25rem;">
                Edit Full Content Blocks for this Page &rarr;
              </a>
            </div>
          </form>
        </div>
      </details>
    <?php endforeach; ?>
  </div>
</div>

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
      <input type="email" name="notification_email" value="<?= h($settings['notification_email'] ?? 'princeartpress@gmail.com') ?>" placeholder="princeartpress@gmail.com">
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
     TAB: ADMIN CAPTCHA & SECURITY
===================================================================== -->
<?php if ($tab === 'security'): ?>

<div class="panel" style="margin-bottom:1.5rem;">
  <h3><i class="ri-shield-keyhole-line text-teal"></i> Admin Login Security &amp; CAPTCHA</h3>
  <p style="color:var(--muted);font-size:0.85rem;margin-bottom:1.5rem;">
    Enforce human verification on <code>/admin/login.php</code> before login attempts are evaluated to prevent brute-force attacks and credential stuffing bots.
  </p>

  <form method="post" class="content-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action"     value="save_security_captcha">

    <!-- Provider Selection -->
    <div style="margin-bottom:1.75rem;">
      <label style="font-weight:700; margin-bottom:0.75rem; display:block; font-size:1rem; color:var(--navy);">Select Protection Provider</label>
      <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:1rem;">
        
        <label style="display:flex; align-items:flex-start; gap:0.75rem; padding:1.1rem; border:2px solid <?= ($settings['admin_captcha_type'] ?? 'turnstile') === 'turnstile' ? 'var(--teal)' : 'var(--border)' ?>; border-radius:10px; cursor:pointer; background:<?= ($settings['admin_captcha_type'] ?? 'turnstile') === 'turnstile' ? '#f0fdfa' : '#fff' ?>;">
          <input type="radio" name="admin_captcha_type" value="turnstile" <?= ($settings['admin_captcha_type'] ?? 'turnstile') === 'turnstile' ? 'checked' : '' ?> style="margin-top:0.25rem;">
          <div>
            <strong style="color:var(--navy); font-size:0.95rem; display:flex; align-items:center; gap:0.35rem;">
              <i class="ri-cloud-line text-teal"></i> Cloudflare Turnstile <span style="background:#ccfbf1; color:#0f766e; font-size:0.7rem; padding:0.15rem 0.5rem; border-radius:4px; font-weight:700;">Active</span>
            </strong>
            <span style="display:block; font-size:0.8rem; color:var(--muted); margin-top:0.35rem;">Modern, privacy-preserving interactive checkbox widget. Zero friction, works out-of-the-box on localhost and live domains.</span>
          </div>
        </label>

        <label style="display:flex; align-items:flex-start; gap:0.75rem; padding:1.1rem; border:2px solid <?= ($settings['admin_captcha_type'] ?? '') === 'recaptcha' ? 'var(--teal)' : 'var(--border)' ?>; border-radius:10px; cursor:pointer; background:<?= ($settings['admin_captcha_type'] ?? '') === 'recaptcha' ? '#f0fdfa' : '#fff' ?>;">
          <input type="radio" name="admin_captcha_type" value="recaptcha" <?= ($settings['admin_captcha_type'] ?? '') === 'recaptcha' ? 'checked' : '' ?> style="margin-top:0.25rem;">
          <div>
            <strong style="color:var(--navy); font-size:0.95rem; display:flex; align-items:center; gap:0.35rem;">
              <i class="ri-google-line text-teal"></i> Google reCAPTCHA v2
            </strong>
            <span style="display:block; font-size:0.8rem; color:var(--muted); margin-top:0.35rem;">Classic "I'm not a robot" checkbox challenge. Requires your domain to be registered in Google reCAPTCHA console.</span>
          </div>
        </label>

        <label style="display:flex; align-items:flex-start; gap:0.75rem; padding:1.1rem; border:2px solid <?= ($settings['admin_captcha_type'] ?? '') === 'built_in' ? 'var(--teal)' : 'var(--border)' ?>; border-radius:10px; cursor:pointer; background:<?= ($settings['admin_captcha_type'] ?? '') === 'built_in' ? '#f0fdfa' : '#fff' ?>;">
          <input type="radio" name="admin_captcha_type" value="built_in" <?= ($settings['admin_captcha_type'] ?? '') === 'built_in' ? 'checked' : '' ?> style="margin-top:0.25rem;">
          <div>
            <strong style="color:var(--navy); font-size:0.95rem; display:flex; align-items:center; gap:0.35rem;">
              <i class="ri-checkbox-circle-line text-teal"></i> Built-in Robot Checkbox
            </strong>
            <span style="display:block; font-size:0.8rem; color:var(--muted); margin-top:0.35rem;">Interactive "I'm not a robot" verification box matching the UI style. Zero external API calls, works 100% offline.</span>
          </div>
        </label>

        <label style="display:flex; align-items:flex-start; gap:0.75rem; padding:1.1rem; border:2px solid <?= ($settings['admin_captcha_type'] ?? '') === 'disabled' ? '#ef4444' : 'var(--border)' ?>; border-radius:10px; cursor:pointer; background:<?= ($settings['admin_captcha_type'] ?? '') === 'disabled' ? '#fef2f2' : '#fff' ?>;">
          <input type="radio" name="admin_captcha_type" value="disabled" <?= ($settings['admin_captcha_type'] ?? '') === 'disabled' ? 'checked' : '' ?> style="margin-top:0.25rem;">
          <div>
            <strong style="color:#b91c1c; font-size:0.95rem; display:flex; align-items:center; gap:0.35rem;">
              <i class="ri-close-circle-line"></i> Disabled
            </strong>
            <span style="display:block; font-size:0.8rem; color:var(--muted); margin-top:0.35rem;">No CAPTCHA verification before login. Convenient for quick local testing, but vulnerable to bot attacks.</span>
          </div>
        </label>

      </div>
    </div>

    <!-- Cloudflare Turnstile Panel -->
    <div class="panel" style="background:#f8fafc; border:1px solid #e2e8f0; margin-bottom:1.5rem; border-left:4px solid var(--teal);">
      <h4 style="margin:0 0 0.5rem 0; color:var(--navy); display:flex; align-items:center; gap:0.5rem;">
        <i class="ri-cloud-line text-teal"></i> Cloudflare Turnstile API Keys
      </h4>
      <p style="font-size:0.82rem; color:var(--muted); margin-bottom:1.25rem;">
        Pre-populated with Cloudflare's official universal testing keys (<code>1x00000000000000000000BB</code> for interactive checkbox). Replace with your production site keys when launching on your custom domain via <a href="https://dash.cloudflare.com/?to=/:account/turnstile" target="_blank" style="color:var(--teal); font-weight:600;">dash.cloudflare.com</a>.
      </p>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
        <label style="font-weight:700;">Turnstile Site Key (Public)
          <input type="text" name="turnstile_site_key" value="<?= h($settings['turnstile_site_key'] ?? '1x00000000000000000000BB') ?>" placeholder="1x00000000000000000000BB">
          <span class="field-hint">Public site key embedded in the login page.</span>
        </label>

        <label style="font-weight:700;">Turnstile Secret Key (Private)
          <input type="text" name="turnstile_secret_key" value="<?= h($settings['turnstile_secret_key'] ?? '1x0000000000000000000000000000000AA') ?>" placeholder="1x0000000000000000000000000000000AA">
          <span class="field-hint">Private secret key used for server-side siteverify.</span>
        </label>
      </div>
    </div>

    <!-- Google reCAPTCHA Panel -->
    <div class="panel" style="background:#f8fafc; border:1px solid #e2e8f0; margin-bottom:1.5rem; border-left:4px solid #4285F4;">
      <h4 style="margin:0 0 0.5rem 0; color:var(--navy); display:flex; align-items:center; gap:0.5rem;">
        <i class="ri-google-line" style="color:#4285F4;"></i> Google reCAPTCHA v2 (Checkbox) API Keys
      </h4>
      <p style="font-size:0.82rem; color:var(--muted); margin-bottom:1.25rem;">
        If using Google reCAPTCHA, register your domain at <a href="https://www.google.com/recaptcha/admin" target="_blank" style="color:#4285F4; font-weight:600;">Google reCAPTCHA Console</a> and select <strong>reCAPTCHA v2 ("I'm not a robot" Checkbox)</strong>.
      </p>

      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
        <label style="font-weight:700;">reCAPTCHA Site Key (Public)
          <input type="text" name="recaptcha_site_key" value="<?= h($settings['recaptcha_site_key'] ?? '') ?>" placeholder="e.g. 6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI">
          <span class="field-hint">Site key for the client-side reCAPTCHA widget.</span>
        </label>

        <label style="font-weight:700;">reCAPTCHA Secret Key (Private)
          <input type="text" name="recaptcha_secret_key" value="<?= h($settings['recaptcha_secret_key'] ?? '') ?>" placeholder="e.g. 6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe">
          <span class="field-hint">Secret key for server validation.</span>
        </label>
      </div>
    </div>

    <button type="submit" style="background:var(--teal); color:#fff; font-size:1rem; font-weight:700; padding:0.75rem 2rem; border-radius:6px; cursor:pointer;">
      <i class="ri-save-line"></i> Save Security &amp; CAPTCHA Settings
    </button>
  </form>
</div>

<?php endif; ?>

<!-- ====================================================================
     TAB: DATABASE BACKUP & SYNC
===================================================================== -->
<?php if ($tab === 'backup'): ?>

<div class="panel" style="margin-bottom:1.5rem;">
  <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.25rem; border-bottom:1px solid var(--border); padding-bottom:1rem;">
    <div>
      <h3 style="display:flex; align-items:center; gap:0.5rem; margin:0;">
        <i class="ri-database-2-line text-teal"></i> Live Database Backup &amp; Localhost Sync
      </h3>
      <span style="font-size:0.85rem; color:var(--muted);">Export all content, CTA button colors, products, images, and settings as a standard MySQL SQL dump file.</span>
    </div>
    <div>
      <a href="export_db.php" class="btn" style="background:#0b2545; color:#fff; font-size:0.95rem; font-weight:700; padding:0.65rem 1.4rem; border-radius:6px; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
        <i class="ri-download-cloud-2-line" style="font-size:1.15rem;"></i> Download Database Backup (.sql)
      </a>
    </div>
  </div>

  <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:1.25rem; margin-bottom:1.5rem;">
    <h4 style="color:#166534; margin:0 0 0.5rem; display:flex; align-items:center; gap:0.4rem;">
      <i class="ri-information-line"></i> Why downloading website files from File Manager doesn't include Admin Panel changes:
    </h4>
    <p style="color:#14532d; font-size:0.88rem; line-height:1.6; margin:0;">
      Whenever you change button colors, edit hero text, upload product photos, or adjust CTA visibility in this Admin Dashboard, those changes are saved directly into your <strong>MySQL Database</strong>, NOT in the physical PHP or HTML files.
      When you download files from your hosting File Manager, you are only downloading the code files from the server's hard drive — the database is hosted separately.
      <strong>To sync your changes to localhost, simply click "Download Database Backup (.sql)" above, then import that file into your local XAMPP MySQL.</strong>
    </p>
  </div>

  <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:1.25rem;">
    <h4 style="color:var(--navy); margin:0 0 0.75rem;">
      <i class="ri-refresh-line text-teal"></i> How to Import this Backup on Localhost (3 Easy Steps):
    </h4>
    <ol style="margin:0; padding-left:1.25rem; font-size:0.88rem; color:var(--text-body); line-height:1.8;">
      <li>Click the blue <strong>"Download Database Backup (.sql)"</strong> button above to save the <code>.sql</code> file to your computer.</li>
      <li>Open your local XAMPP phpMyAdmin in your browser: <a href="http://localhost/phpmyadmin" target="_blank" style="color:var(--teal); font-weight:700;">http://localhost/phpmyadmin</a>.</li>
      <li>Select the database <code>prince_art_packages</code> on the left sidebar, click the <strong>Import</strong> tab at the top, choose your downloaded <code>.sql</code> file, and click <strong>Import</strong> (or <strong>Go</strong>).</li>
    </ol>
    <p style="font-size:0.82rem; color:var(--muted); margin:0.75rem 0 0;">
      Once imported, your localhost website will immediately reflect 100% of the live content, button colors, and images!
    </p>
  </div>
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
