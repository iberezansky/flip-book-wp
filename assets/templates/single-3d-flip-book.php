<?php
  namespace iberezansky\fb3d;

  get_header();
  echo(shortcode_handler(array(
    'id'=> get_the_ID()
  )));
  get_footer();
?>
