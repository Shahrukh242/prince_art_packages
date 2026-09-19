<?php
$pageSlug = 'sustainability';
require __DIR__ . '/includes/header.php';
?>
<div class="container section">
      <div class="section-header">
        <span class="section-subtitle"><?= h(get_content('sustainability', 'hero_subtitle', 'Environmental Responsibility')) ?></span>
        <h2><?= h(get_content('sustainability', 'hero_title', 'Sustainable Manufacturing & FSC Certification')) ?></h2>
        <p><?= h(get_content('sustainability', 'hero_intro', 'Reducing ecological footprint while delivering uncompromised pharmaceutical secondary packaging strength.')) ?></p>
      </div>

      <div class="grid-2" style="align-items:center;">
        <div>
          <h3><?= h(get_content('sustainability', 'fsc_title', 'FSC Chain of Custody Standard FSC-STD-40-004')) ?></h3>
          <p><?= h(get_content('sustainability', 'fsc_text', 'We hold FSC Chain of Custody Certification (License Code FSC-C222205, Cert RR-COC-003348), verifying that all paperboard materials are sourced from responsibly managed forests.')) ?></p>
          <ul style="padding-left:1.2rem; color:var(--text-body);">
            <li><strong><?= h(get_content('sustainability', 'point_1_title', 'ColdSeal Footprint Reduction')) ?>:</strong> <?= h(get_content('sustainability', 'point_1_desc', 'Reduces plastic and aluminum foil usage by up to 50%.')) ?></li>
            <li><strong><?= h(get_content('sustainability', 'point_2_title', 'Low-VOC Inks')) ?>:</strong> <?= h(get_content('sustainability', 'point_2_desc', 'Vegetable oil-based printing inks safe for pharmaceutical plants.')) ?></li>
            <li><strong><?= h(get_content('sustainability', 'point_3_title', 'Paper Recycling')) ?>:</strong> <?= h(get_content('sustainability', 'point_3_desc', '98% paperboard off-cuts recycled back to certified paper mills.')) ?></li>
          </ul>
        </div>
        <div>
          <?= render_image(get_content('sustainability', 'image_main', 'assets/images/prod_cold_seal.jpg'), 'Eco-Friendly ColdSeal Packaging', '', ['style' => 'width:100%; border-radius:var(--radius-md);']) ?>
        </div>
      </div>
    </div>

<?php require __DIR__ . '/includes/footer.php'; ?>
