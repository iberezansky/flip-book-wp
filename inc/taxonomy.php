<?php
  namespace iberezansky\fb3d;

  function register_taxonomy() {
    \register_taxonomy(POST_ID.'-category', POST_ID, array(
      'hierarchical'=> true,
      'label'=> __('Categories', POST_ID),
      'show-ui'=> true
    ));
  }

  add_action('init', '\iberezansky\fb3d\register_taxonomy');
?>
