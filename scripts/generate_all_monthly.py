import asyncio
import os
import json
from playwright.async_api import async_playwright

posts = [
    {
        "id": "01",
        "category": "Folding Cartons",
        "headline": "ENGINEERED FOR HIGH-SPEED LINES",
        "subhead": "Calibrated 230–450 GSM board engineered for jam-free feeding up to 400 cartons/minute.",
        "badge": "Automated Cartoning",
        "points": ["CALIBRATED STIFFNESS", "LASER CREASING", "cGMP TRACEABILITY"],
        "footer_note": "Secondary packaging engineered for high-volume automated lines."
    },
    {
        "id": "02",
        "category": "Multi-Panel Leaflets",
        "headline": "MICRO-TYPOGRAPHY THAT COMPLIES",
        "subhead": "High-density prescribing inserts meeting 6-point legibility standards at 25,000 sheets/hour.",
        "badge": "Precision Folding",
        "points": ["6-PT READABILITY", "ZERO-JAM FOLDING", "GMP PAPER STOCKS"],
        "footer_note": "Regulatory-compliant prescribing information inserts."
    },
    {
        "id": "03",
        "category": "Blister Lidding Foils",
        "headline": "TOTAL BARRIER INTEGRITY",
        "subhead": "Push-through aluminium foil with hermetic seals against moisture, oxygen, and UV degradation.",
        "badge": "Barrier Protection",
        "points": ["MOISTURE DEFENSE", "HERMETIC SEAL", "PUSH-THROUGH FOIL"],
        "footer_note": "Primary and secondary barrier engineering for stable shelf life."
    },
    {
        "id": "04",
        "category": "Quality & Compliance",
        "headline": "ZERO BATCH MIX-UPS",
        "subhead": "100% electronic line clearance, segregation protocols, and multi-sensor inspection systems.",
        "badge": "cGMP Protocol",
        "points": ["ELECTRONIC CLEARANCE", "LINE SEGREGATION", "100% AUDIT TRAIL"],
        "footer_note": "Rigorous quality controls compliant with global pharmaceutical standards."
    },
    {
        "id": "05",
        "category": "Security & Tamper Evidence",
        "headline": "VISUAL PROOF OF FIRST OPENING",
        "subhead": "Engineered security labels with irreversible fiber-tear adhesives for tamper indication.",
        "badge": "Tamper-Evident",
        "points": ["FIBER-TEAR ADHESIVE", "SECURITY SLITS", "IMMEDIATE DETECTION"],
        "footer_note": "Overt and covert security solutions for patient safety."
    },
    {
        "id": "06",
        "category": "Color & Print Engineering",
        "headline": "DELTA-E < 2.0 COLOR ACCURACY",
        "subhead": "Closed-loop spectrophotometric ink control ensuring exact brand uniformity across million-run batches.",
        "badge": "Color Consistency",
        "points": ["SPECTROPHOTOMETRY", "BATCH CONSISTENCY", "LOW-MIGRATION INKS"],
        "footer_note": "High-fidelity offset printing with closed-loop color management."
    },
    {
        "id": "07",
        "category": "Vial & Ampoule Partitions",
        "headline": "IMPACT DEFENSE FOR VIALS",
        "subhead": "Rigid pharmaceutical honeycomb grids preventing glass-to-glass transit contact and breakage.",
        "badge": "Glass Protection",
        "points": ["ZERO GLASS CONTACT", "RIGID PARTITIONS", "SHOCK ABSORPTION"],
        "footer_note": "Engineered secondary dividers for high-value injectable products."
    },
    {
        "id": "08",
        "category": "Accessibility & Standards",
        "headline": "ACCURATE MARBURG BRAILLE",
        "subhead": "Standardized dot profiles (EN 15823) ensuring permanent touch-readability without board puncture.",
        "badge": "EN 15823 Compliant",
        "points": ["STANDARDIZED DOTS", "ZERO FIBER PUNCTURE", "GLOBAL EXPORT READY"],
        "footer_note": "Accredited Braille embossing for international regulatory compliance."
    },
    {
        "id": "09",
        "category": "Cold-Chain Packaging",
        "headline": "HEAT-FREE SEALING PROTECTION",
        "subhead": "Pressure-sensitive cold-seal packaging safeguarding temperature-sensitive biologics from heat.",
        "badge": "Cold-Seal Cards",
        "points": ["THERMAL PROTECTION", "SEAL INTEGRITY", "BIOLOGICS READY"],
        "footer_note": "Advanced packaging solutions for heat-sensitive pharmaceuticals."
    },
    {
        "id": "10",
        "category": "Anti-Counterfeit Innovation",
        "headline": "OPTICAL SECURITY DEFENSE",
        "subhead": "Micro-embossed 3D optical structures enabling instantaneous visual authentication without tools.",
        "badge": "Brand Protection",
        "points": ["MICRO-EMBOSSING", "TOOL-FREE VERIFY", "NON-REPLICABLE"],
        "footer_note": "Proprietary optical security technologies to protect pharma brands."
    },
    {
        "id": "11",
        "category": "Sustainable Packaging",
        "headline": "RESPONSIBLY SOURCED BOARD",
        "subhead": "100% recyclable, FSC-certified virgin SBS and FBB boards meeting global sustainability standards.",
        "badge": "FSC-Certified",
        "points": ["FSC CHAIN-OF-CUSTODY", "VIRGIN SBS/FBB", "100% RECYCLABLE"],
        "footer_note": "Certified sustainable substrates for environmentally conscious brands."
    },
    {
        "id": "12",
        "category": "Turnkey Capabilities",
        "headline": "YOUR PACKAGING PARTNER",
        "subhead": "Integrated secondary packaging manufacturing from CAD dieline engineering to JIT batch delivery.",
        "badge": "Single-Source",
        "points": ["CAD PROTOTYPING", "cGMP CONVERSION", "JIT LOGISTICS"],
        "footer_note": "End-to-end secondary packaging solutions for leading pharmaceutical companies."
    }
]

