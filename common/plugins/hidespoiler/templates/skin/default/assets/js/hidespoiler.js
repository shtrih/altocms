;(function ($, document) {
    $(document).ready(function () {
        // сраный костыль для сраного iCheck, который навязан всем чекбоксам без разбора
        setTimeout(function () {
            $('.hidespoiler > [class^="icheckbox_"] input, .spoiler > [class^="icheckbox_"] input').iCheck('destroy');
        }, 1000);
    });
}(jQuery, document));