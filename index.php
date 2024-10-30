<?php
/*
Plugin Name: Bolt Plugin
Plugin URI: https://boltmedia.co/
Description: WordPress plugin which adds bolt media integration feature
Version: 1.5
Author: bolt
Author URI: https://boltmedia.co/
License: GPLv2 or later
*/
define( 'PLUGIN_DIR', dirname(__FILE__).'/' );

register_activation_hook( __FILE__, 'bolt_plugin_activate' );

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/screen.php' );
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
require dirname( __FILE__ ) . '/lib/class-wp-bolt-admin.php';
require dirname( __FILE__ ) . '/lib/class-wp-bolt-listtable.php';
require dirname( __FILE__ ) . '/lib/class-wp-bolt-rest-client.php';
require dirname( __FILE__ ) . '/lib/function-shortcode.php';
add_action( 'admin_menu', array( 'Bolt_Admin', 'register' ) );

function bolt_plugin_activate(){
    // Require parent plugin
    if ( ! is_plugin_active( 'rest-api/plugin.php' ) and is_plugin_active( 'rest-api-oauth1/oauth-server.php' ) and current_user_can( 'activate_plugins' ) ) {
      //show notice msg
      add_action('admin_notices', 'oauth_not_loaded');
    }
}

add_action('plugins_loaded', 'bolt_plugin_init');

function bolt_plugin_init() {
  if ( !class_exists('WP_REST_OAuth1') ) {
    add_action('admin_notices', 'oauth_not_loaded');
  }
}



function oauth_not_loaded() {
    printf(
      '<div class="error"><p>%s</p></div>',
      __('To enable bolt media integration you must install the <a href="https://wordpress.org/plugins-wp/rest-api/">Wordpress REST API</a> and the <a href="https://wordpress.org/plugins-wp/rest-api-oauth1/">Wordpress Oauth Server</a>')
    );
}