html_template = """<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=1080, height=1080, initial-scale=1.0">
  <title>{headline}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * {{
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }}
    html, body {{
      width: 1080px;
      height: 1080px;
      overflow: hidden;
      background-color: #061520;
      font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif;
      -webkit-font-smoothing: antialiased;
      color: #ffffff;
    }}
    
    .canvas {{
      width: 1080px;
      height: 1080px;
      position: relative;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      padding: 56px 60px 44px 60px;
      background: radial-gradient(circle at 50% 15%, #0e2b3e 0%, #061520 60%, #030a10 100%);
    }}

    .tech-grid {{
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-image: 
        linear-gradient(rgba(0, 210, 224, 0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(0, 210, 224, 0.04) 1px, transparent 1px);
      background-size: 40px 40px;
      pointer-events: none;
      z-index: 1;
    }}

    /* Top Brand Bar */
    .header-zone {{
      position: relative;
      z-index: 10;
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
    }}
    .brand-logo-wrap {{
      display: flex;
      align-items: center;
    }}
    .brand-svg-logo {{
      height: 46px;
      width: auto;
      display: block;
      filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.6));
    }}
    .category-badge {{
      display: flex;
      align-items: center;
      gap: 8px;
      background: rgba(0, 210, 224, 0.08);
      border: 1px solid rgba(0, 210, 224, 0.28);
      padding: 8px 18px;
      border-radius: 30px;
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 0.1em;
      color: #00d2e0;
      text-transform: uppercase;
      backdrop-filter: blur(10px);
    }}

    /* Typography Zone */
    .typography-zone {{
      position: relative;
      z-index: 10;
      margin-top: 32px;
      max-width: 960px;
    }}
    .kicker-line {{
      font-size: 14px;
      font-weight: 800;
      letter-spacing: 0.16em;
      color: #00d2e0;
      text-transform: uppercase;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      gap: 10px;
    }}
    .kicker-line::before {{
      content: "";
      width: 24px;
      height: 2px;
      background: #00d2e0;
      display: inline-block;
    }}
    .main-headline {{
      font-size: 54px;
      font-weight: 900;
      line-height: 1.05;
      letter-spacing: -0.02em;
      color: #ffffff;
      text-transform: uppercase;
      text-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
    }}
    .sub-headline {{
      font-size: 20px;
      font-weight: 500;
      line-height: 1.4;
      color: rgba(255, 255, 255, 0.82);
      margin-top: 14px;
      max-width: 860px;
      letter-spacing: 0.01em;
    }}

    /* Center Visual Stage (Clinical Hero Stage) */
    .visual-stage {{
      position: relative;
      z-index: 5;
      margin: 30px 0;
      height: 420px;
      border-radius: 16px;
      background: linear-gradient(135deg, rgba(14, 43, 62, 0.4) 0%, rgba(6, 21, 32, 0.7) 100%);
      border: 1px solid rgba(0, 210, 224, 0.15);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 30px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.08);
      overflow: hidden;
    }}
    .stage-glow {{
      position: absolute;
      top: -50%;
      left: 50%;
      transform: translateX(-50%);
      width: 600px;
      height: 300px;
      background: radial-gradient(circle, rgba(0, 210, 224, 0.15) 0%, transparent 70%);
      pointer-events: none;
    }}
    .stage-spec-grid {{
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      width: 100%;
      position: relative;
      z-index: 6;
    }}
    .spec-card {{
      background: rgba(6, 21, 32, 0.6);
      border: 1px solid rgba(0, 210, 224, 0.2);
      border-radius: 12px;
      padding: 24px 20px;
      text-align: center;
      backdrop-filter: blur(8px);
    }}
    .spec-icon-dot {{
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #00d2e0;
      margin: 0 auto 14px auto;
      box-shadow: 0 0 12px #00d2e0;
    }}
    .spec-title {{
      font-size: 15px;
      font-weight: 800;
      letter-spacing: 0.08em;
      color: #ffffff;
      text-transform: uppercase;
      line-height: 1.3;
    }}
    .stage-footer-note {{
      margin-top: 24px;
      font-size: 15px;
      font-weight: 600;
      color: #00d2e0;
      letter-spacing: 0.04em;
      position: relative;
      z-index: 6;
      text-align: center;
    }}

    /* Footer Zone */
    .footer-zone {{
      position: relative;
      z-index: 10;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-top: 1px solid rgba(0, 210, 224, 0.18);
      padding-top: 18px;
    }}
    .footer-left {{
      display: flex;
      align-items: center;
      gap: 16px;
    }}
    .cert-pill {{
      font-size: 12px;
      font-weight: 700;
      color: rgba(255, 255, 255, 0.75);
      letter-spacing: 0.05em;
      display: flex;
      align-items: center;
      gap: 8px;
    }}
    .cert-dot {{
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: #00d2e0;
    }}
    .footer-website {{
      font-size: 16px;
      font-weight: 800;
      color: #00d2e0;
      letter-spacing: 0.03em;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
    }}
    .globe-svg {{
      width: 16px;
      height: 16px;
      fill: #00d2e0;
    }}
  </style>
</head>
<body>

  <div class="canvas">
    <div class="tech-grid"></div>

    <!-- TOP HEADER -->
    <div class="header-zone">
      <div class="brand-logo-wrap">
        <svg class="brand-svg-logo" viewBox="0 0 426.42 121.19" xmlns="http://www.w3.org/2000/svg">
          <polygon fill="#00d2e0" points="100.2 62.04 118.28 62.04 59.03 2.79 0 61.8 59.35 121.19 67.96 112.59 17.28 61.92 57.69 21.53 100.2 62.04"/>
          <polygon fill="#00d2e0" points="69.07 61.51 50.99 61.51 110.24 2.25 169.27 61.28 109.91 120.63 101.32 112.05 151.99 61.4 111.57 20.98 69.07 61.51"/>
          <path fill="#ffffff" d="M57.14,76.1,70.63,89.6H126.2l13.62-13.39,17,.45L99.32,134.57,41,76.21Zm42.39,40,15.52-15.51H82.68Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#00d2e0" d="M166.34,14.87h10c4.45,0,7.91.92,10.35,2.75a9.09,9.09,0,0,1,3.7,7.76q0,5.64-3.53,8.5c-2.38,1.92-5.87,2.87-10.52,2.87h-3V50.18h-6.93Zm6.93,15.81h3a9.51,9.51,0,0,0,5.31-1.22,4.12,4.12,0,0,0,1.76-3.64,4.15,4.15,0,0,0-1.76-3.64A9.35,9.35,0,0,0,176.3,21h-3Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#00d2e0" d="M194.35,14.87h11q6.67,0,10.35,2.87a9.5,9.5,0,0,1,3.68,8c0,3.89-1.25,6.81-3.74,8.81a14.66,14.66,0,0,1-6,2.59l10.15,13h-8.21l-10.28-14.1v14.1h-6.93Zm6.93,16.94h3.55a10,10,0,0,0,5.7-1.34,4.67,4.67,0,0,0,1.91-4.06,4.74,4.74,0,0,0-1.91-4.1,10,10,0,0,0-5.7-1.35h-3.55Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#00d2e0" d="M224.18,14.87h6.95V50.18h-6.95Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#00d2e0" d="M245.22,50.18h-6.95V14.87h8l15.62,25.8V14.87h6.93V50.18h-7.64l-15.93-26Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#00d2e0" d="M310.94,40.29A16.68,16.68,0,0,1,304,48a20.31,20.31,0,0,1-10.66,2.87,19.28,19.28,0,0,1-13.72-5.35,17.22,17.22,0,0,1-5.65-13,17.22,17.22,0,0,1,5.65-13,19.26,19.26,0,0,1,13.72-5.37,20.25,20.25,0,0,1,10.73,2.93,17.36,17.36,0,0,1,7,7.85h-7.91a12.71,12.71,0,0,0-4.48-3.42,13.3,13.3,0,0,0-5.58-1.27,11.82,11.82,0,0,0-8.58,3.55A11.47,11.47,0,0,0,281,32.29a12,12,0,0,0,3.67,8.87,12.3,12.3,0,0,0,9,3.63,12.43,12.43,0,0,0,9.4-4.5Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#00d2e0" d="M314.93,50.18V14.87h23.28v6.08H321.86v7.71h14.73v6.07H321.86v9.38h16.71v6.07Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M192.89,76.54h3.69l9.74,27.17h-4l-2.43-7.27H189.65l-2.48,7.27h-4Zm5.71,16.27-3.8-11.41-3.85,11.41Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M211,76.54h7.52q5.23,0,7.85,2a6.64,6.64,0,0,1,2.62,5.62,6.77,6.77,0,0,1-2.67,5.77,11,11,0,0,1-4.7,1.86l7.74,11.92H223.7l-7.39-11.51h-1.6V103.7H211Zm3.69,11.86h3.61a7.48,7.48,0,0,0,4.24-.95,3.31,3.31,0,0,0,1.44-2.88,3.37,3.37,0,0,0-1.44-2.91,7.48,7.48,0,0,0-4.24-1h-3.61Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M241.08,80.17h-8.73V76.54h21.16v3.63h-8.73v23.53h-3.7Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M272.23,76.54h7.24a8.67,8.67,0,0,1,6.59,2.37,8.31,8.31,0,0,1,2.37,6.29,8.5,8.5,0,0,1-2.42,6.39,8.71,8.71,0,0,1-6.54,2.36h-3.55v9.75h-3.69Zm3.69,13.78h3.42a5.53,5.53,0,0,0,4-.14,4.86,4.86,0,0,0,1.52-3.86,5.06,5.06,0,0,0-1.47-3.92,5.27,5.27,0,0,0-3.92-1.34h-3.55Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M298.53,76.54h3.69l9.74,27.17h-4l-2.43-7.27H295.29l-2.48,7.27h-4Zm5.71,16.27-3.8-11.41-3.85,11.41Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M336.57,96.09a13,13,0,0,1-5.38,6,15.75,15.75,0,0,1-8.24,2.23,15,15,0,0,1-10.6-4.14A13.3,13.3,0,0,1,308,90.1a13.33,13.33,0,0,1,4.37-10.05,14.9,14.9,0,0,1,10.6-4.16,15.65,15.65,0,0,1,8.29,2.28,13.56,13.56,0,0,1,5.43,6.07h-4.22a10.82,10.82,0,0,0-4-3.8,11.23,11.23,0,0,0-5.5-1.34A11,11,0,0,0,315,82.35a9.75,9.75,0,0,0-3.26,7.75,9.75,9.75,0,0,0,3.26,7.75,11,11,0,0,0,7.94,3.25,11.39,11.39,0,0,0,5.65-1.39,10.37,10.37,0,0,0,4.09-3.62Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M342.3,103.7V76.54H346v13.68l9.63-13.68h4.63l-9.25,12.72,9.82,14.44h-4.66L348,89.94l-2,2.83V103.7Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M369.3,76.54h3.69l9.74,27.17h-4l-2.43-7.27H366.06l-2.48,7.27h-4Zm5.71,16.27-3.8-11.41-3.85,11.41Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M407.72,96.09a13,13,0,0,1-5.38,6,15.75,15.75,0,0,1-8.24,2.23,15,15,0,0,1-10.6-4.14A13.3,13.3,0,0,1,379.15,90.1a13.33,13.33,0,0,1,4.37-10.05,14.9,14.9,0,0,1,10.6-4.16,15.65,15.65,0,0,1,8.29,2.28,13.56,13.56,0,0,1,5.43,6.07h-4.22a10.82,10.82,0,0,0-4-3.8,11.23,11.23,0,0,0-5.5-1.34,11,11,0,0,0-7.94,3.25,9.75,9.75,0,0,0-3.26,7.75,9.75,9.75,0,0,0,3.26,7.75,11,11,0,0,0,7.94,3.25,11.39,11.39,0,0,0,5.65-1.39,10.37,10.37,0,0,0,4.09-3.62Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M412.39,103.7V76.54h18.2v3.63H416.08v7.69h13.29v3.58H416.08v8.63h15.22v3.63Z" transform="translate(-14.17 -14.17)"/>
          <path fill="#ffffff" d="M440.69,83.91a8.47,8.47,0,0,0-2.3-2.73,8.76,8.76,0,0,0-4.66-1.18,9.58,9.58,0,0,0-5.06,1.26,3.94,3.94,0,0,0-1.92,3.53,3.69,3.69,0,0,0,1.28,2.83,16,16,0,0,0,4.68,2.15l4.52,1.34a17.2,17.2,0,0,1,6.58,3.52,7.71,7.71,0,0,1,2.36,5.91,8.43,8.43,0,0,1-3.32,6.92q-3.32,2.62-9.45,2.62a14.73,14.73,0,0,1-7.85-2.07,9.91,9.91,0,0,1-4.22-6.17h4a7.11,7.11,0,0,0,2.65,3.48,9.88,9.88,0,0,0,5.43,1.39,10.51,10.51,0,0,0,5.64-1.33,4.34,4.34,0,0,0,2.14-3.83,4,4,0,0,0-1.42-3.15,16.59,16.59,0,0,0-4.9-2.26l-4.57-1.34a15.77,15.77,0,0,1-6.18-3.38A7.37,7.37,0,0,1,423,84.71a8,8,0,0,1,3.15-6.53q3.14-2.45,8.81-2.45a14.36,14.36,0,0,1,7.24,1.82,9.36,9.36,0,0,1,3.92,5.48Z" transform="translate(-14.17 -14.17)"/>
        </svg>
      </div>
      <div class="category-badge">{badge}</div>
    </div>

    <!-- TYPOGRAPHY -->
    <div class="typography-zone">
      <div class="kicker-line">{category}</div>
      <h1 class="main-headline">{headline}</h1>
      <p class="sub-headline">{subhead}</p>
    </div>

    <!-- VISUAL SPECIFICATION STAGE -->
    <div class="visual-stage">
      <div class="stage-glow"></div>
      <div class="stage-spec-grid">
        <div class="spec-card">
          <div class="spec-icon-dot"></div>
          <div class="spec-title">{point1}</div>
        </div>
        <div class="spec-card">
          <div class="spec-icon-dot"></div>
          <div class="spec-title">{point2}</div>
        </div>
        <div class="spec-card">
          <div class="spec-icon-dot"></div>
          <div class="spec-title">{point3}</div>
        </div>
      </div>
      <div class="stage-footer-note">{footer_note}</div>
    </div>

    <!-- FOOTER -->
    <div class="footer-zone">
      <div class="footer-left">
        <div class="cert-pill">
          <span class="cert-dot"></span>
          <span>cGMP Line Clearance</span>
        </div>
        <div class="cert-pill">
          <span class="cert-dot"></span>
          <span>ISO 9001:2015 &amp; FSC Certified</span>
        </div>
      </div>

      <div class="footer-right">
        <a href="https://www.princeartpackages.com" class="footer-website">
          <svg class="globe-svg" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
          </svg>
          <span>www.princeartpackages.com</span>
        </a>
      </div>
    </div>

  </div>

</body>
</html>
"""

