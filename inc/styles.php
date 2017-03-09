<?php
  namespace iberezansky\fb3d;


  wp_register_style('font-awesome', ASSETS_CSS.'font-awesome.min.css', [], '4.7.0');
  wp_register_style('colorpicker', ASSETS_CSS.'colorpicker.css', [], '1.1.0');

  wp_register_style(POST_ID.'-admin', ASSETS_CSS.'admin.css', ['font-awesome'], VERSION);
  wp_register_style(POST_ID.'-edit', ASSETS_CSS.'edit.css', [POST_ID.'-admin', 'colorpicker'], VERSION);
  wp_register_style(POST_ID.'-insert', ASSETS_CSS.'insert.css', [POST_ID.'-admin'], VERSION);
  wp_register_style(POST_ID.'-settings', ASSETS_CSS.'settings.css', [POST_ID.'-admin'], VERSION);

  wp_register_style(POST_ID.'-client', ASSETS_CSS.'client.css', ['font-awesome'], VERSION);

?>
