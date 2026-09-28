<div class="guides-header">
  <div style="width: 100%;">
    <div style="display: flex; justify-content: center; margin-bottom: 2px;">
      <div style="display: inline-flex; align-items: center; background-color: #d6eaf6; border-radius: 50px; padding: 6px 12px;">
        <span style="font-size: 11px; font-weight: 800; color: #3e7ea3; letter-spacing: 2px; font-family: 'Nunito', sans-serif; line-height: 1;">COMMUNITY GUIDES</span>
      </div>
    </div>
    <div style="margin: 0; padding: 0; text-align: center;">
        <span style="font-size: 46px; font-weight: 700; color: #1e2d3d; font-family: 'Playfair Display', serif; line-height: 1.1;">Live like a </span><span style="font-size: 46px; font-weight: 550; font-style: italic; color: #4a90a4; font-family: 'Playfair Display', serif; line-height: 1.1;">local</span>
    </div>
  </div>
  <a href="/community-guides#our-community-guides" class="guides-view-all">View All</a>
</div>

<div class="guides-carousel-wrapper">
  <div class="guides-grid">

    <!-- Guide 1 -->
    <div class="guide-card">
      <img src="https://www.simplypadre.com/images/pexel-photo-32190153-large.jpeg" alt="Beach Access Guide">
      <div class="guide-overlay">
        <div class="guide-title">Community Guide 1</div>
        <div class="guide-hover-content">
          <p class="guide-excerpt">Discover the best beach access points on Padre Island.</p>
          <a href="/pdfs/beach-access-guide.pdf" target="_blank" class="guide-read-more">Read More</a>
        </div>
      </div>
      <a href="/pdfs/beach-access-guide.pdf" target="_blank" class="guide-full-link" aria-label="Community Guide 1"></a>
    </div>

    <!-- Guide 2 -->
    <div class="guide-card">
      <img src="https://www.simplypadre.com/images/Great-Sunrise-Padre-Island-Texas-Morning.webp" alt="Parking & Transportation">
      <div class="guide-overlay">
        <div class="guide-title">Community Guide 2</div>
        <div class="guide-hover-content">
          <p class="guide-excerpt">Everything you need to know about parking and transportation.</p>
          <a href="/pdfs/parking-guide.pdf" target="_blank" class="guide-read-more">Read More</a>
        </div>
      </div>
      <a href="/pdfs/parking-guide.pdf" target="_blank" class="guide-full-link" aria-label="Community Guide 2"></a>
    </div>

    <!-- Guide 3 -->
    <div class="guide-card">
      <img src="https://www.simplypadre.com/images/images.jpeg" alt="More Local Guides">
      <div class="guide-overlay">
        <div class="guide-title">Community Guide 3</div>
        <div class="guide-hover-content">
          <p class="guide-excerpt">Explore more local guides for Padre Island.</p>
          <a href="/community_guides" class="guide-read-more">Read More</a>
        </div>
      </div>
      <a href="/community_guides" class="guide-full-link" aria-label="Community Guide 3"></a>
    </div>

  </div>
</div>

<!-- Mobile: dots + view all below carousel -->
<div class="guides-mobile-footer">
  <div class="guides-dots" id="guidesDots"></div>
  <a href="/community-guides#our-community-guides" class="guides-view-all-mobile">View All</a>
</div>


/* Header row */
.guides-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1450px;
  margin: 0 auto 30px auto;
  padding: 0;
}

/* Section Title */
.guides-section-title {
  font-family: 'Nunito', serif;
  font-size: 36px;
  font-weight: 700;
  color: rgb(29, 64, 89);
  text-align: center;
  margin: 0;
  line-height: 1.2;
  flex: 1; /* takes up all available space */
}

/* Fake spacer - same width as the View All button to balance the title */
.guides-header::before {
  content: '';
  width: 90px; /* roughly the same width as the View All button */
  flex-shrink: 0;
}

/* View All button - desktop */
.guides-view-all {
  background-color: transparent;
  color: #20465d;
  border: 1.5px solid #20465d;
  padding: 6px 16px;
  font-size: 14px;
  font-weight: 400;
  border-radius: 50px;
  text-decoration: none !important;
  transition: all 0.3s ease, transform 0.3s ease;
  white-space: nowrap;
}

.guides-view-all:hover,
.guides-view-all:focus {
  background-color: #20465d;
  color: #fff !important;
  transform: scale(1.1);
}

.guides-carousel-wrapper {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 5px;
}

.guides-carousel-wrapper.carousel-active {
  overflow: hidden;
  padding: 0 2px;
  border-radius: 8px;
}

/* Grid - desktop */
.guides-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
  transition: transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

/* Carousel mode */
.guides-grid.carousel-mode {
  display: flex;
  flex-wrap: nowrap;
  gap: 16px;
  transform: translateX(0);
}

