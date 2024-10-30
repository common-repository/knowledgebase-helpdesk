<?php
defined('ABSPATH') or die("You can't access this file directly.");

if ( ! function_exists( 'kbxhd_bot_admin_ui' ) ) {
    function kbxhd_bot_admin_ui(){
        $action="edit.php?post_type=kbx_knowledgebase&page=kbx-bot";

       // var_dump(get_option('kbx_floating_search_bot'));wp_die();
        ?>
        <div class="kbx-bot-wrap wrap">
            <div class="icon32"><br></div>
            <form action="<?php echo esc_attr($action); ?>" method="POST" id="kbx-bot-admin-form" enctype="multipart/form-data">
                <div class="form-container">
                    <h3><?php esc_html_e('HelpDesk Bot Control Panel', 'kbx-qc'); ?></h3>
                    <section class="kbx-bot-tab-container-inner">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <p class="qc-opt-title-font"><b><?php esc_html_e('Floating Articles Searching and Bot Option ', 'kbx-qc'); ?></b></p>
                                    <?php
                                        
                                     $floating_search_bot = get_option('kbx_floating_search_bot');
                                      
                                    ?>
                                    <div id="bot_float_select">
                                      <input type="radio" id="float" name="kbx_floating_search_bot" value="float" <?php if ($floating_search_bot == 'float') { echo "checked";}?>/>
                                      <label for="float"><?php esc_html_e('Floating Search Widget', 'kbx-qc'); ?></label><br>
                                      <input type="radio" id="bot" name="kbx_floating_search_bot" value="wp-boat" <?php if ($floating_search_bot == 'wp-boat') { echo "checked";}?>/>
                                      <label for="bot"><?php esc_html_e('KBx Bot', 'kbx-qc'); ?></label><br>
                                      <input type="radio" id="bot-null" name="kbx_floating_search_bot" value="" <?php if ($floating_search_bot == '') { echo "checked";}?>/>
                                      <label for="bot-null"><?php esc_html_e('Disable KBx Bot and Floating Search Widget', 'kbx-qc'); ?></label><br>
                                    </div>
                               
                            </div>
                        </div>
                    </div>
                    </section>
                   <section class="kbx-bot-tab-container-inner d-none" id="kbx-floating-search-settings-container">
                        <div class="row">
                            <div class="col-xs-9 col-xs-offset-3">
                                <p class="qc-opt-title-font"> <?php esc_html_e('Disable Floating Search Widget', 'kbx-qc'); ?> </p>
                                <div class="cxsc-settings-blocks">
                                    <input value="1" id="kbx_floating_search_on" type="checkbox"
                                           name="kbx_floating_search_on" <?php echo esc_attr('checked'); ?>>
                                    <label for="kbx_floating_search_on"><?php esc_html_e('Enable Floating Search Widget for flexible searching in the frontend.', 'kbx-qc'); ?> </label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <br>
                    </section>
                    <section class="kbx-bot-tab-container-inner">
                        <div class="row">
                          <!--   <div class="text-left col-sm-3 col-sm-offset-3">
                                <input type="button" class="btn btn-warning submit-button"
                                       id="kbx-bot-reset-option"
                                       value="<?php //_e('Reset all options to Default', 'kbx_bot'); ?>"/>
                            </div> -->
                            <div class="text-right col-sm-6">
                                <input type="submit" class="btn btn-primary submit-button" name="submit"
                                       id="submit" value="<?php esc_html_e('Save Settings', 'kbx_bot'); ?>"/>
                            </div>
                        </div>
                    </section>
                </div>


                <?php wp_nonce_field('kbx_bot'); ?>
            </form>


        </div>
    <?php
    }
}

if ( ! function_exists( 'kbx_bot_dynamic_multi_option' ) ) {
    function kbx_bot_dynamic_multi_option($options, $option_name, $option_text){
        ?>
        <p class="qc-opt-title-font"><?php echo $option_text; ?> </p>
        <div class="kbx-bot-lng-items">
            <?php
            if (is_array($options) && count($options) > 0) {
                foreach ($options as $key => $value) {
                    ?>
                    <div class="row" class="kbx-bot-lng-item">
                        <div class="col-xs-10">
                            <input type="text"
                                   class="form-control qc-opt-dcs-font"
                                   name="<?php echo $option_name; ?>[]"
                                   value="<?php echo esc_attr(stripslashes($value)); ?>">
                        </div>
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-danger btn-sm kbx-bot-lng-item-remove"> <span class="glyphicon glyphicon-remove"></span> </button>
                        </div>
                    </div>
                    <?php
                }
            } else { ?>
                <div class="row" class="kbx-bot-lng-item">
                    <div class="col-xs-10">
                        <input type="text"
                               class="form-control qc-opt-dcs-font"
                               name="<?php echo $option_name; ?>[]"
                               value="<?php echo $option_text; ?>">
                    </div>
                    <div class="col-xs-2">
                        <span class="kbx-bot-lng-item-remove">X</span>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="row">
            <div class="col-sm-2 col-sm-offset-10">
                <button type="button" class="btn btn-success btn-sm kbx-bot-lng-item-add"> <span class="glyphicon glyphicon-plus"></span> </button>
            </div>
        </div>

        <?php
    }
}