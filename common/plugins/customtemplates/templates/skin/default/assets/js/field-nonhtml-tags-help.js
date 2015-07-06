$(function () {
    var help_block = $('#topic-tags-help'),
        tags_lists_headers = help_block.find('h5'),
        tags_lists = help_block.find('ul'),
        tags_input = $('#topic_field_tags')
    ;

    tags_lists_headers.on('click', function () {
        $(this)
            .find('span').toggleClass('fa-chevron-right fa-chevron-down')
            .end()
            .next('ul').toggle()
        ;
    });

    tags_lists.on('click', 'a', function () {
        tags_input.val(text_toggle_tag($(this).text(), tags_input.val()));

        return false;
    });

    function text_toggle_tag(tag, tags_string) {
        var result = $.map(tags_string.split(','), $.trim),
            tag_index
        ;

        tag_index = result.indexOf(tag);
        if (tag_index >= 0) {
            result.splice(tag_index, 1);
        }
        else {
            result.push(tag);
        }

        return result.join(', ');
    }
});