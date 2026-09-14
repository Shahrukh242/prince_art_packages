<?php
$pageSlug = 'product-detail';
require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db();
$slug = trim($_GET['slug'] ?? '');
$id   = (int)($_GET['id'] ?? 0);

// Comprehensive 8-Product Catalog Specifications Database
$productSpecsCatalog = [
    'printed-cartons' => [
        'name' => 'Printed Cartons',
        'category' => 'SECONDARY PACKAGING',
        'tagline' => 'Precision-Printed Paperboard Printed Cartons Engineered for High-Speed Packaging Lines',
        'image' => 'assets/images/prod_cartons.jpg',
        'what_it_is' => 'High-precision pharmaceutical paperboard printed cartons manufactured from certified food and pharma-grade virgin boxboards (FBB / GC1 / GC2 and SBS) ranging from 230 to 450 GSM.',
        'what_used_for' => 'Enclosing, protecting, and presenting primary packaging units while carrying critical batch coding, 2D serialization data, regulatory warnings, and patient dosing instructions.',
        'who_uses_it' => 'Commercial pharmaceutical formulation plants, multinational healthcare brands, generic drug manufacturers, and contract packaging organizations (CPOs/CMOs).',
        'main_applications' => 'Secondary packaging for solid oral blister strips, liquid cough syrup and antibiotic bottles, sterile injectable vials, ophthalmic dropper cartons, and diagnostic kit boxes.',
        'customization' => 'Available in Reverse Tuck End (RTE), Straight Tuck End (STE), crash-lock auto-bottom, internal partitions, tamper-evident security flaps, in-line Marburg-standard Braille embossing, and aqueous/UV barrier varnishes.',
        'key_benefits' => 'Calibrated score bending resistance guarantees zero-jam performance on automated cartoning lines operating up to 400 cartons/minute, while closed-loop spectrophotometer controls ensure batch-to-batch Delta-E shade accuracy.',
        'overview' => 'Prince Art Packages manufactures high-precision pharmaceutical printed cartons engineered specifically for high-speed automated packaging lines. Produced exclusively from certified virgin boxboards under strict cGMP line clearance, our cartons deliver calibrated score stiffness, exceptional structural integrity, and flawless Delta-E color consistency. Fully customizable with in-line Marburg Braille, 2D DataMatrix serialization, and tamper-evident fiber-tear closures, they provide complete audit-ready packaging compliance for regulated global markets.',
        'specs' => [
            'Structural Formats' => 'Reverse Tuck End (RTE), Straight Tuck End (STE), Auto-Lock Crash Bottom, Snap-Lock Bottom, Dispenser Cartons, Internal Board Dividers',
            'Substrate Options' => 'Virgin FBB (Folding Box Board / GC1 & GC2), SBS (Solid Bleached Sulfate), Pharma-grade Duplex White/Grey Back, FSC-certified boards',
            'Caliper & Weight Range' => '230 GSM to 450 GSM (thickness: 350 to 650 microns)',
            'Printing Technology' => 'Heidelberg multi-color sheetfed offset presses with closed-loop inline spectrophotometry',
            'Inks & Barrier Coatings' => 'Low-migration vegetable oil-based inks, water-based aqueous dispersion (Gloss/Matte/Satin), UV protective varnishes',
            'Die-Cutting & Creasing' => 'CNC laser tooling with micro-crease matrix preventing fiber cracking during automated high-speed erection',
            'Braille Embossing' => 'In-line Marburg Medium dot embossing compliant with European Directive 2004/27/EC',
            'Security & Serialization' => 'High-contrast 2D DataMatrix serialization printing, GS1-128 linear barcodes, tamper-evident security flaps',
            'Line Machine Speeds' => 'Engineered for zero-jam feeding on high-speed cartoning machines (Bosch, Uhlmann, IMA, Marchesini) up to 400 cartons/min',
            'Quality Accreditations' => '100% cGMP line clearance, ISO 9001:2015 Certified (KQ.2025.5393), FSC Chain of Custody (RR-COC-003348)',
            'Target Applications' => 'Blister secondary cartons, oral solids, liquid syrups, ampoule/vial multi-packs, diagnostic kits'
        ]
    ],
    'leaf-inserts' => [
        'name' => 'Leaf-Inserts',
        'category' => 'PATIENT INFORMATION',
        'tagline' => 'High-Density Prescribing Information Leaflets & Miniature Outserts (PIL)',
        'image' => 'assets/images/prod_leaflets.jpg',
        'what_it_is' => 'Lightweight, ultra-opaque pharmaceutical instruction leaflets (PIL) and pre-folded miniature outserts printed on specialized 27 GSM to 60 GSM medical-grade bible paper.',
        'what_used_for' => 'Delivering legally mandated prescribing information, contraindications, dosage instructions, and patient safety data within tight secondary carton dimensions.',
        'who_uses_it' => 'Pharmaceutical regulatory affairs departments, primary formulation manufacturers, hospital supply packagers, and contract manufacturing organizations (CMOs).',
        'main_applications' => 'Prescription oral tablets, antibiotic suspensions, injectable therapies, pediatric liquids, biological medications, and vaccine vials.',
        'customization' => 'Available in cross-folds, concertina zigzag folds, parallel folds, miniature outserts sealed with clear fugitive adhesive wafer tabs, and multi-page cross-folded booklets.',
        'key_benefits' => 'Integrated 100% optical Pharma-Code barcode scanning prevents leaflet mix-ups during high-speed folding; thin-print opacity ensures crystal-clear micro-text legibility without ink show-through.',
        'overview' => 'Prince Art Packages engineers high-density prescribing information leaflets (PIL) and miniature outserts designed to meet stringent global health authority labeling mandates. Printed on ultra-thin 27 to 60 GSM medical-grade paper, our leaflets allow extensive clinical text to fold into ultra-compact footprints suitable for automated inserter carousels. Inline Pharma-Code verification guarantees 100% batch security and zero mix-up risk.',
        'specs' => [
            'Folding Formats' => 'Cross-fold, parallel fold, concertina (zigzag) fold, miniature outsert (pre-folded with wafer tab seals), multi-page booklets',
            'Substrate Materials' => 'Medical-grade lightweight opaque woodfree paper (Bible / Thin-print paper), FSC certified virgin pulp',
            'Grammage Range' => 'Ultra-thin 27 GSM, 32 GSM, 40 GSM, 45 GSM, up to 60 GSM high-opacity paper',
            'Printing Capabilities' => '1 to 4 color high-definition offset printing with micro-text legibility verification and zero show-through opacity',
            'Dimensional Range' => 'Open sheet sizes from 100 × 150 mm up to 600 × 1000 mm; folded miniature outsert dimensions down to 25 × 25 mm',
            'Wafer Seals & Gluing' => 'In-line mechanical gluing and clear fugitive adhesive wafer seals for stable automated pickup by inserter carousels',
            'Inspection & Verification' => '100% inline optical barcode and Pharma-code verification to eliminate any possibility of leaflet mix-ups during folding',
            'Regulatory Compliance' => 'US FDA 21 CFR Part 201 and EU Directive 2001/83/EC font size & legibility compliance, cGMP line clearance',
            'Recommended Applications' => 'Prescription oral solids, injectable solutions, pediatric suspensions, biological therapies, vaccines'
        ]
    ],
    'printed-labels' => [
        'name' => 'Printed Labels',
        'category' => 'CONTAINER LABELING',
        'tagline' => 'High-Precision Primary & Secondary Container Printed Labels',
        'image' => 'assets/images/prod_labels.jpg',
        'what_it_is' => 'High-precision printed bottle and container labels produced from pharmaceutical-grade semi-gloss papers, synthetic films (PP/PE), and destructible tamper-evident face-stocks.',
        'what_used_for' => 'Providing permanent primary container branding, regulatory batch identification, 2D DataMatrix serialization tracking, and tamper verification on medical containers.',
        'who_uses_it' => 'Liquid and injectable pharmaceutical manufacturers, sterile compounding plants, eye-care product packagers, and diagnostic reagent suppliers.',
        'main_applications' => 'Glass and plastic syrup bottles, injectable ampoules and vials, eye/ear droppers, IV infusion containers, and diagnostic cartridge tubes.',
        'customization' => 'Choice of permanent, deep-freeze (-80°C), or removable medical-grade adhesives; opaque block-out backings; roll core diameters (25mm/40mm/76mm); and serialization top-coatings.',
        'key_benefits' => 'High initial tack and tight curve-conformable memory eliminate label flagging on small-diameter vials; resistant to moisture, alcohol rubs, and cold-chain storage condensation.',
        'overview' => 'Our printed bottle and container labels are engineered for flawless, bubble-free application on high-speed automated bottle and vial labeling lines. Manufactured with medical-grade adhesives and durable synthetic or paper face-stocks, they deliver superior adhesion on challenging small-diameter glass curves while resisting moisture, alcohol sterilizations, and deep-freeze temperatures.',
        'specs' => [
            'Substrate Face-stocks' => 'Pharma-grade semi-gloss cast coated paper, Polypropylene (BOPP), Polyethylene (PE), clear transparent film, metallic foils',
            'Adhesive Formulations' => 'Permanent acrylic emulsion, deep-freeze cold-chain adhesive (-80°C resistant), removable medical-grade adhesive',
            'Release Liner' => 'High-tensile glassine paper liner (white/honey), PET clear liner for high-speed sensor detection',
            'Printing Technology' => 'High-definition flexographic & UV offset roll-to-roll printing up to 8 colors',
            'Over-Varnish & Protection' => 'UV gloss varnish, thermal-transfer printable matte varnish for inline batch/expiry/2D barcode overprinting',
            'Roll Specifications' => 'Custom roll core diameters (25 mm, 40 mm, 76 mm); customized roll outer diameter and winding direction',
            'Tamper-Evident Options' => 'Destructible security vinyl face-stocks, tamper-evident cross-perforations across caps',
            'Chemical Resistance' => 'High resistance to alcohol wipes, isopropyl alcohol (IPA), moisture, autoclaving, and refrigerated condensation',
            'Serialization Ready' => 'Formulated surface top-coatings optimized for continuous inkjet (CIJ), thermal transfer (TTO), and laser coding',
            'Quality Standards' => 'cGMP batch traceability, ISO 9001:2015, zero adhesive bleed guarantees on high-speed labeling machinery'
        ]
    ],
    'honeycomb-separators' => [
        'name' => 'Honeycomb Separators',
        'category' => 'PROTECTIVE PARTITIONS',
        'tagline' => 'Heavy-Duty Shock-Absorbing Protective Grid Partitions & Separators',
        'image' => 'assets/images/prod_honeycomb.jpg',
        'what_it_is' => 'Structural cardboard honeycomb dividers and interlocking paperboard cell grids fabricated from heavy-caliper chipboard and multi-ply kraft board.',
        'what_used_for' => 'Isolating individual fragile glass containers within bulk shippers to eliminate glass-to-glass contact, friction scuffing, and transit vibration breakage.',
        'who_uses_it' => 'Injectable liquid manufacturing facilities, parenteral pharmaceutical exporters, vaccine production plants, and diagnostic reagent distributors.',
        'main_applications' => 'Bulk packaging partitions for sterile glass ampoules (1ml to 20ml), lyophilized vaccine vials, pre-filled glass syringes, and liquid dropper bottles.',
        'customization' => 'Custom cell matrix sizing (10, 20, 50, 100 cells), variable wall heights, collapsible pre-assembled grid designs, and moisture-resistant board coatings.',
        'key_benefits' => 'Prevents transit breakage and hairline glass micro-fractures; low-dust, cGMP-compatible paperboard construction eliminates particulate contamination in packing halls.',
        'overview' => 'Prince Art Packages manufactures engineered honeycomb partitions and cell dividers that protect high-value fragile glass containers during transit and storage. By creating dedicated individual cushioned cells, our partitions eliminate glass-to-glass contact and impact shock, ensuring zero breakage rates for sterile injectables, ampoules, and vials.',
        'specs' => [
            'Material Construction' => 'High-strength virgin Kraftliner, solid bleached board, multi-ply recycled chipboard, rigid honeycomb cell core',
            'Board Caliper' => '400 GSM to 1200 GSM solid board; 10 mm to 25 mm honeycomb sandwich thickness',
            'Cell Configurations' => '10-cell, 20-cell, 50-cell, 100-cell custom matrix arrays tailored to specific container outer diameters',
            'Container Sizing' => 'Engineered for glass ampoules (1ml, 2ml, 5ml, 10ml, 20ml), vaccine vials (2R to 50R), pre-filled syringes, and bottles',
            'Assembly Formats' => 'Pre-assembled collapsible interlocking grids for rapid setup, flat-packed die-cut slot sheets',
            'Surface Finish' => 'Dust-free, lint-free smooth paperboard surface preventing particulate shedding in clean packaging environments',
            'Cushioning & Crush Strength' => 'High edge-crush test (ECT) and flat crush resistance absorbing multi-axis transit vibration',
            'Environmental Profile' => '100% biodegradable, recyclable paper-based packaging replacing expanded polystyrene (EPS) foam',
            'Compliance' => 'cGMP packaging hall particulate cleanliness standards, FSC certified sustainable raw materials'
        ]
    ],
    'pill-folders' => [
        'name' => 'Pill-Folders',
        'category' => 'DOSE ADHERENCE',
        'tagline' => 'Multi-Panel Paperboard Blister Folders & Dose Adherence Packaging Wallets',
        'image' => 'assets/images/prod_pill_folders.jpg',
        'what_it_is' => 'Multi-panel paperboard packaging wallets with integrated die-cut blister retention cavities, tear-off dosage strips, and child-resistant locking closures.',
        'what_used_for' => 'Encapsulating solid oral dose blister strips within an organized calendar structure to guide patient medication compliance and protect blister foils.',
        'who_uses_it' => 'Chronic therapy pharmaceutical manufacturers (cardiovascular, diabetes, CNS), clinical trial research organizations, oral contraceptive brands, and wellness companies.',
        'main_applications' => 'Calendarized unit-dose pill packs, weekly/monthly titration regimens, pediatric treatment courses, clinical trial blinded patient kits, and premium OTC vitamins.',
        'customization' => 'Available in 7-day, 14-day, 28-day, or bespoke calendar layouts; bi-fold, tri-fold, and book-fold geometries; push-button child-resistant safety latches; and foil-embossed branding.',
        'key_benefits' => 'Dramatically improves patient therapy compliance through intuitive visual scheduling while offering expansive printable surface area for multilingual dosing guidelines.',
        'overview' => 'Our paperboard pill-folders and dose adherence wallets provide a patient-centric secondary packaging solution for complex therapeutic regimens. By combining secure blister encapsulation with clear visual calendar tracking, they enhance medication adherence while offering abundant surface area for regulatory text, patient instructions, and brand presentation.',
        'specs' => [
            'Structural Geometries' => 'Bi-fold, Tri-fold, 4-panel gatefold, Book-format wallet with spine, Slide-out sleeve drawer',
            'Substrate Materials' => 'High-caliper Virgin FBB (300 to 450 GSM), Solid Bleached Sulfate (SBS) with high tear resistance',
            'Blister Retention' => 'Integrated die-cut aperture windows, internal adhesive bonding, thermoformed cavity pockets',
            'Dose Tracking Calendars' => 'Numbered daily grids (Day 1 to Day 28), Morning/Afternoon/Evening divided dosage matrices',
            'Child-Resistance Features' => 'Engineered safety release tabs and locking mechanisms compliant with ISO 8317 / US 16 CFR § 1700.20',
            'Senior-Friendly Access' => 'Easy-to-open push-through apertures requiring minimal hand strength for elderly patients',
            'Printing & Finishing' => 'Multi-color offset printing, high-contrast calendar numbering, spot gloss/matte UV coatings',
            'Closure Options' => 'Cohesive cold-seal adhesive, clear fugitive glue spots, magnetic closures, mechanical interlocking tabs',
            'Target Therapies' => 'Cardiovascular regimens, oral contraceptives, antibiotic titration packs, clinical trial test kits'
        ]
    ],
    'temper-evident-cartons' => [
        'name' => 'Temper Evident Cartons & Labels',
        'category' => 'SECURITY SEALS',
        'tagline' => 'EN 16679 Compliant Tamper-Evident Cartons & Destructible Fiber-Tear Security Seals',
        'image' => 'assets/images/prod_tamper_labels.jpg',
        'what_it_is' => 'Anti-tampering secondary packaging cartons with engineered structural score cuts, fiber-tearing adhesive closures, and destructible tamper-evident label seals.',
        'what_used_for' => 'Providing irreversible visual evidence of package opening to safeguard medicines against counterfeiting, illicit refilling, and unauthorized supply chain tampering.',
        'who_uses_it' => 'Prescription pharmaceutical manufacturers, oncology and biological therapy producers, hospital pharmacy suppliers, and export drug distributors.',
        'main_applications' => 'High-value prescription medicine cartons, over-the-counter retail drug boxes, sterile surgical supplies, and hospital-grade injectables.',
        'customization' => 'Choice of structural score-cut flap geometries, integrated fiber-tear adhesive strips, destructible holographic security seals, and void-pattern release tapes.',
        'key_benefits' => 'Ensures full compliance with the EU Falsified Medicines Directive (Directive 2011/62/EU) and US DSCSA anti-tampering mandates while guaranteeing patient safety.',
        'overview' => 'Prince Art Packages manufactures specialized tamper-evident printed cartons and destructible security labels conforming strictly to European Standard EN 16679. Engineered with precision score cuts and fiber-tearing cohesive zones, our cartons display immediate, irreversible visual evidence if opened, protecting brand integrity and patient trust.',
        'specs' => [
            'Compliance Standards' => 'EN 16679:2014 (Packaging — Tamper verification features for medicinal product packaging), EU 2011/62/EU FMD',
            'Carton Tamper Mechanisms' => 'Specially scored perforated tuck flaps, internal fiber-tear locking flaps, glue-sealed end flaps',
            'Label Tamper Mechanisms' => 'Ultra-destructible vinyl security seals, holographic void-release film seals, frangible paper labels',
            'Substrate Board' => 'High internal bond virgin FBB / SBS boxboard designed for guaranteed surface fiber tear upon opening',
            'Visual Evidence Type' => 'Irreversible carton tearing, non-resealable void message ("VOID / OPENED"), shattered seal fragments',
            'High-Speed Line Gluing' => 'Formulated for automated hot-melt and cold-glue application on high-speed folder-gluers up to 400 cpm',
            'Serialization Integration' => 'Pre-printed unique serial numbers, tamper seal barcode registration, 2D DataMatrix alignment',
            'Target Segments' => 'Prescription medicines, high-potency drugs, biological therapies, premium retail healthcare'
        ]
    ],
    '3d-engravix' => [
        'name' => '3D-ENGRAVIX™',
        'category' => 'OPTICAL ANTI-COUNTERFEIT',
        'tagline' => 'Proprietary Micro-Optic Anti-Counterfeit Security Integrated on Printed Cartons',
        'image' => 'assets/images/engravix.jpg',
        'what_it_is' => 'Proprietary micro-structured optical security feature embedded directly into pharmaceutical paperboard printed cartons during inline printing and converting.',
        'what_used_for' => 'Delivering immediate, overt visual authentication under ambient light to protect lifesaving medicines from counterfeiting without requiring scanning devices or apps.',
        'who_uses_it' => 'Multinational pharmaceutical manufacturers, high-value drug innovators, anti-counterfeit brand protection officers, and regulatory compliance agencies.',
        'main_applications' => 'High-value branded prescription cartons, antiviral medications, oncology therapies, life-critical antibiotics, and export formulation boxes.',
        'customization' => 'Custom 3D optical depth illusions, multi-axis kinetic motion blade effects, corporate logo flip transformations, angle-dependent color shift seals, and hidden covert micro-text.',
        'key_benefits' => '100% visual authentication under ambient light enables instant verification by pharmacists, doctors, and patients; impossible to duplicate with conventional color copiers or commercial printing.',
        'overview' => '3D-ENGRAVIX™ is our proprietary overt anti-counterfeit optical technology engineered directly into pharmaceutical printed cartons. Featuring dynamic 3D depth, animated kinetic motion, and color-shifting security seals, it enables doctors, pharmacists, customs inspectors, and patients to authenticate genuine medicine in seconds under normal room lighting without any reader device.',
        'specs' => [
            'Technology Classification' => 'Micro-structured optical security device directly integrated onto paperboard substrate',
            'Authentication Method' => '100% overt visual verification under ambient room light / daylight (zero electronic readers needed)',
            'Dynamic Optical Effects' => '3D micro-depth floating elements, kinetic motion blades, vertical/horizontal flip transformations',
            'Color-Shifting Seals (CCS)' => 'Angle-dependent optical color shift seals (e.g. violet-to-emerald transition)',
            'Covert Security Layers' => 'Micro-text resolution (< 50 microns), polarized light security verification, invisible UV ink marks',
            'Integration Method' => 'Inline micro-embossing and optical transfer directly on Heidelberg offset packaging lines',
            'Duplication Resistance' => 'Zero reproduction capability using digital scanners, color copiers, standard flexo, or offset plates',
            'Substrate Compatibility' => 'Pharma-grade virgin FBB (GC1/GC2), Solid Bleached Sulfate (SBS), metallized PET board',
            'Target Segments' => 'Critical prescription pharmaceuticals, oncology medicines, vaccines, export healthcare products'
        ]
    ],
    'coldseal-blister-wallet' => [
        'name' => 'Cold-seal Wallet',
        'category' => 'PRESSURE-SEALED ECO PACKAGING',
        'tagline' => 'Eco-Friendly Heat-Free Pressure-Sealed Paperboard Blister Packaging Wallet',
        'image' => 'assets/images/coldseal.jpg',
        'what_it_is' => 'Sustainable paperboard blister packaging system that bonds two coated board layers using cohesive pressure rollers without applying thermal heat.',
        'what_used_for' => 'Encapsulating temperature-sensitive pharmaceuticals, probiotic capsules, and sterile medical devices without exposing active ingredients to thermal heat degradation.',
        'who_uses_it' => 'Probiotic formulation producers, biological and peptide drug manufacturers, medical device packaging engineers, and eco-conscious pharmaceutical brands.',
        'main_applications' => 'Heat-labile capsules, probiotic blister cards, effervescent tablets, pre-filled syringes, diagnostic devices, and clinical trial sample wallets.',
        'customization' => 'Available in wallet format, box format, or single hang-card format; pre-formed plastic/paper-pulp blister pockets; child-resistant slide latches; and calendarized dosing layout.',
        'key_benefits' => '100% heat-free sealing protects drug potency and saves factory energy; reduces plastic usage by up to 50% compared to traditional rigid plastic clamshells.',
        'overview' => 'ColdSeal Blister Wallets represent the future of sustainable, heat-free pharmaceutical secondary packaging. By replacing thermal heat-sealing with instant cohesive pressure bonding, ColdSeal protects active pharmaceutical ingredients from thermal degradation while cutting plastic packaging volume by up to 50%. Certified FSC paperboard construction provides a recyclable, eco-friendly solution with tamper-evident security.',
        'specs' => [
            'Sealing Principle' => '100% Cold-Seal (cohesive pressure bonding without heat application)',
            'Thermal Protection' => 'Zero heat transferred to blister cavity — preserves heat-labile APIs, probiotics, and biologics',
            'Substrate Materials' => 'Certified FSC virgin paperboard with cohesive adhesive coating on reverse sides',
            'Plastic Reduction' => 'Up to 50% reduction in plastic packaging weight compared to full plastic clamshells',
            'Cavity Compatibility' => 'Formed PVC, PVDC, Aclar, Alu-Alu cold-form blisters, and molded paper-pulp cavities',
            'Child-Resistance & Compliance' => 'Engineered child-resistant locking tabs compliant with ISO 8317 standards',
            'Tamper Evidence' => 'Immediate, irreversible paperboard fiber tear upon opening attempt',
            'Energy Efficiency' => 'Pinch-roller sealing requires zero pre-heating energy, lowering plant carbon emissions',
            'Target Applications' => 'Probiotic supplements, temperature-sensitive oral solids, pre-filled syringes, diagnostic devices'
        ]
    ]
];

