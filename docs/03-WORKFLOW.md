# Prince Art Packages — Master Workflow (Website → SEO → Social)

## Order of work (phases)

### PHASE 0 — Align & Decide (now)
- [ ] Pick hosting option (A/B/C) — see `00-STATUS-AND-DECISIONS.md`.
- [ ] Confirm real-URL conversion (required for SEO).
- [ ] Gather real assets: logo files, facility/product photos, cert PDFs, testimonials.

### PHASE 1 — Finish & Build the Website
- [ ] Apply design spec (`01-WEBSITE-SPEC.md`).
- [ ] Convert sections → real URLs (Blocker #1).
- [ ] Fix canonical/OG to live domain.
- [ ] Merge Resources + Blogs.
- [ ] Replace placeholder case studies.
- [ ] Image compression + alt text.
- [ ] Privacy / Terms pages.
- [ ] Mobile + form + CTA QA on real phone.
> Deliverable: deployable, responsive, SEO-ready site.

### PHASE 2 — Deploy  (target host = StackCP / 20i shared Linux, PHP/MySQL)
> Node backend CANNOT run on StackCP. See `00-STATUS-AND-DECISIONS.md` Option B or C.

**If Option B (static + tiny Node CMS host):**
- [ ] Build static HTML export of all pages (real URLs) from the site source.
- [ ] Upload static files to StackCP via **File Manager** or **FTP** into the site root (`/home/sites/1b/f/fd44d61855/public_html` or StackCP's web root).
- [ ] Deploy the Node/Express + MySQL CMS to a small Node host (Railway/Render/VPS); set env vars + DB.
- [ ] Point static site's blog/RFQ calls at the CMS host URL (CORS allowed).
- [ ] Set up 301 redirects (old live URLs → new) via StackCP redirects/CDN.

**If Option C (pure static, no DB):**
- [ ] Build static HTML; upload to StackCP web root.
- [ ] Wire RFQ form to email/Google Sheet endpoint.
- [ ] Set up 301 redirects from old site.

**Both:**
- [ ] Verify live site end-to-end on the real domain (https://www.princeartpackages.com).
- [ ] Submit sitemap in Google Search Console only after real URLs live.

### PHASE 3 — Technical SEO (in-house or first outsourced milestone)
- [ ] Work `02-SEO-CHECKLIST.md` Phase 1.
- [ ] Set up Search Console + GA4 + sitemap submission.
- [ ] Speed/Core Web Vitals pass.

### PHASE 4 — On-Page SEO (outsourced, milestone 2)
- [ ] Keyword map; per-page tags; content depth; schema.
- [ ] Publish blog plan.

### PHASE 5 — Off-Page SEO (outsourced, milestone 3 — only after 3&4 done)
- [ ] Local SEO, citations, GBP, link building.

### PHASE 6 — Social Media Marketing (after site is live & stable)
- Platforms: **LinkedIn** (primary B2B), **Instagram** (visual/process), **Facebook** (company page).
- Content pillars: certifications, process/plant tours, product education, ColdSeal & 3D-Engravix explainers, client wins (anonymized), industry news.
- Cadence: LinkedIn 3×/wk, IG 2×/wk, FB 2×/wk.
- Repurpose blog articles into posts. Drive traffic to site (feeds SEO).

## Notes
- SEO is gated: Technical → On-Page → Off-Page. No skipping.
- Pay outsourced SEO per milestone tied to this checklist.
- All docs live in `docs/`; keep updated as work progresses.
