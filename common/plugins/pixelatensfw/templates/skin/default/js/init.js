$(window).on('load', function () {
    var topics_nsfw = $('.nsfw-pictures'),
        initialized = false,
        need_pixelate = $.cookie('pixelatensfw') == null ? true : !!parseInt($.cookie('pixelatensfw'))
    ;

    function init_pixelate() {
        $('.topic-text img:not([id^=jp_poster_]),.topic-photoset img:not([id^=jp_poster_])', topics_nsfw).pixelate({
            value: PIXELATENSFW_VALUE,
            reveal:PIXELATENSFW_REVEAL,
            revealonclick: PIXELATENSFW_REVEALONCLICK
        });

        $('a', topics_nsfw).has('img')
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

        $('canvas:not(.pixelated > canvas)', topics_nsfw).on('mouseenter', function () {
            $(this).hide().next('img').show();
        });
        $('canvas:not(.pixelated > canvas) + img', topics_nsfw).on('mouseleave', function () {
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
        var a_pixelate = $('<a href="#"><i class="fa"></i>&nbsp;Замутнять картинки в nsfw-постах</a>'),
            a_notpixelate = $('<a href="#"><i class="fa"></i>&nbsp;Не замутнять</a>')
        ;

        if (need_pixelate)
            a_pixelate.children('i').addClass('fa-check');
        else
            a_notpixelate.children('i').addClass('fa-check');

        $('<li class="dropdown">' +
            '<a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#"><i class="fa fa-cog muted"></i></a>' +
            '<ul class="dropdown-menu">' +
                '<li></li>' +
                '<li></li>' +
            '</ul>' +
        '</li>').insertAfter(
            $('.topic-footer .topic-comments', topics_nsfw).first()
        )
        .find('.dropdown-menu > li')
            .eq(0).append(a_pixelate)
            .end()
            .eq(1).append(a_notpixelate)
        ;

        a_pixelate.on('click', function () {
            $(this).children('i').addClass('fa-check');
            a_notpixelate.children('i').removeClass('fa-check');

            $.cookie('pixelatensfw', 1, {path: '/', expires: 365});

            if (!initialized)
                init_pixelate();

            return false;
        });

        a_notpixelate.on('click', function () {
            $(this).children('i').addClass('fa-check');
            a_pixelate.children('i').removeClass('fa-check');

            $.cookie('pixelatensfw', 0, {path: '/', expires: 365});

            destroy_pixelate();

            return false;
        });
    }());
});
