<?php
defined('ABSPATH') or die("You can't access this file directly.");
/**
 * Template functions used by Better Search
 *
 * @package Better_Search
 */


/**
 * returns an array with the first and last indices to be displayed on the page.
 *
 * @since	2.0.0
 *
 * @param	array $search_info    Search query
 * @param 	bool  $boolean_mode   Set BOOLEAN mode for FULLTEXT searching
 * @param	bool  $bydate         Sort by date?
 * @return	array	First and last indices to be displayed on the page
 */
if ( ! function_exists( 'kbxsearch_sql_prepare' ) ) {
  function kbxsearch_sql_prepare( $search_info,$posts_per_page,$offset,$reffer  ) {

  	global $wpdb, $kbx_options;

  	// Initialise some variables
  	$fields = '';
  	$where = '';
  	$join = '';
  	$groupby = '';
  	$orderby = '';

  	$post_types = array('kbx_knowledgebase');

  	$n = '%';



          // Fields to return
          $fields = ' ID, 0 AS score ';
          //Kbs taxonomies
          $kbx_taxs=array('kbx_tag','kbx_category');
          // Create the WHERE Clause
         if(is_string($search_info) && $reffer=='all' ){
            // $where = ' AND ( ';
            //  $where .= $wpdb->prepare(
            //      " (post_title LIKE '%s') OR (pm.meta_key = '%s' AND pm.meta_value LIKE '%s')",
            //      $n . $search_info . $n,
            //      'kpm_more_queries',
            //      $n .$search_info. $n


            //  );
            //  $where .= ' ) ';

             $search_terms = explode( ' ', $search_info );

             //$search_terms = $search_info[1];
              $where = ' AND ( ( ';
              $where .= $wpdb->prepare(
                  " (post_title LIKE '%s')",
                  $n . $search_terms[0] . $n
              );

              for ( $i = 1; $i < count( $search_terms ); $i = $i + 1 ) {
                  $where .= $wpdb->prepare(
                      " OR (post_title LIKE '%s') ",
                      $n . $search_terms[ $i ] . $n
                  );
              }
              /*for ( $i = 0; $i < count( $search_terms ); $i = $i + 1 ) {
                  foreach ($kbx_taxs as $tax) {
                      $where .= $wpdb->prepare("OR (tt.taxonomy = '%s' AND t.name LIKE '%s')", $tax, $n .$search_terms[$i]. $n);
                  }
              }*/
              $where .= ' ) ';


             //$search_terms = $search_info[1];
             $where .= ' OR ( ';

              $count = 0;
             for ( $i = 0; $i < count( $search_terms ); $i = $i + 1 ) {
                 foreach ($kbx_taxs as $tax) {
                    $count++;
                    if( $count == 1 ){
                      $where .= $wpdb->prepare(" (tt.taxonomy = '%s' AND t.name LIKE '%s')", $tax, $n .$search_terms[$i]. $n);
                    }else{
                      $where .= $wpdb->prepare(" OR (tt.taxonomy = '%s' AND t.name LIKE '%s')", $tax, $n .$search_terms[$i]. $n);
                    }
                 }
             }
             $where .= ' ) ';


             //$search_terms = $search_info[1];
             $where .= ' OR ( ';
             $where .= $wpdb->prepare(
                 " (post_content LIKE '%s')",
                 $n . $search_terms[0] . $n
             );

             for ( $i = 1; $i < count( $search_terms ); $i = $i + 1 ) {
                 $where .= $wpdb->prepare(
                     " OR (post_content LIKE '%s') ",
                     $n . $search_terms[ $i ] . $n
                 );
             }
             $where .= ' ) ) ';


         }else if(is_string($search_info) && $reffer=='string' ){
             $where = ' AND ( ';
             $where .= $wpdb->prepare(
                 " ((post_title LIKE '%s') OR (post_content LIKE '%s') OR (post_excerpt LIKE '%s')) OR (pm.meta_key = '%s' AND pm.meta_value LIKE '%s')",
                 $n . $search_info . $n,
                  $n . $search_info . $n,
                  $n . $search_info . $n,
                 'kpm_more_queries',
                 $n .$search_info. $n


             );
             $where .= ' ) ';
             //$where .= $wpdb->prepare("OR (pm.meta_key = '%s' AND pm.meta_value LIKE '%s')", 'kpm_more_queries', $n .$search_info. $n);
         }else if(is_array($search_info && $reffer=='title')){

          // $search_terms = $search_info[1];
          // $where = ' AND ( ';
          // $where .= $wpdb->prepare(
          //     " (post_title LIKE '%s')",
          //     $n . $search_terms[0] . $n
          // );

          // for ( $i = 1; $i < count( $search_terms ); $i = $i + 1 ) {
          //     $where .= $wpdb->prepare(
          //         " OR (post_title LIKE '%s') ",
          //         $n . $search_terms[ $i ] . $n
          //     );
          // }
          // /*for ( $i = 0; $i < count( $search_terms ); $i = $i + 1 ) {
          //     foreach ($kbx_taxs as $tax) {
          //         $where .= $wpdb->prepare("OR (tt.taxonomy = '%s' AND t.name LIKE '%s')", $tax, $n .$search_terms[$i]. $n);
          //     }
          // }*/
          // $where .= ' ) ';
  	}else if(is_array($search_info && $reffer=='section')){
             $search_terms = $search_info[1];
             $where = ' AND ( ';

             for ( $i = 0; $i < count( $search_terms ); $i = $i + 1 ) {
                 foreach ($kbx_taxs as $tax) {
                     $where .= $wpdb->prepare("OR (tt.taxonomy = '%s' AND t.name LIKE '%s')", $tax, $n .$search_terms[$i]. $n);
                 }
             }
             $where .= ' ) ';
       }else if(is_array($search_info && $reffer=='description')){

             $search_terms = $search_info[1];
             $where = ' AND ( ';
             $where .= $wpdb->prepare(
                 " (post_content LIKE '%s')",
                 $n . $search_terms[0] . $n
             );

             for ( $i = 1; $i < count( $search_terms ); $i = $i + 1 ) {
                 $where .= $wpdb->prepare(
                     " OR (post_content LIKE '%s') ",
                     $n . $search_terms[ $i ] . $n
                 );
             }
             $where .= ' ) ';
         }


      if( $reffer=='all' ){
        $where .= " AND ( (post_status = 'publish')";
        $where .= " AND $wpdb->posts.post_type IN ('" . join( "', '", $post_types ) . "') ) ";
      }else{
        $where .= " AND (post_status = 'publish' OR post_status = 'inherit')";

        // Array of post types
        $where .= " AND $wpdb->posts.post_type IN ('" . join( "', '", $post_types ) . "') ";
      }


      // Create the ORDERBY Clause
      if(get_option('kbx_bot_product_orderby') && get_option('kbx_bot_product_orderby')=='title' ){
          $orderby= "$wpdb->posts.post_title";
      } else if(get_option('kbx_bot_product_orderby') && get_option('kbx_bot_product_orderby')=='date' ){
          $orderby= "$wpdb->posts.post_date";
      }else if(get_option('kbx_bot_product_orderby') && get_option('kbx_bot_product_orderby')=='modified' ){
          $orderby= "$wpdb->posts.post_modified";
      }else if(get_option('kbx_bot_product_orderby') && get_option('kbx_bot_product_orderby')=='random' ){
          $orderby= "$wpdb->posts.post_date";
      }else if(get_option('kbx_bot_product_orderby') && get_option('kbx_bot_product_orderby')=='none' ){
          $orderby= " ";
      }else{
          $orderby= "$wpdb->posts.post_date";
      }

      //create the Order clause
      if(get_option('kbx_bot_product_order') && get_option('kbx_bot_product_order')=='ASC' ){
          $order= 'ASC';
      } else if(get_option('kbx_bot_product_order') && get_option('kbx_bot_product_order')=='DESC' ){
          $order= 'DESC';
      }else{
          $order= 'ASC';
      }

      /**
  	 * Filter the WHERE clause of the query.
  	 *
  	 * @since	2.0.0
  	 *
  	 * @param string   $where  		The WHERE clause of the query
  	 * @param string   $search_info[0]	Search query
  	 */
  	$where = apply_filters( 'bsearch_posts_where', $where, $search_info[0] );

      /**
       * Filter the ORDER BY clause of the query.
       *
       * @since	2.0.0
       *
       * @param string   $orderby  		The ORDER BY clause of the query
       * @param string   $search_info[0]	Search query
       */
      $orderby = apply_filters( 'bsearch_posts_orderby', $orderby, $search_info[0] );

  	/**
  	 * Filter the ORDER BY clause of the query.
  	 *
  	 * @since	2.0.0
  	 *
  	 * @param string   $order  		The ORDER BY clause of the query
  	 * @param string   $search_info[0]	Search query
  	 */
      $order = apply_filters( 'bsearch_posts_order', $order, $search_info[0] );

  	/**
  	 * Filter the GROUP BY clause of the query.
  	 *
  	 * @since	2.0.0
  	 *
  	 * @param string   $groupby  		The GROUP BY clause of the query
  	 * @param string   $search_info[0]	Search query
  	 */
  	$groupby = apply_filters( 'bsearch_posts_groupby', $groupby, $search_info[0] );

  	/**
  	 * Filter the JOIN clause of the query.
  	 *
  	 * @since	2.0.0
  	 *
  	 * @param string   $join  		The JOIN clause of the query
  	 * @param string   $search_info[0]	Search query
  	 */
  	$join = kbx_articles_join_table($join);
  	//$join = apply_filters( 'bsearch_posts_join', $join, $search_info[0] );

  	/**
  	 * Filter the JOIN clause of the query.
  	 *
  	 * @since	2.0.0
  	 *
  	 * @param string   $limits  		The JOIN clause of the query
  	 * @param string   $search_info[0]	Search query
  	 */
  	//$limits = apply_filters( 'bsearch_posts_limits', $limits, $search_info[0] );
      $limits    =   " LIMIT ".$posts_per_page." OFFSET ".$offset;

      if ( ! empty( $groupby ) ) {
  		$groupby = 'GROUP BY ' . $groupby;
  	}
  	if ( isset( $orderby ) && $orderby!="" ) {
  		$orderby = 'ORDER BY ' . $orderby;
  	}

  	$sql = "SELECT DISTINCT $fields FROM $wpdb->posts $join WHERE 1=1 $where $groupby $orderby $order $limits";

  	/**
  	 * Filter MySQL string used to fetch results.
  	 *
  	 * @param	string	$sql			MySQL string
  	 * @param	array	$search_info	Search query
  	 * @param 	bool	$boolean_mode	Set BOOLEAN mode for FULLTEXT searching
  	 * @param	bool	$bydate			Sort by date?
  	 */
  	return apply_filters( 'kbxsearch_sql_prepare', $sql, $search_info);
      /***
       * Table Joins for taxonomies
       */
      //add_filter('posts_join_request', array($this, 'kbx_articles_join_table'));
  }
}

