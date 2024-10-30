<?php
defined('ABSPATH') or die("You can't access this file directly.");

/*******************************
 * Print pagination links
 *******************************/
if ( ! function_exists( 'kbx_get_pagination_links' ) ) {
    function kbx_get_pagination_links($numpages = '', $pagerange = '', $paged = '') {

        if (empty($pagerange)) {
            $pagerange = 2;
        }

        if (empty($paged)) {
            $paged = 1;
        }

        if ($numpages == '') {
            global $wp_query;
            $numpages = $wp_query->max_num_pages;
            if (!$numpages) {
                $numpages = 1;
            }
        }

        $pagination_args = array(
            'base'                  => str_replace('%_%', 1 == $paged ? '' : "?page=%#%", "?page=%#%"),
            'format'                => '?page=%#%',
            'total'                 => $numpages,
            'current'               => $paged,
            'show_all'              => false,
            'end_size'              => 1,
            'mid_size'              => 2,
            'prev_next'             => true,
            'prev_text'             => __('&laquo;'),
            'next_text'             => __('&raquo;'),
            'type'                  => 'plain',
            'add_args'              => false,
            'add_fragment'          => '',
            'before_page_number'    => '',
            'after_page_number'     => ''
        );

        $paginate_links = paginate_links($pagination_args);

        return $paginate_links;

    }
}

/*******************************
 * Generate and Show Glossary
 *******************************/
if ( ! function_exists( 'get_glossary_links' ) ) {
    function get_glossary_links(){

        ob_start();

        $glossary_array = array();
        $keys_array = array();

        //Query Parameters
        $kb_args = array(
            'post_type'         => 'kbx_knowledgebase',
    		'post_status'       => 'publish',
            'orderby'           => 'title',
            'order'             => 'ASC',
            'posts_per_page'    => -1,
        );

        // The Query
        $query = new WP_Query($kb_args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $gMetaTerm = get_post_meta(get_the_ID(), 'kpm_gterm', true);

                if ($gMetaTerm != "" && !in_array($gMetaTerm, $glossary_array)) {
                    array_push($glossary_array, trim($gMetaTerm));
                }
            }
        }

        wp_reset_postdata();

        if (count($glossary_array) > 0) {
            foreach ($glossary_array as $key => $value) {
                $trimmedLetter = substr($value, 0, 1);
                if (!in_array(strtolower($trimmedLetter), $keys_array)) {
                    array_push($keys_array, strtolower($trimmedLetter));
                }
            }
        }

        $sorted = sort($keys_array);

        $page_permalink = get_permalink();

        $selected = '';

        if (!isset($_GET['kbx-glossary']) || $_GET['kbx-glossary'] == "") {
            $selected = "g-selected";
        }
        ?>
        <div class="kbx-glossary-letters">
            <ul>
                <li data-letter="all" class="kbx-glossary-letter active-glossary-item-bold">
                    <div class="letter"><i class="fa fa-home"></i> </div>
                </li>
                <?php
                foreach ($keys_array as $key => $value) {
                    if (isset($_GET['kbx-glossary']) && $_GET['kbx-glossary'] == $value) {
                        $class = "g-selected";
                    }
                    //active-glossary-item
                   // echo '<li><a class="' . $class . '" href="' . $page_permalink . '?kbx-glossary=' . $value . '">' . strtoupper($value) . '</a></li>';
                    ?>
                    <li data-letter="<?php echo $value; ?>" class="kbx-glossary-letter">
                        <div class="letter"><?php echo strtoupper($value) ; ?></div>
                    </li>
                    <?php

                    $class = "";
                }
                ?>
            </ul>
        </div>
        <?php
        $content = ob_get_clean();

        return $content;
    }
}
//Getting articls by term ajax glossary

if ( ! function_exists( 'get_kbx_glossary_articles_by_term' ) ) {
    add_action('wp_ajax_kbx_glossary_articles_by_term', 'get_kbx_glossary_articles_by_term');
    add_action('wp_ajax_nopriv_kbx_glossary_articles_by_term', 'get_kbx_glossary_articles_by_term');
    function get_kbx_glossary_articles_by_term(){

        check_ajax_referer( 'kbx_ajax_nonce', 'security');

        global $post, $kbx_options;
        $gterm              = isset( $_POST['gterm'] ) ? stripslashes($_POST['gterm']) : 'all';
        $articles_per_page  = ( isset( $kbx_options['kbx_per_page'] ) && $kbx_options['kbx_per_page'] != '') ? $kbx_options['kbx_per_page'] : 10;
        $glossaryTerm       = get_kbx_gterm_values_by_index('kpm_gterm', 'kbx_knowledgebase',$gterm);
        //Total articles
        if( $gterm == 'all' ){
            $kb_total_argu = array(
                'post_type'         => 'kbx_knowledgebase',
                'post_status'       => 'publish',
                'posts_per_page'    => -1,
            );
        }else{
            $kb_total_argu = array(
                'post_type'         => 'kbx_knowledgebase',
                'post_status'       => 'publish',
                'posts_per_page'    => -1,
                'meta_query'        =>array(
                    array(
                        'key' 		=> 'kpm_gterm',
                        'value'    	=> $glossaryTerm,
                        'compare'   => 'IN',
                    ),
                )
            );
        }

        $total_query = new WP_Query($kb_total_argu);
        $total_articles_num= $total_query->post_count;
        //Terms paginated articles
        if( $gterm =='all' ){
            $kb_args = array(
                'post_type'         => 'kbx_knowledgebase',
                'post_status'       => 'publish',
                'posts_per_page'    => $articles_per_page ,
            );
        }else{
            $kb_args = array(
                'post_type'         => 'kbx_knowledgebase',
                'post_status'       => 'publish',
                'posts_per_page'    => $articles_per_page,
                'meta_query'        =>array(
                    array(
                        'key' 		=> 'kpm_gterm',
                        'value'    	=> $glossaryTerm,
                        'compare'   => 'IN',
                    ),
                )
            );
        }
        $kb_query = new WP_Query($kb_args);
        $html='';
        if( $kb_query->have_posts() ) :
        while( $kb_query->have_posts() ) : $kb_query->the_post();
           $html.=' <div class="kbx-glossary-item">';
            $html.='<h3><a href="'.esc_url( get_permalink($post->ID) ).'">';
            $html.=$post->post_title;
            $html.='</a>
                </h3>
                <div>'.get_the_excerpt().'</div>
            </div>';
        endwhile;
        else :
            $html.='<p>'.__('No articles found under this section.', 'kbx-qc').'</p>';
        endif;
        $response = array( 'html' => $html, 'total_articles' => $total_articles_num, 'offset' => $articles_per_page, 'gterm' => $gterm );
        //$response = array('html' => $glossaryTerm);
        echo wp_send_json($response);
        wp_die();

    }
}

