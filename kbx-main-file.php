<?php
/**
 * Plugin Name: Knowledgebase Helpdesk with AI ChatBot Helpdesk
 * Plugin URI: https://www.quantumcloud.com
 * Description: Advanced knowledgebase plugin with knowledgebase, helpdesk, glossary, ChatBot all in one.
 * Version: 3.6.4
 * Requires at least: 4.7
 * Tested up to: 6.6.2
 * Author: Knowledgebase Helpdesk
 * Author URI: https://www.quantumcloud.com
 * Text Domain: kbx-qc
 * License URI: https://www.quantumcloud.com
 * Domain Path: /languages
 */


// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via Administrator Access?" );
}


/**
 * Holds the URL for Knowledgebase-X
 *
 * @since	1.0
 *
 * @var string
 */

$kbx_url = plugins_url() . '/' . plugin_basename( dirname( __FILE__ ) );

//Custom Constants
if ( ! defined( 'KBX_VERSION' ) ) {
    define('KBX_VERSION', '3.6.3');
}

if ( ! defined( 'KBX_URL' ) ) {
	define('KBX_URL', plugin_dir_url(__FILE__));
}

if ( ! defined( 'KBX_ASSETS_URL' ) ) {
	define('KBX_ASSETS_URL', KBX_URL . "assets");
}

if ( ! defined( 'KBX_IMG_URL' ) ) {
    define('KBX_IMG_URL', KBX_ASSETS_URL . "/images");
}

if ( ! defined( 'KBX_DIR_ABS_PATH' ) ) {
define('KBX_DIR_ABS_PATH', plugin_dir_path(__FILE__));
}

if ( ! defined( 'KBX_IMG_ABS_PATH' ) ) {
    define('KBX_IMG_ABS_PATH', KBX_DIR_ABS_PATH . "assets/images");
}

if ( ! defined( 'KBX_DIR' ) ) {
	define('KBX_DIR', dirname(__FILE__));
}

if ( ! defined( 'KBX_PLUGIN_FILE' ) ) {
	define( 'KBX_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'kbx_adv_bot_plugin_main_file' ) ) {
    define('kbx_adv_bot_plugin_main_file', __FILE__);
}

/**
 * 
 * Declare $kbx_options global so that it can be accessed in every function
 *
 */

global $kbx_options;

$kbx_options = kbx_get_settings();

/**
 * 
 * Function to load translation files.
 *
 */
if ( ! function_exists( 'kbx_lang_init' ) ) {
    function kbx_lang_init() {
    	load_plugin_textdomain( 'kbx-qc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    }
    add_action( 'plugins_loaded', 'kbx_lang_init' );
}


/**
 * Get Settings.
 *
 * Retrieves all plugin settings
 * 
 * @return array wzkb settings
 */

function kbx_get_settings() {

	$settings = get_option( 'kbx_settings' );

	/**
	 * Settings array
	 *
	 * Retrieves all plugin settings
	 * 
	 * @param array $settings Settings array
	 */
	return apply_filters( 'wzkb_get_settings', $settings );
}



/*
 * ----------------------------------------------------------------------------*
 * Include files
 *----------------------------------------------------------------------------
 */

require_once( KBX_DIR . '/admin/kbx-register-settings.php' );
require_once( KBX_DIR . '/admin/kbx-shortcode-generator.php' );
require_once( KBX_DIR . '/includes/kbx-post-type.php' );
require_once( KBX_DIR . '/includes/kbx-add-post-meta.php' );
require_once( KBX_DIR . '/includes/kbx-shortcode.php' );
require_once( KBX_DIR . '/includes/kbx-ajax-search-question.php' );
require_once( KBX_DIR . '/includes/kbx-load-assets.php' );
require_once( KBX_DIR . '/includes/kbx-template-handling.php' );
require_once( KBX_DIR . '/includes/kbx-query.php' );
require_once( KBX_DIR . '/includes/kbx-utilities.php' );
require_once( KBX_DIR . '/includes/kbx-ajax-like-article.php' );
require_once( KBX_DIR . '/includes/kbx-widgets.php' );
require_once( KBX_DIR . '/includes/kbx-floating-search-widget.php' );
require_once( KBX_DIR . '/admin/kbx-articles-sections-ordering.php' );
require_once( KBX_DIR . '/kbx-wpbot/kbx-wpbot.php' );

if ( function_exists( 'register_block_type' ) ) {
	require_once( KBX_DIR . '/gutenberg/knowledgebase-blocks/plugin.php' );
}

require_once( 'kbx-help.php' );

//Support Page - Updated On - 06-01-2017
require_once('qc-support-promo-page/class-qc-support-promo-page.php');
require_once('qc-support-promo-page/class-qc-free-plugin-upgrade-notice.php');

/*
 ************************************************
 * Dashboard and Administrative Functionality
 ************************************************
 */
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {

	/**
	 *  Load the admin pages if we're in the Admin.
	 */
	require_once( KBX_DIR . '/admin/kbx-admin.php' );
	require_once( KBX_DIR . '/admin/kbx-bot-admin.php' );
	require_once( KBX_DIR . '/admin/kbx-bot-admin-ui.php' );
	require_once( KBX_DIR . '/admin/kbx-settings-page.php' );
	require_once( KBX_DIR . '/admin/category/kbx_category_image.php');
	require_once( KBX_DIR . '/admin/kbx-save-settings.php' );
	//if(isset($_GET['taxonomy']) && $_GET['taxonomy']=='kbx_category'&& isset($_GET['post_type']) && $_GET['post_type']=='kbx_knowledgebase'){
        //require_once( KBX_DIR . '/admin/kbx-articles-sections-ordering.php' );
    //}


} // End admin.inc


/*******************************
 * Filter title to add Breadcrumb
 *******************************/
if ( ! function_exists( 'kbx_modify_archive_query' ) ) {
    function kbx_modify_archive_query( $query ) {
    	
    	if( is_post_type_archive('kbx_knowledgebase') || is_tax( 'kbx_category' ) || is_tax( 'kbx_tag' ))
    	{
    		if( isset($_GET['sort']) &&  $_GET['sort'] != "" )
    		{
    			
    			$sortBy = sanitize_text_field( $_GET['sort'] );
                $orderby = 'date';
    			if( isset($sortBy) && $sortBy == 'name' ){
    				$orderby = 'title';
    				$order   = 'ASC';
    				$query->query_vars['order'] = $order;
    			}

    			if( isset($sortBy) && $sortBy == 'popularity' ){
    				$orderby  = array( 'meta_value_num' => 'DESC' );
    				$meta_key = 'kpm_upvotes';
    				$query->query_vars['meta_key'] = $meta_key;
    			}

    			if( isset($sortBy) && $sortBy == 'views' ){
    				$orderby  = array( 'meta_value_num' => 'DESC' );
    				$meta_key = 'kpm_views';
    				$query->query_vars['meta_key'] = $meta_key;
    			}
                
                if( isset($sortBy) && $sortBy == 'date' ){
                    $orderby = 'date';
                    $order   = 'ASC';
                    $query->query_vars['date'] = $order;
                }

    			$query->query_vars['orderby'] = $orderby;
    		}

    	}

    	return $query;
    }
    add_action( 'pre_get_posts', 'kbx_modify_archive_query' );
}


/**
 * Submenu filter function. Tested with Wordpress 4.7.3
 * Sort and order submenu positions to match your custom order.
 */
if ( ! function_exists( 'kbx_order_index_menu_page' ) ) {
    function kbx_order_index_menu_page( $menu_ord ) {

        global $submenu;

        // Enable the next line to see a specific menu and it's order positions
        //echo '<pre>'; print_r( $submenu['edit.php?post_type=kbx_knowledgebase'] ); echo '</pre>'; exit();

        $arr = array();
        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][5]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][5];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][10]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][10];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][15]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][15];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][16]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][16];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][18]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][18];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][19]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][19];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][24]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][24];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][25]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][25];
        }

        // $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][21];
        // $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][22];
      
        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][17]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][17];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][26]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][26];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][300]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][300];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][23]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][23];
        }

        if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][20]) ){
            $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][20];
        }

      
      /*
      if( isset($submenu['edit.php?post_type=kbx_knowledgebase'][300]) ){
        $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][300];
      }
      $arr[] = $submenu['edit.php?post_type=kbx_knowledgebase'][23];
      */

      $submenu['edit.php?post_type=kbx_knowledgebase'] = $arr;

      return $menu_ord;

    }
    add_filter( 'custom_menu_order', 'kbx_order_index_menu_page' );
}

