;(function( $ ) {
    if( typeof tinymce !== 'undefined' ){
        tinymce.PluginManager.add('kbx_shortcode_cmn', function( editor,url )
        {
            var shortcodeValues = [];

            editor.addButton('kbx_shortcode_cmn', {
    			title : 'Knowledgebase Shortcode',
                text: 'Knowledgebase',
                icon: false,
                onclick : function(e){
                    $.post(
                        ajaxurl,
                        {
                            action : 'show_kbx_shortcode_cmn'
                            
                        },
                        function(data){
                            $('#wpwrap').append(data);
                        }
                    )
                },
                values: shortcodeValues
            });
        });
    }

    var selector = '';
    $(document).on( 'click', '.modal-content .close', function(){
        $(this).parent().parent().remove();
    }).on( 'click', '#kbx_add_shortcode_cmn',function(){
        var mode = $('#kbx_mode').val();
        if(mode !== ''){
            var shortcodedata='['+mode;
            if(mode=='kbx_app' || mode=='kbxbot' ){
                shortcodedata += ']';
            }else{

                var order = $('#kbx_order').val();
				var limit = $('#kbx_limit').val();
				var searchBar = $('input[name=kbx_search_bar]:checked').val();
				var pagination = $('input[name=kbx_pagination]:checked').val();

				if(mode!='kbx-knowledgebase'){
                    var sections = $('#kbx_shortcode_sections').val();
                    var orderby = $('#kbx_orderby').val();
                    if( sections !== '' ){
                        shortcodedata +=' section="'+sections+'"';
                    }

                    if( orderby !== '' ){
                        shortcodedata +=' orderby="'+orderby+'"';
                    }
                }

                if( mode == 'kbx-faq' ){
                    var content_type = $('#kbx_content_type_sections').val();

                    if( content_type != '' ){
                        shortcodedata +=' content_type="'+ content_type+'"';
                    }
                }

                if( order !== '' ){
                    shortcodedata +=' order="'+order+'"';
                }

                if(mode!='kbx-knowledgebase') {
                    if( limit !== '' ){
                        shortcodedata +=' limit="'+limit+'"';
                    }
                    shortcodedata += ' show_pagination="' + pagination + '"';
                }

                shortcodedata +=' show_search_form="'+searchBar+'"';
                shortcodedata += ']';


            }
            if( !isGutenbergActive() ){
                jQuery('#sm-modal').remove();
                tinyMCE.activeEditor.selection.setContent(shortcodedata);
            }else{
                jQuery(this).attr('gutenberg_kbx_shortcode_generator_value', shortcodedata);
                //jQuery('#kbx_add_shortcode_cmn').prev('.gutenberg_kbx_shortcode_generator_value').remove();
                //jQuery('#kbx_add_shortcode_cmn').before('<textarea class="gutenberg_kbx_shortcode_generator_value gutenberg_hidden">'+ shortcodedata +'</textarea>');
            }
        }
        else
        {
            alert("Please select a shortcode option.");
        }



    }).on( 'change', '#kbx_mode',function(){
	
		var mode = $('#kbx_mode').val();
        $('#content_showcase_type').hide();
        if(mode!=''){
            if(mode=='kbx_app' || mode=='kbxbot' ){
                $('#kbx-common-shortcode-options').hide();
            }else{
                $('#kbx-common-shortcode-options').show();
                if(mode=='kbx-knowledgebase') {
                    $('#field_showcase_sections').hide();
                    $('#field_showcase_pagination').hide();
                    $('#field_showcase_orderby').hide();
                    $('#field_showcase_limit').hide();
                }else{
                    $('#field_showcase_sections').show();
                    $('#field_showcase_pagination').show();
                    $('#field_showcase_orderby').hide();
                    $('#field_showcase_limit').hide();
                    
                    //toggle the cotent or excerpt view section
                    if( mode == 'kbx-faq' ){
                        $('#content_showcase_type').show();
                    }else{
                        $('#content_showcase_type').hide();
                    }

                }
            }
		}else{
            $('#kbx-common-shortcode-options').hide();
		}
	});

}(jQuery));


function isGutenbergActive() {
    return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
}