//kbx glossary init
if ( ! function_exists( 'kbx_glossary_init' ) ) {
    add_action('wp_ajax_kbx_glossary_init', 'kbx_glossary_init');
    add_action('wp_ajax_nopriv_kbx_glossary_init', 'kbx_glossary_init');
    function kbx_glossary_init(){

        check_ajax_referer( 'kbx_ajax_nonce', 'security');

        global $post, $kbx_options;
        $gterm              = isset( $_POST['gterm'] ) ? stripslashes($_POST['gterm']) : 'all';
        $offset             = 0;
        $articles_per_page  = $kbx_options['kbx_per_page'] != '' ? $kbx_options['kbx_per_page'] : 10;
        $glossaryTerm       = get_kbx_gterm_values_by_index('kpm_gterm', 'kbx_knowledgebase',$gterm);
        //Total articles
        if( $gterm == 'all' ){
            $kb_total_argu = array(
                'post_type'         => 'kbx_knowledgebase',
                'post_status'       => 'publish',
                'posts_per_page'    => -1,
            );
        }else{
            $kb_total_argu = array(
                'post_type'         => 'kbx_knowledgebase',
                'post_status'       => 'publish',
                'posts_per_page'    => -1,
                'meta_query'        => array(
                    array(
                        'key'       => 'kpm_gterm',
                        'value'     => $glossaryTerm,
                        'compare'   => 'IN',
                    ),
                )
            );
        }

        $total_query = new WP_Query($kb_total_argu);
        $total_articles_num= $total_query->post_count;
        if(intval($articles_per_page + $offset)< $total_articles_num ){
            $next_offset = intval($articles_per_page + $offset);
        }else{
            $next_offset =-1;
        }

        //Terms paginated articles
        if( $gterm == 'all' ){
            $kb_args = array(
                'post_type'         => 'kbx_knowledgebase',
                'post_status'       => 'publish',
                'posts_per_page'    => $articles_per_page ,
                'offset'            => $offset,
            );
        }else{
            $kb_args = array(
                'post_type'         => 'kbx_knowledgebase',
                'post_status'       => 'publish',
                'posts_per_page'    => $articles_per_page,
                'offset'            => $offset,
                'meta_query'        => array(
                    array(
                        'key'       => 'kpm_gterm',
                        'value'     => $glossaryTerm,
                        'compare'   => 'IN',
                    ),
                )
            );
        }
        $kb_query = new WP_Query($kb_args);
        $html='';
        if( $kb_query->have_posts() ) :
            while( $kb_query->have_posts() ) : $kb_query->the_post();
                $html.=' <div class="kbx-glossary-item">';
                $html.='<h3><a href="'.esc_url( get_permalink($post->ID) ).'">';
                $html.=$post->post_title;
                $html.='</a>
                </h3>
                <div>'.get_the_excerpt().'</div>
            </div>';
            endwhile;
        else :
            $html.='<p>'.__('No articles found under this section.', 'kbx-qc').'</p>';
        endif;
        $response = array( 'html' => $html, 'offset'=> $next_offset, 'gterm'=>$gterm );
        //$response = array('html' => $glossaryTerm);
        echo wp_send_json($response);
        wp_die();
    }
}

