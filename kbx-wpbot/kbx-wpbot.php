<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if ( ! defined( 'kbx_adv_bot_plugin_dir_path' ) ) {
  define('kbx_adv_bot_plugin_dir_path', plugin_dir_path(__FILE__));
}

if ( ! defined( 'kbx_adv_bot_plugin_url' ) ) {
  define('kbx_adv_bot_plugin_url', plugin_dir_url(__FILE__));
}

if ( ! defined( 'KBX_WP_CHATBOT' ) ) {
  define('KBX_WP_CHATBOT', '1');
}

//var_dump(get_option('kbx_floating_search_bot'));wp_die();
if ( get_option('kbx_floating_search_bot') == 'wp-boat' ) {
	require_once('chatbot/qcld-wpwbot.php');
  // extended-search-addon plugin deactivated by delowar/ 09-27-24
	//require_once('extended-search-addon/wpbot-posttype-search-addon.php');
}

function remove_kbx_bot_page(){
	global $pagenow;
	if( $pagenow == 'edit.php' && isset( $_GET['page'] ) && $_GET['page'] == 'kbx-bot' ){
		//wp_redirect( admin_url( 'admin.php?page=wpbot' ), 301 );
        //exit;
	}
	//remove_submenu_page('edit.php?post_type=kbx_knowledgebase', 'kbx-bot');
	remove_submenu_page('wbpt-posttypesetting-page', 'extended-search-help-license');
	//remove_submenu_page('wbca-chat-page', 'qc-wplive-chat-help-license');
	//remove_submenu_page('wpbot', 'wpbot_license_page');
	remove_submenu_page('wbpt-posttypesetting-page', 'wbpt-posttypesetting-page');
	//remove_menu_page('wbpt-posttypesetting-page');

}
add_action('admin_init', 'remove_kbx_bot_page', 1000 );

// add_action('admin_menu', 'wpdocs_register_my_custom_submenu_page', 15);
 
// function wpdocs_register_my_custom_submenu_page() {

//     add_submenu_page( 
//         'chatbot',   
//         'Extended Search',
//         'Extended Search',
//         'manage_options',
//         'wbpt-posttypesetting-page',
//         ''
//     );
// }



/**
 * Submenu filter function. Tested with Wordpress 4.1.1
 * Sort and order submenu positions to match your custom order.
 *
 * @author Hendrik Schuster <contact@deviantdev.com>
 */
function kbx_wp_bot_order_index_catalog_menu_page( $menu_ord ){

  global $submenu;

  if( isset($submenu['wpbot']) ){
    $arr = array();

    if( isset($submenu['wpbot'][0]) ){
      $arr[] = $submenu['wpbot'][0];
    }
    if( isset($submenu['wpbot'][1]) ){
      $arr[] = $submenu['wpbot'][1];
    }
    if( isset($submenu['wpbot'][2]) ){
      $arr[] = $submenu['wpbot'][2];
    }
    if( isset($submenu['wpbot'][5]) ){
      $arr[] = $submenu['wpbot'][5];
    }

    $submenu['wpbot'] = $arr;
  }

  return $submenu;

}


// add the filter to wordpress
add_filter( 'custom_menu_order', 'kbx_wp_bot_order_index_catalog_menu_page' );