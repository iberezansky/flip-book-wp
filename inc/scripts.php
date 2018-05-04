<?php
  namespace iberezansky\fb3d;
  define('iberezansky\fb3d\PDFJS_WORKER', ASSETS_JS.'pdf.worker.js');

  function register_scripts() {
    wp_register_script('react', ASSETS_JS.'react.min.js', null, '15.3.1', true);
    wp_register_script('react-dom', ASSETS_JS.'react-dom.min.js', array('react'), '15.3.1', true);
    wp_register_script('pdf-js', ASSETS_JS.'pdf.min.js', null, '1.5.188', true);
    wp_register_script('three', ASSETS_JS.'three.min.js', null, '0.83', true);
    wp_register_script('html2canvas', ASSETS_JS.'html2canvas.min.js', null, '0.5', true);

    wp_register_script(POST_ID.'-colorpicker', ASSETS_JS.'colorpicker.js', array('jquery'), '1.1.0', true);
    wp_register_script(POST_ID.'-edit', ASSETS_JS.'edit.min.js', array('react', 'react-dom', 'jquery', 'pdf-js', 'html2canvas', POST_ID.'-colorpicker'), VERSION, true);
    wp_register_script(POST_ID.'-insert', ASSETS_JS.'insert.min.js', array('react', 'react-dom', 'jquery'), VERSION, true);
    wp_register_script(POST_ID.'-settings', ASSETS_JS.'settings.min.js', array('react', 'react-dom', 'jquery'), VERSION, true);
    wp_register_script(POST_ID.'-shortcode-generator', ASSETS_JS.'shortcode-generator.js', array('react', 'react-dom', 'jquery', POST_ID.'-insert'), VERSION, true);


    wp_register_script(POST_ID, ASSETS_JS.'3d-flip-book.min.js', array('jquery', 'pdf-js', 'html2canvas', 'three'), VERSION, true);

    wp_register_script(POST_ID.'-fullscreen', ASSETS_JS.'fullscreen.min.js', array('jquery', 'pdf-js', 'html2canvas', POST_ID), VERSION, true);
    wp_register_script(POST_ID.'-thumbnail', ASSETS_JS.'thumbnail.min.js', array('jquery', 'pdf-js', 'html2canvas'), VERSION, true);
    wp_register_script(POST_ID.'-thumbnail-lightbox', ASSETS_JS.'thumbnail-lightbox.min.js', array('jquery', 'pdf-js', 'html2canvas', POST_ID), VERSION, true);
    wp_register_script(POST_ID.'-link-lightbox', ASSETS_JS.'link-lightbox.min.js', array('jquery', 'pdf-js', 'html2canvas', POST_ID), VERSION, true);

    localize_scripts();
  }

  $fb3d['registered_scripts_and_styles'] = false;
  function register_scripts_and_styles() {
    global $fb3d;
    if(!$fb3d['registered_scripts_and_styles']) {
      register_scripts();
      register_styles();
      $fb3d['registered_scripts_and_styles'] = true;
    }
  }



  function localize_scripts() {
    global $fb3d;

    wp_localize_script('pdf-js', 'PDFJS_LOCALE', array(
      'pdfJsWorker'=> PDFJS_WORKER,
      'pdfJsCMapUrl'=> ASSETS_CMAPS
    ));

    wp_localize_script(POST_ID, 'FB3D_LOCALE', array(
      'dictionary'=> $fb3d['dictionary']
    ));

    wp_localize_script(POST_ID.'-edit', 'FB3D_ADMIN_LOCALE', array(
      'editMountNode'=> POST_ID.'-edit',
      'images'=> ASSETS_IMAGES,
      'dictionary'=> $fb3d['dictionary']
    ));

    wp_localize_script(POST_ID.'-insert', 'FB3D_ADMIN_LOCALE', array(
      'key'=> POST_ID,
      'templates'=> $fb3d['templates'],
      'dictionary'=> $fb3d['dictionary'],
      'shortcodeGeneratorMountNode'=> POST_ID.'-shortcode-generator'
    ));

    wp_localize_script(POST_ID.'-settings', 'FB3D_ADMIN_LOCALE', array(
      'settingsMountNode'=> POST_ID.'-settings',
      'images'=> ASSETS_IMAGES,
      'templates'=> $fb3d['templates'],
      'dictionary'=> $fb3d['dictionary']
    ));

    $client_locale = array(
      'key'=> POST_ID,
      'ajaxurl'=> admin_url('admin-ajax.php'),
      'templates'=> $fb3d['templates'],
      'images'=> ASSETS_IMAGES
    );
    wp_localize_script(POST_ID.'-fullscreen', 'FB3D_CLIENT_LOCALE', $client_locale);
    wp_localize_script(POST_ID.'-thumbnail', 'FB3D_CLIENT_LOCALE', $client_locale);
    wp_localize_script(POST_ID.'-thumbnail-lightbox', 'FB3D_CLIENT_LOCALE', $client_locale);
    wp_localize_script(POST_ID.'-link-lightbox', 'FB3D_CLIENT_LOCALE', $client_locale);
  }

?>
