$(window).on('load', function () {
    var topics_nsfw = $('.topic-nsfw'),
        initialized = false,
        need_pixelate = $.cookie('pixelatensfw') === null ? true : !!parseInt($.cookie('pixelatensfw'))
    ;

    function init_pixelate() {
        $('.topic-content img', topics_nsfw).pixelate({
            value: PIXELATENSFW_VALUE,
            reveal:PIXELATENSFW_REVEAL,
            revealonclick: PIXELATENSFW_REVEALONCLICK
        });

        $('.unfoldable', topics_nsfw)
            // дабы не переопределять уже повешанный обработчик клика, добавляем новый класс и вешаем новое событие на него
            .addClass('pixelated')
            .filter('.pixelated')
            .on('click', function () {
                $(this).find('canvas').hide();
            })
            .on('mouseenter', function () {
                var self = $(this);
                if (!self.data('unfolded')) {
                    self.find('canvas').hide().next('img').show();
                }
            })
            .on('mouseleave', function () {
                var self = $(this);
                if (!self.data('unfolded')) {
                    self.find('canvas').show().next('img').hide();
                }
            })
        ;

        $('canvas:not(.unfoldable > canvas)', topics_nsfw).on('mouseenter', function () {
            $(this).hide().next('img').show();
        });
        $('canvas:not(.unfoldable > canvas) + img', topics_nsfw).on('mouseleave', function () {
            $(this).hide().prev('canvas').show();
        });
        // скопировать классы картинок, чтобы были одинаковые стили
        $('canvas + img', topics_nsfw).each(function () {
            var self = $(this);
            self.prev()
                .addClass(self.attr('class'))
                .attr('align', self.attr('align'))
            ;
        });

        initialized = true;
    }

    function destroy_pixelate() {
        if (initialized) {
            $('canvas', topics_nsfw)
                .next('img').show()
                .end()
                .remove()
            ;
            initialized = false;
        }
    }

    if (need_pixelate)
        init_pixelate();

    (function () {
        var a_pixelate = $('<a href="#"><i></i>Замутнять картинки в nsfw-постах</a>'),
            a_notpixelate = $('<a href="#"><i></i>Не замутнять</a>')
        ;

        if (need_pixelate)
            a_pixelate.children('i').addClass('icon-ok');
        else
            a_notpixelate.children('i').addClass('icon-ok');

        $('<li class="dropdown">' +
            '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#"><i class="icon-cog muted"></i></a>' +
            '<ul class="dropdown-menu">' +
                '<li></li>' +
                '<li></li>' +
            '</ul>' +
        '</li>').insertAfter(
            $('.topic-footer .label-important:first').parent()
        )
        .find('.dropdown-menu > li')
            .eq(0).append(a_pixelate)
            .end()
            .eq(1).append(a_notpixelate)
        ;

        a_pixelate.on('click', function () {
            $(this).children('i').addClass('icon-ok');
            a_notpixelate.children('i').removeClass('icon-ok');

            $.cookie('pixelatensfw', 1, {path: '/', expires: 5 * 365});

            if (!initialized)
                init_pixelate();

            return false;
        });

        a_notpixelate.on('click', function () {
            $(this).children('i').addClass('icon-ok');
            a_pixelate.children('i').removeClass('icon-ok');

            $.cookie('pixelatensfw', 0, {path: '/'});

            destroy_pixelate();

            return false;
        });
    }());
});