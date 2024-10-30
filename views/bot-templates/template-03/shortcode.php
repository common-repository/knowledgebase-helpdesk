<div id="kbx-bot-shortcode-template-container" class="kbx-bot-shortcode-template-container kbx-bot-shortcode-template-03">

    <div class="kbx-bot-shortcode-row">

        <div class="kbx-bot-container">
            <div class="kbx-bot-header">
                <h3> <?php if (get_option('kbx_bot_host') != '') {
                        $welcomes = maybe_unserialize(get_option('kbx_bot_welcome'));
                        echo $welcomes[0] . ' ' . get_option('kbx_bot_host');
                    } ?></h3>
            </div>
            <!--kbx-bot-header-->
            <div class="kbx-bot-ball-inner  kbx-bot-content">
                <div class="kbx-bot-messages-wrapper">
                    <ul id="kbx-bot-messages-container" class="kbx-bot-messages-container">
                    </ul>
                </div>
                <!--kbx-bot-messages-wrapper-->
            </div>
            <!--kbx-bot-ball-inner-->
            <div class="kbx-bot-footer">
                <div id="kbx-bot-editor-area" class="kbx-bot-editor-area">
                    <input id="kbx-bot-editor" class="kbx-bot-editor" required="" placeholder="<?php esc_html_e('Send a message.', 'kbx-qc'); ?>"
                           maxlength="100">
                    <button type="button" id="kbx-bot-send-message" class="kbx-bot-button"><?php esc_html_e('send', 'kbx-qc'); ?></button>
                </div>
                <!--kbx-bot-editor-container-->
            </div>
            <!--kbx-bot-footer-->
        </div>
        <!--kbx-bot-container-->

    </div>
    <!--    kbx-bot-shortcode-row-->

<!--kbx-bot-ball-container-->