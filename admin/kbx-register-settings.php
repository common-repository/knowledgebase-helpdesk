<?php
defined('ABSPATH') or die("You can't access this file directly.");
/**
 * Register settings.
 *
 * Functions to register, read, write and update settings.
 * Portions of this code have been inspired by Easy Digital Downloads, WordPress Settings Sandbox, etc.
 *
 * @subpackage Admin/Register_Settings
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Get an option
 *
 * Looks to see if the specified setting exists, returns default if not
 *
 * @param string $key Key of the option to fetch.
 * @param mixed  $default Default value to fetch if option is missing.
 * @return mixed
 */
if ( ! function_exists( 'kbx_get_option' ) ) {
    function kbx_get_option( $key , $default ) {

    	global $kbx_options;

    	$value = ! empty( $kbx_options[ $key ] ) ? $kbx_options[ $key ] : $default;

    	/**
    	 * Filter the value for the option being fetched.
    	 *
    	 * @param mixed $value  Value of the option
    	 * @param mixed $key  Name of the option
    	 * @param mixed $default Default value
    	 */
    	$value = apply_filters( 'kbx_get_option', $value, $key, $default );

    	/**
    	 * Key specific filter for the value of the option being fetched.
    	 *
    	 * @param mixed $value  Value of the option
    	 * @param mixed $key  Name of the option
    	 * @param mixed $default Default value
    	 */
    	return apply_filters( 'kbx_get_option_' . $key, $value, $key, $default );
    }
}


/**
 * Update an option
 *
 * Updates an kbx setting value in both the db and the global variable.
 * Warning: Passing in an empty, false or null string value will remove
 * the key from the kbx_options array.
 *
 * @param  string          $key   The Key to update.
 * @param  string|bool|int $value The value to set the key to.
 * @return boolean   True if updated, false if not.
 */
if ( ! function_exists( 'kbx_update_option' ) ) {
    function kbx_update_option( $key, $value ) {

    	// If no key, exit.
    	if ( empty( $key ) ) {
    		return false;
    	}

    	// If no value, delete.
    	if ( empty( $value ) ) {
    		$remove_option = kbx_delete_option( $key );
    		return $remove_option;
    	}

    	// First let's grab the current settings.
    	$options = get_option( 'kbx_settings' );

    	/**
    	 * Filters the value before it is updated
    	 *
    	 * @param  string|bool|int $value The value to set the key to
    	 * @param  string          $key   The Key to update
    	 */
    	$value = apply_filters( 'kbx_update_option', $value, $key );

    	// Next let's try to update the value.
    	$options[ $key ] = $value;
    	$did_update = update_option( 'kbx_settings', $options );

    	// If it updated, let's update the global variable.
    	if ( $did_update ) {
    		global $kbx_options;
    		$kbx_options[ $key ] = $value;
    	}
    	return $did_update;
    }
}


/**
 * Remove an option
 *
 * Removes an kbx setting value in both the db and the global variable.
 *
 * @param  string $key The Key to update.
 * @return boolean   True if updated, false if not.
 */
if ( ! function_exists( 'kbx_delete_option' ) ) {
    function kbx_delete_option( $key ) {

    	// If no key, exit.
    	if ( empty( $key ) ) {
    		return false;
    	}

    	// First let's grab the current settings.
    	$options = get_option( 'kbx_settings' );

    	// Next let's try to update the value.
    	if ( isset( $options[ $key ] ) ) {
    		unset( $options[ $key ] );
    	}

    	$did_update = update_option( 'kbx_settings', $options );

    	// If it updated, let's update the global variable.
    	if ( $did_update ) {
    		global $kbx_options;
    		$kbx_options = $options;
    	}
    	return $did_update;
    }
}


/**
 * Register settings function
 *
 * @return void
 */
