<?php
  namespace iberezansky\fb3d;

  function register_post_type() {
    \register_post_type(POST_ID, array(
      'public'=> true,
      'label'=> __('3D FlipBook', POST_ID),
      'labels'=> array(
        'all_items'=> __('All Books', POST_ID),
      ),
      'menu_icon'=> 'dashicons-book-alt',
      'exclude_from_search'=> true,
      'supports'=> array(
        'title'
      )
    ));
  }

  add_action('init', '\iberezansky\fb3d\register_post_type');

  function custom_template($single) {
    global $wp_query, $post;
    if($post->post_type===POST_ID) {
      $template = TEMPLATES.'/single-3d-flip-book.php';
      if(file_exists($template)) {
        $single = $template;
      }
    }
    return $single;
  }

  add_filter('single_template', '\iberezansky\fb3d\custom_template');
?>
