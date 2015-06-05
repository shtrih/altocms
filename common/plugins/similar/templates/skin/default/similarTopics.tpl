{if $aSimilarTopics}
    <section class="topics-similar">
        <header>
            <h3>{$aLang.plugin.similar.block_similar_articles_title}</h3>
        </header>
        <div class="">
            <ul class="latest-list unstyled muted">
                {foreach from=$aSimilarTopics item=oTopic name="cmt"}
                    {assign var="oBlog" value=$oTopic->getBlog()}
                    {assign var="oUser" value=$oTopic->getUser()}

                    <li title="{$oTopic->getText()|strip_tags|trim|truncate:150:'...'|escape:'html'}">
                        <a href="{$oTopic->getUrl()}" class="stream-topic">{$oTopic->getTitle()|escape:'html'}</a>
                        <small><span class="block-item-comments"><i class="icon-comment"></i>{$oTopic->getCountComment()}</span>
                        (
                            <a href="{$oUser->getUserWebPath()}" class="author">{$oUser->getLogin()}</a>,
                        <time datetime="{date_format date=$oTopic->getDateAdd() format='c'}" title="{date_format date=$oTopic->getDateAdd() format="j F Y, H:i"}">
                        {date_format date=$oTopic->getDateAdd() hours_back="12" minutes_back="60" now="60" day="day H:i" format="j F Y, H:i"}
                        </time>, в блоге <a href="{$oBlog->getUrlFull()}" class="stream-blog">{$oBlog->getTitle()|escape:'html'}</a>
                        )</small>
                    </li>
                {/foreach}
            </ul>
        </div>
    </section>
{/if}