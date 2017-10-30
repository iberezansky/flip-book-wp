<?php
  namespace iberezansky\fb3d;

  function add_settings_page() {
    add_submenu_page(
      'edit.php?post_type='.POST_ID,
      __('3D FlipBook Settings', POST_ID),
      __('Settings', POST_ID),
      'manage_options',
      POST_ID.'-settings',
      '\iberezansky\fb3d\settings_render'
    );
  }

  add_action('admin_menu', '\iberezansky\fb3d\add_settings_page');

  function settings_render() {
    register_scripts_and_styles();

    wp_enqueue_style(POST_ID.'-settings');
    wp_enqueue_script(POST_ID.'-settings');
    ?>
    <div id="<?php echo(POST_ID.'-settings');?>">

    </div>
    <?php
  }
?>
