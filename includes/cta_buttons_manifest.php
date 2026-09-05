<?php
// includes/cta_buttons_manifest.php — Central registry of default CTA buttons across all pages and placement zones
require_once __DIR__ . '/db.php';

function get_all_default_cta_buttons(): array {
    return [
        'global' => [
            [
                'placement'   => 'header',
                'label'       => 'Request a Quote',
                'url'         => 'contact.php',
                'action_type' => 'popup',
                'style'       => 'btn-gold btn-sm',
                'icon'        => '',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'footer',
                'label'       => 'Request a Quote',
                'url'         => 'contact.php',
                'action_type' => 'popup',
                'style'       => 'btn-gold btn-sm',
                'icon'        => 'ri-file-list-3-line',
                'sort_order'  => 20,
                'is_active'   => 1,
            ],
        ],
        'home' => [
            [
                'placement'   => 'hero',
                'label'       => 'Explore Products & Capabilities',
                'url'         => 'products.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-arrow-right-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'hero',
                'label'       => 'Request a Formal Quote',
                'url'         => 'contact.php',
                'style'       => 'btn-outline-navy',
                'icon'        => 'ri-file-list-3-line',
                'sort_order'  => 20,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'values_cta',
                'label'       => 'About Prince Art',
                'url'         => 'about.php',
                'style'       => 'btn-navy',
                'icon'        => 'ri-arrow-right-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'feat_cartons',
                'label'       => 'Specifications →',
                'url'         => 'products.php#product-folding-cartons',
                'style'       => 'btn-gold btn-sm',
                'icon'        => '',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'feat_leaflets',
                'label'       => 'Specifications →',
                'url'         => 'products.php#product-leaf-inserts',
                'style'       => 'btn-gold btn-sm',
                'icon'        => '',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'feat_labels',
                'label'       => 'Specifications →',
                'url'         => 'products.php#product-printed-labels',
                'style'       => 'btn-gold btn-sm',
                'icon'        => '',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'feat_tamper',
                'label'       => 'Specifications →',
                'url'         => 'products.php#product-temper-evident',
                'style'       => 'btn-gold btn-sm',
                'icon'        => '',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'feat_coldseal',
                'label'       => 'Specifications →',
                'url'         => 'products.php#product-cold-seal-wallet',
                'style'       => 'btn-gold btn-sm',
                'icon'        => '',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'feat_anticounterfeit',
                'label'       => 'Specifications →',
                'url'         => 'products.php#product-3d-engravix',
                'style'       => 'btn-gold btn-sm',
                'icon'        => '',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'why_choose_cta',
                'label'       => 'Explore Our Capabilities →',
                'url'         => 'capabilities.php',
                'style'       => 'btn-navy',
                'icon'        => 'ri-settings-4-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'industry_solutions_cta',
                'label'       => 'Explore Industry Solutions →',
                'url'         => 'industries.php',
                'style'       => 'btn-navy',
                'icon'        => 'ri-building-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'quality_compliance_cta',
                'label'       => 'Explore Quality & Compliance →',
                'url'         => 'quality.php',
                'style'       => 'btn-navy',
                'icon'        => 'ri-shield-check-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'social_proof_cta',
                'label'       => 'Explore Case Studies & Results →',
                'url'         => 'case-studies.php',
                'style'       => 'btn-navy',
                'icon'        => 'ri-article-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'faq_cta',
                'label'       => 'Have More Questions? Contact Our Technical Team →',
                'url'         => 'contact.php',
                'style'       => 'btn-navy',
                'icon'        => 'ri-question-answer-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'banner',
                'label'       => 'Learn About Innovation & Technology',
                'url'         => 'innovation.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-lightbulb-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
        ],
        'about' => [
            [
                'placement'   => 'intro_cta',
                'label'       => 'Explore Packaging Solutions',
                'url'         => 'products.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-arrow-right-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'intro_cta',
                'label'       => 'Request a Quote',
                'url'         => 'contact.php',
                'style'       => 'btn-outline-navy',
                'icon'        => 'ri-file-list-3-line',
                'sort_order'  => 20,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'about_bottom',
                'label'       => 'Schedule a Facility Audit',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-calendar-check-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'about_bottom',
                'label'       => 'Contact Technical Sales',
                'url'         => 'contact.php',
                'style'       => 'btn-outline-navy',
                'icon'        => 'ri-phone-line',
                'sort_order'  => 20,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'journey_cta',
                'label'       => 'View Manufacturing Capabilities',
                'url'         => 'capabilities.php',
                'style'       => 'btn-navy',
                'icon'        => 'ri-settings-4-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'journey_cta',
                'label'       => 'Speak with Our Specialists',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-phone-line',
                'sort_order'  => 20,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'quality_compliance_cta',
                'label'       => 'Schedule a Facility Audit',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-calendar-check-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'quality_compliance_cta',
                'label'       => 'View Quality Accreditations',
                'url'         => 'quality.php',
                'style'       => 'btn-outline-navy',
                'icon'        => 'ri-external-link-line',
                'sort_order'  => 20,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'diff_cta',
                'label'       => 'Discuss Your Requirements',
                'url'         => 'contact.php',
                'style'       => 'btn-gold btn-lg',
                'icon'        => 'ri-chat-smile-3-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'faq_cta',
                'label'       => 'Have Additional Questions? Contact Our Technical Team',
                'url'         => 'contact.php',
                'style'       => 'btn-navy btn-lg',
                'icon'        => 'ri-question-answer-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
        ],
        'products' => [
            [
                'placement'   => 'hero',
                'label'       => 'Request Quotation & Samples',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-file-list-3-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'hero',
                'label'       => 'Explore 3D-ENGRAVIX™ Technology',
                'url'         => 'innovation.php',
                'style'       => 'btn-outline-navy',
                'icon'        => 'ri-lightbulb-line',
                'sort_order'  => 20,
                'is_active'   => 1,
            ],
        ],
        'capabilities' => [
            [
                'placement'   => 'hero',
                'label'       => 'Request a Quote',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-arrow-right-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'hero',
                'label'       => 'Download Capabilities Overview',
                'url'         => 'assets/downloads/Capabilities-Overview-Prince-Art-Packages.pdf',
                'style'       => 'btn-outline-navy',
                'icon'        => 'ri-download-2-line',
                'sort_order'  => 20,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'bottom_cta',
                'label'       => 'Request a Facility Audit',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-calendar-check-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'bottom_cta',
                'label'       => 'Request a Quote',
                'url'         => 'contact.php',
                'style'       => 'btn-outline-navy',
                'icon'        => 'ri-file-list-3-line',
                'sort_order'  => 20,
                'is_active'   => 1,
            ],
        ],
        'innovation' => [
            [
                'placement'   => 'hero',
                'label'       => 'Request Optical Security Samples',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-shield-check-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'hero',
                'label'       => 'Request a Quote',
                'url'         => 'contact.php',
                'style'       => 'btn-outline-navy',
                'icon'        => 'ri-file-list-3-line',
                'sort_order'  => 20,
                'is_active'   => 1,
            ],
        ],
        'quality' => [
            [
                'placement'   => 'quality_banner',
                'label'       => 'Request a Formal Quote',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-file-list-3-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
        ],
        'industries' => [
            [
                'placement'   => 'industries_banner',
                'label'       => 'Request a Formal Quote',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-file-list-3-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
        ],
        'case-studies' => [
            [
                'placement'   => 'case_studies_bottom',
                'label'       => 'Request an Audit & Verification Samples',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-shield-check-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
        ],
        'blog' => [
            [
                'placement'   => 'blog_bottom',
                'label'       => 'Discuss Your Packaging Requirements',
                'url'         => 'contact.php',
                'style'       => 'btn-gold',
                'icon'        => 'ri-mail-send-line',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
        ],
        'contact' => [
            [
                'placement'   => 'hero_cta',
                'label'       => 'Send an Enquiry',
                'url'         => '#enquiry-form',
                'style'       => 'btn-gold btn-lg',
                'icon'        => 'ri-mail-send-line',
                'sort_order'  => 5,
                'is_active'   => 1,
            ],
            [
                'placement'   => 'form_submit',
                'label'       => 'Submit Quotation Request',
                'url'         => '#',
                'style'       => 'btn-gold',
                'icon'        => 'ri-send-plane-fill',
                'sort_order'  => 10,
                'is_active'   => 1,
            ],
        ],
    ];
}

function ensure_page_default_cta_buttons(PDO $pdo, string $pageSlug): void {
    $all = get_all_default_cta_buttons();
    if (!isset($all[$pageSlug])) return;

    $stmt = $pdo->prepare("SELECT id FROM pages WHERE slug = ? LIMIT 1");
    $stmt->execute([$pageSlug]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$page) return;
    $pageId = (int)$page['id'];

    // If buttons already exist for this page, never auto-insert or overwrite user edits
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM cta_buttons WHERE page_id = ?");
    $countStmt->execute([$pageId]);
    if ((int)$countStmt->fetchColumn() > 0) {
        return;
    }

    $insStmt = $pdo->prepare("INSERT INTO cta_buttons (page_id, placement, label, url, action_type, style, icon, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    foreach ($all[$pageSlug] as $btn) {
        $insStmt->execute([
            $pageId,
            $btn['placement'],
            $btn['label'],
            $btn['url'],
            $btn['action_type'] ?? 'link',
            $btn['style'],
            $btn['icon'] ?: null,
            $btn['sort_order'],
            $btn['is_active']
        ]);
    }
}

function restore_page_default_cta_buttons(PDO $pdo, string $pageSlug): int {
    $all = get_all_default_cta_buttons();
    if (!isset($all[$pageSlug])) return 0;

    $stmt = $pdo->prepare("SELECT id FROM pages WHERE slug = ? LIMIT 1");
    $stmt->execute([$pageSlug]);
    $page = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$page) return 0;
    $pageId = (int)$page['id'];

    // Delete existing buttons for this page
    $pdo->prepare("DELETE FROM cta_buttons WHERE page_id = ?")->execute([$pageId]);

    $insStmt = $pdo->prepare("INSERT INTO cta_buttons (page_id, placement, label, url, action_type, style, icon, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $count = 0;
    foreach ($all[$pageSlug] as $btn) {
        $insStmt->execute([
            $pageId,
            $btn['placement'],
            $btn['label'],
            $btn['url'],
            $btn['action_type'] ?? 'link',
            $btn['style'],
            $btn['icon'] ?: null,
            $btn['sort_order'],
            $btn['is_active']
        ]);
        $count++;
    }
    return $count;
}
