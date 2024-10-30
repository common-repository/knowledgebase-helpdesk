/******************************
 Metarial preloader plugin start
 ******************************/
(function (e) {
    var t = {
        position: "bottom",
        height: "5px",
        col_1: "#159756",
        col_2: "#da4733",
        col_3: "#3b78e7",
        col_4: "#fdba2c",
        fadeIn: 200,
        fadeOut: 200
    };
    e.materialPreloader = function (n) {
        var r = e.extend({}, t, n);
        $template = "<div id='materialPreloader' class='load-bar' style='height:" + r.height + ";display:none;" + r.position + ":0px'><div class='load-bar-container'><div class='load-bar-base base1' style='background:" + r.col_1 + "'><div class='color red' style='background:" + r.col_2 + "'></div><div class='color blue' style='background:" + r.col_3 + "'></div><div class='color yellow' style='background:" + r.col_4 + "'></div><div class='color green' style='background:" + r.col_1 + "'></div></div></div> <div class='load-bar-container'><div class='load-bar-base base2' style='background:" + r.col_1 + "'><div class='color red' style='background:" + r.col_2 + "'></div><div class='color blue' style='background:" + r.col_3 + "'></div><div class='color yellow' style='background:" + r.col_4 + "'></div> <div class='color green' style='background:" + r.col_1 + "'></div> </div> </div> </div>";
        e(".kbx-articles").prepend($template);
        this.on = function () {
            e("#materialPreloader").fadeIn(r.fadeIn)
        };
        this.off = function () {
            e("#materialPreloader").fadeOut(r.fadeOut)
        }
    }
})(jQuery)
jQuery(document).ready(function ($) {
    $("#kbx-query").on("keyup", function (e) {

        var value = $(this).val();
        var currentInputBox = $(this);

        $(".kbx-hidden-search").val(value);

        if (value.length > 3) {

            var data = {
                'action'    : 'kbx_search_article',
                'post_key'  : value,
                'security'  : kbx_ajax_nonce
            };

            currentInputBox.addClass('searching');

            jQuery.post(ajaxurl, data, function (response) {

                var json = $.parseJSON(response);

                if (json.status == 'true') {
                    currentInputBox.siblings('#serp-dd').css('display', 'block');
                    currentInputBox.siblings('#serp-dd').children(".result").html('');
                    currentInputBox.siblings('#serp-dd').children(".result").html(json.list);
                }

                if (json.status == 'false') {
                    currentInputBox.siblings('#serp-dd').css('display', 'block');
                    currentInputBox.siblings('#serp-dd').children(".result").html(json.list);
                }

                currentInputBox.removeClass('searching');

            });

        }
        else {
            currentInputBox.siblings('#serp-dd').children(".result").html("");
            currentInputBox.siblings('#serp-dd').css('display', 'none');
            currentInputBox.removeClass('searching');
        }

    });
    //Hide the pop up if click outside of result.
    $(document).mouseup(function (e) {
        var container = $('#serp-dd');
        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.css({'display': 'none'});
        }
    });

});

