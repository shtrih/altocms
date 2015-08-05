//;(function ($, document, window) {
$(function () {
    var upload = $('#multiple-file-upload'),
        btn_start = $('.fileupload-buttonbar button.start', upload),
        btn_cancel  = $('.fileupload-buttonbar button.cancel', upload)
    ;
    var r = function(){
        return (Math.floor(Math.random() * (90)) + 10).toString();
    };
    var formId = r()+r()+r()+r()+r()+r();
    var topicId = upload.attr('rel');

    upload.fileupload({
        autoUpload: false,
//        disableImageResize: true,
//        disableImagePreview: true,
//        disableVideoPreview: true,
//        disableAudioPreview: true,
        maxFileSize: 2048 * 1024 * 1024,
        url: DIR_WEB_ROOT + '/attachments/receive',
        formData: [{
            name: 'form_id',
            value: formId
        }, {
            name: 'topic_id',
            value: topicId
        }],
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
    upload.bind('fileuploadcompleted', function (e, data) {data.context.appendTo('.table-uploaded')});

    $('#form-topic-add').append('<input name="form_id_topic" type="hidden" value="' + formId + '" />');

//    var fileList = [
//        {
//            name: 'Filename.jpg',
//            size: '10kb',
//            url: '/file/down',
//            id: 1
//        }
//    ];
//    console.log(fileList);
    if (typeof fileList != 'undefined') {
        upload.fileupload('option', 'done')
            .call(upload[0], $.Event('done'), {result: {files: fileList}});
    }

    window.linkfile = function(id, size, name) {
        var url = DIR_WEB_ROOT + (topicId
            ? '/attachments/linkto/' + id + '/' + topicId
            : '/attachments/link/' + id + '/' + formId
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
                url: DIR_WEB_ROOT + '/attachments/get/' + id,
                id: id
            }]}});
    };

    window.deletefile = function(id, name) {
        if (confirm('Удалить файл ' + name + '?')) {
            $.ajax ({
                url: DIR_WEB_ROOT + '/attachments/delete/' + id + '/' + formId,
                data: {
                    security_ls_key: ALTO_SECURITY_KEY
                },
                dataType: 'json',
                type: 'GET'
            });
            return true;
        }
        return false;
    };

    $('#uploadFromUrl').on('click', function(){
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
            upload.fileupload('add', {files: [blob]});
        }
    });

    $('.table-uploaded tbody').sortable({
        containment: '.table-uploaded',
        cursor: "move",
        forcePlaceholderSize: true,
        handle: ".sort img",
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
            $.ajax ({
                url: DIR_WEB_ROOT + '/attachments/sort/',
                data: {
                    form: formId,
                    topic: topicId ? topicId : '',
                    sort: ids,
                    security_ls_key: ALTO_SECURITY_KEY
                },
                dataType: 'json',
                type: 'POST'
            });
        }
    });
});
//}(jQuery, document, window));