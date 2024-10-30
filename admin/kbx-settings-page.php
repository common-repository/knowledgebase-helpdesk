<?php
defined('ABSPATH') or die("You can't access this file directly.");
/**
 * Renders the settings page.
 * Portions of this code have been inspired by Easy Digital Downloads, WordPress Settings Sandbox, etc.
 *
 */


/**
 * Render the settings page.
 *
 * @return void
 */
if ( ! function_exists( 'kbx_options_page' ) ) {
	function kbx_options_page() {
		
		$active_tab = isset( $_GET['tab'] ) && array_key_exists( sanitize_key( wp_unslash( $_GET['tab'] ) ), kbx_get_settings_sections() ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general'; // Input var okay.

		ob_start();
		add_action('admin_enqueue_scripts', 'kbx_enqueue_media_script');

		add_action('admin_footer', 'kbx_image_uploader_script');

		?>
	<div class="qcld-kbx-wrap">
		<div class="wrap">
			
			<h3><?php _e( 'Knowledgebase Settings', 'kbx-qc' ); // WPCS: XSS OK. ?></h3>
	        <div class="kbx-parmalink-404-notice">
	            <h3> <strong style="color:orange"> Notice : </strong> <?php _e( 'If you get a 404 page for articles, please go to ', 'kbx-qc' );?> <a href="<?php echo  admin_url();?>options-permalink.php"><?php _e( 'WordPress settings->Permalinks ', 'kbx-qc' );?></a> <?php _e( ' and save the settings again without making any changes. ', 'kbx-qc' );?> You can now add images for the <a href="<?php echo admin_url('edit-tags.php?taxonomy=kbx_category&post_type=kbx_knowledgebase'); ?>">KnowledgeBase sections.</a></h3>
	        </div>

			<?php settings_errors(); ?>

			<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-3">
			<div id="post-body-content">

				<div class="nav-tab-wrapper" style="padding:0">
					<?php
					foreach ( kbx_get_settings_sections() as $tab_id => $tab_name ) {

						$tab_url = esc_url( add_query_arg(
							array(
							'settings-updated' => false,
							'tab' => $tab_id,
							)
						) );

						$active = $active_tab === $tab_id ? ' nav-tab-active' : '';

						echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab ' . sanitize_html_class( $active ) . '">';
									echo esc_html( $tab_name );
						echo '</a>';

					}
					?>
				</div>

				<div id="tab_container">
					<form method="post" action="options.php">
						<table class="form-table">
						<?php
							settings_fields( 'kbx_settings' );
							do_settings_fields( 'kbx_settings_' . $active_tab, 'kbx_settings_' . $active_tab );

						?>
						</table>
						<?php 
						if( isset($active_tab) && $active_tab == 'helps'){
						?>
					
								<div class="qcld-kbx-help-section">
									
								<div id="icon-tools" class="icon32"></div>
		
								<div class='qcld-kbx-section-block'>
									<h3 class="shortcode-section-title">
										<?php esc_html_e('Shortcodes:', 'kbx-qc'); ?>
									</h3>
									<p>
										<strong><?php esc_html_e('You need to use shortcodes to display article search page or glossary page. Copy the below shortcodes as per your requirement and put in your post or page.', 'kbx-qc'); ?></strong>
									</p>
									<p>
									<code><strong>[kbx-knowledgebase]<strong></code>: </strong> <?php esc_html_e('for article search page with category tiles.', 'kbx-qc'); ?>
									</p>
									<p>
									<code><strong>[kbx-knowledgebase-glossary]</strong></code>: </strong> <?php esc_html_e('for glossary page. For glossary to work, you need to add the relevant Glossary letter at the bottom of each article', 'kbx-qc'); ?>
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
									</div>v

								</div>

	

						<?php } ?>
						<p>
						<?php
							// Default submit button.
							submit_button(
								__( 'Submit', 'kbx-qc' ),
								'primary',
								'submit',
								false
							);

							echo '&nbsp;&nbsp;';

							// Reset button.
							$confirm = esc_js( __( 'Do you really want to reset all these settings to their default values?', 'kbx-qc' ) );
							submit_button(
								__( 'Reset', 'kbx-qc' ),
								'secondary',
								'settings_reset',
								false,
								array(
									'onclick' => "return confirm('{$confirm}');",
								)
							);
						?>
						</p>
					</form>
				</div><!-- /#tab_container-->

			</div><!-- /#post-body-content -->

			<div id="postbox-container-1" class="postbox-container">

				<div id="side-sortables" class="meta-box-sortables ui-sortable">
					<?php //include_once( 'sidebar.php' ); ?>
				</div><!-- /#side-sortables -->

			</div><!-- /#postbox-container-1 -->
			</div><!-- /#post-body -->
			<br class="clear" />
			</div><!-- /#poststuff -->

		</div><!-- /.wrap -->
		</div>
		<?php
		echo ob_get_clean(); // WPCS: XSS OK.
	}
}

/**
 * Array containing the settings' sections.
 *
 * @return array Settings array
 */
if ( ! function_exists( 'kbx_get_settings_sections' ) ) {
	function kbx_get_settings_sections() {
		$kbx_settings_sections = array(
			'general' => __( 'General', 'kbx-qc' ),
			'styles' => __( 'Styles', 'kbx-qc' ),
			'others' => __( 'Others', 'kbx-qc' ),
			'helps' => __( 'Shortcodes and Help', 'kbx-qc' ),
		);

		/**
		 * Filter the array containing the settings' sections.
		 *
		 * @param array $kbx_settings_sections Settings array
		 */
		return apply_filters( 'kbx_settings_sections', $kbx_settings_sections );

	}
}


/**
 * Miscellaneous callback funcion
 *
 * @param array $args Arguments passed by the setting.
 * @return void
 */
if ( ! function_exists( 'kbx_missing_callback' ) ) {
	function kbx_missing_callback( $args ) {
		printf( esc_html__( 'The callback function used for the <strong>%s</strong> setting is missing.', 'kbx-qc' ), esc_html( $args['id'] ) );
	}
}


/**
 * Header Callback
 *
 * Renders the header.
 *
 * @param array $args Arguments passed by the setting.
 * @return void
 */
if ( ! function_exists( 'kbx_header_callback' ) ) {
	function kbx_header_callback( $args ) {

		/**
		 * After Settings Output filter
		 * @param string $html HTML string.
		 * @param array Arguments array.
		 */
		echo apply_filters( 'kbx_after_setting_output', '', $args ); // WPCS: XSS OK.
	}
}


/**
 * Display text fields.
 *
 * @param array $args Array of arguments.
 * @return void
 */
if ( ! function_exists( 'kbx_text_callback' ) ) {
	function kbx_text_callback( $args ) {

		// First, we read the options collection.
		global $kbx_options;

		if ( isset( $kbx_options[ $args['id'] ] ) ) {
			$value = $kbx_options[ $args['id'] ];
		} else {
			$value = isset( $args['options'] ) ? $args['options'] : '';
		}

		$html = '<input type="text" id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '" style="width:100%" />';
		$html .= '<p class="description">' . $args['desc'] . '</p>';

		/** This filter has been defined in settings-page.php */
		echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
	}
}


/**
 * Display textarea.
 *
 * @param array $args Array of arguments.
 * @return void
 */
if ( ! function_exists( 'kbx_textarea_callback' ) ) {
	function kbx_textarea_callback( $args ) {

		// First, we read the options collection.
		global $kbx_options;

		if ( isset( $kbx_options[ $args['id'] ] ) ) {
			$value = $kbx_options[ $args['id'] ];
		} else {
			$value = isset( $args['options'] ) ? $args['options'] : '';
		}

		$html = '<textarea class="large-text" cols="50" rows="5" id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']">' . esc_textarea( stripslashes( $value ) ) . '</textarea>';
		$html .= '<p class="description">' . $args['desc'] . '</p>';

		/** This filter has been defined in settings-page.php */
		echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
	}
}


