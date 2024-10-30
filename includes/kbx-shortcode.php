<?php
defined('ABSPATH') or die("You can't access this file directly.");

/*******************************
 * Shortcode for Displaying 
 * Main Knowledgebase panel
 *******************************/
if ( ! function_exists( 'kbx_show_search_panel_sc' ) ) {
    add_shortcode('kbx-knowledgebase', 'kbx_show_search_panel_sc');
    function kbx_show_search_panel_sc( $atts){

    	ob_start();

        kbx_display_search_panel( $atts );

        $content = ob_get_clean();

        return $content;
    }
}

if ( ! function_exists( 'kbx_display_search_panel' ) ) {
    function kbx_display_search_panel( $atts){

    	global $kbx_options;
        //Defaults & Set Parameters
        if( !array_key_exists( 'kbx_article_template', $kbx_options ) ){
            $kbx_options['kbx_article_template'] = '01';
        }
    	extract( shortcode_atts(
    		array(
    			'orderby'                => 'date',
    			'order'                  => 'DESC',
    			'limit'                  => 10,
    			'section'                => '',
    			'template'               => $kbx_options['kbx_article_template'],
    			'hide_empty_section'     => false,
    			'show_section_box'       => true,
    			'show_article_title'     => '',
    			'show_search_form'       => true,
    		), $atts
    	));

        $template = "basic";

        require ( KBX_DIR . "/views/templates/$template/template.php" );

    }
}

/*******************************
 * Shortcode for Displaying 
 * Knowledgebase Articles
 *******************************/
if ( ! function_exists( 'kbx_show_kbarticle_panel_sc' ) ) {
    add_shortcode('kbx-knowledgebase-articles', 'kbx_show_kbarticle_panel_sc');
    function kbx_show_kbarticle_panel_sc( $atts){
    	
    	ob_start();

        kbx_display_kbarticle_panel( $atts );

        $content = ob_get_clean();

        return $content;
    }
}

if ( ! function_exists( 'kbx_display_kbarticle_panel' ) ) {
    function kbx_display_kbarticle_panel( $atts){
        global $kbx_options;
    	require_once(KBX_DIR . '/includes/kbx-reading-time.php');
    	$kbxReadingOptions = get_option('kbx_reading_time_options');
    	$kbxArticleReadTime = new kbxArticleReadTime();
    	
    	//Defaults & Set Parameters
        if( !array_key_exists( 'sorting_option', $kbx_options ) ){
            $kbx_options['sorting_option'] = 'date';
        }
        $articles_per_page  = ( isset( $kbx_options['kbx_per_page'] ) && $kbx_options['kbx_per_page'] != '') ? $kbx_options['kbx_per_page'] : 10;
    	extract( shortcode_atts(
    		array(
    			'orderby'            => $kbx_options['sorting_option'],
    			'order'              => 'DESC',
    			'limit'              => $articles_per_page,
    			'section'            => '',
    			'template'           => '',
    			'meta_key'           => '',
                'show_search_form'   => true,
                'show_pagination'    => true,
    		), $atts
    	));

    	if( isset($_GET['sort']) && $_GET['sort'] == 'name' ){
    		$orderby = 'title';
    		$order   = 'ASC';
    	}

        if( isset($kbx_options['sorting_option']) && $kbx_options['sorting_option'] == 'name' ){
            $orderby = 'title';
            $order   = 'ASC';
        }

    	if( isset($_GET['sort']) && $_GET['sort'] == 'popularity' ){
    		$orderby  = array( 'meta_value_num' => 'DESC' );
    		$meta_key = 'kpm_upvotes';
    	}

        if( isset($kbx_options['sorting_option']) && $kbx_options['sorting_option'] == 'popularity' ){
            $orderby  = array( 'meta_value_num' => 'DESC' );
            $meta_key = 'kpm_upvotes';
        }

    	if( isset($_GET['sort']) && $_GET['sort'] == 'views' ){
    		$orderby  = array( 'meta_value_num' => 'DESC' );
    		$meta_key = 'kpm_views';
    	}

        if( isset($kbx_options['sorting_option']) && $kbx_options['sorting_option'] == 'views' ){
            $orderby  = array( 'meta_value_num' => 'DESC' );
            $meta_key = 'kpm_views';
        }

    	//Query Parameters
        $paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
    	$kb_args = array(
    		'post_type' => 'kbx_knowledgebase',
    		'post_status'=>'publish',
    		'order' => $order,
    		'posts_per_page' => $limit,
            'orderby' => $orderby,
    		'meta_key' => $meta_key,
            'paged' => $paged,
    	);
    	
        $taxArray = array();
        
    	if( $section != "" )
    	{
    		$taxArray = array(
    			array(
    				'taxonomy' => 'kbx_category',
    				'field'    => 'term_id',
    				'terms'    => $section,
    			),
    		);
    		
    		$kb_args = array_merge($kb_args, array( 'tax_query' => $taxArray ));
    		
    	}
    	$query = new WP_Query( $kb_args );

    	//Sticky articles list query
        $kb_sticky_args = array(
            'post_type'      => 'kbx_knowledgebase',
    		'post_status'    => 'publish',
            'posts_per_page' => -1,
            'tax_query'      => $taxArray,
            'meta_query'     => array(
                array(
                    'key'     => 'kpm_featured',
                    'value'   => 'yes',
                )
            )
        );
        $sticky_query = new WP_Query( $kb_sticky_args );

        $template = "basic";

        require ( KBX_DIR . "/views/templates/$template/template-articles.php" );

    }
}
/*******************************
 * Shortcode for Displaying
 * FAQ Page
 *******************************/