.guides-grid.carousel-mode .guide-card {
  flex-shrink: 0;
  border-radius: 8px;
  margin: 0;
}

/* Card */
.guide-card {
  position: relative;
  height: 240px;
  overflow: hidden;
  border-radius: 8px;
  background: #000;
  line-height: 0;
  font-size: 0;
}

/* Image Cover */
.guide-card img {
  position: absolute !important;
  top: 0;
  left: 0;
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
  object-position: center center !important;
  display: block !important;
  margin: 0 !important;
  padding: 0 !important;
  border: 0 !important;
  max-width: none !important;
  min-width: 100% !important;
  min-height: 100% !important;
  transform: translateZ(0);
  transition: transform 0.5s ease;
}

/* Hover Zoom */
.guide-card:hover img {
  transform: scale(1.08);
}

/* Overlay */
.guide-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 14px 16px;
  background: linear-gradient(
    rgba(62, 126, 163, 1) 0%,
    rgba(62, 126, 163, 0.6) 100%,
    rgba(62, 126, 163, 0.0) 100%
  );
  z-index: 2;
  transition: padding 0.5s ease;
  line-height: 1.4;
  font-size: 14px;
}

/* Title */
.guide-title {
  font-family: 'Nunito', serif;
  font-size: 22px;
  font-weight: 700;
  color: #fff;
  line-height: 1.3;
  margin: 0;
  position: relative;
  z-index: 2;
}

/* Hover content */
.guide-hover-content {
  max-height: 0;
  overflow: hidden;
  opacity: 0;
  transition: max-height 0.5s ease, opacity 0.4s ease;
}

.guide-card:hover .guide-hover-content {
  max-height: 120px;
  opacity: 1;
}

/* Excerpt */
.guide-excerpt {
  font-size: 14px;
  color: #fff;
  margin: 8px 0 10px 0;
  line-height: 1.4;
  text-align: left;
}

/* Read More button */
.guide-read-more {
  display: inline-block;
  background-color: #f9a03f !important;
  color: #fff !important;
  padding: 8px 14px;
  font-size: 14px;
  font-weight: 600;
  border-radius: 4px;
  border: none !important;
  box-shadow: none !important;
  text-decoration: none !important;
  transition: transform 0.3s ease;
  position: relative;
  z-index: 4;
  line-height: 1.4;
}

.guide-read-more:hover,
.guide-read-more:focus,
.guide-read-more:active {
  transform: scale(1.1);
  color: #fff !important;
  text-decoration: none !important;
}

/* Full card link */
.guide-full-link {
  position: absolute;
  inset: 0;
  z-index: 3;
  display: block;
  text-indent: -9999px;
}

/* Mobile footer - hidden on desktop */
.guides-mobile-footer {
  display: none;
}

/* =====================
   TABLET
   ===================== */
@media (min-width: 601px) and (max-width: 900px) {
  .guides-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .guides-section-title {
    font-size: 28px;
  }
}

/* =====================
   MOBILE
   ===================== */
@media (max-width: 600px) {

  .guides-view-all {
    display: none;
  }

  .guides-header {
    justify-content: center;
    padding: 0; 
  }
  .guides-header::before {
    display: none; /* remove the spacer on mobile since button is hidden */
  }

  .guides-section-title {
    text-align: center;
    flex: unset;
    width: 100%;
  }
  .guides-mobile-footer {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 22px;
    margin-top: 16px;
  }

  .guides-dots {
    display: flex;
    gap: 25px;
    justify-content: center;
  }

  .guide-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background-color: #ccc;
    cursor: pointer;
    transition: background-color 0.1s ease, transform 0.s ease;
    display: block;
  }

  .guide-dot.active {
    background-color: #20465d;
  }

  .guides-view-all-mobile {
    background-color: transparent;
    color: #20465d;
    border: 1.5px solid #20465d;
    padding: 6px 38px;
    font-size: 14px;
    font-weight: 400;
    border-radius: 50px;
    text-decoration: none !important;
    transition: all 0.3s ease;
    display: inline-block;
  }

  .guides-view-all-mobile:hover,
  .guides-view-all-mobile:focus {
    background-color: #20465d;
    color: #fff !important;
	transform: scale(1.1);
  }
}