/**
 * Display checboxes.
 *
 * @param array $args Array of arguments.
 * @return void
 */
if ( ! function_exists( 'kbx_checkbox_callback' ) ) {
	function kbx_checkbox_callback( $args ) {

		// First, we read the options collection.
		global $kbx_options;

		$checked = isset( $kbx_options[ $args['id'] ] ) ? checked( 1, $kbx_options[ $args['id'] ], false ) : '';

		$disabled = '';
		if( isset( $args['disabled'] ) && $args['disabled'] == true ){
			$disabled = 'disabled="disabled" class="kbx_disabled_input"';
		}

		$html = '<input type="checkbox" '.$disabled.' id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']" value="1" ' . $checked . '/>';
		if( isset($args['side_note']) ){
			$html .= $args['side_note'];
		}
		$html .= '<p class="description">' . $args['desc'] . '</p>';

		/** This filter has been defined in settings-page.php */
		echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
	}
}


/**
 * Multicheck Callback
 *
 * Renders multiple checkboxes.
 *
 * @param array $args Array of arguments.
 * @return void
 */
if ( ! function_exists( 'kbx_multicheck_callback' ) ) {
	function kbx_multicheck_callback( $args ) {
		global $kbx_options;
		$html = '';

		if ( ! empty( $args['options'] ) ) {
			foreach ( $args['options'] as $key => $option ) {
				if ( isset( $kbx_options[ $args['id'] ][ $key ] ) ) {
					$enabled = $option;
				} else {
					$enabled = null;
				}

				$html .= '<input name="kbx_settings[' . $args['id'] . '][' . $key . ']" id="kbx_settings[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked( $option, $enabled, false ) . '/> <br />';

				$html .= '<label for="kbx_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
			}

			$html .= '<p class="description">' . $args['desc'] . '</p>';
		}

		/** This filter has been defined in settings-page.php */
		echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
	}
}


/**
 * Radio Callback
 *
 * Renders radio boxes.
 *
 * @param array $args Array of arguments.
 * @return void
 */
