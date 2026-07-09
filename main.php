<?php
/**
 * DokuWiki Modern Template
 *
 * A clean, modern redesign of the classic DokuWiki default template.
 * Features: hamburger menu, mobile-first responsive design, modern CSS.
 *
 * @link   http://dokuwiki.org/templates
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author desbest <afaninthehouse@gmail.com>
 * @author Modernized by Ali Hadi Saedi
 */

if (!defined('DOKU_INC')) die();

?>
<!DOCTYPE html>
<html lang="<?php echo $conf['lang']?>" dir="<?php echo $lang['direction']?>">
<head>
  <meta charset="utf-8" />
  <title>
    <?php tpl_pagetitle()?>
    [<?php echo strip_tags($conf['title'])?>]
  </title>

  <?php tpl_metaheaders()?>
  <?php echo tpl_favicon(array('favicon', 'mobile')) ?>

  <?php @include(dirname(__FILE__).'/meta.html')?>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<?php @include(dirname(__FILE__).'/topheader.html')?>

<!-- Mobile Menu Overlay -->
<div class="mobile-overlay" id="mobile-overlay"></div>

<!-- Side Drawer Navigation -->
<nav class="side-drawer" id="side-drawer">
  <div class="drawer-header">
    <div class="drawer-logo">
      <?php tpl_link(wl(),$conf['title'])?>
    </div>
    <button class="drawer-close" id="drawer-close" aria-label="Close menu">&times;</button>
  </div>
  <div class="drawer-content">
    <div class="drawer-section">
      <h4 class="drawer-title"><?php echo $lang['btn_search']?></h4>
      <?php tpl_searchform()?>
    </div>
    <div class="drawer-section">
      <h4 class="drawer-title"><?php echo tpl_getLang('tools')?></h4>
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
      <h4 class="drawer-title"><?php echo tpl_getLang('user')?></h4>
      <div class="drawer-userinfo">
        <?php tpl_userinfo()?>
      </div>
      <div class="drawer-buttons">
        <?php tpl_button('profile')?>
        <?php tpl_button('login')?>
        <?php tpl_button('subscribe')?>
      </div>
    </div>
  </div>
</nav>

<div class="dokuwiki">
  <?php html_msgarea()?>

  <!-- Modern Header -->
  <header class="site-header" id="site-header">
    <div class="header-inner">
      <!-- Hamburger Button (Mobile) -->
      <button class="hamburger" id="hamburger-btn" aria-label="Toggle menu">
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
        <span class="hamburger-line"></span>
      </button>

      <!-- Logo / Site Name -->
      <div class="site-branding">
        <?php tpl_link(wl(),$conf['title'],'class="site-title" name="dokuwiki__top" id="dokuwiki__top" accesskey="h" title="[H]"')?>
      </div>

      <!-- Page Name -->
      <div class="page-name">
        <?php tpl_link(wl($ID,'do=backlink'),tpl_pagetitle($ID,true),'title="'.$lang['btn_backlink'].'"')?>
      </div>

      <!-- Desktop Navigation -->
      <nav class="desktop-nav">
        <div class="nav-search">
          <?php tpl_searchform()?>
        </div>
        <div class="nav-actions">
          <?php tpl_button('edit')?>
          <?php tpl_button('history')?>
          <?php tpl_button('recent')?>
          <?php tpl_button('media')?>
          <?php tpl_button('index')?>
          <?php tpl_button('admin')?>
        </div>
      </nav>

      <!-- User Area (Desktop) -->
      <div class="user-area">
        <div class="user-info">
          <?php tpl_userinfo()?>
        </div>
        <?php tpl_button('profile')?>
        <?php tpl_button('login')?>
      </div>
    </div>
  </header>

  <?php @include(dirname(__FILE__).'/header.html')?>

  <!-- Breadcrumbs -->
  <?php if($conf['breadcrumbs']){?>
  <div class="breadcrumbs-wrapper">
    <div class="breadcrumbs">
      <?php tpl_breadcrumbs()?>
    </div>
  </div>
  <?php }?>

  <?php if($conf['youarehere']){?>
  <div class="breadcrumbs-wrapper">
    <div class="breadcrumbs">
      <?php tpl_youarehere() ?>
    </div>
  </div>
  <?php }?>

  <?php tpl_flush()?>
  <?php @include(dirname(__FILE__).'/pageheader.html')?>

  <!-- Page Content -->
  <main class="page-content">
    <div class="page">
      <!-- wikipage start -->
      <?php tpl_content()?>
      <!-- wikipage stop -->
    </div>
  </main>

  <div class="clearer"></div>

  <?php tpl_flush()?>

  <!-- Page Footer -->
  <footer class="page-footer">
    <div class="meta">
      <div class="meta-left">
        <span class="meta-user"><?php tpl_userinfo()?></span>
        <span class="meta-sep">&middot;</span>
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

    <?php @include(dirname(__FILE__).'/pagefooter.html')?>

    <?php tpl_license(false);?>
  </footer>

</div>

<?php @include(dirname(__FILE__).'/footer.html')?>

<div class="no"><?php tpl_indexerWebBug()?></div>

<!-- Mobile Menu JavaScript -->
<script>
(function() {
  var hamburger = document.getElementById('hamburger-btn');
  var drawer = document.getElementById('side-drawer');
  var overlay = document.getElementById('mobile-overlay');
  var closeBtn = document.getElementById('drawer-close');

  function openMenu() {
    drawer.classList.add('is-open');
    overlay.classList.add('is-visible');
    document.body.classList.add('menu-open');
    hamburger.classList.add('is-active');
  }

  function closeMenu() {
    drawer.classList.remove('is-open');
    overlay.classList.remove('is-visible');
    document.body.classList.remove('menu-open');
    hamburger.classList.remove('is-active');
  }

  if (hamburger) {
    hamburger.addEventListener('click', function(e) {
      e.stopPropagation();
      if (drawer.classList.contains('is-open')) {
        closeMenu();
      } else {
        openMenu();
      }
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', closeMenu);
  }

  if (overlay) {
    overlay.addEventListener('click', closeMenu);
  }

  // Close on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
      closeMenu();
    }
  });
})();
</script>
</body>
</html>
