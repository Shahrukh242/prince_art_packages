<?php
$pageSlug = 'blog';
require __DIR__ . '/includes/header.php';

$pdo = get_db();
$posts = $pdo->query("SELECT * FROM blog_posts WHERE is_published = 1 ORDER BY published_at DESC LIMIT 12")->fetchAll();
?>

<!-- ================================================================ -->
<!-- SECTION 01 — PAGE HERO                                           -->
<!-- ================================================================ -->
<section class="page-hero-section" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 4.5rem 0 3.5rem 0; position: relative; overflow: hidden; text-align: center;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  <div class="container" style="position: relative; z-index: 2; max-width: 860px; margin: 0 auto;">
    <span class="hero-badge-item" style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.9rem; border-radius: 30px; font-size: 0.82rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1.25rem;">
      <i class="ri-article-line"></i> <?= h(get_content('blog', 'hero_eyebrow', 'TECHNICAL INSIGHTS & REGULATORY UPDATES')) ?>
    </span>
    <h1 style="font-size: 2.8rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-bottom: 1rem;">
      <?= h(get_content('blog', 'hero_title', 'Pharmaceutical Packaging Insights & Technical Knowledge')) ?>
    </h1>
    <p style="font-size: 1.12rem; line-height: 1.7; color: rgba(255,255,255,0.88); margin: 0 auto; max-width: 720px;">
      <?= h(get_content('blog', 'hero_intro', 'Expert analysis, compliance guides, material selection insights, and engineering updates from Prince Art Packages technical desk.')) ?>
    </p>
  </div>
</section>

<!-- ================================================================ -->
<!-- SECTION 02 — ARTICLES GRID (H2 & 6 ARTICLES)                     -->
<!-- ================================================================ -->
<section class="section" style="padding: 4.5rem 0; background: var(--bg-body);">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 880px; margin: 0 auto 3.5rem auto;">
      <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
        <i class="ri-book-open-line"></i> <?= h(get_content('blog', 'sec_subtitle', 'KNOWLEDGE & INDUSTRY ARTICLES')) ?>
      </span>
      <h2 style="font-size: 2.5rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1.25rem;">
        <?= h(get_content('blog', 'sec_h2_title', 'Latest Technical Articles & Industry Guidelines')) ?>
      </h2>
      <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0 auto;">
        <?= h(get_content('blog', 'sec_h2_intro', 'Stay informed on regulatory changes, pharmaceutical secondary packaging best practices, anti-counterfeit advancements, and sustainable packaging materials. Authored by our packaging engineers and quality assurance specialists, our publications provide practical technical insights for pharmaceutical production managers, regulatory affairs leads, and procurement teams.')) ?>
      </p>
    </div>

    <!-- 6-Article Responsive Grid -->
    <div class="grid-3-products" style="gap: 2rem;">
      <?php foreach ($posts as $post): ?>
        <div class="product-card" style="display: flex; flex-direction: column; height: 100%;">
          <a href="blog-post.php?slug=<?= urlencode($post['slug']) ?>" style="text-decoration: none; color: inherit; display: block; overflow: hidden; border-radius: 8px 8px 0 0;">
            <img src="<?= h($post['image_path']) ?>" class="card-img-top" alt="<?= h($post['title']) ?>" style="height: 220px; width: 100%; object-fit: cover; transition: transform 0.3s ease;">
          </a>
          <div class="card-body" style="padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1; justify-content: space-between;">
            <div>
              <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.65rem; flex-wrap: wrap; gap: 0.5rem;">
                <span class="cert-pill" style="background: rgba(0,168,150,0.1); color: var(--teal-primary); font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; padding: 0.2rem 0.6rem; border-radius: 20px;">
                  PHARMA PACKAGING
                </span>
                <span style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.3rem;">
                  <i class="ri-calendar-line"></i> <?= h(date('M j, Y', strtotime($post['published_at']))) ?>
                </span>
              </div>
              <h3 class="card-title" style="font-size: 1.2rem; line-height: 1.4; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.75rem;">
                <a href="blog-post.php?slug=<?= urlencode($post['slug']) ?>" style="text-decoration: none; color: inherit;">
                  <?= h($post['title']) ?>
                </a>
              </h3>
              <p style="font-size: 0.9rem; line-height: 1.6; color: var(--text-body); margin-bottom: 1.25rem;">
                <?= h($post['excerpt']) ?>
              </p>
            </div>
            <div class="btn-quote-wrapper" style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-color);">
              <a href="blog-post.php?slug=<?= urlencode($post['slug']) ?>" class="btn btn-outline-navy btn-sm" style="width: 100%; text-align: center; justify-content: center;">
                Read Full Article &rarr;
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (empty($posts)): ?>
        <p style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-muted);">No articles published yet — check back soon.</p>
      <?php endif; ?>
    </div>

    <div class="innovation-banner" style="margin-top: 4.5rem; text-align: center;">
      <h2 class="innovation-banner-heading">Need Custom Packaging Guidance?</h2>
      <p class="innovation-banner-desc">Speak directly with our packaging engineers and technical sales team to evaluate your secondary packaging requirements.</p>
      <?= render_cta_buttons('blog', 'blog_bottom', '<a href="contact.php" class="btn btn-gold innovation-banner-btn"><i class="ri-mail-send-line"></i> Discuss Your Packaging Requirements</a>') ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
