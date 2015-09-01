 {* Тема оформления Experience v.1.0  для Alto CMS      *}
 {* @licence     CC Attribution-ShareAlike  http://site.creatime.org/experience/*}

{$oBlog=$oTopic->getBlog()}
{$oUser=$oTopic->getUser()}
{$oVote=$oTopic->getVote()}
{$oFavourite=$oTopic->getFavourite()}
{$oContentType=$oTopic->getContentType()}
{$oNsfw = $oTopic->getFieldValueByName('nsfw')}
{$bNsfwPictures = !!$oTopic->getFieldValueByName('nsfw-pictures')}

{*{if $oContentType}*}
    {*{$oField = $oContentType->getFieldByName('nsfw')}*}
    {*{if $oField}*}
        {*{$oTopicField = $oTopic->getField($oField->getFieldId())}*}

        {*{if $oTopicField}*}
            {*<p>*}
                {*<strong>{$oField->getFieldName()}</strong>:*}
                {*{$oTopicField->getValue()}*}
            {*</p>*}
        {*{/if}*}
    {*{/if}*}
{*{/if}*}
<!-- Блок топика -->
<div class="panel panel-default topic flat topic-type_{$oTopic->getType()} js-topic{if $oNsfw} nsfw-show{/if}{if $bNsfwPictures} nsfw-pictures{/if}">

    <div class="panel-body">
        {block name="topic_header"}
            <h2 class="topic-title accent">
                {$oTopic->getTitle()|escape:'html'}

                {if $oTopic->getPublish() == 0}
                    &nbsp;<span class="fa fa-file-text-o" title="{$aLang.topic_unpublish}"></span>
                {/if}

                {if $oTopic->getType() == 'link'}
                    &nbsp;<span class="fa fa-globe" title="{$aLang.topic_link}"></span>
                {/if}

                {if $oNsfw}
                    &nbsp;<span class="label label-danger label-nsfw" title="Not Safe For Work">nsfw</span>
                {/if}
                {if $oTopic->getPublishIndex()}
                    &nbsp;<span class="label label-success label-publish-index" title="">Одобрено</span>
                {/if}
            </h2>

            <div class="topic-info">
                <ul>
                    <li data-alto-role="popover"
                        data-api="user/{$oUser->getId()}/info"
                        data-api-param-tpl="default"
                        data-trigger="hover"
                        data-placement="bottom"
                        data-animation="true"
                        data-cache="true"
                        class="topic-user">
                        <img src="{$oUser->getAvatarUrl('small')}" alt="{$oUser->getDisplayName()}"/>
                        <a class="userlogo link link-dual link-lead link-clear js-popup-{$oUser->getId()}"
                           href="{$oUser->getProfileUrl()}">
                            {$oUser->getDisplayName()}
                        </a>
                    </li>
                    <li class="topic-blog">
                        <a class="link link-lead link-blue"
                           href="{$oBlog->getUrlFull()}">{$oBlog->getTitle()|escape:'html'}</a>
                    </li>
                    <li class="topic-date-block">
                        <span class="topic-date">{$oTopic->getDate()|date_format:'d.m.Y'}</span>
                        <span class="topic-time">{$oTopic->getDate()|date_format:"H:i"}</span>
                    </li>
                </ul>
            </div>
        {/block}


        {* Топики, которых нет на главной, не показываем неавторизованным юзерам.
           Показывать ли одобренные нсфв-топики, зависит от опции. *}
        {if !E::IsUser() && (
            !$oTopic->getPublishIndex()
            OR $oTopic->getPublishIndex() && $oNsfw
                && C::Get('plugin.customtemplates.hide_nsfw_topics_4guests')
            )
        }
            <div class="topic-text">
                <p>{$aLang.plugin.customtemplates.topic_text_dummy}</p>
            </div>
        {else}
            {block name="topic_content"}
                <div class="topic-text">
                    {hook run='topic_content_begin' topic=$oTopic bTopicList=false}

                    {$sImagePath=$oTopic->getPhotosetMainPhotoUrl(false, '682pad')}
                    {if $sImagePath}
                        <img src="{$sImagePath}" alt="image" align="left"/>
                        <br/>
                    {/if}

                    {$oTopic->getText()}

                    {hook run='topic_content_end' topic=$oTopic bTopicList=false}
                </div>
            {/block}

            {if $oTopic->isShowPhotoset()}
                {include file="fields/field.photoset-show.tpl"}
            {/if}

            {if $oContentType AND $oContentType->isAllow('poll') AND $oTopic->getQuestionAnswers()}
                {include file="fields/field.poll-show.tpl"}
            {/if}

            {if $oContentType AND $oContentType->isAllow('link') AND $oTopic->getSourceLink()}
                {include file="fields/field.link-show.tpl"}
            {/if}

            {foreach from=$oContentType->getFields() item=oField}
                {* Пропускаем некоторые поля *}
                {if in_array($oField->getFieldUniqueName(), ['nsfw', 'nsfw-pictures'])}
                    {continue}
                {/if}

                {$sFieldPath = "`$sTemplateDir`tpls/fields/`$oContentType->getContentUrl()`/field.custom.`$oField->getFieldType()`-show.tpl"}
                {if file_exists($sFieldPath)}
                    {include file=$sFieldPath oField=$oField}
                {else}
                    {include file="fields/customs/field.custom.`$oField->getFieldType()`-show.tpl" oField=$oField}
                {/if}
            {/foreach}
        {/if}

        {include file="fields/field.tags-show.tpl"}

    </div>



    {block name="topic_footer"}
        {if !$bPreview}
        <div class="topic-footer">
            <ul>

                {hook run='topic_show_info' topic=$oTopic bTopicList=false oVote=$oVote}

                <li class="topic-favourite">
                    <a class="link link-dark link-lead link-clear {if E::IsUser() AND $oTopic->getIsFavourite()}active{/if}"
                       onclick="return ls.favourite.toggle({$oTopic->getId()},this,'topic');"
                       href="#">
                        {if $oTopic->getIsFavourite()}<i class="fa fa-star"></i>{else}<i class="fa fa-star-o"></i>{/if}
                        <span class="favourite-count" id="fav_count_topic_{$oTopic->getId()}">{$oTopic->getCountFavourite()}</span>
                    </a>
                </li>
                {*<li class="topic-info-share">*}
                    {*<a class="link link-dark link-lead link-clear" href="#"*}
                       {*title="{$aLang.topic_share}"*}
                       {*onclick="$('#topic_share_' + '{$oTopic->getId()}').slideToggle(); return false;">*}
                        {*<i class="fa fa-share-alt"></i>&nbsp;*}
                    {*</a>*}
                {*</li>*}

                <li class="topic-comments">
                    <a href="{$oTopic->getUrl()}#comments" title="{$aLang.topic_comment_read}" class="link link-dark link-lead link-clear">
                        <i class="fa fa-comment"></i>
                        <span>{$oTopic->getCountComment()}</span>
                        {if $oTopic->getCountCommentNew()}<span class="green">+{$oTopic->getCountCommentNew()}</span>{/if}
                    </a>
                </li>

                {if Config::Get('module.topic.draft_link') AND !$bPreview AND !$oTopic->getPublish()}
                    <li>
                        <a href="#" class="link link-dark link-lead link-clear"
                           onclick="prompt('{$aLang.topic_draft_link}', '{$oTopic->getDraftUrl()}'); return false;">
                            <i class="fa fa-link"></i>
                        </a>
                    </li>
                {/if}

                {if !$bPreview}
                    <li class="pull-right topic-controls">
                        {if E::IsAdmin() OR E::UserId()==$oTopic->getUserId() OR E::UserId()==$oBlog->getOwnerId() OR $oBlog->getUserIsAdministrator() OR $oBlog->getUserIsModerator()}
                            <a href="{router page='content'}edit/{$oTopic->getId()}/" title="{$aLang.topic_edit}" class="small link link-lead link-dark link-clear">
                                <i class="fa fa-pencil"></i>
                                {*&nbsp;{$aLang.topic_edit}*}
                            </a>
                            {if E::IsAdmin() OR $oBlog->getUserIsAdministrator() OR $oBlog->getOwnerId()==E::UserId()}
                            &nbsp;<a href="#" class="small link link-lead link-clear link-red-blue" title="{$aLang.topic_delete}"
                                     onclick="ls.topic.remove('{$oTopic->getId()}', '{$oTopic->getTitle()}'); return false;">
                                <i class="fa fa-trash-o"></i>
                                {*&nbsp;{$aLang.topic_delete}*}
                            </a>
                            {/if}
                        {/if}
                    </li>
                {/if}

            </ul>
            {/if}

        </div>
    {/block}
</div> <!-- /.topic -->

 {hook run='topic_show_end' topic=$oTopic bTopicList=false}