//kbx glossary load more
//Load more articles
if ( ! function_exists( 'kbx_glossary_load_more' ) ) {
    add_action('wp_ajax_kbx_glossary_load_more', 'kbx_glossary_load_more');
    add_action('wp_ajax_nopriv_kbx_glossary_load_more', 'kbx_glossary_load_more');
    function kbx_glossary_load_more(){

        check_ajax_referer( 'kbx_ajax_nonce', 'security');

        global $post, $kbx_options;
        $gterm              = isset( $_POST['gterm'] ) ? stripslashes($_POST['gterm']) : 'all';
        $offset             = isset( $_POST['offset'] ) ? stripslashes($_POST['offset']) : -1;
        $articles_per_page  = ( isset($kbx_options['kbx_per_page']) && $kbx_options['kbx_per_page'] != '' ) ? $kbx_options['kbx_per_page'] : 10;
        $glossaryTerm       = get_kbx_gterm_values_by_index('kpm_gterm', 'kbx_knowledgebase',$gterm);
        //Total articles
        if($gterm=='all'){
            $kb_total_argu =array(
                'post_type' => 'kbx_knowledgebase',
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );
        }else{
            $kb_total_argu =array(
                'post_type' => 'kbx_knowledgebase',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'meta_query' =>array(
                    array(
                        'key' 		=> 'kpm_gterm',
                        'value'    	=> $glossaryTerm,
                        'compare'   => 'IN',
                    ),
                )
            );
        }

        $total_query            = new WP_Query($kb_total_argu);
        $total_articles_num     = $total_query->post_count;
        if(intval($articles_per_page + $offset)< $total_articles_num ){
            $next_offset        = intval($articles_per_page + $offset);
        }else{
            $next_offset        =-1;
        }

        //Terms paginated articles
        if($gterm=='all'){
            $kb_args = array(
                'post_type'         => 'kbx_knowledgebase',
                'post_status'       => 'publish',
                'posts_per_page'    => $articles_per_page ,
                'offset'            => $offset,
            );
        }else{
            $kb_args = array(
                'post_type'         => 'kbx_knowledgebase',
                'post_status'       => 'publish',
                'posts_per_page'    => $articles_per_page,
                'offset'            => $offset,
                'meta_query'        => array(
                    array(
                        'key' 		=> 'kpm_gterm',
                        'value'    	=> $glossaryTerm,
                        'compare'   => 'IN',
                    ),
                )
            );
        }
        $kb_query = new WP_Query($kb_args);
        $html='';
        if( $kb_query->have_posts() ) :
            while( $kb_query->have_posts() ) : $kb_query->the_post();
                $html.=' <div class="kbx-glossary-item">';
                $html.='<h3><a href="'.esc_url( get_permalink($post->ID) ).'">';
                $html.=$post->post_title;
                $html.='</a>
                </h3>
                <div>'.get_the_excerpt().'</div>
            </div>';
            endwhile;
        else :
            $html.='<p>'.__('No articles found under this section.', 'kbx-qc').'</p>';
        endif;
        $response = array('html' => $html, 'offset'=>$next_offset,'gterm'=>$gterm);
        //$response = array('html' => $glossaryTerm);
        echo wp_send_json($response);
        wp_die();
    }
}


if ( ! function_exists( 'get_kbx_gterm_values_by_index' ) ) {
    function get_kbx_gterm_values_by_index($meta_key, $post_type, $glossary_letter) {

        $posts = get_posts(
            array(
                'post_type'         => $post_type,
                'meta_key'          => $meta_key,
                'posts_per_page'    => -1,
            )
        );

        $meta_values = array();
        foreach ($posts as $post) {
            $meta_value = get_post_meta($post->ID, $meta_key, true);
            if ($meta_value != "") {
                $meta_value_first_letter = strtolower(substr($meta_value, 0, 1));
                if ($meta_value_first_letter == strtolower($glossary_letter)) {
                    $meta_values[] = $meta_value;
                }

            }

        }

        return $meta_values;

    }
}

/*******************************
 * Filter meta key from the generated
 * query to compare wildcard, only the first character
 *******************************/
add_filter('posts_where', function ($where, \WP_Query $q) {
    // Check for our custom query var
    if (true !== $q->get('wildcard_on_key'))
        return $where;

    // Lets filter the clause
    $where = str_replace('meta_value LIKE \'%', 'meta_value LIKE \'', $where);

    return $where;
}, 10, 2);


/*******************************
 * Get search form
 *******************************/
if ( ! function_exists( 'kbx_get_search_form' ) ) {
    function kbx_get_search_form(){
        ob_start();
        global $kbx_options;
        $kb_floating_widget_main_title = ( isset($kbx_options['kb_floating_widget_main_title']) && $kbx_options['kb_floating_widget_main_title'] != "" ) ? $kbx_options['kb_floating_widget_main_title'] : 'Looking for help?';
        $kb_floating_widget_placeholder = ( isset($kbx_options['kb_floating_widget_placeholder']) && $kbx_options['kb_floating_widget_placeholder'] != "" ) ? $kbx_options['kb_floating_widget_placeholder'] : 'Know All is a fully featured knowledge base theme for WordPress.';
        $kbx_search_placeholder = ( isset($kbx_options['kb_search_placeholder']) && $kbx_options['kb_search_placeholder'] != "" ) ? $kbx_options['kb_search_placeholder'] : 'Search the knowledge base';
        ?>
        <?php $src = isset($kbx_options['kb_search_bg_image']) ? wp_get_attachment_image_src($kbx_options['kb_search_bg_image'], 'full') :''; ?>
        <section id="docsSearch" style="background: url(<?php echo isset($src[0]) ? esc_url($src[0]) : ''; ?>) no-repeat center center;">


        <h2><?php echo esc_html($kb_floating_widget_main_title); ?>
        <span><?php echo esc_html($kb_floating_widget_placeholder); ?><span></h2>
  

            <form action="<?php echo home_url('/'); ?>" method="GET" id="searchBar" autocomplete="off">
                <input type="hidden" name="s" id="s" value="" class="kbx-hidden-search">
                <input id="kbx-query" name="kbx-query" title="search-query" class="search-query"
                       placeholder="<?php echo esc_html($kbx_search_placeholder); ?>" value="" type="text" required>
                <button type="submit">
                    <i class="fa fa-search" aria-hidden="true"></i>
    <!--                <span>Search</span>-->
                </button>
                <div id="serp-dd" style="display: none;">
                    <ul class="result">
                    </ul>
                </div>
            </form>
        </section>


        

        <?php

        $content = ob_get_clean();

        return $content;
    }
}

/*******************************
 * Generate and Serve post after
 * block
 *******************************/
