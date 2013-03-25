eva.ready(function(){
    var modal = $("#fileupload-modal");
    var videoPopover = $("#video-popover");
    var uploader = $('#singlefileupload');
    var form = $("#activity-form");
    var textarea = form.find('textarea');
    var submit = form.find('button[type=submit]');
    var syncTwitter = form.find('#activity-box-sync-twitter input');

    if(!$(".icon-social-twitter").hasClass('highlight')){
        syncTwitter.parent().hide();
    }

    form.on('submit', function(){
        
        if(syncTwitter.attr('checked')){
            form.data('allowSubmit', false);
            submit.addClass('disabled').html('Sync To Twitter...');

            $.ajax({
                    url : eva.d('/webservice/feed/sync/'),
                    data : {
                        'content' : textarea.val(),
                        'service' : 'twitter',
                        'type' : 'oauth1'
                    },

                    error : function(){
                        form.data('allowSubmit', true);
                        syncTwitter.removeAttr('checked');
                        submit.removeClass('disabled').html('Submit');
                        form.submit();
                    },

                    success : function(){
                        form.data('allowSubmit', true);
                        syncTwitter.removeAttr('checked');
                        submit.removeClass('disabled').html('Submit');
                        form.submit();
                    }
            });
        } else {
            form.data('allowSubmit', true);
        }

        return true === form.data('allowSubmit') ? true : false;
    });

    eva.loader(eva.assets('/module/core/js/eva.jquery.evaTip.js'), function(){
            $('#activity-box-image-button').on('click', function() {
                    $(this).evaTip("show", {
                            'tip' : modal,
                            'offsetX' : -75,
                            'offsetY' : 30,
                            'direction' : 'bottom'			
                    });
                    return false;
            });

            modal.find(".close").on("click", function(){
                    modal.hide();
            });

            $("#activity-box-video-button").on('click', function() {
                    $(this).evaTip("show", {
                            'tip' : videoPopover,
                            'offsetX' : -90,
                            'offsetY' : 30,
                            'direction' : 'bottom'			
                    });
                    return false;
            });

            videoPopover.find(".close").on("click", function(){
                    videoPopover.hide();
            });
    });


    uploader.bind('fileuploaddone', function (e, data) {
            var file = data.result[0];
            form.find('input[name="MessageFile[file_id]"]').val(file.id);
            modal.find(".close").hide();
    });

    $(".activity-box-text").live('click', function(){
            textarea.val(textarea.val() + $(this).attr('data-activity-text'));
            return false;
    });

    $(".item-func.reference").live('click', function(){
            textarea.val(textarea.val() + $(this).attr('data-activity-text'));
            form.find('input[name="reference_id"]').val($(this).attr('data-activity-reference'));
            form.find('input[name="messageType"]').val('forward');
    });

    $(".item-func.comment").live('click', function(){
            var item = $(this).parentsUntil('.item').parent();
            var idUrl = item.find('.item-id').attr('href');
            item.find('.item-sub-ajax').load(idUrl + ' .item-sub-wrap', function(){
                    $(this).show();
            });
    });

    $(".item-func.remove").live('click', function(){
            var item = $(this).parentsUntil('.item').parent();
            var removeForm = item.find('.item-remove');
            var idUrl = item.find('.item-id').attr('href');
            if(!removeForm[0]){
                item.find('.item-remove-ajax').load(idUrl + ' .item-remove', function(){
                    item.find('.item-remove').show();
                });
            } else {
                removeForm.show();
            }
            return false;
    });

    $(".item-func.remove-cancel").live('click', function(){
            var item = $(this).parentsUntil('.item').parent();
            var removeForm = item.find('.item-remove');
            removeForm.hide();
            return false;
    });

    $(".activity-list").on("submit", ".item-sub-form form", function(){
            var form = $(this);
            var item = form.parentsUntil('.item').parent();
            var feedUrl = form.find('input[name=callback]').val();
            $.ajax({
                    url : form.attr('action'),
                    data : form.serialize(),
                    type : form.attr('method'),
                    success : function(){
                            item.find('.item-sub-ajax').load(feedUrl + ' .item-sub-wrap');
                    }
            });
            return false;
    });

    $("#add-video").on('click', function(){
            var popover = $("#video-popover");
            var url = popover.find("input[name=url]").val();
            if(url == ''){
                return false;
            }

            if(!/https?:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/i.test(url)) {
                    return false;
            }

            $.ajax({
                url : eva.d('/video/parser/'),
                data : {
                    url : url
                },
                dataType : 'json',
                success : function(response){
                        if(response.remoteId){
                                popover.find(".ajax-video-preview").html(eva.template('<embed src="{swf}" quality="high" width="200" height="120" align="middle" allowScriptAccess="always" allowFullScreen="true" mode="transparent" type="application/x-shockwave-flash"></embed>', response));
                                textarea.val(textarea.val() + ' ' + response.url);
                        } else {
                    
                        }
                }
            });

            return false;
    });

    var maxMessage = 140;
    var countHandler = $('.activity-box-count');
    var messageLetterCount = function(){
        countHandler.html(maxMessage - textarea.val().length);
    }

    textarea.live('keyup', function(){
        messageLetterCount();
    });
});
