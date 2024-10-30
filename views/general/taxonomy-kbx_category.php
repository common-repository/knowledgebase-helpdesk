<?php
/**
 * The template for displaying taxonomy archives
 *
 * Used to display custom taxonomy archives if no archive template is found in the theme folder.
 *
 */

global $wp_query,$kbx_options;
$term = $wp_query->get_queried_object();

/* This plugin uses the Archive file of TwentyFifteen theme as an example */
get_header();

// Hide the first level header when displaying the category archives.
$custom_css = '
	.wzkb-section-name-level-1 {
		display: none;
	}
';
wp_add_inline_style( 'wzkb_styles', $custom_css );


?>

<div class="kbx-outer-wrapper" style="margin-top:<?php echo $kbx_options['article_margin_top'] ;?>px">

	<section id="primary" class="content-area">
		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h2 class="page-title"><?php echo esc_html( $term->name ); ?></h2>
                <?php
                if(function_exists('kbx_custom_breadcrumbs') && isset($kbx_options['enable_kbx_breadcrum']) && $kbx_options['enable_kbx_breadcrum'] == '1' ) {
                    kbx_custom_breadcrumbs();
                }
                ?>
			</header><!-- .page-header -->

			<?php

            if ( kbx_article_user_permit($term->term_id) ){
                echo do_shortcode( "[kbx-knowledgebase-articles section='{$term->term_id}']" );
            }else{
                esc_html_e('You do not have permission to this section.', 'kbx-qc');
            }

			// If no content, include the "No posts found" template.
		else :

			esc_html_e('No articles found under this section.', 'kbx-qc');

		endif;
		?><!-- .site-main -->

	</section><!-- .content-area -->
	
</div>

<?php get_footer();


