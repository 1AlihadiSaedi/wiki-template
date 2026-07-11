<?php
/**
 * DokuWiki Green Silk Template v3 — main.php
 * Full UX redesign: sidebar layout, clean header, modern components
 */
if (!defined('DOKU_INC')) die();

$dir_attr  = $lang['direction'];
$has_logo  = @file_exists(__DIR__ . '/images/logo.png');
$logo_path = DOKU_TPL . 'images/logo.png';
$is_rtl    = ($dir_attr === 'rtl');
$body_cls  = $is_rtl ? ' class="rtl-page"' : '';

// Page tools available?
$tools = ['edit','history','recent','media','index','admin'];
?><!DOCTYPE html>
<html lang="<?php echo $conf['lang'] ?>" dir="<?php echo $dir_attr ?>">
<head>
<meta charset="utf-8">
<title><?php tpl_pagetitle() ?> [<?php echo strip_tags($conf['title']) ?>]</title>

<!-- Anti-flash: set theme BEFORE any CSS loads -->
<script>
(function(){
  var t;
  try { t = localStorage.getItem('gswiki-theme'); } catch(e){}
  if (t === 'dark' || t === 'light') {
    document.documentElement.setAttribute('data-theme', t);
  } else {
    document.documentElement.setAttribute('data-theme',
      window.matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light');
  }
})();
</script>

<?php tpl_metaheaders() ?>
<?php echo tpl_favicon(array('favicon','mobile')) ?>
<?php @include(dirname(__FILE__) . '/meta.html') ?>

<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="#059669">
<meta name="color-scheme" content="light dark">

<!-- Inter font (Google Fonts) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body<?php echo $body_cls ?>>
<a href="#wiki__content" class="skip-link">Skip to content</a>

<?php @include(dirname(__FILE__) . '/topheader.html') ?>

<!-- ===== Mobile Overlay ===== -->
<div class="mobile-overlay" id="mobile-overlay" aria-hidden="true"></div>

<!-- ===== Side Drawer ===== -->
<nav class="side-drawer" id="side-drawer" aria-label="Menu" dir="<?php echo $dir_attr ?>">
  <div class="drawer-header">
    <div class="drawer-logo">
      <?php if ($has_logo): ?>
        <img src="<?php echo $logo_path ?>" alt="" class="drawer-logo-img" width="24" height="24">
      <?php endif ?>
      <?php tpl_link(wl(), $conf['title']) ?>
    </div>
    <button class="drawer-close" id="drawer-close" aria-label="Close menu">
      <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
        <path d="M13.5 4.5l-9 9M4.5 4.5l9 9" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"/>
      </svg>
    </button>
  </div>
  <div class="drawer-content">
    <div class="drawer-section">
      <span class="drawer-label"><?php echo $lang['btn_search'] ?></span>
      <?php tpl_searchform() ?>
    </div>
    <div class="drawer-section">
      <span class="drawer-label">Navigation</span>
      <div class="drawer-buttons">
        <?php tpl_button('edit') ?>
        <?php tpl_button('history') ?>
        <?php tpl_button('recent') ?>
        <?php tpl_button('media') ?>
        <?php tpl_button('index') ?>
        <?php tpl_button('admin') ?>
      </div>
    </div>
    <div class="drawer-section">
      <span class="drawer-label">Account</span>
      <div class="drawer-userinfo"><?php tpl_userinfo() ?></div>
      <div class="drawer-buttons">
        <?php tpl_button('profile') ?>
        <?php tpl_button('login') ?>
        <?php tpl_button('subscribe') ?>
      </div>
    </div>
  </div>
</nav>

<!-- ===== Wiki Wrapper ===== -->
<div class="dokuwiki">
  <?php html_msgarea() ?>

  <!-- ===== HEADER ===== -->
  <header class="site-header" id="site-header" dir="ltr">
    <div class="header-inner">

      <!-- Hamburger (mobile only) -->
      <button class="hamburger" id="hamburger-btn" aria-label="Open menu" aria-expanded="false">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>

      <!-- Logo + Site Name -->
      <div class="site-branding">
        <?php if ($has_logo): ?>
          <img src="<?php echo $logo_path ?>" alt="" class="site-logo" width="28" height="28">
        <?php endif ?>
        <?php tpl_link(wl(), $conf['title'],
          'class="site-title" name="dokuwiki__top" id="dokuwiki__top" accesskey="h" title="[H]"') ?>
      </div>

      <!-- Current page name (desktop) -->
      <div class="page-name">
        <?php if ($ID): ?>
          <span class="page-name-sep" aria-hidden="true">/</span>
          <?php tpl_link(wl($ID, 'do=backlink'),
            tpl_pagetitle($ID, true),
            'title="' . $lang['btn_backlink'] . '"') ?>
        <?php endif ?>
      </div>

      <!-- Desktop Nav -->
      <nav class="desktop-nav" aria-label="Main navigation">
        <div class="nav-search"><?php tpl_searchform() ?></div>
        <div class="nav-actions">
          <?php tpl_button('edit') ?>
          <?php tpl_button('history') ?>
          <?php tpl_button('recent') ?>
          <?php tpl_button('media') ?>
          <?php tpl_button('index') ?>
          <?php tpl_button('admin') ?>
        </div>
      </nav>

      <!-- Language Selector -->
      <div class="lang-selector">
        <?php
          $langs  = ['fa' => 'فارسی', 'en' => 'English', 'ar' => 'العربية'];
          $lpfx   = ['fa', 'en', 'ar'];
          $cur    = $conf['lang'];

          echo '<button class="lang-toggle" id="lang-toggle"'
             . ' aria-label="Select language" aria-expanded="false">'
             . '<svg width="15" height="15" viewBox="0 0 24 24" fill="none"'
             . ' stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'
             . '<circle cx="12" cy="12" r="10"/>'
             . '<path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>'
             . '</svg>'
             . '<span class="lang-current">' . strtoupper($cur) . '</span>'
             . '<svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor">'
             . '<path d="M2 3.5l3 3 3-3"/>'
             . '</svg>'
             . '</button>';
          echo '<div class="lang-dropdown" id="lang-dropdown" role="menu">';
          foreach ($langs as $c => $n) {
            $ac  = ($cur == $c) ? ' active' : '';
            $p   = explode(':', $ID, 2);
            $nid = (count($p) == 2 && in_array($p[0], $lpfx))
                   ? $c . ':' . $p[1]
                   : $c . ':' . $ID;
            echo '<a href="' . wl($nid) . '" class="lang-option' . $ac
               . '" hreflang="' . $c . '" role="menuitem">' . $n . '</a>';
          }
          echo '</div>';
        ?>
      </div>

      <!-- User Area -->
      <div class="user-area">
        <?php if (tpl_userinfo()): ?>
          <span class="user-info" aria-hidden="true"><?php tpl_userinfo() ?></span>
        <?php endif ?>
        <?php tpl_button('profile') ?>
        <?php tpl_button('login') ?>
      </div>

      <!-- Theme Toggle -->
      <button class="theme-toggle" id="theme-toggle" aria-label="Toggle dark/light theme" title="Toggle theme">
        <!-- Sun (shown in dark mode) -->
        <svg class="theme-icon-light" width="16" height="16" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="5"/>
          <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </svg>
        <!-- Moon (shown in light mode) -->
        <svg class="theme-icon-dark" width="16" height="16" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
        </svg>
      </button>

    </div>
  </header>

  <?php @include(dirname(__FILE__) . '/header.html') ?>

  <!-- ===== Breadcrumbs ===== -->
  <?php if ($conf['breadcrumbs'] || $conf['youarehere']): ?>
    <nav class="breadcrumbs-bar" aria-label="Breadcrumb">
      <div class="breadcrumbs-inner">
        <?php if ($conf['breadcrumbs']): ?>
          <div class="breadcrumbs"><?php tpl_breadcrumbs() ?></div>
        <?php endif ?>
        <?php if ($conf['youarehere']): ?>
          <div class="breadcrumbs"><?php tpl_youarehere() ?></div>
        <?php endif ?>
      </div>
    </nav>
  <?php endif ?>

  <?php tpl_flush() ?>
  <?php @include(dirname(__FILE__) . '/pageheader.html') ?>

  <!-- ===== Main Layout: Sidebar + Content ===== -->
  <div class="site-body">

    <!-- Sidebar (desktop) -->
    <aside class="site-sidebar" id="site-sidebar" aria-label="Sidebar">
      <!-- TOC placeholder — populated by JS -->
      <div id="sidebar-toc" class="sidebar-section" style="display:none">
        <span class="sidebar-label">On this page</span>
      </div>

      <!-- Page tools -->
      <div class="sidebar-section">
        <span class="sidebar-label">Page tools</span>
        <div class="sidebar-tools">
          <?php tpl_button('edit') ?>
          <?php tpl_button('revisions') ?>
          <?php tpl_button('history') ?>
          <?php tpl_button('recent') ?>
          <?php tpl_button('subscribe') ?>
          <?php tpl_button('revert') ?>
          <?php tpl_button('top') ?>
        </div>
      </div>

      <div class="sidebar-divider"></div>

      <!-- Site tools -->
      <div class="sidebar-section">
        <span class="sidebar-label">Site</span>
        <div class="sidebar-tools">
          <?php tpl_button('media') ?>
          <?php tpl_button('index') ?>
          <?php tpl_button('admin') ?>
          <?php tpl_button('profile') ?>
          <?php tpl_button('login') ?>
        </div>
      </div>

      <!-- Language -->
      <?php
        $langs = ['fa' => 'فارسی', 'en' => 'English', 'ar' => 'العربية'];
        $lpfx  = ['fa', 'en', 'ar'];
        $cur   = $conf['lang'];
        if (count($langs) > 1):
      ?>
      <div class="sidebar-divider"></div>
      <div class="sidebar-section">
        <span class="sidebar-label">Language</span>
        <div class="lang-buttons">
          <?php foreach ($langs as $c => $n):
            $ac  = ($cur == $c) ? ' active' : '';
            $p   = explode(':', $ID, 2);
            $nid = (count($p) == 2 && in_array($p[0], $lpfx))
                   ? $c . ':' . $p[1]
                   : $c . ':' . $ID;
          ?>
          <a href="<?php echo wl($nid) ?>" class="lang-btn<?php echo $ac ?>"
             hreflang="<?php echo $c ?>"><?php echo $n ?></a>
          <?php endforeach ?>
        </div>
      </div>
      <?php endif ?>
    </aside>

    <!-- Content -->
    <div class="site-content" id="wiki__content">
      <main class="page-content" aria-label="Page content">
        <div class="page"><?php tpl_content() ?></div>
      </main>

      <?php tpl_flush() ?>

      <!-- Page Footer -->
      <footer class="page-footer">
        <div class="footer-meta">
          <div class="meta-left">
            <span class="meta-user"><?php tpl_userinfo() ?></span>
            <?php if (tpl_userinfo()): ?>
              <span class="meta-sep" aria-hidden="true">&middot;</span>
            <?php endif ?>
            <span class="meta-pageinfo"><?php tpl_pageinfo() ?></span>
          </div>
          <div class="meta-right">
            <?php tpl_button('edit') ?>
            <?php tpl_button('history') ?>
            <?php tpl_button('revert') ?>
            <?php tpl_button('subscribe') ?>
            <?php tpl_button('top') ?>
          </div>
        </div>
        <?php @include(dirname(__FILE__) . '/pagefooter.html') ?>
        <?php tpl_license(false) ?>
      </footer>
    </div>

  </div><!-- .site-body -->

</div><!-- .dokuwiki -->

<?php @include(dirname(__FILE__) . '/footer.html') ?>
<div class="no"><?php tpl_indexerWebBug() ?></div>

<!-- ===== JavaScript ===== -->
<script>
(function () {
  'use strict';

  /* ── Hamburger / Side Drawer ─────────────────────── */
  var ham  = document.getElementById('hamburger-btn'),
      drw  = document.getElementById('side-drawer'),
      ovl  = document.getElementById('mobile-overlay'),
      cls  = document.getElementById('drawer-close');

  function openDrawer() {
    drw.classList.add('is-open');
    ovl.classList.add('is-visible');
    ovl.removeAttribute('aria-hidden');
    document.body.classList.add('menu-open');
    ham.classList.add('is-active');
    ham.setAttribute('aria-expanded', 'true');
  }

  function closeDrawer() {
    drw.classList.remove('is-open');
    ovl.classList.remove('is-visible');
    ovl.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('menu-open');
    ham.classList.remove('is-active');
    ham.setAttribute('aria-expanded', 'false');
  }

  if (ham && drw && ovl) {
    ham.addEventListener('click', function (e) {
      e.stopPropagation();
      drw.classList.contains('is-open') ? closeDrawer() : openDrawer();
    });
    if (cls) cls.addEventListener('click', closeDrawer);
    ovl.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && drw.classList.contains('is-open')) closeDrawer();
    });
  }

  /* ── Language Dropdown ───────────────────────────── */
  var ltBtn = document.getElementById('lang-toggle'),
      ltDrp = document.getElementById('lang-dropdown');
  if (ltBtn && ltDrp) {
    ltBtn.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = ltDrp.classList.toggle('is-open');
      ltBtn.setAttribute('aria-expanded', open);
    });
    document.addEventListener('click', function () {
      ltDrp.classList.remove('is-open');
      ltBtn.setAttribute('aria-expanded', 'false');
    });
    ltDrp.addEventListener('click', function (e) { e.stopPropagation(); });
  }

  /* ── Theme Toggle ────────────────────────────────── */
  var ttBtn = document.getElementById('theme-toggle'),
      html  = document.documentElement;

  function getTheme() {
    try { return localStorage.getItem('gswiki-theme'); } catch(e) { return null; }
  }
  function saveTheme(t) {
    try { localStorage.setItem('gswiki-theme', t); } catch(e) {}
  }
  function applyTheme(t) {
    html.setAttribute('data-theme',
      t === 'dark' ? 'dark' :
      t === 'light' ? 'light' :
      (window.matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light'));
  }

  applyTheme(getTheme() || 'auto');

  if (ttBtn) {
    ttBtn.addEventListener('click', function () {
      var nxt = html.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
      applyTheme(nxt);
      saveTheme(nxt);
    });
  }

  window.matchMedia('(prefers-color-scheme:dark)').addEventListener('change', function () {
    if (!getTheme()) applyTheme('auto');
  });

  /* ── Table wrap ──────────────────────────────────── */
  document.querySelectorAll('.dokuwiki table.inline').forEach(function (t) {
    if (t.parentNode.className === 'table-wrap') return;
    var w = document.createElement('div');
    w.className = 'table-wrap';
    t.parentNode.insertBefore(w, t);
    w.appendChild(t);
  });

  /* ── Code block wrap (header + copy button) ──────── */
  document.querySelectorAll('.dokuwiki pre').forEach(function (pre) {
    if (pre.closest('.code-block-wrap')) return;

    var wrap = document.createElement('div');
    wrap.className = 'code-block-wrap';

    var hd   = document.createElement('div');
    hd.className = 'code-block-header';

    var lang = document.createElement('span');
    lang.className = 'code-block-lang';
    var m = (pre.className || '').match(/(?:code|file)\s+(\w+)/);
    lang.textContent = m ? m[1] : 'code';

    var btn = document.createElement('button');
    btn.className = 'code-copy-btn';
    btn.textContent = 'Copy';
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      var text = pre.textContent || '';
      function done() {
        btn.textContent = 'Copied!';
        btn.classList.add('copied');
        setTimeout(function () {
          btn.textContent = 'Copy';
          btn.classList.remove('copied');
        }, 2000);
      }
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(done).catch(function () {
          fallbackCopy(text); done();
        });
      } else { fallbackCopy(text); done(); }
    });

    hd.appendChild(lang);
    hd.appendChild(btn);
    pre.parentNode.insertBefore(wrap, pre);
    wrap.appendChild(hd);
    wrap.appendChild(pre);
  });

  function fallbackCopy(text) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0';
    document.body.appendChild(ta);
    ta.select();
    try { document.execCommand('copy'); } catch(e) {}
    document.body.removeChild(ta);
  }

  /* ── Move TOC into sidebar ───────────────────────── */
  var toc        = document.getElementById('dw__toc'),
      tocSlot    = document.getElementById('sidebar-toc'),
      sidebar    = document.getElementById('site-sidebar');

  if (toc && tocSlot && sidebar) {
    // Remove float styles so it renders clean in sidebar
    toc.style.cssText = 'float:none;width:auto;margin:0';

    // Copy TOC links into sidebar container
    var inner = toc.querySelector('div');
    if (inner) {
      while (inner.firstChild) {
        tocSlot.appendChild(inner.firstChild);
      }
    }
    // Hide original TOC
    toc.style.display = 'none';
    tocSlot.style.display = '';

    // Active link tracking on scroll
    var anchors = tocSlot.querySelectorAll('a[href]');
    if (anchors.length > 0 && 'IntersectionObserver' in window) {
      var headings = [];
      anchors.forEach(function (a) {
        var id = a.getAttribute('href').replace(/^.*#/, '');
        var el = document.getElementById(id);
        if (el) headings.push({ link: a, el: el });
      });

      function setActive(link) {
        anchors.forEach(function (a) { a.classList.remove('toc-active'); });
        if (link) link.classList.add('toc-active');
      }

      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            var h = headings.find(function (x) { return x.el === entry.target; });
            if (h) setActive(h.link);
          }
        });
      }, { rootMargin: '-60px 0px -70% 0px', threshold: 0 });

      headings.forEach(function (h) { io.observe(h.el); });
    }
  }

  /* ── Header scroll shadow ────────────────────────── */
  var siteHeader = document.getElementById('site-header');
  if (siteHeader) {
    window.addEventListener('scroll', function () {
      siteHeader.classList.toggle('scrolled', window.scrollY > 4);
    }, { passive: true });
  }

})();
</script>
</body>
</html>
