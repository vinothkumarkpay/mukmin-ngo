/* Welfare NGO - Main JavaScript */
(function () {
    'use strict';

    /* ---- HERO SLIDER ---- */
    var slides = document.querySelectorAll('.slide');
    var dots   = document.querySelectorAll('#slider-dots .dot');
    var current = 0;
    var autoplay;

    function goToSlide(n) {
        slides[current].classList.remove('active');
        dots[current] && dots[current].classList.remove('active');
        current = (n + slides.length) % slides.length;
        slides[current].classList.add('active');
        dots[current] && dots[current].classList.add('active');
    }

    function startAutoplay() {
        autoplay = setInterval(function () { goToSlide(current + 1); }, 5000);
    }

    if (slides.length) {
        var prev = document.getElementById('slider-prev');
        var next = document.getElementById('slider-next');
        if (prev) prev.addEventListener('click', function () { clearInterval(autoplay); goToSlide(current - 1); startAutoplay(); });
        if (next) next.addEventListener('click', function () { clearInterval(autoplay); goToSlide(current + 1); startAutoplay(); });
        dots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                clearInterval(autoplay);
                goToSlide(parseInt(this.dataset.index));
                startAutoplay();
            });
        });
        startAutoplay();
    }

    /* ---- TESTIMONIALS SLIDER ---- */
    var tItems = document.querySelectorAll('.testimonial-item');
    var tDots  = document.querySelectorAll('#testimonial-dots .dot');
    var tCurrent = 0;

    function goToTestimonial(n) {
        tItems[tCurrent].classList.remove('active');
        tDots[tCurrent] && tDots[tCurrent].classList.remove('active');
        tCurrent = (n + tItems.length) % tItems.length;
        tItems[tCurrent].classList.add('active');
        tDots[tCurrent] && tDots[tCurrent].classList.add('active');
    }

    if (tItems.length) {
        tDots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                goToTestimonial(parseInt(this.dataset.index));
            });
        });
        setInterval(function () { goToTestimonial(tCurrent + 1); }, 6000);
    }

    /* ---- MOBILE MENU ---- */
    var mobileToggle = document.getElementById('mobile-toggle');
    var mainNav      = document.getElementById('main-nav');
    if (mobileToggle && mainNav) {
        mobileToggle.addEventListener('click', function () {
            mainNav.classList.toggle('open');
            var icon = mobileToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });
    }

    /* ---- INLINE SEARCH TOGGLE ---- */
    var searchIconBtn = document.getElementById('search-icon-btn');
    var searchBoxInline = document.getElementById('search-box-inline');
    var searchClearBtn = document.getElementById('search-clear-btn');
    var searchInputInline = document.getElementById('search-input-inline');

    if (searchIconBtn && searchBoxInline) {
        searchIconBtn.addEventListener('click', function () {
            searchBoxInline.classList.toggle('open');
            if (searchBoxInline.classList.contains('open')) {
                searchInputInline && searchInputInline.focus();
            }
        });
    }

    if (searchClearBtn && searchBoxInline) {
        searchClearBtn.addEventListener('click', function () {
            if (searchInputInline && searchInputInline.value !== '') {
                searchInputInline.value = '';
                searchInputInline.focus();
            } else {
                searchBoxInline.classList.remove('open');
            }
        });
    }

    /* ---- STICKY HEADER ---- */
    var header = document.getElementById('site-header');
    if (header) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 50) {
                header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.12)';
            } else {
                header.style.boxShadow = '0 2px 8px rgba(0,0,0,0.07)';
            }
        });
    }

    /* ---- COUNTER ANIMATION ---- */
    function animateCounters() {
        var counters = document.querySelectorAll('.stat-number');
        counters.forEach(function (counter) {
            var target = parseInt(counter.getAttribute('data-count'));
            var start  = 0;
            var duration = 2000;
            var step = target / (duration / 16);
            var timer = setInterval(function () {
                start += step;
                if (start >= target) {
                    counter.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(start).toLocaleString();
                }
            }, 16);
        });
    }

    /* Trigger counters when stats section is in view */
    var statsSection = document.querySelector('.section-stats');
    if (statsSection) {
        var triggered = false;
        window.addEventListener('scroll', function () {
            if (!triggered) {
                var rect = statsSection.getBoundingClientRect();
                if (rect.top < window.innerHeight - 100) {
                    triggered = true;
                    animateCounters();
                }
            }
        });
    }

    /* ---- SMOOTH SCROLL for anchor links ---- */
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    /* ---- PROGRESS BARS ANIMATION ---- */
    function animateProgressBars() {
        document.querySelectorAll('.progress-fill').forEach(function (bar) {
            var width = bar.style.width;
            bar.style.width = '0';
            setTimeout(function () { bar.style.width = width; }, 300);
        });
    }

    var campaignsSection = document.querySelector('.section-campaigns');
    if (campaignsSection) {
        var pbTriggered = false;
        window.addEventListener('scroll', function () {
            if (!pbTriggered) {
                var rect = campaignsSection.getBoundingClientRect();
                if (rect.top < window.innerHeight) {
                    pbTriggered = true;
                    animateProgressBars();
                }
            }
        });
    }

})();
