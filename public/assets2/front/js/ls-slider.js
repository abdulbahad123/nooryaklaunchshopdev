/**
 * ls-slider.js — LaunchShop lightweight slider engine
 * Supports: auto-play, prev/next arrows, dot indicators,
 *           touch/swipe, responsive per-view, infinite wrap.
 * Zero external dependencies.
 */
(function (global) {
  "use strict";

  var lsState = {};

  /* ─── Public API ─────────────────────────────────────── */
  global.lsInit = function (id, opts) {
    opts = opts || {};
    var wrapper  = document.getElementById(id);
    if (!wrapper) return;
    var viewport = wrapper.querySelector('.ls-slider-viewport');
    var track    = wrapper.querySelector('.ls-slider-track');
    var slides   = wrapper.querySelectorAll('.ls-slide');
    var dotsEl   = document.getElementById(id + '-dots');
    if (!track || !slides.length || !dotsEl) return;

    var total   = slides.length;
    var perView = _perView(viewport || wrapper);
    var current = 0;
    var autoMs  = opts.auto !== false ? (opts.autoMs || 4000) : 0;
    var timer   = null;

    _buildDots(id, dotsEl, total, perView);

    lsState[id] = {
      track: track, slides: slides, dotsEl: dotsEl,
      total: total, perView: perView, current: current,
      timer: timer, autoMs: autoMs, viewport: viewport
    };

    _render(id);
    if (autoMs) _startAuto(id);

    /* Pause on hover */
    wrapper.addEventListener('mouseenter', function () { _stopAuto(id); });
    wrapper.addEventListener('mouseleave', function () {
      if (lsState[id] && lsState[id].autoMs) _startAuto(id);
    });

    /* Touch / swipe */
    var touchX = 0;
    wrapper.addEventListener('touchstart', function (e) {
      touchX = e.changedTouches[0].screenX;
    }, { passive: true });
    wrapper.addEventListener('touchend', function (e) {
      var diff = touchX - e.changedTouches[0].screenX;
      if (Math.abs(diff) > 40) global.lsSlide(id, diff > 0 ? 1 : -1);
    }, { passive: true });

    /* Responsive resize */
    window.addEventListener('resize', function () { _resize(id); });
  };

  global.lsSlide = function (id, dir) {
    var s = lsState[id];
    if (!s) return;
    _stopAuto(id);
    var maxIdx = s.total - s.perView;
    if (maxIdx < 0) maxIdx = 0;
    var next = s.current + dir;
    if (next > maxIdx) next = 0;
    if (next < 0)     next = maxIdx;
    s.current = next;
    _render(id);
    if (s.autoMs) _startAuto(id);
  };

  global.lsGoTo = function (id, idx) {
    var s = lsState[id];
    if (!s) return;
    var maxIdx = s.total - s.perView;
    if (maxIdx < 0) maxIdx = 0;
    s.current = Math.max(0, Math.min(idx, maxIdx));
    _render(id);
  };

  /* ─── Private helpers ────────────────────────────────── */
  function _perView(el) {
    var w = el ? (el.offsetWidth || window.innerWidth) : window.innerWidth;
    if (w >= 992) return 3;
    if (w >= 576) return 2;
    return 1;
  }

  function _render(id) {
    var s = lsState[id];
    if (!s) return;
    var pct    = 100 / s.perView;
    var offset = -(s.current * pct);

    for (var i = 0; i < s.slides.length; i++) {
      s.slides[i].style.flex     = '0 0 ' + pct + '%';
      s.slides[i].style.maxWidth = pct + '%';
    }
    s.track.style.transform = 'translateX(' + offset + '%)';

    var dots = s.dotsEl.querySelectorAll('.ls-dot');
    for (var j = 0; j < dots.length; j++) {
      dots[j].classList.toggle('active', j === s.current);
    }
  }

  function _resize(id) {
    var s = lsState[id];
    if (!s) return;
    var el     = s.viewport || document.getElementById(id);
    var newPer = _perView(el);
    if (newPer === s.perView) return;
    s.perView  = newPer;
    _buildDots(id, s.dotsEl, s.total, newPer);
    var maxIdx = s.total - newPer;
    if (s.current > maxIdx) s.current = maxIdx < 0 ? 0 : maxIdx;
    _render(id);
  }

  function _buildDots(id, dotsEl, total, perView) {
    dotsEl.innerHTML = '';
    var maxIdx = total - perView;
    if (maxIdx <= 0) return;
    var pages = maxIdx + 1;
    for (var i = 0; i < pages; i++) {
      var d = document.createElement('button');
      d.className   = 'ls-dot' + (i === 0 ? ' active' : '');
      d.setAttribute('aria-label', 'Go to slide ' + (i + 1));
      (function (idx) {
        d.addEventListener('click', function () {
          global.lsGoTo(id, idx);
        });
      })(i);
      dotsEl.appendChild(d);
    }
  }

  function _startAuto(id) {
    var s = lsState[id];
    if (!s || !s.autoMs) return;
    _stopAuto(id);
    s.timer = setInterval(function () { global.lsSlide(id, 1); }, s.autoMs);
  }

  function _stopAuto(id) {
    var s = lsState[id];
    if (s && s.timer) { clearInterval(s.timer); s.timer = null; }
  }

})(window);
