;$(function () {
    'use strict';

    ls.hook.add('ls_editor_berfore_init', function (editor_name, settings, is_comment_settings) {
        if (editor_name !== 'markitup')
            return;

        settings.markupSet.splice((is_comment_settings ? VIDEO_UPLOAD_INDEX_COMMENTS : VIDEO_UPLOAD_INDEX_TOPIC), 0, {
            name:         'Загрузить webm-видео',
            className:    'video-upload',
            beforeInsert: function (h) {
                jQuery('#modal-video-upload').modal();
            }
        });

        ls.ajaxUploadVideo = function (form) {
            form = $(form).closest('form');
            var modalWin = form.parents('.modal').first();

            ls.progressStart();
            ls.ajaxSubmit(ls.routerUrl('markitup-video-upload') + 'add-video/', form, function (result) {
                ls.progressDone();

                if (!result) {
                    ls.msg.error(null, 'System error #1001');
                } else if (result.bStateError) {
                    ls.msg.error(result.sMsgTitle, result.sMsg);
                } else {
                    ls.insertToEditor(result.sText);
                    modalWin.find('input[type="text"], input[type="file"]').val('');
                    modalWin.modal('hide');
                }
            });
        };
    });

    // удаляем ссылку, навешанный на нее лайтбокс
    $('a > img.webm').each(function () {
        var $this = $(this);
        $this.closest('a').after($this).remove();
    });

    $(document).on('click', '.webm', function(e) {
        e.preventDefault();
        if (e.which != 1)
            return;

        var thumb = $(this),
            thumb_nsfw = thumb.prev('canvas'), // pixelate on nsfw
            src = thumb.attr('src') + '_full.webm',
            video = $('<video controls="1" loop="1" onloadedmetadata="this.volume=0.5" src="' + src + '"></video>'),
            link = $('<a href="' + src + '" target="_blank">На отдельной вкладке</a>'),
            close = $('<a href="#">Свернуть</a>'),
            container = $('<span/>').append(link, ' | ', close, '<br />', video).addClass('webmContainer')
        ;

        thumb_nsfw.hide();
        thumb
            .hide()
            .before(container)
        ;
        video.get(0).play();

        close.on('click', function(e){
            e.preventDefault();

            if (thumb_nsfw.length)
                thumb_nsfw.show();
            else
                thumb.show();

            container.remove();
        });
        link.on('click', function () {
            video.get(0).pause();
        });
    });
});
