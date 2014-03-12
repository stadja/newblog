var isLoading = false;

if (navigator.onLine) {
    $('.ajax-loader').slideDown('500');
}

var loadIfBottom = function() {
    var scroll = $(document).height() - ($(window).height() + $(window).scrollTop());
    if (scroll && isLoading) {
        return;
    }
    if (scroll < 50) {
        isLoading = true;
        $.ajax({
            dataType: 'json',
            url: '/posts/offset/' + $('#posts_infinite').attr('value'),
            success: function(data) {
                if (data.count == '0') {
                    $(window).unbind('scroll');
                    $('.ajax-loader').slideUp('500');
                    return false;
                }
                var count = Number($('#posts_infinite').attr('value')) + Number(data.count);
                $('#posts_infinite').attr('value', count);
                var new_dom = document.createElement('div');
                $(new_dom).addClass('post_ajax');
                $(new_dom).append(data.html);
                $(new_dom).hide();

                $('#posts_infinite').append($(new_dom));
                $(new_dom).slideDown('2000');
                $(new_dom).show();

                isLoading = false;
                loadIfBottom();
            }
        });
    }
};

$(window).scroll(loadIfBottom);

loadIfBottom();