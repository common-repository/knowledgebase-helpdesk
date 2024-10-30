<div class="kbx-glossary-container">
    <?php if ($show_search_form == 'true') : ?>

        <?php echo kbx_get_search_form(); ?>

    <?php endif; ?>

    <div id="kbx-glossary-head">
        <div class="kbx-glossary-keys">
            <?php echo get_glossary_links(); ?>

        </div>
    </div>

    <section class="kbx-articles">

        <div class="clear"></div>


        <div class="articleList" data-packery='{ "itemSelector": ".kbx-glossary-item"}'>
            <!-- <?php// if ($query->have_posts()) : ?>
                <?php //while ($query->have_posts()) : $query->the_post() ?>
                    <div class="kbx-glossary-item">
                        <h3><a href="<?php// the_permalink(); ?>">
                                <?php //the_title(); ?>
                            </a>
                        </h3>
                        <div>
                            <?php //the_excerpt(); ?>
                        </div>
                    </div>
                <?php// endwhile; wp_reset_postdata(); ?>

            <?php// else : ?>
                <p>
                    <?php //_e('No articles found under this section.', 'kbx-qc'); ?>
                </p>
            <?php //endif; ?> -->

        </div>
        <div class="clear"></div>
        <div class="kbx-glossary-loadmore-container" style="text-align: center">
            <?php if ($total_articles_num > $kbx_options['kbx_per_page']) { ?>
                <button class="kbx-glossary-load-btn" type="button" id="kbx-glossary-load-more"
                        data-offset="<?php echo $kbx_options['kbx_per_page']; ?>" data-gterm="all"><?php esc_html_e( 'Load More', 'kbx-qc') ?> <i
                            style="display: none"
                            class="fa fa-spinner fa-spin fa-fw" id="kbx-glossary-load-more-pre-loader"></i></button>
            <?php } ?>
        </div>
        <div class="clear"></div>

    </section>
</div>