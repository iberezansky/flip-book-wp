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


  add_action('current_screen', '\iberezansky\fb3d\add_lite_notice');

  function add_lite_notice() {
    global $typenow;
    if($typenow===POST_ID) {
      add_action('admin_notices', '\iberezansky\fb3d\pro_notice_render');
    }
  }

  add_action('add_meta_boxes', '\iberezansky\fb3d\add_details_meta_box');

  function add_manual_meta_box() {
    add_meta_box(
      POST_ID.'-manual',
      __('Help', POST_ID),
      '\iberezansky\fb3d\manual_metabox_render',
      POST_ID,
      'side',
      'low'
    );
  }

  add_action('add_meta_boxes', '\iberezansky\fb3d\add_manual_meta_box');

  function add_shortcode_meta_box() {
    global $pagenow;
    if($pagenow!=='post-new.php') {
      add_meta_box(
        POST_ID.'-shortcode',
        __('Shortcode', POST_ID),
        '\iberezansky\fb3d\shortcode_metabox_render',
        POST_ID,
        'side',
        'high'
      );
    }
  }

  add_action('add_meta_boxes', '\iberezansky\fb3d\add_shortcode_meta_box');

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

  function pro_notice_render($post) {
    ?>
      <div class="notice notice-warning">
        <p>
          This is <b>Lite Version</b>. To get more functions use <a href="http://3dflipbook.net/download-wp" target="_blank"><b>Pro Version</b></a>.
        </p>
        <p>
          <b>Pro Version</b> has custom control settings (keys binding, disabling the control buttons,  ...), skins and <a href="http://3dflipbook.net/download-wp" target="_blank">much more</a>.
        </p>
      </div>
    <?php
  }

  function details_metabox_render($post) {
    wp_nonce_field(PROPS_NONCE_ACTION, PROPS_NONCE_NAME);
    $meta = get_post_meta($post->ID);
    ?>
    <div id="<?php echo(POST_ID.'-edit');?>" data-id="<?php echo($post->ID);?>">

    </div>
    <?php
  }

  function manual_metabox_render($post) {
    ?>
    <div class="fb3d">
      <ul class="manual">
        <li>
          <span class="dashicons dashicons-book"></span> <a href="http://3dflipbook.net/wp-user-manual" target="_blank"><?php _e('User Manual', POST_ID) ?></a>
        </li>
        <li>
          <span class="dashicons dashicons-video-alt3"></span> <a href="https://www.youtube.com/channel/UCRLxHMrRORwfPs9WvTOzlHQ/videos" target="_blank"><?php _e('Video Examples', POST_ID) ?></a>
        </li>
        <li>
          <span class="dashicons dashicons-format-chat"></span> <a href="http://3dflipbook.net/support?product=2" target="_blank"><?php _e('Support Forum', POST_ID) ?></a>
        </li>
      </ul>
    </div>
    <?php
  }

  function shortcode_metabox_render($post) {
    ?>
    <div class="fb3d">
      <div class="form-group">
        <textarea class="form-control" readonly>[<?php echo(POST_ID.' mode="fullscreen" id="'.$post->ID.'" ][/'.POST_ID) ?>]</textarea></div>
    </div>
    <?php
  }


?>
