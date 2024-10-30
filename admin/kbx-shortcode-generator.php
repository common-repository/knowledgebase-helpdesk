<?php
defined('ABSPATH') or die("You can't access this file directly.");

/*******************************
 * Shortcode Generator
 * For Knowledgebase
 *******************************/
if ( ! function_exists( 'kbx_tinymce_shortcode_button_function_cmn' ) ) {
    function kbx_tinymce_shortcode_button_function_cmn(){

        add_filter("mce_external_plugins", "kbx_shortcode_generator_btn_js_cmn");
        add_filter("mce_buttons", "kbx_shortcode_generator_btn_cmn");

    }
    add_action('init', 'kbx_tinymce_shortcode_button_function_cmn');
}

if ( ! function_exists( 'kbx_shortcode_generator_btn_js_cmn' ) ) {
    function kbx_shortcode_generator_btn_js_cmn($plugin_array){

        $plugin_array['kbx_shortcode_cmn'] = KBX_ASSETS_URL . '/js/kbx-tinymce-button.js';
        //$plugin_array['kbx_shortcode_cmn'] = plugins_url('assets/js/kbx-tinymce-button.js', __FILE__);
        return $plugin_array;

    }
}

if ( ! function_exists( 'kbx_shortcode_generator_btn_cmn' ) ) {
    function kbx_shortcode_generator_btn_cmn($buttons){

        array_push($buttons, 'kbx_shortcode_cmn');
        return $buttons;

    }
}


if ( ! function_exists( 'kbx_load_custom_wp_admin_style_cmn' ) ) {
    function kbx_load_custom_wp_admin_style_cmn(){

        wp_register_style('kbx_shortcode_gerator_css_cmn', KBX_ASSETS_URL . '/css/shortcode-generator-modal.css', false, '1.0.0');
        wp_enqueue_style('kbx_shortcode_gerator_css_cmn');

    }
    add_action('admin_enqueue_scripts', 'kbx_load_custom_wp_admin_style_cmn');
}


