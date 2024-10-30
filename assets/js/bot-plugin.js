/*
 * Project:      kbxBot jQuery Plugin
 * Description:  kbxBot AI based  functionality are handled .
 * Author:       QuantumCloud
 * Version:      1.0
 */
(function ($) {

    /*
     * Global variable as object will beused to handle
     * kbxBot  initialize, tree change transfer,
     * changing tree steps and cookies etc.
     */
    var globalKbx = {
        initialize: 0,
        settings: {},
        wildCard: 0,
        wildcards: '',
        wildcardsHelp: ['start', 'faq', 'email', 'reset'],
        articleStep: 'asking',
        supportStep: 'welcome',
        hasNameCookie: $.cookie("shopper"),
        shopperUserName: '',
        shopperEmail: '',
        shopperMessage: '',
        emptymsghandler: 0,
        repeatQueryEmpty: '',
        kbxIsWorking: 0,
        ai_step: 0,
        df_status_lock: 0,

    };

    /*
     * kbxBot welcome section coverd
     * greeting for new and already visited shopper
     * based the memory after asking thier name.
     */
    var kbxWelcome = {
        greeting: function () {
            //Very begining greetting.
            var botJoinMsg = "<strong>" + globalKbx.settings.obj.agent + " </strong> " + kbxKits.randomMsg(globalKbx.settings.obj.agent_join);
            kbxMsg.single(botJoinMsg);
            //Showing greeting for name in cookie or fresh shopper.
            setTimeout(function () {
                var firstMsg = kbxKits.randomMsg(globalKbx.settings.obj.hi_there) + ' ' + kbxKits.randomMsg(globalKbx.settings.obj.welcome) + " <strong>" + globalKbx.settings.obj.host + "!</strong> ";
                var secondtMsg = kbxKits.randomMsg(globalKbx.settings.obj.asking_name);
                kbxMsg.double(firstMsg, secondtMsg);

                //dialogflow
                if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
                    globalKbx.wildCard = 0;
                    globalKbx.ai_step = 0;
                    localStorage.setItem("wildCard", globalKbx.wildCard);
                    localStorage.setItem("aiStep", globalKbx.ai_step);
                }
            }, globalKbx.settings.preLoadingTime);
        }
    };
    //Append the message to the message container based on the requirement.
    var kbxMsg = {
        single: function (msg) {
            globalKbx.kbxIsWorking = 1;
            $(globalKbx.settings.messageContainer).append(kbxKits.botPreloader());
            //scroll to the last message
            kbxKits.scrollTo();
            setTimeout(function () {
                $(globalKbx.settings.messageLastChild + ' .kbx-bot-paragraph').html(msg);
                //If has youtube link then show video
                kbxKits.videohandler();
                //Enable the editor
                kbxKits.enableEditor(globalKbx.settings.obj.send_msg);
                //keeping in history
                kbxKits.kbxBotHistorySave();
                //scroll to the last message
                kbxKits.scrollTo();
            }, globalKbx.settings.preLoadingTime);

        },

        single_nobg: function (msg) {
            globalKbx.kbxIsWorking = 1;
            $(globalKbx.settings.messageContainer).append(kbxKits.botPreloader());
            //scroll to the last message
            kbxKits.scrollTo();
            setTimeout(function () {
                $(globalKbx.settings.messageLastChild + ' .kbx-bot-paragraph').css({
                    'background-color': 'transparent',
                    'border': 'none'
                }).html(msg);
                //Enable the editor
                kbxKits.enableEditor(globalKbx.settings.obj.send_msg);
                //scroll to the last message
                kbxKits.scrollTo();
                //keeping in history
                kbxKits.kbxBotHistorySave();
            }, globalKbx.settings.preLoadingTime);

        },

        double: function (fristMsg, secondMsg) {
            globalKbx.kbxIsWorking = 1;
            $(globalKbx.settings.messageContainer).append(kbxKits.botPreloader());
            //scroll to the last message
            kbxKits.scrollTo();
            setTimeout(function () {
                $(globalKbx.settings.messageLastChild + ' .kbx-bot-paragraph').html(fristMsg);
                //Second Message with interval
                $(globalKbx.settings.messageContainer).append(kbxKits.botPreloader());
                //scroll to the last message
                kbxKits.scrollTo();
                setTimeout(function () {
                    $(globalKbx.settings.messageLastChild + ' .kbx-bot-paragraph').html(secondMsg);
                    //Enable the editor
                    kbxKits.enableEditor(globalKbx.settings.obj.send_msg);
                    //scroll to the last message
                    kbxKits.scrollTo();
                    //keeping in history
                    kbxKits.kbxBotHistorySave();
                }, globalKbx.settings.preLoadingTime);

            }, globalKbx.settings.preLoadingTime);

        },
        double_nobg: function (fristMsg, secondMsg) {
            globalKbx.kbxIsWorking = 1;
            $(globalKbx.settings.messageContainer).append(kbxKits.botPreloader());
            //scroll to the last message
            kbxKits.scrollTo();
            setTimeout(function () {
                $(globalKbx.settings.messageLastChild + ' .kbx-bot-paragraph').html(fristMsg);
                //Second Message with interval
                $(globalKbx.settings.messageContainer).append(kbxKits.botPreloader());
                //scroll to the last message
                kbxKits.scrollTo();
                setTimeout(function () {
                    $(globalKbx.settings.messageLastChild + ' .kbx-bot-paragraph').parent().addClass('kbx-bot-msg-flat').html(secondMsg);
                    //scroll to the last message
                    kbxKits.scrollTo();
                    //Enable the editor
                    kbxKits.enableEditor(globalKbx.settings.obj.send_msg);
                    //scroll to the last message
                    kbxKits.scrollTo();
                    //keeping in history
                    kbxKits.kbxBotHistorySave();
                }, globalKbx.settings.preLoadingTime);
            }, globalKbx.settings.preLoadingTime);
        },
        shopper: function (shopperMsg) {
            globalKbx.kbxIsWorking = 1;
            $(globalKbx.settings.messageContainer).append(kbxKits.shopperMsgDom(shopperMsg));
            //scroll to the last message
            kbxKits.scrollTo();
        },
        shopper_choice: function (shopperChoice) {
            globalKbx.kbxIsWorking = 1;
            $(globalKbx.settings.messageLastChild).fadeOut(globalKbx.settings.preLoadingTime);
            $(globalKbx.settings.messageContainer).append(kbxKits.shopperMsgDom(shopperChoice));
            //scroll to the last message
            kbxKits.scrollTo();
            //keeping in history
            kbxKits.kbxBotHistorySave();
        }

    };

    //Every tiny tools are implemented  in kbxKits as object literal.
    var kbxKits = {
        ajax: function (data) {
            return jQuery.post(globalKbx.settings.obj.ajax_url, data);

        },
        dailogAIOAction: function (text) {
            return jQuery.ajax({
                type: "POST",
                url: "https://api.dialogflow.com/v1/query?v=20170712",
                contentType: "application/json; charset=utf-8",
                dataType: "json",
                headers: {
                    "Authorization": "Bearer " + globalKbx.settings.obj.ai_df_token
                },
                data: JSON.stringify({
                    query: text,
                    //lang : globalKbx.language,
                    lang: 'en-US',
                    sessionId: 'KbxBot_df_20180801'
                })
            });
        },
        sugestCat: function () {
            var data = {'action': 'kbx_bot_category'};
            var result = kbxKits.ajax(data);
            result.done(function (response) {
                kbxMsg.single_nobg(response);
                //Updating & keeping steps and wildcard in localstorage
                globalKbx.articleStep = 'category';
                localStorage.setItem("articleStep", globalKbx.articleStep);
                setTimeout(function () {
                    if (globalKbx.articleStep == 'category') {

						
						if (globalKbx.settings.obj.disable_support != 1) {
						var emailSuggMsg = kbxKits.randomMsg(globalKbx.settings.obj.support_email);
                        var confirmBtn = '<span class="kbx-bot-suggest-email"  type="button">' + globalKbx.settings.obj.yes + '</span> <span> ' + globalKbx.settings.obj.or + ' </span><span class="kbx-bot-reset-btn" type="button" reset-data="no">' + globalKbx.settings.obj.no + '</span>';
                        var backStart = '<span class="kbx-bot-wildcard" data-wildcart="back">' + kbxKits.randomMsg(globalKbx.settings.obj.back_start) + '</span>';
                        kbxMsg.double_nobg(emailSuggMsg, confirmBtn + backStart);
						 }else{
							 var backStart = '<span class="kbx-bot-wildcard" data-wildcart="back">' + kbxKits.randomMsg(globalKbx.settings.obj.back_start) + '</span>';
							kbxMsg.single(backStart);
						 }

                    }
                    //For handle the ai and alone
                    if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
                        globalKbx.wildCard = 0;
                        globalKbx.ai_step = 1;
                        //keeping value in localstorage
                        localStorage.setItem("wildCard", globalKbx.wildCard);
                        localStorage.setItem("aiStep", globalKbx.ai_step);
                    } else {
                        globalKbx.wildCard = 1;
                        globalKbx.articleStep = 'search'
                        //keeping value in localstorage
                        localStorage.setItem("wildCard", globalKbx.wildCard);
                        localStorage.setItem("articleStep", globalKbx.articleStep);
                    }
                }, globalKbx.settings.wildcardsShowTime);
            });
        },
        kbxBotHistorySave: function () {
            var kbxBotHistory = $(globalKbx.settings.messageWrapper).html();
            localStorage.setItem("kbxBotHistory", kbxBotHistory);
            globalKbx.kbxIsWorking = 0;
        },
        suggestEmail: function () {
            var emailSuggMsg = kbxKits.randomMsg(globalKbx.settings.obj.support_email);
            var confirmBtn = '<span class="kbx-bot-suggest-email"  type="button">' + globalKbx.settings.obj.yes + '</span> <span> ' + globalKbx.settings.obj.or + ' </span><span class="kbx-bot-reset-btn" type="button" reset-data="no">' + globalKbx.settings.obj.no + '</span>';
            var backStart = '<span class="kbx-bot-wildcard" data-wildcart="back">' + kbxKits.randomMsg(globalKbx.settings.obj.back_start) + '</span>';
            kbxMsg.double_nobg(emailSuggMsg, confirmBtn + backStart);
        }
        ,
        videohandler: function () {
            $(globalKbx.settings.messageLastChild + ' .kbx-bot-paragraph').html(function (i, html) {
                return html.replace(/(?:https:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?(.+)/g, '<iframe width="250" height="180" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>');
            });
        },
        enableEditor: function (placeHolder) {
            $("#kbx-bot-editor").attr('disabled', false).focus();
            $("#kbx-bot-editor").attr('placeholder', placeHolder);
            $("#kbx-bot-send-message").attr('disabled', false);
        },
        disableEditor: function (placeHolder) {
            $("#kbx-bot-editor").attr('placeholder', placeHolder);
            $("#kbx-bot-editor").attr('disabled', true);
            $("#kbx-bot-send-message").attr('disabled', true);
            //Remove extra pre loader.
            if ($('.kbx-bot-messages-container').find('.kbx-bot-comment-loader').length > 0) {
                $('.kbx-bot-messages-container').find('.kbx-bot-comment-loader').parent().parent().hide();
            }

        },
        scrollTo: function () {
            $(globalKbx.settings.botContainer).animate({scrollTop: $('.kbx-bot-messages-wrapper').prop("scrollHeight")}, 'slow');
            // $(globalKbx.settings.botContainer).animate({ scrollTop: $('#kbx-bot-messages-container').prop("scrollHeight")}, 'slow');
        },
        botPreloader: function () {
            var msgContent = '<li class="kbx-bot-msg">' +
                '<div class="kbx-bot-avatar">' +
                '<img src="' + globalKbx.settings.obj.agent_image_path + '" alt="">' +
                '</div>' +
                '<div class="kbx-bot-agent">' + globalKbx.settings.obj.agent + '</div>'
                + '<div class="kbx-bot-paragraph"><img class="kbx-bot-comment-loader" src="' + globalKbx.settings.obj.image_path + 'comment.gif" alt="Typing..." /></div></li>';
            return msgContent;
        },
        shopperMsgDom: function (msg) {
            if (globalKbx.hasNameCookie) {
                var shopper = globalKbx.hasNameCookie;
            } else {
                var shopper = globalKbx.settings.obj.shopper_demo_name;
            }
            var msgContent = '<li class="kbx-bot-user-msg">' +
                '<div class="kbx-bot-avatar">' +
                '<img src="' + globalKbx.settings.obj.image_path + 'client.png" alt="">' +
                '</div>' +
                '<div class="kbx-bot-agent">' + shopper + '</div>'
                + '<div class="kbx-bot-paragraph">' + msg + '</div></li>';
            return msgContent;
        },
        showCart: function () {
            var data = {'action': 'kbx_bot_show_cart'}
            this.ajax(data).done(function (response) {
                //if cart show on message board
                if ($('#kbx-bot-shortcode-template-container').length == 0) {
                    $('.kbx-bot-messages-wrapper').html(response.html);
                    $('#kbx-bot-cart-numbers').html(response.items);
                    kbxKits.disableEditor('Shopping Cart');
                } else {  //Cart show on shortcode
                    $('.kbx-bot-cart-shortcode-container').html(response.html);

                }
                //Add scroll to the cart shortcode
                if ($('#kbx-bot-shortcode-template-container').length > 0 && $('.kbx-bot-shortcode-template-02').length == 0) {
                    $('.kbx-bot-cart-body').slimScroll({height: '200px', start: 'bottom'});
                }
            });
        },
        randomMsg: function (arrMsg) {
            var index = Math.floor(Math.random() * arrMsg.length);
            return arrMsg[index];
        },
        toTitlecase: function (msg) {
            return msg.replace(/\w\S*/g, function (txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            });
        },
        filterStopWords: function (msg) {
            var spcialStopWords = ",;,/,\\,[,],{,},(,),&,*,.,+ ,?,^,$,=,!,<,>,|,:,-";
            var userMsg = "";
            //Removing Special Characts from last position.
            var msgLastChar = msg.slice(-1);
            if (spcialStopWords.indexOf(msgLastChar) >= 0) {
                userMsg = msg.slice(0, -1);
            } else {
                userMsg = msg;
            }
            var stopWords = globalKbx.settings.obj.stop_words + spcialStopWords;
            var stopWordsArr = stopWords.split(',');
            var msgArr = userMsg.split(' ');
            var filtermsgArr = msgArr.filter(function myCallBack(el) {
                return stopWordsArr.indexOf(el.toLowerCase()) < 0;
            });
            filterMsg = filtermsgArr.join(' ');
            return filterMsg;
        },
        htmlTagsScape: function (userString) {
            var tagsToReplace = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;'
            };
            return userString.replace(/[&<>]/g, function (tag) {
                return tagsToReplace[tag] || tag;
            });
        },
        cardResponse: function (title, subtitle, buttons, text, postback) {
            var card = '<div class="kbx-bot-card-title">' + title + '</div>';
            card += '<div class="kbx-bot-card-subtitle">' + subtitle + '</div>';
            var index = 0;
            for (index; index < buttons.length; index++) {
                card += '<span type="button" class="kbx-bot-card-button" card-target="' + buttons[index].postback + '">' + buttons[index].text + '</span>';
            }
            return card;
        },
        quickRepliesResponse: function (title, replies) {
            var quickRes = '<div class="kbx-bot-quick-replies-title">' + title + '</div>';

            var index = 0;
            for (index; index < replies.length; index++) {
                quickRes += '<input type="button" class="kbx-bot-quick-reply"  value="' + replies[index] + '"/>';
            }
            return quickRes;
        },
        imageResponse: function (imageUrl) {
            if (imageUrl != "") {
                var ImgRes = '<img src="' + imageUrl + '"/>';
                return ImgRes;
            }
        }

    }
    /*
     * kbxBot Trees are basically product,order and support
     * Article tree : to show the list of article based on the asking.
     * support tree : List of support query-answer including text & video and email to admin option.
     */
    var kbxTree = {
        greeting: function (msg) {
			
            if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
				
                //When intialize 1 and don't have cookies then keep  the name of shooper in in cookie
                if (globalKbx.initialize == 1 && !localStorage.getItem('shopper') && globalKbx.wildCard == 0 && globalKbx.ai_step == 0) {
					
					var main_text = msg;
                    msg=kbxKits.toTitlecase(msg);
					var dfReturns=kbxKits.dailogAIOAction(msg);
                    dfReturns.done(function( response ) {
                        
                        if(response.status.code==200){
							var intent = response.result.metadata.intentName;
							
							if(intent=="get name"){
								
								given_name = response.result.parameters.given_name;
								last_name = response.result.parameters.last_name;
								fullname = given_name+' '+last_name;
								if(fullname.length<2){
									fullname = msg
								}
								$.cookie("shopper", fullname, { expires : 365 });
								localStorage.setItem('shopper',fullname);
								globalKbx.hasNameCookie=fullname;
								//Greeting with name and suggesting the wildcard.
								var NameGreeting=kbxKits.randomMsg(globalKbx.settings.obj.i_am) +" <strong>"+globalKbx.settings.obj.agent+"</strong>! "+kbxKits.randomMsg(globalKbx.settings.obj.name_greeting)+", <strong>"+fullname+"</strong>!";
								var serviceOffer=kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
								//After completing two steps messaging showing wildcards.
								kbxMsg.double(NameGreeting,serviceOffer);
								globalKbx.ai_step=1;
								globalKbx.wildCard=0;
								localStorage.setItem("wildCard",  globalKbx.wildCard);
								localStorage.setItem("aiStep", globalKbx.ai_step);
								
							}else{
								
								/*
								msg = kbxKits.toTitlecase(msg);
								$.cookie("shopper", msg, {expires: 365});
								localStorage.setItem('shopper', msg);
								globalKbx.hasNameCookie = msg;
								// /Greeting with name and suggesting the wildcard.
								var NameGreeting = kbxKits.randomMsg(globalKbx.settings.obj.i_am) + " <strong>" + globalKbx.settings.obj.agent + "</strong>! " + kbxKits.randomMsg(globalKbx.settings.obj.name_greeting) + ", <strong>" + msg + "</strong>!";
								var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
								//After completing two steps messaging showing wildcards.
								kbxMsg.double(NameGreeting, serviceOffer);
								globalKbx.wildCard = 0;
								globalKbx.ai_step = 1;
								localStorage.setItem("wildCard", globalKbx.wildCard);
								localStorage.setItem("aiStep", globalKbx.ai_step);
								*/
								
								$.cookie("shopper", globalKbx.settings.obj.shopper_demo_name, { expires : 365 });
								localStorage.setItem('shopper',globalKbx.settings.obj.shopper_demo_name);
								globalKbx.hasNameCookie=globalKbx.settings.obj.shopper_demo_name;
								globalKbx.ai_step=1;
								globalKbx.wildCard=0;
								localStorage.setItem("wildCard",  globalKbx.wildCard);
								localStorage.setItem("aiStep", globalKbx.ai_step);
								kbxMsg.single(globalKbx.settings.obj.shopper_call_you+' '+globalKbx.settings.obj.shopper_demo_name);
								setTimeout(function(){
									var serviceOffer=kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
									kbxMsg.single(serviceOffer);
								},globalKbx.settings.preLoadingTime)
								
								
							}
						}
					})
					

					
                }
                //When returning shopper then greeting with name and wildcards.
                else if (localStorage.getItem('shopper') && globalKbx.wildCard == 0 && globalKbx.ai_step == 0) {
					
                    //else if (globalKbx.hasNameCookie && globalKbx.wildCard == 0) {
                    //After asking service show the wildcards.
                    var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
                    kbxMsg.single(serviceOffer);
					globalKbx.ai_step = 1;
                    localStorage.setItem("wildCard", globalKbx.wildCard);
                    localStorage.setItem("aiStep", globalKbx.ai_step);
                }
                //When user asking needs then DialogFlow will given intent after NLP steps.
                else if (globalKbx.wildCard == 0 && globalKbx.ai_step == 1) {
					
					
					
                    var dfReturns = kbxKits.dailogAIOAction(msg);
                    dfReturns.done(function (response) {
                        if (response.status.code == 200) {
                            var userIntent = response.result.metadata.intentName;
                            if (userIntent == 'start') {
                                globalKbx.wildCard = 0;
                                var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
                                kbxMsg.double_nobg(serviceOffer, globalKbx.wildcards);
                            } else if (userIntent == 'reset') {
                                var restWarning = globalKbx.settings.obj.reset;
                                var confirmBtn = '<span class="kbx-bot-reset-btn" reset-data="yes" >' + globalKbx.settings.obj.yes + '</span> <span> ' + globalKbx.settings.obj.or + ' </span><span class="kbx-bot-reset-btn"  reset-data="no">' + globalKbx.settings.obj.no + '</span>';
                                var backStart = '<span class="kbx-bot-wildcard" data-wildcart="back">' + kbxKits.randomMsg(globalKbx.settings.obj.back_start) + '</span>';
                                kbxMsg.double_nobg(restWarning, confirmBtn + backStart);
                            } else if (userIntent == 'article_search') {
                                var searchQuery = kbxKits.filterStopWords(response.result.resolvedQuery);
                                globalKbx.wildCard = 1;
                                globalKbx.articleStep = 'search'
                                kbxAction.bot(searchQuery);
                                //keeping value in localstorage
                                localStorage.setItem("wildCard", globalKbx.wildCard);
                                localStorage.setItem("articleStep", globalKbx.articleStep);
                            } else if (userIntent == 'article_list') {
                                kbxAction.bot(globalKbx.settings.obj.sys_key_catalog.toLowerCase());
                            }else if(userIntent=='get name'){
								
								given_name = response.result.parameters.given_name;
								last_name = response.result.parameters.last_name;
								fullname = given_name+' '+last_name;
								
								$.cookie("shopper", fullname, { expires : 365 });
								localStorage.setItem('shopper',fullname);
								globalKbx.hasNameCookie=fullname;
								//Greeting with name and suggesting the wildcard.
								var NameGreeting=kbxKits.randomMsg(globalKbx.settings.obj.i_am) +" <strong>"+globalKbx.settings.obj.agent+"</strong>! "+kbxKits.randomMsg(globalKbx.settings.obj.name_greeting)+", <strong>"+fullname+"</strong>!";
								var serviceOffer=kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
								//After completing two steps messaging showing wildcards.
								kbxMsg.double(NameGreeting,serviceOffer);
								globalKbx.ai_step=1;
								globalKbx.wildCard=0;
								localStorage.setItem("wildCard",  globalKbx.wildCard);
								localStorage.setItem("aiStep", globalKbx.ai_step);
								
							} 
							else if (userIntent == 'email') {
                                //kbxMsg.shopper_choice('Support');
                                //Then ask email address
                                if (typeof(globalKbx.hasNameCookie) == 'undefined' || globalKbx.hasNameCookie == '') {
                                    var shopperName = globalKbx.settings.obj.shopper_demo_name;
                                } else {
                                    var shopperName = globalKbx.hasNameCookie;
                                }
                                var askEmail = globalKbx.settings.obj.hello + ' ' + shopperName + '! ' + kbxKits.randomMsg(globalKbx.settings.obj.asking_email);
                                kbxMsg.single(askEmail);
                                //Now updating the support part and keeping value in localstorage
                                globalKbx.supportStep = 'email';
                                globalKbx.wildCard = 3;
                                localStorage.setItem("wildCard", globalKbx.wildCard);
                                localStorage.setItem("supportStep", globalKbx.supportStep);

                            } else if (response.result.score != 0) { // checking is reponsing from dialogflow.
                                if (response.result.action == "") {
                                    if (response.result.fulfillment.speech != "" && globalKbx.settings.obj.custom_intent_enable == 1) {
                                        //DialogFlow all defualt message will be printed.
                                        var DFMsg = response.result.fulfillment.speech;
                                        kbxMsg.single(DFMsg);
                                    } else if (response.result.fulfillment.speech == "" && response.result.fulfillment.hasOwnProperty('messages') && globalKbx.settings.obj.rich_response_enable == 1 && globalKbx.settings.obj.custom_intent_enable == 1) {
                                        //DialogFlow all defualt message will be printed.
                                        var DFMsg = "";
                                        var messages = response.result.fulfillment.messages;
                                        var numMessages = messages.length;
                                        var index = 0;
                                        for (index; index < numMessages; index++) {
                                            var message = messages[index];
                                            switch (message.type) {
                                                case 0: // For text response
                                                    DFMsg += message.speech;
                                                    break;
                                                case 1: // For card part
                                                    DFMsg += kbxKits.cardResponse(message.title, message.subtitle, message.buttons, message.text, message.postback);
                                                    break;
                                                case 2: // For quick replies
                                                    DFMsg += kbxKits.quickRepliesResponse(message.title, message.replies);
                                                    break;
                                                case 3: // For image response
                                                    DFMsg += kbxKits.imageResponse(message.imageUrl);
                                                    break;
                                                case 3: // custom payload

                                                    break;
                                                default:
                                            }
                                        }

                                        kbxMsg.single(DFMsg);
                                    } else if (globalKbx.settings.obj.disable_product_search != 1) {
                                        //Default is considered as product searching in the system if its not smalltalk && no respone message from DF
                                        var searchQuery = kbxKits.filterStopWords(response.result.resolvedQuery);
                                        globalKbx.wildCard = 1;
                                        globalKbx.articleStep = 'search'
                                        kbxAction.bot(searchQuery);
                                        //keeping value in localstorage
                                        localStorage.setItem("wildCard", globalKbx.wildCard);
                                        localStorage.setItem("articleStep", globalKbx.articleStep);
                                    } else {
                                        var dfDefaultMsg = globalKbx.settings.obj.df_defualt_reply;
                                        kbxMsg.single(dfDefaultMsg);
                                    }
                                } else if (response.result.action != "") {
                                    //Working for smalltalk
                                    var sTalkAction = response.result.action;
                                    var sTalkActionArr = sTalkAction.split('.');
                                    if (sTalkActionArr[0] == 'smalltalk') {
                                        var sMgs = response.result.fulfillment.speech;
                                        kbxMsg.single(sMgs);
                                    } else {
                                        var searchQuery = kbxKits.filterStopWords(response.result.resolvedQuery);
                                        globalKbx.wildCard = 1;
                                        globalKbx.articleStep = 'search'
                                        kbxAction.bot(searchQuery);
                                        //keeping value in localstorage
                                        localStorage.setItem("wildCard", globalKbx.wildCard);
                                        localStorage.setItem("articleStep", globalKbx.articleStep);
                                    }
                                }

                            } else {
                                var searchQuery = kbxKits.filterStopWords(response.result.resolvedQuery);
                                globalKbx.wildCard = 1;
                                globalKbx.articleStep = 'search'
                                kbxAction.bot(searchQuery);
                                //keeping value in localstorage
                                localStorage.setItem("wildCard", globalKbx.wildCard);
                                localStorage.setItem("articleStep", globalKbx.articleStep);
                            }
                        } else {
                            //if bad request or limit cross then
                            globalKbx.df_status_lock = 1;
                            var dfDefaultMsg = globalKbx.settings.obj.df_defualt_reply;
                            kbxMsg.double_nobg(dfDefaultMsg, globalKbx.wildcards);
                        }
                    }).fail(function (error) {
                        var dfDefaultMsg = globalKbx.settings.obj.df_defualt_reply;
                        kbxMsg.double_nobg(dfDefaultMsg, globalKbx.wildcards);
                    });
                }
            } else {
                if (globalKbx.initialize == 1 && !localStorage.getItem('shopper') && globalKbx.wildCard == 0) {
                    msg = kbxKits.toTitlecase(msg);
                    $.cookie("shopper", msg, {expires: 365});
                    localStorage.setItem('shopper', msg);
                    globalKbx.hasNameCookie = msg;
                    // /Greeting with name and suggesting the wildcard.
                    var NameGreeting = kbxKits.randomMsg(globalKbx.settings.obj.i_am) + " <strong>" + globalKbx.settings.obj.agent + "</strong>! " + kbxKits.randomMsg(globalKbx.settings.obj.name_greeting) + ", <strong>" + msg + "</strong>!";
                    var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
                    //After completing two steps messaging showing wildcards.
                    kbxMsg.double(NameGreeting, serviceOffer);

                    setTimeout(function () {
                        if (globalKbx.wildcards != "") {
                            kbxMsg.single_nobg(globalKbx.wildcards);
                        }
                        globalKbx.wildCard = 1;
                        globalKbx.articleStep = 'search';
                        localStorage.setItem("wildCard", globalKbx.wildCard);
                        localStorage.setItem("articleStep", globalKbx.articleStep);
                        //console.log(globalKbx.wildCard, globalKbx.articleStep);
                    }, parseInt(globalKbx.settings.preLoadingTime * 2.5));
                }
                //When returning shopper then greeting with name and wildcards.
                else if (localStorage.getItem('shopper') && globalKbx.wildCard == 0) {
                    //else if (globalKbx.hasNameCookie && globalKbx.wildCard == 0) {
                    //After asking service show the wildcards.
                    var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
                    kbxMsg.double_nobg(serviceOffer, globalKbx.wildcards);
                    globalKbx.wildCard = 1;
                    globalKbx.articleStep = 'search';
                    localStorage.setItem("wildCard", globalKbx.wildCard);
                    localStorage.setItem("articleStep", globalKbx.articleStep);
                }
            }
        },
        article: function (msg) {
			console.log(msg);
            if (globalKbx.wildCard == 1 && globalKbx.articleStep == 'search') {
                var data = {'action': 'kbx_bot_keyword', 'keyword': msg};
                //Products by string search ajax handler.
                kbxKits.ajax(data).done(function (response) {
                    //console.log(response);
                    if (response.articles_num == 0) {
                        //var productFail=kbxKits.randomMsg(globalKbx.settings.obj.product_fail)+" <strong>"+msg+"</strong>!";
                        //var productSuggest=kbxKits.randomMsg(globalKbx.settings.obj.product_suggest);
                        var articleFail = kbxKits.randomMsg(globalKbx.settings.obj.articles_fail);
                        var catalogSuggest = kbxKits.randomMsg(globalKbx.settings.obj.catalog_suggest);
                        kbxMsg.double(articleFail, catalogSuggest);

                        //Suggesting category.
                        setTimeout(function () {
                            kbxKits.sugestCat();
                        }, parseInt(globalKbx.settings.preLoadingTime * 2.1));

                    } else {
                        var findMore = '<span class="kbx-bot-find-more">' + kbxKits.randomMsg(globalKbx.settings.obj.find_more) + '</span>';
                        var articleSucces = kbxKits.randomMsg(globalKbx.settings.obj.articles_success)+" <strong>"+msg+"</strong>";
                        kbxMsg.double_nobg(articleSucces, response.html + findMore);
                    }
                });
				globalKbx.wildCard = 0;
            } else if (globalKbx.wildCard == 1 && globalKbx.articleStep == 'category') {
                var msg = msg.split("#");
                var categoryTitle = msg[0];
                var categoryId = msg[1];
                var data = {'action': 'kbx_bot_category_articles', 'category': categoryId};
                //Product by category ajax handler.
                kbxKits.ajax(data).done(function (response) {
                    if (response.product_num == 0) {
                        //Since product does not found then show message and suggesting infinity search
                        var productFail = kbxKits.randomMsg(globalKbx.settings.obj.articles_fail) + " <strong>" + categoryTitle + "</strong>!";
                        var searchAgain = kbxKits.randomMsg(globalKbx.settings.obj.articles_infinite);
                        kbxMsg.double(productFail, searchAgain);

                        //For handle the ai and alone
                        if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
                            globalKbx.wildCard = 0;
                            globalKbx.ai_step = 1;
                            //keeping value in localstorage
                            localStorage.setItem("wildCard", globalKbx.wildCard);
                            localStorage.setItem("aiStep", globalKbx.ai_step);
                        } else {
                            globalKbx.wildCard = 1;
                            globalKbx.articleStep = 'search'
                            //keeping value in localstorage
                            localStorage.setItem("wildCard", globalKbx.wildCard);
                            localStorage.setItem("articleStep", globalKbx.articleStep);
                        }
                    } else {
                        //Now show chat message to choose the product.
                        var productSuccess = kbxKits.randomMsg(globalKbx.settings.obj.articles_success) + " <strong>" + categoryTitle + "</strong>!";
                        var products = response.html;
                        kbxMsg.double_nobg(productSuccess, products);
                        //For handle the ai and alone
                        if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
                            globalKbx.wildCard = 0;
                            globalKbx.ai_step = 1;
                            //keeping value in localstorage
                            localStorage.setItem("wildCard", globalKbx.wildCard);
                            localStorage.setItem("aiStep", globalKbx.ai_step);
                        } else {
                            globalKbx.wildCard = 1;
                            globalKbx.articleStep = 'search'
                            //keeping value in localstorage
                            localStorage.setItem("wildCard", globalKbx.wildCard);
                            localStorage.setItem("articleStep", globalKbx.articleStep);
                        }
                    }
                });
            }
        },
        support: function (msg) {
            if (globalKbx.wildCard == 3 && globalKbx.supportStep == 'welcome') {
                var welcomeMsg = kbxKits.randomMsg(globalKbx.settings.obj.support_welcome);
                kbxMsg.single(welcomeMsg);
                setTimeout(function () {
                    kbxKits.suggestEmail();
                }, parseInt(globalKbx.settings.wildcardsShowTime / 2));
            } else if (globalKbx.wildCard == 3 && globalKbx.supportStep == 'email') {

                globalKbx.shopperEmail = msg;
                var validate = "";
                var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                if (re.test(globalKbx.shopperEmail) != true) {
                    validate = validate + kbxKits.randomMsg(globalKbx.settings.obj.invalid_email);
                }
                if (validate == "") {
                    var askingMsg = kbxKits.randomMsg(globalKbx.settings.obj.asking_msg);
                    kbxMsg.single(askingMsg);
                    globalKbx.supportStep = 'message';
                    //keeping value in localstorage
                    localStorage.setItem("supportStep", globalKbx.supportStep);

                } else {
                    kbxMsg.single(validate);
                    globalKbx.supportStep = 'email';
                    //keeping value in localstorage
                    localStorage.setItem("supportStep", globalKbx.supportStep);
                }
            } else if (globalKbx.wildCard == 3 && globalKbx.supportStep == 'message') {
                var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
                var data = {
                    'action': 'kbx_bot_support_email',
                    'name': globalKbx.hasNameCookie,
                    'email': globalKbx.shopperEmail,
                    'message': msg
                };
                kbxKits.ajax(data).done(function (response) {
                    //var json = $.parseJSON(response);
                    var json = response;
                    if (json.status == 'success') {
                        var sucMsg = json.message;
                        kbxMsg.single(sucMsg);
                        //Asking email after showing answer.
                        setTimeout(function () {
                            kbxMsg.single(serviceOffer);
                            //For handle the ai and alone
                            if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
                                globalKbx.wildCard = 0;
                                globalKbx.ai_step = 1;
                                //keeping value in localstorage
                                localStorage.setItem("wildCard", globalKbx.wildCard);
                                localStorage.setItem("aiStep", globalKbx.ai_step);
                            } else {
                                globalKbx.wildCard = 1;
                                globalKbx.articleStep = 'search'
                                //keeping value in localstorage
                                localStorage.setItem("wildCard", globalKbx.wildCard);
                                localStorage.setItem("articleStep", globalKbx.articleStep);
                            }
                        }, globalKbx.settings.preLoadingTime);
                    } else {
                        var failMsg = json.message;
                        kbxMsg.single(failMsg);
                        //Asking email after showing answer.
                        setTimeout(function () {
                            kbxMsg.single(serviceOffer);
                            //For handle the ai and alone
                            if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
                                globalKbx.wildCard = 0;
                                globalKbx.ai_step = 1;
                                //keeping value in localstorage
                                localStorage.setItem("wildCard", globalKbx.wildCard);
                                localStorage.setItem("aiStep", globalKbx.ai_step);
                            } else {
                                globalKbx.wildCard = 1;
                                globalKbx.articleStep = 'search'
                                //keeping value in localstorage
                                localStorage.setItem("wildCard", globalKbx.wildCard);
                                localStorage.setItem("articleStep", globalKbx.articleStep);
                            }
                        }, globalKbx.settings.preLoadingTime);
                    }
                });
            } else if (globalKbx.wildCard == 3 && globalKbx.supportStep == 'phone') {

                var data = {'action': 'kbx_bot_support_phone', 'name': globalKbx.hasNameCookie, 'phone': msg};
                kbxKits.ajax(data).done(function (response) {
                    //var json = $.parseJSON(response);
                    var json = response;
                    if (json.status == 'success') {
                        var sucMsg = json.message;
                        kbxMsg.single(sucMsg);
                        //Asking email after showing answer.
                        setTimeout(function () {
                            var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
                            kbxMsg.single(serviceOffer);
                            setTimeout(function () {
                                if (globalKbx.wildcards != "") {
                                    kbxMsg.single_nobg(globalKbx.wildcards);
                                }
                                //For handle the ai and alone
                                if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
                                    globalKbx.wildCard = 0;
                                    globalKbx.ai_step = 1;
                                    //keeping value in localstorage
                                    localStorage.setItem("wildCard", globalKbx.wildCard);
                                    localStorage.setItem("aiStep", globalKbx.ai_step);
                                } else {
                                    globalKbx.wildCard = 1;
                                    globalKbx.articleStep = 'search'
                                    //keeping value in localstorage
                                    localStorage.setItem("wildCard", globalKbx.wildCard);
                                    localStorage.setItem("articleStep", globalKbx.articleStep);
                                }
                            }, parseInt(globalKbx.settings.preLoadingTime));
                        }, globalKbx.settings.preLoadingTime);
                    } else {
                        var failMsg = json.message;
                        kbxMsg.single(failMsg);
                        //Asking email after showing answer.
                        setTimeout(function () {
                            var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
                            kbxMsg.single(serviceOffer);
                            setTimeout(function () {
                                if (globalKbx.wildcards != "") {
                                    kbxMsg.single_nobg(globalKbx.wildcards);
                                }
                                //For handle the ai and alone
                                if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
                                    globalKbx.wildCard = 0;
                                    globalKbx.ai_step = 1;
                                    //keeping value in localstorage
                                    localStorage.setItem("wildCard", globalKbx.wildCard);
                                    localStorage.setItem("aiStep", globalKbx.ai_step);
                                } else {
                                    globalKbx.wildCard = 1;
                                    globalKbx.articleStep = 'search'
                                    //keeping value in localstorage
                                    localStorage.setItem("wildCard", globalKbx.wildCard);
                                    localStorage.setItem("articleStep", globalKbx.articleStep);
                                }
                            }, parseInt(globalKbx.settings.preLoadingTime));
                        }, globalKbx.settings.preLoadingTime);
                    }
                });
            }
        }
    };
    /*
     * kbxBot Actions are divided into two part
     * shopper will response after initialize message,
     * then based on shopper activities shopper will act.
     */
    var kbxAction = {
        bot: function (msg) {
            
            //Disable the Editor
            kbxKits.disableEditor(globalKbx.settings.obj.agent + ' ' + kbxKits.randomMsg(globalKbx.settings.obj.is_typing));
            if (globalKbx.wildcardsHelp.indexOf(msg) > -1) {

                if (msg == globalKbx.settings.obj.sys_key_help.toLowerCase()) {   //start
                    globalKbx.wildCard = 1;
                    globalKbx.articleStep = 'search';
                    //keeping wildcard and steps in localstorage
                    localStorage.setItem("wildCard", globalKbx.wildCard);
                    localStorage.setItem("articleStep", globalKbx.articleStep);
                    var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
                    kbxMsg.single(serviceOffer);
                    setTimeout(function () {
                        if (globalKbx.wildcards != "") {
                            kbxMsg.single_nobg(globalKbx.wildcards);
                        }
                    }, parseInt(globalKbx.settings.preLoadingTime));
                }
                if (msg == globalKbx.settings.obj.sys_key_catalog.toLowerCase()) {
                    globalKbx.wildCard = 1;
                    globalKbx.articleStep = 'search';
                    //keeping wildcard and steps in localstorage
                    localStorage.setItem("wildCard", globalKbx.wildCard);
                    localStorage.setItem("articleStep", globalKbx.articleStep);
                    kbxKits.sugestCat();
                }
                if (msg == globalKbx.settings.obj.sys_key_support.toLowerCase()) {
                    //Then ask email address
                    if (typeof(globalKbx.hasNameCookie) == 'undefined' || globalKbx.hasNameCookie == '') {
                        var shopperName = globalKbx.settings.obj.shopper_demo_name;
                    } else {
                        var shopperName = globalKbx.hasNameCookie;
                    }
                    var askEmail = globalKbx.settings.obj.hello + ' ' + shopperName + '! ' + kbxKits.randomMsg(globalKbx.settings.obj.asking_email);
                    kbxMsg.single(askEmail);
                    //Now updating the support part and keeping value in localstorage
                    globalKbx.supportStep = 'email';
                    globalKbx.wildCard = 3;
                    localStorage.setItem("wildCard", globalKbx.wildCard);
                    localStorage.setItem("supportStep", globalKbx.supportStep);
                }
                if (msg == globalKbx.settings.obj.sys_key_reset.toLowerCase()) {
                    var restWarning = globalKbx.settings.obj.reset;
                    var confirmBtn = '<span class="kbx-bot-reset-btn" reset-data="yes" type="button">' + globalKbx.settings.obj.yes + '</span> <span> ' + globalKbx.settings.obj.or + ' </span><span class="kbx-bot-reset-btn" type="button" reset-data="no">' + globalKbx.settings.obj.no + '</span>';
                    var backStart = '<span class="kbx-bot-wildcard" data-wildcart="back">' + kbxKits.randomMsg(globalKbx.settings.obj.back_start) + '</span>';
                    kbxMsg.double_nobg(restWarning, confirmBtn + backStart);
                    //dialogFlow
                    globalKbx.wildCard = 0;
                    globalKbx.ai_step = 1;
                    localStorage.setItem("wildCard", globalKbx.wildCard);
                    localStorage.setItem("aiStep", globalKbx.ai_step);
                }

            } else {
                /*
                 *   Greeting part
                 *   bot action
                 */
				 
                if (globalKbx.wildCard == 0) {
                    kbxTree.greeting(msg);
                }
                /*
                 *   Article Search part
                 *   bot action
                 */
                if (globalKbx.wildCard == 1) {
                    kbxTree.article(msg);
                }
                /*
                 *   support part
                 *   bot action
                 */
                if (globalKbx.wildCard == 3) {
                    kbxTree.support(msg);
                }

            }
        },
        shopper: function (msg) {
            kbxMsg.shopper(msg);
            if (globalKbx.wildCard == 1 && globalKbx.articleStep == "search") {
                this.bot(msg);
            } else if (globalKbx.wildCard == 3) {
                this.bot(msg);
            } else if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.wildCard == 0 && globalKbx.ai_step == 1 && globalKbx.df_status_lock == 0) {
                this.bot(msg);
            } else {
                //Filtering the user given messages by stopwords
                var spcialStopWords = ",;,/,\\,[,],{,},(,),&,*,.,+ ,?,^,$,=,!,<,>,|,:,-";
                var userMsg = "";
                //Removing Special Characts from last position.
                var msgLastChar = msg.slice(-1);
                if (spcialStopWords.indexOf(msgLastChar) >= 0) {
                    userMsg = msg.slice(0, -1);
                } else {
                    userMsg = msg;
                }
                var stopWords = globalKbx.settings.obj.stop_words + spcialStopWords;
                var stopWordsArr = stopWords.split(',');
                var msgArr = userMsg.split(' ');
                var filtermsgArr = msgArr.filter(function myCallBack(el) {
                    return stopWordsArr.indexOf(el.toLowerCase()) < 0;
                });
                filterMsg = filtermsgArr.join(' ');
                //handle empty filterMsg as repeat the message.
                if (filterMsg == "") {
                    if (globalKbx.emptymsghandler == 0) {
                        globalKbx.repeatQueryEmpty = kbxKits.randomMsg(globalKbx.settings.obj.empty_filter_msg) + ' "' + $(globalKbx.settings.messageLastBot).text() + '"';
                        globalKbx.emptymsghandler++;
                    }
                    kbxMsg.single(globalKbx.repeatQueryEmpty);
                } else {
                    globalKbx.emptymsghandler = 0;
                    this.bot(filterMsg);
                }
            }
            //Keeping the chat history in localStorage
            setTimeout(function () {
                var kbxBotHistory = $(globalKbx.settings.messageWrapper).html();
                localStorage.setItem("kbxBotHistory", kbxBotHistory);
            }, 5000);
        }
    };

    /*
     * kbxBot Plugin Creation without selector and
     * kbxBot and shoppers all activities will be handled.
     */
    $.kbxbot = function (options) {

        //Using plugins defualts values or overwrite by options.
        var settings = $.extend({}, $.kbxbot.defaults, options);

        //Updating global settings
        globalKbx.settings = settings;
        //updating the helpkeywords
        globalKbx.wildcardsHelp = [globalKbx.settings.obj.sys_key_help.toLowerCase(), globalKbx.settings.obj.sys_key_catalog.toLowerCase(), globalKbx.settings.obj.sys_key_support.toLowerCase(), globalKbx.settings.obj.sys_key_reset.toLowerCase()]

        //updating wildcards
        globalKbx.wildcards = '';
        if (globalKbx.settings.obj.disable_article_search != 1) {
            globalKbx.wildcards = '<span class="kbx-bot-wildcard" data-wildcart="artilces">' + kbxKits.randomMsg(globalKbx.settings.obj.wildcard_artilces) + '</span>';
        }
        if (globalKbx.settings.obj.disable_article_list != 1) {
            globalKbx.wildcards += '<span class="kbx-bot-wildcard" data-wildcart="list">' + kbxKits.randomMsg(globalKbx.settings.obj.wildcard_list) + '</span>';
        }
        if (globalKbx.settings.obj.disable_support != 1) {
            globalKbx.wildcards += '<span class="kbx-bot-wildcard" data-wildcart="support">' + kbxKits.randomMsg(globalKbx.settings.obj.wildcard_support) + '</span>';
        }
        if (globalKbx.settings.obj.disable_call_me != 1) {
            globalKbx.wildcards += '<span class="kbx-bot-wildcard" data-wildcart="phone">' + kbxKits.randomMsg(globalKbx.settings.obj.wildcard_phone) + '</span>';
        }
        //Initialize the kbxbot with greeting and if already initialize and given name then return greeting..
        if (localStorage.getItem("kbxBotHistory") && globalKbx.initialize == 0) {
            var kbxBotHistory = localStorage.getItem("kbxBotHistory");
            $(globalKbx.settings.messageContainer).html(kbxBotHistory);
            //Scroll to the last element.
            kbxKits.scrollTo();
            if (localStorage.getItem("wildCard")) {
                globalKbx.wildCard = localStorage.getItem("wildCard");
            }
            if (localStorage.getItem("articleStep")) {
                globalKbx.articleStep = localStorage.getItem("articleStep");
            }
            if (localStorage.getItem("supportStep")) {
                globalKbx.supportStep = localStorage.getItem("supportStep");
            }
            //update the value for initializing.
            globalKbx.initialize = 1;

        } else {
            if (globalKbx.initialize == 0 && globalKbx.wildCard == 0) {
                kbxWelcome.greeting();
                //update the value for initializing.
                globalKbx.initialize = 1;
                //keeping the chat history in local storage.
                setTimeout(function () {
                    var kbxBotHistory = $(globalKbx.settings.messageWrapper).html();
                    localStorage.setItem("kbxBotHistory", kbxBotHistory);
                }, 4000);

            }
        }
        //When shopper click on send button
        $(document).on('click', settings.sendButton, function (e) {
            var shopperMsg = $(settings.messageEditor).val();
            if (shopperMsg != "") {
                kbxAction.shopper(kbxKits.htmlTagsScape(shopperMsg));
                $(settings.messageEditor).val('');
            }
        });

        /*
         * Or when shopper press the ENTER key
         * Then chatting functionality will be started.
         */
        $(document).on('keypress', settings.messageEditor, function (e) {

            if (e.which == 13 || e.keyCode == 13) {
                e.preventDefault();
                var shopperMsg = $(settings.messageEditor).val();
                if (shopperMsg != "") {
                    kbxAction.shopper(kbxKits.htmlTagsScape(shopperMsg));
                    $(settings.messageEditor).val('');
                }

            }
        });
        //DialogFlow richresponse click
        $(document).on('click', '.kbx-bot-card-button', function (e) {
            var PostBack = $(this).attr('card-target');
            kbxAction.bot(PostBack);
        });
        //Articles search result accordion.
        $(document).on('click', '.kbx-bot-search-article-title', function (event) {
            event.preventDefault();
            if ($(this).parent().parent().hasClass('kbx-article-accordion-close')) {
                $(this).parent().parent().removeClass('kbx-article-accordion-close');
                $(this).parent().parent().addClass('kbx-article-accordion-open');
            } else {
                $(this).parent().parent().addClass('kbx-article-accordion-close');
                $(this).parent().parent().removeClass('kbx-article-accordion-open');
            }
            // $('.kbx-bot-articles-area ul li').removeClass('kbx-article-accordion-open').addClass('kbx-article-accordion-close');
            // if($(this).parent().parent().hasClass('kbx-article-accordion-close')){
            //     $(this).parent().parent().toggleClass('kbx-article-accordion-close');
            //    $(this).parent().parent().toggleClass('kbx-article-accordion-open');
            // }
        });
        //Article Load More features for product search or category products
        $(document).on('click', '.kbx-bot-find-more', function (e) {
            $('#kbx-bot-loadmore-loader').html('<img class="kbx-bot-comment-loader" src="' + globalKbx.settings.obj.image_path + 'loadmore.gif" alt="..." />');
            var data = {'action': 'kbx_bot_category'};
            var result = kbxKits.ajax(data);
            result.done(function (response) {
                kbxMsg.double_nobg(globalKbx.settings.obj.find_more_msg, response);
                //Updating & keeping steps and wildcard in localstorage
                globalKbx.articleStep = 'category';
                localStorage.setItem("articleStep", globalKbx.articleStep);
                setTimeout(function () {
                    if (globalKbx.articleStep == 'category') {
                        
						 if (globalKbx.settings.obj.disable_support != 1) {
						var emailSuggMsg = kbxKits.randomMsg(globalKbx.settings.obj.support_email);
                        var confirmBtn = '<span class="kbx-bot-suggest-email"  type="button">' + globalKbx.settings.obj.yes + '</span> <span> ' + globalKbx.settings.obj.or + ' </span><span class="kbx-bot-reset-btn" type="button" reset-data="no">' + globalKbx.settings.obj.no + '</span>';
                        var backStart = '<span class="kbx-bot-wildcard" data-wildcart="back">' + kbxKits.randomMsg(globalKbx.settings.obj.back_start) + '</span>';
                        kbxMsg.double_nobg(emailSuggMsg, confirmBtn + backStart);
						 }else{
							 var backStart = '<span class="kbx-bot-wildcard" data-wildcart="back">' + kbxKits.randomMsg(globalKbx.settings.obj.back_start) + '</span>';
							kbxMsg.single(backStart);
						 }
						
                        //For handle the ai and alone
                        if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
                            globalKbx.wildCard = 0;
                            globalKbx.ai_step = 1;
                            //keeping value in localstorage
                            localStorage.setItem("wildCard", globalKbx.wildCard);
                            localStorage.setItem("aiStep", globalKbx.ai_step);
                        } else {
                            globalKbx.wildCard = 1;
                            globalKbx.articleStep = 'search'
                            //keeping value in localstorage
                            localStorage.setItem("wildCard", globalKbx.wildCard);
                            localStorage.setItem("articleStep", globalKbx.articleStep);
                        }
                    }
                }, globalKbx.settings.wildcardsShowTime);
            });
        });
        //Find more thens show category list.
        $(document).on('click', '#kbx-bot-loadmore', function (e) {
            $('#kbx-bot-loadmore-loader').html('<img class="kbx-bot-comment-loader" src="' + globalKbx.settings.obj.image_path + 'loadmore.gif" alt="..." />');
            var loadMoreDom = $(this);
            var artilesOffest = loadMoreDom.attr('data-offset');
            var searchType = loadMoreDom.attr('data-search-type');
            var searchTerm = loadMoreDom.attr('data-search-term');
            var data = {
                'action': 'kbx_bot_load_more',
                'offset': artilesOffest,
                'search_type': searchType,
                'search_term': searchTerm
            };
            //Load more ajax handler.
            kbxKits.ajax(data).done(function (response) {
                //Change button text
                $('#kbx-bot-loadmore-loader').html('');
                $('.articleList').append(response.html);
                loadMoreDom.attr('data-offset', response.offset);
                if (response.articles_num < response.per_page) {
                    loadMoreDom.hide();
                }
                //scroll to the last message
                kbxKits.scrollTo();
            });
        });
        //Articles will be shown for corresponding category.
        $(document).on('click', '.kbx-bot-article-category', function () {
            var shopperChoiceCatId = $(this).text() + '#' + $(this).attr('data-category-id');
            var shopperChoiceCategory = $(this).text();

            //keeping value in localstorage
            globalKbx.wildCard = 1;
            localStorage.setItem("wildCard", globalKbx.wildCard);
            globalKbx.articleStep = 'category';
            localStorage.setItem("articleStep", globalKbx.articleStep);
            
            //Now hide all categories but shopper choice.
            kbxMsg.shopper_choice(shopperChoiceCategory);
            kbxAction.bot(shopperChoiceCatId);
        });
        /*Support Email **/
        $(document).on('click', '.kbx-bot-suggest-email', function (e) {
            var shopperChoice = $(this).text();
            kbxMsg.shopper_choice(shopperChoice);
            //Then ask email address
            if (typeof(globalKbx.hasNameCookie) == 'undefined' || globalKbx.hasNameCookie == '') {
                var shopperName = globalKbx.settings.obj.shopper_demo_name;
            } else {
                var shopperName = globalKbx.hasNameCookie;
            }
            var askEmail = globalKbx.settings.obj.hello + ' ' + shopperName + '! ' + kbxKits.randomMsg(globalKbx.settings.obj.asking_email);
            kbxMsg.single(askEmail);
            //Now updating the support part and keeping value in localstorage
            globalKbx.supportStep = 'email';
            globalKbx.wildCard = 3;
            localStorage.setItem("wildCard", globalKbx.wildCard);
            localStorage.setItem("supportStep", globalKbx.supportStep);
        });
        //Show ,cart and recently view products by click event.
        $(document).on('click', '.kbx-bot-operation-option', function (e) {
            e.preventDefault();
            var oppt = $(this).attr('data-option');
            if (oppt == 'chat' && globalKbx.kbxIsWorking == 0) {
                $('.kbx-bot-messages-wrapper').html(localStorage.getItem("kbxBotHistory"));
                kbxKits.enableEditor('Send Message');
                //First remove kbx-bot-operation-active class from all selector
                $('.kbx-bot-operation-option').parent().removeClass('kbx-bot-operation-active');
                //then add the active class to current element.
                $(this).parent().addClass('kbx-bot-operation-active');
            } else if (oppt == 'help' && globalKbx.kbxIsWorking == 0) {
                if ($('.kbx-bot-messages-container').length == 0) {
                    //if from other nob then goo to the chat window
                    $('.kbx-bot-messages-wrapper').html(localStorage.getItem("kbxBotHistory"));
                    //Showing help message
                    setTimeout(function () {
                        kbxKits.scrollTo();
                        var helpWelcome = kbxKits.randomMsg(globalKbx.settings.obj.help_welcome);
                        var helpMsg = kbxKits.randomMsg(globalKbx.settings.obj.help_msg);
                        kbxMsg.double(helpWelcome, helpMsg);
                    }, globalKbx.settings.preLoadingTime);
                } else {
                    //Showing help message on chat self window.
                    var helpWelcome = kbxKits.randomMsg(globalKbx.settings.obj.help_welcome);
                    var helpMsg = kbxKits.randomMsg(globalKbx.settings.obj.help_msg);
                    kbxMsg.double(helpWelcome, helpMsg);
                }
                //First remove kbx-bot-operation-active class from all selector
                $('.kbx-bot-operation-option').parent().removeClass('kbx-bot-operation-active');
                //then add the active class to current element.
                $(this).parent().addClass('kbx-bot-operation-active');

            } else if (oppt == 'support' && globalKbx.kbxIsWorking == 0) {
                if ($('.kbx-bot-messages-container').length == 0) {
                    //if from other nob then goo to the chat window
                    $('.kbx-bot-messages-wrapper').html(localStorage.getItem("kbxBotHistory"));
                    //Showing help message
                    setTimeout(function () {
                        kbxKits.scrollTo();
                        //Then ask email address
                        if (typeof(globalKbx.hasNameCookie) == 'undefined' || globalKbx.hasNameCookie == '') {
                            var shopperName = globalKbx.settings.obj.shopper_demo_name;
                        } else {
                            var shopperName = globalKbx.hasNameCookie;
                        }
                        var askEmail = globalKbx.settings.obj.hello + ' ' + shopperName + '! ' + kbxKits.randomMsg(globalKbx.settings.obj.asking_email);
                        kbxMsg.single(askEmail);
                        //Now updating the support part and keeping value in localstorage
                        globalKbx.supportStep = 'email';
                        globalKbx.wildCard = 3;
                        localStorage.setItem("wildCard", globalKbx.wildCard);
                        localStorage.setItem("supportStep", globalKbx.supportStep);
                    }, globalKbx.settings.preLoadingTime);
                } else {
                    //Then ask email address
                    if (typeof(globalKbx.hasNameCookie) == 'undefined' || globalKbx.hasNameCookie == '') {
                        var shopperName = globalKbx.settings.obj.shopper_demo_name;
                    } else {
                        var shopperName = globalKbx.hasNameCookie;
                    }
                    var askEmail = globalKbx.settings.obj.hello + ' ' + shopperName + '! ' + kbxKits.randomMsg(globalKbx.settings.obj.asking_email);
                    kbxMsg.single(askEmail);
                    //Now updating the support part and keeping value in localstorage
                    globalKbx.supportStep = 'email';
                    globalKbx.wildCard = 3;
                    localStorage.setItem("wildCard", globalKbx.wildCard);
                    localStorage.setItem("supportStep", globalKbx.supportStep);
                }

                //First remove kbx-bot-operation-active class from all selector
                $('.kbx-bot-operation-option').parent().removeClass('kbx-bot-operation-active');
                //then add the active class to current element.
                $(this).parent().addClass('kbx-bot-operation-active');
            }

        });
        //reset conversation history.
        $(document).on('click', '.kbx-bot-reset-btn', function (e) {
            e.preventDefault();
            var actionType = $(this).attr('reset-data');
            if (actionType == 'yes') {
                $('#kbx-bot-messages-container').html('');
                $.removeCookie('shopper');
                globalKbx.wildCard = 0;
                globalKbx.ai_step = 0;
                globalKbx.initialize = 1;
                //keeping wildcard and steps in localstorage
                localStorage.removeItem("shopper");
                localStorage.setItem("wildCard", globalKbx.wildCard);
                localStorage.setItem("aiStep", globalKbx.ai_step);
                kbxWelcome.greeting();
            } else if (actionType == 'no') {
                kbxAction.bot(globalKbx.settings.obj.sys_key_help.toLowerCase());
            }
        });
        //Click on the wildcards
        //Click on the wildcards to select a service
        $(document).on('click', '.kbx-bot-wildcard', function () {
            var wildcardData = $(this).attr('data-wildcart');
            var shooperChoice = $(this).text();
            kbxMsg.shopper_choice(shooperChoice);
            //Wild cards handling for bot.
            if (wildcardData == 'artilces') {
                globalKbx.wildCard = 1;
                globalKbx.articleStep = 'search';
                //keeping wildcard and steps in localstorage
                localStorage.setItem("wildCard", globalKbx.wildCard);
                localStorage.setItem("articleStep", globalKbx.articleStep);
                var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.articles_search_msg);
                kbxMsg.single(serviceOffer);
            }
            if (wildcardData == 'list') {
                globalKbx.wildCard = 1;
                globalKbx.articleStep = 'search';
                //keeping wildcard and steps in localstorage
                localStorage.setItem("wildCard", globalKbx.wildCard);
                localStorage.setItem("articleStep", globalKbx.articleStep);
                kbxKits.sugestCat();
            }
            if (wildcardData == 'support') {
                kbxMsg.shopper_choice(shooperChoice);
                //Then ask email address
                if (typeof(globalKbx.hasNameCookie) == 'undefined' || globalKbx.hasNameCookie == '') {
                    var shopperName = globalKbx.settings.obj.shopper_demo_name;
                } else {
                    var shopperName = globalKbx.hasNameCookie;
                }
                var askEmail = globalKbx.settings.obj.hello + ' ' + shopperName + '! ' + kbxKits.randomMsg(globalKbx.settings.obj.asking_email);
                kbxMsg.single(askEmail);
                //Now updating the support part and keeping value in localstorage
                globalKbx.supportStep = 'email';
                globalKbx.wildCard = 3;
                localStorage.setItem("wildCard", globalKbx.wildCard);
                localStorage.setItem("supportStep", globalKbx.supportStep);
            }
            if (wildcardData == 'phone') {
                //Then ask email address
                if (typeof(globalKbx.hasNameCookie) == 'undefined' || globalKbx.hasNameCookie == '') {
                    var shopperName = globalKbx.settings.obj.shopper_demo_name;
                } else {
                    var shopperName = globalKbx.hasNameCookie;
                }
                var askEmail = globalKbx.settings.obj.hello + ' ' + shopperName + '! ' + kbxKits.randomMsg(globalKbx.settings.obj.asking_phone);
                kbxMsg.single(askEmail);
                //Now updating the support part and keeping value in localstorage
                globalKbx.supportStep = 'phone';
                globalKbx.wildCard = 3;
                localStorage.setItem("wildCard", globalKbx.wildCard);
                localStorage.setItem("supportStep", globalKbx.supportStep);
            }
            if (wildcardData == 'back') {
                var serviceOffer = kbxKits.randomMsg(globalKbx.settings.obj.wildcard_msg);
                kbxMsg.single(serviceOffer);
                setTimeout(function () {
                    if (globalKbx.wildcards != "") {
                        kbxMsg.single_nobg(globalKbx.wildcards);
                    }
                    //For handle the ai and alone
                    if (globalKbx.settings.obj.ai_df_enable == 1 && globalKbx.df_status_lock == 0) {
                        globalKbx.wildCard = 0;
                        globalKbx.ai_step = 1;
                        //keeping value in localstorage
                        localStorage.setItem("wildCard", globalKbx.wildCard);
                        localStorage.setItem("aiStep", globalKbx.ai_step);


                    } else {
                        globalKbx.wildCard = 1;
                        globalKbx.articleStep = 'search'
                        //keeping value in localstorage
                        localStorage.setItem("wildCard", globalKbx.wildCard);
                        localStorage.setItem("articleStep", globalKbx.articleStep);
                    }
                }, parseInt(globalKbx.settings.preLoadingTime));

            }

        });
        return this;
    };
    //Deafault value for kbxbot.If nothing passes from the work station
    //Then defaults value will be used.
    $.kbxbot.defaults = {
        obj: {},
        sendButton: '#kbx-bot-send-message',
        messageEditor: '#kbx-bot-editor',
        messageContainer: '#kbx-bot-messages-container',
        messageWrapper: '.kbx-bot-messages-wrapper',
        botContainer: '.kbx-bot-ball-inner',
        messageLastChild: '#kbx-bot-messages-container li:last',
        messageLastBot: '#kbx-bot-messages-container .kbx-bot-msg:last .kbx-bot-paragraph',
        preLoadingTime: 1500,
        wildcardsShowTime: 5000,
    }

})(jQuery);