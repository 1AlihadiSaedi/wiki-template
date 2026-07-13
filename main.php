<?php
/**
 * DokuWiki Green Silk Template v3 — main.php
 * Full UX redesign: sidebar layout, clean header, modern components
 */
if (!defined('DOKU_INC')) die();

$dir_attr  = $lang['direction'];
$logo_id   = 'wiki:logo.png';
$has_logo  = @file_exists(mediaFN($logo_id));
$logo_path = ml($logo_id);
$is_rtl    = ($dir_attr === 'rtl');
$body_cls  = $is_rtl ? ' class="rtl-page"' : '';

// Are we in edit/preview mode?
global $ACT;
$is_editing = (isset($ACT) && ($ACT === 'edit' || $ACT === 'preview'));

// Show edit FAB only on normal wiki page views
$show_edit_fab = (isset($ACT) && $ACT === 'show');

// Page tools available?
$tools = ['edit','history','recent','media','admin'];
?><!DOCTYPE html>
<html lang="<?php echo $conf['lang'] ?>" dir="<?php echo $dir_attr ?>">
<head>
<meta charset="utf-8">
<title><?php echo tpl_pagetitle() ?> [<?php echo strip_tags($conf['title']) ?>]</title>

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
<a href="#wiki__content" class="skip-link"><?php echo $lang['skip_to_content'] ?></a>

<!-- Mobile Overlay -->
<div class="mobile-overlay" id="mobile-overlay"></div>

<!-- Side Drawer -->
<nav class="side-drawer" id="side-drawer" aria-label="Menu" dir="<?php echo $dir_attr ?>">
  <div class="drawer-header">
    <div class="drawer-logo">
      <img src="<?php echo $logo_path ?>" alt="" class="drawer-logo-img" width="32" height="32">
      <a href="<?php echo wl() ?>"><?php echo $conf['title'] ?></a>
    </div>
    <button class="drawer-close" id="drawer-close" aria-label="Close menu">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
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
        <?php if (isset($INFO) && $INFO['exists']): ?>
        <a href="<?php echo wl($ID,'do=history') ?>" class="drawer-link-btn"><?php echo $lang['btn_revs'] ?></a>
        <?php endif; ?>
        <a href="<?php echo wl('','do=recent') ?>" class="drawer-link-btn"><?php echo $lang['btn_recent'] ?></a>
        <a href="<?php echo wl($ID,'do=media') ?>" class="drawer-link-btn"><?php echo $lang['btn_media'] ?></a>
        <?php if (isset($INFO) && $INFO['isadmin']): ?>
        <a href="<?php echo wl('','do=admin') ?>" class="drawer-link-btn"><?php echo $lang['btn_admin'] ?></a>
        <?php endif; ?>
      </div>
    </div>
    <div class="drawer-section">
      <span class="drawer-label">Account</span>
      <div class="drawer-userinfo"><?php tpl_userinfo() ?></div>
      <div class="drawer-buttons">
        <?php
          if (!empty($_SERVER['REMOTE_USER'])) {
            echo '<a href="' . wl($ID, 'do=profile') . '" class="drawer-link-btn">' . $lang['btn_profile'] . '</a>';
            echo '<a href="' . wl($ID, 'do=login&sectok=' . getSecurityToken()) . '" class="drawer-link-btn">' . $lang['btn_logout'] . '</a>';
          } else {
            echo '<a href="' . wl($ID, 'do=login') . '" class="drawer-link-btn">' . $lang['btn_login'] . '</a>';
          }
        ?>
      </div>
    </div>
  </div>
</nav>

