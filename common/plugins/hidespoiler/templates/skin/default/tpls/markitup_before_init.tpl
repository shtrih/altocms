{if $bCommentSettings}
    {$iIndex1 = 4}
    {$iIndex2 = 5}
{else}
    {$iIndex1 = 10}
    {$iIndex2 = 11}
{/if}

settings.markupSet.splice({$iIndex1}, 0, {literal}{ name: 'Скрытый текст', className: 'editor-hide', openWith: '<hide>', closeWith: '</hide>' }{/literal});
settings.markupSet.splice({$iIndex2}, 0, {literal}{
    name: 'Спойлер',
    className:'editor-hidespoiler',
    replaceWith: function(m) {
        return '<spoiler name="Нажмите для просмотра содержимого">'+(m.selectionOuter || m.selection)+'</spoiler>';
    }
}{/literal});
//