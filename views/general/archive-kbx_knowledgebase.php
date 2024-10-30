<?php
/**
 * The template for displaying post type archives
 *
 */

global $wp_query;

get_header();
global $kbx_options;
global $wp;
$current_url = home_url(add_query_arg(array(),$wp->request));
$parsed = parse_url($current_url);
$path = $parsed['path'];
$path_parts = explode('/', $path);
if(in_array($kbx_options['tag_slug'],$path_parts)){
    //Here this will load for Tags
?>
    <div class="kbx-outer-wrapper" style="max-width:<?php echo $kbx_options['article_max_width'].'px';?>; margin: 0 auto; ">

        <section class="kbx-articles">

            <?php
            $page_title = esc_html('Knowledebase Articles', 'kbx-qc');

            if( is_tax( 'kbx_category' ) )
            {

                $page_title = single_term_title('Knowledebase Section: ', false);;
            }

            if( is_tax( 'kbx_tag' ) )
            {

                $page_title = single_term_title('Knowledebase Tag: ', false);;
            }
            ?>

            <header class="page-header">
                <h2 class="page-title">
                    <?php echo esc_html( $page_title ); ?>
                </h2>
            </header>

            <div class="clear"></div>

            <?php if( have_posts() ) : ?>

                <div id="categoryHead">
                    <div class="sort">
                        <form action="" method="GET">

                            <select name="sort" id="sortBy" title="<?php esc_html_e('Sort By', 'kbx-qc'); ?>" onchange="this.form.submit();">
                                <option value="" selected="selected"><?php esc_html_e('Sort by Default', 'kbx-qc'); ?></option>
                                <option value="name"><?php esc_html_e('Sort A-Z', 'kbx-qc'); ?></option>
                                <option value="views"><?php esc_html_e('Sort by Views', 'kbx-qc'); ?></option>
                            </select>

                        </form>
                    </div>
                </div>

                <div class="clear"></div>

                <ul class="articleList">

                    <?php while( have_posts() ) : the_post() ?>
                        <li>
                            <a href="<?php the_permalink(); ?>">
                                <i class="fa fa-file-text-o"></i>
                                <span>
						<?php the_title(); ?>
					</span>
                            </a>
                        </li>
                    <?php endwhile; ?>

                </ul>

                <section class="kbx-pagination">

                    <?php
                    echo paginate_links();
                    ?>

                </section>

            <?php else : ?>

                <p>
                    <?php esc_html_e('No articles found under this section.', 'kbx-qc'); ?>
                </p>

            <?php endif; ?>

        </section>

    </div>


<?php }else{
    //For Main shortcode  or else
?>
    <div id="main-content" style="margin-top:20px;margin-left: 10px;margin-right: 10px; " >
            <div id="content-area" style="margin: 0 auto; ">
                <?php echo do_shortcode("[kbx-knowledgebase]"); ?>
            </div>
    </div>

<?php } get_footer();


