<section class="kbx-articles">
    <?php if ($show_search_form == 'true') : ?>

        <?php echo kbx_get_search_form();
        global $kbx_options;
        if (isset($_GET['sort'])) {
            $sort_option = $_GET['sort'];
        } else {
            $sort_option = $kbx_options['sorting_option'];
        }

        ?>

    <?php endif; ?>
    <div id="categoryHead">
        <div class="sort">
            <form action="" method="GET">

                <select name="sort" id="sortBy" title="<?php esc_html_e('Sort By', 'kbx-qc'); ?>"
                        onchange="this.form.submit();">
                    <option value="date" <?php if ($sort_option == 'date') {
                        echo "selected";
                    } ?> ><?php esc_html_e('Sort by Default', 'kbx-qc'); ?></option>
                    <option value="name" <?php if ($sort_option == 'name') {
                        echo "selected";
                    } ?>><?php esc_html_e('Sort A-Z', 'kbx-qc'); ?></option>
                    <option value="popularity" <?php if ($sort_option == 'popularity') {
                        echo "selected";
                    } ?>><?php esc_html_e('Sort by Popularity', 'kbx-qc'); ?></option>
                    <option value="views" <?php if ($sort_option == 'views') {
                        echo "selected";
                    } ?>><?php esc_html_e('Sort by Views', 'kbx-qc'); ?></option>
                </select>

            </form>
        </div>
    </div>

    <div class="clear"></div>
   

    <?php if ($query->have_posts()) : ?>



<div class="kbx-category-list-details">

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

</section>




        <div class="kbx-category-list">
            <?php 

             $excluded_posts= array();

            if ($sticky_query->have_posts()){ while ($sticky_query->have_posts()){  
                $sticky_query->the_post(); $image_id=get_post_thumbnail_id(get_the_ID()); 
                if($image_id) { $no_image='kbx-section-img-present'; }else{ $no_image='kbx-section-img-blank'; } 
            ?>
                <div class="kbx-sticky-post kbx-category-box <?php echo $no_image; ?>" id="kbx-post-<?php echo get_the_ID(); ?>">
                    <div class="kbx-cat-box-inn" >
                        <div class="kbx-section-feature-image">
                            <a href="<?php echo get_permalink(get_the_ID()); ?>">
                                <?php 
                                if ($image_id) {
                                    echo wp_get_attachment_image($image_id, 'thumbnail');
                                } else {
                                    //echo '<img src="' . KBX_IMG_URL . '/defualt_section.png" alt="Defualt section">';
                                } ?>
                            </a>
                        </div>

                        <h3><a href="<?php echo get_permalink(get_the_ID()); ?>"><?php the_title(); ?></a></h3>

                        <p class="kbx-main-term-desc">  <?php the_excerpt(); ?></p>
                    <div class="article-count"></div>
                    </div>
                    <!-- /category -->
                </div>
                <!--                category-bok-->
            <?php array_push($excluded_posts, get_the_ID() ); } wp_reset_postdata(); } ?>
            <?php
            $grid_kb_args = array(
                'post_type' => 'kbx_knowledgebase',
                'post_status'=>'publish',
                'order' => $order,
                'posts_per_page' => $limit,
                'orderby' => $orderby,
              //  'meta_key' => $meta_key,
                'paged' => $paged,
                'post__not_in' => $excluded_posts
            );
            
            $taxArray = array();
            
            if( $section != "" )
            {
                $taxArray = array(
                    array(
                        'taxonomy' => 'kbx_category',
                        'field'    => 'term_id',
                        'terms'    => $section,
                    ),
                );
                
                $grid_kb_args = array_merge($grid_kb_args, array( 'tax_query' => $taxArray ));
                
            }
            $grid_query = new WP_Query( $grid_kb_args );
            if ($grid_query->have_posts()){ while ($grid_query->have_posts()){  $grid_query->the_post(); if(!is_sticky( get_the_ID() )){ $image_id=get_post_thumbnail_id(get_the_ID()); if($image_id) { $no_image='kbx-section-img-present'; }else{ $no_image='kbx-section-img-blank'; } ?>
                <div class="kbx-normal-post kbx-category-box <?php echo $no_image; ?>" id="kbx-post-<?php echo get_the_ID(); ?>">
                    <div class="kbx-cat-box-inn" >
                        <div class="kbx-section-feature-image">
                            <a href="<?php echo get_permalink(get_the_ID()); ?>">
                                <?php 
                                if ($image_id) {
                                    echo wp_get_attachment_image($image_id, 'thumbnail');
                                } else {
                                    //echo '<img src="' . KBX_IMG_URL . '/defualt_section.png" alt="Defualt section">';
                                } ?>
                            </a>
                        </div>

                        <h3><a href="<?php echo get_permalink(get_the_ID()); ?>"><?php the_title(); ?></a></h3>

                        <p class="kbx-main-term-desc">  <?php the_excerpt(); ?></p>
                        
                    </div>
                    <!-- /category -->
                </div>
                <!--                category-bok-->
            <?php } } wp_reset_postdata(); }/* End Grid Box View */ ?>
        </div>


        
</div>
       <?php if ($show_pagination == 'true') : ?>

<section class="kbx-pagination">

    <?php
    echo kbx_get_pagination_links($query->max_num_pages, "", $paged);
    ?>

</section>

<?php endif; ?>

<?php else : ?>

<p>
<?php esc_html_e('No articles found under this section.', 'kbx-qc'); ?>
</p>

<?php endif; ?>

</section>
