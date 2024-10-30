<?php
defined('ABSPATH') or die("You can't access this file directly.");
/**
 * 
 * Knowledgebase Custom Post Type.
 * 
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Register Knowledgebase Post Type.
 */
if ( ! function_exists( 'kbx_register_post_type' ) ) {
	function kbx_register_post_type() {

		$slug 		= kbx_get_option( 'kb_slug', 'knowledgebase' );
		$archives 	= defined( 'KBX_DISABLE_ARCHIVE' ) && KBX_DISABLE_ARCHIVE ? false : $slug;
		$rewrite  	= defined( 'KBX_DISABLE_REWRITE' ) && KBX_DISABLE_REWRITE ? false : array( 'slug' => $slug, 'with_front' => false );

		$ptlabels = array(
			'name'               => _x( 'Knowledgebase', 'Post Type General Name', 'kbx-qc' ),
			'singular_name'      => _x( 'Knowledgebase', 'Post Type Singular Name', 'kbx-qc' ),
			'menu_name'          => __( 'KBx', 'kbx-qc' ),
			'name_admin_bar'     => __( 'Knowledgebase Article', 'kbx-qc' ),
			'parent_item_colon'  => __( 'Parent Article', 'kbx-qc' ),
			'all_items'          => __( 'All Articles', 'kbx-qc' ),
			'add_new_item'       => __( 'Add New Article', 'kbx-qc' ),
			'add_new'            => __( 'Add New Article', 'kbx-qc' ),
			'new_item'           => __( 'New Article', 'kbx-qc' ),
			'edit_item'          => __( 'Edit Article', 'kbx-qc' ),
			'update_item'        => __( 'Update Article', 'kbx-qc' ),
			'view_item'          => __( 'View Article', 'kbx-qc' ),
			'search_items'       => __( 'Search Article', 'kbx-qc' ),
			'not_found'          => __( 'Not found', 'kbx-qc' ),
			'not_found_in_trash' => __( 'Not found in Trash', 'kbx-qc' ),
		);

		/**
		 * Filter the labels of the post type.
		 *
		 * @param array $ptlabels Post type lables
		 */
		$ptlabels = apply_filters( 'kbx_post_type_labels', $ptlabels );

		$ptargs = array(
			'label'              => __( 'kbx_knowledgebase', 'kbx-qc' ),
			'description'        => __( 'Knowledgebase', 'kbx-qc' ),
			'labels'             => $ptlabels,
			'supports'           => array( 'title', 'editor', 'author','thumbnail', 'excerpt', 'revisions', 'comments' ),
			'taxonomies'         => array( 'kbx_category', 'kbx_tag' ),
			'public'             => true,
			'hierarchical'       => true,
			'menu_position'      => 5,
			'menu_icon'          => KBX_IMG_URL.'/menu-icon.png',
			'map_meta_cap'       => true,
			'show_in_rest' 		 => true,
			'has_archive'        => $archives,
			'rewrite'            => $rewrite,
		);

		/**
		 * Filter the arguments passed to register the post type.
		 *
		 * @param array $ptargs Post type arguments
		 */
		$ptargs = apply_filters( 'kbx_post_type_args', $ptargs );

		register_post_type( 'kbx_knowledgebase', $ptargs );
	    kbx_permalink_handler();
	}
	add_action( 'init', 'kbx_register_post_type' );
}

/***
 * Filter Articles by Section from Articles list in Admin panel.
 */
if ( ! function_exists( 'kbx_filter_articles_by_section' ) ) {
	add_action('restrict_manage_posts', 'kbx_filter_articles_by_section');
	function kbx_filter_articles_by_section() {
	    global $typenow;
	    $post_type = 'kbx_knowledgebase'; // change to your post type
	    $taxonomy  = 'kbx_category'; // change to your taxonomy
	    if ($typenow == $post_type) {
	        $selected      = isset($_GET[$taxonomy]) ? $_GET[$taxonomy] : '';
	        $info_taxonomy = get_taxonomy($taxonomy);
	        wp_dropdown_categories(array(
	            'show_option_all' => __("Show All {$info_taxonomy->label}"),
	            'taxonomy'        => $taxonomy,
	            'name'            => $taxonomy,
	            'orderby'         => 'name',
	            'selected'        => $selected,
	            'show_count'      => true,
	            'hide_empty'      => true,
	        ));
	    };
	}
}

/**
 * Filter articles by author
 */
if ( ! function_exists( 'kbx_articles_filter_by_the_author' ) ) {
	add_action('restrict_manage_posts', 'kbx_articles_filter_by_the_author');
	function kbx_articles_filter_by_the_author() {
	    $params = array(
	        'name' => 'author',
	        'show_option_all' => 'All Authors'
	    );

	    if ( isset($_GET['user']) )
	        $params['selected'] = $_GET['user'];

	    wp_dropdown_users( $params );
	}
}