// add the filter to wordpress

// if( ( !empty($_GET['page']) && $_GET["page"] == "kbx-settings") || ( !empty($_GET['page']) && $_GET['page'] == 'wpbot') || ( !empty($_GET['page']) && $_GET['page'] == 'wpbot_openAi') || ( !empty($_GET['page']) && $_GET['page'] == 'kbx-bot') || ( !empty($_GET['page']) && $_GET['page'] == 'kbx-help-kbhd-page')  ){
//  add_action( 'admin_notices', 'qcld_kbx_promotion_notice');
// }
if ( ! function_exists( 'qcld_kbx_promotion_notice' ) ) {
    function qcld_kbx_promotion_notice(){
        $promotion_img = KBX_IMG_URL . "/eid-24.gif";
        ?>
        <div data-dismiss-type="qcbot-feedback-notice" class="notice is-dismissible qcbot-feedback" style="background: #c13825">
            <div class="">
                
                <div class="qc-review-text" >
                <a href="<?php echo esc_url( "https://www.quantumcloud.com/products/knowledgebase-helpdesk/" ); ?>" target="_blank">
                    <img src="<?php echo esc_url( $promotion_img ); ?>" alt=""></a>
                </div>
            </div>
        </div>
        <?php
    }
}


//plugin activate redirect codecanyon
if ( ! function_exists( 'qc_kbx_activation_redirect' ) ) {
    function qc_kbx_activation_redirect( $plugin ) {
        $screen = get_current_screen();
        if( ( isset( $screen->base ) && $screen->base == 'plugins' ) && $plugin == plugin_basename( __FILE__ ) ) {
            if( $plugin == plugin_basename( __FILE__ ) ) {
                exit( wp_redirect( admin_url('edit.php?post_type=kbx_knowledgebase&page=kbx-help-kbhd-page') ) );
            }
        }
    }
    add_action( 'activated_plugin', 'qc_kbx_activation_redirect' );
}


//Adding upgrade to pro version notice
if ( ! function_exists( 'kbx_upgrade_to_pro_notice' ) ) {
    function kbx_upgrade_to_pro_notice() {
        $screen = get_current_screen();


        if ( is_admin() && ($screen->post_type == 'kbx_knowledgebase') ) {
            ?>
            <div class="notice notice-info is-dismissible woowbot-notice">
                <h4 style="text-align: center;"> <?php esc_html_e('The KB X Pro version is now available with Intelligent ChatBot Integrated with Google\'s DialogFlow,', 'kbx-qc') ?> <a href="<?php echo esc_url( "https://www.quantumcloud.com/products/knowledgebase-helpdesk/" ); ?>" target="_blank" style="text-decoration: none"> <strong style="color: orange"><?php esc_html_e('Upgrade to Pro', 'kbx-qc') ?></strong></a> </h4>
            </div>
            <?php
        }
    }
    //add_action( 'admin_notices', 'kbx_upgrade_to_pro_notice',100 );
}

require_once( KBX_DIR . '/includes/kbx-activate-deactivate.php' );