// Alias support: folding-cartons -> printed-cartons
if ($slug === 'folding-cartons') {
    $slug = 'printed-cartons';
}

// Determine requested product
$currentProduct = null;
$currentSlug = '';

if ($slug !== '' && isset($productSpecsCatalog[$slug])) {
    $currentProduct = $productSpecsCatalog[$slug];
    $currentSlug = $slug;
} elseif ($id > 0) {
    $dbProd = $pdo->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $dbProd->execute([$id]);
    $prodRow = $dbProd->fetch(PDO::FETCH_ASSOC);
    if ($prodRow) {
        $pSlug = ($prodRow['slug'] === 'folding-cartons') ? 'printed-cartons' : $prodRow['slug'];
        if (isset($productSpecsCatalog[$pSlug])) {
            $currentProduct = $productSpecsCatalog[$pSlug];
            $currentSlug = $pSlug;
        }
    }
}

// Fallback to database lookup if slug exists in DB
if (!$currentProduct && $slug !== '') {
    $dbProd = $pdo->prepare("SELECT * FROM products WHERE slug = ? LIMIT 1");
    $dbProd->execute([$slug]);
    $prodRow = $dbProd->fetch(PDO::FETCH_ASSOC);
    if ($prodRow) {
        $currentSlug = $prodRow['slug'];
        $currentProduct = [
            'name' => $prodRow['name'],
            'category' => $prodRow['category'] ?: 'PHARMACEUTICAL PACKAGING',
            'tagline' => $prodRow['short_description'] ?: 'High-Precision Pharmaceutical Packaging Solution',
            'image' => $prodRow['image_path'] ?: 'assets/images/prod_cartons.jpg',
            'what_it_is' => 'High-precision pharmaceutical packaging manufactured from certified virgin boxboards conforming to global cGMP and ISO 9001:2015 standards.',
            'what_used_for' => 'Enclosing, protecting, and identifying pharmaceutical dosage forms while carrying regulatory batch data.',
            'who_uses_it' => 'Commercial pharmaceutical formulation plants, multinational healthcare brands, and contract packaging organizations.',
            'main_applications' => 'Solid oral dosage blister strips, liquid bottles, injectable vials, ampoules, and medical kits.',
            'customization' => 'Custom dielines, dimensions, board calipers, barrier coatings, Braille embossing, and security features.',
            'key_benefits' => 'High-speed automated line compatibility, consistent Delta-E color reproduction, and full cGMP batch traceability.',
            'overview' => $prodRow['description'] ?: 'Purpose-engineered packaging solution manufactured under ISO 9001:2015 and cGMP guidelines for regulated pharmaceutical plants.',
            'specs' => [
                'Product Name' => $prodRow['name'],
                'Category' => $prodRow['category'],
                'Manufacturing Standard' => 'ISO 9001:2015 & cGMP Compliant',
                'Material Options' => 'Virgin FSC-Certified Boxboard & Specialty Substrates',
                'Customization' => 'Tailored dimensions, dielines, coatings, and security features available upon request'
            ]
        ];
    }
}