<script>
(function() {
  function initGuidesCarousel() {
    var wrapper = document.querySelector('.guides-carousel-wrapper');
    var grid = document.querySelector('.guides-grid');
    var originalCards = Array.from(grid.querySelectorAll('.guide-card'));
    var dotsContainer = document.getElementById('guidesDots');
    var currentIndex = 1;
    var isCarousel = false;
    var startX = 0;
    var currentX = 0;
    var isDragging = false;
    var cardGap = 16;
    var wrapperPadding = 4;
    var totalCards = originalCards.length;

    function buildDots() {
      dotsContainer.innerHTML = '';
      originalCards.forEach(function(_, i) {
        var dot = document.createElement('span');
        dot.className = 'guide-dot' + (i === 0 ? ' active' : '');
        dot.addEventListener('click', function() { goTo(i + 1); });
        dotsContainer.appendChild(dot);
      });
    }

    function updateDots() {
      var dots = dotsContainer.querySelectorAll('.guide-dot');
      var realIndex = currentIndex - 1;
      dots.forEach(function(d, i) {
        d.classList.toggle('active', i === realIndex);
      });
    }

    function getCardWidth() {
      return wrapper.offsetWidth - wrapperPadding;
    }

    function setCardWidths() {
      var cardWidth = getCardWidth();
      Array.from(grid.querySelectorAll('.guide-card')).forEach(function(card) {
        card.style.minWidth = cardWidth + 'px';
        card.style.width = cardWidth + 'px';
      });
    }

    function getBaseOffset(index) {
      return index * (getCardWidth() + cardGap);
    }

    function goTo(index, animate) {
      if (animate === undefined) animate = true;
      currentIndex = index;
      grid.style.transition = animate
        ? 'transform 0.1s cubic-bezier(0.25, 0.46, 0.45, 0.94)'
        : 'none';
      grid.style.transform = 'translateX(-' + getBaseOffset(currentIndex) + 'px)';
      updateDots();
    }

    function addClones() {
      Array.from(grid.querySelectorAll('.guide-card-clone')).forEach(function(c) { c.remove(); });
      var firstClone = originalCards[totalCards - 1].cloneNode(true);
      firstClone.classList.add('guide-card-clone');
      grid.insertBefore(firstClone, grid.firstChild);
      var lastClone = originalCards[0].cloneNode(true);
      lastClone.classList.add('guide-card-clone');
      grid.appendChild(lastClone);
    }

    function onTouchStart(e) {
      isDragging = true;
      startX = e.touches[0].clientX;
      currentX = startX;
      grid.style.transition = 'none'; // disable transition while dragging
    }

    function onTouchMove(e) {
      if (!isDragging) return;
      currentX = e.touches[0].clientX;
      var diff = startX - currentX;
      var baseOffset = getBaseOffset(currentIndex);
      // Move grid in real time with finger
      grid.style.transform = 'translateX(-' + (baseOffset + diff) + 'px)';
    }

    function onTouchEnd(e) {
      if (!isDragging) return;
      isDragging = false;
      var diff = startX - currentX;

      if (Math.abs(diff) > 50) {
        // Swipe threshold met — go to next or prev
        goTo(diff > 0 ? currentIndex + 1 : currentIndex - 1);
      } else {
        // Not enough swipe — snap back to current card
        goTo(currentIndex);
      }
    }

    function enableCarousel() {
      if (isCarousel) return;
      isCarousel = true;
      wrapper.classList.add('carousel-active');
      grid.classList.add('carousel-mode');
      addClones();
      setCardWidths();
      buildDots();
      currentIndex = 1;
      goTo(currentIndex, false);

      grid.addEventListener('touchstart', onTouchStart, { passive: true });
      grid.addEventListener('touchmove', onTouchMove, { passive: true });
      grid.addEventListener('touchend', onTouchEnd, { passive: true });

      grid.addEventListener('transitionend', function() {
        if (currentIndex === 0) {
          goTo(totalCards, false);
          currentIndex = totalCards;
        } else if (currentIndex === totalCards + 1) {
          goTo(1, false);
          currentIndex = 1;
        }
        updateDots();
      });
    }

    function disableCarousel() {
      if (!isCarousel) return;
      isCarousel = false;
      wrapper.classList.remove('carousel-active');
      grid.classList.remove('carousel-mode');
      grid.style.transform = '';
      grid.style.transition = '';
      dotsContainer.innerHTML = '';
      currentIndex = 1;

      Array.from(grid.querySelectorAll('.guide-card-clone')).forEach(function(c) { c.remove(); });
      Array.from(grid.querySelectorAll('.guide-card')).forEach(function(card) {
        card.style.minWidth = '';
        card.style.width = '';
      });

      grid.removeEventListener('touchstart', onTouchStart);
      grid.removeEventListener('touchmove', onTouchMove);
      grid.removeEventListener('touchend', onTouchEnd);
    }

    function checkMode() {
      if (window.innerWidth <= 600) {
        enableCarousel();
      } else {
        disableCarousel();
      }
    }

    checkMode();
    window.addEventListener('resize', function() {
      checkMode();
      if (isCarousel) {
        setCardWidths();
        goTo(currentIndex, false);
      }
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGuidesCarousel);
  } else {
    initGuidesCarousel();
  }
})();</script>
