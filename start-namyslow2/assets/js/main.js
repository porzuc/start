(function () {
  var toggle = document.querySelector('.nav-toggle');
  var nav = document.getElementById('main-nav');
  if (!toggle || !nav) { return; }
  toggle.addEventListener('click', function () {
    var open = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
})();

/* Baner ze zdjęciami: automatyczna zmiana, strzałki, kropki, przeciąganie, klawiatura. */
(function () {
  var box = document.querySelector('.hero__slider');
  if (!box) { return; }
  var slides = box.querySelectorAll('.hero__slide');
  var dots = box.querySelectorAll('.hero__dot');
  if (slides.length < 2) { return; }
  var interval = parseInt(box.getAttribute('data-interval'), 10) || 5000;
  var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var current = 0, timer = null, paused = false;

  function show(n) {
    current = (n + slides.length) % slides.length;
    for (var i = 0; i < slides.length; i++) {
      slides[i].classList.toggle('is-active', i === current);
      if (dots[i]) { dots[i].classList.toggle('is-active', i === current); }
    }
  }
  function stop() { if (timer) { clearInterval(timer); timer = null; } }
  function start() {
    stop();
    if (!reduce && !paused) { timer = setInterval(function () { show(current + 1); }, interval); }
  }
  function go(n) { show(n); start(); }

  for (var d = 0; d < dots.length; d++) {
    (function (i) { dots[i].addEventListener('click', function () { go(i); }); })(d);
  }

  var hero = box.parentNode;
  hero.addEventListener('mouseenter', function () { paused = true; stop(); });
  hero.addEventListener('mouseleave', function () { paused = false; start(); });
  document.addEventListener('visibilitychange', function () { if (document.hidden) { stop(); } else { start(); } });

  var x0 = null;
  hero.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
  hero.addEventListener('touchend', function (e) {
    if (x0 === null) { return; }
    var dx = e.changedTouches[0].clientX - x0;
    if (Math.abs(dx) > 50) { go(dx < 0 ? current + 1 : current - 1); }
    x0 = null;
  }, { passive: true });

  hero.setAttribute('tabindex', '0');
  hero.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft') { go(current - 1); }
    if (e.key === 'ArrowRight') { go(current + 1); }
  });

  start();
})();

/* Terminarz: odliczanie do najbliższego meczu, przewijanie kart (przeciąganie, kropki). */
(function () {
  var cd = document.querySelector('.sch-countdown');
  if (cd) {
    var target = parseInt(cd.getAttribute('data-ts'), 10) * 1000;
    var out = { d: cd.querySelector('[data-u="d"]'), h: cd.querySelector('[data-u="h"]'), m: cd.querySelector('[data-u="m"]'), s: cd.querySelector('[data-u="s"]') };
    var pad = function (n) { return (n < 10 ? '0' : '') + n; };
    var iv = null;
    var tick = function () {
      var diff = Math.max(0, Math.floor((target - Date.now()) / 1000));
      out.d.textContent = pad(Math.floor(diff / 86400));
      out.h.textContent = pad(Math.floor(diff % 86400 / 3600));
      out.m.textContent = pad(Math.floor(diff % 3600 / 60));
      out.s.textContent = pad(diff % 60);
      if (diff === 0 && iv) { clearInterval(iv); }
    };
    iv = setInterval(tick, 1000);
    tick();
  }

  var track = document.querySelector('.sch-track');
  if (!track) { return; }
  var cards = track.querySelectorAll('.sch-card');
  var dotsBox = document.querySelector('.sch-dots');

  function cardStep() { return cards.length > 1 ? cards[1].offsetLeft - cards[0].offsetLeft : track.clientWidth; }

  // na starcie pokaż najbliższy mecz (z jednym poprzednim po lewej)
  var next = track.querySelector('.sch-card.is-next');
  if (next) {
    var first = (window.innerWidth > 600 && next.previousElementSibling) ? next.previousElementSibling : next;
    track.scrollLeft = Math.max(0, first.offsetLeft - cards[0].offsetLeft);
  }

  var dots = [];
  function mark() {
    if (!dots.length) { return; }
    var idx = Math.round(track.scrollLeft / cardStep());
    idx = Math.max(0, Math.min(dots.length - 1, idx));
    for (var i = 0; i < dots.length; i++) { dots[i].classList.toggle('is-active', i === idx); }
  }
  function buildDots() {
    if (!dotsBox) { return; }
    dotsBox.innerHTML = ''; dots = [];
    var max = track.scrollWidth - track.clientWidth;
    if (max <= 4) { return; }
    var n = Math.max(2, Math.ceil(max / cardStep()) + 1);
    for (var i = 0; i < n; i++) {
      (function (i) {
        var b = document.createElement('button');
        b.type = 'button'; b.className = 'sch-dot'; b.setAttribute('aria-label', 'Terminarz ' + (i + 1));
        b.addEventListener('click', function () { track.scrollTo({ left: Math.min(max, i * cardStep()), behavior: 'smooth' }); });
        dotsBox.appendChild(b); dots.push(b);
      })(i);
    }
    dotsBox.removeAttribute('aria-hidden');
    mark();
  }
  track.addEventListener('scroll', mark, { passive: true });
  window.addEventListener('resize', buildDots);
  buildDots();

  // przeciąganie myszą
  var down = false, sx = 0, sl = 0;
  track.addEventListener('mousedown', function (e) { down = true; sx = e.pageX; sl = track.scrollLeft; });
  window.addEventListener('mouseup', function () { down = false; track.classList.remove('is-dragging'); });
  window.addEventListener('mousemove', function (e) {
    if (!down) { return; }
    var dx = e.pageX - sx;
    if (Math.abs(dx) > 4) { track.classList.add('is-dragging'); }
    track.scrollLeft = sl - dx;
  });

  track.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowRight') { track.scrollBy({ left: cardStep(), behavior: 'smooth' }); }
    if (e.key === 'ArrowLeft') { track.scrollBy({ left: -cardStep(), behavior: 'smooth' }); }
  });
})();
