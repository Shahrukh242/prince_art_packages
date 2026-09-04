<?php
$pageSlug = 'blog';

require_once __DIR__ . '/../includes/functions.php';
$pdo = get_db();
$slug = trim($_GET['slug'] ?? '');

$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE slug = ? AND is_published = 1 LIMIT 1");
$stmt->execute([$slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// If not found, fetch the latest post
if (!$post) {
    $fallbackStmt = $pdo->query("SELECT * FROM blog_posts WHERE is_published = 1 ORDER BY published_at DESC LIMIT 1");
    $post = $fallbackStmt->fetch(PDO::FETCH_ASSOC);
}

// Fetch 3 related/other blog posts (excluding current post)
$relatedPosts = [];
if ($post) {
    $relStmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id != ? AND is_published = 1 ORDER BY published_at DESC LIMIT 3");
    $relStmt->execute([$post['id']]);
    $relatedPosts = $relStmt->fetchAll(PDO::FETCH_ASSOC);
}

// Meta tags setup
$metaTitle = ($post['meta_title'] ?? $post['title']) . " | Prince Art Packages";
$metaDesc = $post['meta_description'] ?? $post['excerpt'];

require __DIR__ . '/includes/header.php';
?>

<?php if ($post): ?>
  <!-- ================================================================ -->
  <!-- BLOG ARTICLE HERO                                                -->
  <!-- ================================================================ -->
  <section class="blog-hero-section" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 4rem 0 3rem 0; position: relative; overflow: hidden;">
    <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
    <div class="container" style="position: relative; z-index: 2; max-width: 900px; margin: 0 auto;">
      
      <!-- Breadcrumbs -->
      <nav class="breadcrumb-nav" aria-label="Breadcrumb" style="margin-bottom: 1.25rem;">
        <a href="index.php" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.88rem;"><i class="ri-home-line"></i> Home</a>
        <span style="color: rgba(255,255,255,0.4); margin: 0 0.5rem;">/</span>
        <a href="blog.php" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.88rem;">Articles &amp; Insights</a>
        <span style="color: rgba(255,255,255,0.4); margin: 0 0.5rem;">/</span>
        <span style="color: var(--teal-brand); font-size: 0.88rem; font-weight: 600;">Article</span>
      </nav>

      <span class="product-category-pill" style="display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.85rem; border-radius: 30px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1rem;">
        <i class="ri-book-read-line"></i> PHARMACEUTICAL PACKAGING INSIGHTS
      </span>

      <h1 style="font-size: 2.6rem; font-weight: 800; color: #ffffff; line-height: 1.25; margin-bottom: 1rem;">
        <?= h($post['title']) ?>
      </h1>

      <div style="display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap; color: rgba(255,255,255,0.85); font-size: 0.92rem; margin-top: 1rem;">
        <span style="display: flex; align-items: center; gap: 0.4rem;">
          <i class="ri-calendar-event-line text-teal"></i> Published: <?= h(date('F j, Y', strtotime($post['published_at']))) ?>
        </span>
        <span style="display: flex; align-items: center; gap: 0.4rem;">
          <i class="ri-user-line text-teal"></i> By Prince Art Packages Technical Desk
        </span>
        <span style="display: flex; align-items: center; gap: 0.4rem;">
          <i class="ri-time-line text-teal"></i> 5 Min Read
        </span>
      </div>

    </div>
  </section>

  <!-- ================================================================ -->
  <!-- MAIN ARTICLE BODY                                                -->
  <!-- ================================================================ -->
  <section class="section" style="padding: 4rem 0; background: #ffffff;">
    <div class="container" style="max-width: 860px; margin: 0 auto;">
      
      <!-- Featured Image -->
      <div style="margin-bottom: 2.5rem; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(10,37,64,0.08); border: 1px solid var(--border-color);">
        <img src="<?= h($post['image_path']) ?>" alt="<?= h($post['title']) ?>" style="width: 100%; height: auto; max-height: 440px; object-fit: cover; display: block;">
        <div style="background: #f8fafc; padding: 0.75rem 1.25rem; font-size: 0.85rem; color: var(--text-muted); border-top: 1px solid var(--border-color); display: flex; align-items: center; gap: 0.5rem;">
          <i class="ri-camera-lens-line text-teal"></i> Pharmaceutical Secondary Packaging Infrastructure — Prince Art Packages
        </div>
      </div>

      <!-- Lead Excerpt Box -->
      <div style="background: rgba(0,168,150,0.06); border-left: 4px solid var(--teal-primary); padding: 1.5rem 1.75rem; border-radius: 0 8px 8px 0; margin-bottom: 2.5rem;">
        <p style="font-size: 1.15rem; line-height: 1.7; color: var(--navy-dark); font-weight: 600; margin: 0;">
          <?= h($post['excerpt']) ?>
        </p>
      </div>

      <!-- Article HTML Content -->
      <div class="article-content" style="font-size: 1.05rem; line-height: 1.8; color: var(--text-body);">
        <?= $post['content'] /* trusted HTML authored by admin */ ?>
      </div>

      <!-- Article Author / QA Desk Footer Box -->
      <div style="margin-top: 3.5rem; padding: 2rem; background: var(--bg-alt); border-radius: 10px; border: 1px solid var(--border-color); display: flex; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
        <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--navy-dark); color: var(--teal-brand); display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0;">
          <i class="ri-shield-star-line"></i>
        </div>
        <div style="max-width: 650px;">
          <h4 style="margin: 0 0 0.25rem 0; font-size: 1.15rem; color: var(--navy-dark); font-weight: 800;">Prince Art Packages Technical Directorate</h4>
          <p style="margin: 0; font-size: 0.9rem; color: var(--text-muted); line-height: 1.55;">
            Our packaging engineering desk authors technical guidelines on ISO 9001:2015, cGMP line clearance, anti-counterfeit security, and automated cartoning line compatibility for pharmaceutical brand owners across regulated markets.
          </p>
        </div>
      </div>

      <!-- Return Button -->
      <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <a href="blog.php" class="btn btn-outline-navy btn-sm">
          &larr; Back to All Articles
        </a>
        <a href="contact.php" class="btn btn-gold btn-sm">
          <i class="ri-mail-send-line"></i> Consult Our Engineers &rarr;
        </a>
      </div>

    </div>
  </section>

  <!-- ================================================================ -->
  <!-- EXPLORE OTHER TECHNICAL ARTICLES (RELATED BLOGS DOWNSIDE)         -->
  <!-- ================================================================ -->
  <section class="section" style="background: var(--bg-alt); padding: 5rem 0; border-top: 1px solid var(--border-color);">
    <div class="container">
      <div class="section-header" style="text-align: center; max-width: 760px; margin: 0 auto 3.5rem auto;">
        <span class="section-subtitle" style="display: inline-block; font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; color: var(--teal-primary); text-transform: uppercase; margin-bottom: 0.75rem; background: rgba(0,168,150,0.1); padding: 0.35rem 0.85rem; border-radius: 30px;">
          <i class="ri-article-line"></i> CONTINUED READING
        </span>
        <h2 style="font-size: 2.2rem; color: var(--navy-dark); font-weight: 800;">Explore Other Technical Articles</h2>
        <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6;">
          Discover our latest technical insights, regulatory updates, and packaging engineering guides for regulated pharmaceutical manufacturing.
        </p>
      </div>

      <div class="grid-3-products" style="gap: 2rem;">
        <?php foreach ($relatedPosts as $relPost): ?>
          <div class="product-card" style="display: flex; flex-direction: column; height: 100%;">
            <a href="blog-post.php?slug=<?= urlencode($relPost['slug']) ?>" style="text-decoration: none; color: inherit; display: block; overflow: hidden; border-radius: 8px 8px 0 0;">
              <img src="<?= h($relPost['image_path']) ?>" class="card-img-top" alt="<?= h($relPost['title']) ?>" style="height: 200px; width: 100%; object-fit: cover; transition: transform 0.3s ease;">
            </a>
            <div class="card-body" style="padding: 1.5rem; display: flex; flex-direction: column; flex-grow: 1; justify-content: space-between;">
              <div>
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 0.5rem;">
                  <span class="cert-pill" style="background: rgba(0,168,150,0.1); color: var(--teal-primary); font-size: 0.72rem; font-weight: 700; padding: 0.18rem 0.55rem; border-radius: 20px;">
                    PHARMA PACKAGING
                  </span>
                  <span style="font-size: 0.78rem; color: var(--text-muted);">
                    <i class="ri-calendar-line"></i> <?= h(date('M j, Y', strtotime($relPost['published_at']))) ?>
                  </span>
                </div>
                <h3 class="card-title" style="font-size: 1.15rem; line-height: 1.4; color: var(--navy-dark); font-weight: 800; margin-bottom: 0.75rem;">
                  <a href="blog-post.php?slug=<?= urlencode($relPost['slug']) ?>" style="text-decoration: none; color: inherit;">
                    <?= h($relPost['title']) ?>
                  </a>
                </h3>
                <p style="font-size: 0.88rem; line-height: 1.55; color: var(--text-body); margin-bottom: 1rem;">
                  <?= h($relPost['excerpt']) ?>
                </p>
              </div>
              <div class="btn-quote-wrapper" style="margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                <a href="blog-post.php?slug=<?= urlencode($relPost['slug']) ?>" class="btn btn-outline-teal btn-sm" style="width: 100%; text-align: center; justify-content: center;">
                  <i class="ri-file-list-3-line"></i> Read Article &rarr;
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div style="text-align: center; margin-top: 3rem;">
        <a href="blog.php" class="btn btn-outline-navy btn-lg">
          <i class="ri-arrow-left-line"></i> View All Technical Articles
        </a>
      </div>

    </div>
  </section>

<?php else: ?>
  <div class="container section" style="max-width: 800px; text-align: center; padding: 6rem 0;">
    <h1>Article Not Found</h1>
    <p style="color: var(--text-muted); margin-bottom: 2rem;">The technical article you are looking for does not exist or has been relocated.</p>
    <a href="blog.php" class="btn btn-navy">&larr; Return to All Articles</a>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
