<?php
defined('ABSPATH') or die("You can't access this file directly.");

/**
 * Knowledgebase Activation/Deactivation function.
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Fired for each blog when the plugin is activated.
 *
 *
 * @param boolean $network_wide True if WPMU superadmin uses
 *                              "Network Activate" action, false if
 *                              WPMU is disabled or plugin is
 *                              activated on an individual blog.
 */
if ( ! function_exists( 'kbx_plugin_activate' ) ) {
	function kbx_plugin_activate( $network_wide ) {
		global $wpdb;
		// Get all blogs in the network and activate plugin on each one.
		if ( is_multisite() && $network_wide ) {
			$blog_ids = $wpdb->get_col( "
				SELECT blog_id FROM $wpdb->blogs
				WHERE archived = '0' AND spam = '0' AND deleted = '0'
			" );

			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				kbx_single_activate();
				restore_current_blog();
			}
		} else {
			kbx_single_activate();
		}
	}
	register_activation_hook( KBX_PLUGIN_FILE, 'kbx_plugin_activate' );
}

if ( ! function_exists( 'kbx_bot_fulltext_table_alter' ) ) {
	function kbx_bot_fulltext_table_alter(){
	    global $wpdb;
	    // full text index table create
	    $wpdb->query('ALTER TABLE '.$wpdb->posts.'ADD FULLTEXT (post_title, post_content)');
	}
}

/**
 * Runs on Plugin activation.
 */
if ( ! function_exists( 'kbx_single_activate' ) ) {
	function kbx_single_activate() {

		// Register types to register the rewrite rules.
		kbx_register_settings();
		kbx_register_post_type();

		// Then flush them.
		global $wp_rewrite;

		$wp_rewrite->init();

	}
}


/**
 * Fired when a new site is activated with a WPMU environment.
 *
 * @param int $blog_id ID of the new blog.
 */
if ( ! function_exists( 'kbx_activate_new_site' ) ) {
	function kbx_activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		kbx_single_activate();
		restore_current_blog();

	}
}

add_action( 'wpmu_new_blog', 'kbx_activate_new_site' );


/**
 * Runs on Plugin deactivation.
 *
 * @param bool $network_wide Network wide flag.
 */
if ( ! function_exists( 'kbx_plugin_deactivate' ) ) {
	function kbx_plugin_deactivate( $network_wide ) {

		global $wpdb;

		if ( is_multisite() && $network_wide ) {

			// Get all blogs in the network and activate plugin on each one.
			$blog_ids = $wpdb->get_col( "
				SELECT blog_id FROM $wpdb->blogs
				WHERE archived = '0' AND spam = '0' AND deleted = '0'
			" );

			foreach ( $blog_ids as $blog_id ) 
			{
				switch_to_blog( $blog_id );
				global $wp_rewrite;
				$wp_rewrite->init();
				flush_rewrite_rules();
			}

			// Switch back to the current blog.
			restore_current_blog();

		}

		flush_rewrite_rules();
	}
	register_deactivation_hook( KBX_PLUGIN_FILE, 'kbx_plugin_deactivate' );
}


