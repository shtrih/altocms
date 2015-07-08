{literal}
settings.markupSet.splice(8, 0, { name: 'Скрытый текст', className:'editor-hide', openWith:'<hide>', closeWith:'</hide>' });
settings.markupSet.splice(11, 0, {
    name: 'Спойлер',
    className:'editor-hidespoiler',
    replaceWith: function(m) {
        return '<spoiler name="Нажмите для просмотра содержимого">'+(m.selectionOuter || m.selection)+'</spoiler>';
    }
});
{/literal}