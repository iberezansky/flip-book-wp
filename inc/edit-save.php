<?php
  namespace iberezansky\fb3d;

  function put_value(&$arr, &$key, &$value, $p=0) {
    $i = strpos($key, '-', $p);
    if($i===false) {
      $arr[substr($key, $p)] = $value;
    }
    else {
      $k = substr($key, $p, $i-$p);
      if(!isset($arr[$k])) {
        $arr[$k] = [];
      }
      put_value($arr[$k], $key, $value, $i+1);
    }
  }

  function cast_type($value, $spec) {
    return sprintf($spec, $value); //sanitize_text_field
  }

  function plane2struct($data, $info) {
    $struct = [];
    foreach($info['base'] as $k=> $i) {
      $data[$k] = isset($data[$k])? $data[$k]: $i['default'];
    }
    foreach($data as $k=> $v) {
      unset($value);
      if(isset($info['base'][$k])) {
        $value = $v=='auto'? $v: cast_type($v, $info['base'][$k]['qualifier']);
      }
      else {
        foreach($info['regs'] as $reg) {
          if(preg_match($reg['pattern'], $k)) {
            $value = $v=='auto'? $v: cast_type(isset($v)? $v: $reg['default'], $reg['qualifier']);
          }
        }
      }
      if(isset($value)) {
        put_value($struct, $k, $value);
      }
    }
    return $struct;
  }

  function get_post_data($id, $plane) {
    return plane2struct($plane, [
      'base'=> [
        '3dfb-post-type'=> ['default'=> 'pdf', 'qualifier'=> '%s'],
        '3dfb-post-data-post_ID'=> ['default'=> 0, 'qualifier'=> '%d'],
        '3dfb-post-data-guid'=> ['default'=> '', 'qualifier'=> '%s'],
        '3dfb-post-thumbnail-type'=> ['default'=> 'auto', 'qualifier'=> '%s'],
        '3dfb-post-thumbnail-data-post_ID'=> ['default'=> 0, 'qualifier'=> '%d'],

        '3dfb-post-props-height'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-width'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-gravity'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cachedPages'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-renderInactivePages'=> ['default'=> 'auto', 'qualifier'=> '%d'],
        '3dfb-post-props-renderWhileFlipping'=> ['default'=> 'auto', 'qualifier'=> '%d'],
        '3dfb-post-props-pagesForPredicting'=> ['default'=> 'auto', 'qualifier'=> '%d'],
        '3dfb-post-props-preloadPages'=> ['default'=> 'auto', 'qualifier'=> '%d'],

        '3dfb-post-props-sheet-startVelocity'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-sheet-cornerDeviation'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-sheet-flexibility'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-sheet-flexibleCorner'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-sheet-bending'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-sheet-wave'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-sheet-widthTexels'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-sheet-heightTexels'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-sheet-color'=> ['default'=> 'auto', 'qualifier'=> '%f'],

        '3dfb-post-props-cover-startVelocity'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cover-flexibility'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cover-flexibleCorner'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cover-bending'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cover-wave'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cover-widthTexels'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cover-heightTexels'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cover-color'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cover-depth'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cover-padding'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-cover-binderTexture'=> ['default'=> 'auto', 'qualifier'=> '%s'],
        '3dfb-post-props-cover-mass'=> ['default'=> 'auto', 'qualifier'=> '%f'],

        '3dfb-post-props-page-startVelocity'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-page-flexibility'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-page-flexibleCorner'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-page-bending'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-page-wave'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-page-widthTexels'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-page-heightTexels'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-page-color'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-page-depth'=> ['default'=> 'auto', 'qualifier'=> '%f'],
        '3dfb-post-props-page-mass'=> ['default'=> 'auto', 'qualifier'=> '%f'],
      ],
      'regs'=> [ [
          'pattern'=> '/3dfb-pages-\d+-page_ID/',
          'qualifier'=> '%d',
          'default'=> 0
        ], [
          'pattern'=> '/3dfb-pages-\d+-page_post_ID/',
          'qualifier'=> '%d',
          'default'=> $id
        ], [
          'pattern'=> '/3dfb-pages-\d+-page_title/',
          'qualifier'=> '%s',
          'default'=> ''
        ], [
          'pattern'=> '/3dfb-pages-\d+-page_source_type/',
          'qualifier'=> '%s',
          'default'=> 'epdf'
        ], [
          'pattern'=> '/3dfb-pages-\d+-page_source_data-post_ID/',
          'qualifier'=> '%d',
          'default'=> 0
        ], [
          'pattern'=> '/3dfb-pages-\d+-page_source_data-guid/',
          'qualifier'=> '%s',
          'default'=> ''
        ], [
          'pattern'=> '/3dfb-pages-\d+-page_source_data-interactive/',
          'qualifier'=> '%d',
          'default'=> 0
        ], [
          'pattern'=> '/3dfb-pages-\d+-page_source_data-number/',
          'qualifier'=> '%d',
          'default'=> 0
        ], [
          'pattern'=> '/3dfb-pages-\d+-page_thumbnail_type/',
          'qualifier'=> '%s',
          'default'=> 'auto'
        ], [
          'pattern'=> '/3dfb-pages-\d+-page_thumbnail_data-post_ID/',
          'qualifier'=> '%s',
          'default'=> ''
        ], [
          'pattern'=> '/3dfb-pages-\d+-page_number/',
          'qualifier'=> '%d',
          'default'=> ''
      ] ]
    ]);
  }

  function props_save($id) {
    $autosave = wp_is_post_autosave($id);
    $revision = wp_is_post_revision($id);
    $valid = isset($_POST[PROPS_NONCE_NAME]) && wp_verify_nonce($_POST[PROPS_NONCE_NAME], PROPS_NONCE_ACTION);

    if(!($autosave || $revision || !$valid)) {
      $data = get_post_data($id, $_POST);
      foreach ($data['3dfb']['post'] as $key => $value) {
        update_post_meta($id, META_PREFIX.$key, $value);
      }

      set_post_pages($id, $data['3dfb']['pages']);
    }
  }

  add_action('save_post', '\iberezansky\fb3d\props_save');
?>
