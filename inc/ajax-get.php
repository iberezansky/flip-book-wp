<?php
  namespace iberezansky\fb3d;
  use \WP_Query;

  function send_json_finish($code) {
    if($code) {
      wp_send_json(array(
        'code'=> $code
      ));
    }
  }

  function post_to_user_post($post, $isMeta) {
    if($isMeta) {
      $meta = get_post_meta($post->ID);
    }
    else {
      $meta = [];
    }
    $def = get_post_data($post->ID, []);
    $def = $def['3dfb']['post'];
    return [
      'ID'=> $post->ID,
      'title'=> $post->post_title,
      'type'=> isset($meta[META_PREFIX.'type'][0])? $meta[META_PREFIX.'type'][0]: 'pdf',
      'data'=> unserialize(isset($meta[META_PREFIX.'data'][0])? $meta[META_PREFIX.'data'][0]: serialize($def['data'])),
      'thumbnail'=> unserialize(isset($meta[META_PREFIX.'thumbnail'][0])? $meta[META_PREFIX.'thumbnail'][0]: serialize($def['thumbnail'])),
      'props'=> unserialize(isset($meta[META_PREFIX.'props'][0])? $meta[META_PREFIX.'props'][0]: serialize($def['props']))
    ];
  }

  function send_posts_json() {
    $q = new WP_Query(array('post_type'=> POST_ID, 'posts_per_page'=>-1));
    $r = [];
    for($i=0; $i<$q->post_count; ++$i) {
      array_push($r, post_to_user_post($q->posts[$i], false));
    }
    wp_send_json(['code'=> CODE_OK,'posts'=> $r]);
  }

  add_action('wp_ajax_fb3d_send_posts', '\iberezansky\fb3d\send_posts_json');
  add_action('wp_ajax_nopriv_fb3d_send_posts', '\iberezansky\fb3d\send_posts_json');

  function send_template_html() {
    $template_url = isset($_GET['template'])? $_GET['template']: ASSETS_TEMPLATES.'default-book-view.php';
    include(template_url_to_path($template_url));
    exit(0);
  }

  add_action('wp_ajax_fb3d_send_template_html', '\iberezansky\fb3d\send_template_html');
  add_action('wp_ajax_nopriv_fb3d_send_template_html', '\iberezansky\fb3d\send_template_html');

  function get_media_image($id) {
    $q = new WP_Query([
      'p'=> $id,
      'post_type'=> 'attachment',
      'post_status'=> 'inherit',
      'post_mime_type'=> [
        'image/gif',
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/svg+xml'
      ]
    ]);
    if($q->post_count) {
      $post = $q->posts[0];
      $meta = wp_get_attachment_metadata($post->ID);
      $meta['parent_url'] = substr($post->guid, 0, strrpos($post->guid, '/')+1);
      $meta['id'] = $post->ID;
    }
    else {
      $meta = null;
    }
    return $meta;
  }

  function send_post_json() {
    $code = CODE_ERROR;
    $id = intval($_GET['id']);
    if($id) {
      $q = new WP_Query(array('post_type'=> POST_ID, 'p'=> $id));
      if($q->post_count) {
        $code = CODE_OK;
        $post = post_to_user_post($q->posts[0], true);
        if($_GET['thumbnailUrl']=='true') {
          if($post['thumbnail']['type']=='mediaImage') {
            $post['thumbnail']['data']['mediaImage'] = get_media_image(intval($post['thumbnail']['data']['post_ID']));
          }
        }
        wp_send_json(['code'=> $code,'post'=> $post]);
      }
      else {
        $code = CODE_NOT_FOUND;
      }
    }
    send_json_finish($code);
  }

  add_action('wp_ajax_fb3d_send_post', '\iberezansky\fb3d\send_post_json');
  add_action('wp_ajax_nopriv_fb3d_send_post', '\iberezansky\fb3d\send_post_json');

  function send_posts_in_json() {
    $ids = [];
    if(isset($_GET['tus'])) {
      $ids = array_merge($ids, $_GET['tus']);
    }
    if(isset($_GET['ntus'])) {
      $ids = array_merge($ids, $_GET['ntus']);
    }
    $posts = [];
    $q = new WP_Query(array('post_type'=> POST_ID, 'post__in'=> $ids, 'posts_per_page'=>-1));
    for($i = 0; $i<$q->post_count; ++$i) {
      $post = post_to_user_post($q->posts[$i], true);
      if(isset($_GET['tus']) && in_array(strval($post['ID']), $_GET['tus'])) {
        if($post['thumbnail']['type']=='mediaImage') {
          $post['thumbnail']['data']['mediaImage'] = get_media_image(intval($post['thumbnail']['data']['post_ID']));
        }
      }
      array_push($posts, $post);
    }
    wp_send_json(['code'=> CODE_OK,'posts'=> $posts]);
  }

  add_action('wp_ajax_fb3d_send_posts_in', '\iberezansky\fb3d\send_posts_in_json');
  add_action('wp_ajax_nopriv_fb3d_send_posts_in', '\iberezansky\fb3d\send_posts_in_json');

  function send_post_pages_json() {
    $code = CODE_ERROR;
    $id = intval($_GET['id']);
    if($id) {
      $pages = select_post_pages_by_page_post_ID($id);
      $code = CODE_OK;
      wp_send_json(['code'=> $code, 'pages'=> $pages]);
    }
    send_json_finish($code);
  }

  add_action('wp_ajax_fb3d_send_post_pages', '\iberezansky\fb3d\send_post_pages_json');
  add_action('wp_ajax_nopriv_fb3d_send_post_pages', '\iberezansky\fb3d\send_post_pages_json');

  function send_posts_in_pages_json() {
    $ids = $_GET['ids'];
    $pages = select_post_pages_by_page_posts_IDs_in($ids);
    wp_send_json(['code'=> CODE_OK, 'pages'=> $pages]);
  }

  add_action('wp_ajax_fb3d_send_posts_in_pages', '\iberezansky\fb3d\send_posts_in_pages_json');
  add_action('wp_ajax_nopriv_fb3d_send_posts_in_pages', '\iberezansky\fb3d\send_posts_in_pages_json');

  function send_posts_in_first_page_json() {
    $ids = $_GET['ids'];
    $pages = select_post_first_page_by_page_post_IDs_in($ids);
    wp_send_json(['code'=> CODE_OK, 'pages'=> $pages]);
  }

  add_action('wp_ajax_fb3d_send_posts_in_first_page', '\iberezansky\fb3d\send_posts_in_first_page_json');
  add_action('wp_ajax_nopriv_fb3d_send_posts_in_first_page', '\iberezansky\fb3d\send_posts_in_first_page_json');

  function send_post_first_page_json() {
    $code = CODE_ERROR;
    $id = intval($_GET['id']);
    if($id) {
      $page = select_post_first_page_by_page_post_ID($id);
      $code = CODE_OK;
      wp_send_json(['code'=> $code, 'page'=> $page]);
    }
    send_json_finish($code);
  }

  add_action('wp_ajax_fb3d_send_post_first_page', '\iberezansky\fb3d\send_post_first_page_json');
  add_action('wp_ajax_nopriv_fb3d_send_post_first_page', '\iberezansky\fb3d\send_post_first_page_json');

  function send_media_image_json() {
    $code = CODE_ERROR;
    $id = intval($_GET['id']);
    if($id) {
      $meta = get_media_image($id);
      if($meta) {
        $code = CODE_OK;
        wp_send_json(['code'=> $code, 'mediaImage'=> $meta]);
      }
      else {
        $code = CODE_NOT_FOUND;
      }
    }
    send_json_finish($code);
  }

  add_action('wp_ajax_fb3d_send_media_image', '\iberezansky\fb3d\send_media_image_json');
  add_action('wp_ajax_nopriv_fb3d_send_media_image', '\iberezansky\fb3d\send_media_image_json');

  function send_book_control_props_json() {
    $props = get_option(META_PREFIX.'book_control_props');
    $props = unserialize($props);
    $props = $props? $props: [];
    wp_send_json(['code'=> CODE_OK, 'props'=> $props]);
  }

  add_action('wp_ajax_fb3d_send_book_control_props', '\iberezansky\fb3d\send_book_control_props_json');
  add_action('wp_ajax_nopriv_fb3d_send_book_control_props', '\iberezansky\fb3d\send_book_control_props_json');

?>
