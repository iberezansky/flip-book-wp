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
        $arr[$k] = array();
      }
      put_value($arr[$k], $key, $value, $i+1);
    }
  }

  function cast_type($value, $spec) {
    return sprintf($spec, $value); //sanitize_text_field
  }

  function plane2struct($data, $info) {
    $struct = array();
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
    $data = plane2struct($plane, array(
      'base'=> array(
        '3dfb-post-type'=> array('default'=> 'pdf', 'qualifier'=> '%s'),
        '3dfb-post-data-post_ID'=> array('default'=> 0, 'qualifier'=> '%d'),
        '3dfb-post-data-guid'=> array('default'=> '', 'qualifier'=> '%s'),
        '3dfb-post-data-pages_customization'=> array('default'=> 'all', 'qualifier'=> '%s'),
        '3dfb-post-data-pdf_pages'=> array('default'=> 0, 'qualifier'=> '%d'),
        '3dfb-post-thumbnail-type'=> array('default'=> 'auto', 'qualifier'=> '%s'),
        '3dfb-post-thumbnail-data-post_ID'=> array('default'=> 0, 'qualifier'=> '%d'),

        '3dfb-post-props-height'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-width'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-gravity'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cachedPages'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-renderInactivePages'=> array('default'=> 'auto', 'qualifier'=> '%d'),
        '3dfb-post-props-renderInactivePagesOnMobile'=> array('default'=> 'auto', 'qualifier'=> '%d'),
        '3dfb-post-props-renderWhileFlipping'=> array('default'=> 'auto', 'qualifier'=> '%d'),
        '3dfb-post-props-pagesForPredicting'=> array('default'=> 'auto', 'qualifier'=> '%d'),
        '3dfb-post-props-preloadPages'=> array('default'=> 'auto', 'qualifier'=> '%d'),
        '3dfb-post-props-rtl'=> array('default'=> 'auto', 'qualifier'=> '%d'),

        '3dfb-post-props-sheet-startVelocity'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-sheet-cornerDeviation'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-sheet-flexibility'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-sheet-flexibleCorner'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-sheet-bending'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-sheet-wave'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-sheet-widthTexels'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-sheet-heightTexels'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-sheet-color'=> array('default'=> 'auto', 'qualifier'=> '%f'),

        '3dfb-post-props-cover-startVelocity'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cover-flexibility'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cover-flexibleCorner'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cover-bending'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cover-wave'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cover-widthTexels'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cover-heightTexels'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cover-color'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cover-depth'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cover-padding'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-cover-binderTexture'=> array('default'=> 'auto', 'qualifier'=> '%s'),
        '3dfb-post-props-cover-mass'=> array('default'=> 'auto', 'qualifier'=> '%f'),

        '3dfb-post-props-page-startVelocity'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-page-flexibility'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-page-flexibleCorner'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-page-bending'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-page-wave'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-page-widthTexels'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-page-heightTexels'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-page-color'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-page-depth'=> array('default'=> 'auto', 'qualifier'=> '%f'),
        '3dfb-post-props-page-mass'=> array('default'=> 'auto', 'qualifier'=> '%f'),

        '3dfb-post-controlProps-actions-cmdSave-enabled'=> array('default'=> 'auto', 'qualifier'=> '%d'),
        '3dfb-post-controlProps-actions-cmdPrint-enabled'=> array('default'=> 'auto', 'qualifier'=> '%d'),
        '3dfb-post-controlProps-actions-cmdSinglePage-enabled'=> array('default'=> 'auto', 'qualifier'=> '%d'),
        '3dfb-post-controlProps-actions-cmdSinglePage-active'=> array('default'=> 'auto', 'qualifier'=> '%d'),
      ),
      'regs'=> array( array(
          'pattern'=> '/3dfb-pages-\d+-page_ID/',
          'qualifier'=> '%d',
          'default'=> 0
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_post_ID/',
          'qualifier'=> '%d',
          'default'=> $id
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_title/',
          'qualifier'=> '%s',
          'default'=> ''
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_source_type/',
          'qualifier'=> '%s',
          'default'=> 'epdf'
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_source_data-post_ID/',
          'qualifier'=> '%d',
          'default'=> 0
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_source_data-guid/',
          'qualifier'=> '%s',
          'default'=> ''
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_source_data-interactive/',
          'qualifier'=> '%d',
          'default'=> 0
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_source_data-number/',
          'qualifier'=> '%d',
          'default'=> 0
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_thumbnail_type/',
          'qualifier'=> '%s',
          'default'=> 'auto'
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_thumbnail_data-post_ID/',
          'qualifier'=> '%s',
          'default'=> ''
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_meta_data-css_layer-css/',
          'qualifier'=> '%s',
          'default'=> ''
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_meta_data-css_layer-html/',
          'qualifier'=> '%s',
          'default'=> ''
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_meta_data-css_layer-js/',
          'qualifier'=> '%s',
          'default'=> ''
        ), array(
          'pattern'=> '/3dfb-pages-\d+-page_number/',
          'qualifier'=> '%d',
          'default'=> ''
      ) )
    ));
    $data['3dfb']['pages'] = isset($data['3dfb']['pages'])? $data['3dfb']['pages']: array();
    return $data;
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
