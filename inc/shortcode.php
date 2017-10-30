<?php
  namespace iberezansky\fb3d;

  function shortcode_handler($atts, $content='') {
    $atts = shortcode_atts(
      array(
        'id'=> '0',
        'mode'=> 'fullscreen',
        'title'=> 'false',
        'template'=> 'default',
        'lightbox'=> 'dark',
        'classes'=> '',
        'urlparam'=> 'fb3d-page'
      ),
      $atts
    );

    $is_link = $atts['mode']=='link-lightbox';

    register_scripts_and_styles();

    wp_enqueue_style(POST_ID.'-client');
    wp_enqueue_script(POST_ID.'-'.$atts['mode']);

    $classes = str_replace([' ', "\t"], '', $atts['classes']);
    $classes = implode(' ', explode(',', $classes));

    $r = sprintf('<%s class="full-size %s %s"', $is_link? 'a href="#"': 'div', POST_ID, $classes);
    foreach($atts as $k=> $v) {
      if($k!=='classes') {
        $r .= sprintf(' data-%s="%s"', $k, $v);
      }
    }

    return $is_link? $r.'>'.$content.'</a>' :$r.'></div>'.$content;
  }

  add_shortcode(POST_ID, '\iberezansky\fb3d\shortcode_handler');
?>
