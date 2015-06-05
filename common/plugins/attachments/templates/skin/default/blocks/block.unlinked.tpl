
  {if $aUnlinkedFiles}
    <!-- Attachments plugin -->
    <div class="block white">
      <header class="block-header">
        <h3 title="{$aLang.plugin.attachments.not_attached_files_title}">{$aLang.plugin.attachments.not_attached_files}</h3>
      </header>
      
      <div class="block-content">
        <div id="Attachments_SidebarUnattachedFileListIDContainer">
          {foreach from=$aUnlinkedFiles item=oFile name="oFileIteract"}
            <div id="UnattachedFileID{$oFile.attachment_id}" class="UnattachedFileList {if $smarty.foreach.oFileIteract.iteration % 2 == 1}secondLine{/if}">
              <div class="DeleteThisFile" id="DeleteThisFile_{$oFile.attachment_id}" title="{$aLang.plugin.attachments.element_title_delete_file}"></div>
              <div class="AttachThisFileToNewTopic" id="AttachThisFileToNewTopic_{$oFile.attachment_id}" title="{$aLang.plugin.attachments.attach_file_to_new_topic}"></div>
              <a class="CurFileA" href="{router page='attachments'}get/{$oFile.attachment_id}" title="{$aLang.plugin.attachments.element_title_download_file}">{$oFile.attachment_name}</a>
            </div>
            <script>
				$(function () {
					$('#AttachThisFileToNewTopic_{$oFile.attachment_id}').click(function(){
						$('#UnattachedFileID{$oFile.attachment_id}').remove();
						linkfile({$oFile.attachment_id}, {$oFile.attachment_size}, "{$oFile.attachment_name}");
					});
					$('#DeleteThisFile_{$oFile.attachment_id}').click(function(){						
						if (deletefile({$oFile.attachment_id}, "{$oFile.attachment_name}")) {
							$('#UnattachedFileID{$oFile.attachment_id}').remove();
						}
					});
				});
            </script>
          {/foreach}
        </div>
      </div>
    </div>
    <!-- /Attachments plugin -->
  {/if}
