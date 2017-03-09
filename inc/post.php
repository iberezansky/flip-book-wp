<?php
  namespace iberezansky\fb3d;

  function register_post_type() {
    \register_post_type(POST_ID, array(
      'public'=> true,
      'label'=> __('3D FlipBook'),
      'menu_icon'=> 'dashicons-book-alt',
      'supports'=> array(
        'title'
      )
    ));
  }

  add_action('init', '\iberezansky\fb3d\register_post_type');
?>
