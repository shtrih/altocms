
    {assign var="AttachedFilesList" value=$oTopic->getAttachmentsForDisplay()}
    {if $AttachedFilesList}
      <!-- Attachments plugin -->
      <div class="AttachmentsInTopic">        
        {$aLang.plugin.attachments.files_in_topics}
        {if count($AttachedFilesList) > 5}
            <a id="AttachmentsInTopicShow_{$oTopic->getId()}" href="#">показать файлы ({count($AttachedFilesList)} шт.)</a>
            <a id="AttachmentsInTopicHide_{$oTopic->getId()}" href="#" class="hide">скрыть файлы</a>
        {/if}
        {if count($AttachedFilesList) > 1}<br />{/if}

        {if count($AttachedFilesList) > 5}<div class="hide" id="AttachmentsInTopicList_{$oTopic->getId()}">{/if}
            {foreach from=$AttachedFilesList item=oFile name=nFileList}
                <div class="AttachmentsInTopicItem">
                    <a href="{router page='attachments'}get/{$oFile.attachment_id}/{$oFile.attachment_name|escape:'url'}">{$oFile.attachment_name|escape:'html'}</a>
                    <span class="pull-right">
                        {$oTopic->humanizeAttachmentSize($oFile.attachment_size)}
                    </span>
                </div>
            {/foreach}        
        {if count($AttachedFilesList) > 5}
            </div>
            <script>
                $(document).ready(function($){
                    $('#AttachmentsInTopicShow_{$oTopic->getId()}').click(function(e){
                        $('#AttachmentsInTopicList_{$oTopic->getId()}').slideDown();
                        $(this).hide();
                        $('#AttachmentsInTopicHide_{$oTopic->getId()}').show();
                        e.preventDefault();
                    });
                    $('#AttachmentsInTopicHide_{$oTopic->getId()}').click(function(e){
                        $('#AttachmentsInTopicList_{$oTopic->getId()}').slideUp();
                        $(this).hide();
                        $('#AttachmentsInTopicShow_{$oTopic->getId()}').show();
                        e.preventDefault();
                    });
                });
            </script>
        {/if}
      </div>
      <!-- /Attachments plugin -->
    {/if}
