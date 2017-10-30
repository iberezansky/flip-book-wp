<?php
  namespace iberezansky\fb3d;

  define('iberezansky\fb3d\PROPS_NONCE_ACTION', basename(__FILE__));
  define('iberezansky\fb3d\PROPS_NONCE_NAME', 'fb3d-props-nonce');

  require_once(INC.'edit-save.php');

  function add_details_meta_box() {
    add_meta_box(
      POST_ID.'-details',
      __('Details', POST_ID),
      '\iberezansky\fb3d\details_metabox_render',
      POST_ID,
      'normal',
      'core'
    );
  }

  add_action('add_meta_boxes', '\iberezansky\fb3d\add_details_meta_box');

  function enqueue_edit_scripts() {
    global $pagenow, $typenow;

    if(($pagenow == 'post.php' || $pagenow == 'post-new.php') && $typenow == POST_ID) {
      register_scripts_and_styles();

      wp_enqueue_media();

      wp_enqueue_style(POST_ID.'-edit');
      wp_enqueue_script(POST_ID.'-edit');
    }
  }

  add_action('admin_enqueue_scripts', '\iberezansky\fb3d\enqueue_edit_scripts');

  function details_metabox_render($post) {
    wp_nonce_field(PROPS_NONCE_ACTION, PROPS_NONCE_NAME);
    $meta = get_post_meta($post->ID);
    ?>
    <div id="<?php echo(POST_ID.'-edit');?>" data-id="<?php echo($post->ID);?>">

    </div>
    <?php
  }



?>