if ( ! function_exists( 'kbx_flush_rewrite_rules_on_plugin_activate' ) ) {
    register_activation_hook(  __FILE__, 'kbx_flush_rewrite_rules_on_plugin_activate' );
    function kbx_flush_rewrite_rules_on_plugin_activate(){
        
    	global $wpdb;
        if ( ! get_option( 'kbx_flush_rewrite_rules_flag' ) ) {
            add_option( 'kbx_flush_rewrite_rules_flag', true );
        }
    	$collate = '';
    	
    	if ( $wpdb->has_cap( 'collation' ) ) {

    		if ( ! empty( $wpdb->charset ) ) {

    			$collate .= "DEFAULT CHARACTER SET $wpdb->charset";
    		}
    		if ( ! empty( $wpdb->collate ) ) {

    			$collate .= " COLLATE $wpdb->collate";

    		}
    	}
    	
        //Bot User Table
        $table1    = $wpdb->prefix.'wpbot_sessions';
    	$sql_sliders_Table1 = "
    		CREATE TABLE IF NOT EXISTS `$table1` (
    		  `id` int(11) NOT NULL AUTO_INCREMENT,
    		  `session` int(11) NOT NULL,
    		  PRIMARY KEY (`id`)
    		)  $collate AUTO_INCREMENT=1 ";
    		
    	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_sliders_Table1 );
    	
    	//Bot Response Table
        $table1    = $wpdb->prefix.'wpbot_response';
        $sql_sliders_Table1 = "
            CREATE TABLE IF NOT EXISTS `$table1` (
            `id` INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
            `query` TEXT NOT NULL,
            `keyword` TEXT NOT NULL,
            `response` TEXT NOT NULL,
            `category` varchar(256) NOT NULL,
            `intent` varchar(256) NOT NULL,
            `custom` varchar(256) NOT NULL,
            FULLTEXT(`query`, `keyword`, `response`)
            )  $collate AUTO_INCREMENT=1 ENGINE=InnoDB";
            
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_sliders_Table1 );
    	
    	$sqlqry = $wpdb->get_results("select * from $table1");
    	if(empty($sqlqry)){
    	
    		$query    = esc_html('What Can WPBot do for you?', 'kbx-qc');
    		$response = esc_html('WPBot can converse fluidly with users on website and FB messenger. It can search your website, send/collect eMails, user feedback & phone numbers . You can create Custom Intents from DialogFlow with Rich Messages & Card responses!', 'kbx-qc');

    		$data = array('query' => $query, 'keyword' => '', 'response'=> $response, 'intent'=> '');
    		$format = array('%s','%s', '%s', '%s');
    		$wpdb->insert($table1,$data,$format);
    	}
    	
        $url = get_site_url();
        $url = parse_url($url);
        $domain = $url['host'];
        //$admin_email = "admin@" . $domain;
        $admin_email = get_option('admin_email');
        if(!get_option('kbx_floating_search_bot')) {
        	update_option('kbx_floating_search_bot', sanitize_text_field('wp-boat'));
        	update_option('kbx_floating_search_on', sanitize_text_field('1'));
        }
        if(!get_option('wp_chatbot_position_x')) {
            update_option('wp_chatbot_position_x', 50);
        }
        if(!get_option('wp_chatbot_position_y')) {
            update_option('wp_chatbot_position_y', 50);
        }
        if(!get_option('disable_wp_chatbot')) {
            update_option('disable_wp_chatbot', '');
        }
        if(!get_option('disable_wp_chatbot_icon_animation')) {
            update_option('disable_wp_chatbot_icon_animation', '');
        }
        if(!get_option('disable_wp_chatbot_on_mobile')) {
            update_option('disable_wp_chatbot_on_mobile', '');
        }
    	if(!get_option('qlcd_wp_chatbot_admin_email')) {
            update_option('qlcd_wp_chatbot_admin_email', get_option('admin_email'));
        }
        if(!get_option('disable_wp_chatbot_product_search')) {
            update_option('disable_wp_chatbot_product_search', '');
        }
        if(!get_option('disable_wp_chatbot_catalog')) {
            update_option('disable_wp_chatbot_catalog', '');
        }
        if(!get_option('disable_wp_chatbot_order_status')) {
            update_option('disable_wp_chatbot_order_status', '');
        }
        if(!get_option('enable_wp_chatbot_rtl')) {
            update_option('enable_wp_chatbot_rtl', '');
        }
    	if(!get_option('show_menu_after_greetings')) {
            update_option('show_menu_after_greetings', '');
        }
        if(!get_option('enable_wp_chatbot_mobile_full_screen')) {
            update_option('enable_wp_chatbot_mobile_full_screen', 1);
        }
        if(!get_option('wpbot_preloading_time')) {
            update_option('wpbot_preloading_time', '0.5');
        }

         if(!get_option('disable_wp_chatbot_notification')) {
            update_option('disable_wp_chatbot_notification', '1');
        }
        if(!get_option('disable_wp_chatbot_cart_item_number')) {
            update_option('disable_wp_chatbot_cart_item_number', '');
        }
        if(!get_option('disable_wp_chatbot_featured_product')) {
            update_option('disable_wp_chatbot_featured_product', '');
        }
        if(!get_option('disable_wp_chatbot_sale_product')) {
            update_option('disable_wp_chatbot_sale_product', '');
        }
         if(!get_option('wp_chatbot_open_product_detail')) {
            update_option('wp_chatbot_open_product_detail', '');
        }
        if(!get_option('qlcd_wp_chatbot_product_orderby')) {
            update_option('qlcd_wp_chatbot_product_orderby', sanitize_text_field('title'));
        }
        if(!get_option('qlcd_wp_chatbot_product_order')) {
            update_option('qlcd_wp_chatbot_product_order', sanitize_text_field('ASC'));
        }
        if(!get_option('qlcd_wp_chatbot_ppp')) {
            update_option('qlcd_wp_chatbot_ppp', intval(6));
        }
        if(!get_option('wp_chatbot_exclude_stock_out_product')) {
            update_option('wp_chatbot_exclude_stock_out_product', '');
        }
        if(!get_option('wp_chatbot_show_sub_category')) {
            update_option('wp_chatbot_show_sub_category', '');
        }
        if(!get_option('wp_chatbot_vertical_custom')){
            update_option('wp_chatbot_vertical_custom', 'Go To');
        }
        if(!get_option('wp_chatbot_show_home_page')) {
            update_option('wp_chatbot_show_home_page', 'on');
        }
    	if(!get_option('qc_wpbot_menu_order')) {
            update_option('qc_wpbot_menu_order', '');
        }
    	
        if(!get_option('wp_chatbot_show_posts')) {
            update_option('wp_chatbot_show_posts', 'on');
        }
        if(!get_option('wp_chatbot_show_pages')){
            update_option('wp_chatbot_show_pages', 'on');
        }
        if(!get_option('wp_chatbot_show_pages_list')) {
            update_option('wp_chatbot_show_pages_list', serialize(array()));
        }
        if(!get_option('wp_chatbot_show_wpcommerce')) {
            update_option('wp_chatbot_show_wpcommerce', 'on');
        }
        if(!get_option('qlcd_wp_chatbot_stop_words_name')) {
            update_option('qlcd_wp_chatbot_stop_words_name', 'english');
        }
        if(!get_option('qlcd_wp_chatbot_stop_words')) {
            update_option('qlcd_wp_chatbot_stop_words', "a,able,about,above,abst,accordance,according,accordingly,across,act,actually,added,adj,affected,affecting,affects,after,afterwards,again,against,ah,all,almost,alone,along,already,also,although,always,am,among,amongst,an,and,announce,another,any,anybody,anyhow,anymore,anyone,anything,anyway,anyways,anywhere,apparently,approximately,are,aren,arent,arise,around,as,aside,ask,asking,at,auth,available,away,awfully,b,back,be,became,because,become,becomes,becoming,been,before,beforehand,begin,beginning,beginnings,begins,behind,being,believe,below,beside,besides,between,beyond,biol,both,brief,briefly,but,by,c,ca,came,can,cannot,can't,cause,causes,certain,certainly,co,com,come,comes,contain,containing,contains,could,couldnt,d,date,did,didn't,different,do,does,doesn't,doing,done,don't,down,downwards,due,during,e,each,ed,edu,effect,eg,eight,eighty,either,else,elsewhere,end,ending,enough,especially,et,et-al,etc,even,ever,every,everybody,everyone,everything,everywhere,ex,except,f,far,few,ff,fifth,first,five,fix,followed,following,follows,for,former,formerly,forth,found,four,from,further,furthermore,g,gave,get,gets,getting,give,given,gives,giving,go,goes,gone,got,gotten,h,had,happens,hardly,has,hasn't,have,haven't,having,he,hed,hence,her,here,hereafter,hereby,herein,heres,hereupon,hers,herself,hes,hi,hid,him,himself,his,hither,home,how,howbeit,however,hundred,i,id,ie,if,i'll,im,immediate,immediately,importance,important,in,inc,indeed,index,information,instead,into,invention,inward,is,isn't,it,itd,it'll,its,itself,i've,j,just,k,keep,keeps,kept,kg,km,know,known,knows,l,largely,last,lately,later,latter,latterly,least,less,lest,let,lets,like,liked,likely,line,little,'ll,look,looking,looks,ltd,m,made,mainly,make,makes,many,may,maybe,me,mean,means,meantime,meanwhile,merely,mg,might,million,miss,ml,more,moreover,most,mostly,mr,mrs,much,mug,must,my,myself,n,na,name,namely,nay,nd,near,nearly,necessarily,necessary,need,needs,neither,never,nevertheless,new,next,nine,ninety,no,nobody,non,none,nonetheless,noone,nor,normally,nos,not,noted,nothing,now,nowhere,o,obtain,obtained,obviously,of,off,often,oh,ok,okay,old,omitted,on,once,one,ones,only,onto,or,ord,other,others,otherwise,ought,our,ours,ourselves,out,outside,over,overall,owing,own,p,page,pages,part,particular,particularly,past,per,perhaps,placed,please,plus,poorly,possible,possibly,potentially,pp,predominantly,present,previously,primarily,probably,promptly,proud,provides,put,q,que,quickly,quite,qv,r,ran,rather,rd,re,readily,really,recent,recently,ref,refs,regarding,regardless,regards,related,relatively,research,respectively,resulted,resulting,results,right,run,s,said,same,saw,say,saying,says,sec,section,see,seeing,seem,seemed,seeming,seems,seen,self,selves,sent,seven,several,shall,she,shed,she'll,shes,should,shouldn't,show,showed,shown,showns,shows,significant,significantly,similar,similarly,since,six,slightly,so,some,somebody,somehow,someone,somethan,something,sometime,sometimes,somewhat,somewhere,soon,sorry,specifically,specified,specify,specifying,still,stop,strongly,sub,substantially,successfully,such,sufficiently,suggest,sup,sure,t,take,taken,taking,tell,tends,th,than,thank,thanks,thanx,that,that'll,thats,that've,the,their,theirs,them,themselves,then,thence,there,thereafter,thereby,thered,therefore,therein,there'll,thereof,therere,theres,thereto,thereupon,there've,these,they,theyd,they'll,theyre,they've,think,this,those,thou,though,thoughh,thousand,throug,through,throughout,thru,thus,til,tip,to,together,too,took,toward,towards,tried,tries,truly,try,trying,ts,twice,two,u,un,under,unfortunately,unless,unlike,unlikely,until,unto,up,upon,ups,us,use,used,useful,usefully,usefulness,uses,using,usually,v,value,various,'ve,very,via,viz,vol,vols,vs,w,want,wants,was,wasnt,way,we,wed,welcome,we'll,went,were,werent,we've,what,whatever,what'll,whats,when,whence,whenever,where,whereafter,whereas,whereby,wherein,wheres,whereupon,wherever,whether,which,while,whim,whither,who,whod,whoever,whole,who'll,whom,whomever,whos,whose,why,widely,willing,wish,with,within,without,wont,words,world,would,wouldnt,www,x,y,yes,yet,you,youd,you'll,your,youre,yours,yourself,yourselves,you've,z,zero");
        }
        if(!get_option('qlcd_wp_chatbot_order_user')) {
            update_option('qlcd_wp_chatbot_order_user', sanitize_text_field('login'));
        }
        if(!get_option('wp_chatbot_custom_agent_path')) {
            update_option('wp_chatbot_custom_agent_path', '');
        }
        if(!get_option('wp_chatbot_custom_icon_path')) {
            update_option('wp_chatbot_custom_icon_path', '');
        }

        if(!get_option('wp_chatbot_icon')) {
            update_option('wp_chatbot_icon', sanitize_text_field('icon-13.png'));
        }
    	if(!get_option('wp_chatbot_floatingiconbg_color')) {
            update_option('wp_chatbot_floatingiconbg_color', '#fff');
        }
        if(!get_option('wp_chatbot_agent_image')){
            update_option('wp_chatbot_agent_image',sanitize_text_field('agent-0.png'));
        }
        if(!get_option('qcld_wb_chatbot_theme')) {
            update_option('qcld_wb_chatbot_theme', sanitize_text_field('template-00'));
        }
        if(!get_option('qcld_wb_chatbot_change_bg')) {
            update_option('qcld_wb_chatbot_change_bg', '');
        }
        if(!get_option('wp_chatbot_custom_css')) {
            update_option('wp_chatbot_custom_css', '');
        }
        if(!get_option('qlcd_wp_chatbot_host')) {
            update_option('qlcd_wp_chatbot_host', sanitize_text_field('Our Website'));
        }
        if(!get_option('qlcd_wp_chatbot_agent')) {
            update_option('qlcd_wp_chatbot_agent', sanitize_text_field('Carrie'));
        }
        if(!get_option('qlcd_wp_chatbot_host')) {
            update_option('qlcd_wp_chatbot_host', sanitize_text_field('Our Website'));
        }
        if(!get_option('qlcd_wp_chatbot_shopper_demo_name')) {
            update_option('qlcd_wp_chatbot_shopper_demo_name', sanitize_text_field('Amigo'));
        }
        if(!get_option('qlcd_wp_chatbot_yes')) {
            update_option('qlcd_wp_chatbot_yes', sanitize_text_field('YES'));
        }
        if(!get_option('qlcd_wp_chatbot_no')) {
            update_option('qlcd_wp_chatbot_no', sanitize_text_field('NO'));
        }
        if(!get_option('qlcd_wp_chatbot_or')) {
            update_option('qlcd_wp_chatbot_or', sanitize_text_field('OR'));
        }
        if(!get_option('qlcd_wp_chatbot_sorry')) {
            update_option('qlcd_wp_chatbot_sorry', sanitize_text_field('Sorry'));
        }
    	
    	 if(!get_option('qlcd_wp_chatbot_dialogflow_project_id')) {
            update_option('qlcd_wp_chatbot_dialogflow_project_id', '');
        }
        if(!get_option('wp_chatbot_df_api')) {
            update_option('wp_chatbot_df_api', 'v1');
        }

        
        if(!get_option('qlcd_wp_chatbot_dialogflow_project_key')) {
            update_option('qlcd_wp_chatbot_dialogflow_project_key', '');
        }
    	
        if(!get_option('qlcd_wp_chatbot_agent_join')) {
            update_option('qlcd_wp_chatbot_agent_join', serialize(array('has joined the conversation')));
        }
        if(!get_option('qlcd_wp_chatbot_welcome')) {
            update_option('qlcd_wp_chatbot_welcome', serialize(array('Welcome to', 'Glad to have you at')));
        }
        if(!get_option('qlcd_wp_chatbot_back_to_start')) {
            update_option('qlcd_wp_chatbot_back_to_start', serialize(array('Back to Start')));
        }
        if(!get_option('qlcd_wp_chatbot_hi_there')) {
            update_option('qlcd_wp_chatbot_hi_there', serialize(array('Hi There!')));
        }
        if(!get_option('qlcd_wp_chatbot_welcome_back')) {
            update_option('qlcd_wp_chatbot_welcome_back', serialize(array('Welcome back', 'Good to see your again')));
        }
        if(!get_option('qlcd_wp_chatbot_asking_name')) {
            update_option('qlcd_wp_chatbot_asking_name', serialize(array('May I know your name?', 'What should I call you?')));
        }
        if(!get_option('qlcd_wp_chatbot_name_greeting')) {
            update_option('qlcd_wp_chatbot_name_greeting', serialize(array('Nice to meet you')));
        }
        if(!get_option('qlcd_wp_chatbot_i_am')) {
            update_option('qlcd_wp_chatbot_i_am', serialize(array('I am', 'This is')));
        }
        if(!get_option('qlcd_wp_chatbot_is_typing')) {
            update_option('qlcd_wp_chatbot_is_typing', serialize(array('is typing...')));
        }
        if(!get_option('qlcd_wp_chatbot_send_a_msg')) {
            update_option('qlcd_wp_chatbot_send_a_msg', serialize(array('Send a message.')));
        }
        if(!get_option('qlcd_wp_chatbot_choose_option')) {
            update_option('qlcd_wp_chatbot_choose_option', serialize(array('Choose an option.')));
        }
        if(!get_option('qlcd_wp_chatbot_viewed_products')) {
            update_option('qlcd_wp_chatbot_viewed_products', serialize(array('Recently viewed products')));
        }
        if(!get_option('qlcd_wp_chatbot_add_to_cart')) {
            update_option('qlcd_wp_chatbot_add_to_cart', serialize(array('Add to Cart')));
        }
        if(!get_option('qlcd_wp_chatbot_cart_link')) {
            update_option('qlcd_wp_chatbot_cart_link', serialize(array('Cart')));
        }
        if(!get_option('qlcd_wp_chatbot_checkout_link')) {
            update_option('qlcd_wp_chatbot_checkout_link', serialize(array('Checkout')));
        }
        if(!get_option('qlcd_wp_chatbot_featured_product_welcome')) {
            update_option('qlcd_wp_chatbot_featured_product_welcome', serialize(array('I have found following featured products')));
        }
        if(!get_option('qlcd_wp_chatbot_viewed_product_welcome')) {
            update_option('qlcd_wp_chatbot_viewed_product_welcome', serialize(array('I have found following recently viewed products')));
        }
        if(!get_option('qlcd_wp_chatbot_latest_product_welcome')) {
            update_option('qlcd_wp_chatbot_latest_product_welcome', serialize(array('I have found following latest products')));
        }
        if(!get_option('qlcd_wp_chatbot_cart_welcome')) {
            update_option('qlcd_wp_chatbot_cart_welcome', serialize(array('I have found following items from Shopping Cart.')));
        }
        if(!get_option('qlcd_wp_chatbot_cart_title')) {
            update_option('qlcd_wp_chatbot_cart_title', serialize(array('Title')));
        }
        if(!get_option('qlcd_wp_chatbot_cart_quantity')) {
            update_option('qlcd_wp_chatbot_cart_quantity', serialize(array('Qty')));
        }
        if(!get_option('qlcd_wp_chatbot_cart_price')) {
            update_option('qlcd_wp_chatbot_cart_price', serialize(array('Price')));
        }
        if(!get_option('qlcd_wp_chatbot_no_cart_items')) {
            update_option('qlcd_wp_chatbot_no_cart_items', serialize(array('No items in the cart')));
        }
        if(!get_option('qlcd_wp_chatbot_cart_updating')) {
            update_option('qlcd_wp_chatbot_cart_updating', serialize(array('Updating cart items ...')));
        }
        if(!get_option('qlcd_wp_chatbot_cart_removing')) {
            update_option('qlcd_wp_chatbot_cart_removing', serialize(array('Removing cart items ...')));
        }
        if(!get_option('qlcd_wp_chatbot_wildcard_msg')) {
            update_option('qlcd_wp_chatbot_wildcard_msg', serialize(array('I am here to find what you need. What are you looking for?')));
        }
        if(!get_option('qlcd_wp_chatbot_empty_filter_msg')) {
            update_option('qlcd_wp_chatbot_empty_filter_msg', serialize(array('Sorry, I did not understand you.')));
        }
    	if(!get_option('qlcd_wp_chatbot_did_you_mean')) {
            update_option('qlcd_wp_chatbot_did_you_mean', serialize(array('Did you mean?')));
        }
        if(!get_option('qlcd_wp_chatbot_sys_key_help')) {
            update_option('qlcd_wp_chatbot_sys_key_help', 'start');
        }
        if(!get_option('qlcd_wp_chatbot_sys_key_product')) {
            update_option('qlcd_wp_chatbot_sys_key_product', 'product');
        }
        if(!get_option('qlcd_wp_chatbot_sys_key_catalog')) {
            update_option('qlcd_wp_chatbot_sys_key_catalog', 'catalog');
        }
        if(!get_option('qlcd_wp_chatbot_sys_key_order')) {
            update_option('qlcd_wp_chatbot_sys_key_order', 'order');
        }
        if(!get_option('qlcd_wp_chatbot_sys_key_support')) {
            update_option('qlcd_wp_chatbot_sys_key_support', 'faq');
        }
        if(!get_option('qlcd_wp_chatbot_sys_key_reset')) {
            update_option('qlcd_wp_chatbot_sys_key_reset', 'reset');
        }
        if(!get_option('qlcd_wp_chatbot_help_welcome')) {
            update_option('qlcd_wp_chatbot_help_welcome', serialize(array('Welcome to Help Section.')));
        }
        if(!get_option('qlcd_wp_chatbot_help_msg')) {
            update_option('qlcd_wp_chatbot_help_msg', serialize(array('<h3>Type and Hit Enter</h3>  1. <b>start</b> Get back to the main menu. <br>  2. <b>faq</b> for  FAQ. <br> 3. <b>eMail </b> to Send eMail <br> 4. <b>reset</b> To clear chat history and start from the beginning.')));
         }
        if(!get_option('qlcd_wp_chatbot_reset')) {
            update_option('qlcd_wp_chatbot_reset', serialize(array('Do you want to clear our chat history and start over?')));
        }
        if(!get_option('qlcd_wp_chatbot_wildcard_product')) {
            update_option('qlcd_wp_chatbot_wildcard_product', serialize(array('Product Search')));
        }
        if(!get_option('qlcd_wp_chatbot_wildcard_catalog')) {
            update_option('qlcd_wp_chatbot_wildcard_catalog', serialize(array('Catalog')));
        }
        if(!get_option('qlcd_wp_chatbot_featured_products')) {
            update_option('qlcd_wp_chatbot_featured_products', serialize(array('Featured Products')));
        }
        if(!get_option('qlcd_wp_chatbot_sale_products')) {
            update_option('qlcd_wp_chatbot_sale_products', serialize(array('Products on  Sale')));
        }
        if(!get_option('qlcd_wp_chatbot_wildcard_support')) {
            update_option('qlcd_wp_chatbot_wildcard_support', 'FAQ');
        }
      if(!get_option('qlcd_wp_chatbot_messenger_label')) {
            update_option('qlcd_wp_chatbot_messenger_label', serialize(array('Chat with Us on Facebook Messenger')));
        }
        if(!get_option('qlcd_wp_chatbot_product_success')) {
            update_option('qlcd_wp_chatbot_product_success', serialize(array('Great! We have these products for', 'Found these products for')));
        }
        if(!get_option('qlcd_wp_chatbot_product_fail')) {
            update_option('qlcd_wp_chatbot_product_fail', serialize(array('Oops! Nothing matches your criteria', 'Sorry, I found nothing')));
        }
        if(!get_option('qlcd_wp_chatbot_product_asking')) {
            update_option('qlcd_wp_chatbot_product_asking', serialize(array('What are you shopping for?')));
        }
        if(!get_option('qlcd_wp_chatbot_product_suggest')) {
            update_option('qlcd_wp_chatbot_product_suggest', serialize(array('You can browse our extensive catalog. Just pick a category from below:')));
        }
        if(!get_option('qlcd_wp_chatbot_product_infinite')) {
            update_option('qlcd_wp_chatbot_product_infinite', serialize(array('Too many choices? Let\'s try another search term', 'I may have something else for you. Why not search again?')));
        }
        if(!get_option('qlcd_wp_chatbot_load_more')) {
            update_option('qlcd_wp_chatbot_load_more', serialize(array('Load More')));
        }
        if(!get_option('qlcd_wp_chatbot_wildcard_order')) {
            update_option('qlcd_wp_chatbot_wildcard_order', serialize(array('Order Status')));
        }
        if(!get_option('qlcd_wp_chatbot_order_welcome')) {
            update_option('qlcd_wp_chatbot_order_welcome', serialize(array('Welcome to Order status section!')));
        }
        if(!get_option('qlcd_wp_chatbot_order_username_asking')) {
            update_option('qlcd_wp_chatbot_order_username_asking', serialize(array('Please type your username?')));
        }
        if(!get_option('qlcd_wp_chatbot_order_username_password')) {
            update_option('qlcd_wp_chatbot_order_username_password', serialize(array('Please type your password')));
        }
        if(!get_option('qlcd_wp_chatbot_order_username_not_exist')) {
            update_option('qlcd_wp_chatbot_order_username_not_exist', serialize(array('This username does not exist.')));
        }
        if(!get_option('qlcd_wp_chatbot_order_username_thanks')) {
            update_option('qlcd_wp_chatbot_order_username_thanks', serialize(array('Thank you for the username')));
        }
        if(!get_option('qlcd_wp_chatbot_order_password_incorrect')) {
            update_option('qlcd_wp_chatbot_order_password_incorrect', serialize(array('Sorry Password is not correct!')));
        }
        if(!get_option('qlcd_wp_chatbot_asking_email')) {
            update_option('qlcd_wp_chatbot_asking_email', serialize(array('Please provide your email address')));
        }
        if(!get_option('qlcd_wp_chatbot_order_not_found')) {
            update_option('qlcd_wp_chatbot_order_not_found', serialize(array('I did not find any order by you')));
        }
         if(!get_option('qlcd_wp_chatbot_order_found')) {
            update_option('qlcd_wp_chatbot_order_found', serialize(array('I have found the following orders')));
        }
        if(!get_option('qlcd_wp_chatbot_order_email_support')) {
            update_option('qlcd_wp_chatbot_order_email_support', serialize(array('Email our support center about your order.')));
        }
        if(!get_option('qlcd_wp_chatbot_support_welcome')) {
            update_option('qlcd_wp_chatbot_support_welcome', serialize(array('Welcome to FAQ Section')));
        }
        if(!get_option('qlcd_wp_chatbot_support_email')) {
            update_option('qlcd_wp_chatbot_support_email', 'Send us Email.');
        }
        if(!get_option('qlcd_wp_chatbot_asking_msg')) {
            update_option('qlcd_wp_chatbot_asking_msg', serialize(array('Thank you for email address. Please write your message now.')));
        }
    	if(!get_option('qlcd_wp_chatbot_no_result')) {
            update_option('qlcd_wp_chatbot_no_result', serialize(array('Sorry, No result found!')));
        }
        if(!get_option('qlcd_wp_chatbot_invalid_email')) {
            update_option('qlcd_wp_chatbot_invalid_email', serialize(array('Sorry, Email address is not valid! Please provide a valid email.')));
        }
        if(!get_option('qlcd_wp_chatbot_support_phone')) {
            update_option('qlcd_wp_chatbot_support_phone', 'Leave your number. We will call you back!');
        }
        if(!get_option('qlcd_wp_chatbot_asking_phone')) {
            update_option('qlcd_wp_chatbot_asking_phone', serialize(array('Please provide your Phone number')));
        }
        if(!get_option('qlcd_wp_chatbot_thank_for_phone')) {
            update_option('qlcd_wp_chatbot_thank_for_phone', serialize(array('Thank you for Phone number')));
        }
        if(!get_option('qlcd_wp_chatbot_support_option_again')) {
            update_option('qlcd_wp_chatbot_support_option_again', serialize(array('You may choose option from below.')));
        }
        if(!get_option('qlcd_wp_chatbot_admin_email')) {
            update_option('qlcd_wp_chatbot_admin_email', $admin_email);
        }
        if(!get_option('qlcd_wp_chatbot_email_sub')) {
            update_option('qlcd_wp_chatbot_email_sub', sanitize_text_field('WPBot Support Mail'));
        }
    	if(!get_option('qlcd_wp_site_search')) {
            update_option('qlcd_wp_site_search', sanitize_text_field('Site Search'));
        }
        if(!get_option('qlcd_wp_chatbot_email_sent')) {
            update_option('qlcd_wp_chatbot_email_sent', sanitize_text_field('Your email was sent successfully.Thanks!'));
        }
        if(!get_option('qlcd_wp_chatbot_email_fail')) {
            update_option('qlcd_wp_chatbot_email_fail', sanitize_text_field('Sorry! I could not send your mail! Please contact the webmaster.'));
        }
        if(!get_option('qlcd_wp_chatbot_notification_interval')) {
            update_option('qlcd_wp_chatbot_notification_interval', sanitize_text_field(5));
        }
        if(!get_option('qlcd_wp_chatbot_notifications')) {
            update_option('qlcd_wp_chatbot_notifications', serialize(array('Welcome to WPBot')));
        }
        if(!get_option('support_query')) {
            update_option('support_query', serialize(array('What is WPBot?')));
        }
        if(!get_option('support_ans')) {
            update_option('support_ans', serialize(array('WPBot is a stand alone Chat Bot with zero configuration or bot training required. This plug and play chatbot also does not require any 3rd party service integration like Facebook. This chat bot helps shoppers find the products they are looking for easily and increase store sales! WPBot is a must have plugin for trending conversational commerce or conversational shopping.')));
        }
        if(!get_option('qlcd_wp_chatbot_search_option')) {
            update_option('qlcd_wp_chatbot_search_option', 'standard');
        }
        if(!get_option('wp_chatbot_index_count')) {
            update_option('wp_chatbot_index_count', 0);
        }
        if(!get_option('wp_chatbot_app_pages')) {
            update_option('wp_chatbot_app_pages', 0);
        }
        //messenger options.
        if(!get_option('enable_wp_chatbot_messenger')) {
            update_option('enable_wp_chatbot_messenger', '');
        }
        if(!get_option('enable_wp_chatbot_messenger_floating_icon')) {
            update_option('enable_wp_chatbot_messenger_floating_icon', '');
        }
        if(!get_option('qlcd_wp_chatbot_fb_app_id')) {
            update_option('qlcd_wp_chatbot_fb_app_id', '');
        }
        if(!get_option('qlcd_wp_chatbot_fb_page_id')) {
            update_option('qlcd_wp_chatbot_fb_page_id', '');
        }
        if(!get_option('qlcd_wp_chatbot_fb_color')) {
            update_option('qlcd_wp_chatbot_fb_color', '#0084ff');
        }
        if(!get_option('qlcd_wp_chatbot_fb_in_msg')) {
            update_option('qlcd_wp_chatbot_fb_in_msg', 'Welcome to WPBot!');
        }
        if(!get_option('qlcd_wp_chatbot_fb_out_msg')) {
            update_option('qlcd_wp_chatbot_fb_out_msg', 'You are not logged in');
        }
        //Skype option
        if(!get_option('enable_wp_chatbot_skype_floating_icon')) {
            update_option('enable_wp_chatbot_skype_floating_icon', '');
        }
        if(!get_option('enable_wp_chatbot_skype_id')) {
            update_option('enable_wp_chatbot_skype_id', '');
        }
         //Whats App
        if(!get_option('enable_wp_chatbot_whats')) {
            update_option('enable_wp_chatbot_whats', '');
        }
        if(!get_option('qlcd_wp_chatbot_whats_label')) {
            update_option('qlcd_wp_chatbot_whats_label', serialize(array('Chat with Us on WhatsApp')));
        }
        if(!get_option('enable_wp_chatbot_floating_whats')) {
            update_option('enable_wp_chatbot_floating_whats', '');
        }
        if(!get_option('qlcd_wp_chatbot_whats_num')) {
            update_option('qlcd_wp_chatbot_whats_num', '');
        }
        //Viber
        if(!get_option('enable_wp_chatbot_floating_viber')) {
            update_option('enable_wp_chatbot_floating_viber', '');
        }
        if(!get_option('qlcd_wp_chatbot_viber_acc')) {
            update_option('qlcd_wp_chatbot_viber_acc', '');
        }
        //Integration others
        if(!get_option('enable_wp_chatbot_floating_phone')) {
            update_option('enable_wp_chatbot_floating_phone', '');
        }
        if(!get_option('qlcd_wp_chatbot_phone')) {
            update_option('qlcd_wp_chatbot_phone', '');
        }
        if(!get_option('enable_wp_chatbot_floating_link')) {
            update_option('enable_wp_chatbot_floating_link', '');
        }

        if(!get_option('qlcd_wp_chatbot_weblink')) {
            update_option('qlcd_wp_chatbot_weblink', '');
        }
        //Re-Tagetting
        if(!get_option('qlcd_wp_chatbot_ret_greet')) {
            update_option('qlcd_wp_chatbot_ret_greet', 'Hello');
        }
        if(!get_option('enable_wp_chatbot_exit_intent')) {
            update_option('enable_wp_chatbot_exit_intent', '');
        }
        if(!get_option('wp_chatbot_exit_intent_msg')) {
            update_option('wp_chatbot_exit_intent_msg', 'WAIT, WE HAVE A SPECIAL OFFER FOR YOU! Get Your 50% Discount Now. Use Coupon Code QC50 during checkout.');
        }
        if(!get_option('wp_chatbot_exit_intent_once')) {
            update_option('wp_chatbot_exit_intent_once', '');
        }

        if(!get_option('enable_wp_chatbot_scroll_open')) {
            update_option('enable_wp_chatbot_scroll_open', '');
        }
        if(!get_option('wp_chatbot_scroll_open_msg')) {
            update_option('wp_chatbot_scroll_open_msg', 'WE HAVE A VERY SPECIAL OFFER FOR YOU! Get Your 50% Discount Now. Use Coupon Code QC50 during checkout.');
        }
        if(!get_option('wp_chatbot_scroll_percent')) {
            update_option('wp_chatbot_scroll_percent', 50);
        }
        if(!get_option('wp_chatbot_scroll_once')) {
            update_option('wp_chatbot_scroll_once', '');
        }

        if(!get_option('enable_wp_chatbot_auto_open')) {
            update_option('enable_wp_chatbot_auto_open', '');
        }

        if(!get_option('enable_wp_chatbot_ret_sound')) {
            update_option('enable_wp_chatbot_ret_sound', '');
        }
        if(!get_option('enable_wp_chatbot_sound_initial')) {
            update_option('enable_wp_chatbot_sound_initial', '');
        }

        if(!get_option('wp_chatbot_auto_open_msg')) {
            update_option('wp_chatbot_auto_open_msg', 'A SPECIAL OFFER FOR YOU! Get Your 50% Discount Now. Use Coupon Code QC50 during checkout.');
        }
        if(!get_option('wp_chatbot_auto_open_time')) {
            update_option('wp_chatbot_auto_open_time', 10);
        }
        if(!get_option('wp_chatbot_auto_open_once')) {
            update_option('wp_chatbot_auto_open_once', '');
        }
        if(!get_option('wp_chatbot_inactive_once')) {
            update_option('wp_chatbot_inactive_once', '');
        }

        //To complete checkout.
        if(!get_option('enable_wp_chatbot_ret_user_show')) {
            update_option('enable_wp_chatbot_ret_user_show', '');
        }
        if(!get_option('wp_chatbot_auto_open_msg')) {
            update_option('wp_chatbot_checkout_msg', 'You have products in shopping cart, please complete your order.');
        }
        if(!get_option('wp_chatbot_inactive_time')) {
            update_option('wp_chatbot_inactive_time', 300);
        }
        if(!get_option('enable_wp_chatbot_inactive_time_show')) {
            update_option('enable_wp_chatbot_inactive_time_show', '');
        }

        if(!get_option('wp_chatbot_proactive_bg_color')) {
            update_option('wp_chatbot_proactive_bg_color', '#ffffff');
        }
        if(!get_option('disable_wp_chatbot_feedback')) {
            update_option('disable_wp_chatbot_feedback','');
        }
    	if(!get_option('disable_wp_chatbot_faq')) {
            update_option('disable_wp_chatbot_faq','');
        }
        if(!get_option('qlcd_wp_chatbot_feedback_label')) {
            update_option('qlcd_wp_chatbot_feedback_label',serialize(array('Send Feedback')));
        }

        if(!get_option('enable_wp_chatbot_meta_title')) {
            update_option('enable_wp_chatbot_meta_title','');
        }
        if(!get_option('qlcd_wp_chatbot_meta_label')) {
            update_option('qlcd_wp_chatbot_meta_label','*New Messages');
        }

        if(!get_option('disable_wp_chatbot_call_gen')) {
            update_option('disable_wp_chatbot_call_gen', '');
        }
    	
    	if(!get_option('disable_wp_chatbot_site_search')) {
            update_option('disable_wp_chatbot_site_search', '');
        }
        if(!get_option('disable_wp_chatbot_call_sup')) {
            update_option('disable_wp_chatbot_call_sup', '');
        }

        if(!get_option('qlcd_wp_chatbot_phone_sent')) {
            update_option('qlcd_wp_chatbot_phone_sent',  'Thanks for your phone number. We will call you ASAP!');
        }
        if(!get_option('qlcd_wp_chatbot_phone_fail')) {
            update_option('qlcd_wp_chatbot_phone_fail', 'Sorry! I could not collect your phone number!');
        }
        if(!get_option('enable_wp_chatbot_opening_hour')) {
            update_option('enable_wp_chatbot_opening_hour', '');
        }
        if(!get_option('enable_wp_chatbot_opening_hour')) {
            update_option('wpwbot_hours', array());
        }

        if(!get_option('enable_wp_chatbot_dailogflow')) {
            update_option('enable_wp_chatbot_dailogflow', '');
        }
        if(!get_option('qlcd_wp_chatbot_dialogflow_client_token')) {
            update_option('qlcd_wp_chatbot_dialogflow_client_token', '');
        }
        if(!get_option('qlcd_wp_chatbot_dialogflow_defualt_reply')) {
            update_option('qlcd_wp_chatbot_dialogflow_defualt_reply', 'Sorry, I did not understand you. You may browse');
        }
    	if(!get_option('qlcd_wp_chatbot_dialogflow_agent_language')) {
            update_option('qlcd_wp_chatbot_dialogflow_agent_language', 'en');
        }

        /* Extended search updated with kbx_knowledgebase custom post type */
        if(!get_option('wppt_post_types')){
            update_option('wppt_post_types', array( 'page', 'post', 'kbx_knowledgebase' ));
        }


    }
}

