
;var ls = ls || {};

ls.multiplefileupload = (function ($) {
    'use strict';

    var self = this;

    this.init = function () {
        var oFileupload = this.oFileUpload = $('#multiple-file-upload'),
            iTopicId = oFileupload.data('topicId'),
            btn_start = $('.fileupload-buttonbar button.start', oFileupload),
            btn_cancel  = $('.fileupload-buttonbar button.cancel', oFileupload)
        ;

        // Ссылка «показать все файлы»
        $('.js-topic-preview-place,.js-topic').on('click', '.multiple-file-upload .toggle-others', function () {
            $(this)
                .closest('ul')
                .find('li').removeClass('hide')
                .end().end().parent('li').remove();

            return false;
        });

        if (!oFileupload.length)
            return;

        // Кнопка «загрузить по url»
        $('.url-upload', oFileupload).on('click', function () {
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

        oFileupload.fileupload({
            autoUpload: MFU_CONFIG['auto-upload'] || false,
            messages: {
                uploadedBytes: ls.lang.get('plugin.multiplefileupload.upload_err_uploaded_bytes_exceed_fs'), // 'Uploaded bytes exceed file size',
                maxNumberOfFiles: ls.lang.get('plugin.multiplefileupload.upload_err_max_number_of_files'), // 'Maximum number of files exceeded',
                acceptFileTypes: ls.lang.get('plugin.multiplefileupload.upload_err_not_allowed_extension'), // 'File type not allowed',
                maxFileSize: ls.lang.get('plugin.multiplefileupload.upload_err_file_too_large'), // 'File is too large',
                minFileSize: ls.lang.get('plugin.multiplefileupload.upload_err_file_too_small'),  // 'File is too small',
                unknownError: ls.lang.get('plugin.multiplefileupload.upload_err_unknown') // 'Unknown error'
            },
            disableImageResize: true,
            disableImagePreview: false,
            disableVideoPreview: false,
            disableAudioPreview: false,
            maxFileSize: MFU_CONFIG['max-file-size'],
            acceptFileTypes: MFU_CONFIG['accept-file-types'] ? new RegExp(MFU_CONFIG['accept-file-types'], 'i') : undefined,
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

        oFileupload.bind('fileuploadcompleted', function (e, data) {
            data.context.appendTo('.table-uploaded');
            // загрузка файла при создании топика
            if (!iTopicId) {
                self.createAttachInput(oFileupload.data('fieldId'), $(data.context).data('fileId'));
            }
        });

        // кнопка «Удалить»
        oFileupload.on('click', '.mfu-remove-file', function () {
            var oBtn = $(this),
                oRowFile = oBtn.closest('tr'),
                iFileId = oRowFile.data('fileId'),
                sFileName = $.trim(oRowFile.find('.name').text()),
                iiTopicId = (oRowFile.closest('.mfu-unattached').length ? 0 : iTopicId)
            ;
            self.removeFile(iiTopicId, iFileId, sFileName, function() {
                oRowFile.remove();
            });

            return false;
        });

        // кнопка «Прикрепить»
        oFileupload.on('click', '.mfu-attach-file', function () {
            var oBtn = $(this),
                oRowFile = oBtn.closest('tr'),
                iFileId = oRowFile.data('fileId'),
                iFileSize = oRowFile.data('fileSize'),
                oFielName = oRowFile.find('.name'),
                sFileName = $.trim(oFielName.text()),
                sFileUrl = oFielName.attr('href'),
                iFieldId = oFileupload.data('fieldId')
            ;

            if (iTopicId) {
                self.attachFile(iTopicId, iFileId, function () {
                    oRowFile.remove();
                    self.addFiles([{
                        id: iFileId,
                        name: sFileName,
                        url: sFileUrl,
                        size: iFileSize
                    }]);
                });
            }
            // прикрепление файла в новом топике
            else {
                self.createAttachInput(iFieldId, iFileId);
                oRowFile.remove();
                self.addFiles([{
                    id: iFileId,
                    name: sFileName,
                    url: sFileUrl,
                    size: iFileSize
                }]);
            }

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
            this.oFileUpload.fileupload('option', 'done')
                .call(this.oFileUpload[0], $.Event('done'), {result: {files: aFiles}});
        }
    };

    this.createAttachInput = function (iFieldId, iFileId) {
        self.oFileUpload.append(
            $('<input />', {
                type: 'hidden',
                name: 'fields['+ iFieldId +'][]',
                value: iFileId
            })
        );
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
        this.oFileUpload.fileupload('add', {files: [oBlob]});
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
