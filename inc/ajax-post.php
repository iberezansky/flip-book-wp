<?php
  namespace iberezansky\fb3d;

  function rec_stripslashes($mixed) {
    return  is_array($mixed) ? array_map('\iberezansky\fb3d\rec_stripslashes', $mixed) : stripslashes($mixed);
  }

  function receive_book_control_props_json() {
    $props = rec_stripslashes($_POST['props']);
    update_option(META_PREFIX.'book_control_props', serialize($props));
    wp_send_json(array('code'=> CODE_OK));
  }

  add_action('wp_ajax_fb3d_receive_book_control_props', '\iberezansky\fb3d\receive_book_control_props_json');

  function receive_question_answer_json() {
    global $fb3d;
    $q = $_POST['question'];
    $fb3d['options']['questions'][$q['id']] = [
      'state'=> $q['state'],
      'date'=> date(DTM_FORMAT)
    ];
    push_options();
    wp_send_json(['code'=> CODE_OK]);
  }

  add_action('wp_ajax_fb3d_receive_question_answer', '\iberezansky\fb3d\receive_question_answer_json');

?>
