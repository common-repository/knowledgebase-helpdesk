<?php
defined('ABSPATH') or die("You can't access this file directly.");

global $kbx_options;

if( !is_admin() ){

	if( isset($kbx_options['enable_question_widget']) && $kbx_options['enable_question_widget'] == '1' )
	{
		add_action('wp_footer', 'kbx_enable_question_widget_func');
	}

}


if ( ! function_exists( 'kbx_enable_question_widget_func' ) ) {
	function kbx_enable_question_widget_func() {
		?>

		  <!-- Modal HTML embedded directly into document -->
		  <div id="kbx-aq-modal" style="display:none;">
		    <div class="kbx-aq-form">
		    	<form action="">
		    		<div class="kbx-form-group">
		    			<label for=""><?php esc_html_e( 'Question Title', 'kbx-qc' ); ?></label>
		    			<input type="text" name="kbx-aq-title" id="kbx-aq-title" class="kbx-form-control">
		    		</div>
		    		<div class="kbx-form-group">
		    			<button type="submit" class="kbx-btn kbx-btn-default"><?php esc_html_e( 'Submit', 'kbx-qc' ); ?></button>
		    		</div>
		    	</form>
		    </div>
		  </div>

		  <!-- Link to open the modal -->
		  <p>
		  	<a href="#kbx-aq-modal"><?php esc_html_e( 'Ask Question', 'kbx-qc' ); ?></a>
		  </p>

		<?php
	}
}