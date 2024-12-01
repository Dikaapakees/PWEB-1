$(document).ready(function() {
    $('.photo').each(function(index) {
        $(this).delay(index * 300).fadeTo(1000, 1);
    });

    $('.photo').click(function() {
        var imgSrc = $(this).attr('src');
        $('#modalImage').attr('src', imgSrc);
        $('#imageModal').fadeIn();
    });

    $('.close').click(function() {
        $('#imageModal').fadeOut();
    });

    $(window).click(function(event) {
        if ($(event.target).is('.modal')) {
            $('#imageModal').fadeOut();
        }
    });
});
