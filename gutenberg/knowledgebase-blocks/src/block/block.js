/**
 * BLOCK: knowledgebase-blocks
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'kbx/block-knowledgebase-blocks', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'Knowledgebase Helpdesk — Shortcode Generator' ), // Block title.
	icon: 'shield', // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [
		__( 'Knowledgebase Blocks' ),
		__( 'Knowledgebase Helpdesk' ),
		__( 'Knowledgebase Helpdesk - Shortcode Generator' ),
	],
	attributes: {
        shortcode: {
            type: 'string',
            default: ''
        }
    },

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function( props ) {
		const { attributes: { shortcode }, setAttributes } = props;

		
		function showShortcodeModal(e){
			 jQuery('#kbx_shortcode_generator_meta_block').prop('disabled', true);
			 jQuery(e.target).addClass('currently_editing');
			jQuery.post(
                ajaxurl,
                {
                    action : 'show_kbx_shortcode_cmn'
                    
                },
                function(data){
                    jQuery('#wpwrap').append(data);
                }
            )
		}

		function insertShortCode(e){
			const shortcodeData = jQuery('#kbx_add_shortcode_cmn').attr('gutenberg_kbx_shortcode_generator_value');
			setAttributes( { shortcode: shortcodeData } );
			jQuery('#kbx_add_shortcode_cmn').parents('#sm-modal').remove();
			jQuery('.currently_editing').removeClass('currently_editing');
			jQuery('#kbx_shortcode_generator_meta_block').removeAttr('disabled');
			console.log({ shortcode });
		}


		jQuery(document).on('click','#kbx_add_shortcode_cmn', function(){
			//e.preventDefault();
			jQuery('.currently_editing').next('#insert_kbx_shortcode').trigger('click');
		});

		function getShortCode(){
			jQuery(document).find('#kbx_add_shortcode_cmn').trigger('click');
			//jQuery(document).find( '.modal-content .close').trigger('click');
		}
		jQuery(document).on( 'click', '.modal-content .close', function(){
			jQuery('.currently_editing').removeClass('currently_editing');
			jQuery('#kbx_shortcode_generator_meta_block').removeAttr('disabled');
		});

		return (
			<div className={ props.className }>
				<input type="button" id="kbx_shortcode_generator_meta_block" onClick={showShortcodeModal} className="button button-primary button-large" value="Generate Knowledgebase Shortcode" />
				<input type="button" id="insert_kbx_shortcode" onClick={insertShortCode} className="button button-primary button-large gutenberg_hidden" value="Test Knowledgebase Shortcode" />
				<input type="button" id="get_kbx_shortcode" onClick={getShortCode} className="button button-primary button-large gutenberg_hidden" value="Get Knowledgebase Shortcode" />
				<br />
				<div className="kbx_shortcode_value">
						{ shortcode }
				</div>
			</div>
		);
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function( props ) {
		const { attributes: { shortcode } } = props;
		return (
			<div>
				{shortcode}
			</div>
		);
	},
} );
