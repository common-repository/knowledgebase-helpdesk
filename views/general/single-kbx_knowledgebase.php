<?php
/**
 * The template for displaying post type archives
 *
 */

global $wp_query, $kbx_options;

get_header();
//Loading the read time for kbx articles.
if( array_key_exists('kbx_read_time', $kbx_options) ){
    if (is_singular('kbx_knowledgebase') && $kbx_options['kbx_read_time']) {
        require_once(KBX_DIR . '/includes/kbx-reading-time.php');
    }
}

?>

    <?php //if ($show_search_form == 'true') : ?>

        <?php echo kbx_get_search_form(); ?>

    <?php //endif; ?>

    <div class="kbx-outer-wrapper"
         style="max-width:<?php echo $kbx_options['article_max_width']; ?>px; margin-top:<?php echo $kbx_options['article_margin_top']; ?>px;">

         <div class="kbx-inner-details-wrapper">
         <section class="kbx-category-list-sidebar">


         <h2 class="sidebar-category-title">
<?php if(isset($kbx_options['sidebar_category_title'])){echo $kbx_options['sidebar_category_title']; }else{echo "Browse Articles";} ?>
</h2>
<?php
    if (isset($kbx_options['show_empty_categories']) && $kbx_options['show_empty_categories']) {
        $terms = get_terms('kbx_category', array(
            'hide_empty'    => false,
            'hierarchical'  => 0,
            'parent'        => 0,
        ));
    } else {
        $terms = get_terms('kbx_category', array(
            'hide_empty'    => true,
            'hierarchical'  => 0,
            'parent'        => 0,
        ));

    }
?>


    <ul class="kbx-sidebar-navbar-nav">

       <?php foreach ($terms as $term) :  

        $kbxhd_get_sub_child = kbxhd_get_sub_child_categories($term->term_id);
        ?>
        <li class="kbx-sidebar-nav-item <?php echo (isset($kbxhd_get_sub_child) && !empty( $kbxhd_get_sub_child) ) ? 'kbx-sidebar-dropdown-item' : ''; ?>">
            <a class="kbx-sidebar-nav-link" href="<?php echo get_term_link($term->term_id); ?>">
                <?php echo $term->name; ?>
            </a>
            <?php 
            echo kbxhd_get_sub_child_categories($term->term_id);
            ?>
        </li>
        <?php  endforeach; ?>
        
    </ul>

	<!-- <ul class="kbx-sidebar-navbar-nav">

	
		<li class="kbx-sidebar-nav-item kbx-sidebar-dropdown-item">
			<a class="kbx-sidebar-nav-link" href="#">
				Каталог
			</a>
			<div class="kbx-sidebar-dropdown-menu">
				<ul>
					<li><a href="#" class="kbx-sidebar-nav-link">20 футов</a></li>
					<li><a href="#" class="kbx-sidebar-nav-link">40 футов</a></li>
					<li><a href="#" class="kbx-sidebar-nav-link">45 футов</a></li>
					<li><a href="#" class="kbx-sidebar-nav-link">Новые</a></li>
					<li><a href="#" class="kbx-sidebar-nav-link">Б/У</a></li>
				</ul>
	
				
	
			</div>
		</li>
		<li class="kbx-sidebar-nav-item kbx-sidebar-dropdown-item">
	
			<a class="kbx-sidebar-nav-link" href="#">
				<span>Услуги</span>
			</a>
	
			<div class="kbx-sidebar-dropdown-menu">
				<span><a href="#" class="kbx-sidebar-nav-link">Доставка ЖД контейнеров</a></span>
				<span><a href="#" class="kbx-sidebar-nav-link">Онлайн осмотр морских контейнеров</a></span>
				<span><a href="#" class="kbx-sidebar-nav-link">Грузоперевозки в контейнерах</a></span>
				<span><a href="#" class="kbx-sidebar-nav-link">Ремонт морских контейнеров</a></span>
			</div>
		</li>
		<li class="kbx-sidebar-nav-item kbx-sidebar-dropdown-item">
			<a class="kbx-sidebar-nav-link" href="#">
				<span>
					Информация
				</span>
			</a>
			<div class="kbx-sidebar-dropdown-menu">
				<span><a href="#" class="kbx-sidebar-nav-link">Прайс</a></span>
				<span><a href="#" class="kbx-sidebar-nav-link">Вопрос-ответ (FAQ)</a></span>
				<span><a href="#" class="kbx-sidebar-nav-link">Словарь терминов</a></span>
				<span><a href="#" class="kbx-sidebar-nav-link">Регионы поставок</a></span>
				<span><a href="#" class="kbx-sidebar-nav-link">Документы</a></span>
				<span><a href="#" class="kbx-sidebar-nav-link">Наши клиенты</a></span>
				<span><a href="#" class="kbx-sidebar-nav-link">Статьи</a></span>
			</div>
		</li>
	
	</ul> -->

