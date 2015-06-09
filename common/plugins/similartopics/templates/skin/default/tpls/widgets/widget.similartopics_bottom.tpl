{$aSimilarTopics = $oTopic->getSimilarTopics($aWidgetParams.limit)}
{$nSimilarCount = $oTopic->CountSimilarTopics()}
{if $aSimilarTopics}
    <div class="simtopics panel panel-default">
        <div class="panel-body">

            <header class="simtopics-header">
                <h4 class="simtopics-title">{$aLang.plugin.similartopics.widget_title}</h4>
            </header>

            <div class="simtopics-content">

                <ul class="list-unstyled row">
                    {if count($aSimilarTopics)>1}{$sCssClass="col-xs-6"}{/if}
                    {$bPreview=$aWidgetParams.preview.enable}
                    {foreach $aSimilarTopics as $oSimilarTopic}
                        <li class="{$sCssClass} {if $bPreview}simtopics-preview-on{/if}">
                            {if $aWidgetParams.preview.enable}
                                <a href="{$oSimilarTopic->getUrl()}" class="simtopics-topic-preview">
                                    {if $oSimilarTopic->getPreviewImage()}
                                        <img src="{$oSimilarTopic->getPreviewImageUrl($aWidgetParams.preview.size.default)}">
                                    {/if}
                                </a>
                            {/if}
                            <a href="{$oSimilarTopic->getUrl()}" class="simtopics-topic-title">{$oSimilarTopic->getTitle()|escape:"html"}</a>
                            <div class="simitopics-topic-intro">
                                {$oSimilarTopic->getIntroText()}
                            </div>
                        </li>
                    {/foreach}
                </ul>

                {* if $nSimilarCount > count($aSimilarTopics)}
                    <div class="simtopics-more">
                        <a href="{router page="similar-topics"}{$oTopic->getId()}/">{$aLang.plugin.similartopics.more_topics} ({$nSimilarCount})</a>
                    </div>
                {/if *}
            </div>

        </div>
    </div>

{/if}