if ( ! function_exists( 'kbx_faq_shortcode' ) ) {
    add_shortcode('kbx-faq', 'kbx_faq_shortcode');
    function kbx_faq_shortcode( $atts){
        ob_start();

        kbx_faq_display( $atts );

        $content = ob_get_clean();

        return $content;
    }
}


if ( ! function_exists( 'kbx_faq_display' ) ) {
    function kbx_faq_display( $atts) {
        global $kbx_options;
        //Defaults & Set Parameters
        if( !array_key_exists( 'sorting_option', $kbx_options ) ){
            $kbx_options['sorting_option'] = 'date';
        }
        $articles_per_page  = ( isset( $kbx_options['kbx_per_page'] ) && $kbx_options['kbx_per_page'] != '') ? $kbx_options['kbx_per_page'] : 10;
        extract( shortcode_atts(
            array(
                'orderby'           => $kbx_options['sorting_option'],
                'content_type'      => 'full-content',
                'order'             => 'DESC',
                'limit'             => $articles_per_page,
                'section'           => '',
                'template'          => '',
                'meta_key'          => '',
                'show_search_form'  => true,
                'show_pagination'   => true,
            ), $atts
        ));

        if( isset($_GET['sort']) && $_GET['sort'] == 'name' ){
            $orderby = 'title';
            $order   = 'ASC';
        }

        if( isset($_GET['sort']) && $_GET['sort'] == 'popularity' ){
            $orderby  = array( 'meta_value_num' => 'DESC' );
            $meta_key = 'kpm_upvotes';
        }

        if( isset($_GET['sort']) && $_GET['sort'] == 'views' ){
            $orderby  = array( 'meta_value_num' => 'DESC' );
            $meta_key = 'kpm_views';
        }

        //Query Parameters
        $paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
        $kb_args = array(
            'post_type' => 'kbx_knowledgebase',
    		'post_status'=>'publish',
            'order' => $order,
            'posts_per_page' => $limit,
            'orderby' => $orderby,
            'meta_key' => $meta_key,
            'paged' => $paged,
        );

        if( $section != "" )
        {
            $taxArray = array(
                array(
                    'taxonomy' => 'kbx_category',
                    'field'    => 'term_id',
                    'terms'    => $section,
                ),
            );

            $kb_args = array_merge($kb_args, array( 'tax_query' => $taxArray ));

        }
        // The Query
        $query = new WP_Query( $kb_args );

        $template = "basic";

        require ( KBX_DIR . "/views/templates/$template/kbx-faq.php" );

    }
}
/*******************************
 * Glossary
 *******************************/
if ( ! function_exists( 'kbx_show_glossary_sc' ) ) {
    add_shortcode('kbx-knowledgebase-glossary', 'kbx_show_glossary_sc');
    function kbx_show_glossary_sc( $atts){
    	ob_start();

        kbx_display_glossary( $atts );

        $content = ob_get_clean();

        return $content;
    }
}

if ( ! function_exists( 'kbx_display_glossary' ) ) {
    function kbx_display_glossary( $atts){
        global $kbx_options;


        $articles_per_page  = ( isset( $kbx_options['kbx_per_page'] ) && $kbx_options['kbx_per_page'] != '') ? $kbx_options['kbx_per_page'] : 10;
    	//Defaults & Set Parameters
    	extract( shortcode_atts(
    		array(
    			'orderby'            => 'title',
    			'order'              => 'ASC',
    			'limit'              => $articles_per_page,
    			'template'           => '',
                'show_search_form'   => true,
                'show_pagination'    => true,
    		), $atts
    	));

    	$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;

    	//Query Parameters
    	$kb_args = array(
    		'post_type' => 'kbx_knowledgebase',
    		'post_status'=>'publish',
    		'orderby' => $orderby,
    		'order' => $order,
    		'posts_per_page' => $limit,
    	);

    	//check if glossary term is set
    	if( isset($_GET['kbx-glossary']) && $_GET['kbx-glossary'] != '' )
    	{
    		

    		$enableCustomWildCard = array('wildcard_on_key' => true);

    		$kb_args = array_merge($kb_args, $enableCustomWildCard);

            $glossaryKey = trim( sanitize_text_field( $_GET['kbx-glossary'] ) );
            $glossaryTerm=get_kbx_gterm_values_by_index('kpm_gterm', 'kbx_knowledgebase',$glossaryKey);

    		$metaArray = array(
    			array(
    				'key' 		=> 'kpm_gterm',
    				'value'    	=> $glossaryTerm,
    				'compare'   => 'IN',
    			),
    		);
    		
    		$kb_args = array_merge($kb_args, array( 'meta_query' => $metaArray ));
    	}

    	// The Query
    	$query = new WP_Query( $kb_args );

        $template = "basic";
        //Total argu and query
        $kb_total_argu =array(
            'post_type' => 'kbx_knowledgebase',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );
        $total_query = new WP_Query($kb_total_argu);
        $total_articles_num= $total_query->post_count;

        require ( KBX_DIR . "/views/templates/$template/template-glossary.php" );

    }
}