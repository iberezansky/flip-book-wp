<?php
  /*
    Plugin Name: 3D FlipBook - Lite Edition
    Plugin URI: http://3dflipbook.net/
    Description: Interactive 3D FlipBook Powered Physics Engine WordPress Plugin  <a href="http://3dflipbook.net/download-wp"><strong>Go Pro</strong></a>
    Author: iberezansky
    Author URI: http://3dflipbook.net/
    Version: 1.10.15
    License: GPLv2 or later

    Text Domain: 3d-flip-book
  */
  namespace iberezansky\fb3d;

  function get_dump($var) {
    ob_start();
    var_dump($var);
    return '<pre class="dump"><code>'.htmlspecialchars(ob_get_clean()).'</code></pre>';
  }

  function dump($var) {
    echo get_dump($var);
  }

  function aa($a, $n, $v=[]) {
    return isset($a[$n])? $a[$n]: $v;
  }

  $fb3d = array(
    'load-keys'=> false,
    'dictionary'=> [],
    'jsData'=> [
      'urls'=> [],
      'posts'=> ['ids_mis'=> [], 'ids'=> []],
      'pages'=> [],
      'firstPages'=> []
      ]
  );


  function define_tables_names() {
    global $wpdb;
    define('iberezansky\fb3d\TABLE_NAME', $wpdb->prefix.'fb3d_pages');
  }

  function fetch_options() {
    global $fb3d;
    $fb3d['options'] = get_option(META_PREFIX.'options');
    $fb3d['options'] = unserialize($fb3d['options']);
    $fb3d['options'] = $fb3d['options']? $fb3d['options']: [
      'questions'=> []
    ];
  }

  function push_options() {
    global $fb3d;
    update_option(META_PREFIX.'options', serialize($fb3d['options']));
  }

  define('iberezansky\fb3d\VERSION', '1.10.15');
  define('iberezansky\fb3d\DBVERSION', '1.1');
  define('iberezansky\fb3d\SKINVERSION', '1.0');
  define('DTM_FORMAT', 'Y-m-d H:i:s');
  define_tables_names();
  define('iberezansky\fb3d\MAIN', __FILE__);
  define('iberezansky\fb3d\DIR', plugin_dir_path(__FILE__));
  define('iberezansky\fb3d\DIR_NAME', dirname(plugin_basename(__FILE__)));
  define('iberezansky\fb3d\INC', DIR.'inc/');
  define('iberezansky\fb3d\TEMPLATES', DIR.'assets/templates/');
  define('iberezansky\fb3d\URL', plugins_url('/', __FILE__));
  define('iberezansky\fb3d\ASSETS', URL.'assets/');
  define('iberezansky\fb3d\ASSETS_JS', ASSETS.'js/');
  define('iberezansky\fb3d\ASSETS_CSS', ASSETS.'css/');
  define('iberezansky\fb3d\ASSETS_TEMPLATES', ASSETS.'templates/');
  define('iberezansky\fb3d\ASSETS_IMAGES', ASSETS.'images/');
  define('iberezansky\fb3d\ASSETS_SOUNDS', ASSETS.'sounds/');
  define('iberezansky\fb3d\ASSETS_CMAPS', ASSETS.'cmaps/');

  define('iberezansky\fb3d\POST_ID', '3d-flip-book');
  define('iberezansky\fb3d\META_PREFIX', '3dfb_');
  fetch_options();

  require_once(INC.'codes.php');
  require_once(INC.'templates.php');
  require_once(INC.'install.php');
  require_once(INC.'post-pages.php');
  require_once(INC.'post.php');
  require_once(INC.'taxonomy.php');
  require_once(INC.'dictionary.php');
  require_once(INC.'styles.php');
  require_once(INC.'scripts.php');
  require_once(INC.'edit.php');
  require_once(INC.'insert.php');
  require_once(INC.'shortcode-generator.php');
  require_once(INC.'shortcode.php');
  require_once(INC.'ajax-get.php');
  require_once(INC.'ajax-post.php');
  require_once(INC.'question.php');

//file_put_contents('d:/php.html', get_dump($w));
//file_put_contents('d:/php.html', get_dump(array('post'=>$_POST,'data'=>$data)));

?>