<!-- Site Header -->
<header class="site-header" id="site-header">
  <div class="header-inner">
    <!-- Hamburger -->
    <button class="hamburger" id="hamburger-btn" aria-label="Menu">
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
      <span class="hamburger-line"></span>
    </button>

    <!-- Logo + Title -->
    <div class="site-branding">
      <?php if ($has_logo): ?>
        <img src="<?php echo $logo_path ?>" alt="" class="site-logo" width="36" height="36">
      <?php endif; ?>
      <a href="<?php echo wl() ?>" class="site-title"><?php echo $conf['title'] ?></a>
    </div>

    <!-- Page name (breadcrumb style) -->
    <div class="page-name">
      <?php
      $parts = explode(':', $ID);
      $acc = '';
      foreach ($parts as $i => $part) {
        $acc .= ($i > 0 ? ':' : '') . $part;
        if ($i > 0) echo '<span class="page-name-sep">›</span>';
        echo '<a href="' . wl($acc) . '">' . hsc(ucfirst(str_replace('_', ' ', $part))) . '</a>';
      }
      ?>
    </div>

    <!-- Desktop Nav: search + quick actions -->
    <nav class="desktop-nav">
      <div class="nav-search"><?php tpl_searchform() ?></div>
      <div class="header-actions">
        <a href="<?php echo wl($ID, 'do=media') ?>" class="header-action-btn" title="<?php echo $lang['btn_media'] ?>" aria-label="<?php echo $lang['btn_media'] ?>">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
        </a>
        <?php if (isset($INFO) && $INFO['isadmin']): ?>
        <a href="<?php echo wl('', 'do=admin') ?>" class="header-action-btn" title="<?php echo $lang['btn_admin'] ?>" aria-label="<?php echo $lang['btn_admin'] ?>">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </a>
        <?php endif; ?>
      </div>
    </nav>

    <!-- Language Selector -->
    <?php
    $lang_names = [
      'en' => 'English', 'fa' => 'فارسی', 'fr' => 'Français', 'de' => 'Deutsch',
      'es' => 'Español', 'it' => 'Italiano', 'pt' => 'Português', 'ru' => 'Русский',
      'ar' => 'العربية', 'tr' => 'Türkçe', 'nl' => 'Nederlands', 'pl' => 'Polski',
      'ja' => '日本語', 'zh' => '中文', 'ko' => '한국어', 'he' => 'עברית',
      'sv' => 'Svenska', 'da' => 'Dansk', 'no' => 'Norsk', 'fi' => 'Suomi',
      'cs' => 'Čeština', 'el' => 'Ελληνικά', 'hu' => 'Magyar', 'ro' => 'Română',
      'uk' => 'Українська', 'id' => 'Indonesia', 'vi' => 'Tiếng Việt', 'th' => 'ไทย',
    ];
    // Discover available languages from translation plugin or config
    $avail_langs = [];
    $translation_plugin = plugin_load('helper','translation');
    if ($translation_plugin && !empty($translation_plugin->translations)) {
      $avail_langs = $translation_plugin->translations;
    }
    if (!in_array($conf['lang'], $avail_langs)) $avail_langs[] = $conf['lang'];
    // Determine current language from page ID prefix (e.g. "fa:api_engine" → "fa")
    $id_parts = explode(':', $ID, 2);
    $current_lang = (count($id_parts) === 2 && in_array($id_parts[0], $avail_langs)) ? $id_parts[0] : $conf['lang'];
    $page_base = (count($id_parts) === 2 && in_array($id_parts[0], $avail_langs)) ? $id_parts[1] : $ID;
    if (count($avail_langs) > 1):
    ?>
    <div class="lang-selector">
      <button class="lang-toggle" id="lang-toggle">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        <span class="lang-current"><?php echo isset($lang_names[$current_lang]) ? $lang_names[$current_lang] : strtoupper($current_lang) ?></span>
      </button>
      <div class="lang-dropdown" id="lang-dropdown">
        <?php foreach ($avail_langs as $code): ?>
          <?php $label = isset($lang_names[$code]) ? $lang_names[$code] : strtoupper($code); ?>
          <?php $translated_id = $code . ':' . $page_base; ?>
          <a href="<?php echo wl($translated_id) ?>" class="lang-option <?php echo ($code === $current_lang) ? 'active' : '' ?>"><?php echo $label ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- User Area -->
    <div class="user-area">
      <?php if (!empty($_SERVER['REMOTE_USER'])): ?>
        <span class="user-info"><?php echo hsc($_SERVER['REMOTE_USER']) ?></span>
        <a href="<?php echo wl($ID, 'do=profile') ?>" class="header-action-btn" title="<?php echo $lang['btn_profile'] ?>">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </a>
        <a href="<?php echo wl($ID, 'do=login&sectok=' . getSecurityToken()) ?>" class="header-action-btn" title="<?php echo $lang['btn_logout'] ?>">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        </a>
      <?php else: ?>
        <a href="<?php echo wl($ID, 'do=login') ?>" class="header-action-btn" title="<?php echo $lang['btn_login'] ?>">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
        </a>
      <?php endif; ?>
    </div>

    <!-- Theme Toggle -->
    <button class="theme-toggle" id="theme-toggle" aria-label="Toggle theme">
      <svg class="theme-icon-dark" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
      <svg class="theme-icon-light" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
    </button>
  </div>
</header>

<!-- Breadcrumbs -->
<?php if (isset($INFO) && $INFO['exists']): ?>
<div class="breadcrumbs-bar">
  <div class="breadcrumbs-inner">
    <?php tpl_breadcrumbs() ?>
  </div>
</div>
<?php endif; ?>

<!-- Mobile TOC Bar (mobile only, only if page has TOC) -->
<?php
global $TOC;
$has_toc = (isset($INFO) && $INFO['exists'] && !$is_editing && !empty($TOC));
?>
<?php if ($has_toc): ?>
<div class="mobile-toc-bar" id="mobile-toc-bar">
  <button class="mobile-toc-toggle" id="mobile-toc-toggle" aria-expanded="false">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    <span>Contents</span>
    <svg class="mobile-toc-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
  </button>
  <div class="mobile-toc-content" id="mobile-toc-content">
    <?php tpl_toc() ?>
  </div>