// Default fallback if nothing matched
if (!$currentProduct) {
    if ($slug !== '' || $id > 0) {
        http_response_code(404);
        require __DIR__ . '/404.php';
        exit;
    }
    $currentSlug = 'printed-cartons';
    $currentProduct = $productSpecsCatalog['printed-cartons'];
}

// SEO Meta setup
$metaTitle = $currentProduct['name'] . " Specifications | Prince Art Packages";
$metaDesc = "Technical specifications, applications, customizations, and manufacturing standards for " . $currentProduct['name'] . " by Prince Art Packages.";

require __DIR__ . '/includes/header.php';
?>

<!-- ================================================================ -->
<!-- PRODUCT SPECIFICATION HERO WITH ACTION CTAS                       -->
<!-- ================================================================ -->
<section class="product-hero-section" style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 3.5rem 0 3rem 0; position: relative; overflow: hidden;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: radial-gradient(var(--teal-primary) 1px, transparent 1px); background-size: 24px 24px;"></div>
  <div class="container" style="position: relative; z-index: 2;">
    
    <!-- Breadcrumb navigation -->
    <nav class="breadcrumb-nav" aria-label="Breadcrumb" style="margin-bottom: 1.25rem;">
      <a href="index.php" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.88rem;"><i class="ri-home-line"></i> Home</a>
      <span style="color: rgba(255,255,255,0.4); margin: 0 0.5rem;">/</span>
      <a href="products.php" style="color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.88rem;">Products</a>
      <span style="color: rgba(255,255,255,0.4); margin: 0 0.5rem;">/</span>
      <span style="color: var(--teal-brand); font-size: 0.88rem; font-weight: 600;"><?= h($currentProduct['name']) ?></span>
    </nav>

    <div class="product-hero-header" style="max-width: 900px;">
      <span class="product-category-pill" style="display: inline-flex; align-items: center; gap: 0.4rem; background: rgba(0,168,150,0.18); color: var(--teal-brand); border: 1px solid rgba(0,168,150,0.35); padding: 0.35rem 0.85rem; border-radius: 30px; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 1rem;">
        <i class="ri-shield-check-line"></i> <?= h($currentProduct['category']) ?>
      </span>
      <h1 class="product-hero-title" style="font-size: 2.75rem; font-weight: 800; color: #ffffff; line-height: 1.2; margin-bottom: 0.75rem;">
        <?= h($currentProduct['name']) ?>
      </h1>
      <p class="product-hero-tagline" style="font-size: 1.1rem; line-height: 1.6; color: rgba(255,255,255,0.9); margin-bottom: 1.75rem;">
        <?= h($currentProduct['tagline']) ?>
      </p>

      <!-- HERO ACTION CTAS -->
      <div class="product-hero-ctas" style="display: flex; gap: 0.85rem; align-items: center; flex-wrap: wrap;">
        <a href="contact.php?product=<?= urlencode($currentProduct['name']) ?>#enquiry-form" class="btn btn-gold btn-lg" style="box-shadow: 0 4px 14px rgba(212,175,55,0.35);">
          <i class="ri-mail-send-line"></i> Request a Formal Quote
        </a>
        <button type="button" class="btn btn-outline-teal btn-lg open-specs-modal" style="border-color: var(--teal-brand); color: #ffffff; background: rgba(0,168,150,0.18); cursor: pointer;">
          <i class="ri-file-settings-line"></i> See Technical Specs
        </button>
        <a href="contact.php?product=<?= urlencode($currentProduct['name']) ?>&action=audit#enquiry-form" class="btn btn-outline-white btn-lg" style="color: #ffffff; border-color: rgba(255,255,255,0.35); background: rgba(255,255,255,0.08);">
          <i class="ri-calendar-check-line"></i> Schedule Facility Audit
        </a>
      </div>

    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- MAIN PRODUCT SPECIFICATION & MEDIA LAYOUT                         -->
