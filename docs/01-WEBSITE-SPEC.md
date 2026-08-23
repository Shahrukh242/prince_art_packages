# Prince Art Packages — Website Design Specification

*Plain-language design direction + page-by-page layout. Based on the existing (good) design system already in the repo.*

---

## 1. The look & feel (theme)

**Personality:** Trustworthy, industrial, precise, premium-B2B. Think "audited pharmaceutical supplier," not "flashy agency."

### Colour palette (already defined in `prince-art.css` — keep it)
| Role | Colour | Hex | Use |
|---|---|---|---|
| Primary / authority | Deep Navy | `#0B2545` / `#12305C` | Headings, header, footer, dark sections |
| Brand / credibility | Teal | `#139EA8` | Icons, links, badges, trust accents |
| Premium accent | Gold | `#C9A24B` | Primary CTA buttons ("Request a Quote"), cert highlights |
| Background | Light grey | `#F7F8FA` | Page background |
| Surface | White | `#FFFFFF` | Cards, sections |
| Text | Charcoal | `#1F2933` / `#3E4C59` | Body & headings |

**Rule:** Navy = trust/structure, Teal = brand/credibility, Gold = the action we want (get a quote). Never use more than these three accents at once.

### Typography
- **Font:** Inter (already loaded). Clean, modern, highly legible — correct for technical B2B.
- **Scale:** H1 ~2.6rem, H2 ~1.95rem, body 15px / line-height 1.6. Generous whitespace.

### Visual language
- Card-based layout, subtle shadows (`--shadow-md`), small radii (4–12px).
- Cert "pills" (e.g. `ISO 9001:2015 • FSC CERTIFIED`).
- Remix Icon line-icons (already in use) for scannable sections.
- Real photography: facility, cartons, leaflets, labels, ColdSeal, 3D-Engravix. Avoid generic stock where possible.

### Responsive
- Already built: mobile hamburger menu, fluid grids. Verify on phone + tablet before launch.

---

## 2. Site structure (recommended navigation)

> ⚠️ These must become **real URLs** (see docs/00 Blocker #1), not `#` fragments.

1. **Home** (`/`) — hero + trust bar + capabilities + innovation teaser + CTA.
2. **About** (`/about`) — heritage, two plants, facilities.
3. **Products** (`/products`) — 8 product cards, each with its own page (`/products/folding-cartons`, etc.).
4. **Capabilities** (`/capabilities`) — machinery, 8-stage production flow, capacity stats.
5. **Innovation** (`/innovation`) — ColdSeal Blister Wallet + 3D-Engravix™ + security suite.
6. **Quality** (`/quality`) — ISO/FSC certs, OurLAB testing lab.
7. **Industries** (`/industries`) — pharma, healthcare/OTC, biologics/devices.
8. **Sustainability** (`/sustainability`) — FSC, ColdSeal footprint, recycling.
9. **Resources / Blog** (`/blog`) — merge old "Resources" + "Blogs" into one hub with category filters.
10. **Contact / RFQ** (`/contact`) — quotation form + addresses + certs.

---

## 3. Homepage layout (top → bottom)

1. **Sticky header:** logo (left) · nav (center) · gold "Request a Quote" button (right) · mobile hamburger.
2. **Hero:** left = certification badges (cGMP / ISO / FSC) + H1 "Precision Pharmaceutical Secondary Packaging Engineered for Global Audit Compliance" + sub-copy + two CTAs ("Request a Formal Quote" gold, "Explore 3D-Engravix & ColdSeal" teal); right = product/innovation image.
3. **Trust bar:** 4 items — ISO 9001:2015, FSC CoC, cGMP, Unit 1 & 2 plants (with cert numbers).
4. **Capabilities strip:** 3 cards (Folding Cartons, Leaf-Inserts, Printed Labels) + featured-innovation banner (ColdSeal + 3D-Engravix).
5. **Final CTA band:** "Planning Your Next Packaging Run?" → quote/contact.
6. **Footer (4 columns):** company + contact · quick links · products & tech · newsletter + plant locations. Admin link (discreet).

---

## 4. Content map (what copy each section needs)

| Section | Key message | Proof to show |
|---|---|---|
| Hero | We make audit-ready pharma packaging | cGMP / ISO / FSC badges |
| About | Trusted since Prince Art Press; 2 modern plants | Unit addresses, facility photo |
| Products | 8 compliant packaging types | Specs, cert pills, "Request a Quote" per product |
| Capabilities | High-volume, precise, integrated | 15 machines, 8–10M cartons/mo, 8-stage flow |
| Innovation | Two proprietary edges competitors lack | ColdSeal (–50% plastic/foil), 3D-Engravix (no-device auth) |
| Quality | Verified, tested, auditable | ISO/FSC cert numbers, OurLAB |
| Industries | We serve regulated formulation plants | 3 sector cards |
| Sustainability | Responsible + lower footprint | FSC, recycling 98%, ColdSeal savings |
| Blog | Technical authority / thought leadership | 6+ articles, category filter |
| Contact | Easy to request a quote / audit | RFQ form, 2 addresses, tel/email |

---

## 5. Finish-list (what's left before launch)

- [ ] Convert `#` sections → real URLs (Blocker #1).
- [ ] Fix canonical/OG to live domain.
- [ ] Merge Resources + Blogs into one hub.
- [ ] Replace placeholder case studies with real anonymized wins.
- [ ] Add alt text + descriptive filenames to all images (SEO).
- [ ] Compress images (several are 700KB+ JPGs) for speed.
- [ ] Add a privacy policy + terms page (trust + legal).
- [ ] Verify mobile menu, forms, and CTAs on a real phone.
- [ ] Set favicon, social share image.
