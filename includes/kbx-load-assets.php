<?php
defined('ABSPATH') or die("You can't access this file directly.");

/**
 * Proper way to enqueue scripts and styles
 */
if ( ! function_exists( 'kbx_plugin_scripts' ) ) {
    function kbx_plugin_scripts() {

        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'kbx-jquery-modal-js', KBX_ASSETS_URL . '/js/jquery.modal.min.js', array('jquery'));
        wp_enqueue_script( 'imagesloaded', KBX_ASSETS_URL . '/js/imagesloaded.js', array('jquery'));
        wp_enqueue_script( 'kbx-packery-js', KBX_ASSETS_URL . '/js/packery.pkgd.min.js', array('jquery'));
        wp_enqueue_script( 'kbx-script-js', KBX_ASSETS_URL . '/js/script.js', array('jquery','kbx-packery-js'));
        wp_enqueue_script( 'kbx-slim-scroll', KBX_ASSETS_URL . '/js/jquery.slimscroll.min.js', array('jquery'));
        wp_enqueue_script( 'kbx-cookies-js', KBX_ASSETS_URL . '/js/jquery.cookie.js', array('jquery'));
        wp_enqueue_script( 'kbx-bot-js', KBX_ASSETS_URL . '/js/bot-plugin.js', array('jquery','kbx-cookies-js'));
        wp_enqueue_script( 'kbx-general-js', KBX_ASSETS_URL . '/js/general.js', array('jquery'));
        $kbx_bot_obj = array(
            'kbx_bot_position_x'        => get_option('kbx_bot_position_x'),
            'kbx_bot_position_y'        => get_option('kbx_bot_position_y'),
            'disable_icon_animation'    => get_option('disable_kbx_bot_icon_animation'),
            'ajax_url'                  => admin_url('admin-ajax.php'),
            'image_path'                => KBX_IMG_URL.'/',
            'host'                      => get_option('kbx_bot_host'),
            'agent'                     => get_option('kbx_bot_agent'),
            'agent_image_path'          => kbx_bot_agent_icon(),
            'shopper_demo_name'         => get_option('kbx_bot_shopper_demo_name'),
            'shopper_call_you'          => get_option('kbx_wp_chatbot_shopper_call_you'),
            'agent_join'                => maybe_unserialize(get_option('kbx_bot_agent_join')),
            'back_start'                => maybe_unserialize(get_option('kbx_bot_back_start')),
            'welcome'                   => maybe_unserialize(get_option('kbx_bot_welcome')),
            'welcome_back'              => maybe_unserialize(get_option('kbx_bot_welcome_back')),
            'hi_there'                  => maybe_unserialize(get_option('kbx_bot_hi_there')),
            'asking_name'               => maybe_unserialize(get_option('kbx_bot_asking_name')),
            'i_am'                      => maybe_unserialize(get_option('kbx_bot_i_am')),
            'name_greeting'             => maybe_unserialize(get_option('kbx_bot_name_greeting')),
            'wildcard_msg'              => maybe_unserialize(get_option('kbx_bot_wildcard_msg')),
            'articles_search_msg'       => maybe_unserialize(get_option('kbx_bot_articles_search_msg')),
            'empty_filter_msg'          => maybe_unserialize(get_option('kbx_bot_empty_filter_msg')),

            'articles_success'          => maybe_unserialize(get_option('kbx_bot_articles_success')),
            'articles_fail'             => maybe_unserialize(get_option('kbx_bot_articles_fail')),
            'catalog_suggest'           => maybe_unserialize(get_option('kbx_bot_catalog_suggest')),
            'is_typing'                 => maybe_unserialize(get_option('kbx_bot_is_typing')),
            'articles_infinite'         => maybe_unserialize(get_option('kbx_bot_articles_infinite_infinite')),

            'wildcard_artilces'         => maybe_unserialize(get_option('kbx_bot_wildcards_artilcs')),
            'wildcard_list'             => maybe_unserialize(get_option('kbx_bot_wildcards_list')),
            'wildcard_support'          => maybe_unserialize(get_option('kbx_bot_wildcard_support')),
            'wildcard_phone'            => maybe_unserialize(get_option('kbx_bot_articles_support_phone')),
            'support_welcome'           => maybe_unserialize(get_option('kbx_bot_support_welcome')),
            'support_email'             => maybe_unserialize(get_option('kbx_bot_support_email')),
            'asking_email'              => maybe_unserialize(get_option('kbx_bot_asking_email')),
            'asking_msg'                => maybe_unserialize(get_option('kbx_bot_asking_msg')),
            'asking_phone'              => maybe_unserialize(get_option('kbx_bot_articles_asking_phone')),
            'support_query'             => get_option('support_query'),
            'support_ans'               => get_option('support_ans'),
            'send_msg'                  => str_replace('\\', '',get_option('kbx_bot_send_msg')),
            'hello'                     => str_replace('\\', '',get_option('kbx_bot_hello')),
            'yes'                       => get_option('kbx_bot_yes'),
            'no'                        => get_option('kbx_bot_no'),
            'or'                        => get_option('kbx_bot_or'),
            'sorry'                     => get_option('kbx_bot_sorry'),
            //Help part
            'sys_key_help'              => get_option('kbx_bot_sys_key_help'),
            'sys_key_catalog'           => get_option('kbx_bot_sys_key_catalog'),
            'sys_key_support'           => get_option('kbx_bot_sys_key_support'),
            'invalid_email'             => maybe_unserialize(get_option('kbx_bot_invalid_email')),
            'sys_key_reset'             => get_option('kbx_bot_sys_key_reset'),
            'help_welcome'              => maybe_unserialize(get_option('kbx_bot_help_welcome')),
            'help_msg'                  => maybe_unserialize(get_option('kbx_bot_help_msg')),
            'reset'                     => maybe_unserialize(get_option('kbx_bot_reset')),
            'find_more'                 => maybe_unserialize(get_option('kbx_bot_articles_find_more')),
            'find_more_msg'             => maybe_unserialize(get_option('kbx_bot_articles_find_more_msg')),
            'support_phone'             => maybe_unserialize(get_option('kbx_bot_articles_support_phone')),
            'asking_phone'              => maybe_unserialize(get_option('kbx_bot_articles_asking_phone')),
            'stop_words'                => str_replace('\\', '', get_option('kbx_bot_stop_words')),
            'ai_df_enable'              => get_option('enable_kbx_bot_dailogflow'),
            'ai_df_token'               => get_option('qlcd_kbx_bot_dialogflow_client_token'),
            'df_defualt_reply'          => str_replace('\\', '', get_option('qlcd_kbx_bot_dialogflow_defualt_reply')),
            'custom_intent_enable'      => get_option('enable_kbx_bot_custom_intent'),
            'rich_response_enable'      => get_option('enable_kbx_bot_rich_response'),
            'disable_article_search'    => get_option('disable_kbx_bot_article_search'),
            'disable_article_list'      => get_option('disable_kbx_bot_article_list'),
            'disable_call_me'           => get_option('disable_kbx_bot_call_me'),
            'disable_support'           => get_option('disable_kbx_bot_support'),
        );
        wp_localize_script('kbx-general-js', 'kbx_bot_obj', $kbx_bot_obj );

        wp_enqueue_style( 'kbx-fontawesome-css', KBX_ASSETS_URL . '/css/font-awesome.min.css');
        wp_enqueue_style( 'kbx-jquery.modal.min-css', KBX_ASSETS_URL . '/css/jquery.modal.min.css');
        wp_enqueue_style( 'kbx-style-css', KBX_ASSETS_URL . '/css/style.css');

        global $kbx_options;
        if(isset($kbx_options['article_text_color'])){
            $text_color = $kbx_options['article_text_color'];
        }else{
            $text_color ='#212f3e';
        }
        if(isset($kbx_options['article_link_color'])){
            $link_color = $kbx_options['article_link_color'];
        }else{
            $link_color ='#107eec';
        }
        if(isset($kbx_options['article_link_hover_color'])){
            $link_hover_color = $kbx_options['article_link_hover_color'];
        }else{
            $link_hover_color ='#469aef';
        }
        

        if(isset($kbx_options['search_box_color'])){
            $search_box_color = $kbx_options['search_box_color'];
        }else{
            $search_box_color ='#ffffff';
        }
    
        if(isset($kbx_options['search_box_text_color'])){
            $search_box_text_color = $kbx_options['search_box_text_color'];
        }else{
            $search_box_text_color ='#000000';
        }

        $custom_css = "
                    #docsSearch {
                        background-color: ".$search_box_color." !important;
                    }
                    #docsSearch h2 {
                        color: ".$search_box_text_color." !important;
                    }
                    #docsSearch h2 span{
                        color: ".$search_box_text_color." !important;
                    }";

        // wp_add_inline_style( 'kbx-style-css', $custom_css );

        $custom_css .= ".kbx-glossary-keys, .kbx-outer-wrapper, .kbx-articles, .kbx-article-body {
                        color: ".$text_color." !important;
                    }

                    .kbx-glossary-keys a, .kbx-outer-wrapper a, .kbx-articles a {
                        color: ".$link_color." !important;
                    }

                    .kbx-glossary-keys a:hover, .kbx-outer-wrapper a:hover, .kbx-articles a:hover {
                        color: ".$link_hover_color." !important;
                    }

                    .category-list .category-box:hover a:after, .category-list .category-box:hover a:before {
                        border-color: ".$link_color." !important;
                    }";

        wp_add_inline_style( 'kbx-style-css', $custom_css );

        /*Custom CSS*/
        if( isset($kbx_options['custom_css']) && trim($kbx_options['custom_css']) != "" ) {
            $custom_css = $kbx_options['custom_css'];
            wp_add_inline_style( 'kbx-style-css', $custom_css );
        }


        wp_enqueue_style( 'kbx-general-css', KBX_ASSETS_URL . '/css/general.css');
        wp_enqueue_style( 'kbx-bot-style', KBX_ASSETS_URL . '/css/bot-style.css');

        $kbx_bot_theme = get_option('kbx_bot_theme');
        if (file_exists(KBX_DIR_ABS_PATH . '/views/bot-templates/' . $kbx_bot_theme . '/style.css')) {
            wp_enqueue_style( 'kbx-bot-template-css', KBX_URL . '/views/bot-templates/' . $kbx_bot_theme . '/style.css');
        }
        //Loading shortcode style
        if (file_exists(KBX_DIR_ABS_PATH . '/views/bot-templates/' . $kbx_bot_theme . '/shortcode.css')) {
            wp_enqueue_style( 'kbx-bot-shortcode-css',  KBX_URL .'/views/bot-templates/' . $kbx_bot_theme . '/shortcode.css');
        }

    }
    add_action( 'wp_enqueue_scripts', 'kbx_plugin_scripts' );
}