if ( ! function_exists( 'kbx_after_single_content' ) ) {
    function kbx_after_single_content(){

        global $post, $kbx_options;

        $post_id = $post->ID;
        $post_type = $post->post_type;

        $total_like_up = get_post_meta($post_id, 'kpm_upvotes', true);
        $total_like_down = get_post_meta($post_id, 'kpm_downvotes', true);
        $total_views = get_post_meta($post_id, 'kpm_views', true);

        if ($total_like_up == "") {
            $total_like_up = 0;
        }
        if ($total_like_down == "") {
            $total_like_down = 0;
        }

        if ($total_views == "") {
            $total_views = 0;
        }

        $appended_contnet = "";

        if (is_single() && $post_type == 'kbx_knowledgebase') {


            $appended_contnet .= '<div class="kbx-post-single-stats">';
            $appended_contnet .= '<div class="kbx-stats">';
            $appended_contnet .= '<a href="#" title="Like this Article" id="kbx-like-pid-' . $post_id . '" data-like-type="up" data-article-id="' . $post_id . '" class="kbx-vote-article kbx-like-btn"><div class="kbx-post-like kbx-inline">';
            $appended_contnet .= '<span class="kbx-like-icon-up kbx-icon">';
            $appended_contnet .= '<i class="fa fa-thumbs-up"></i>';
            $appended_contnet .= '</span>';
            $appended_contnet .= '<span class="kbx-like-counter-up kbx-counter">';
            $appended_contnet .= $total_like_up;
            $appended_contnet .= '</span>';
            $appended_contnet .= '</div></a>';
            // $appended_contnet .= '<a href="#" title="Dislike this Article" id="kbx-like-pid-' . $post_id . '" data-like-type="down" data-article-id="' . $post_id . '" class="kbx-vote-article kbx-like-btn"><div class="kbx-post-like kbx-inline">';
            // $appended_contnet .= '<span class="kbx-like-icon-down kbx-icon">';
            // $appended_contnet .= '<i class="fa fa-thumbs-down"></i>';
            // $appended_contnet .= '</span>';
            // $appended_contnet .= '<span class="kbx-like-counter-down kbx-counter">';
            // $appended_contnet .= $total_like_down;
            // $appended_contnet .= '</span>';
            // $appended_contnet .= '</div></a>';
            $appended_contnet .= '<a href="#" title="Total Views"><div class="kbx-post-views kbx-inline" id="kbx-views-pid-' . $post_id . '">';
            $appended_contnet .= '<span class="kbx-view-icon kbx-icon">';
            $appended_contnet .= '<i class="fa fa-eye"></i>';
            $appended_contnet .= '</span>';
            $appended_contnet .= '<span class="kbx-view-counter kbx-counter">';
            $appended_contnet .= $total_views;
            $appended_contnet .= '</span>';
            $appended_contnet .= '</div></a>';

            if( array_key_exists('kbx_read_time', $kbx_options) ){
        		if( isset($kbx_options['kbx_read_time']) && $kbx_options['kbx_read_time']==1){
        			$kbxPost = get_the_ID();
        			$kbxReadingOptions = get_option('kbx_reading_time_options');
        			$kbxArticleReadTime = new kbxArticleReadTime();
        			$data = $kbxArticleReadTime->kbx_calculate_reading_time($kbxPost, $kbxReadingOptions);

        			$label = $kbxReadingOptions['label'];
        			$postfix = $kbxReadingOptions['postfix'];
        			$postfix_singular = $kbxReadingOptions['postfix_singular'];

        			if($data > 1) {
        				$calculatedPostfix = $postfix;
        			} else {
        				$calculatedPostfix = $postfix_singular;
        			}

        			$appended_contnet .= '<span class="rt-reading-time" style="display: flex;float:right;">'.'<span class="rt-time" style=" margin-right: 3px;">'.$data.'</span>'.'<span class="rt-label" style=" margin-right: 3px;"> '.$calculatedPostfix.'</span>'.'<span class="rt-label" style=" margin-right: 3px;">'.$label.'</span>'.'</span>';
                }
        	}

    		$appended_contnet .= '</div>';
            $appended_contnet .= '</div>';
        }

        //return $content . $appended_contnet;
        echo $appended_contnet;
    }
}

//add_filter( 'the_content', 'kbx_after_single_content' );


/*******************************
 * Update post views
 *******************************/
if ( ! function_exists( 'kbx_wpb_set_post_views' ) ) {
    function kbx_wpb_set_post_views($postID){

        $count_key = 'kpm_views';
        $count = get_post_meta($postID, $count_key, true);

        if ($count == '') {
            $count = 0;
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, $count);
        } else {
            $count++;
            update_post_meta($postID, $count_key, $count);
        }
    }
}

if ( ! function_exists( 'kbx_wpb_track_post_views' ) ) {
    function kbx_wpb_track_post_views($post_id){

        global $post;

        if (!is_single() || is_user_logged_in()) {
            return;
        }

        if ($post->post_type != 'kbx_knowledgebase') {
            return;
        }

        if (!isset($_SESSION['kbx-post-views'])) {
            $_SESSION['kbx-post-views'] = array();
        }

        $current_ss_cokie = "";

        if ($post->post_type == 'kbx_knowledgebase') {

            if (!in_array($post->ID, $_SESSION['kbx-post-views'])) {

                if (!is_single()) return;

                if (empty ($post_id)) {
                    $post_id = $post->ID;
                }

                kbx_wpb_set_post_views($post_id);

                $_SESSION['kbx-post-views'][] = $post_id;

            }

        }

    }
    add_action('wp_head', 'kbx_wpb_track_post_views');
}


/*******************************
 * Widget Output Markup
 *******************************/
