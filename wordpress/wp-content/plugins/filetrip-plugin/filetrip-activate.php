<?php
  
 /**
  * Activation hook:
  *
  * Bail if version is less than 3.3, set default settings
  */
function activate_filetrip_plugin() {
    global $wp_version;
    if ( version_compare( $wp_version, '3.3', '<' ) ) {
        wp_die( __( 'Frontend Uploader requires WordPress 3.3 or newer. Please upgrade.', 'frontend-uploader' ) );
    }
    
    // Reset option
    if( version_compare( Filetrip_Constants::VERSION, '1.3.5.5', '>=' ) )
    {
      // Delete old folder
      $dirPath = get_option( 'filetrip_bkp_default_path', false );
      if($dirPath)
      {
        filetripDeleteDir($dirPath);
      }
      delete_option( 'filetrip_bkp_default_path' );
    }
}

function filetripDeleteDir($dirPath) 
{
    if (! is_dir($dirPath)) {
        // throw new InvalidArgumentException("$dirPath must be a directory");
        return;
    }
    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
        $dirPath .= '/';
    }
    $files = glob($dirPath . '*', GLOB_MARK);
    foreach ($files as $file) {
        if (is_dir($file)) {
            self::deleteDir($file);
        } else {
            unlink($file);
        }
    }
    @rmdir($dirPath);
}

  
  function itf_install_filetrip_database()
  {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    $create_record_table_script = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix . Filetrip_Constants::RECORD_TABLE_NAME." (
        id BIGINT(20) NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) NOT NULL ,
        att_id BIGINT(20) UNIQUE NOT NULL ,
        arfaly_uploader_id BIGINT(20) NOT NULL ,
        att_size VARCHAR(45) DEFAULT '' NULL ,
        time datetime DEFAULT '0000-00-00 00:00:00' ,
        dropbox_sent TINYINT(1) NULL ,
        drive_sent TINYINT(1) NULL ,
        ftp_sent TINYINT(1) NULL ,
        dropbox_cdn VARCHAR(255) NULL ,
        drive_cdn VARCHAR(255) NULL ,
        short_url VARCHAR(45) NULL ,
        PRIMARY KEY (id),
        UNIQUE INDEX att_id_UNIQUE (att_id ASC)) 
        $charset_collate;";

    $create_metadata_table_script = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix . Filetrip_Constants::METADATA_TABLE_NAME." (
        id BIGINT(20) NOT NULL AUTO_INCREMENT,
        att_id BIGINT(20) NOT NULL ,
        meta_key VARCHAR(45) DEFAULT '' NULL ,
        meta_val VARCHAR(100) NULL ,
        PRIMARY KEY (id)) 
        $charset_collate;";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' ); 
    dbDelta( $create_record_table_script ); 
    dbDelta( $create_metadata_table_script ); 
  }
  
  function itf_uninstall_arfaly_press_database()
  {
    //drop a custom db table
    global $wpdb;
    $wpdb->query( "DROP TABLE IF EXISTS ". $wpdb->prefix .Filetrip_Constants::RECORD_TABLE_NAME );
    $wpdb->query( "DROP TABLE IF EXISTS ". $wpdb->prefix .Filetrip_Constants::METADATA_TABLE_NAME );
  }
  
?>
