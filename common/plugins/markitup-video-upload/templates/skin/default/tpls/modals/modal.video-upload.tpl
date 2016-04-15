{* Тема оформления Experience v.1.0  для Alto CMS      *}
{* @licence     CC Attribution-ShareAlike  http://site.creatime.org/experience/*}

<script>
$(function () {
    $('#modal-video-upload').on('hidden.bs.modal', function () {
        $('#modal-video-upload')
            .find('#video-file, #url, #form-image-title').val('')
            .end()
            .find('select :first').attr("selected", "selected")
                .trigger('change') /* update state «selecter» plugin */
            .end()
            .find('.fileinput-filename').html("")
//            .end()
//            .find('[name="img_width"]').val("100")
        ;
    });

    $('.js-video-width').each(function(){
        $('[name=width_text]', this).val($('#form-topic-add').width());
    });
})
</script>
<div class="modal fade in" id="modal-video-upload">
    <div class="modal-dialog">
        <div class="modal-content">

            <header class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">{$aLang.plugin.markitup_video_upload['dialog-title']}</h4>
            </header>

            <div class="modal-body">
                <form method="POST" action="" enctype="multipart/form-data" id="block_video-upload-content_link"
                      onsubmit="return false;" class="tab-content js-block-video-upload-content">

                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#" data-toggle="tab" data-target=".js-pane-video-upload-pc">{$aLang.uploadimg_from_pc}</a></li>
                        <li>
                            <a href="#" data-toggle="tab" data-target=".js-pane-video-upload-link">{$aLang.uploadimg_from_link}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active js-pane-video-upload-pc">
                            <br />

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">{$aLang.uploadimg_file}</span>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="form-control" data-trigger="fileinput">
                                            <i class="fa fa-file fileinput-exists"></i>
                                            <span class="fileinput-filename"></span>
                                        </div>
                                        <span class="input-group-addon btn btn-default btn-file" >
                                            <span style="cursor: pointer"  class="fileinput-new">{$aLang.select}</span>
                                            <span style="cursor: pointer"  class="fileinput-exists">{$aLang.select}</span>
                                            <input type="file" name="file" id="video-file" />
                                        </span>
                                    </div>
                                </div>
                                <small class="control-notice">{$aLang.plugin.markitup_video_upload['notice-file-extension']}
                                    {join(', ', Config::Get('module.uploader.video.webm.file_extensions'))}
                                    <br />
                                    {$aLang.plugin.markitup_video_upload['notice-max-file-size']}
                                    {Config::Get('module.uploader.video.webm.file_maxsize')}</small>
                            </div>

                            {*{hook run="uploadimg_source"}*}

                            {*{hook run="uploadimg_additional"}*}
                        </div>

                        <div class="tab-pane js-pane-video-upload-link">
                            <br />

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">{$aLang.topic_link_create_url}</span>
                                    <input type="text" name="url" id="url" value="" class="form-control" placeholder="http://" />
                                </div>
                                <small class="control-notice">{$aLang.plugin.markitup_video_upload['notice-file-extension']}
                                    {join(', ', Config::Get('module.uploader.video.webm.file_extensions'))}
                                    <br />
                                    {$aLang.plugin.markitup_video_upload['notice-max-file-size']}
                                    {Config::Get('module.uploader.video.webm.url_maxsize')}</small>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">{$aLang.uploadimg_align}</span>
                            <select name="align" id="form-image-align" class="form-control">
                                <option value="">{$aLang.uploadimg_align_no}</option>
                                <option value="left">{$aLang.uploadimg_align_left}</option>
                                <option value="right">{$aLang.uploadimg_align_right}</option>
                                <option value="center">{$aLang.uploadimg_align_center}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group js-video-width">
                        <div class="input-group">
                            <span class="input-group-addon">{$aLang.plugin.markitup_video_upload['dialog-size-width-max']}</span>
                            <input type="text" name="img_width" value="100" class="form-control"/>
                            <span class="input-group-addon">%</span>
                        </div>
                        <input type="hidden" name="width_unit" value="percent" />
                        <input type="hidden" name="width_ref" value="text" />
                        <input type="hidden" name="width_text" value="" />
                        <small class="control-notice">{$aLang.uploadimg_size_width_max_text}</small>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon">{$aLang.uploadimg_title}</span>
                            <input type="text" name="title" id="form-image-title" value="" class="form-control"/>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-light btn-normal corner-no" data-dismiss="modal">{$aLang.uploadimg_cancel}</button>
                    <button type="submit" class="btn btn-blue pull-right btn-normal corner-no " onclick="ls.ajaxUploadVideo(this,'{$sToLoad}');">
                        {$aLang.uploadimg_submit}
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
