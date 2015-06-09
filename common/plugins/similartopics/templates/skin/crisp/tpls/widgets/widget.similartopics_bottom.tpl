{$aSimilarTopics = $oTopic->getSimilarTopics($aWidgetParams.limit)}
{$aSimilarCount = $oTopic->CountSimilarTopics()}
{if $aSimilarTopics}
    <div class="clearfix"></div>
    <div class="simtopics">
        <hr>
        <div class="panel panel-default">

            <header class="simtopics-header">
                <h4 class="simtopics-title">{$aLang.plugin.similartopics.widget_title}</h4>
            </header>

            <div class="simtopics-content">

                <ul class="list-unstyled row">
                    {if count($aSimilarTopics)>1}{$sCssClass="span6"}{/if}
                    {foreach $aSimilarTopics as $oSimilarTopic}
                        <li class="{$sCssClass}">
                            <a href="{$oSimilarTopic->getUrl()}" class="similartopics-topic-title">{$oSimilarTopic->getTitle()|escape:"html"}</a>
                            <div class="simitopics-topic-intro">
                                {$oSimilarTopic->getIntroText()}
                            </div>
                        </li>
                    {/foreach}
                </ul>
{*
                {if $aSimilarCount > count($aSimilarTopics)}
                    <div class="simtopics-more">
                        <a href="{router page="similar-topics"}{$oTopic->getId()}/">{$aLang.plugin.similartopics.more_topics} ({$aSimilarCount})</a>
                    </div>
                {/if}
*}
            </div>

        </div>
        <hr>
    </div>
{/if}
