<?php
  namespace iberezansky\fb3d;

  function is_page_ID($page_ID) {
    return isset($page_ID) && $page_ID!=0;
  }

  function pages2ids($pages) {
    $ids = array();
    foreach($pages as $page) {
      if(is_page_ID($page['page_ID'])) {
        array_push($ids, $page['page_ID']);
      }
    }
    return $ids;
  }

  function page_description() {
    return array(
      'page_ID'=> array('val'=> 0, 'type'=> '%ai'),
      'page_post_ID'=> array('val'=> 0, 'type'=> '%d'),
      'page_title'=> array('val'=> '', 'type'=> '%s'),
      'page_source_type'=> array('val'=> '', 'type'=> '%s'),
      'page_source_data'=> array('val'=> array(), 'type'=> '%a'),
      'page_thumbnail_type'=> array('val'=> '', 'type'=> '%s'),
      'page_thumbnail_data'=> array('val'=> array(), 'type'=> '%a'),
      'page_meta_data'=> array('val'=> array('css_layer'=> array('css'=> '', 'html'=> '', 'js'=> '')), 'type'=> '%a'),
      'page_number'=> array('val'=> 0, 'type'=> '%d')
    );
  }

  function serialize_page_records($records, $fields=null) {
    $desc = page_description();
    if(!$fields) {
      $fields = array_keys($records);
    }
    $serialized = array('records'=> array(), 'types'=> array());
    foreach($fields as $name) {
      $d = $desc[$name];
      if($d['type']=='%a') {
        $serialized['records'][$name] = serialize(isset($records[$name])? $records[$name]: $d['val']);
        $serialized['types'][$name] = '%s';
      }
      else if($d['type']=='%a') {
        // don't serialize auto generated fields
      }
      else {
        $serialized['records'][$name] = isset($records[$name])? $records[$name]: $d['val'];
        $serialized['types'][$name] = $d['type'];
      }
    }
    return $serialized;
  }

  function stripslashesAray($a) {
    $res = array();
    foreach($a as $k=> $v) {
      if(is_array($v)) {
        $res[$k] = stripslashesAray($v);
      }
      else if(is_string($v)) {
        $res[$k] = stripslashes($v);
      }
      else {
        $res[$k] = $v;
      }
    }
    return $res;
  }

  function unserialize_page_records($records) {
    $desc = page_description();
    $fields = array_keys($desc);
    $unserialized = array();
    foreach($fields as $name) {
      $d = $desc[$name];
      if($d['type']=='%a') {
        $un = unserialize($records[$name]);
        $unserialized[$name] = stripslashesAray($un===false? $d['val']: $un);
      }
      else {
        $unserialized[$name] = $records[$name];
      }
    }
    return $unserialized;
  }

  function serialize_page($page) {
    return serialize_page_records($page, array_keys(page_description()));
  }

  function delete_post_pages($ids) {
    global $wpdb;
    $table = TABLE_NAME;
    $wpdb->query(sprintf("
      DELETE FROM $table
		  WHERE page_ID IN %s
    ", a_to_sql_list($ids)));
  }

  function insert_post_pages($pages) {
    global $wpdb;
    $table = TABLE_NAME;
    foreach($pages as $page) {
      $serialized = serialize_page($page);
      $wpdb->insert($table, $serialized['records']);//, $serialized['types']
    }
  }

  function update_post_pages($pages) {
    global $wpdb;
    $table = TABLE_NAME;
    foreach($pages as $page) {
      $serialized = serialize_page_records($page);
      $wpdb->update($table, $serialized['records'], array('page_ID'=> $page['page_ID']));//, $serialized['types'], array('%d')
    }
  }

  function select_post_pages($where) {
    global $wpdb;
    $table = TABLE_NAME;
    $serialized_pages = $wpdb->get_results("
      SELECT *
      FROM $table
      WHERE $where
    ", ARRAY_A);
    $pages = array();
    foreach($serialized_pages as $serialized_page) {
      array_push($pages, unserialize_page_records($serialized_page));
    }
    return $pages;
  }

  function select_post_pages_by_page_post_ID($page_post_ID) {
    global $wpdb;
    return select_post_pages($wpdb->prepare('page_post_ID = %d', $page_post_ID));
  }

  function a_to_sql_list($a) {
    $a = array_map(function($v) {
        return "'" . esc_sql($v) . "'";
    }, $a);
    $a = implode(',', $a);
    return '('.($a===''? "'0'": $a).')';
  }

  function select_post_pages_by_page_posts_IDs_in($ids) {
    return select_post_pages('page_post_ID IN '.a_to_sql_list($ids));
  }

  function select_post_first_page_by_page_post_IDs_in($ids) {
    global $wpdb;
    $res = select_post_pages('page_number = 0 AND page_post_ID IN '.a_to_sql_list($ids));
    return $res;
  }

  function select_post_first_page_by_page_post_ID($page_post_ID) {
    global $wpdb;
    $res = select_post_pages($wpdb->prepare('page_post_ID = %d AND page_number = 0', $page_post_ID));
    return isset($res[0])? $res[0]: NULL;
  }

  // function select_post_pages_in_page_IDs($page_IDs) {
  //   global $wpdb;
  //   return select_post_pages($wpdb->prepare('page_ID IN %s', a_to_sql_list($page_IDs)));
  // }

  function select_post_pages_by_page_post_ID_and_not_in_page_IDs($page_post_ID, $page_IDs) {
    global $wpdb;
    if(count($page_IDs)) {
      $q = sprintf('page_post_ID = %d AND page_ID NOT IN %s', $page_post_ID, a_to_sql_list($page_IDs));
    }
    else {
      $q = sprintf('page_post_ID = %d', $page_post_ID);
    }
    return select_post_pages($q);
  }

  function divide_to_updated_and_inserted($pages) {
    $res = array('inserted'=> array(), 'updated'=> array());
    foreach($pages as $page) {
      if(is_page_ID($page['page_ID'])) {
        array_push($res['updated'], $page);
      }
      else {
        array_push($res['inserted'], $page);
      }
    }
    return $res;
  }

  function set_post_pages($page_post_ID, $pages) {
    $ids = pages2ids($pages);
    $deleted = select_post_pages_by_page_post_ID_and_not_in_page_IDs($page_post_ID, $ids);
    delete_post_pages(pages2ids($deleted));
    $res = divide_to_updated_and_inserted($pages);
    update_post_pages($res['updated']);
    insert_post_pages($res['inserted']);
  }

?>
