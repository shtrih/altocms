
  <!-- Attachments plugin -->
  <script>
    // --- code ---
    var Attachments_FormUploadAction = "{router page='attachments'}receive";
    var Attachments_PosibleFileExtensions = "{$oConfig->GetValue("plugin.attachments.valid_extensions")}";
    var Attachments_FileFormPlace = "{$oConfig->GetValue("plugin.attachments.AttachmentsFileFormPlace")}";
    var Attachments_PathRootWeb = "{$oConfig->GetValue("path.root.web")}";
    var Attachments_AttachmentsAction = "{router page='attachments'}";
    // --- msgs ---
    var Attachments_AddFileMsg = "{$aLang.plugin.attachments.new_topic_add_file_msg}";
    var Attachments_DefaultInfoMsg = "{$aLang.plugin.attachments.new_topic_first_message}";
    var Attachments_UploadingFileNow = "{$aLang.plugin.attachments.new_topic_uploading_file_now}";
    var Attachments_Not_Allowed_File_Types = "{$aLang.plugin.attachments.new_topic_error_not_allowed_file_types}";
    var Attachments_UploadDone = "{$aLang.plugin.attachments.new_topic_file_upload_done}";
    var Attachments_SidebarDetectionError = "{$aLang.plugin.attachments.sidebar_detection_general_error}";
    var Attachments_WrongFileFormPlace = "{$aLang.plugin.attachments.wrong_parameter_file_form_place}";
    var Attachments_DoYouReallyWantToDeleteThisFile = "{$aLang.plugin.attachments.do_you_really_want_to_delete_this_file}";
    var Attachments_ElementTitleDownloadFile = "{$aLang.plugin.attachments.element_title_download_file}";
    var Attachments_ElementTitleDeleteFile = "{$aLang.plugin.attachments.element_title_delete_file}";
    var Attachments_FileWasDeleted = "{$aLang.plugin.attachments.file_was_deleted}";
    var Attachments_CantAttachToSavedTopic = "{$aLang.plugin.attachments.cant_attach_file_to_saved_topic}";
    var Attachments_ThereIsNoUnattachedFiles = "{$aLang.plugin.attachments.there_is_no_unattached_files}";
  </script>
  <!-- /Attachments plugin -->
