<?php
defined('ABSPATH') or die("You can't access this file directly.");
/*
Kbx articles reading time.
*/

class kbxArticleReadTime {

	// Add label option using add_option if it does not already exist
	public $KbxreadingTime;
	public $readingTime;

	public function __construct() {
       $kbx_options=get_option( 'kbx_settings' );
	   
		$defaultSettings = array(
			'label' =>$kbx_options['kbx_read_time_label'] ,
			'postfix' => __( 'minutes', 'kbx-qc' ),
			'postfix_singular' => __( 'minute', 'kbx-qc' ),
			'wpm' => 300,
			'before_content' => true,
			'before_excerpt' => false,
			'exclude_images' => false,
		);

		//only for kbx_knowledgebase post type
        $defaultSettings['post_types']['kbx_knowledgebase'] = true;
		if(get_option('kbx_reading_time_options')){
			update_option('kbx_reading_time_options', $defaultSettings);
		}else{
			add_option('kbx_reading_time_options', $defaultSettings);
		}
		

		//add_filter('the_content', array($this, 'kbx_add_reading_time_before_content'));
		
		/*if ( isset($kbx_options['kbx_read_time_before_content']) && $kbx_options['kbx_read_time_before_content'] == true ) {
			add_filter('the_content', array($this, 'kbx_add_reading_time_before_content'));
		}*/

		/*if( isset($kbx_options['kbx_read_time_before_excerpt']) && $kbx_options['kbx_read_time_before_excerpt'] ==true ) {
			add_filter('get_the_excerpt', array($this, 'kbx_add_reading_time_before_excerpt'), 1000);
		}*/

	}

	public function kbx_calculate_reading_time($kbxPostID, $kbxOptions) {

		$kbxContent = get_post_field('post_content', $kbxPostID);
		$number_of_images = substr_count(strtolower($kbxContent), '<img ');
		if ( ! isset( $kbxOptions['include_shortcodes'] ) ) {
			$kbxContent = strip_shortcodes($kbxContent);
		}
		$kbxContent = strip_tags($kbxContent);
		$wordCount = str_word_count($kbxContent);

		if ( isset($kbxOptions['exclude_images'] ) && $kbxOptions['exclude_images'] ) {
			// Don't calculate images if they've been set to be excluded
		} else {
			// Calculate additional time added to post by images
			$additional_words_for_images = $this->kbx_calculate_images( $number_of_images, $kbxOptions['wpm'] );
			$wordCount += $additional_words_for_images;
		}

		$wordCount = apply_filters( 'kbx_filter_wordcount', $wordCount );

		$this->readingTime = ceil($wordCount / $kbxOptions['wpm']);

		// If the reading time is 0 then return it as < 1 instead of 0.
		if ( $this->readingTime < 1 ) {
			$this->readingTime = __('< 1', 'kbx-qc');
		}

		return $this->readingTime;

	}

	/**
	 * Adds additional reading time for images
	 *
	 * Calculate additional reading time added by images in posts. Based on calculations by Medium. https://blog.medium.com/read-time-and-you-bc2048ab620c
	 *
	 * @since 1.1.0
	 *
	 * @param int $total_images number of images in post
	 * @param array $wpm words per minute
	 * @return int Additional time added to the reading time by images
	 */
	public function kbx_calculate_images( $total_images, $wpm ) {
		$additional_time = 0;
		// For the first image add 12 seconds, second image add 11, ..., for image 10+ add 3 seconds
		for ( $i = 1; $i <= $total_images; $i++ ) {
			if ( $i >= 10 ) {
				$additional_time += 3 * (int) $wpm / 60;
			} else {
				$additional_time += (12 - ($i - 1) ) * (int) $wpm / 60;
			}
		}

		return $additional_time;
	}


    // Calculate reading time by running it through the_content
	public function kbx_add_reading_time_before_content($content) {
		$kbxReadingOptions = get_option('kbx_reading_time_options');

		// Get the post type of the current post
		$kbx_current_post_type = get_post_type();

		// If the current post type isn't included in the array of post types or it is and set to false, don't display it.
		if ( isset( $kbxReadingOptions['post_types'] ) && ( ! isset( $kbxReadingOptions['post_types'][$kbx_current_post_type] ) || ! $kbxReadingOptions['post_types'][$kbx_current_post_type] ) ) {
			return $content;
		}

		$originalContent = $content;
		$kbxPost = get_the_ID();

		$this->kbx_calculate_reading_time($kbxPost, $kbxReadingOptions);

		$label = $kbxReadingOptions['label'];
		$postfix = $kbxReadingOptions['postfix'];
		$postfix_singular = $kbxReadingOptions['postfix_singular'];

		if(in_array('get_the_excerpt', $GLOBALS['wp_current_filter'])) {
			return $content;
		}

		if($this->readingTime > 1) {
			$calculatedPostfix = $postfix;
		} else {
			$calculatedPostfix = $postfix_singular;
		}

		$content = '<span class="rt-reading-time" style="display: block;">'.'<span class="rt-label">'.$label.' </span>'.'<span class="rt-time">'.$this->readingTime.'</span>'.'<span class="rt-label"> '.$calculatedPostfix.'</span>'.'</span>';
		$content .= $originalContent;
		return $content;
	}

	public function kbx_add_reading_time_before_excerpt($content) {
		$kbxReadingOptions = get_option('kbx_reading_time_options');

		// Get the post type of the current post
		$kbx_current_post_type = get_post_type();

		// If the current post type isn't included in the array of post types or it is and set to false, don't display it.
		if ( ! isset( $kbxReadingOptions['post_types'][$kbx_current_post_type] ) || ! $kbxReadingOptions['post_types'][$kbx_current_post_type] ) {
			return $content;
		}

		$originalContent = $content;
		$kbxPost = get_the_ID();

		$this->kbx_calculate_reading_time($kbxPost, $kbxReadingOptions);

		$label = $kbxReadingOptions['label'];
		$postfix = $kbxReadingOptions['postfix'];
		$postfix_singular = $kbxReadingOptions['postfix_singular'];

		if($this->readingTime > 1) {
			$calculatedPostfix = $postfix;
		} else {
			$calculatedPostfix = $postfix_singular;
		}

		$content = '<span class="rt-reading-time" style="display: block;">'.'<span class="rt-label">'.$label.'</span>'.'<span class="rt-time">'.$this->readingTime.'</span>'.'<span class="rt-label"> '.$calculatedPostfix.'</span>'.'</span>';
		$content .= $originalContent;
		return $content;
	}


}

$kbxArticleReadTime = new kbxArticleReadTime();
//print_r($kbxArticleReadTime);exit;
?>
