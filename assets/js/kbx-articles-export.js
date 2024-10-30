/*global ajaxurl, wc_product_export_params */
;(function ( $, window ) {
	/**
	 * articleExportForm handles the export process.
	 */
	var articleExportForm = function( $form ) {
		this.$form = $form;
		this.xhr   = false;

		// Initial state.
		this.$form.find('.kbx-exporter-progress').val( 0 );

		// Methods.
		this.processStep = this.processStep.bind( this );

		// Events.
		$form.on( 'submit', { articleExportForm: this }, this.onSubmit );
	};

	/**
	 * Handle export form submission.
	 */
	articleExportForm.prototype.onSubmit = function( event ) {
		event.preventDefault();
		event.data.articleExportForm.$form.addClass( 'kbx-exporter__exporting' );
		event.data.articleExportForm.$form.find('.kbx-exporter-progress').val( 0 );
		event.data.articleExportForm.$form.find('.kbx-exporter-button').prop( 'disabled', true );
        event.data.articleExportForm.$form.find('.spinner').css({'display':'block'});
        event.data.articleExportForm.$form.find('progress').css({'display':'block'});
        event.data.articleExportForm.$form.find('table').css({'display':'none'});
        event.data.articleExportForm.$form.find('.kbx-exporter-button').css({'display':'none'});
		event.data.articleExportForm.processStep( 1, $( this ).serialize(), '' );

	};

	/**
	 * Process the current export step.
	 */
	articleExportForm.prototype.processStep = function( step, data, columns) {
		var $this         = this,
			selected_columns = $( '.kbx-exporter-columns' ).val();

		$.ajax( {
			type: 'POST',
			url: ajaxurl,
			data: {
				form             : data,
				action           : 'kbx_do_ajax_article_export',
				step             : step,
				columns          : columns,
				selected_columns : selected_columns,
				security         : kbx_articles_export_params.export_nonce
			},
			dataType: 'json',
			success: function( response ) {
				console.log(response);
				if ( response.success ) {
					if ( 'done' === response.data.step ) {
						$this.$form.find('.kbx-exporter-progress').val( response.data.percentage );
						window.location = response.data.url;
						setTimeout( function() {
							$this.$form.removeClass( 'kbx-exporter__exporting' );
							$this.$form.find('.kbx-exporter-progress').val( 0 );
							$this.$form.find('.spinner').css({'display':'none'});
							$this.$form.find('progress').css({'display':'none'});
							$this.$form.find('table').css({'display':'block'});
                            $this.$form.find('.kbx-exporter-button').css({'display':'block'}).prop( 'disabled', false );
						}, 2000 );
					} else {
						$this.$form.find('.kbx-exporter-progress').val( response.data.percentage );
						$this.processStep( parseInt( response.data.step, 10 ), data, response.data.columns);
					}
				}


			}
		} ).fail( function( response ) {
			window.console.log( response );
		} );
	};

	/**
	 * Function to call articleExportForm on jquery selector.
	 */
	$.fn.kbx_articles_export_form = function() {
		new articleExportForm( this );
		return this;
	};

	$( '.kbx-exporter' ).kbx_articles_export_form();

})( jQuery, window );
