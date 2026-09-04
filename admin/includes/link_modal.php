<?php
/**
 * admin/includes/link_modal.php — Universal Link Inserter Modal & Toolbar Helpers
 * Provides interactive page linking to Products, Core Pages, Blog Articles, and Custom URLs.
 */
$modalPdo = get_db();
$modalPages = $modalPdo->query("SELECT slug, title FROM pages WHERE slug NOT IN ('global') ORDER BY title ASC")->fetchAll();
$modalProducts = $modalPdo->query("SELECT slug, name FROM products ORDER BY name ASC")->fetchAll();
$modalBlogs = $modalPdo->query("SELECT slug, title FROM blog_posts ORDER BY title ASC")->fetchAll();
?>

<!-- ====================================================================
     MODAL: INSERT PAGE / PRODUCT LINK
===================================================================== -->
<div id="insertLinkModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(10,37,64,0.7); z-index:999999; backdrop-filter:blur(4px); align-items:center; justify-content:center; padding:1.5rem;">
  <div style="background:#ffffff; border-radius:12px; max-width:560px; width:100%; box-shadow:0 20px 50px rgba(0,0,0,0.3); overflow:hidden; animation:modalPop 0.2s ease-out;">
    
    <!-- Modal Header -->
    <div style="background:linear-gradient(135deg, #0b2545 0%, #173b6c 100%); color:#ffffff; padding:1.2rem 1.5rem; display:flex; align-items:center; justify-content:space-between; border-bottom:3px solid #00a896;">
      <div style="display:flex; align-items:center; gap:0.6rem;">
        <i class="ri-link text-teal" style="font-size:1.4rem; color:#00a896;"></i>
        <div>
          <h3 style="margin:0; font-size:1.15rem; color:#ffffff; font-weight:700;">Link Text to Page / Product</h3>
          <span style="font-size:0.78rem; color:rgba(255,255,255,0.8);">Connect any word or phrase to internal pages or blogs</span>
        </div>
      </div>
      <button type="button" onclick="closeLinkModal()" style="background:rgba(255,255,255,0.15); border:none; color:#ffffff; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1.1rem;">
        <i class="ri-close-line"></i>
      </button>
    </div>

    <!-- Modal Form Body -->
    <div style="padding:1.5rem;">
      
      <!-- Field 1: Anchor Text -->
      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:#0b2545; margin-bottom:0.35rem;">
          Display / Anchor Text <span style="color:#ef4444;">*</span>
        </label>
        <input type="text" id="linkAnchorText" placeholder="e.g. pharmaceutical packaging solutions" style="width:100%; padding:0.65rem 0.85rem; border:1.5px solid #cbd5e1; border-radius:6px; font-size:0.92rem;" oninput="updateLinkPreview()">
        <span style="font-size:0.75rem; color:#64748b; margin-top:0.25rem; display:block;">This is the visible text that users will click on.</span>
      </div>

      <!-- Field 2: Target Destination Dropdown -->
      <div style="margin-bottom:1rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:#0b2545; margin-bottom:0.35rem;">
          Select Destination Page / Product <span style="color:#ef4444;">*</span>
        </label>
        <select id="linkDestinationSelect" style="width:100%; padding:0.65rem 0.85rem; border:1.5px solid #cbd5e1; border-radius:6px; font-size:0.92rem; background:#fff;" onchange="handleDestChange()">
          <optgroup label="📦 Secondary Packaging Products">
            <option value="products.php">All Products Overview (products.php)</option>
            <?php foreach ($modalProducts as $pr): ?>
              <option value="product-detail.php?slug=<?= h($pr['slug']) ?>"><?= h($pr['name']) ?> (product-detail.php?slug=<?= h($pr['slug']) ?>)</option>
            <?php endforeach; ?>
          </optgroup>

          <optgroup label="🏢 Core Company Pages">
            <option value="about.php">About Us / Company Profile (about.php)</option>
            <option value="quality.php">Quality Assurance &amp; cGMP (quality.php)</option>
            <option value="capabilities.php">Manufacturing Capabilities (capabilities.php)</option>
            <option value="innovation.php">Innovation &amp; Anti-Counterfeit (innovation.php)</option>
            <option value="industries.php">Regulated Industries (industries.php)</option>
            <option value="contact.php">Contact Us / Plant Units (contact.php)</option>
            <option value="blog.php">Technical Blog Hub (blog.php)</option>
          </optgroup>

          <optgroup label="📝 Technical Blog Articles">
            <?php foreach ($modalBlogs as $bl): ?>
              <option value="blog-post.php?slug=<?= h($bl['slug']) ?>"><?= h($bl['title']) ?></option>
            <?php endforeach; ?>
          </optgroup>

          <optgroup label="⚡ Custom / External URL">
            <option value="custom">Enter Custom URL or Anchor (#section)...</option>
          </optgroup>
        </select>
      </div>

      <!-- Field 3: Custom URL input (hidden by default) -->
      <div id="customUrlWrapper" style="display:none; margin-bottom:1rem;">
        <label style="display:block; font-size:0.85rem; font-weight:700; color:#0b2545; margin-bottom:0.35rem;">
          Custom URL / Anchor
        </label>
        <input type="text" id="customUrlInput" placeholder="https://example.com or #section-id" style="width:100%; padding:0.65rem 0.85rem; border:1.5px solid #cbd5e1; border-radius:6px; font-size:0.92rem;" oninput="updateLinkPreview()">
      </div>

      <!-- Field 4: Visual Style -->
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:#0b2545; margin-bottom:0.35rem;">
            Link Style
          </label>
          <select id="linkStyleSelect" style="width:100%; padding:0.65rem 0.85rem; border:1.5px solid #cbd5e1; border-radius:6px; font-size:0.88rem;" onchange="updateLinkPreview()">
            <option value="text-link">Inline Styled Link (Teal Underline)</option>
            <option value="btn btn-gold btn-sm">Gold Action Button</option>
            <option value="btn btn-teal btn-sm">Teal Action Button</option>
            <option value="btn btn-outline-navy btn-sm">Outline Navy Button</option>
          </select>
        </div>

        <div>
          <label style="display:block; font-size:0.85rem; font-weight:700; color:#0b2545; margin-bottom:0.35rem;">
            Target Window
          </label>
          <select id="linkTargetSelect" style="width:100%; padding:0.65rem 0.85rem; border:1.5px solid #cbd5e1; border-radius:6px; font-size:0.88rem;" onchange="updateLinkPreview()">
            <option value="_self">Same Tab (Standard)</option>
            <option value="_blank">New Tab (_blank)</option>
          </select>
        </div>
      </div>

      <!-- Code Preview Box -->
      <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:0.75rem 1rem; margin-bottom:1.25rem;">
        <span style="display:block; font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; margin-bottom:0.25rem;">Generated HTML Preview:</span>
        <code id="linkHtmlPreview" style="font-size:0.82rem; color:#0369a1; word-break:break-all;">&lt;a href="products.php" class="text-link"&gt;pharmaceutical packaging solutions&lt;/a&gt;</code>
      </div>

      <!-- Modal Footer Action Buttons -->
      <div style="display:flex; justify-content:flex-end; gap:0.75rem; border-top:1px solid #e2e8f0; padding-top:1rem;">
        <button type="button" onclick="closeLinkModal()" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; padding:0.55rem 1.1rem; border-radius:6px; font-size:0.88rem; font-weight:600; cursor:pointer;">
          Cancel
        </button>
        <button type="button" onclick="confirmInsertLink()" style="background:#00a896; color:#ffffff; border:none; padding:0.55rem 1.35rem; border-radius:6px; font-size:0.88rem; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:0.4rem; box-shadow:0 4px 12px rgba(0,168,150,0.3);">
          <i class="ri-check-line"></i> Insert Link into Content
        </button>
      </div>

    </div>

  </div>
</div>

<style>
@keyframes modalPop {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}
</style>

<script>
var activeTextareaId = null;
var activeSelectionStart = 0;
var activeSelectionEnd = 0;

function openLinkModal(targetTextarea) {
  var textarea = null;
  if (typeof targetTextarea === 'string') {
    textarea = document.getElementById(targetTextarea);
  } else if (targetTextarea && targetTextarea.tagName === 'TEXTAREA') {
    textarea = targetTextarea;
  } else if (targetTextarea) {
    var form = targetTextarea.closest('form') || targetTextarea.closest('.panel');
    if (form) textarea = form.querySelector('textarea');
  }

  if (!textarea) {
    textarea = document.querySelector('textarea');
  }

  if (textarea) {
    activeTextareaId = textarea.id || (textarea.name ? 'txt_' + textarea.name : 'temp_txt_' + Math.random().toString(36).substr(2, 5));
    textarea.id = activeTextareaId;
    activeSelectionStart = textarea.selectionStart || 0;
    activeSelectionEnd = textarea.selectionEnd || 0;

    var selectedText = textarea.value.substring(activeSelectionStart, activeSelectionEnd).trim();
    var anchorInput = document.getElementById('linkAnchorText');
    if (anchorInput) {
      anchorInput.value = selectedText || 'pharmaceutical packaging solutions';
    }
  }

  updateLinkPreview();
  var modal = document.getElementById('insertLinkModal');
  if (modal) modal.style.display = 'flex';
}

function closeLinkModal() {
  var modal = document.getElementById('insertLinkModal');
  if (modal) modal.style.display = 'none';
}

function handleDestChange() {
  var select = document.getElementById('linkDestinationSelect');
  var customWrap = document.getElementById('customUrlWrapper');
  if (select && customWrap) {
    customWrap.style.display = (select.value === 'custom') ? 'block' : 'none';
  }
  updateLinkPreview();
}

function getSelectedUrl() {
  var select = document.getElementById('linkDestinationSelect');
  if (!select) return 'products.php';
  if (select.value === 'custom') {
    var customInput = document.getElementById('customUrlInput');
    return (customInput && customInput.value.trim()) ? customInput.value.trim() : '#';
  }
  return select.value;
}

function updateLinkPreview() {
  var text = (document.getElementById('linkAnchorText').value || 'Link Text').trim();
  var url = getSelectedUrl();
  var style = document.getElementById('linkStyleSelect').value;
  var target = document.getElementById('linkTargetSelect').value;

  var targetAttr = (target === '_blank') ? ' target="_blank" rel="noopener"' : '';
  var html = '<a href="' + url + '" class="' + style + '"' + targetAttr + '>' + text + '</a>';
  
  var previewBox = document.getElementById('linkHtmlPreview');
  if (previewBox) {
    previewBox.textContent = html;
  }
}

function confirmInsertLink() {
  var text = (document.getElementById('linkAnchorText').value || 'Link Text').trim();
  var url = getSelectedUrl();
  var style = document.getElementById('linkStyleSelect').value;
  var target = document.getElementById('linkTargetSelect').value;
  var targetAttr = (target === '_blank') ? ' target="_blank" rel="noopener"' : '';
  var htmlToInsert = '<a href="' + url + '" class="' + style + '"' + targetAttr + '>' + text + '</a>';

  if (activeTextareaId) {
    var textarea = document.getElementById(activeTextareaId);
    if (textarea) {
      var val = textarea.value;
      var start = activeSelectionStart;
      var end = activeSelectionEnd;

      textarea.value = val.substring(0, start) + htmlToInsert + val.substring(end);
      textarea.focus();
      textarea.setSelectionRange(start + htmlToInsert.length, start + htmlToInsert.length);
      
      // Dispatch input event for any reactive listeners
      textarea.dispatchEvent(new Event('input', { bubbles: true }));
    }
  }

  closeLinkModal();
}

function formatSelection(textareaId, tag) {
  var textarea = (typeof textareaId === 'string') ? document.getElementById(textareaId) : textareaId;
  if (!textarea && typeof textareaId !== 'string') {
    var form = textareaId.closest('form') || textareaId.closest('.panel');
    if (form) textarea = form.querySelector('textarea');
  }
  if (!textarea) return;

  var start = textarea.selectionStart || 0;
  var end = textarea.selectionEnd || 0;
  var selectedText = textarea.value.substring(start, end) || 'text';
  var wrapped = '<' + tag + '>' + selectedText + '</' + tag + '>';

  textarea.value = textarea.value.substring(0, start) + wrapped + textarea.value.substring(end);
  textarea.focus();
  textarea.setSelectionRange(start + wrapped.length, start + wrapped.length);
  textarea.dispatchEvent(new Event('input', { bubbles: true }));
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeLinkModal();
  }
});
</script>