if ( ! function_exists( 'kbx_filter_articles_by_section_query' ) ) {
	add_filter('parse_query', 'kbx_filter_articles_by_section_query');
	function kbx_filter_articles_by_section_query($query) {
	    global $pagenow;
	    $post_type = 'kbx_knowledgebase'; // change to your post type
	    $taxonomy  = 'kbx_category'; // change to your taxonomy
	    $q_vars    = &$query->query_vars;
	    if ( $pagenow == 'edit.php' && isset($q_vars['post_type']) && $q_vars['post_type'] == $post_type && isset($q_vars[$taxonomy]) && is_numeric($q_vars[$taxonomy]) && $q_vars[$taxonomy] != 0 ) {
	        $term = get_term_by('id', $q_vars[$taxonomy], $taxonomy);
	        $q_vars[$taxonomy] = $term->slug;
	    }
	}
}


/**
 * 
 * Register Knowledgebase Custom Taxonomies.
 *
 */
if ( ! function_exists( 'kbx_register_taxonomies' ) ) {
	function kbx_register_taxonomies() {

		$catslug = kbx_get_option( 'category_slug', 'kb-sections' );
		$tagslug = kbx_get_option( 'tag_slug', 'kb-tags' );

		$args = array(
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_tagcloud'     => false,
			'rewrite'           => array( 'slug' => $catslug, 'with_front' => true, 'hierarchical' => true ),
		);

		// Now register categories for the Knowledgebase.
		$catlabels = array(
			'name'                       => _x( 'Sections', 'Taxonomy General Name', 'kbx-qc' ),
			'singular_name'              => _x( 'Section', 'Taxonomy Singular Name', 'kbx-qc' ),
			'menu_name'                  => __( 'Sections', 'kbx-qc' ),
			'all_items'                  => __( 'All Sections', 'kbx-qc' ),
			'parent_item'                => __( 'Parent Section', 'kbx-qc' ),
			'parent_item_colon'          => __( 'Parent Section:', 'kbx-qc' ),
			'new_item_name'              => __( 'New Section Name', 'kbx-qc' ),
			'add_new_item'               => __( 'Add New Section', 'kbx-qc' ),
			'edit_item'                  => __( 'Edit Section', 'kbx-qc' ),
			'update_item'                => __( 'Update Section', 'kbx-qc' ),
			'view_item'                  => __( 'View Section', 'kbx-qc' ),
			'separate_items_with_commas' => __( 'Separate sections with commas', 'kbx-qc' ),
			'add_or_remove_items'        => __( 'Add or remove sections', 'kbx-qc' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'kbx-qc' ),
			'popular_items'              => __( 'Popular Sections', 'kbx-qc' ),
			'search_items'               => __( 'Search Sections', 'kbx-qc' ),
			'not_found'                  => __( 'Not Found', 'kbx-qc' ),
			'no_terms'                   => __( 'No sections', 'kbx-qc' ),
			'items_list'                 => __( 'Sections list', 'kbx-qc' ),
			'items_list_navigation'      => __( 'Sections list navigation', 'kbx-qc' ),
		
		);

		/**
		 * Filter the labels of the custom categories.
		 *
		 * @param array $catlabels Category labels
		 */
		$args['labels'] = apply_filters( 'kbx_cat_labels', $catlabels );
		$args['show_in_rest'] = true;
		register_taxonomy(
			'kbx_category',
			array( 'kbx_knowledgebase' ),
			/**
			 * Filter the arguments of the custom categories.
			 *
			 * @param array $catlabels Category labels
			 */
			apply_filters( 'kbx_cat_args', $args )
		);

		// Now register tags for the Knowledgebase.
		$taglabels = array(
			'name'          => _x( 'Tags', 'Taxonomy General Name', 'kbx-qc' ),
			'singular_name' => _x( 'Tag', 'Taxonomy Singular Name', 'kbx-qc' ),
			'menu_name'     => __( 'Tags', 'kbx-qc' ),
		);

		/**
		 * Filter the labels of the custom tags.
		 *
		 * @param array $taglabels Tags labels
		 */
		$args['labels'] = apply_filters( 'kbx_tag_labels', $taglabels );

		$args['hierarchical']    = false;
		$args['show_tagcloud']   = true;
		$args['rewrite']['slug'] = $tagslug;
		$args['show_in_rest'] = true;
		register_taxonomy(
			'kbx_tag',
			array( 'kbx_knowledgebase' ),
			/**
			 * Filter the arguments of the custom tags.
			 *
			 * @since 1.2.0
			 *
			 * @param array $args Tag arguments
			 */
			apply_filters( 'kbx_tag_args', $args )
		);
		//Insert the default kbx-section as uncategories section.
	    if(empty(get_term_by('slug', 'default-section', 'kbx_category'))){
	        wp_insert_term(
	            'Uncategorized', // the term
	            'kbx_category', // the taxonomy
	            array(
	                'description'=> 'Default section for articles.',
	                'slug' => 'default-section'
	            )
	        );
	    }

	    kbx_permalink_handler();
	}
	add_action( 'init', 'kbx_register_taxonomies');
}


