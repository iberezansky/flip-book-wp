<?php
  namespace iberezansky\fb3d;

  function add_shortcode_generator_page() {
    add_submenu_page(
      'edit.php?post_type='.POST_ID,
      __('3D FlipBook - Shortcode Generator', POST_ID),
      __('Shortcode Generator', POST_ID),
      'edit_posts',//'manage_options',
      POST_ID.'-shortcode-generator',
      '\iberezansky\fb3d\shortcode_generator_render'
    );
  }

  add_action('admin_menu', '\iberezansky\fb3d\add_shortcode_generator_page');

  function shortcode_generator_render() {
    register_scripts_and_styles();

    wp_enqueue_style(POST_ID.'-insert');
    wp_enqueue_script(POST_ID.'-shortcode-generator');
    ?>
    <div class="fb3d">
      <h1><?php _e('3D FlipBook - Shortcode Generator', POST_ID);?></h1>
      <div class="form-group">
        <textarea id="3dfb-shortcode-textarea" class="form-control"></textarea>
      </div>
      <div class="fb3d-admin-container">
        <div id="<?php echo(POST_ID.'-shortcode-generator');?>">

        </div>
      </div>
    </div>
    <?php
  }
?>
