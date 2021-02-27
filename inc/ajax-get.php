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
      $meta = array();
    }
    $def = get_post_data($post->ID, array());
    $def = $def['3dfb']['post'];
    return array(
      'ID'=> $post->ID,
      'title'=> $post->post_title,
      'type'=> isset($meta[META_PREFIX.'type'][0])? $meta[META_PREFIX.'type'][0]: 'pdf',
      'ready_function'=> isset($meta[META_PREFIX.'ready_function'][0])? $meta[META_PREFIX.'ready_function'][0]: '',
      'book_style'=> isset($meta[META_PREFIX.'book_style'][0])? $meta[META_PREFIX.'book_style'][0]: 'volume',
      'data'=> unserialize(isset($meta[META_PREFIX.'data'][0])? $meta[META_PREFIX.'data'][0]: serialize($def['data'])),
      'thumbnail'=> unserialize(isset($meta[META_PREFIX.'thumbnail'][0])? $meta[META_PREFIX.'thumbnail'][0]: serialize($def['thumbnail'])),
      'props'=> unserialize(isset($meta[META_PREFIX.'props'][0])? $meta[META_PREFIX.'props'][0]: serialize($def['props'])),
      'controlProps'=> unserialize(isset($meta[META_PREFIX.'controlProps'][0])? $meta[META_PREFIX.'controlProps'][0]: serialize($def['controlProps'])),
      'autoThumbnail'=> get_auto_thumbnail_url($post->ID),
      'post_name'=> $post->post_name,
      'post_type'=> $post->post_type
    );
  }

  function send_taxonomy_terms_json() {
    wp_send_json(array('code'=> CODE_OK,'taxonomies'=> get_terms(POST_ID.'-category', array('hide_empty' => false))));
  }

  add_action('wp_ajax_fb3d_send_taxonomy_terms', '\iberezansky\fb3d\send_taxonomy_terms_json');

  function send_posts_json() {
    $q = new WP_Query(array('post_type'=> POST_ID, 'posts_per_page'=>-1));
    $r = array();
    for($i=0; $i<$q->post_count; ++$i) {
      array_push($r, post_to_user_post($q->posts[$i], false));
    }
    wp_send_json(array('code'=> CODE_OK,'posts'=> $r));
  }

  add_action('wp_ajax_fb3d_send_posts', '\iberezansky\fb3d\send_posts_json');
  add_action('wp_ajax_nopriv_fb3d_send_posts', '\iberezansky\fb3d\send_posts_json');

  // function send_template_html() {
  //   $template_url = isset($_GET['template'])? $_GET['template']: ASSETS_TEMPLATES.'default-book-view.php';
  //   include(template_url_to_path($template_url));
  //   exit(0);
  // }
  //
  // add_action('wp_ajax_fb3d_send_template_html', '\iberezansky\fb3d\send_template_html');
  // add_action('wp_ajax_nopriv_fb3d_send_template_html', '\iberezansky\fb3d\send_template_html');

  function get_media_image($id) {
    $q = new WP_Query(array(
      'p'=> $id,
      'post_type'=> 'attachment',
      'post_status'=> 'inherit',
      'post_mime_type'=> array(
        'image/gif',
        'image/jpeg',
        'image/pjpeg',
        'image/png',
        'image/svg+xml'
      )
    ));
    if($q->post_count) {
      $post = $q->posts[0];
      $meta = wp_get_attachment_metadata($post->ID);
      $meta['parent_url'] = substr($post->guid, 0, strrpos($post->guid, '/')+1);
      $meta['id'] = $post->ID;
    }
    else {
      $meta = NULL;
    }
    return $meta;
  }


  function client_post($id, $thumbnailUrl) {
    $post = NULL;
    $id = intval($id);
    if($id) {
      $q = new WP_Query(['post_type'=> POST_ID, 'p'=> $id]);
      if($q->post_count) {
        $post = post_to_user_post($q->posts[0], true);
        if($thumbnailUrl) {
          if($post['thumbnail']['type']=='mediaImage') {
            $post['thumbnail']['data']['mediaImage'] = get_media_image(intval($post['thumbnail']['data']['post_ID']));
          }
        }
      }
    }
    return $post;
  }

  function send_post_json() {
    $post = client_post($_GET['id'], aa($_GET, 'thumbnailUrl', 'false')==='true');
    wp_send_json(['code'=> $post!==NULL? CODE_OK: CODE_NOT_FOUND, 'post'=> $post]);
  }

  add_action('wp_ajax_fb3d_send_post', '\iberezansky\fb3d\send_post_json');
  add_action('wp_ajax_nopriv_fb3d_send_post', '\iberezansky\fb3d\send_post_json');

  function client_posts_in($ids_mis, $ids) {
    $ids = array_merge($ids_mis, $ids);
    $posts = [];
    if(count($ids)) {
      $q = new WP_Query(array('post_type'=> POST_ID, 'post__in'=> $ids, 'posts_per_page'=>-1));
      for($i = 0; $i<$q->post_count; ++$i) {
        $post = post_to_user_post($q->posts[$i], true);
        if(in_array(strval($post['ID']), $ids_mis)) {
          if($post['thumbnail']['type']=='mediaImage') {
            $post['thumbnail']['data']['mediaImage'] = get_media_image(intval($post['thumbnail']['data']['post_ID']));
          }
        }
        array_push($posts, $post);
      }
    }
    return $posts;
  }

  function send_posts_in_json() {
    $posts = client_posts_in(aa($_GET, 'tus'), aa($_GET, 'ntus'));
    wp_send_json(array('code'=> CODE_OK,'posts'=> $posts));
  }

  add_action('wp_ajax_fb3d_send_posts_in', '\iberezansky\fb3d\send_posts_in_json');
  add_action('wp_ajax_nopriv_fb3d_send_posts_in', '\iberezansky\fb3d\send_posts_in_json');

  function client_post_pages($id) {
    $id = intval($id);
    $pages = NULL;
    if($id) {
      $pages = select_post_pages_by_page_post_ID($id);
    }
    return $pages;
  }

  function send_post_pages_json() {
    $pages = client_post_pages($_GET['id']);
    wp_send_json(['code'=> $pages!==NULL? CODE_OK: CODE_ERROR, 'pages'=> $pages]);
  }

  add_action('wp_ajax_fb3d_send_post_pages', '\iberezansky\fb3d\send_post_pages_json');
  add_action('wp_ajax_nopriv_fb3d_send_post_pages', '\iberezansky\fb3d\send_post_pages_json');

  function client_posts_in_pages($ids) {
    return select_post_pages_by_page_posts_IDs_in($ids);
  }

  function send_posts_in_pages_json() {
    $pages = client_posts_in_pages(aa($_GET, 'ids'));
    wp_send_json(array('code'=> CODE_OK, 'pages'=> $pages));
  }

  add_action('wp_ajax_fb3d_send_posts_in_pages', '\iberezansky\fb3d\send_posts_in_pages_json');
  add_action('wp_ajax_nopriv_fb3d_send_posts_in_pages', '\iberezansky\fb3d\send_posts_in_pages_json');

  function client_posts_in_first_page($ids) {
    return select_post_first_page_by_page_post_IDs_in($ids);
  }

  function send_posts_in_first_page_json() {
    $pages = client_posts_in_first_page(aa($_GET, 'ids'));
    wp_send_json(array('code'=> CODE_OK, 'pages'=> $pages));
  }

  add_action('wp_ajax_fb3d_send_posts_in_first_page', '\iberezansky\fb3d\send_posts_in_first_page_json');
  add_action('wp_ajax_nopriv_fb3d_send_posts_in_first_page', '\iberezansky\fb3d\send_posts_in_first_page_json');

  function client_post_first_page($id) {
    $page = NULL;
    $id = intval($id);
    if($id) {
      $page = select_post_first_page_by_page_post_ID($id);
    }
    return $page;
  }

  function send_post_first_page_json() {
    $page = client_post_first_page($id);
    wp_send_json(['code'=> $page!==NULL? CODE_OK: CODE_ERROR, 'page'=> $page]);
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
        wp_send_json(array('code'=> $code, 'mediaImage'=> $meta));
      }
      else {
        $code = CODE_NOT_FOUND;
      }
    }
    send_json_finish($code);
  }

  add_action('wp_ajax_fb3d_send_media_image', '\iberezansky\fb3d\send_media_image_json');
  add_action('wp_ajax_nopriv_fb3d_send_media_image', '\iberezansky\fb3d\send_media_image_json');

  function client_book_control_props() {
    $props = get_option(META_PREFIX.'book_control_props');
    $props = unserialize($props);
    $props = $props? $props: [];
    return $props;
  }

  function send_book_control_props_json() {
    $props = client_book_control_props();
    wp_send_json(array('code'=> CODE_OK, 'props'=> $props));
  }

  add_action('wp_ajax_fb3d_send_book_control_props', '\iberezansky\fb3d\send_book_control_props_json');
  add_action('wp_ajax_nopriv_fb3d_send_book_control_props', '\iberezansky\fb3d\send_book_control_props_json');

?>
