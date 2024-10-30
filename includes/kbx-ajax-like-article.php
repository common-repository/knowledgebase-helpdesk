<?php
defined('ABSPATH') or die("You can't access this file directly.");

if ( ! function_exists( 'kbx_post_like_action' ) ) {
	function kbx_post_like_action(){

		//Get posted items
		$post_id 	= isset( $_POST['post_id'] ) 	? trim( sanitize_text_field( $_POST['post_id'] ) ) : '';
		$like_type 	= isset( $_POST['like_type'] ) 	? trim( sanitize_text_field( $_POST['like_type'] ) ) : '';
		$post_id 	= intval($post_id);

		//Check wpdb directly, for all matching meta items
		global $wpdb;

		//Defaults
		$votes = 0;

		$data['votes'] = 0;
		$data['vote_status'] = 'failed';

		//$exists = in_array("$post_id", $_COOKIE['voted_articles']);

	    if( isset($_COOKIE['voted_articles'])){
	    	$exists = in_array("$post_id", $_COOKIE['voted_articles']);
	    }else{
	    	$exists = false;
	    }

		//If li-id not exists in the cookie, then prceed to vote
		if( !$exists )
		{
			
			if($like_type=="up"){
	            $votes = get_post_meta($post_id, 'kpm_upvotes', true);
	        }else if($like_type=="down"){
	            $votes = get_post_meta($post_id, 'kpm_downvotes', true);
	        }


			if( $votes == "" || $votes == null ){
				$votes = 0;
			}

			$vote_increment = $votes + 1;

	        if($like_type=="up"){
	            update_post_meta($post_id, 'kpm_upvotes', $vote_increment);
	        }else if($like_type=="down"){
	            update_post_meta($post_id, 'kpm_downvotes', $vote_increment);
	        }

			setcookie("voted_articles[]",$post_id, time() + (86400 * 30), "/");

			$data['vote_status'] = 'success';
			$data['votes'] = $vote_increment;
		}

		$data['cookies'] = $_COOKIE['voted_articles'];

		echo json_encode($data);

		die(); // stop executing script
	}

	//Implementing the ajax action for frontend users
	add_action( 'wp_ajax_kbx_post_like_action', 'kbx_post_like_action' );
	add_action( 'wp_ajax_nopriv_kbx_post_like_action', 'kbx_post_like_action' );
}

