<?php
defined('ABSPATH') or die("You can't access this file directly.");

if ( ! function_exists( 'kbx_ajax_ajaxurl' ) ) {
	add_action('wp_head', 'kbx_ajax_ajaxurl');
	function kbx_ajax_ajaxurl() {

	   echo '<script type="text/javascript">
	           	var ajaxurl 		= "' . admin_url('admin-ajax.php') . '";
                var kbx_ajax_nonce  = "'. wp_create_nonce( 'kbx_ajax_nonce' ).'"; 
	         </script>';
	}
}

add_action( 'wp_ajax_kbx_search_article', 'func_kbx_search_article' );
add_action( 'wp_ajax_nopriv_kbx_search_article', 'func_kbx_search_article' );
if ( ! function_exists( 'func_kbx_search_article' ) ) {
	function func_kbx_search_article(){

		check_ajax_referer( 'kbx_ajax_nonce', 'security');

		global $wpdb;
		global $kbx_options;


		$data['status'] = 'false';

		$searchKey 	= isset( $_POST['post_key'] ) ? trim( sanitize_text_field($_POST['post_key']) ) : '';

	   	$posts_per_page = $kbx_options['kbx_per_page'];

	   	$offset = 0;

		$results = kbx_get_search_results( $searchKey, $posts_per_page,$offset, 'all' );

		$list = "";

		/*
		*	Fixation starts here.
		*
		*	$result variable is throwing noting in case of empty results. This issue is throwing a 500-Internal Server error.
		*	To solve this issue, Manually setting $result variable to empty array.
		*/

		if( !isset($results) ){
			$results = [];
		}
		
		/*Fixation ends here*/


		if( count($results) > 0 ){
			
			$data['status'] = 'true';

			$list = "";

			foreach( $results as $article ){

				$list .= '<li class="">
							<a href="'.get_permalink($article->ID).'">
								'.get_the_title($article->ID).'
							</a>
						</li>';

			}

		}else{

			if( trim($list) == "" ){

				$list .= '<li class="">
							<a href="#">
								'.__('No result found.', 'kbx-qc').'
							</a>
						</li>';
			}
		}

		$data['list'] = $list;

		echo json_encode($data);

		die();
	}
}

/*******************************
 * This function will return the
 * results set
 *******************************/
if ( ! function_exists( 'kbx_get_search_results' ) ) {
	function kbx_get_search_results( $search_key,$posts_per_page,$offset, $search_term ){
		    global $wpdb, $kbx_options;
		    $search_position = $search_term;
	        $string_sql = kbxsearch_sql_prepare( $search_key,$posts_per_page,$offset, $search_position);
	        $string_results = $wpdb->get_results( $string_sql );
			
			//return $string_sql;
			if( $search_position == 'all' ){
				return $string_results ;
			}

			if(count($string_results)>0){     //Exact title like search
	           return $string_results ;
	        } else{
				
	            $search_words = explode( ' ', $search_key );
	            if(count($search_words)>1){
					
					$s_array[0] = $search_key;	// Save original query at [0]
					$s_array[1] = $search_words;	// Save array of terms at [1]

					$search_info = $s_array;
					$title_sql = kbxsearch_sql_prepare( $search_info,$posts_per_page,$offset,'title'); //word by word title like search
					$title_results = $wpdb->get_results( $title_sql );
					if(count($string_results)>0){
						return $title_results;
					}else{
						$section_sql = kbxsearch_sql_prepare( $search_info,$posts_per_page,$offset,'section'); //word by word section like search
						$section_results = $wpdb->get_results( $section_sql );
						if(count($section_results)>0){
							return $section_results;
						}else{
							$description_sql = kbxsearch_sql_prepare( $search_info,$posts_per_page,$offset,'description'); //word by word description like search
							$description_results = $wpdb->get_results( $description_sql );
							return $description_results;
						}
					}
				}
				

	        }
	}
}