if ( ! function_exists( 'kbx_get_widget_display' ) ) {
    function kbx_get_widget_display($sort_by, $limit){
        if (!isset($sort_by) || $sort_by == null) {
            $sort_by = 'date';
        }

        if (!isset($limit) || $limit == '') {
            $limit = 5;
        }

        ob_start();

        $orderby = "";
        $order = "";
        $meta_key = "";

        if ($sort_by == 'date') {
            $orderby = 'date';
            $order = "DESC";
        }

        if ($sort_by == 'popularity') {
            $orderby = array('meta_value_num' => 'DESC');
            $meta_key = 'kpm_upvotes';
        }

        if ($sort_by == 'views') {
            $orderby = array('meta_value_num' => 'DESC');
            $meta_key = 'kpm_views';
        }

        //Query Parameters
        $kb_args = array(
            'post_type' => 'kbx_knowledgebase',
    		'post_status'=>'publish',
            'orderby' => $orderby,
            'order' => $order,
            'posts_per_page' => $limit,
            'meta_key' => $meta_key,
        );

        $query = new WP_Query($kb_args);

        ?>

        <ul class="kbx-widget kbx-widget-sortby-<?php echo $sort_by; ?> kbx-widget-articles">

            <?php while ($query->have_posts()) : $query->the_post() ?>
                <li>
                    <a href="<?php the_permalink(); ?>">
                        <i class="fa fa-file-text-o"></i>
                        <span>
                        <?php the_title(); ?>
                    </span>
                    </a>
                </li>
            <?php endwhile;
            wp_reset_postdata(); ?>

        </ul>

        <?php

        $content = ob_get_clean();

        return $content;
    }
}

if ( ! function_exists( 'kbx_article_tab_widget_display' ) ) {
    function kbx_article_tab_widget_display($limit,$kbx_popular,$kbx_stricky,$kbx_recent){
    	global $kbx_options;
    	$kbxReadingOptions = get_option('kbx_reading_time_options');
        require_once(KBX_DIR . '/includes/kbx-reading-time.php');
    	$kbxArticleReadTime = new kbxArticleReadTime();
        ob_start();
        ?>
        <section class="kbx-article-tabs">
            <div class="kbx-tabs-container">
                <ul class="kbx-tabs">
                    <?php
                    if ($kbx_stricky == 'on'):
                        ?>
                        <li class="tab-link" data-tab="tab-1"><span>Sticky</span></li>
                    <?php
                    endif;
                    if ($kbx_popular == 'on'):
                        ?>
                        <li class="tab-link" data-tab="tab-2"><span>Popular</span></li>
                    <?php
                    endif;
                    if ($kbx_recent== 'on'):
                        ?>
                        <li class="tab-link" data-tab="tab-3"><span>Recent</span></li>
                    <?php endif;?>
                </ul>
                <?php
                if ($kbx_stricky == 'on'):
                    ?>
                    <div id="tab-1" class="kbx-tab-content">
                        <?php
                        $kb_sticky_args = array(
                            'post_type' => 'kbx_knowledgebase',
    						'post_status'=>'publish',
                            'posts_per_page' => $limit,
                            'meta_query' => array(
                                array(
                                    'key' => 'kpm_featured',
                                    'value' => 'yes',
                                )
                            )
                        );

                        $sticky_query = new WP_Query($kb_sticky_args);
                        if ($sticky_query->have_posts()) : ?>

                            <ul class="sticky-articleList">

                                <?php while ($sticky_query->have_posts()) : $sticky_query->the_post() ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <i class="fa fa-bookmark"></i>
                                            <span>
                                                    <?php the_title(); ?>
                                                </span>
                                        </a>
    									<?php
    										if( isset($kbx_options['kbx_read_time']) && $kbx_options['kbx_read_time']==1){
    											$kbxPost = get_the_ID();


    											$data = $kbxArticleReadTime->kbx_calculate_reading_time($kbxPost, $kbxReadingOptions);

    											$label = $kbxReadingOptions['label'];
    											$postfix = $kbxReadingOptions['postfix'];
    											$postfix_singular = $kbxReadingOptions['postfix_singular'];

    											if($data > 1) {
    												$calculatedPostfix = $postfix;
    											} else {
    												$calculatedPostfix = $postfix_singular;
    											}

    											echo '<span class="rt-reading-time" style="display: inline-block;">'.'<span class="rt-time" style=" margin-right: 3px;">'.$data.'</span>'.'<span class="rt-label" style=" margin-right: 3px;"> '.$calculatedPostfix.'</span>'.'<span class="rt-label" style=" margin-right: 3px;">'.$label.'</span>'.'</span>';
    											}
    											?>
                                    </li>
                                <?php endwhile;// wp_reset_postdata(); ?>

                            </ul>
                        <?php endif; ?>
                    </div>
                <?php
                endif;
                if ($kbx_popular == 'on'):
                    ?>
                    <div id="tab-2" class="kbx-tab-content">
                        <?php
                        $popular_orderby = array('meta_value_num' => 'DESC');
                        $popular_meta_key = 'kpm_views';
                        $kb_popular_args = array(
                            'post_type' => 'kbx_knowledgebase',
    						'post_status'=>'publish',
                            'posts_per_page' => $limit,
                            'orderby' => $popular_orderby,
                            'meta_key' => $popular_meta_key,
                        );
                        $popular_query = new WP_Query($kb_popular_args);
                        if ($popular_query->have_posts()) : ?>

                            <ul class="sticky-articleList">

                                <?php while ($popular_query->have_posts()) : $popular_query->the_post() ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <i class="fa fa-file-text-o"></i>
                                            <span>
                                                    <?php the_title(); ?>
                                                </span>
                                        </a>
    									<?php
    										if( isset($kbx_options['kbx_read_time']) && $kbx_options['kbx_read_time']==1){
    											$kbxPost = get_the_ID();


    											$data = $kbxArticleReadTime->kbx_calculate_reading_time($kbxPost, $kbxReadingOptions);

    											$label = $kbxReadingOptions['label'];
    											$postfix = $kbxReadingOptions['postfix'];
    											$postfix_singular = $kbxReadingOptions['postfix_singular'];

    											if($data > 1) {
    												$calculatedPostfix = $postfix;
    											} else {
    												$calculatedPostfix = $postfix_singular;
    											}

    											echo '<span class="rt-reading-time" style="display: inline-block;">'.'<span class="rt-time" style=" margin-right: 3px;">'.$data.'</span>'.'<span class="rt-label" style=" margin-right: 3px;"> '.$calculatedPostfix.'</span>'.'<span class="rt-label" style=" margin-right: 3px;">'.$label.'</span>'.'</span>';

    										}
    									?>
                                    </li>
                                <?php endwhile;// wp_reset_postdata(); ?>

                            </ul>
                        <?php endif; ?>
                    </div>
                <?php
                endif;
                if ($kbx_recent == 'on'):
                    ?>
                    <div id="tab-3" class="kbx-tab-content">
                        <?php
                        $kb_recent_args = array(
                            'post_type' => 'kbx_knowledgebase',
    						'post_status'=>'publish',
                            'posts_per_page' => $limit,
                        );
                        $recent_query = new WP_Query($kb_recent_args);
                        if ($recent_query->have_posts()) : ?>

                            <ul class="sticky-articleList">

                                <?php while ($recent_query->have_posts()) : $recent_query->the_post() ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <i class="fa fa-file-text-o"></i>
                                            <span>
                                                    <?php the_title(); ?>
                                                </span>
                                        </a>
    									<?php
    										if( isset($kbx_options['kbx_read_time']) && $kbx_options['kbx_read_time']==1){
    											$kbxPost = get_the_ID();


    											$data = $kbxArticleReadTime->kbx_calculate_reading_time($kbxPost, $kbxReadingOptions);

    											$label = $kbxReadingOptions['label'];
    											$postfix = $kbxReadingOptions['postfix'];
    											$postfix_singular = $kbxReadingOptions['postfix_singular'];

    											if($data > 1) {
    												$calculatedPostfix = $postfix;
    											} else {
    												$calculatedPostfix = $postfix_singular;
    											}

    											echo '<span class="rt-reading-time" style="display: inline-block;">'.'<span class="rt-time" style=" margin-right: 3px;">'.$data.'</span>'.'<span class="rt-label" style=" margin-right: 3px;"> '.$calculatedPostfix.'</span>'.'<span class="rt-label" style=" margin-right: 3px;">'.$label.'</span>'.'</span>';
    											}
    											?>
                                    </li>
                                <?php endwhile;// wp_reset_postdata(); ?>

                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif;?>

            </div><!-- container -->

        </section>

        <?php
        $content = ob_get_clean();

        return $content;
    }
}

