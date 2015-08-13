
;var ls = ls || {};

ls.multiplefileupload = (function ($) {
    "use strict";

    var self = this;

    this.init = function () {
        this.$fileupload = $('#multiple-file-upload');
        var iTopicId = this.$fileupload.data('topicId');
        var fileupload = this.$fileupload,
            btn_start = $('.fileupload-buttonbar button.start', fileupload),
            btn_cancel  = $('.fileupload-buttonbar button.cancel', fileupload)
        ;

        // Ссылка «показать все файлы»
        $('.js-topic-preview-place,.js-topic').on('click', '.multiple-file-upload .toggle-others', function () {
            $(this)
                .closest('ul')
                .find('li').removeClass('hide')
                .end().end().parent('li').remove();

            return false;
        });

        // Кнопка «загрузить по url»
        $('.url-upload', fileupload).on('click', function () {
            var sUrl = prompt("Адрес файла: ", "http://");
            if (sUrl) {
                var sFileName = sUrl.match(/.*\/(.*)$/)[1];
                if (!sFileName) {
                    sFileName = 'file';
                }
                if (sFileName.indexOf('.') == -1) {
                    sFileName += '.html';
                }
                self.addFileUrl(sUrl, sFileName);
            }

            return false;
        });

        fileupload.fileupload({
            autoUpload: false,
            disableImageResize: true,
            disableImagePreview: false,
            disableVideoPreview: false,
            disableAudioPreview: false,
            maxFileSize: 2048 * 1024 * 1024,
            url: ls.routerUrl('multiplefileupload') + 'upload/',
            formData: [
                {
                    name: 'topic_id',
                    value: iTopicId
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

        // кнопка «Удалить»
        fileupload.on('click', '.mfu-remove-file', function (e) {
            var oBtn = $(this),
                oRowFile = oBtn.closest('tr'),
                iFileId = oRowFile.data('fileId'),
                sFileName = $.trim(oRowFile.find('.name').text()),
                iTopicId = (oRowFile.closest('.mfu-unattached').length ? 0 : iTopicId)
            ;
            self.removeFile(iTopicId, iFileId, sFileName, function() {
                oRowFile.remove();
            });

            return false;
        });

        // кнопка «Прикрепить»
        fileupload.on('click', '.mfu-attach-file', function () {
            var oBtn = $(this),
                oRowFile = oBtn.closest('tr'),
                iFileId = oRowFile.data('fileId'),
                iFileSize = oRowFile.data('fileSize'),
                oFielName = oRowFile.find('.name'),
                sFileName = $.trim(oFielName.text()),
                sFileUrl = oFielName.attr('href')
            ;

            self.attachFile(iTopicId, iFileId, function () {
                oRowFile.remove();
                self.addFiles([{
                    id: iFileId,
                    name: sFileName,
                    url: sFileUrl,
                    size: iFileSize
                }]);
            });

            return false;
        });

        // Сортировка файлов
        this.initSortable(iTopicId);
    };

    /**
     * @param aFiles [{id: '', name: '', url: '', size: ''}, …]
     */
    this.addFiles = function (aFiles) {
        if (aFiles.length) {
            this.$fileupload.fileupload('option', 'done')
                .call(this.$fileupload[0], $.Event('done'), {result: {files: aFiles}});
        }
    };

    this.attachFile = function(iTopicId, iFileId, fOnSuccess) {
        var bResult = false;
        ls.progressStart();
        ls.ajax(
            ls.routerUrl('multiplefileupload') + 'attach/',
            {
                file_id: iFileId,
                target_id: iTopicId
            },
            /**
             * @param {{bStateError: {boolean}, sMsg: {string}, sMsgTitle: {string}}} oResult
             */
            function (oResult) {
                ls.progressDone();
                if (!oResult) {
                    ls.msg.error(null, 'System error #1001');
                } else if (oResult.bStateError) {
                    ls.msg.error(oResult.sMsgTitle, oResult.sMsg);
                } else {
                    ls.msg.notice(oResult.sMsgTitle, oResult.sMsg);
                    if (typeof fOnSuccess == 'function') {
                        fOnSuccess();
                    }
                }
            },
            {
                error: function () {
                    ls.progressDone();
                    ls.msg.error(null, 'System error #1001');
                }
            }
        );
    };

    this.removeFile = function(iTopicId, iFileId, sFileName, fOnSuccess) {
        if (confirm('Подтвердите удаление файла: «' + sFileName + '»')) {
            ls.progressStart();
            ls.ajax(
                ls.routerUrl('multiplefileupload') + 'remove/',
                {
                    topic_id: iTopicId,
                    target_id: iFileId
                },
                /**
                 * @param {{bStateError: {boolean}, sMsg: {string}, sMsgTitle: {string}}} oResult
                 */
                function (oResult) {
                    ls.progressDone();
                    if (!oResult) {
                        ls.msg.error(null, 'System error #1001');
                    } else if (oResult.bStateError) {
                        ls.msg.error(oResult.sMsgTitle, oResult.sMsg);
                    } else {
                        ls.msg.notice(oResult.sMsgTitle, oResult.sMsg);
                        if (typeof fOnSuccess == 'function') {
                            fOnSuccess();
                        }
                    }
                },
                {
                    error: function () {
                        ls.progressDone();
                        ls.msg.error(null, 'System error #1001');
                    }
                }
            );
        }
    };

    this.addFileUrl = function(sUrl, sFileName) {
        var oBlob = new Blob([sUrl], {type : 'file/link'});
        oBlob.name = sFileName;
        this.$fileupload.fileupload('add', {files: [oBlob]});
    };

    this.initSortable = function (iTopicId) {
        $('.table-uploaded tbody').sortable({
            containment: '.table-uploaded',
            cursor: "move",
            forcePlaceholderSize: true,
            handle: ".sort",
            /*helper: function(e, ui) {
                ui.children().each(function() {
                    $(this).width($(this).width());
                });
                return ui;
            },*/
            stop: function(e) {
                var ids = [];
                $('.table-uploaded tr').each(function(){
                    ids.push($(this).data('fileId'));
                });
                ids.reverse();
                ls.ajax(
                    ls.routerUrl('multiplefileupload') + 'sort/',
                    {
                        target: 'multiple-file-upload',
                        target_id: iTopicId ? iTopicId : '',
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
    };


    return this;
}).call(ls.multiplefileupload || {}, jQuery);

$(document).ready(function () {
    ls.multiplefileupload.init();
});