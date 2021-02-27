<?php
  namespace iberezansky\fb3d;

  get_header();
  echo(shortcode_handler([
    'id'=> get_the_ID(),
    'classes'=> 'fb3d-default-page'
  ]));
  get_footer();
?>