if ( ! function_exists( 'kbx_register_settings' ) ) {
    function kbx_register_settings() {

    	if ( false === get_option( 'kbx_settings' ) ) {
    		add_option( 'kbx_settings', kbx_settings_defaults() );
    	}

    	foreach ( kbx_get_registered_settings() as $section => $settings ) {

    		add_settings_section(
    			'kbx_settings_' . $section, // ID used to identify this section and with which to register options, e.g. kbx_settings_general.
    			__return_null(),	// No title, we will handle this via a separate function.
    			'__return_false',	// No callback function needed. We'll process this separately.
    			'kbx_settings_' . $section  // Page on which these options will be added.
    		);

    		foreach ( $settings as $setting ) {

    			$args = wp_parse_args( $setting, array(
    					'section' => $section,
    					'id'      => null,
    					'name'    => '',
    					'desc'    => '',
    					'type'    => null,
    					'options' => '',
    					'max'     => null,
    					'min'     => null,
    					'step'    => null,
    			) );

    			add_settings_field(
    				'kbx_settings[' . $args['id'] . ']', // ID of the settings field. We save it within the kbx_settings array.
    				$args['name'],	   // Label of the setting.
    				function_exists( 'kbx_' . $args['type'] . '_callback' ) ? 'kbx_' . $args['type'] . '_callback' : 'kbx_missing_callback', // Function to handle the setting.
    				'kbx_settings_' . $section,	// Page to display the setting. In our case it is the section as defined above.
    				'kbx_settings_' . $section,	// Name of the section.
    				$args
    			);
    		}
    	}

    	// Register the settings into the options table.
    	register_setting( 'kbx_settings', 'kbx_settings', 'kbx_settings_sanitize' );
    }
    add_action( 'admin_init', 'kbx_register_settings' );
}


/**
 * Retrieve the array of plugin settings
 *
 * @since 1.2.0
 *
 * @return array Settings array
 */
