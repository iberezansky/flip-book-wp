<?php
  namespace iberezansky\fb3d;

  function install() {
    global $wpdb;
    $table = TABLE_NAME;
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table (
      page_ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
      page_post_ID bigint(20) UNSIGNED NOT NULL,
      page_title text NOT NULL,
      page_source_type varchar(20) NOT NULL,
      page_source_data longtext NOT NULL,
      page_thumbnail_type varchar(20) NOT NULL,
      page_thumbnail_data longtext NOT NULL,
      page_meta_data longtext NOT NULL,
      page_number int(11) NOT NULL,
      PRIMARY KEY  (page_ID),
      KEY page_post_ID (page_post_ID ASC),
      KEY page_source_type (page_source_type ASC),
      KEY page_thumbnail_type (page_thumbnail_type ASC)
    ) $charset_collate;";

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option($table.'_version', DBVERSION);

    register_post_type();
    flush_rewrite_rules(false);
  }

  register_activation_hook(MAIN, '\iberezansky\fb3d\install');
?>
