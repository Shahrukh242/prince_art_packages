# Prince Art Packages — Project Status & Decisions Needed

*Prepared by Hermes (your team member) after reviewing the live site, the GitHub repo, the local build, and the client's hosting control panel (StackCP / 20i).*
*Date: 23 Aug 2026*

---

## 1. What the business actually is
**Prince Art Packages (Private) Limited** (formerly *Prince Art Press*) — a **B2B pharmaceutical secondary-packaging manufacturer** in Karachi, Pakistan.
- **Products:** Folding cartons, leaf-inserts/outserts, printed labels & tamper-evident seals, honeycomb separators, pill-folders, 3D-ENGRAVIX™ (optical anti-counterfeit), ColdSeal Blister Wallet.
- **Proof points (use everywhere):** ISO 9001:2015 (KQ.2025.5393, ASCERT/MSCB-223), FSC Chain of Custody (RR-COC-003348, FSC-C222205), cGMP. Two plants in Korangi Creek Industrial Park.
- **Capacity:** 8–10M cartons/mo, 23–25M leaflets/mo, 15 printing machines.
- **Contact on record:** Tel +92 21-38893400-3, email info@princeartpackages.com.
- ⚠️ **Live site mismatch:** current princeartpackages.com is an OLD design ("Fiber Shield", face shields). New repo build is correct and should replace it.

---

## 2. What's already built (good foundation — we finish, not start)
- **Front-end:** `public/index.html` — responsive, professional B2B-pharma design (navy + teal + gold), 11 sections.
- **CMS backend:** Node.js + Express + MySQL (`src/`) with admin dashboard `/admin.html`.
- **SEO scaffolding:** dynamic `sitemap.xml` + `robots.txt`, 301 redirect engine, per-section meta swapping.

---

## 3. 🔴 HOSTING CONFIRMED — StackCP / 20i shared Linux (PHP/MySQL)
From the control-panel screenshot:
- Platform: **Autoscaling Linux** (shared hosting) · Home path `/home/sites/1b/f/fd44d61855/`
- Tools: CDN, File Manager, FTP, Web Builder, Backups, Mail. **No Node runtime / app hosting.**
- IP `185.151.30.150`, London UK.
- **Implication:** the Node.js + MySQL app **cannot run here** as-is. This eliminates "deploy the Node backend on their host."

### The two realistic options now (was A/B/C — A is removed)
| Option | Build | CMS/admin | Forms | Best for |
|---|---|---|---|---|
| **B. Static site + tiny Node host for CMS** | Generate SEO-friendly static HTML pages → upload to StackCP via File Manager/FTP. | Keep admin + API on a small Node host (e.g. Railway/Render free tier, or a cheap VPS) that talks to a MySQL DB. | Static pages call the Node API for blog + RFQ capture. | Client keeps self-edit CMS; site is 100% SEO-friendly on their host. |
| **C. Pure static, no live DB** | Pure HTML/CSS/JS → StackCP. | None (edits by us/developer). | RFQ form posts to email (Formspree/own script) or a Google Sheet. | Simplest, cheapest, zero backend to maintain. Client can't self-edit. |

**Recommendation:** **Option B** if the client wants to manage blog/products themselves (the CMS already exists — don't throw it away). **Option C** if they're happy for us to update content. Either way the *public site* lives on StackCP and is fully crawlable.

> Note: even on Option B, the live **database on the tiny Node host** is the only moving part. The marketing site itself stays on the client's own hosting.

---

## 4. 🔴 BLOCKER #1 — Site is "one page" (hash routing). Breaks SEO.
Every section loads by JS on a single URL; address only changes after `#` (e.g. `#products`).
- Google sees ~one page → products/articles can't rank.
- Sitemap currently lists `#` URLs (Google ignores fragments).
**Required:** give each product/service/article its **own real URL** (e.g. `/products/folding-cartons`, `/blog/coldseal-50-percent-less-plastic`). This is mandatory before outsourced SEO.

---

## 5. Other pre-launch fixes
- [ ] Canonical/OG tags point to `localhost:3000` → must become `https://www.princeartpackages.com`.
- [ ] Merge duplicate "Resources" + "Blogs" into one hub.
- [ ] Replace placeholder case studies with real (anonymized) wins.
- [ ] Real domain in sitemap/robots.
- [ ] Google Search Console + GA4 set up **before** SEO outsourcing.
- [ ] 301-redirect old live-site URLs → new ones.
- [ ] Image compression (several JPGs 700KB+), alt text, favicon, social share image.
- [ ] Privacy Policy + Terms pages.

---

## 6. Decisions — STATUS
1. **Hosting path: ✅ OPTION B chosen** (client wants self-edit CMS). Static pages → StackCP; Node/Express+MySQL CMS → small Node host (Railway/Render/VPS). See `03-WORKFLOW.md` Phase 2 for steps.
2. **Real-URL conversion: ✅ required & in progress** (see `tools/build-static.js` → `dist/`). This unblocks outsourced SEO.
3. **Order confirmed:** finish website → deploy → technical SEO → on-page SEO (outsourced) → off-page SEO (outsourced) → social media.

### Still needed from the CLIENT (cannot be invented)
- [ ] Real (anonymized) case studies to replace placeholders in Case Studies section.
- [ ] Any client testimonials / quotes (none in repo yet).
- [ ] Cert PDF scans if they want downloadable certs on the site.
- [ ] Final copy approval on product descriptions.
- [ ] The Node CMS host URL (e.g. cms.princeartpackages.com) once provisioned — used by static site's API calls.