</div>
<?php endif; ?>

<!-- Main Content -->
<div class="dokuwiki">
  <div class="site-body">
    <main class="site-content">
      <div class="page-content">
        <?php tpl_content() ?>
      </div>
    </main>
  </div>

  <!-- Footer -->
  <footer class="site-footer">
    <div class="footerinc-inner">
      <div class="footer-meta">
        <div class="meta-left">
          <?php if (isset($INFO) && $INFO['exists']): ?>
            <span><?php echo $lang['lastmod'] ?>: <?php echo dformat($INFO['lastmod']) ?></span>
            <?php if ($INFO['editor']): ?>
              <span class="meta-sep">·</span>
              <span><?php echo $lang['by'] ?> <?php echo hsc($INFO['editor']) ?></span>
            <?php endif; ?>
          <?php endif; ?>
        </div>
        <div class="meta-right">
          <?php if (isset($INFO) && $INFO['exists']): ?>
            <a href="<?php echo wl($ID,'do=history') ?>" class="header-action-btn"><?php echo $lang['btn_revs'] ?></a>
            <?php if ($INFO['writable']): ?>
              <a href="<?php echo wl($ID,'do=edit') ?>" class="header-action-btn"><?php echo $lang['btn_edit'] ?></a>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>
      <div class="footer-powered">
        <a href="https://dokuwiki.org" class="footer-link">DokuWiki</a>
        <span class="footer-sep">·</span>
        <span>Green Silk Template</span>
        <span class="footer-sep">·</span>
        <?php tpl_pageinfo() ?>
      </div>
    </div>
  </footer>
</div>

<!-- Floating Edit Button (mid-screen, only on editable pages) -->
<?php if ($show_edit_fab && isset($INFO) && $INFO['exists'] && $INFO['writable']): ?>
<div class="fab-edit-container" dir="<?php echo $dir_attr ?>">
  <a href="<?php echo wl($ID, 'do=edit') ?>" class="fab-edit-btn" title="<?php echo $lang['btn_edit'] ?>" aria-label="<?php echo $lang['btn_edit'] ?>">
    <svg class="fab-edit-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
  </a>
</div>
<?php endif; ?>

<!-- Back to Top Button (bottom corner) -->
<button class="fab-top" id="fab-top" aria-label="Back to top">
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
</button>

<script>
(function(){
  // Hamburger toggle
  var btn = document.getElementById('hamburger-btn'),
      drw = document.getElementById('side-drawer'),
      ov  = document.getElementById('mobile-overlay'),
      cls = document.getElementById('drawer-close');

  if (btn && drw && ov) {
    function open()  { drw.classList.add('is-open'); ov.classList.add('is-visible'); document.body.classList.add('menu-open'); btn.classList.add('is-active'); }
    function close() { drw.classList.remove('is-open'); ov.classList.remove('is-visible'); document.body.classList.remove('menu-open'); btn.classList.remove('is-active'); }
    btn.addEventListener('click', function() {
      if (drw.classList.contains('is-open')) close(); else open();
    });
    if (cls) cls.addEventListener('click', close);
    ov.addEventListener('click', close);
  }

  // Theme toggle
  var tt = document.getElementById('theme-toggle');
  if (tt) {
    tt.addEventListener('click', function() {
      var cur = document.documentElement.getAttribute('data-theme') || 'light';
      var next = cur === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      try { localStorage.setItem('gswiki-theme', next); } catch(e) {}
    });
  }

  // Language dropdown
  var lt = document.getElementById('lang-toggle'),
      ld = document.getElementById('lang-dropdown');
  if (lt && ld) {
    lt.addEventListener('click', function(e) {
      e.stopPropagation();
      ld.classList.toggle('is-open');
    });
    document.addEventListener('click', function() { ld.classList.remove('is-open'); });
  }

  // Header scroll shadow
  var header = document.getElementById('site-header');
  if (header) {
    var scrolled = false;
    window.addEventListener('scroll', function() {
      var s = window.scrollY > 10;
      if (s !== scrolled) { scrolled = s; header.classList.toggle('scrolled', s); }
    }, { passive: true });
  }

  // Mobile TOC toggle
  var mtt = document.getElementById('mobile-toc-toggle'),
      mtc = document.getElementById('mobile-toc-content');
  if (mtt && mtc) {
    mtt.addEventListener('click', function() {
      var open = mtc.classList.toggle('is-open');
      mtt.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // Back to top
  var ft = document.getElementById('fab-top');
  if (ft) {
    window.addEventListener('scroll', function() {
      if (window.scrollY > 300) ft.classList.add('is-visible');
      else ft.classList.remove('is-visible');
    }, { passive: true });
    ft.addEventListener('click', function() {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }
})();
</script>

<div class="no"><?php tpl_indexerWebBug() ?></div>
</body>
</html>