if ( ! function_exists( 'kbx_flush_rewrite_rules_maybe' ) ) {
    add_action( 'init', 'kbx_flush_rewrite_rules_maybe', 20 );
    function kbx_flush_rewrite_rules_maybe() {
        if ( get_option( 'kbx_flush_rewrite_rules_flag' ) ) {
            flush_rewrite_rules();
            delete_option( 'kbx_flush_rewrite_rules_flag' );
        }
    }
}

if( is_admin() ){
    require_once('class-plugin-deactivate-feedback.php');
    $SBD_feedback = new KBX_Usage_Feedback( __FILE__, 'plugins@quantumcloud.com', false, true );
}



function kbx_add_searchbar_image(){
	global $kbx_options;
	if( isset($kbx_options['kb_search_bg_image']) && !empty($kbx_options['kb_search_bg_image']) ){
		$src = wp_get_attachment_image_src($kbx_options['kb_search_bg_image'], 'full');
?>
		<style>
			#docsSearch{
				background: url(<?php echo $src[0]; ?>) no-repeat center center;
			}
		</style>
<?php
	}
}




if ( ! function_exists( 'kbxhd_get_sub_child_categories' ) ) {
    function kbxhd_get_sub_child_categories( $category_id ){
              
        $args = array(
            'taxonomy'      => 'kbx_category',
            'child_of'      => 1,
            'parent'        => $category_id,
            'hide_empty'    => 1,
            'order'         => 'asc',
            'orderby'       => 'title',
            'hierarchical'  => 0,
        );

        $sub_cats = get_categories( $args );
        $html = '';
        if( $sub_cats ) {
            $html .='<div class="kbx-sidebar-dropdown-menu">';
                $html .= '<ul>';
                    foreach($sub_cats as $sub_category) {
                    $html .= '<li><a href="'.get_term_link($sub_category->term_id).'" class="kbx-sidebar-nav-link">'.esc_html($sub_category->name).'</a></li>';
                    }
                $html .= '</ul>';
            $html .= '</div>';

        }

        return $html;

    }
}