<!-- ================================================================ -->
<section class="section product-detail-body" style="padding: 3.5rem 0;">
  <div class="container">
    <div class="product-detail-grid">
      
      <!-- Left Column: Visual Showcase & RFQ CTA Box -->
      <div class="product-visual-col">
        
        <!-- Main Product Image Frame -->
        <div class="product-image-card">
          <?= render_image($currentProduct['image'], $currentProduct['name'], 'product-detail-main-img', ['loading' => 'eager']) ?>
          <div class="product-image-caption">
            <i class="ri-checkbox-circle-fill text-teal"></i> Pharmaceutical Secondary Packaging Standard
          </div>
        </div>

        <!-- Primary RFQ CTA Action Card -->
        <div class="card product-rfq-card">
          <span class="rfq-card-badge"><i class="ri-send-plane-fill"></i> DIRECT INQUIRY</span>
          <h3>Request a Quotation</h3>
          <p>
            Request custom dielines, physical samples, or a formal technical quotation for <strong><?= h($currentProduct['name']) ?></strong>.
          </p>
          <a href="contact.php?product=<?= urlencode($currentProduct['name']) ?>#enquiry-form" class="btn btn-gold btn-lg" style="width:100%; text-align:center; justify-content:center; margin-bottom:0.75rem;">
            <i class="ri-mail-send-line"></i> Request a Quote &rarr;
          </a>
          <button type="button" class="btn btn-outline-navy btn-sm open-specs-modal" style="width:100%; text-align:center; justify-content:center; margin-bottom:0.75rem; cursor:pointer;">
            <i class="ri-file-settings-line"></i> View Technical Specs &rarr;
          </button>
          
          <div class="rfq-direct-help" style="margin-top:1rem;">
            <i class="ri-phone-line text-teal"></i>
            <div>
              <span>Technical Sales Desk:</span>
              <strong>+92 21-38893400-3</strong>
            </div>
          </div>
        </div>

        <!-- Trust Badges Card -->
        <div class="card product-trust-card">
          <h4>Verified Quality Standards</h4>
          <ul class="product-trust-list">
            <li><i class="ri-checkbox-circle-fill text-teal"></i> 100% cGMP Line Clearance Protocols</li>
            <li><i class="ri-checkbox-circle-fill text-teal"></i> ISO 9001:2015 Certified (KQ.2025.5393)</li>
            <li><i class="ri-checkbox-circle-fill text-teal"></i> FSC Chain of Custody (RR-COC-003348)</li>
            <li><i class="ri-checkbox-circle-fill text-teal"></i> In-House Optical &amp; Physical Laboratory Testing</li>
          </ul>
        </div>

      </div>

      <!-- Right Column: Engineering Details & Specifications -->
      <div class="product-specs-col">
        
        <!-- Product Overview Narrative (H2 Header) -->
        <div class="product-specs-intro" style="margin-bottom: 2rem;">
          <h2 style="font-size: 2.2rem; color: var(--navy-dark); font-weight: 800; line-height: 1.25; margin-bottom: 1rem;">
            Product Overview &amp; Engineering Precision
          </h2>
          <p style="font-size: 1.05rem; line-height: 1.75; color: var(--text-body); margin: 0;"><?= h($currentProduct['overview']) ?></p>
        </div>

        <!-- 6-PILLAR STRUCTURED PRODUCT DETAILS GRID -->
        <div class="product-six-pillars-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.15rem; margin-bottom: 2rem;">
          
          <!-- 1. What the product is -->
          <div class="pillar-box" style="background: #ffffff; border: 1px solid var(--border-color); border-top: 4px solid var(--navy-dark); border-radius: 8px; padding: 1.15rem; box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.45rem;">
              <i class="ri-box-3-line text-teal" style="font-size: 1.2rem;"></i>
              <strong style="color: var(--navy-dark); font-size: 0.95rem;">What the Product Is</strong>
            </div>
            <p style="font-size: 0.88rem; color: var(--text-body); margin: 0; line-height: 1.55;"><?= h($currentProduct['what_it_is'] ?? '') ?></p>
          </div>

          <!-- 2. What it is used for -->
          <div class="pillar-box" style="background: #ffffff; border: 1px solid var(--border-color); border-top: 4px solid var(--teal-primary); border-radius: 8px; padding: 1.15rem; box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.45rem;">
              <i class="ri-function-line text-teal" style="font-size: 1.2rem;"></i>
              <strong style="color: var(--navy-dark); font-size: 0.95rem;">What It Is Used For</strong>
            </div>
            <p style="font-size: 0.88rem; color: var(--text-body); margin: 0; line-height: 1.55;"><?= h($currentProduct['what_used_for'] ?? '') ?></p>
          </div>

          <!-- 3. Who uses it -->
          <div class="pillar-box" style="background: #ffffff; border: 1px solid var(--border-color); border-top: 4px solid var(--gold-accent); border-radius: 8px; padding: 1.15rem; box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.45rem;">
              <i class="ri-user-star-line text-gold" style="font-size: 1.2rem;"></i>
              <strong style="color: var(--navy-dark); font-size: 0.95rem;">Who Uses It</strong>
            </div>
            <p style="font-size: 0.88rem; color: var(--text-body); margin: 0; line-height: 1.55;"><?= h($currentProduct['who_uses_it'] ?? '') ?></p>
          </div>

          <!-- 4. Main packaging applications -->
          <div class="pillar-box" style="background: #ffffff; border: 1px solid var(--border-color); border-top: 4px solid var(--teal-brand); border-radius: 8px; padding: 1.15rem; box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.45rem;">
              <i class="ri-apps-2-line text-teal" style="font-size: 1.2rem;"></i>
              <strong style="color: var(--navy-dark); font-size: 0.95rem;">Main Packaging Applications</strong>
            </div>
            <p style="font-size: 0.88rem; color: var(--text-body); margin: 0; line-height: 1.55;"><?= h($currentProduct['main_applications'] ?? '') ?></p>
          </div>

          <!-- 5. Available customization -->
          <div class="pillar-box" style="background: #ffffff; border: 1px solid var(--border-color); border-top: 4px solid var(--navy-primary); border-radius: 8px; padding: 1.15rem; box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.45rem;">
              <i class="ri-tools-line text-teal" style="font-size: 1.2rem;"></i>
              <strong style="color: var(--navy-dark); font-size: 0.95rem;">Available Customization</strong>
            </div>
            <p style="font-size: 0.88rem; color: var(--text-body); margin: 0; line-height: 1.55;"><?= h($currentProduct['customization'] ?? '') ?></p>
          </div>

          <!-- 6. Key benefits -->
          <div class="pillar-box" style="background: #ffffff; border: 1px solid var(--border-color); border-top: 4px solid var(--gold-dark); border-radius: 8px; padding: 1.15rem; box-shadow: var(--shadow-sm);">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.45rem;">
              <i class="ri-checkbox-circle-line text-gold" style="font-size: 1.2rem;"></i>
              <strong style="color: var(--navy-dark); font-size: 0.95rem;">Key Benefits</strong>
            </div>
            <p style="font-size: 0.88rem; color: var(--text-body); margin: 0; line-height: 1.55;"><?= h($currentProduct['key_benefits'] ?? '') ?></p>
          </div>

        </div>

      </div>

    </div>
  </div>