/**
 * Returns an array with the cleaned-up search string at the zero index and possibly a list of terms in the second.
 *
 * @param	mixed $search_query   The search term.
 * @return	array	Cleaned up search string
 */
if ( ! function_exists( 'get_kbxsearch_terms' ) ) {
  function get_kbxsearch_terms( $search_query ) {

  	global $kbx_options;

  	if ( ( '' == $search_query ) || empty( $search_query ) ) {
  		$search_query = '';
  	}

  	$s_array[0] = $search_query;

  	/**
  	*	If use_fulltext is false OR if all the words are shorter than four chars,
  	*   add the array of search terms.
  	*	Currently this will disable match ranking and won't be quote-savvy.
  	*	If we are using fulltext, turn it off unless there's a search word
  	*   longer than three chars
  	*	ideally we'd also check against stopwords here
  	*/
  	    $search_words = explode( ' ', $search_query );
  		$s_array[0] = $search_query;	// Save original query at [0]
  		$s_array[1] = $search_words;	// Save array of terms at [1]

  	/**
  	 * Filter array holding the search query and terms
  	 *
  	 * @param	array	$s_array	Original query is at [0] and array of terms at [1]
  	 */

  	return apply_filters( 'get_kbxsearch_terms', $s_array );
  }
}
//extra
if ( ! function_exists( 'kbx_articles_join_table' ) ) {
  function kbx_articles_join_table($join){
      global $wpdb;
      //join taxonomies table
          $join .= " LEFT JOIN $wpdb->term_relationships tr ON ($wpdb->posts.ID = tr.object_id) ";
          $join .= " LEFT JOIN $wpdb->term_taxonomy tt ON (tr.term_taxonomy_id = tt.term_taxonomy_id) ";
          $join .= " LEFT JOIN $wpdb->terms t ON (tt.term_id = t.term_id) ";
          $join .= " LEFT JOIN $wpdb->postmeta pm ON ($wpdb->posts.ID = pm.post_id) ";

      return $join;
  }
}

