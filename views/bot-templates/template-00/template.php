<div id="kbx-bot-ball-container" class="kbx-bot-template-01">
    <div class="kbx-bot-container">


        <div id="kbx-bot-board-container" class="kbx-bot-board-container">
            <div class="kbx-bot-header">
                <h3> <?php if (get_option('kbx_bot_host') != '') {
					   $welcomes=maybe_unserialize(get_option('kbx_bot_welcome'));
                        echo stripslashes($welcomes[0]).' '.get_option('kbx_bot_host');
                    } ?></h3>
            </div>
            <!--kbx-bot-header-->
            <div class="kbx-bot-ball-inner kbx-bot-content">
                <div class="kbx-bot-messages-wrapper">
                    <ul id="kbx-bot-messages-container" class="kbx-bot-messages-container">
                    </ul>
                </div>
            </div>
            <div class="kbx-bot-footer">
                <div id="kbx-bot-editor-container" class="kbx-bot-editor-container">
                    <input id="kbx-bot-editor" class="kbx-bot-editor" required placeholder="<?php esc_html_e('Send a message.', 'kbx-qc'); ?>"
                           maxlength="100">
                    <button type="button" id="kbx-bot-send-message" class="kbx-bot-button"><?php esc_html_e('send', 'kbx-qc'); ?></button>
                </div>
                <!--kbx-bot-editor-container-->
                <div class="kbx-bot-tab-nav">
                    <ul>
                        <li><a class="kbx-bot-operation-option" data-option="help" href=""></a></li>
                        <li class="kbx-bot-operation-active"><a class="kbx-bot-operation-option" data-option="chat" href=""></a></li>
                        <li><a class="kbx-bot-operation-option" data-option="support"  href=""></a></li>
                    </ul>
                </div>
                <!--kbx-bot-tab-nav-->

            </div>
            <!--kbx-bot-footer-->
        </div>
        <!--        kbx-bot-board-container-->
    </div>
</div>