/*******************************
 * Custom Breadcrumb
 *******************************/
// Breadcrumbs
if ( ! function_exists( 'kbx_custom_breadcrumbs' ) ) {
    function kbx_custom_breadcrumbs(){

        // Settings
        $separator          = '<span class="kbx-breadcrumbs-seperator">&#47;</span>';
        $breadcrums_id      = 'kbx-breadcrumbs';
        $breadcrums_class   = 'kbx-breadcrumbs';
        $home_title         = __('Home', 'kbx-qc');
        $custom_taxonomy    = 'kbx_category';


        // Get the query & post information
        global $post, $wp_query;

        // Do not display on the homepage
        if (!is_front_page()) {

            // Build the breadcrums
            echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';

            // Home page
            echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
            echo '<li class="separator separator-home"> ' . $separator . ' </li>';

            if (is_archive() && !is_tax() && !is_category() && !is_tag()) {

                echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';

            } else if (is_archive() && is_tax() && !is_category() && !is_tag()) {

                // If post is a custom post type
                $post_type = get_post_type();

                // If it is a custom post type display name and link
                if ($post_type != 'post') {

                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);

                    echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                    echo '<li class="separator"> ' . $separator . ' </li>';

                }

                $custom_tax_name = get_queried_object()->name;
                echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';

            } else if (is_single()) {

                // If post is a custom post type
                $post_type = get_post_type();

                // If it is a custom post type display name and link
                if ($post_type != 'post') {

                    $post_type_object = get_post_type_object($post_type);
                    $post_type_archive = get_post_type_archive_link($post_type);

                    echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                    echo '<li class="separator"> ' . $separator . ' </li>';

                }

                // Get post category info
                $category = get_the_category();

                if (!empty($category)) {

                    // Get last category post is in

                    $last_category = end(($category));
                    // Get parent any categories and create array
                    $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','), ',');
                    $cat_parents = explode(',', $get_cat_parents);

                    // Loop through parent categories and store in variable $cat_display
                    $cat_display = '';
                    foreach ($cat_parents as $parents) {
                        $cat_display .= '<li class="item-cat">' . $parents . '</li>';
                        $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                    }

                }

                // If it's a custom post type within a custom taxonomy
                $taxonomy_exists = taxonomy_exists($custom_taxonomy);
                if (empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {

                    $taxonomy_terms = get_the_terms($post->ID, $custom_taxonomy);
                    $cat_id = $taxonomy_terms[0]->term_id;
                    $cat_nicename = $taxonomy_terms[0]->slug;
                    $cat_link = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                    $cat_name = $taxonomy_terms[0]->name;

                }

                // Check if the post is in a category
                if (!empty($last_category)) {
                    echo $cat_display;
                    echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

                    // Else if post is in a custom taxonomy
                } else if (!empty($cat_id)) {

                    echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                    echo '<li class="separator"> ' . $separator . ' </li>';
                    echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

                } else {

                    echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

                }

            } else if (is_category()) {

                // Category page
                echo '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>';

            } else if (is_page()) {

                // Standard page
                if ($post->post_parent) {

                    // If child page, get parents
                    $anc = get_post_ancestors($post->ID);

                    // Get parents in the right order
                    $anc = array_reverse($anc);

                    // Parent page loop
                    if (!isset($parents)) $parents = null;
                    foreach ($anc as $ancestor) {
                        $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                        $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                    }

                    // Display parent pages
                    echo $parents;

                    // Current page
                    echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';

                } else {

                    // Just display current page if not parents
                    echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';

                }

            } else if (is_tag()) {

                // Tag page

                // Get tag information
                $term_id = get_query_var('tag_id');
                $taxonomy = 'post_tag';
                $args = 'include=' . $term_id;
                $terms = get_terms($taxonomy, $args);
                $get_term_id = $terms[0]->term_id;
                $get_term_slug = $terms[0]->slug;
                $get_term_name = $terms[0]->name;

                // Display the tag name
                echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';

            } elseif (is_day()) {

                // Day archive

                // Year link
                echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link(get_the_time('Y')) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
                echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

                // Month link
                echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link(get_the_time('Y'), get_the_time('m')) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
                echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';

                // Day display
                echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';

            } else if (is_month()) {

                // Month Archive

                // Year link
                echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link(get_the_time('Y')) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
                echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

                // Month display
                echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';

            } else if (is_year()) {

                // Display year archive
                echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';

            } else if (is_author()) {

                // Auhor archive

                // Get the author information
                global $author;
                $userdata = get_userdata($author);

                // Display author name
                echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';

            } else if (get_query_var('paged')) {

                // Paginated archives
                echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">' . __('Page') . ' ' . get_query_var('paged') . '</strong></li>';

            } else if (is_search()) {

                // Search results page
                echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';

            } elseif (is_404()) {

                // 404 page
                echo '<li>' . __('Error 404', 'kbx-qc') . '</li>';
            }

            echo '</ul>';

        }

    }
}

