<?php
defined('ABSPATH') or die("You can't access this file directly.");

/**
 * Register a custom help menu page.
 */
function kbxhd_add_help_menu_page(){

	$menu_slug = 'edit.php?post_type=kbx_knowledgebase';
	
	add_submenu_page(
        $menu_slug,
        __( 'Knowledgebase Help', 'kbx-qc' ),
        __( 'Shortcodes and Help', 'kbx-qc' ),
        'manage_options',
        'edit.php?post_type=kbx_knowledgebase&page=kbx-settings&tab=helps',
        'kbxhd_add_help_page_callaback_func'
    );
	
}
add_action( 'admin_menu', 'kbxhd_add_help_menu_page' );
 
/**
 * Display help page content
 */
if ( ! function_exists( 'kbxhd_add_help_page_callaback_func' ) ) {
function kbxhd_add_help_page_callaback_func() {
    
    ?>
	<div class="wrap">

        <div class="qcld-kbx-help-section">
            
    	<div id="icon-tools" class="icon32"></div>
        <h2><?php esc_html_e('Knowledgebase - Shortcodes and Help', 'kbx-qc'); ?></h2>

        

        <div class='qcld-kbx-section-block'>
            <h3 class="shortcode-section-title">
                <?php esc_html_e('Shortcodes:', 'kbx-qc'); ?>
            </h3>
            <p>
                <strong><?php esc_html_e('You need to use shortcodes to display article search page or glossary page. Copy the below shortcodes as per your requirement and put in your post or page.', 'kbx-qc'); ?></strong>
            </p>
            <p>
                <strong>[kbx-knowledgebase]: </strong> <?php esc_html_e('for article search page with category tiles.', 'kbx-qc'); ?>
            </p>
            <p>
                <strong>[kbx-knowledgebase-glossary]: </strong> <?php esc_html_e('for glossary page. For glossary to work, you need to add the relevant Glossary letter at the bottom of each article', 'kbx-qc'); ?>
            </p>
            <img src="<?php echo KBX_IMG_URL?>/glossary.jpg">
        </div>

        <div class='qcld-kbx-section-block'>
            <h3 class="shortcode-section-title">
                <?php esc_html_e('Settings:', 'kbx-qc'); ?>
            </h3>
            <p>
                <?php esc_html_e('Settings options are self explanatory. These can be found under', 'kbx-qc'); ?> <strong><?php esc_attr_e('"Knowledgebase --> Settings"', 'kbx-qc'); ?></strong>.
            </p>
        </div>

        <div class='qcld-kbx-section-block'>
            <h3 class="shortcode-section-title">
                <?php esc_html_e('Widgets:', 'kbx-qc'); ?>
            </h3>
            <p>
                <?php esc_html_e('Widgets can be found under', 'kbx-qc'); ?> <strong><?php esc_attr_e('"Appearence --> Widgets"', 'kbx-qc'); ?></strong>.
            </p>
            <p>
                <?php esc_html_e('There are two avilable widgets. These are:', 'kbx-qc'); ?>
                <ol>
                    <li><?php esc_html_e('Knowledgebase Articles', 'kbx-qc'); ?> <strong> <?php esc_attr_e('[Sorting Options: Date, Popularity, Views]', 'kbx-qc'); ?></strong></li>
                    <li><?php esc_html_e('Knowledgebase Tag Cloud', 'kbx-qc'); ?></li>
                </ol>
            </p>
        </div>

        <div class='qcld-kbx-section-block'>
            <h3 class="shortcode-section-title">
                <?php esc_html_e('Floating Search Widget:', 'kbx-qc'); ?>
            </h3>
            <p><?php esc_html_e('You can enable this setting from the Bot Settings Page.', 'kbx-qc'); ?>
            </p>
        </div>

        <div class="qcld-kbx-section-block">
										<div>
											<p>
												<h3 class="shortcode-section-title"><?php esc_html_e('Custom Templating', 'kbx-qc'); ?>:</h3>
											</p>
											<p>
												<?php esc_html_e('To design custom template for Archive,Articles Section, Article Search  & Article Detail page, you have to put kbhd  folder into your active theme root folder. Where', 'kbx-qc'); ?>
											</p>
											<ol>
												<li> <?php esc_attr_e('archive-kbx_knowledgebase.php', 'kbx-qc'); ?>  <?php esc_html_e('is for Archive page ', 'kbx-qc'); ?> </li>
												<li> <?php esc_attr_e('taxonomy-kbx_category.php', 'kbx-qc'); ?> <?php esc_html_e('is for Articles Section or Articles Category  page ', 'kbx-qc'); ?>  </li>
												<li> <?php esc_attr_e('search-kbx_knowledgebase.php', 'kbx-qc'); ?> <?php esc_html_e('is for Article Search page ', 'kbx-qc'); ?> </li>
												<li> <?php esc_attr_e('single-kbx_knowledgebase.php', 'kbx-qc'); ?> <?php esc_html_e(' is for Article Detail page ', 'kbx-qc'); ?> </li>
											</ol>
											<p><?php esc_html_e(' After placing template files into root directory of your active theme folder, now you can write custom style (CSS)  in your style.css file which will be implemented in Knowledgebase templates.', 'kbx-qc'); ?> </p>
											<p><?php esc_html_e(' Warning :  Don\'t rename kbhd  folder name.', 'kbx-qc'); ?> </p>
										</div>
									</div>

        </div>

    </div>
    <?php 
}
}
