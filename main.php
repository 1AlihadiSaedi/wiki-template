<?php
/**
 * DokuWiki Modern Green Silk Template - main.php
 * Features: anti-flash theme, table-wrap, code-block (ChatGPT-style)
 */
if (!defined('DOKU_INC')) die();

// --- Init ---
$dir_attr  = $lang['direction'];
$has_logo  = @file_exists(__DIR__ . '/images/logo.png');
$logo_path = DOKU_TPL . 'images/logo.png';
$body_class = ($dir_attr === 'rtl') ? ' class="rtl-page"' : '';
?><!DOCTYPE html>
<html lang="<?php echo $conf['lang']?>" dir="<?php echo $dir_attr?>">
<head>
<meta charset="utf-8">
<title><?php tpl_pagetitle()?> [<?php echo strip_tags($conf['title'])?>]</title>

<!-- Anti-flash: set data-theme BEFORE any CSS loads -->
<script>
(function(){
  var t;
  try { t = localStorage.getItem('dokuwiki-theme'); } catch(e) {}
  if (t === 'dark') {
    document.documentElement.setAttribute('data-theme', 'dark');
  } else if (t === 'light') {
    document.documentElement.setAttribute('data-theme', 'light');
  } else {
    document.documentElement.setAttribute('data-theme',
      window.matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light');
  }
})();
</script>

<?php tpl_metaheaders()?>
<?php echo tpl_favicon(array('favicon', 'mobile'))?>
<?php @include(dirname(__FILE__) . '/meta.html')?>

<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="#059669">
<meta name="color-scheme" content="light dark">
</head>

<body<?php echo $body_class?>>

<?php @include(dirname(__FILE__) . '/topheader.html')?>

<!-- ===== Mobile Overlay ===== -->
<div class="mobile-overlay" id="mobile-overlay" aria-hidden="true"></div>

<!-- ===== Side Drawer (mobile nav) ===== -->
<nav class="side-drawer" id="side-drawer" aria-label="Menu" dir="<?php echo $dir_attr?>">
  <div class="drawer-header">
    <div class="drawer-logo">
      <?php if ($has_logo): ?>
        <img src="<?php echo $logo_path?>" alt="" class="drawer-logo-img" width="32" height="32">
      <?php endif?>
      <?php tpl_link(wl(), $conf['title'])?>
    </div>
    <button class="drawer-close" id="drawer-close" aria-label="Close">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
        <path d="M15 5L5 15M5 5l10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
  </div>
  <div class="drawer-content">
    <div class="drawer-section">
      <span class="drawer-title"><?php echo $lang['btn_search']?></span>
      <?php tpl_searchform()?>
    </div>
    <div class="drawer-section">
      <span class="drawer-title">Tools</span>
      <div class="drawer-buttons">
        <?php tpl_button('edit')?>
        <?php tpl_button('history')?>
        <?php tpl_button('recent')?>
        <?php tpl_button('media')?>
        <?php tpl_button('index')?>
        <?php tpl_button('admin')?>
      </div>
    </div>
    <div class="drawer-section">
      <span class="drawer-title">User</span>
      <div class="drawer-userinfo"><?php tpl_userinfo()?></div>
      <div class="drawer-buttons">
        <?php tpl_button('profile')?>
        <?php tpl_button('login')?>
        <?php tpl_button('subscribe')?>
      </div>
    </div>
  </div>
</nav>

<!-- ===== Main Wiki Area ===== -->
<div class="dokuwiki">
  <?php html_msgarea()?>

  <!-- ===== HEADER - always LTR ===== -->
  <header class="site-header" id="site-header" dir="ltr">
    <div class="header-inner">

      <!-- Hamburger -->
      <button class="hamburger" id="hamburger-btn" aria-label="Menu" aria-expanded="false">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>

      <!-- Logo + Site Name -->
      <div class="site-branding">
        <?php if ($has_logo): ?>
          <img src="<?php echo $logo_path?>" alt="" class="site-logo" width="28" height="28">
        <?php endif?>
        <?php tpl_link(wl(), $conf['title'], 'class="site-title" name="dokuwiki__top" id="dokuwiki__top" accesskey="h" title="[H]"')?>
      </div>

      <!-- Current Page Name -->
      <div class="page-name">
        <?php if ($ID): ?>
          <span class="page-name-sep">/</span>
          <?php tpl_link(wl($ID, 'do=backlink'), tpl_pagetitle($ID, true), 'title="' . $lang['btn_backlink'] . '"')?>
        <?php endif?>
      </div>

      <!-- Desktop Nav: Search + Action Buttons -->
      <nav class="desktop-nav" aria-label="Main">
        <div class="nav-search"><?php tpl_searchform()?></div>
        <div class="nav-actions">
          <?php tpl_button('edit')?>
          <?php tpl_button('history')?>
          <?php tpl_button('recent')?>
          <?php tpl_button('media')?>
          <?php tpl_button('index')?>
          <?php tpl_button('admin')?>
        </div>
      </nav>

      <!-- Language Selector -->
      <div class="lang-selector">
        <?php
          $langs   = ['fa' => 'فارسی', 'en' => 'English', 'ar' => 'العربية'];
          $lang_pf = ['fa', 'en', 'ar'];
          $cur     = $conf['lang'];
          echo '<button class="lang-toggle" id="lang-toggle" aria-label="Language" aria-expanded="false">'
             . '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">'
             . '<circle cx="12" cy="12" r="10"/>'
             . '<path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/>'
             . '</svg>'
             . '<span class="lang-current">' . strtoupper($cur) . '</span>'
             . '</button>';
          echo '<div class="lang-dropdown" id="lang-dropdown">';
          foreach ($langs as $c => $n) {
            $ac  = ($cur == $c) ? ' active' : '';
            $p   = explode(':', $ID, 2);
            $nid = (count($p) == 2 && in_array($p[0], $lang_pf))
                   ? $c . ':' . $p[1]
                   : $c . ':' . $ID;
            $u = wl($nid);
            echo '<a href="' . $u . '" class="lang-option' . $ac . '" hreflang="' . $c . '">' . $n . '</a>';
          }
          echo '</div>';
        ?>
      </div>

      <!-- User Area -->
      <div class="user-area">
        <div class="user-info"><?php tpl_userinfo()?></div>
        <?php tpl_button('profile')?>
        <?php tpl_button('login')?>
      </div>

      <!-- Dark/Light Toggle -->
      <button class="theme-toggle" id="theme-toggle" aria-label="Toggle theme" title="Dark/Light">
        <svg class="theme-icon-light" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <circle cx="12" cy="12" r="5"/>
          <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </svg>
        <svg class="theme-icon-dark" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
        </svg>
      </button>
    </div>
  </header>

  <?php @include(dirname(__FILE__) . '/header.html')?>

  <!-- Breadcrumbs -->
  <?php if ($conf['breadcrumbs']): ?>
    <nav class="breadcrumbs-wrapper" aria-label="Breadcrumb">
      <div class="breadcrumbs"><?php tpl_breadcrumbs()?></div>
    </nav>
  <?php endif?>
  <?php if ($conf['youarehere']): ?>
    <nav class="breadcrumbs-wrapper" aria-label="Breadcrumb">
      <div class="breadcrumbs"><?php tpl_youarehere()?></div>
    </nav>
  <?php endif?>

  <?php tpl_flush()?>
  <?php @include(dirname(__FILE__) . '/pageheader.html')?>

  <!-- ===== Page Content ===== -->
  <main class="page-content">
    <div class="page"><?php tpl_content()?></div>
  </main>

  <?php tpl_flush()?>

  <!-- ===== Footer ===== -->
  <footer class="page-footer">
    <div class="meta">
      <div class="meta-left">
        <span class="meta-user"><?php tpl_userinfo()?></span>
        <span class="meta-sep" aria-hidden="true">&middot;</span>
        <span class="meta-pageinfo"><?php tpl_pageinfo()?></span>
      </div>
      <div class="meta-right">
        <?php tpl_button('edit')?>
        <?php tpl_button('history')?>
        <?php tpl_button('revert')?>
        <?php tpl_button('subscribe')?>
        <?php tpl_button('top')?>
      </div>
    </div>
    <?php @include(dirname(__FILE__) . '/pagefooter.html')?>
    <?php tpl_license(false)?>
  </footer>
</div>

<?php @include(dirname(__FILE__) . '/footer.html')?>
<div class="no"><?php tpl_indexerWebBug()?></div>

<!-- ===== JavaScript ===== -->
<script>
(function() {

  // --- Hamburger / Side Drawer ---
  var h = document.getElementById('hamburger-btn'),
      d = document.getElementById('side-drawer'),
      o = document.getElementById('mobile-overlay'),
      c = document.getElementById('drawer-close');

  function open() {
    d.classList.add('is-open');
    o.classList.add('is-visible');
    o.setAttribute('aria-hidden', 'false');
    document.body.classList.add('menu-open');
    h.classList.add('is-active');
    h.setAttribute('aria-expanded', 'true');
  }

  function close() {
    d.classList.remove('is-open');
    o.classList.remove('is-visible');
    o.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('menu-open');
    h.classList.remove('is-active');
    h.setAttribute('aria-expanded', 'false');
  }

  h.addEventListener('click', function(e) {
    e.stopPropagation();
    d.classList.contains('is-open') ? close() : open();
  });
  c.addEventListener('click', close);
  o.addEventListener('click', close);
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && d.classList.contains('is-open')) close();
  });

  // --- Language Dropdown ---
  var lt = document.getElementById('lang-toggle'),
      ld = document.getElementById('lang-dropdown');
  lt.addEventListener('click', function(e) {
    e.stopPropagation();
    var is = ld.classList.toggle('is-open');
    lt.setAttribute('aria-expanded', is);
  });
  document.addEventListener('click', function() {
    ld.classList.remove('is-open');
    lt.setAttribute('aria-expanded', 'false');
  });

  // --- Theme Toggle ---
  var tt   = document.getElementById('theme-toggle'),
      html = document.documentElement;

  function getTheme() {
    try { return localStorage.getItem('dokuwiki-theme'); } catch(e) { return null; }
  }
  function setTheme(t) {
    try { localStorage.setItem('dokuwiki-theme', t); } catch(e) {}
  }
  function applyTheme(t) {
    if (t === 'dark') {
      html.setAttribute('data-theme', 'dark');
    } else if (t === 'light') {
      html.setAttribute('data-theme', 'light');
    } else {
      html.setAttribute('data-theme',
        window.matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light');
    }
  }

  var saved = getTheme();
  if (saved) { applyTheme(saved); }
  else       { applyTheme('auto'); }

  tt.addEventListener('click', function() {
    var cur = html.getAttribute('data-theme'),
        nxt = (cur === 'dark') ? 'light' : 'dark';
    applyTheme(nxt);
    setTheme(nxt);
  });

  window.matchMedia('(prefers-color-scheme:dark)').addEventListener('change', function() {
    if (!getTheme()) applyTheme('auto');
  });

  // --- Table Wrap (scroll wrapper for wide tables) ---
  var tbls = document.querySelectorAll('.dokuwiki table.inline');
  for (var i = 0; i < tbls.length; i++) {
    (function(t) {
      if (t.parentNode.className == 'table-wrap') return;
      var w = document.createElement('div');
      w.className = 'table-wrap';
      t.parentNode.insertBefore(w, t);
      w.appendChild(t);
    })(tbls[i]);
  }

  // --- Code Block Wrap (ChatGPT-style header + copy button) ---
  var pres = document.querySelectorAll('.dokuwiki pre');
  for (var i = 0; i < pres.length; i++) {
    (function(pre) {
      if (pre.parentNode.className == 'code-block-wrap') return;

      var w = document.createElement('div');
      w.className = 'code-block-wrap';

      var hd = document.createElement('div');
      hd.className = 'code-block-header';

      var lb = document.createElement('span');
      lb.className = 'code-block-lang';
      var cls = pre.className || '';
      var m = cls.match(/code\s+(\w+)/) || cls.match(/file\s+(\w+)/);
      lb.textContent = m ? m[1] : 'code';

      var btn = document.createElement('button');
      btn.className = 'code-copy-btn';
      btn.textContent = 'Copy';

      btn.addEventListener('click', function(e) {
        e.stopPropagation();
        var t = pre.textContent || '';
        function done() {
          btn.textContent = 'Copied!';
          btn.classList.add('copied');
          setTimeout(function() {
            btn.textContent = 'Copy';
            btn.classList.remove('copied');
          }, 2000);
        }
        if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(t).then(done);
        } else {
          var ta = document.createElement('textarea');
          ta.value = t;
          ta.style.cssText = 'position:fixed;opacity:0';
          document.body.appendChild(ta);
          ta.select();
          document.execCommand('copy');
          document.body.removeChild(ta);
          done();
        }
      });

      hd.appendChild(lb);
      hd.appendChild(btn);
      pre.parentNode.insertBefore(w, pre);
      w.appendChild(hd);
      w.appendChild(pre);
    })(pres[i]);
  }

})();
</script>

</body>
</html>