if ( ! function_exists( 'kbxhd_get_kb_list_sub_child_categories' ) ) {
    function kbxhd_get_kb_list_sub_child_categories( $category_id ){
              
        $args = array(
            'taxonomy'      => 'kbx_category',
            'child_of'      => 1,
            'parent'        => $category_id,
            'hide_empty'    => 1,
            'order'         => 'asc',
            'orderby'       => 'title',
            'hierarchical'  => 0,
        );

        $sub_cats = get_categories( $args );
        $html = '';
        if( $sub_cats ) {
            $html .='<div class="kbx-list-dropdown-menu">';
                $html .= '<ul>';
                    foreach($sub_cats as $sub_category) {
                    $html .= '<li><a href="'.get_term_link($sub_category->term_id).'" class="kbx-sidebar-nav-link">'.esc_html($sub_category->name).'</a></li>';
                    }
                $html .= '</ul>';
            $html .= '</div>';

        }

        return $html;

    }
}



add_action( 'admin_notices', 'kbx_wp_shortcode_notice',100 );

function kbx_wp_shortcode_notice(){

    global $pagenow, $typenow;

    if ( isset($typenow) && $typenow == 'kbx_knowledgebase'  ) {
    ?>


<div id="kbx-shortcode-message" class="notice notice-info is-dismissible">
<p>
    <?php
    printf(
        __(' <strong><code>[kbx-knowledgebase]</code> </strong>: for article search page with category tiles. <strong><code>[kbx-knowledgebase-glossary]</code></strong>: for glossary page. For glossary to work, you need to add the relevant Glossary letter at the bottom of each article.', 'dna88-wp-notice'),
    );

    ?>
</p>
</div>
    <?php 
        
    }

}


