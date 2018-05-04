<?php
  namespace iberezansky\fb3d;
  use \WP_Query;

  function convert_tax_to_tax_query($tax) {
    $ids = explode(',', $tax);
    $iids = array();
    foreach($ids as $id) {
      array_push($iids, intval($id));
    }
    return array(array(
      'taxonomy'=> POST_ID.'-category',
  		'field'=> 'term_id',
  		'terms'=> $iids
    ));
  }

  function shortcode_handler($atts, $content='') {
    $atts = shortcode_atts(
      array(
        'id'=> '0',
        'mode'=> 'fullscreen',
        'title'=> 'false',
        'template'=> 'default',
        'lightbox'=> 'dark',
        'classes'=> '',
        'urlparam'=> 'fb3d-page',
        'pdf'=> '',
        'tax'=> 'null',
        'thumbnail'=> '',
        'cols'=> '3'
      ),
      $atts
    );

    if($atts['tax']==='null') {
      $is_link = $atts['mode']=='link-lightbox';      $atts['template'] = 'short-white-book-view';
      register_scripts_and_styles();

      wp_enqueue_style(POST_ID.'-client');
      wp_enqueue_script(POST_ID.'-'.$atts['mode']);

      $classes = str_replace(array(' ', "\t"), '', $atts['classes']);
      $classes = implode(' ', explode(',', $classes));

      $r = sprintf('<%s class="full-size %s %s"', $is_link? 'a href="#"': 'div', POST_ID, $classes);
      foreach($atts as $k=> $v) {
        if($k!=='classes') {
          $r .= sprintf(' data-%s="%s"', $k, $v);
        }
      }

      $res = $is_link? $r.'>'.$content.'</a>' :$r.'></div>'.$content;
    }
    else {
      $params = array('post_type'=> '3d-flip-book', 'posts_per_page'=>-1);
  		if($atts['tax']!=='') {
        if(substr($atts['tax'], 0, 1)==='{') {
          $params['tax_query'] = json_decode(str_replace("'", '"', $atts['tax']), true);
        }
  			else {
          $params['tax_query'] = convert_tax_to_tax_query($atts['tax']);
        }
  		}
  		$q = new WP_Query($params);
  		$params = $atts;
  		$cols = intval($atts['cols']);
  		unset($params['tax']);
      ob_start();
  		echo('<table><tr>');
  		for($i=0; $i<$q->post_count; ++$i) {
  			if($i%$cols===0 && $i) {
  				echo('</tr><tr>');
  			}
  			$params['id'] = $q->posts[$i]->ID;
  			echo('<td>'.shortcode_handler($params).'</td>');
      }
  		echo('</tr></table>');
      $res = ob_get_clean();
    }

    return $res;
  }

  add_shortcode(POST_ID, '\iberezansky\fb3d\shortcode_handler');
?>
