jQuery(document).ready(function($){
	$('#kb_shortcode_generator_meta').on('click', function(e){
		 $('#kb_shortcode_generator_meta').prop('disabled', true);
		$.post(
			ajaxurl,
			{
				action : 'show_kbx_shortcode_cmn'
				
			},
			function(data){
				 $('#kb_shortcode_generator_meta').prop('disabled', false);
				$('#wpwrap').append(data);
			}
		)
	})
	
		$(document).on( 'click', '.kb_copy_close', function(){
        $(this).parent().parent().parent().parent().parent().remove();
    })
	
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

                
                shortcodedata += ']';


            }
            //tinyMCE.activeEditor.selection.setContent(shortcodedata);
            //$('#sm-modal').remove();
			$('.sm_shortcode_list').hide();
			$('.kb_shortcode_container').show();
			$('#kb_shortcode_container').val(shortcodedata);
			$('#kb_shortcode_container').select();
			document.execCommand('copy');
        }
        else
        {
            alert("Please select a shortcode option.");
        }



    }).on( 'change', '#kbx_mode',function(){
	
		var mode = $('#kbx_mode').val();
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
                }
            }
		}else{
            $('#kbx-common-shortcode-options').hide();
		}
	});
	
})