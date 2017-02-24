{$aVideoConfig = Config::Get('module.uploader.video.webm')}
<script>
    var VIDEO_UPLOAD_INDEX_TOPIC = {Config::Get('plugin.markitup_video_upload.button_index_topic')},
        VIDEO_UPLOAD_INDEX_COMMENTS = {Config::Get('plugin.markitup_video_upload.button_index_comments')},
        VIDEO_UPLOAD_ORIGINAL_SUFFIX = '{$aVideoConfig['original']['suffix']|escape:'javascript'}'
    ;
</script>