jQuery(document).ready(function ($) {

    $(".kbx-fes-trigger").on("click", function (e) {

        e.preventDefault();

        $(this).toggleClass("open");
        $(".kbx-fes-widget-main").toggleClass("visible");

    });

    $(".kbx-fes-widget-main .close-it").on("click", function (e) {

        e.preventDefault();

        $(".kbx-fes-trigger").toggleClass("open");
        $(".kbx-fes-widget-main").toggleClass("visible");

    });

    $(".kbx-fes-search-form-submit").on("click", function (e) {

        e.preventDefault();

        var value = $(".kbx-fes-search-form-input").val();

        getFewSearchResult(value);

    });

    $(".kbx-fes-search-form-input").on("keyup", function (e) {

        e.preventDefault();

        var value = $(this).val();

        if (value.length > 3) {
            getFewSearchResult(value);
        }

    });

    function getFewSearchResult(value) {
        var searchString = value;

        if (searchString.length > 3) {

            var data = {
                'action'    : 'kbx_search_article',
                'post_key'  : value,
                'security'  : kbx_ajax_nonce
            };

            $(".search-spinner").removeClass('hidden');
            $('.search-empty').addClass("hidden");
            $('.kbx-fes-alert').addClass("hidden");

            jQuery.post(ajaxurl, data, function (response) {

                var json = $.parseJSON(response);

                if (json.status == 'true') {
                    $('.kbx-fes-search-results').css('display', 'block');
                    $('.kbx-fes-search-results-ul').html('');
                    $('.kbx-fes-search-results-ul').html(json.list);
                }

                if (json.status == 'false') {
                    $('.kbx-fes-search-results').css('display', 'none');
                    $('.kbx-fes-search-results-ul').html('');
                    $('.search-empty').removeClass("hidden");
                    $('.search-empty .fes-search-terms').html(searchString);
                }

                $(".search-spinner").addClass('hidden');

            });

        }
        else {
            $('.kbx-fes-search-results').css('display', 'none');
            $('.kbx-fes-search-results-ul').html('');
            $('.search-empty').addClass("hidden");
            $('.kbx-fes-alert').removeClass("hidden");
            $('.kbx-fes-alert').html("");
            $('.kbx-fes-alert').html("Search string is too short!");
        }
    }

    //kbx tabs
    $('ul.kbx-tabs li').first().addClass('kbx-tab-current');
    $('.kbx-tabs-container .kbx-tab-content').first().addClass('kbx-tab-current');
    $('ul.kbx-tabs li').click(function () {
        var tab_id = $(this).attr('data-tab');

        $('ul.kbx-tabs li').removeClass('kbx-tab-current');
        $('.kbx-tab-content').removeClass('kbx-tab-current');

        $(this).addClass('kbx-tab-current');
        $("#" + tab_id).addClass('kbx-tab-current');
    });
    //Glossary page Search Full Width
    if ($("#docsSearch").length > 0) {
        var offset = $("#docsSearch").offset();
        var offsetTop = 0;
        if (offset.top < 500) {
            var offsetTop = 10;
        } else {
            var offsetTop = offset.top - 100;
        }

        var serchArea = $("#searchBar");
        $(window).scroll(function () {
            var scroll = $(window).scrollTop();
            if (scroll >= offsetTop) {
                serchArea.css({
                    "width": "100%"
                })
            } else {
                serchArea.css({
                    "width": "50%"
                })
            }
        });
    }
    //Basic Layout Masonry Load

    // setTimeout(function () {
    //     $('.kbx-category-list').imagesLoaded().packery({
    //         itemSelector: '.kbx-category-box',
    //     });
    // }, 300);

    if($('.kbx-glossary-container').is(':visible'))
    {

    kbx_glossary_init();   
    } 
    recalculateGlossaryWidth();

    //fullWidthGlossary();

    //Initialize the metarial preloader
    var kbx_glossary_preloader = new $.materialPreloader({
        position: 'top',
        height: '8px',
        col_1: '#159756',
        col_2: '#da4733',
        col_3: '#3b78e7',
        col_4: '#fdba2c',
        fadeIn: 200,
        fadeOut: 200
    });
    //Glossay page terms action
    var gloddaryConMargin = $(".kbx-glossary-letters").height() / 2;
    var gloddaryConMarginStr = '-' + gloddaryConMargin + 'px';
    $('.kbx-glossary-letters').css({
        'margin-top': gloddaryConMarginStr
    });
    $(document).on('mouseover', '.kbx-glossary-letter', function (e) {
        $('.kbx-glossary-letter').removeClass('active-glossary-item');
        $(this).addClass('active-glossary-item');
        var position = $(this).position();
        var topPosition = position.top - 50;
        var bgposition = "0px " + topPosition + "px";

        $(this).parent().css({
            'background-position': bgposition,
        })

    });
    $(document).on('click', '.kbx-glossary-letter', function (e) {
        $("#kbx-glossary-load-more").css({'display': 'none'});
        kbx_glossary_preloader.on();
        $('.kbx-glossary-letter').removeClass('active-glossary-item');
        $('.kbx-glossary-letter').removeClass('active-glossary-item-bold');
        $(this).addClass('active-glossary-item');
        $(this).addClass('active-glossary-item-bold');
        var kbxGterm = $(this).attr('data-letter');
        var data = {
            'action'    : 'kbx_glossary_articles_by_term',
            'gterm'     : kbxGterm,
            'security'  : kbx_ajax_nonce
        };

        $.post(ajaxurl, data, function (response) {
            kbx_glossary_preloader.off();
            $('.articleList').html('');
            var $container = $('.articleList').packery();
            var $html = $(response.html);
            $container.append($html);
            recalculateGlossaryWidth();
            $container.packery('appended', $html);
            if (response.total_articles > response.offset) {
                $("#kbx-glossary-load-more").attr('data-gterm', response.gterm);
                $("#kbx-glossary-load-more").attr('data-offset', response.offset);
                $("#kbx-glossary-load-more").css({'display': 'block'});
            }

        });
    });
    //Infinite Scrolling
    $(document).on("click", "#kbx-glossary-load-more", function () {
        var currentDom = $(this);
        var actionType = "more";
        kbx_glossary_load_more(currentDom, actionType);
    });

    /*$(window).on("scroll", function() {

        if($("#kbx-glossary-load-more").length > 0){
            var scrollHeight = $(document).height();
            var scrollPosition = $(window).height() + $(window).scrollTop();
            var scrollVal=(scrollHeight - scrollPosition) / scrollHeight;
            if (scrollVal.toFixed(2)<= 0.02) {
                var currentDom=$("#kbx-glossary-load-more");
                currentDom.css({'display':'inline-block'});
                kbx_glossary_load_more(currentDom);
                //alert('working');
            }
        }
    });*/


    function kbx_glossary_load_more(currentDom) {
        $('#kbx-glossary-load-more-pre-loader').show();
        var offset = currentDom.attr('data-offset');
        var gTerm = currentDom.attr('data-gterm');
        //console.log(offset);
        var data = {
            'action'    : 'kbx_glossary_load_more',
            'offset'    : offset,
            'gterm'     : gTerm,
            'security'  : kbx_ajax_nonce
        };

        $.post(ajaxurl, data, function (response) {
            $('#kbx-glossary-load-more-pre-loader').hide();
            //Showing more product by appending to the list.
            $('.articleList').html();
            var $container = $('.articleList').packery();
            var $html = $(response.html);
            $container.append($html);
            recalculateGlossaryWidth();
            $container.packery('appended', $html);
            // console.log(response);
            if (response.offset == -1) {
                currentDom.css({'display': 'none'});
            } else {
                currentDom.attr('data-offset', response.offset);
                currentDom.attr('data-gterm', response.gterm);
            }
            //alert(response.offset);
        });
    }
    function kbx_glossary_init() {
        var data = {
            'action'    : 'kbx_glossary_init',
            'security'  : kbx_ajax_nonce
        };
        $.post(ajaxurl, data, function (response) {
            $('#kbx-glossary-load-more-pre-loader').hide();
            $('.articleList').html();
            var $container = $('.articleList').packery();
            var $html = $(response.html);
            $container.append($html);
            recalculateGlossaryWidth();
            $container.packery('appended', $html);
            if (response.offset == -1) {
                currentDom.css({'display': 'none'});
            } else {
                currentDom.attr('data-offset', response.offset);
                currentDom.attr('data-gterm', response.gterm);
            }
        });
    }

    jQuery(window).resize(function(){
        recalculateGlossaryWidth();
    });
});