</section>

<!-- ================================================================ -->
<!-- TECHNICAL SPECIFICATIONS MODAL POPUP                              -->
<!-- ================================================================ -->
<div id="specsModal" class="specs-modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(10,37,64,0.75); z-index: 99999; backdrop-filter: blur(4px); overflow-y: auto; padding: 2rem 1rem; align-items: center; justify-content: center;">
  <div class="specs-modal-dialog" style="background: #ffffff; border-radius: 12px; max-width: 860px; width: 100%; margin: auto; box-shadow: 0 20px 50px rgba(0,0,0,0.3); overflow: hidden; position: relative; animation: modalFadeIn 0.25s ease-out;">
    
    <!-- Modal Header -->
    <div style="background: linear-gradient(135deg, var(--navy-dark) 0%, #173b6c 100%); color: #ffffff; padding: 1.5rem 2rem; display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid var(--teal-brand);">
      <div style="display: flex; align-items: center; gap: 0.85rem;">
        <div style="width: 40px; height: 40px; border-radius: 8px; background: rgba(0,168,150,0.25); display: flex; align-items: center; justify-content: center; font-size: 1.35rem; color: var(--teal-brand);">
          <i class="ri-file-settings-line"></i>
        </div>
        <div>
          <h3 style="margin: 0; font-size: 1.3rem; color: #ffffff; font-weight: 800;">Technical Specifications Sheet</h3>
          <span style="font-size: 0.82rem; color: rgba(255,255,255,0.8);"><?= h($currentProduct['name']) ?> &mdash; <?= h($currentProduct['category']) ?></span>
        </div>
      </div>
      <button type="button" id="closeSpecsModal" style="background: rgba(255,255,255,0.12); border: none; color: #ffffff; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; cursor: pointer; transition: background 0.2s;">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <!-- Modal Body with Full Specs Table -->
    <div style="padding: 2rem; max-height: 65vh; overflow-y: auto;">
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; flex-wrap: wrap; gap: 0.5rem;">
        <p style="margin: 0; font-size: 0.92rem; color: var(--text-body);">
          Verified engineering specifications for high-speed cartoning lines and regulatory audit compliance.
        </p>
        <span class="specs-updated-badge" style="font-size: 0.75rem; font-weight: 700; background: rgba(0,168,150,0.12); color: var(--teal-primary); padding: 0.25rem 0.65rem; border-radius: 20px;">
          <i class="ri-checkbox-circle-fill"></i> ISO 9001:2015 &amp; cGMP Verified
        </span>
      </div>

      <table class="product-specs-table" style="width: 100%; border-collapse: collapse; font-size: 0.92rem;">
        <thead>
          <tr style="background: #f8fafc; border-bottom: 2px solid var(--border-color);">
            <th style="width: 34%; text-align: left; padding: 0.85rem 1rem; color: var(--navy-dark); font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Parameter / Property</th>
            <th style="text-align: left; padding: 0.85rem 1rem; color: var(--navy-dark); font-weight: 700; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em;">Standard Technical Specification</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($currentProduct['specs'] as $param => $val): ?>
            <tr style="border-bottom: 1px solid var(--border-color);">
              <td class="spec-param-title" style="padding: 0.85rem 1rem; font-weight: 700; color: var(--navy-dark); vertical-align: top; background: #fafbfc;">
                <i class="ri-arrow-right-s-line text-teal"></i> <?= h($param) ?>
              </td>
              <td class="spec-param-value" style="padding: 0.85rem 1rem; color: var(--text-body); line-height: 1.6; vertical-align: top;">
                <?= h($val) ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Modal Footer -->
    <div style="background: #f8fafc; padding: 1.25rem 2rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid var(--border-color); flex-wrap: wrap; gap: 1rem;">
      <div style="font-size: 0.85rem; color: var(--text-muted);">
        <i class="ri-shield-check-line text-teal"></i> Prince Art Packages Quality Assurance Desk
      </div>
      <div style="display: flex; gap: 0.75rem;">
        <button type="button" class="btn btn-outline-navy btn-sm" onclick="window.print()" style="cursor: pointer;">
          <i class="ri-printer-line"></i> Print Specs
        </button>
        <a href="contact.php?product=<?= urlencode($currentProduct['name']) ?>#enquiry-form" class="btn btn-gold btn-sm">
          <i class="ri-mail-send-line"></i> Request Quote for these Specs &rarr;
        </a>
        <button type="button" id="closeSpecsModalBtn" class="btn btn-navy btn-sm" style="cursor: pointer;">
          Close
        </button>
      </div>
    </div>

  </div>
