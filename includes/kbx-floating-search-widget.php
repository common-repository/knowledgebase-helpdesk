<?php
defined('ABSPATH') or die("You can't access this file directly.");

global $kbx_options;
$floating_search_bot = get_option('kbx_floating_search_bot');
if( !is_admin() ){

	if( $floating_search_bot == 'float' )
	{
		add_action('wp_footer', 'kbx_show_floating_search_widget');
	}

}

if ( ! function_exists( 'kbx_show_floating_search_widget' ) ) {
	function kbx_show_floating_search_widget(){

	    global $kbx_options;
	    $floating_search_placeholder = ( isset($kbx_options['kb_search_placeholder']) && $kbx_options['kb_search_placeholder'] != "" ) ? $kbx_options['kb_search_placeholder']: esc_html( 'Search the knowledge base', 'kbx-qc');
	    $floating_widget_placeholder = ( isset($kbx_options['kb_floating_widget_placeholder']) && $kbx_options['kb_floating_widget_placeholder'] != "") ? $kbx_options['kb_floating_widget_placeholder']: esc_html( 'Type your search string. Minimum 4 characters are required.', 'kbx-qc');

	    ?>
		<div class="kbx-fes-widget-wrapper">
			<div class="kbx-fes-widget-main">
				<div class="beacon-wrapper">
					<form class="search-form" id="ajax-fes-search-form">
						<div>
							<input class="kbx-fes-search-form-input" placeholder="<?php echo $floating_search_placeholder; ?>" autocomplete="off" offset="18" type="text">
						</div>
						<a href="#" class="kbx-fes-search-form-submit">
							<?php esc_html_e('Submit', 'kbx-qc') ?>
						</a>
						<div class="search-spinner hidden">
							<div class="double-bounce1">
							</div>
							<div class="double-bounce2">
							</div>
						</div>
					</form>
					<div class="kbx-fes-content kbx-fes-results" style="max-height:300px;">
						<div class="kbx-fes-search-results">
							<ul class="kbx-fes-search-results-ul"></ul>
						</div>
						<p class="search-empty hidden">
							<span><?php esc_html_e('No results found for ', 'kbx-qc') ?></span>
							"<span class="fes-search-terms"></span>"
						</p>
						<p class="kbx-fes-alert">
							<span>
								<?php echo $floating_widget_placeholder; ?>
							</span>
						</p>
					</div>
				</div>
			</div>
		</div>

		<div class="kbx-widget-trigger-wrapper">
			<button class="kbx-fes-trigger widget-trigger widget-trigger-search"><?php esc_html_e('Help', 'kbx-qc'); ?></button>
		</div>

		<?php
	}
}