if ( ! function_exists( 'kbx_get_registered_settings' ) ) {
    function kbx_get_registered_settings() {

    	$kbx_settings = array(
    		/*** General settings ***/
    		'general'             => apply_filters( 'kbx_settings_general',
    			array(
    				'kb_slug'           => array(
    					'id'               => 'kb_slug',
    					'name'             => esc_html__( 'Knowledgebase slug', 'kbx-qc' ),
    					'desc'             => esc_html__( 'This will set the opening path of the URL of the knowledgebase and is set when registering the custom post type', 'kbx-qc' ),
    					'type'             => 'text',
    					'options'          => 'knowledgebase',
    				),
    				'category_slug'     => array(
    					'id'               => 'category_slug',
    					'name'             => esc_html__( 'Category slug', 'kbx-qc' ),
    					'desc'             => esc_html__( 'Each category is a section of the knowledgebase. This setting is used when registering the custom category and forms a part of the URL when browsing category archives', 'knowledgebase' ),
    					'type'             => 'text',
    					'options'          => 'kb-sections',
    				),
    				'tag_slug'          => array(
    					'id'               => 'tag_slug',
    					'name'             => esc_html__( 'Tag slug', 'kbx-qc' ),
    					'desc'             => esc_html__( 'Each article can have multiple tags. This setting is used when registering the custom tag and forms a part of the URL when browsing tag archives', 'kbx-qc' ),
    					'type'             => 'text',
    					'options'          => 'kb-tags',
    				),


                    'kb_search_bg_image'          => array(
                        'id'               => 'kb_search_bg_image',
                        'name'             => esc_html__( 'Search box image', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Upload the Background Image of Search Box.', 'kbx-qc' ),
                        'type'             => 'upload',
                        'options'          => '',
                    ),
    

                    'kb_search_placeholder'          => array(
                        'id'               => 'kb_search_placeholder',
                        'name'             => esc_html__( 'Search box text', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Change the text of Placeholder from Knowledgebase main search box and floating widget.', 'kbx-qc' ),
                        'type'             => 'text',
                        'options'          => 'Search the knowledge base',
                    ),

                    'kb_floating_widget_main_title'          => array(
                        'id'               => 'kb_floating_widget_main_title',
                        'name'             => esc_html__( 'Looking for help?', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Change the Title of Placeholder from Knowledgebase main search box and floating widget', 'kbx-qc' ),
                        'type'             => 'text',
                        'options'          => 'Looking for help?',
                    ),

                    'kb_floating_widget_placeholder'          => array(
                        'id'               => 'kb_floating_widget_placeholder',
                        'name'             => esc_html__( 'Floating widget Placeholder', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Change the text of Placeholder from Knowledgebase main search box and floating widget', 'kbx-qc' ),
                        'type'             => 'text',
                        'options'          => 'Type your search string. Minimum 4 characters are required.',
                    ),
                    'kbx_per_page'          => array(
                        'id'               => 'kbx_per_page',
                        'name'             => esc_html__( 'Articles Per Page', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Articles Per Page is used to control to show articles per page on section and glossary page', 'kbx-qc' ),
                        'type'             => 'text',
                        'options'          => '10',
                    ),
                    'kbx_per_section'          => array(
                        'id'               => 'kbx_per_section',
                        'name'             => esc_html__( 'Articles Per Section', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Articles Per Section on Template two is used to control to show articles per section on template two page', 'kbx-qc' ),
                        'type'             => 'text',
                        'options'          => '5',
                    ),
                    'kbx_article_template'        => array(
                        'id'               => 'kbx_article_template',
                        'name'             => esc_html__( 'Template Option', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Article Template Option is used to display sections on main shortcode.', 'kbx-qc' ),
                        'type'             => 'select',
                        'disabled_options_key' => array('02'),
                        'options'          => array(
                            '01' => 'Basic',
                            '02' => 'Template 02',
                        ),
                    ),

                    
                    'sorting_option'        => array(
                        'id'               => 'sorting_option',
                        'name'             => esc_html__( 'Article Sorting Option', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Article Sorting Option is used to display by sort as option.', 'kbx-qc' ),
                        'type'             => 'select',
                        'options'          => array(
                            'date' => 'Sort by Default',
                            'name' => 'Sort A-Z',
                            'popularity' => 'Sort by Popularity',
                            'views' => 'Sort by Views',
                        ),
                    ),
                    'kbx_read_time' => array(
                        'id'               => 'kbx_read_time',
                        'name'             => esc_html__( 'Enable Read Time on Article', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Once enabled you can display read time on the article details page', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => true,
                    ),
                   'kbx_read_time_label'          => array(
                        'id'               => 'kbx_read_time_label',
                        'name'             => esc_html__( 'Reading Time Label', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Reading time label is used to display just before of the read time count', 'kbx-qc' ),
                        'type'             => 'text',
                        'options'          => 'read',
                    ),
                    /*'kbx_read_time_before_content' => array(
                       'id'               => 'kbx_read_time_before_content',
                       'name'             => esc_html__( 'Show Read Time Before Content', 'kbx-qc' ),
                       'desc'             => esc_html__( 'Once enabled you can display read time just before content start', 'kbx-qc' ),
                       'type'             => 'checkbox',
                       'options'          => true,
                   ),
                   'kbx_read_time_before_excerpt' => array(
                       'id'               => 'kbx_read_time_before_excerpt',
                       'name'             => esc_html__( 'Show Read Time Before Excerpt', 'kbx-qc' ),
                       'desc'             => esc_html__( 'Once enabled you can display read time just before excerpt', 'kbx-qc' ),
                       'type'             => 'checkbox',
                       'options'          => false,
                   ),*/
                    'enable_section_heading' => array(
                        'id'               => 'enable_section_heading',
                        'name'             => esc_html__( 'Enable Section Heading', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Once enabled you can display heading for the Sections', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => true,
                    ),
                    'section_heading'     => array(
                        'id'               => 'section_heading',
                        'name'             => esc_html__( 'Section Heading', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Enter value for chaning the section heading , Example: Browse the KnowledgeBase.' ),
                        'type'             => 'text',
                        'options'          => 'Browse the KnowledgeBase',
                    ),
                    
                    'kbx_all_categories'          => array(
                        'id'               => 'kbx_all_categories',
                        'name'             => esc_html__( 'View All Categories', 'kbx-qc' ),
                        //'desc'             => esc_html__( 'Reading time label is used to display just before of the read time count', 'kbx-qc' ),
                        'type'             => 'text',
                        'options'          => 'View All Categories',
                    ),


                    'sidebar_category_title'     => array(
                        'id'               => 'sidebar_category_title',
                        'name'             => esc_html__( 'Sidebar Category Title', 'kbx-qc' ),
                        'type'             => 'text',
                        'options'          => __('Browse Articles', 'kbx-qc'),
                    ),

                    'user_role_cat' => array(
                        'id'               => 'user_role_cat',
                        'name'             => esc_html__( 'Enable Access by Use Roles', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Once enabled you can restrict by user roles from Edit Section area', 'kbx-qc' ),
                        'side_note'        => '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a>',
                        'type'             => 'checkbox',
                        'options'          => false,
                        'disabled'         => true,
                    ),
                    'show_empty_categories'        => array(
                        'id'               => 'show_empty_categories',
                        'name'             => esc_html__( 'Show  or Not Show Empty Articles Section', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Enable to Show Empty Articles Section in the frontend.', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => false,
                    ),
                    'kbx_home_tabs' => array(
                        'id'               => 'kbx_home_tabs',
                        'name'             => esc_html__( 'Enable Sticky,Popular and Recent article tabs ', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Once enabled you can display Sticky,Popular and Recent article tabs on main shortcode', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => false,
                        'disabled'         => true,
                        'side_note'        => '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a>',
                    ),
                    'kbx_home_tabs_option'        => array(
                        'id'               => 'kbx_home_tabs_option',
                        'name'             => esc_html__( 'Articles Tab Display Option', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Articles Tab Display Option is used to display Sticky,Popular and Recent article tabs at the top or at the bottom.', 'kbx-qc' ),
                        'type'             => 'select',
                        'readonly'          => true,
                        'disabled_options_key' => array('bottom'),
                        'side_note'        => '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a>',
                        'options'          => array(
                            'top' => 'At the Top',
                            'bottom' => 'At the Bottom',
                        ),
                    ),
                    'kbx_home_tab_stricky' => array(
                        'id'               => 'kbx_home_tab_stricky',
                        'name'             => esc_html__( 'Enable Sticky on article tabs ', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Once enabled you can display Sticky article tabs on main shortcode', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => false,
                        'disabled'         => true,
                        'side_note'        => '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a>',
                    ),
                    'kbx_home_tab_popular' => array(
                        'id'               => 'kbx_home_tab_popular',
                        'name'             => esc_html__( 'Enable Popular on article tabs ', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Once enabled you can display Popular article tabs on main shortcode', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => false,
                        'disabled'         => true,
                        'side_note'        => '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a>',
                    ),
                    'kbx_home_tab_recent' => array(
                        'id'               => 'kbx_home_tab_recent',
                        'name'             => esc_html__( 'Enable Recent on article tabs ', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Once enabled you can display Recent article tabs on main shortcode', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => false,
                        'disabled'         => true,
                        'side_note'        => '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a>',
                    ),
                    'kbx_hide_from_cat_list_for_excluded_user' => array(
                        'id'               => 'kbx_hide_from_cat_list_for_excluded_user',
                        'name'             => esc_html__( 'Hide from Category Listing', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Hide from Category Listing for Excluded User Roles', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => false,
                        'disabled'         => true,
                        'side_note'        => '<a class="go-pro-link" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/" target="_blank"><strong>Coming Soon</strong></a>',
                    ),
                    

    				'uninstall_header'  => array(
    					'id'               => 'uninstall_header',
    					'name'             => '<h3>' . esc_html__( 'Uninstall options', 'kbx-qc' ) . '</h3>',
    					'desc'             => '',
    					'type'             => 'header',
    					'options'          => '',
    				),
    				'uninstall_options' => array(
    					'id'               => 'uninstall_options',
    					'name'             => esc_html__( 'Delete options on uninstall', 'kbx-qc' ),
    					'desc'             => esc_html__( 'Check this box to delete the settings on this page when the plugin is deleted via the Plugins page in your WordPress Admin', 'kbx-qc' ),
    					'type'             => 'checkbox',
    					'options'          => false,
    				),
    				'uninstall_data'    => array(
    					'id'               => 'uninstall_data',
    					'name'             => esc_html__( 'Delete all knowledgebase posts on uninstall', 'kbx-qc' ),
    					'desc'             => esc_html__( 'Check this box to delete all the posts, categories and tags created by the plugin. There is no way to restore the data if you choose this option', 'kbx-qc' ),
    					'type'             => 'checkbox',
    					'options'          => false,
    				),
    			)
    		),
    		/*** Style settings ***/
    		'styles'              => apply_filters( 'kbx_settings_styles',
    			array(
                    'article_max_width'     => array(
                        'id'               => 'article_max_width',
                        'name'             => esc_html__( 'Article Details Page Max-Width', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Enter value for single page width for article details , Example: 1170, 850,1050 etc.' ),
                        'type'             => 'text',
                        'options'          => '1170',
                    ),
                    'article_margin_top'     => array(
                        'id'               => 'article_margin_top',
                        'name'             => esc_html__( 'Article Details Page Margin Top', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Enter value for article details page margin top. Example: 10' ),
                        'type'             => 'text',
                        'options'          => '10',
                    ),


                    'search_box_color'     => array(
                        'id'               => 'search_box_color',
                        'name'             => esc_html__( 'Search Box Background Color', 'kbx-qc' ),
                        'type'             => 'color',
                        'options'          => '#ffffff',
                    ),
    
    
                    'search_box_text_color'     => array(
                        'id'               => 'search_box_text_color',
                        'name'             => esc_html__( 'Search Box Text Color', 'kbx-qc' ),
                        'type'             => 'color',
                        'options'          => '#00000',
                    ),

                    'article_text_color'     => array(
                        'id'               => 'article_text_color',
                        'name'             => esc_html__( 'Article Text Color', 'kbx-qc' ),
                        'type'             => 'color',
                        'options'          => '#212f3e',
                    ),
                    'article_link_color'     => array(
                        'id'               => 'article_link_color',
                        'name'             => esc_html__( 'Link Color', 'kbx-qc' ),
                        'type'             => 'color',
                        'options'          => '#107eec',
                    ),
                    'article_link_hover_color'     => array(
                        'id'               => 'article_link_hover_color',
                        'name'             => esc_html__( 'Link Hover Color', 'kbx-qc' ),
                        'type'             => 'color',
                        'options'          => '#469aef',
                    ),
    				'custom_css'        => array(
    					'id'               => 'custom_css',
    					'name'             => esc_html__( 'Custom CSS', 'kbx-qc' ),
    					'desc'             => esc_html__( 'Enter any custom valid CSS without any wrapping &lt;style&gt; tags', 'kbx-qc' ),
    					'type'             => 'textarea',
    					'options'          => '',
    				),
    			)
    		),	
    		/*** others settings ***/
    		'others'              => apply_filters( 'kbx_settings_others',
    			array(
                    'enable_kbx_breadcrum'        => array(
                        'id'               => 'enable_kbx_breadcrum',
                        'name'             => esc_html__( 'Enable Breadcrumb', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Enable Breadcrumb for Articles and Categories in the frontend.', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => true,
                    ),
                    'enable_related_artilces'  => array(
                        'id'               => 'enable_related_artilces',
                        'name'             => esc_html__( 'Enable Related Articles', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Enable related articles option to display related articles for visitors on details page.', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => false,
                    ),
                    'enable_article_comments'  => array(
                        'id'               => 'enable_article_comments',
                        'name'             => esc_html__( 'Enable Comments on Article', 'kbx-qc' ),
                        'desc'             => esc_html__( 'Enable comments option feature to an article for visitors.', 'kbx-qc' ),
                        'type'             => 'checkbox',
                        'options'          => false,
                    ),
    			)
    		),




            'helps' => apply_filters( 'kbx_settings_helps',
                     array(
                    
                        
                
                     )
            ),




    	);

    	/**
    	 * Filters the settings array
    	 *
    	 *
    	 * @param array $kbx_setings Settings array
    	 */
    	return apply_filters( 'kbx_registered_settings', $kbx_settings );

    }
}



/**
 * Default settings.
 *
 * @return array Default settings
 */
if ( ! function_exists( 'kbx_settings_defaults' ) ) {
    function kbx_settings_defaults() {

    	$options = array();

    	// Populate some default values.
    	foreach ( kbx_get_registered_settings() as $tab => $settings ) {
    		foreach ( $settings as $option ) {
    			// When checkbox is set to true, set this to 1.
    			if ( 'checkbox' === $option['type'] && ! empty( $option['options'] ) ) {
    				$options[ $option['id'] ] = '1';
    			}
    			// If an option is set.
    			if ( in_array( $option['type'], array( 'textarea', 'text', 'csv' ), true ) && ! empty( $option['options'] ) ) {
    				$options[ $option['id'] ] = $option['options'];
    			}
    		}
    	}

    	/**
    	 * Filters the default settings array.
    	 *
    	 * @param array $options Default settings.
    	 */
    	return apply_filters( 'kbx_settings_defaults', $options );
    }
}


/**
 * Reset settings.
 *
 * @return void
 */
if ( ! function_exists( 'kbx_settings_reset' ) ) {
    function kbx_settings_reset() {
    	delete_option( 'kbx_settings' );
    }
}


function kbx_upload_callback($args){
	global $kbx_options;
	if ( isset( $kbx_options[ $args['id'] ] ) ) {
        $value = $kbx_options[ $args['id'] ];
    } else {
        $value = isset( $args['options'] ) ? $args['options'] : '';
    }

    $default_image = KBX_IMG_URL . '/defualt_section.png';
    $width = 150;
    $height = 150;

    if ( !empty( $kbx_options[$args['id']] ) ) {
        $image_attributes = wp_get_attachment_image_src( $kbx_options[$args['id']], array( $width, $height ) );
        $src = $image_attributes[0];
        $value = $kbx_options[$args['id']];
    } else {
        $src = $default_image;
        $value = '';
    }

    $text = __( 'Upload', 'kbx-qc' );
    $name = $args['id'];

    // Print HTML field
    echo '<div class="upload">';
  
    	echo '<img data-src="' . $default_image . '" src="' . $src . '" width="' . $width . 'px" height="' . $height . 'px" />';

    echo '
            <div>
                <input type="hidden" name="kbx_settings[' . $name . ']" id="kbx_settings[' . $name . ']" value="' . $value . '" />
                <button type="submit" class="upload_image_button button">' . $text . '</button>
                <button type="submit" class="remove_image_button button">&times;</button>
            </div>
        </div>
    ';
}

function kbx_image_uploader_script(){

    /*
    if possible try not to queue this all over the admin by adding your settings GET page val into next
    if( empty( $_GET['page'] ) || "my-settings-page" !== $_GET['page'] ) { return; }
    */

    ?>

    <script>
        jQuery(document).ready(function(){

            // The "Upload" button
			jQuery('.upload_image_button').click(function() {
			    var send_attachment_bkp = wp.media.editor.send.attachment;
			    var button = jQuery(this);
			    wp.media.editor.send.attachment = function(props, attachment) {
			        jQuery(button).parent().prev().attr('src', attachment.url);
			        jQuery(button).prev().val(attachment.id);
			        wp.media.editor.send.attachment = send_attachment_bkp;
			    }
			    wp.media.editor.open(button);
			    return false;
			});

			// The "Remove" button (remove the value from input type='hidden')
			jQuery('.remove_image_button').click(function() {
			    var answer = confirm('Are you sure?');
			    if (answer == true) {
			        var src = jQuery(this).parent().prev().attr('data-src');
			        jQuery(this).parent().prev().attr('src', src);
			        jQuery(this).prev().prev().val('');
			    }
			    return false;
			});   
        });
    </script>

    <?php
}

function kbx_enqueue_media_script(){
    /*
    if possible try not to queue this all over the admin by adding your settings GET page val into next
    if( empty( $_GET['page'] ) || "my-settings-page" !== $_GET['page'] ) { return; }
    */
    wp_enqueue_media();
}
