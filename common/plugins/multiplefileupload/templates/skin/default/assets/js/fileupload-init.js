//;(function ($, document, window) {
$(function () {
    var fileupload = $('#multiple-file-upload'),
        btn_start = $('.fileupload-buttonbar button.start', fileupload),
        btn_cancel  = $('.fileupload-buttonbar button.cancel', fileupload),
        topic_id = fileupload.data('topic-id')
    ;

    fileupload.fileupload({
        autoUpload: false,
        disableImageResize: true,
        disableImagePreview: false,
        disableVideoPreview: false,
        disableAudioPreview: false,
        maxFileSize: 2048 * 1024 * 1024,
        url: DIR_WEB_ROOT + '/multiplefileupload/upload',
        formData: [
            {
                name: 'topic_id',
                value: topic_id
            },
            {
                name: 'security_key',
                value: ALTO_SECURITY_KEY
            }
        ],
        added: function (e, data) {
            if ($(this).find('table tr').length > 1)
                btn_start.removeClass('hide');
        },
        started: function () {
            btn_cancel.removeClass('hide');
            btn_start.addClass('hide');
        },
        finished: function () {
            btn_cancel.addClass('hide');
        }
    });
    fileupload.bind('fileuploadcompleted', function (e, data) {
        data.context.appendTo('.table-uploaded')
    });

    if (typeof fileList != 'undefined') {
        fileupload.fileupload('option', 'done')
            .call(fileupload[0], $.Event('done'), {result: {files: fileList}});
    }
/*
    window.linkfile = function(id, size, name) {
        var url = DIR_WEB_ROOT + (topicId
            ? '/multiplefileupload/linkto/' + id + '/' + topicId
            : '/multiplefileupload/link/' + id + '/' + formId
            );
        $.ajax ({
            url: url,
            data: {
                security_ls_key: ALTO_SECURITY_KEY
            },
            dataType: 'json',
            type: 'GET'
        });
        upload.fileupload('option', 'done')
            .call(upload[0], $.Event('done'), {result: {files: [{
                name: name,
                size: size,
                url: DIR_WEB_ROOT + '/multiplefileupload/get/' + id,
                id: id
            }]}});
    };
*/
    window.deletefile = function(id, name) {
        if (confirm('Удалить файл ' + name + '?')) {
            $.ajax ({
                url: DIR_WEB_ROOT + '/multiplefileupload/remove/' + id + '/' + formId,
                data: {
                    security_key: ALTO_SECURITY_KEY
                },
                dataType: 'json',
                type: 'GET'
            });
            return true;
        }
        return false;
    };

    $('#uploadFromUrl').on('click', function() {
        var url = prompt("Укажите адрес файла: ", "http://");
        if (url) {
            var filename = url.match(/.*\/(.*)$/)[1];
            if (!filename) {
                filename = 'file';
            }
            if (filename.indexOf('.') == -1) {
                filename += '.html';
            }
            var blob = new Blob([url], {type : "file\/link"});
            blob.name = filename;
            fileupload.fileupload('add', {files: [blob]});
        }
    });

    $('.table-uploaded tbody').sortable({
        containment: '.table-uploaded',
        cursor: "move",
        forcePlaceholderSize: true,
        handle: ".sort",
        helper: function(e, ui) {
            ui.children().each(function() {
                $(this).width($(this).width());
            });
            return ui;
        },
        stop: function(e) {
            var ids = [];
            $('.table-uploaded tr').each(function(){
                ids.push($(this).data('id'));
            });
            ids.reverse();
            ls.ajax(
                DIR_WEB_ROOT + '/multiplefileupload/sort/',
                {
                    target: 'multiple-file-upload',
                    target_id: topic_id ? topic_id : '',
                    order: ids
                },
                /**
                 * Выведем пользователю сообщение о результате сортировки
                 * @param {{bStateError: {boolean}, sMsg: {string}, sMsgTitle: {string}}} result
                 */
                function (result) {
                    return result.bStateError
                        ? ls.msg.error(result.sMsgTitle, result.sMsg)
                        : ls.msg.notice(result.sMsgTitle, result.sMsg);
                }
            );
        }
    });
});
//}(jQuery, document, window));