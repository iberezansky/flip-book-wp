<?php
  namespace iberezansky\fb3d;

  $fb3d['templates'] = array(
    'short-white-book-view'=> array(
      'styles'=> array(
        ASSETS_CSS.'short-white-book-view.css'
      ),
      'links'=> array(
        array(
          'rel'=> 'stylesheet',
          'href'=> ASSETS_CSS.'font-awesome.min.css'
        )
      ),
      'html'=> ASSETS_TEMPLATES.'default-book-view.html',
      'script'=> ASSETS_JS.'default-book-view.js',
      'sounds'=> array(
        'startFlip'=> ASSETS_SOUNDS.'start-flip.mp3',
        'endFlip'=> ASSETS_SOUNDS.'end-flip.mp3'
      )
    )
  );
  function import_templates($skins) {
    global $fb3d;
    $fb3d['templates'] = $skins[POST_ID]['skins'];
  }

  add_action('iberezansky_skinedit_export_skins', '\iberezansky\fb3d\import_templates');

  function export_templates($skins) {
    global $fb3d;
    $skins[POST_ID] = array(
      'skins'=> $fb3d['templates'],
      'version'=> SKINVERSION,
      'name'=> __('3D FlipBook'),
      'description'=> array(
        'caption'=> array(
          'type'=> 'text',
          'required'=> false
        ),
        'styles'=> array(
          'type'=> 'file',
          'ext'=> 'css',
          'number'=> 'inf',
          'required'=> false
        ),
        'links'=> array(
          'type'=> 'links',
          'number'=> 'inf',
          'required'=> false
        ),
        'html'=> array(
          'type'=> 'file',
          'ext'=> 'php',
          'number'=> 'one',
          'required'=> true
        ),
        'script'=> array(
          'type'=> 'file',
          'ext'=> 'js',
          'number'=> 'one',
          'required'=> false
        ),
        'sounds'=> array(
          'type'=> 'file',
          'ext'=> 'mp3',
          'number'=> array(
            'startFlip',
            'endFlip'
          ),
          'required'=> false
        )
      )
    );
    return $skins;
  }

  add_filter('iberezansky_skinedit_import_skins', '\iberezansky\fb3d\export_templates');

  function template_url_to_path($url) {
    $url = str_replace ('\\', '/', $url);
    $dir = str_replace ('\\', '/', DIR);
    $pattern = '/wp-content/plugins/';
    return substr($dir, 0, strpos($dir, $pattern)).substr($url, strpos($url, $pattern));
  }

?>
