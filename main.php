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

// Are we in edit/preview mode?
global $ACT;
$is_editing = (isset($ACT) && ($ACT === 'edit' || $ACT === 'preview'));

// Show edit FAB only on normal wiki page views
$show_edit_fab = (isset($ACT) && $ACT === 'show');

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

      <!-- Desktop Nav: search + quick actions -->
      <nav class="desktop-nav" aria-label="Main navigation">
        <div class="nav-search"><?php tpl_searchform() ?></div>
        <div class="header-actions">
          <a href="<?php echo wl($ID, 'do=media') ?>" class="header-action-btn" title="<?php echo $lang['btn_media'] ?>" aria-label="<?php echo $lang['btn_media'] ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
          </a>
          <a href="<?php echo wl('', 'do=admin') ?>" class="header-action-btn" title="<?php echo $lang['btn_admin'] ?>" aria-label="<?php echo $lang['btn_admin'] ?>">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z"/><path d="M2 7l10 5 10-5M12 22V12"/></svg>
          </a>
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
        <?php ob_start(); tpl_userinfo(); $__ui = ob_get_clean(); if ($__ui): ?>
          <span class="user-info" aria-hidden="true"><?php echo $__ui ?></span>
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

  <!-- ===== TOC Bar (collapsible, shown on all sizes when TOC exists) ===== -->
  <div class="toc-bar" id="toc-bar" style="display:none">
    <button class="toc-bar-toggle" id="toc-bar-toggle" aria-expanded="false">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="12" y2="18"/>
      </svg>
      <span><?php echo $lang['toc'] ?: 'On this page' ?></span>
      <svg class="toc-bar-chevron" width="12" height="12" viewBox="0 0 12 12" fill="currentColor">
        <path d="M2 4l4 4 4-4"/>
      </svg>
    </button>
    <div class="toc-bar-content" id="toc-bar-content" aria-hidden="true"></div>
  </div>

  <!-- ===== Main Layout: Content only ===== -->
  <div class="site-body">

    <!-- Content -->
    <div class="site-content" id="wiki__content">
      <main class="page-content" aria-label="Page content">
        <div class="page"><?php tpl_content() ?></div>
      </main>

      <?php tpl_flush() ?>

    </div>

  </div><!-- .site-body -->

</div><!-- .dokuwiki -->

<?php @include(dirname(__FILE__) . '/footer.html') ?>
<div class="no"><?php tpl_indexerWebBug() ?></div>

<!-- ===== Floating Action Buttons ===== -->
<div class="fab-group" id="fab-group" dir="<?php echo $dir_attr ?>">
  <?php if ($show_edit_fab): ?>
  <div class="fab-edit-wrap" id="fab-edit-wrap" data-mode="<?php echo $is_editing ? 'edit' : 'view' ?>">
    <a href="<?php echo wl($ID, 'do=edit') ?>" class="fab-edit-link" id="fab-edit-link" title="<?php echo $lang['btn_edit'] ?>" aria-label="<?php echo $lang['btn_edit'] ?>"></a>
  </div>
  <?php endif; ?>
  <button class="fab-top" id="fab-top" aria-label="Back to top" title="Back to top">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M18 15l-6-6-6 6"/>
    </svg>
  </button>
</div>

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

  /* ── Move TOC into collapsible bar ──────────────── */
  var toc       = document.getElementById('dw__toc'),
      tocBar    = document.getElementById('toc-bar'),
      tocCnt    = document.getElementById('toc-bar-content'),
      tocBtn    = document.getElementById('toc-bar-toggle');

  if (toc) {
    toc.style.cssText = 'float:none;width:auto;margin:0';
    var inner = toc.querySelector('div');

    // ── Clone TOC into collapsible bar ──
    if (tocBar && tocCnt && inner) {
      var cloneInner = inner.cloneNode(true);
      while (cloneInner.firstChild) tocCnt.appendChild(cloneInner.firstChild);
      tocBar.style.display = '';
    }

    // Hide original TOC
    toc.style.display = 'none';

    // Toggle
    if (tocBtn && tocCnt) {
      tocBtn.addEventListener('click', function () {
        var open = tocCnt.classList.toggle('is-open');
        tocBtn.setAttribute('aria-expanded', open);
        tocCnt.setAttribute('aria-hidden', !open);
      });
    }

    // ── Active link tracking on scroll ──
    if (tocCnt) {
      var anchors = tocCnt.querySelectorAll('a[href]');
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
  }

  /* ── Header scroll shadow ────────────────────────── */
  var siteHeader = document.getElementById('site-header');
  if (siteHeader) {
    window.addEventListener('scroll', function () {
      siteHeader.classList.toggle('scrolled', window.scrollY > 4);
    }, { passive: true });
  }

  /* ── FAB: inject edit/back icon ──────────────────── */
  var fabEditWrap = document.getElementById('fab-edit-wrap');
  if (fabEditWrap) {
    var mode = fabEditWrap.getAttribute('data-mode') || 'view';
    var svg;
    if (mode === 'edit') {
      svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>';
    } else {
      svg = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 113 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>';
    }
    var icon = document.createElement('span');
    icon.className = 'fab-edit-icon';
    icon.setAttribute('aria-hidden', 'true');
    icon.innerHTML = svg;
    fabEditWrap.appendChild(icon);
  }

  /* ── FAB: back-to-top visibility + click ─────────── */
  var fabTop = document.getElementById('fab-top');
  if (fabTop) {
    window.addEventListener('scroll', function () {
      fabTop.classList.toggle('is-visible', window.scrollY > 300);
    }, { passive: true });
    fabTop.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

})();
</script>
</body>
</html>
