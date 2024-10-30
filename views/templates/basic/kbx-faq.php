<section id="contentAreafaq" class="container-fluid">
<section class="kbx-articles">
    <?php if( $show_search_form == 'true' ) : ?>

        <?php echo kbx_get_search_form();
            global $kbx_options;
            if( isset($_GET['sort'])){
                $sort_option=$_GET['sort'];
            }else{
                $sort_option=$kbx_options['sorting_option'];
            }
        ?>

    <?php endif; ?>

    <div id="categoryHead">
        <div class="sort">
            <form action="" method="GET">

                <select name="sort" id="sortBy" title="<?php esc_html_e('Sort By', 'kbx-qc'); ?>" onchange="this.form.submit();">
                    <option value="date" <?php if($sort_option=='date'){echo "selected"; }?> ><?php esc_html_e('Sort by Default', 'kbx-qc'); ?></option>
                    <option value="name" <?php if($sort_option=='name'){echo "selected"; }?>><?php esc_html_e('Sort A-Z', 'kbx-qc'); ?></option>
                    <option value="views" <?php if($sort_option=='views'){echo "selected"; }?>><?php esc_html_e('Sort by Views', 'kbx-qc'); ?></option>
                </select>

            </form>
        </div>
    </div>
    <br>
    <br>
    <br>

    <div style="clear: both !important;"></div>

    <?php if( $query->have_posts() ) : ?>
        <ul class="kbx-faq-list" >
            <?php while( $query->have_posts() ) : $query->the_post() ?>
                <li itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <div class="kbx-faq-title" itemprop="name" ><?php the_title(); ?></div>
                    <div itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <?php if( $content_type == 'full-content' ){ ?>
                            <div class="kbx-faq-content" itemprop="text"><?php the_content(); ?></div>
                        <?php }else{ ?>
                            <div class="kbx-faq-content" itemprop="text"><?php echo kbx_excerpts(); ?></div>
                        <?php } ?>
                    </div>

                </li>
            <?php endwhile;?>
        </ul>
    <?php if( $show_pagination == 'true' ) : ?>
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
</div>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('html').attr('itemscope', '');
        jQuery('html').attr('itemtype', "https://schema.org/FAQPage");
    });
</script>