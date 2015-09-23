;(function ($, document) {
    $(document).ready(function () {
        // сраный костыль для сраного iCheck, который навязан всем чекбоксам без разбора
        setTimeout(function () {
            if ($.fn.iCheck) {
                $('.betterspoiler > [class^="icheckbox_"] input, .spoiler > [class^="icheckbox_"] input').iCheck('destroy');

                $('.betterspoiler > input ~ a:last-of-type').on('click', function () {
                    $(this).prevAll('input[type=checkbox]').trigger('click');

                    return false;
                });
            }
        }, 1000);
    });
}(jQuery, document));
