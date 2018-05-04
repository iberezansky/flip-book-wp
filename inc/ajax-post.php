<?php
  namespace iberezansky\fb3d;

  function receive_book_control_props_json() {
    $props = $_POST['props'];
    update_option(META_PREFIX.'book_control_props', serialize($props));
    wp_send_json(array('code'=> CODE_OK));
  }

  add_action('wp_ajax_fb3d_receive_book_control_props', '\iberezansky\fb3d\receive_book_control_props_json');

?>
