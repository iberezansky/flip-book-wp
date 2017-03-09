<?php
  namespace iberezansky\fb3d;
  define('iberezansky\fb3d\PDFJS_WORKER', ASSETS_JS.'pdf.worker.js');

  wp_register_script('react', ASSETS_JS.'react.min.js', null, '15.3.1', true);
  wp_register_script('react-dom', ASSETS_JS.'react-dom.min.js', ['react'], '15.3.1', true);
  wp_register_script('pdf-js', ASSETS_JS.'pdf.min.js', null, '1.5.188', true);
  wp_register_script('three', ASSETS_JS.'three.min.js', null, '0.83', true);
  wp_register_script('html2canvas', ASSETS_JS.'html2canvas.min.js', null, '0.5', true);

  wp_register_script(POST_ID.'-colorpicker', ASSETS_JS.'colorpicker.js', ['jquery'], '1.1.0', true);
  wp_register_script(POST_ID.'-edit', ASSETS_JS.'edit.min.js', ['react', 'react-dom', 'jquery', 'pdf-js', 'html2canvas', POST_ID.'-colorpicker'], VERSION, true);
  wp_register_script(POST_ID.'-insert', ASSETS_JS.'insert.min.js', ['react', 'react-dom', 'jquery'], VERSION, true);
  wp_register_script(POST_ID.'-settings', ASSETS_JS.'settings.min.js', ['react', 'react-dom', 'jquery'], VERSION, true);


  wp_register_script(POST_ID, ASSETS_JS.'3d-flip-book.min.js', ['jquery', 'pdf-js', 'html2canvas', 'three'], VERSION, true);

  wp_register_script(POST_ID.'-fullscreen', ASSETS_JS.'fullscreen.min.js', ['jquery', 'pdf-js', 'html2canvas', POST_ID], VERSION, true);
  wp_register_script(POST_ID.'-thumbnail', ASSETS_JS.'thumbnail.min.js', ['jquery', 'pdf-js', 'html2canvas'], VERSION, true);
  wp_register_script(POST_ID.'-thumbnail-lightbox', ASSETS_JS.'thumbnail-lightbox.min.js', ['jquery', 'pdf-js', 'html2canvas', POST_ID], VERSION, true);
  wp_register_script(POST_ID.'-link-lightbox', ASSETS_JS.'link-lightbox.min.js', ['jquery', 'pdf-js', 'html2canvas', POST_ID], VERSION, true);

  wp_localize_script('pdf-js', 'PDFJS_LOCALE', [
    'pdfJsWorker'=> PDFJS_WORKER
  ]);

  // wp_localize_script(POST_ID, 'FB3D_LOCALE', [
  //
  // ]);

  wp_localize_script(POST_ID.'-edit', 'FB3D_EDIT_LOCALE', [
    'editMountNode'=> POST_ID.'-edit',
    'images'=> ASSETS_IMAGES
  ]);

  wp_localize_script(POST_ID.'-insert', 'FB3D_INSERT_LOCALE', [
    'key'=> POST_ID,
    'templates'=> $templates
  ]);

  wp_localize_script(POST_ID.'-settings', 'FB3D_SETTINGS_LOCALE', [
    'settingsMountNode'=> POST_ID.'-settings',
    'images'=> ASSETS_IMAGES
  ]);

  $client_locale = [
    'key'=> POST_ID,
    'ajaxurl'=> admin_url('admin-ajax.php'),
    'templates'=> $templates,
    'images'=> ASSETS_IMAGES
  ];
  wp_localize_script(POST_ID.'-fullscreen', 'FB3D_CLIENT_LOCALE', $client_locale);
  wp_localize_script(POST_ID.'-thumbnail', 'FB3D_CLIENT_LOCALE', $client_locale);
  wp_localize_script(POST_ID.'-thumbnail-lightbox', 'FB3D_CLIENT_LOCALE', $client_locale);
  wp_localize_script(POST_ID.'-link-lightbox', 'FB3D_CLIENT_LOCALE', $client_locale);

?>
