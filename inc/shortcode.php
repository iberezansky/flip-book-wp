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

  function template_url_to_path($url) {
    $url = str_replace('\\', '/', $url);
    $dir = str_replace('\\', '/', DIR);
    $wp_content = str_replace('\\', '/', WP_CONTENT_DIR);
    $wp_content = substr($wp_content, strrpos($wp_content, '/'));
    $pattern = $wp_content.'/plugins/';
    return substr($dir, 0, strpos($dir, $pattern)).substr($url, strpos($url, $pattern));
  }

  function fetch_url_to_js_data($url) {
    global $fb3d;
    if(!isset($fb3d['jsData']['urls'][$url])) {
      $fb3d['jsData']['urls'][$url] = base64_encode(file_get_contents(template_url_to_path($url)));
    }
  }

  function load_js_data($a) {
    global $fb3d;

    if($a['mode']!=='thumbnail' && !isset($fb3d['jsData']['bookCtrlProps'])) {
      $fb3d['jsData']['bookCtrlProps'] = client_book_control_props();
    }

    if($a['id']!=='0') {
      array_push($fb3d['jsData']['posts'][in_array($a['mode'], ['thumbnail', 'thumbnail-lightbox'])? 'ids_mis': 'ids'], $a['id']);

      if($a['mode']!=='thumbnail') {
        array_push($fb3d['jsData']['pages'], $a['id']);
      }
      else {
        array_push($fb3d['jsData']['firstPages'], $a['id']);
      }
    }

    if($a['mode']!=='thumbnail') {
      $template = $a['template'];
      if($template==='default') {
        $template = aa(aa($fb3d['jsData']['bookCtrlProps'], 'skin'), 'default', 'short-white-book-view');
        if($template==='auto') {
          $template = 'short-white-book-view';
        }
        $a['template'] = $template;
      }

      if($a['lightbox']==='default') {
        $a['lightbox'] = aa(aa($fb3d['jsData']['bookCtrlProps'], 'lightbox'), 'default', 'dark');
        if($a['lightbox']==='auto') {
          $a['lightbox'] = 'dark';
        }
      }
      $template = $fb3d['templates'][$template];
      fetch_url_to_js_data($template['html']);
      fetch_url_to_js_data($template['script']);
      foreach($template['styles'] as $style) {
        fetch_url_to_js_data($style);
      }
    }

    return $a;
  }

  function enqueue_client_scripts() {
    global $fb3d;
    if(isset($fb3d['load_client_scripts']) && !isset($fb3d['enqueued_client_scripts'])) {
      $fb3d['enqueued_client_scripts'] = TRUE;

      $posts = client_posts_in($fb3d['jsData']['posts']['ids_mis'], $fb3d['jsData']['posts']['ids']);
      $fb3d['jsData']['posts'] = [];
      foreach ($posts as $post) {
        $fb3d['jsData']['posts'][$post['ID']] = $post;
      }

      $pages = client_posts_in_pages($fb3d['jsData']['pages']);
      $fb3d['jsData']['pages'] = [];
      foreach ($pages as $page) {
        if(!isset($fb3d['jsData']['pages'][$page['page_post_ID']])) {
          $fb3d['jsData']['pages'][$page['page_post_ID']] = [];
        }
        array_push($fb3d['jsData']['pages'][$page['page_post_ID']], $page);
      }

      $pages = client_posts_in_first_page($fb3d['jsData']['firstPages']);
      $fb3d['jsData']['firstPages'] = [];
      foreach ($pages as $page) {
        $fb3d['jsData']['firstPages'][$page['page_post_ID']] = $page;
      }

      register_scripts_and_styles();
      wp_enqueue_style(POST_ID.'-client');
      wp_enqueue_script(POST_ID.'-client');
    }
  }

  add_action('wp_footer', '\iberezansky\fb3d\enqueue_client_scripts');

  function shortcode_handler($atts, $content='') {
    global $fb3d;
    $fb3d['load_client_scripts'] = TRUE;
    $atts = shortcode_atts([
      'id'=> '0',
      'mode'=> 'fullscreen',
      'title'=> 'false',
      'template'=> 'default',
      'lightbox'=> 'default',
      'classes'=> '',
      'urlparam'=> 'fb3d-page',
      'page-n'=>'0',
      'pdf'=> '',
      'tax'=> 'null',
      'thumbnail'=> '',
      'cols'=> '3'
    ], $atts);

    if($atts['tax']==='null') {
      $is_link = $atts['mode']==='link-lightbox';
      $atts['template'] = 'short-white-book-view';
      $classes = str_replace(array(' ', "\t"), '', $atts['classes']);
      $classes = explode(',', $classes);
      array_push($classes, 'fb3d-'.$atts['mode'].'-mode');
      if($atts['mode']==='fullscreen') {
        array_push($classes, 'full-size');
      }
      $classes = implode(' ', $classes);

      $atts = load_js_data($atts);

      $r = sprintf('<%s class="%s %s"', $is_link? 'a href="#"': 'div', '_'.POST_ID, $classes);
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
