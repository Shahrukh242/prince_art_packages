# Prince Art Packages — SEO Master Checklist (Outsource Verification)

*Use this as your acceptance sheet. Every box must be ✅ before you pay the SEO provider. Sections: Technical → On-Page → Off-Page. Status legend: ⬜ not started · 🟡 in progress · ✅ done.*

---

## PHASE 1 — TECHNICAL SEO  (must be fixed BEFORE content work)

> Most of these are **also website-build tasks**. If the site is still one URL with `#` links, the rest of SEO is wasted.

### 1.1 Crawlability & Indexing
- [ ] ⬜ **Real URLs:** every product/service/article has its own address (no `#` fragments). Pages return HTTP 200.
- [ ] ⬜ **sitemap.xml** valid, lists only real URLs, submitted in Google Search Console. (Current build emits `#` URLs — fix the generator.)
- [ ] ⬜ **robots.txt** allows crawling, references the sitemap, blocks `/admin.html` & `/api/`.
- [ ] ⬜ **301 redirects:** old live-site URLs → new URLs (protect existing backlinks/rankings). Redirect engine already exists in repo — configure it.
- [ ] ⬜ **Canonical tags** correct on every page (self-referencing; no duplicates).
- [ ] ⬜ **HTTPS** enforced site-wide (redirect http→https, www↔non-www chosen consistently).
- [ ] ⬜ **XML + HTML sitemap** both present.
- [ ] ⬜ **No orphan pages**; every page reachable in ≤3 clicks from home.
- [ ] ⬜ **Crawl test:** site fully renderable by Googlebot (JS content is server-rendered or pre-rendered — *critical because the site is JS-driven*).

### 1.2 Site Speed & Core Web Vitals
- [ ] ⬜ **Mobile-friendly** (Google Mobile-Friendly Test pass).
- [ ] ⬜ **LCP < 2.5s**, **CLS < 0.1**, **INP < 200ms** (PageSpeed Insights, mobile).
- [ ] ⬜ Images compressed & served in modern format (WebP). (Several current JPGs are 700KB+ — fix.)
- [ ] ⬜ Render-blocking CSS/JS minimized; lazy-load below-fold images.
- [ ] ⬜ Browser caching + GZIP/Brotli enabled.

### 1.3 Structure & Schema
- [ ] ⬜ **HTTPS, clean URL structure** (`/products/folding-cartons`, not `?id=12`).
- [ ] ⬜ **Breadcrumbs** on inner pages + `BreadcrumbList` schema.
- [ ] ⬜ **Organization / LocalBusiness schema** (name, addresses, phone, certs, logo).
- [ ] ⬜ **Product schema** on each product page.
- [ ] ⬜ **Article schema** on each blog post.
- [ ] ⬜ **FAQ schema** where Q&A exists.
- [ ] ⬜ **XML hreflang** if multilingual later (not now).

### 1.4 Tracking (set up BEFORE outreach)
- [ ] ⬜ Google Search Console verified (DNS or HTML tag).
- [ ] ⬜ Google Analytics 4 installed + conversions (quote form submit) tracked.
- [ ] ⬜ Bing Webmaster Tools (optional but cheap).
- [ ] ⬜ Monthly rank/position reporting dashboard agreed.

---

## PHASE 2 — ON-PAGE SEO

### 2.1 Keyword Strategy (do this first)
- [ ] ⬜ Keyword research for pharma packaging terms (e.g. "pharmaceutical folding cartons Pakistan", "cGMP carton manufacturer", "ColdSeal blister wallet", "FSC certified packaging").
- [ ] ⬜ Primary + secondary keyword mapped to each page (no keyword cannibalization).
- [ ] ⬜ Competitor gap analysis (who ranks for these terms now).

### 2.2 Per-Page Elements (every indexable page)
- [ ] ⬜ **Title tag** 50–60 chars, unique, includes primary keyword + brand.
- [ ] ⬜ **Meta description** 150–160 chars, compelling, unique.
- [ ] ⬜ **H1** single, includes primary keyword; H2/H3 hierarchy logical.
- [ ] ⬜ **URL** short, keyword-rich, hyphenated.
- [ ] ⬜ **Image alt text** descriptive (not "img123"); filenames keyword-rich.
- [ ] ⬜ **Internal links** between related products/articles.
- [ ] ⬜ **External links** to authoritative sources (FSC, ISO) where relevant.
- [ ] ⬜ **Content depth:** ≥300–800 words on key pages; blog ≥1,200 words for pillar posts.
- [ ] ⬜ **Readability:** short paragraphs, scannable, plain language where possible.
- [ ] ⬜ **OG + Twitter cards** set per page (for social sharing).
- [ ] ⬜ **No duplicate content**; canonical where needed.

### 2.3 Content Plan
- [ ] ⬜ Publish 6+ blog/articles already drafted in repo (ColdSeal, 3D-Engravix, ISO/cGMP, etc.).
- [ ] ⬜ Replace placeholder case studies with real, anonymized client wins.
- [ ] ⬜ Add **FAQ** section (feeds FAQ schema + captures long-tail queries).
- [ ] ⬜ Editorial calendar (e.g. 2 posts/month).

---

## PHASE 3 — OFF-PAGE SEO  (after Technical + On-Page are solid)

### 3.1 Foundation
- [ ] ⬜ **Google Business Profile** (Korangi Creek, Karachi) claimed & optimized (photos, hours, certs).
- [ ] ⬜ **Local citations** consistent NAP (Name/Address/Phone) across directories.
- [ ] ⬜ Industry directories (pharma packaging, packaging-portal, export/b2b).

### 3.2 Link Building (quality > quantity)
- [ ] ⬜ Digital PR: trade publications (Packaging Europe, Pharma Packaging News) features.
- [ ] ⬜ Guest articles on pharma-manufacturing / packaging blogs.
- [ ] ⬜ Backlinks from certification bodies / partner pages where allowed.
- [ ] ⬜ Supplier/distributor reciprocal links.
- [ ] ⬜ **No** PBNs, link farms, or paid spam links (risk penalties).

### 3.3 Authority & Signals
- [ ] ⬜ Reviews/testimonials gathered (LinkedIn recommendations from clients).
- [ ] ⬜ Social signals (see Social Media plan) reinforcing brand.
- [ ] ⬜ Quarterly backlink audit (disavow toxic links).

---

## ACCEPTANCE / REPORTING
The SEO provider must deliver **monthly**:
- [ ] Rankings for target keywords (before/after).
- [ ] Organic traffic & conversions (GA4).
- [ ] Index coverage (Search Console).
- [ ] Backlinks acquired (off-page phase).
- [ ] List of fixes implemented against THIS checklist.

> **Gate:** Do not start Off-Page until Technical (Phase 1) + On-Page (Phase 2) are ✅. Pay per milestone, not upfront.
