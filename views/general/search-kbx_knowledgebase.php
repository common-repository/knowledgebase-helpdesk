<?php
/**
 * The template for displaying taxonomy archives
 *
 * Used to display custom taxonomy archives if no archive template is found in the theme folder.
 *
 */

global $wp_query;

get_header();

$search_term = isset($_GET['kbx-query']) ? sanitize_text_field( $_GET['kbx-query'] ) : "";
$posts_per_page=$kbx_options['kbx_per_page'];
$offset=0;
$results = kbx_get_search_results( $search_term,$posts_per_page,$offset, 'all' );
?>

<div class="kbx-outer-wrapper" style="max-width:<?php echo $kbx_options['article_max_width'] ;?>px; margin-top:<?php echo $kbx_options['article_margin_top'] ;?>px">

	<section id="primary" class="content-area">

		<?php echo kbx_get_search_form(); ?>

		<header class="page-header">
			<h2 class="page-title">
				<?php esc_html_e('Search Results For: ', 'kbx-qc'); ?>
				<?php echo esc_html( $search_term ); ?>
			</h2>
		</header><!-- .page-header -->

		<?php if ( is_countable($results) && count($results) > 0 ) : ?>

		<section class="kbx-articles">

			<div class="clear"></div>

			<ul class="articleList">

		        <?php foreach( $results as $result ) : ?>
		        <li>
		        	<a href="<?php echo get_permalink($result->ID); ?>">
		        		<i class="fa fa-file-text-o"></i>
		        		<span>
		        			<?php echo get_the_title($result->ID); ?>
		        		</span>
		        	</a>
		        </li>
		    	<?php endforeach; ?>

			</ul>

		</section>

		<?php

			// If no content, include the "No posts found" template.
		else :

		echo '<div class="kbx-center kbx-strong">';

			esc_html_e('No articles found as per your search query.', 'kbx-qc');

		echo '</div>';

		endif;

		?><!-- .site-main -->

	</section><!-- .content-area -->

</div>

<?php get_footer();