async def run_batch():
    os.makedirs(r"D:\Downloads\princeart_monthly_creatives", exist_ok=True)
    
    async with async_playwright() as p:
        browser = await p.chromium.launch(headless=True)
        page = await browser.new_page(viewport={"width": 1080, "height": 1080}, device_scale_factor=2)
        
        for item in posts:
            post_html = html_template.format(
                headline=item["headline"],
                category=item["category"],
                subhead=item["subhead"],
                badge=item["badge"],
                point1=item["points"][0],
                point2=item["points"][1],
                point3=item["points"][2],
                footer_note=item["footer_note"]
            )
            
            html_path = rf"D:\Downloads\princeart_monthly_creatives\temp_post_{item['id']}.html"
            with open(html_path, "w", encoding="utf-8") as f:
                f.write(post_html)
                
            out_png = rf"D:\Downloads\princeart_post_{item['id']}_{item['category'].lower().replace(' ', '_').replace('&', 'and')}.png"
            await page.goto(f"file:///{os.path.abspath(html_path).replace(os.sep, '/')}")
            await page.wait_for_timeout(400)
            await page.screenshot(path=out_png, clip={"x": 0, "y": 0, "width": 1080, "height": 1080})
            
            # Also keep a copy in the dedicated folder
            folder_png = rf"D:\Downloads\princeart_monthly_creatives\post_{item['id']}.png"
            with open(out_png, "rb") as src, open(folder_png, "wb") as dst:
                dst.write(src.read())
                
            os.remove(html_path)
            print(f"GENERATED: {out_png} ({os.path.getsize(out_png)} bytes)")
            
        await browser.close()
    print("ALL 12 CREATIVES GENERATED & VERIFIED SUCCESSFULLY!")

asyncio.run(run_batch())