</section>
        
        
        
        
         <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <?php
            $kbx_artical_title = "";
            if (have_posts()) : while (have_posts()) : the_post();
                $kbx_artical_title = get_the_title();
                $taxonomy_terms = get_the_terms(get_the_ID(), 'kbx_category');
                $term_id = $taxonomy_terms[0]->term_id;
                $current_article_id = get_the_ID();
                if (kbx_article_user_permit($term_id)) {
                    ?>

                    <div class="single-kbx-post-outer">
                        <header class="entry-header">
                            <h1 class="entry-title" style="margin-bottom: 10px;"><?php echo esc_html(get_the_title()); ?></h1>
							
							<?php
                                if( array_key_exists('kbx_read_time', $kbx_options) ){
        							if($kbx_options['kbx_read_time']==1){
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

            							echo '<span class="rt-reading-time" style="display: block;margin-bottom: 10px;">'.'<span class="rt-time" style=" margin-right: 3px;">'.$data.'</span>'.'<span class="rt-label" style=" margin-right: 3px;"> '.$calculatedPostfix.'</span>'.'<span class="rt-label" style=" margin-right: 3px;">'.$label.'</span>'.'</span>';
                                    }
        						}
							?>
							
                            <?php
                            if (function_exists('kbx_custom_breadcrumbs') && isset($kbx_options['enable_kbx_breadcrum']) && $kbx_options['enable_kbx_breadcrum'] == '1') {
                                kbx_custom_breadcrumbs();
                            }
                            ?>
                        </header>
                        <div class="entry-content kbx-article-body">
                            <?php the_content(); ?>

                        </div>
                        <!-- entry-content-->
                        <?php
                        $kpm_more_queries = maybe_unserialize(get_post_meta(get_the_ID(), 'kpm_more_queries', true));
                        if (isset($kpm_more_queries) && !empty($kpm_more_queries) && count($kpm_more_queries) > 0 && $kpm_more_queries[0] != "") {
                            ?>
                            <div class="kbx-articles-more-queries number-count-list">
                                <p><strong><i class="fa fa-tasks"
                                              aria-hidden="true"></i> <?php esc_html_e('This Article Also Answers the Following Questions : ', 'kbx-qc'); ?>
                                    </strong></p>
                                <ul>
                                    <?php
                                    $kpm_more_counter = 1;
                                    foreach ($kpm_more_queries as $kpm_more_query) {
                                        ?>
                                        <li><?php echo esc_attr($kpm_more_query); ?></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        }
                        //=========
                        $kpm_article_files = maybe_unserialize(get_post_meta(get_the_ID(), 'kpm_article_file', true));
                        if (isset($kpm_article_files) && !empty($kpm_article_files)) {
                            ?>
                            <div class="kbx-articles-files number-count-list">
                                <?php
                                $kpm_file_counter = 0;
                                $kpm_article_labels = $kpm_article_files['file_label'];
                                $kpm_article_links = $kpm_article_files['file_link'];
                                ?>
                                <ul>
                                    <p><strong><i class="fa fa-download"
                                                  aria-hidden="true"></i> <?php esc_html_e('Attached Files', 'kbx-qc'); ?>
                                        </strong></p>
                                    <?php
                                    foreach ($kpm_article_labels as $kpm_article_label) {
                                        ?>
                                        <li> <a href="<?php echo $kpm_article_links[$kpm_file_counter]; ?>"
                                                  target="_blank"><?php echo $kpm_article_label; ?></a></li>
                                        <?php
                                        $kpm_file_counter++;
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="kbx-articles-tags">
                            <?php the_terms(get_the_ID(), 'kbx_tag', '', ' '); ?>
                        </div>
                        <div class="kbx-poet-meta">
                            
                            <?php kbx_after_single_content() ?>
                        </div>

                    </div>
                    <!--single-kbx-post-outer-->
                    <?php
                } else {
                    esc_html_e('You do not have permission to this section.', 'kbx-qc');
                }

            endwhile;
            endif;
            ?>

        </article>

        </div>












        <section class="kbx-article-related-articles number-count-list">
            <?php
            function kbx_title_filter($where, $wp_query)
            {
                global $wpdb;
                if ($search_term = $wp_query->get('title_filter')) {
                    $search_term = $wpdb->esc_like($search_term); //instead of esc_sql()
                    $search_term = ' \'%' . $search_term . '%\'';
                    $title_filter_relation = (strtoupper($wp_query->get('title_filter_relation')) == 'OR' ? 'OR' : 'AND');
                    $where .= ' ' . $title_filter_relation . ' ' . $wpdb->posts . '.post_title LIKE ' . $search_term;
                }
                return $where;
            }

            add_filter('posts_where', 'kbx_title_filter', 10, 2);
            if (isset($kbx_options['enable_related_artilces']) && $kbx_options['enable_related_artilces'] == '1' && isset($term_id)) {
                $kb_related_args = array(
                    'post_type' => 'kbx_knowledgebase',
					'post_status'=>'publish',
                    //'title_filter' => $kbx_artical_title,
                    //'title_filter_relation' => 'OR',
                    'posts_per_page' => $kbx_options['kbx_per_page'],
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'kbx_category',
                            'field' => 'term_id',
                            'terms' => $term_id,
                        ),
                    )
                );
                $related_query = new WP_Query($kb_related_args);

                if ($related_query->have_posts()) : ?>
                    <h3><i class="fa fa-diamond" aria-hidden="true"></i> <?php esc_html_e('Related Knowledge Base Posts', 'kbx-qc'); ?></h3>

                    <ul class="related-articles">

                        <?php 
						
						$kbxReadingOptions = get_option('kbx_reading_time_options');
                        if( array_key_exists('kbx_read_time', $kbx_options) ){
						  $kbxArticleReadTime = new kbxArticleReadTime();
                        }
						while ($related_query->have_posts()) : $related_query->the_post();
                            if ($current_article_id != get_the_ID()) {
                                ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>">
                                        <!--                                        <i class="fa fa-file-text-o"></i>-->

                                        <?php the_title(); ?>

                                    </a>
									<?php
                                        if( array_key_exists('kbx_read_time', $kbx_options) ){
    										if($kbx_options['kbx_read_time']==1){
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

    											echo '<span class="rt-reading-time" style="display: inline-block;">('.'<span class="rt-time" style=" margin-right: 3px;">'.$data.'</span>'.'<span class="rt-label" style=" margin-right: 3px;"> '.$calculatedPostfix.'</span>'.'<span class="rt-label" style=" margin-right: 3px;">'.$label.'</span>)'.'</span>';
                                           }
									   }
									?>
                                    

                                    <a href="<?php echo get_term_link($term->term_id); ?>" class="qckbx-readmore">Read more <i class="fa fa-long-arrow-right"></i></a>

                                </li>
                                <?php
                            }
                        endwhile;// wp_reset_postdata(); ?>

                    </ul>

                <?php else : ?>

                    <p>
                        <?php esc_html_e('No articles found under this section.', 'kbx-qc'); ?>
                    </p>

                <?php endif; ?>

            <?php }
            remove_filter('posts_where', 'kbx_title_filter', 10, 2);
            ?>
        </section>
        <section class="kbx-article-comments">
            <?php
            if (isset($kbx_options['enable_article_comments']) && $kbx_options['enable_article_comments'] == '1') {
                comments_template('', true);
            }
            ?>
        </section>
        <!--        kbx-article-comments-->

    </div>
    <!--    kbx-outer-wrapper-->

<?php get_footer();