if ( ! function_exists( 'kbx_render_shortcode_modal_cmn' ) ) {
    function kbx_render_shortcode_modal_cmn(){

        global $kbx_options;
        $limit = $kbx_options['kbx_per_page'];
        ?>

        <div id="sm-modal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
    		
    			<span class="close">
    				<span class="dashicons dashicons-no"></span>
    			</span>
                <h3 class="kbx-shortcode-gn-heading">
                    <?php esc_html_e('Knowledgebase Helpdesk: Shortcode Generator', 'kbx-qc'); ?></h3>


                <div class="sm_shortcode_list">
                    <div class="kbx_single_field_shortcode">
                        <label style="width: 200px;display: inline-block;"><?php esc_html_e('Shortcode for', 'kbx-qc'); ?></label>
                        <select style="width: 225px;" id="kbx_mode">
                            <option value=""><?php esc_html_e('Please Select Option', 'kbx-qc'); ?></option>
                            <option value="kbx-knowledgebase"><?php esc_html_e('Knowledgebase Main', 'kbx-qc'); ?></option>
                            <option value="kbx-knowledgebase-articles"><?php esc_html_e('Knowledgebase Articles', 'kbx-qc'); ?></option>
                            <option value="kbx-faq"><?php esc_html_e('Knowledgebase FAQ', 'kbx-qc'); ?></option>
                            <option value="kbx-knowledgebase-glossary"><?php esc_html_e('Knowledgebase Glossary', 'kbx-qc'); ?></option>
                            <option disabled="disabled" class="kbx_disabled_input" value="kbxbot"><?php esc_html_e('Knowledgebase Chatbot', 'kbx-qc'); ?></option>
                        </select>
                    </div>

                    <div id="kbx-common-shortcode-options" style="display: none;">
                        <?php
                        $terms = get_terms('kbx_category', array(
                            'hide_empty' => true,
                        ));
                        ?>

                        <div id="field_showcase_sections" class="kbx_single_field_shortcode">
                            <label style="width: 200px;display: inline-block;">
                                <?php esc_html_e('Showcase Style', 'kbx-qc'); ?>
                            </label>
                            <select style="width: 225px;" id="kbx_shortcode_sections">
                                <option value=""><?php esc_html_e('All Section', 'kbx-qc'); ?></option>
                                <?php foreach ($terms as $term) : ?>
                                    <option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div id="content_showcase_type" class="kbx_single_field_shortcode">
                            <label style="width: 200px;display: inline-block;">
                                <?php esc_html_e('Content Type', 'kbx-qc'); ?>
                            </label>
                            <select style="width: 225px;" id="kbx_content_type_sections">
                                <option value="full-content"><?php esc_html_e('Full Content', 'kbx-qc'); ?></option>
                                <option value="short-excerpt"><?php esc_html_e('Short Excerpt', 'kbx-qc'); ?></option>
                            </select>
                        </div>
                        <div id="field_showcase_search" class="kbx_single_field_shortcode">
                            <label style="width: 200px;display: inline-block;">
                                <?php esc_html_e('Show Search Bar', 'kbx-qc'); ?>
                            </label>
                            <input type="radio" id="kbx_search_bar_true" value="true" name="kbx_search_bar" checked> <label
                                    for="kbx_search_bar_true"><?php esc_html_e('Yes', 'kbx-qc'); ?></label>
                            <input type="radio" id="kbx_search_bar_false" value="false" name="kbx_search_bar"> <label
                                    for="kbx_search_bar_false"><?php esc_html_e('No', 'kbx-qc'); ?></label>

                        </div>
                        <div id="field_showcase_pagination" class="kbx_single_field_shortcode">
                            <label style="width: 200px;display: inline-block;">
                                <?php esc_html_e('Show Pagination', 'kbx-qc'); ?>
                            </label>
                            <input type="radio" id="kbx_pagination_true" value="true" name="kbx_pagination" checked>
                            <label for="kbx_pagination_true"><?php esc_html_e('Yes', 'kbx-qc'); ?></label>

                            <input type="radio" id="kbx_pagination_false" value="false" name="kbx_pagination">
                            <label for="kbx_pagination_false"><?php esc_html_e('No', 'kbx-qc'); ?></label>

                        </div>
                        <div class="kbx_single_field_shortcode" id="field_showcase_orderby">
                            <label style="width: 200px;display: inline-block;">
                                <?php esc_html_e('Order By', 'kbx-qc'); ?>
                            </label>
                            <select style="width: 225px;" id="kbx_orderby">
                                <option value="date"><?php esc_html_e('Date', 'kbx-qc'); ?></option>
                                <option value="ID"><?php esc_html_e('ID', 'kbx-qc'); ?></option>
                                <option value="title"><?php esc_html_e('Title', 'kbx-qc'); ?></option>
                                <option value="modified"><?php esc_html_e('Date Modified', 'kbx-qc'); ?></option>
                                <option value="rand"><?php esc_html_e('Random', 'kbx-qc'); ?></option>
                                <option value="menu_order"><?php esc_html_e('Menu Order', 'kbx-qc'); ?></option>
                            </select>
                        </div>

                        <div class="kbx_single_field_shortcode">
                            <label style="width: 200px;display: inline-block;">
                                <?php esc_html_e('Order', 'kbx-qc'); ?>
                            </label>
                            <select style="width: 225px;" id="kbx_order">
                                <option value="ASC"><?php esc_html_e('Ascending', 'kbx-qc'); ?></option>
                                <option value="DESC"><?php esc_html_e('Descending', 'kbx-qc'); ?></option>
                            </select>
                        </div>

                        <div class="kbx_single_field_shortcode" id="field_showcase_limit">
                            <label style="width: 200px;display: inline-block;">
                                <?php esc_html_e('Limit', 'kbx-qc'); ?>
                            </label>
                            <input type="number" id="kbx_limit" value="<?php echo $limit; ?>" name="kbx_showcase_limit">
                            <p>
                                <?php esc_html_e('Numric Limit: e.g. 10', 'kbx-qc'); ?>
                            </p>
                        </div>
                    </div>

                    <div class="kbx_single_field_shortcode">
                        <label style="width: 200px;display: inline-block;">
                        </label>
                        <input class="sld-sc-btn" type="button" id="kbx_add_shortcode_cmn" value="Add Shortcode"/>
                    </div>


                </div>
            </div>

        </div>
        <?php
        exit;
    }

    add_action('wp_ajax_show_kbx_shortcode_cmn', 'kbx_render_shortcode_modal_cmn');

}