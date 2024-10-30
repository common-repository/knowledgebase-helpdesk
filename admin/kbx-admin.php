<?php
defined('ABSPATH') or die("You can't access this file directly.");
/**
 * The admin-specific functionality of the plugin.
 */


/**
 * Creates the admin submenu pages under the Downloads menu and assigns their
 * links to global variables
 *
 * @global $kbx_settings_page
 * @return void
 */
if ( ! function_exists( 'kbx_add_admin_pages_links' ) ) {
	function kbx_add_admin_pages_links() {

		global $kbx_settings_page;

		$kbx_settings_page = add_submenu_page( 'edit.php?post_type=kbx_knowledgebase', __( 'Settings', 'kbx-qc' ), __( 'Settings', 'kbx-qc' ), 'manage_options', 'kbx-settings', 'kbx_options_page' );

		// Load the settings contextual help.
		//add_action( "load-$kbx_settings_page", 'kbx_settings_help' );
	}
	add_action( 'admin_menu', 'kbx_add_admin_pages_links' );
}



/**
 * Customise the taxonomy columns.
 * 
 * @param  array $columns Columns in the admin view.
 * @return array Updated columns.
 */
if ( ! function_exists( 'kbx_tax_columns' ) ) {
	function kbx_tax_columns( $columns ) {

		// Remove the description column.
		unset( $columns['description'] );

		$new_columns = array(
			'tax_id' => 'ID',
		);

		return array_merge( $columns, $new_columns );
	}
	add_filter( 'manage_edit-kbx_category_columns', 'kbx_tax_columns' );
	add_filter( 'manage_edit-kbx_category_sortable_columns', 'kbx_tax_columns' );
	add_filter( 'manage_edit-kbx_tag_columns', 'kbx_tax_columns' );
	add_filter( 'manage_edit-kbx_tag_sortable_columns', 'kbx_tax_columns' );
}


/**
 * Add taxonomy ID to the admin column.
 *
 * @since 1.0.0
 *
 * @param  string     $value Deprecated.
 * @param  string     $name  Name of the column.
 * @param  int|string $id    Category ID.
 * @return int|string
 */
if ( ! function_exists( 'kbx_tax_id' ) ) {
	function kbx_tax_id( $value, $name, $id ) {
		return 'tax_id' === $name ? $id : $value;
	}
	add_filter( 'manage_kbx_category_custom_column', 'kbx_tax_id', 10, 3 );
	add_filter( 'manage_kbx_tag_custom_column', 'kbx_tax_id', 10, 3 );
}


/**
 * Add rating links to the admin dashboard
 *
 * @param string $footer_text The existing footer text.
 * @return string Updated Footer text
 */
if ( ! function_exists( 'kbx_admin_footer' ) ) {
	function kbx_admin_footer( $footer_text ) {

		if ( get_current_screen()->post_type === 'kbx_knowledgebase' ) {

			$text = sprintf( __( 'Thank you for using <a href="%1$s" target="_blank">Knowledgebase</a>! Please <a href="%2$s" target="_blank">rate us</a> on <a href="%2$s" target="_blank">WordPress.org</a>', 'kbx-qc' ),
				'',
				''
			);

			return str_replace( '</span>', '', $footer_text ) . ' | ' . $text . '</span>';

		} else {

			return $footer_text;

		}
	}

	add_filter( 'admin_footer_text', 'kbx_admin_footer' );

}

/**
 * Filters Admin Notices to add a notice when the settings are not saved.
 *
 * @since 1.2.0
 * @return void
 */
if ( ! function_exists( 'kbx_admin_notices' ) ) {
	function kbx_admin_notices() {

		$kbslug = kbx_get_option( 'kb_slug', 'not-set-yet' );
		$catslug = kbx_get_option( 'category_slug', 'not-set-yet' );
		$tagslug = kbx_get_option( 'tag_slug', 'not-set-yet' );

		// Only add the notice if the user is an admin.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Only add the notice if the settings cannot be found.
		if ( 'not-set-yet' === $kbslug || 'not-set-yet' === $catslug || 'not-set-yet' === $tagslug ) {
		?>

		<div class="updated">
			<p><?php printf( __( 'Knowledgebase settings for the slug have not been registered. Please visit the <a href="%s">admin page</a> to update and save the options.', 'kbx-qc' ), esc_url( admin_url( 'edit.php?post_type=kbx_knowledgebase&page=kbx-settings' ) ) ); ?></p>
		</div>

		<?php
		}
	}
	add_action( 'admin_notices', 'kbx_admin_notices' );
}
/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 * @param string|array $var
 * @return string|array
 */
if ( ! function_exists( 'kbx_clean' ) ) {
	function kbx_clean( $var ) {
	    if ( is_array( $var ) ) {
	        return array_map( 'kbx_clean', $var );
	    } else {
	        return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	    }
	}
}
/**
 * Wrapper for set_time_limit to see if it is enabled.
 * @since 2.6.0
 *
 * @param int $limit
 */
if ( ! function_exists( 'kbx_set_time_limit' ) ) {
	function kbx_set_time_limit( $limit = 0 ) {
	    if ( function_exists( 'set_time_limit' ) && false === strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) && ! ini_get( 'safe_mode' ) ) {
	        @set_time_limit( $limit );
	    }
	}
}
