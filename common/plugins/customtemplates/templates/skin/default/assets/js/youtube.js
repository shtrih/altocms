$(function () {
    // Подгружающийся по клику плеер youtube
    $(document).on('click', '[data-youtube-id]', function () {
        var self = $(this);
        self.prev().attr('src', "http://www.youtube.com/embed/"+self.data('youtube-id')+"?wmode=opaque&theme=light&autoplay=1");
        self.remove();
    });
});
