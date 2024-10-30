<!--Adding Template Specific Style -->

<div class="kb-front-main-div">

    <?php if ($show_search_form == 'true') : ?>

        <?php echo kbx_get_search_form(); ?>

    <?php endif; ?>
    <?php
    global $kbx_options;

    ?>

    <section id="contentArea" class="container-fluid">
        <?php if(isset($kbx_options['enable_section_heading']) && $kbx_options['enable_section_heading']==true){ ?>
            <div class="kbx-section-heading">
                <div class="decor-before-kbx-holder"><span></span></div>
                <h2><?php if(isset($kbx_options['section_heading'])){echo $kbx_options['section_heading']; }else{echo "Browse the KnowledgeBase";} ?></h2>
                <div class="decor-after-kbx-holder"><span></span></div>
            </div>
        <?php }?>
        <?php if ($show_section_box == 'true') : ?>

            <!-- Site has only one collection -->
            <section class="kbx-category-list" data-packery='{ "itemSelector": ".kbx-category-box"}'>

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

                <?php foreach ($terms as $term) : if( kbx_article_user_permit($term->term_id) ): $image_id = get_term_meta($term->term_id, 'category-image-id', true); $no_image=''; if($image_id) { $no_image='kbx-section-img-present'; }else{ $no_image='kbx-section-img-blank'; } ?>
                    <div class="kbx-category-box <?php echo $no_image; ?>" id="category-<?php echo $term->term_id; ?>">
                    <div class="kbx-category-inn">
                        <a class="" href="<?php echo get_term_link($term->term_id); ?>">
                            <div class="kbx-section-feature-image">
                                <?php 
                                if ($image_id) {
                                    echo wp_get_attachment_image($image_id, 'thumbnail');
                                } else {
                                    //echo '<img src="' . KBX_IMG_URL . '/defualt_section.png" alt="Defualt section">';
                                } ?>
                            </div>

                            <h3><?php echo $term->name; ?>
                        
                          
                        
                        </h3>
                        <p><?php echo $term->description; ?></p>
                        </a>
                        <?php 

                        //Sticky articles list query
                        $kb_list_args = array(
                            'post_type'      => 'kbx_knowledgebase',
                            'post_status'    => 'publish',
                            'posts_per_page' => 4,
                            'order'          => 'date',
                            'orderby'        => 'DESC',
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'kbx_category',
                                    'field'    => 'term_id',
                                    'terms'    => $term->term_id,
                                ),
                            )
                        );
                        $kb_list_query = new WP_Query( $kb_list_args );

                        ?>
                        <?php if ($kb_list_query->have_posts()) : ?>
                        <ul class="kbx-latest-cat-post">
                            <?php while ($kb_list_query->have_posts()) : $kb_list_query->the_post() ?>
                            <li>
                                <a href="<?php the_permalink(); ?>">
                                    <i class="fa fa-bookmark"></i>
                                    <span>
                                <?php the_title(); ?>
                            </span>
                                </a>
                            </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                        <?php endif; ?>
                        <a href="<?php echo get_term_link($term->term_id); ?>" class="qckbx-readmore"><?php esc_html_e('Read more'); ?> <i class="fa fa-long-arrow-right"></i></a>

                        <?php $kbxhd_get_kb_list = kbxhd_get_kb_list_sub_child_categories($term->term_id); 

                        if( isset( $kbxhd_get_kb_list ) && !empty( $kbxhd_get_kb_list )){

                        ?>

                        <div class="kbx-subcat-menu-list">
                            <div class="kbx-subcat-accordion">
                                <div class="kbx-subcat-accordion-item">
                                    <input type="checkbox" id="<?php echo $term->name; ?>">
                                    <label for="<?php echo $term->name; ?>" class="kbx-subcat-accordion-item-title"><span class="kbx-subcat-icon"></span><?php // esc_html_e('View All Sub Category'); ?>
                                
                                    <?php if(isset($kbx_options['kbx_all_categories'])){echo $kbx_options['kbx_all_categories']; }else{echo "View All Categories";} ?>
                                
                                </label>
                                    <div class="kbx-subcat-accordion-item-desc">

                                        <div class="kbx-list-subcat-menu-wrap">
                                        <?php  echo kbxhd_get_kb_list_sub_child_categories($term->term_id); ?>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                       <?php  } ?>

                       </div>

                        <!-- /category -->
                    </div>
                    <!-- category-bok-->


                <?php endif; endforeach; ?>

            </section>
            <!-- /kbx-category-list -->

        <?php endif; ?>

    </section>

</div>