/**
 * Permalink flash handler.
 */
if (!function_exists('kbx_permalink_handler')) {
    function kbx_permalink_handler(){

        if (!get_option('kbx_parmalink_handled')) {
            flush_rewrite_rules();
            update_option('kbx_parmalink_handled', true);
        }
    }
}
/**
 * Check user role to show articles.
 */
if (!function_exists('kbx_article_user_permit')) {
    function kbx_article_user_permit($term_id){

        $kbx_options = get_option('kbx_settings');
        if (isset($kbx_options['user_role_cat']) && $kbx_options['user_role_cat'] == '1') {
            $cat_roles = get_term_meta($term_id, 'kbx_cats_user_roles', true);

            if( empty($cat_roles) ){
                $cat_roles = array();
            }

            $user_id = get_current_user_id();
            if ($user_id != "") {
                $return = false;
                $user_info = get_userdata($user_id);
                $user_roles = $user_info->roles;
                foreach ($user_roles as $role) {
                    if (in_array($role, $cat_roles)) {
                        return true;
                    } else {
                        $return = false;
                    }
                }
            } else {
                if (in_array('visitor', $cat_roles)) {
                    $return = true;    
                }else{
                    $return = false;
                }
            }
        } else {
            $return = true;
        }
        return $return;
    }
}
//Pre saved post get for drag and drop odering .
/**
 * Handling the article ordering by adding coloumn in term table
 */
if ( ! function_exists( 'kbx_handle_term_order' ) ) {
    add_action('admin_init', 'kbx_handle_term_order');
    function kbx_handle_term_order(){

        global $wpdb;
        $result = $wpdb->query("DESCRIBE $wpdb->terms `term_order`");
        if (!$result) {
            $query = "ALTER TABLE $wpdb->terms ADD `term_order` INT( 4 ) NULL DEFAULT '0'";
            $result = $wpdb->query($query);
        }
    }
}

/**
 * pre post order handle
 */
if ( ! function_exists( 'kbx_pre_get_posts' ) ) {
    add_action('pre_get_posts', 'kbx_pre_get_posts');
    function kbx_pre_get_posts($wp_query)
    {
        if (is_admin()) {
            if (isset($wp_query->query['post_type']) && !isset($_GET['orderby'])) {
                if ($wp_query->query['post_type'] == 'kbx_knowledgebase') {
                    $wp_query->set('orderby', 'menu_order');
                    $wp_query->set('order', 'ASC');
                }
            }
        }
    }
}

//Drag and drop odering menu update.
if ( ! function_exists( 'kbx_menu_order_update' ) ) {
    add_action('wp_ajax_kbx-update-menu-order', 'kbx_menu_order_update');
    function kbx_menu_order_update() {
        global $wpdb;

        parse_str($_POST['order'], $data);

        if (!is_array($data))
            return false;

        $id_arr = array();
        foreach ($data as $key => $values) {
            foreach ($values as $position => $id) {
                $id_arr[] = $id;
            }
        }


        $menu_order_arr = array();
        foreach ($id_arr as $key => $id) {
            $results = $wpdb->get_results("SELECT menu_order FROM $wpdb->posts WHERE ID = " . intval($id));
            foreach ($results as $result) {
                $menu_order_arr[] = $result->menu_order;
            }
        }
        sort($menu_order_arr);

        foreach ($data as $key => $values) {
            foreach ($values as $position => $id) {
                $wpdb->update($wpdb->posts, array('menu_order' => $menu_order_arr[$position]), array('ID' => intval($id)));
            }
        }
    }
}