</div>

<style>
@keyframes modalFadeIn {
  from { opacity: 0; transform: scale(0.96) translateY(-10px); }
  to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var modal = document.getElementById('specsModal');
  var openBtns = document.querySelectorAll('.open-specs-modal');
  var closeBtn = document.getElementById('closeSpecsModal');
  var closeBtn2 = document.getElementById('closeSpecsModalBtn');

  function openModal() {
    if (modal) {
      modal.style.display = 'flex';
      document.body.style.overflow = 'hidden';
    }
  }

  function closeModal() {
    if (modal) {
      modal.style.display = 'none';
      document.body.style.overflow = '';
    }
  }

  openBtns.forEach(function(btn) {
    btn.addEventListener('click', openModal);
  });

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (closeBtn2) closeBtn2.addEventListener('click', closeModal);

  if (modal) {
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        closeModal();
      }
    });
  }

  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
      closeModal();
    }
  });
});
</script>

<!-- ================================================================ -->
<!-- RELATED PRODUCTS BROWSER                                          -->
<!-- ================================================================ -->
<section class="section" style="background: var(--bg-alt); padding: 4.5rem 0; border-top: 1px solid var(--border-color);">
  <div class="container">
    <div class="section-header" style="text-align: center; max-width: 700px; margin: 0 auto 3rem auto;">
      <span class="section-subtitle">COMPLEMENTARY PACKAGING</span>
      <h2 style="font-size: 2rem; color: var(--navy-dark); font-weight: 800;">Explore Other Secondary Packaging Solutions</h2>
      <p style="color: var(--text-muted); font-size: 0.95rem;">
        Discover our integrated product range engineered to work cohesively across your pharmaceutical packaging lines.
      </p>
    </div>

    <div class="grid-3-products">
      <?php 
        $counter = 0;
        foreach ($productSpecsCatalog as $relSlug => $relProd):
          if ($relSlug === $currentSlug) continue;
          if ($counter >= 3) break;
          $counter++;
      ?>
        <div class="product-card">
          <a href="product-detail.php?slug=<?= urlencode($relSlug) ?>" style="text-decoration: none; color: inherit;">
            <?= render_image($relProd['image'], $relProd['name'], 'card-img-top') ?>
          </a>
          <div class="card-body">
            <div class="card-text-content">
              <span class="cert-pill" style="background:var(--teal-bg); color:var(--teal-brand); margin-bottom:0.5rem; display:inline-block;">
                <?= h($relProd['category']) ?>
              </span>
              <h3 class="card-title">
                <a href="product-detail.php?slug=<?= urlencode($relSlug) ?>" style="text-decoration: none; color: var(--navy-dark);">
                  <?= h($relProd['name']) ?>
                </a>
              </h3>
              <p style="font-size: 0.88rem; line-height: 1.5;"><?= h($relProd['tagline']) ?></p>
            </div>
            <div class="btn-quote-wrapper">
              <a href="product-detail.php?slug=<?= urlencode($relSlug) ?>" class="btn btn-outline-teal btn-sm" style="width:100%; text-align:center;">
                <i class="ri-file-list-3-line"></i> View Specifications &rarr;
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align: center; margin-top: 2.5rem;">
      <a href="products.php" class="btn btn-outline-navy btn-lg">
        <i class="ri-arrow-left-line"></i> View Full Product Catalog
      </a>
    </div>

  </div>