if ( ! function_exists( 'kbx_radio_callback' ) ) {
	function kbx_radio_callback( $args ) {
		global $kbx_options;
		$html = '';

		foreach ( $args['options'] as $key => $option ) {
			$checked = false;

			if ( isset( $kbx_options[ $args['id'] ] ) && $kbx_options[ $args['id'] ] === $key ) {
				$checked = true;
			} elseif ( isset( $args['options'] ) && $args['options'] === $key && ! isset( $kbx_options[ $args['id'] ] ) ) {
				$checked = true;
			}

			$html .= '<input name="kbx_settings[' . $args['id'] . ']"" id="kbx_settings[' . $args['id'] . '][' . $key . ']" type="radio" value="' . $key . '" ' . checked( true, $checked, false ) . '/> <br />';
			$html .= '<label for="kbx_settings[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
		}

		$html .= '<p class="description">' . $args['desc'] . '</p>';

		/** This filter has been defined in settings-page.php */
		echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
	}
}


/**
 * Number Callback
 *
 * Renders number fields.
 *
 * @param array $args Array of arguments.
 * @return void
 */
if ( ! function_exists( 'kbx_number_callback' ) ) {
	function kbx_number_callback( $args ) {
		global $kbx_options;

		if ( isset( $kbx_options[ $args['id'] ] ) ) {
			$value = $kbx_options[ $args['id'] ];
		} else {
			$value = isset( $args['options'] ) ? $args['options'] : '';
		}

		$max  = isset( $args['max'] ) ? $args['max'] : 999999;
		$min  = isset( $args['min'] ) ? $args['min'] : 0;
		$step = isset( $args['step'] ) ? $args['step'] : 1;

		$size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$html = '<input type="number" step="' . esc_attr( $step ) . '" max="' . esc_attr( $max ) . '" min="' . esc_attr( $min ) . '" class="' . $size . '-text" id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '"/>';
		$html .= '<p class="description">' . $args['desc'] . '</p>';

		/** This filter has been defined in settings-page.php */
		echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
	}
}


/**
 * Select Callback
 *
 * Renders select fields.
 *
 * @param array $args Array of arguments.
 * @return void
 */
if ( ! function_exists( 'kbx_select_callback' ) ) {
	function kbx_select_callback( $args ) {
		global $kbx_options;

		if ( isset( $kbx_options[ $args['id'] ] ) ) {
			$value = $kbx_options[ $args['id'] ];
		} else {
			$value = isset( $args['options'] ) ? $args['options'] : '';
		}

		if ( isset( $args['chosen'] ) ) {
			$chosen = 'class="kbx-chosen"';
		} else {
			$chosen = '';
		}
		$disabled_options_key = array();
		if( isset($args['disabled_options_key']) && !empty($args['disabled_options_key']) && is_array($args['disabled_options_key']) ){
			$disabled_options_key = $args['disabled_options_key'];
		}

		$readonly = '';
		if( isset($args['readonly']) && !empty($args['readonly']) && $args['readonly'] == 'true' ){
			$readonly = 'readonly="readonly" class="kbx_disabled_input"';
		}
		$html = '<select '.$readonly.' id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']" ' . $chosen . ' />';
		
		foreach ( $args['options'] as $option => $name ) {
			//check if $value is an array
			if( is_array($value) ){
				foreach ($value as $key => $val) {
					if( $option == $key ){
						$selected = selected( $option, $val, false );
						if( in_array($option, $disabled_options_key) ){
							$html .= '<option disabled="disabled" class="kbx_disabled_input" value="' . $option . '" ' . $selected . '>' . $name . '</option>';
						}else{
							$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
						}
						
					}
				}
			}else{
				$selected = selected( $option, $value, false );
				if( in_array($option, $disabled_options_key) ){
					$html .= '<option disabled="disabled" class="kbx_disabled_input" value="' . $option . '" ' . $selected . '>' . $name . '</option>';
				}else{
					$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
				}
			}
		}

		$html .= '</select>';
		if( isset($args['side_note']) ){
			$html .= $args['side_note'];
		}
		$html .= '<p class="description">' . $args['desc'] . '</p>';

		/** This filter has been defined in settings-page.php */
		echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
	}
}
/**
 * Display text fields.
 *
 * @param array $args Array of arguments.
 * @return void
 */
if ( ! function_exists( 'kbx_color_callback' ) ) {
	function kbx_color_callback( $args ) {

	    // First, we read the options collection.
	    global $kbx_options;

	    if ( isset( $kbx_options[ $args['id'] ] ) ) {
	        $value = $kbx_options[ $args['id'] ];
	    } else {
	        $value = isset( $args['options'] ) ? $args['options'] : '';
	    }

	    $html = '<input type="color" class="kbx-color" id="kbx_settings[' . $args['id'] . ']" name="kbx_settings[' . $args['id'] . ']" value="' . esc_attr( stripslashes( $value ) ) . '" />';
	    $html .= '<p class="description">' . $args['desc'] . '</p>';

	    /** This filter has been defined in settings-page.php */
	    echo apply_filters( 'kbx_after_setting_output', $html, $args ); // WPCS: XSS OK.
	}
}


