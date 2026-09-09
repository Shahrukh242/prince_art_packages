/**
 * Prince Art Packages — site.js
 * Rebuilt for a standard multi-page PHP site (no client-side routing).
 * Every link and button now navigates normally; this file only handles
 * the mobile nav toggle and a small scroll-reveal animation.
 */

document.addEventListener('DOMContentLoaded', () => {
  setupMobileNav();
  setupCapAnimations();
  setupProductSlider();
});

function setupMobileNav() {
  const mobileNavBtn = document.getElementById('btn-mobile-nav');
  const navLinks = document.querySelector('.nav-links');
  const backdrop = document.getElementById('mobileNavBackdrop');
  if (!mobileNavBtn || !navLinks) return;

  function openMenu() {
    navLinks.classList.add('mobile-open');
    mobileNavBtn.classList.add('is-active');
    mobileNavBtn.setAttribute('aria-expanded', 'true');
    mobileNavBtn.innerHTML = '<i class="ri-close-line"></i>';
    if (backdrop) backdrop.classList.add('is-visible');
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    navLinks.classList.remove('mobile-open');
    mobileNavBtn.classList.remove('is-active');
    mobileNavBtn.setAttribute('aria-expanded', 'false');
    mobileNavBtn.innerHTML = '<i class="ri-menu-3-line"></i>';
    if (backdrop) backdrop.classList.remove('is-visible');
    document.body.style.overflow = '';
  }

  mobileNavBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    if (navLinks.classList.contains('mobile-open')) {
      closeMenu();
    } else {
      openMenu();
    }
  });

  // Close when tapping backdrop
  if (backdrop) {
    backdrop.addEventListener('click', closeMenu);
  }

  // Close when clicking any nav link
  navLinks.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', closeMenu);
  });

  // Close when pressing Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && navLinks.classList.contains('mobile-open')) {
      closeMenu();
    }
  });

  // Close when clicking outside header or backdrop
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.main-header') && !e.target.closest('.mobile-nav-backdrop')) {
      closeMenu();
    }
  });

  // Close on resize above mobile breakpoint
  window.addEventListener('resize', () => {
    if (window.innerWidth > 768 && navLinks.classList.contains('mobile-open')) {
      closeMenu();
    }
  }, { passive: true });
}

// Capabilities page stat reveal animation — optimized for 60fps scrolling
function setupCapAnimations() {
  const statNumbers = document.querySelectorAll('.cap-stat-number, .cap-infra-row');
  if (!statNumbers.length) return;

  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        obs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.05, rootMargin: '0px 0px -30px 0px' });

  statNumbers.forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(15px)';
    el.style.transition = 'opacity 0.35s ease-out, transform 0.35s ease-out';
    observer.observe(el);
  });
}

// Product Carousel / Slider for Homepage 6 Pharmaceutical Packaging Solutions
function setupProductSlider() {
  const track = document.getElementById('productSliderTrack');
  const prevBtn = document.getElementById('prodSliderPrev');
  const nextBtn = document.getElementById('prodSliderNext');
  const dotsContainer = document.getElementById('productSliderDots');
  if (!track || !prevBtn || !nextBtn) return;

  const slides = track.querySelectorAll('.product-slide-card');
  if (!slides.length) return;

  function getSlideStep() {
    const card = slides[0];
    const gap = parseFloat(window.getComputedStyle(track).gap) || 28;
    return card.offsetWidth + gap;
  }

  function updateDots() {
    if (!dotsContainer) return;
    const dots = dotsContainer.querySelectorAll('.slider-dot');
    const step = getSlideStep();
    const activeIndex = Math.min(
      Math.max(0, Math.round(track.scrollLeft / step)),
      dots.length - 1
    );
    dots.forEach((dot, idx) => {
      dot.classList.toggle('active', idx === activeIndex);
    });
  }

  prevBtn.addEventListener('click', () => {
    track.scrollBy({ left: -getSlideStep(), behavior: 'smooth' });
  });

  nextBtn.addEventListener('click', () => {
    track.scrollBy({ left: getSlideStep(), behavior: 'smooth' });
  });

  if (dotsContainer) {
    const dots = dotsContainer.querySelectorAll('.slider-dot');
    dots.forEach((dot, idx) => {
      dot.addEventListener('click', () => {
        track.scrollTo({ left: idx * getSlideStep(), behavior: 'smooth' });
      });
    });
  }

  let scrollTimeout;
  track.addEventListener('scroll', () => {
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(updateDots, 60);
  }, { passive: true });
}
