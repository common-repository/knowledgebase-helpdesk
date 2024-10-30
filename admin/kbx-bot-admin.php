<?php
defined('ABSPATH') or die("You can't access this file directly.");
/**
 * Register a custom help menu page.
 */
if ( ! function_exists( 'kbxhd_bot_admin_menu' ) ) {
    function kbxhd_bot_admin_menu(){

        $menu_slug = 'edit.php?post_type=kbx_knowledgebase';

        add_submenu_page(
            $menu_slug,
            __( 'Knowledgebase Bot', 'kbx-qc' ),
            __( 'Bot Settings', 'kbx-qc' ),
            'manage_options',
            'kbx-bot',
            'kbxhd_bot_admin_ui'
        );

    }
    //add_action( 'admin_menu', 'kbxhd_bot_admin_menu' );
}


if ((!empty($_GET["page"])) && ($_GET["page"] == "kbx-bot")) {

    add_action('admin_init','kbx_bot_save_options');
}

if ( ! function_exists( 'kbx_bot_save_options' ) ) {
    function kbx_bot_save_options(){
        
        if (isset($_POST['_wpnonce']) && $_POST['_wpnonce']) {


            wp_verify_nonce($_POST['_wpnonce'], 'kbx_bot');


            // Check if the form is submitted or not

            if (isset($_POST['submit'])) {
                //bot options
                $kbx_floating_search_bot = isset( $_POST['kbx_floating_search_bot'] ) ? $_POST['kbx_floating_search_bot'] : '';
                update_option('kbx_floating_search_bot', sanitize_text_field($kbx_floating_search_bot));
                if(isset($_POST['kbx_floating_search_on']) && ($_POST['kbx_floating_search_bot'] == 'float')){
                    $kbx_floating_search_on = $_POST['kbx_floating_search_on'];
                }else{
                    $kbx_floating_search_on="";
                }

                update_option('kbx_floating_search_on', sanitize_text_field($kbx_floating_search_on));

            }
        }
    }
}