/***
 * Main page tabs
 */
if ( ! function_exists( 'kbx_home_tabs_display' ) ) {
    function kbx_home_tabs_display(){
        global $kbx_options;
        ob_start();

        $articles_per_page  = ( isset( $kbx_options['kbx_per_page'] ) && $kbx_options['kbx_per_page'] != '') ? $kbx_options['kbx_per_page'] : 10;

        ?>

        <section class="kbx-featured-section">
            <div class="kbx-featured-container">


                <?php
                if ( isset($kbx_options['kbx_home_tab_stricky']) && $kbx_options['kbx_home_tab_stricky'] == '1'):
                    ?>
                    <div class="kbx-stricky-content">
                        <h3><?php esc_html_e('Sticky', 'kbx-qc'); ?></h3>
                        <?php
                        $kb_sticky_args = array(
                            'post_type'         => 'kbx_knowledgebase',
                            'post_status'       => 'publish',
                            'posts_per_page'    => $articles_per_page,
                            'meta_query'        => array(
                                array(
                                    'key'       => 'kpm_featured',
                                    'value'     => 'yes',
                                )
                            )
                        );
                        $sticky_query = new WP_Query($kb_sticky_args);
                        if ($sticky_query->have_posts()) : ?>

                            <ul class="sticky-articleList">

                                <?php while ($sticky_query->have_posts()) : $sticky_query->the_post() ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <i class="fa fa-bookmark"></i>
                                            <span>
                                                    <?php echo kbx_short_text( get_the_title() ); ?>
                                                </span>
                                        </a>
                                    </li>
                                <?php endwhile;// wp_reset_postdata(); ?>

                            </ul>
                        <?php endif; ?>
                    </div>
                <?php
                endif;
                if ( isset($kbx_options['kbx_home_tab_popular']) && $kbx_options['kbx_home_tab_popular'] == '1'):
                    ?>
                    <div class="kbx-popular-content">
                        <h3><?php esc_html_e('Popular', 'kbx-qc'); ?></h3>
                        <?php
                        $popular_orderby = array('meta_value_num' => 'DESC');
                        $popular_meta_key = 'kpm_views';
                        $kb_popular_args = array(
                            'post_type'         => 'kbx_knowledgebase',
                            'post_status'       => 'publish',
                            'posts_per_page'    => $articles_per_page,
                            'orderby'           => $popular_orderby,
                            'meta_key'          => $popular_meta_key,
                        );
                        $popular_query = new WP_Query($kb_popular_args);
                        if ($popular_query->have_posts()) : ?>

                            <ul class="sticky-articleList">

                                <?php while ($popular_query->have_posts()) : $popular_query->the_post() ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <i class="fa fa-file-text-o"></i>
                                            <span>
                                                <?php echo kbx_short_text( get_the_title() ); ?>
                                            </span>
                                        </a>
                                    </li>
                                <?php endwhile;// wp_reset_postdata(); ?>

                            </ul>
                        <?php endif; ?>
                    </div>
                <?php
                endif;
                if ( isset($kbx_options['kbx_home_tab_popular']) && $kbx_options['kbx_home_tab_recent'] == '1'):
                    ?>
                    <div class="kbx-recent-content">
                        <h3><?php esc_html_e('Recent', 'kbx-qc'); ?></h3>
                        <?php
                        $kb_recent_args = array(
                            'post_type'         => 'kbx_knowledgebase',
                            'post_status'       => 'publish',
                            'posts_per_page'    => $articles_per_page,
                        );
                        $recent_query = new WP_Query($kb_recent_args);
                        if ($recent_query->have_posts()) : ?>

                            <ul class="sticky-articleList">

                                <?php while ($recent_query->have_posts()) : $recent_query->the_post() ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <i class="fa fa-file-text-o"></i>
                                            <span>
                                                <?php echo kbx_short_text( get_the_title() ); ?>
                                            </span>
                                        </a>
                                    </li>
                                <?php endwhile;// wp_reset_postdata(); ?>

                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div><!-- container -->

        </section>

        <?php
        $content = ob_get_clean();

        return $content;
    }
}


/***
 * Custom Excerpt
 */
if ( ! function_exists( 'kbx_excerpts' ) ) {
    function kbx_excerpts($content = false) {
        global $post;
        $mycontent = $post->post_excerpt;

        $mycontent = $post->post_content;
        $mycontent = strip_shortcodes($mycontent);
        $mycontent = str_replace(']]>', ']]&gt;', $mycontent);
        $mycontent = strip_tags($mycontent);
        $excerpt_length = 32;
        $words = explode(' ', $mycontent, $excerpt_length + 1);
        if(count($words) > $excerpt_length) :
            array_pop($words);
            array_push($words, '');
            $mycontent = implode(' ', $words);
        endif;
        $mycontent = $mycontent . '... <a target="_blank" href="'.get_permalink().'">'.esc_html('Read More', 'kbx-qc').'</a>';
        return $mycontent;
    }
}

/***
 * Custom Excerpt
 */
if ( ! function_exists( 'kbx_short_text' ) ) {
    function kbx_short_text( $content = '', $length=32 ) {
        $length += 3;
        $short_text = mb_strimwidth( $content, 0, $length, '...');
        return $short_text;
    }
}