if ( ! function_exists( 'kbx__admin_scripts' ) ) {
    function kbx__admin_scripts(){
        //wp_enqueue_script('jquery');
        if (isset($_GET["page"]) && $_GET["page"] == "kbx-bot") {
            wp_enqueue_script('kbx-admin-tabs-js', KBX_ASSETS_URL . '/js/cbpFWTabs.js', array('jquery'));
            wp_enqueue_script('kbx-admin-bootstrap-js', KBX_ASSETS_URL . '/js/bootstrap.js', array('jquery'));
            wp_enqueue_script('kbx-bot-admin-js', KBX_ASSETS_URL . '/js/bot-admin.js', array('jquery', 'kbx-admin-tabs-js', 'kbx-admin-bootstrap-js','jquery-ui-sortable'));

            wp_enqueue_style('bot-admin-bootstrap', KBX_ASSETS_URL . '/css/bootstrap.min.css');
            wp_enqueue_style('bot-admin-tabs', KBX_ASSETS_URL . '/css/bot-admin-tabs.css');
            wp_enqueue_style('bot-admin-css', KBX_ASSETS_URL . '/css/bot-admin-style.css');
            // WordPress  Media library
            wp_enqueue_media();
        }

        if (isset($_GET["post_type"]) && $_GET["post_type"] == "kbx_knowledgebase") {
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('select2', KBX_ASSETS_URL . '/js/select2.js', array('jquery'));
            wp_enqueue_style('select2', KBX_ASSETS_URL . '/css/select2.css');
            wp_enqueue_script('kbx-admin-js', KBX_ASSETS_URL . '/js/kbx-admin.js', array());
        }
    	wp_enqueue_style('kbx_admin_css', KBX_ASSETS_URL . '/css/admin.css');
        //wp_enqueue_style('kbx-admin-css', KBX_ASSETS_URL . '/css/kbx-admin-style.css');
        $params = array(
            'strings' => array(
                'import_articles' => __( '<a target="_blank" style="text-decoration: none;" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/"><span class="page-title-action">Import Articles <span class="qc-up-pro-link" style="font-weight: bold; color: #FCB214"> pro</span> </span></a>', 'kbx-qc' ),
                'export_articles' => __( '<a target="_blank" style="text-decoration: none;" href="https://www.quantumcloud.com/products/knowledgebase-helpdesk/"><span class="page-title-action">Export Articles <span class="qc-up-pro-link" style="font-weight: bold; color: #FCB214">pro</span></span></a>', 'kbx-qc' ),
            )
        );

        wp_localize_script( 'kbx-admin-js', 'kbx_admin', $params );
    }
}
add_action('admin_enqueue_scripts','kbx__admin_scripts');



//getting kbxbot agent icon path
if ( ! function_exists( 'kbx_bot_agent_icon' ) ) {
    function kbx_bot_agent_icon(){
        if(get_option('kbx_bot_custom_agent_path')!="" && get_option('kbx_bot_agent_image') == "custom-agent.png"  ){
            $kbx_bot_custom_icon_path = get_option('kbx_bot_custom_agent_path');
        }else if(get_option('kbx_bot_custom_agent_path')!="" && get_option('kbx_bot_agent_image')!="custom-agent.png"){
            $kbx_bot_custom_icon_path = KBX_IMG_URL.'/'.get_option('kbx_bot_agent_image');
        }else{
            $kbx_bot_custom_icon_path = KBX_IMG_URL.'/custom-agent.png';
        }
        return $kbx_bot_custom_icon_path;
    }
}

