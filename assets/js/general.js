jQuery(document).ready(function($){
	
	//UpvoteCount
    $(".kbx-like-btn").on("click", function(event){
		
		event.preventDefault();

        var data_id = $(this).attr("data-article-id");
        var like_type = $(this).attr("data-like-type");

        var currentElement = $(this);

        var elementId = 'kbx-like-pid-' + data_id;

        $.post(ajaxurl, {            
            action: 'kbx_post_like_action', 
            post_id: data_id,
            like_type: like_type,

        }, function(data) {
            var json = $.parseJSON(data);
            if( json.vote_status == 'success' )
            {
               if(like_type=="up"){
                   $('#' +elementId+ ' .kbx-like-counter-up').html(json.votes);
                   $('#' +elementId+ ' .kbx-like-counter-up').css("color", "green");
               }else if(like_type=="down"){
                   $('#' +elementId+ ' .kbx-like-counter-down').html(json.votes);
                   $('#' +elementId+ ' .kbx-like-counter-down').css("color", "green");
               }


            }
        });
       
    });

});

jQuery(document).ready(function($){

    $('a[href="#kbx-aq-modal"]').click(function(event) {
      event.preventDefault();
      $(this).modal({
        fadeDuration: 250
      });
    });
    //Accordion for FAQ
    $(document).on('click','.kbx-faq-list li',function (e) {
           if($(this).hasClass('kbx-faq-active')){
               $(this).find('.kbx-faq-content').slideUp();
               $(this).removeClass('kbx-faq-active');
           }else{
               //For all
               $('.kbx-faq-list li').removeClass('kbx-faq-active');
               $('.kbx-faq-list li').find('.kbx-faq-content').slideUp();
               //For current
               $(this).find('.kbx-faq-content').slideDown();
               $(this).addClass('kbx-faq-active');
           }
    });
});

//KB bot starting.
//Global object passed by admin
var kbxBotVar=kbx_bot_obj;
jQuery(document).ready(function($){

    var loadKbxBotPlugin=0;
        if($('#kbx-bot-shortcode-template-container').length == 0  && $('#kbx-bot-app-shortcode-container').length ==0){
            //show it
            $('#kbx-bot-ball-wrapper').css({
                'display':'block',
            });
            //kbx-bot icon  position.
            $('#kbx-bot-container').css({
                'right': kbxBotVar.kbx_bot_position_x + 'px',
                'bottom': kbxBotVar.kbx_bot_position_y + 'px'
            });
            //kbx-bot icon animation disable or enable
            //Disable kbxBot icon Animation
            if (kbxBotVar.disable_icon_animation == 1) {
                $('.kbx-bot-ball').addClass('kbx-bot-animation-deactive');
            } else {
                $('.kbx-bot-ball').addClass('kbx-bot-animation-active');
            }
            //window resize.
            var widowH=$(window).height();
            var ballConH=parseInt(widowH*0.5);
            $('.kbx-bot-ball-inner').css({ 'height':ballConH+'px'})

            $(window).resize(function(){
                var widowH=$(window).height();
                var ballConH=parseInt(widowH*0.5);
                $('.kbx-bot-ball-inner').css({ 'height':ballConH+'px'})
            });
            $(document).on('click', '#kbx-bot-ball', function (event) {
                $("#kbx-bot-board-container").toggleClass('active-board');
                $('.kbx-bot-ball-inner').slimScroll({height: '55hv', start: 'bottom'});

                //Here is the Plugin  to be load only for once.
                if(loadKbxBotPlugin==0){
                    $.kbxbot({obj:kbxBotVar});
                    loadKbxBotPlugin++;
                }
                //If product detials is open then it will be closed.
            });
            $("#qcld-kbx-bot-shortcode-style-css").attr("disabled", "disabled");
            //Animation handle
            if (kbxBotVar.disable_icon_animation == 1) {
                $('.kbx-bot-ball').addClass('kbx-bot-animation-deactive');
            } else {
                $('.kbx-bot-ball').addClass('kbx-bot-animation-active');

                var itemHide = function () {
                    $('.kbx-bot-animation-active .kbx-bot-ball-animation-switch').fadeOut(1000);
                };
                setTimeout(function () {
                    itemHide()
                }, 1000);

                //Click Animation
                $('.kbx-bot-animation-active').click(function () {
                    $('.kbx-bot-animation-active .kbx-bot-ball-animation-switch').fadeIn(100);
                    setTimeout(function () {
                        itemHide()
                    }, 1000);
                });
            }
            //Close button
            $('.kbx-bot-header').append('<div id="kbx-bot-desktop-close">X</div>');
            $(document).on('click', '#kbx-bot-desktop-close', function (event) {
                $("#kbx-bot-board-container").toggleClass('active-board');
            });
        }else if($('#kbx-bot-shortcode-template-container').length > 0){
            $('#kbx-bot-ball').hide();
            //Add Scroll to ui
            $('.kbx-bot-ball-inner').slimScroll({height: '60hv',start : 'bottom'});
            //Add scroll to cart part
            var recentViewHeight=$('.kbx-bot-container').outerHeight();
            //console.log(recentViewHeight);
            if($('.kbx-bot-shortcode-template-02').length==0){
                $('.kbx-bot-cart-body').slimScroll({height: '200px',start : 'bottom'});
                $('.kbx-bot-products').slimScroll({height: '435px',start : 'bottom'});
            }

            //Remove style of template
            $("#qcld-kbx-bot-style-css").attr("disabled", "disabled");
            //Here is the Plugin  to be load only for once.
            if(loadKbxBotPlugin==0){
                $.kbxbot({obj:kbxBotVar});
                loadKbxBotPlugin++;
            }

        }
        else if ($('#kbx-bot-app-shortcode-container').length > 0) {
            //App UI (ball inner)
            setTimeout(function () {
                var widowH = $(window).height();
                var headerH = $('.kbx-bot-header').outerHeight();
                var footerH = $('.kbx-bot-footer').outerHeight();

                var AppContentInner = widowH - (headerH + footerH);
                //alert(footerH);
                $('#kbx-bot-app-shortcode-container .kbx-bot-ball-inner').css({'height': AppContentInner + 'px'})
            }, 300);
            $(window).resize(function () {
                setTimeout(function () {
                    var widowH = $(window).height();
                    var headerH = $('.kbx-bot-header').outerHeight();
                    var footerH = $('.kbx-bot-footer').outerHeight();

                    var AppContentInner = widowH - (headerH + footerH);
                    //alert(footerH);
                    $('#kbx-bot-app-shortcode-container .kbx-bot-ball-inner').css({'height': AppContentInner + 'px'})
                }, 300)
            });

            $('#kbx-bot-ball').hide();
            //Add Scroll to chat ui
            $("#kbx-bot-shortcode-style-css").attr("disabled", "disabled");
            $("#kbx-bot-board-container").addClass('active-chat-board');
            $('.kbx-bot-ball-inner').slimScroll({height: '55hv', start: 'bottom'});
            if (loadKbxBotPlugin == 0) {
                $.kbxbot({obj: kbxBotVar});
                loadKbxBotPlugin++;
            }

        }

});