function getOffset1(el) {
    var _x = 0;
    var _y = 0;
    while (el && !isNaN(el.offsetLeft) && !isNaN(el.offsetTop)) {
        _x += el.offsetLeft - el.scrollLeft;
        _y += el.offsetTop - el.scrollTop;
        el = el.offsetParent;
    }
    return {top: _y, left: _x};
}

//Glossary page forcing to full width
function fullWidthGlossary(){

    var fullwidth = jQuery("body").prop("clientWidth");
    var fullheight = jQuery(window).height();
    if (jQuery('.kbx-glossary-container').length > 0) {
        var maindivcon = jQuery('.kbx-articles').parent()[0];
        jQuery('.kbx-glossary-container').removeAttr('style');
        var getleft = getOffset1(maindivcon);
        console.log(getleft.left);
        jQuery('.kbx-glossary-container').css({
            'width': fullwidth + 'px',
            'left': '-' + getleft.left + 'px',
            'right': '-' + getleft.left + 'px',
            'position': 'relative'
        });
        /*jQuery('#docsSearch').css({
            'width':fullwidth+'px',
            'left':'-'+getleft.left+'px',
        });
        jQuery('.kbx-glossary-letters').css({
            'right':'-'+getleft.left+'px',
        });*/
    }
}


function recalculateGlossaryWidth(){
    var glosary_width = jQuery('.kbx-glossary-container .kbx-articles').width();
    if( glosary_width < 450 ){
        jQuery(document).find('.kbx-glossary-container .kbx-articles .kbx-glossary-item').css({
            'width' : '100%'
        });
    }else if( (glosary_width >= 450) && (glosary_width < 768) ){
        jQuery(document).find('.kbx-glossary-container .kbx-articles .kbx-glossary-item').css({
            'width' : '50%'
        });
    }else if( (glosary_width >= 768) && (glosary_width < 1024) ){
        jQuery(document).find('.kbx-glossary-container .kbx-articles .kbx-glossary-item').css({
            'width' : '33.3%'
        });        
    }else if( (glosary_width >= 1024) && (glosary_width < 1366) ){
        jQuery(document).find('.kbx-glossary-container .kbx-articles .kbx-glossary-item').css({
            'width' : '100%'
        });       
    }
}

jQuery(window).on('load',function(){
    // init Packery
    var kb_grid = jQuery('.kb-container').imagesLoaded().packery({
      // options...
    });

    jQuery('.kbx-category-list').imagesLoaded().packery({
        itemSelector: '.kbx-category-box',
    });


    jQuery('.kbx-sidebar-dropdown-item').click(function(e) {
        if (e.target !== e.currentTarget) return;
        console.log('Разворачиваем/сворачиваем меню')
        e.preventDefault();
        jQuery(this).siblings('.kbx-sidebar-dropdown-item').removeClass('opened');
        jQuery(this).toggleClass('opened');
      })






});