</section>

<?php
if (isset($currentProduct) && $currentProduct) {
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://princeartpackages.com/'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Products', 'item' => 'https://princeartpackages.com/products'],
            ['@type' => 'ListItem', 'position' => 3, 'name' => htmlspecialchars($currentProduct['name'], ENT_QUOTES, 'UTF-8'), 'item' => 'https://princeartpackages.com/product/' . urlencode($currentSlug)]
        ]
    ];
    $productSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => htmlspecialchars($currentProduct['name'] ?? '', ENT_QUOTES, 'UTF-8'),
        'description' => htmlspecialchars(strip_tags($currentProduct['overview'] ?? $currentProduct['what_it_is'] ?? ''), ENT_QUOTES, 'UTF-8'),
        'brand' => ['@type' => 'Brand', 'name' => 'Prince Art Packages'],
        'manufacturer' => ['@type' => 'Organization', 'name' => 'Prince Art Packages (Private) Limited'],
        'url' => 'https://princeartpackages.com/product/' . urlencode($currentSlug ?? ''),
        'image' => !empty($currentProduct['image']) ? 'https://princeartpackages.com/' . ltrim($currentProduct['image'], '/') : 'https://princeartpackages.com/assets/images/prod_cartons.jpg'
    ];
?>
<script type="application/ld+json"><?= json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<script type="application/ld+json"><?= json_encode($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
<?php } ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
