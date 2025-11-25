jQuery(document).ready(function($) {
    $('#load-more-posts').on('click', function() {
        var button = $(this),
            offset = button.data('offset');

        button.text('Ładowanie...');
        button.prop('disabled', true);

        $.post(ajax_params.ajax_url, {
            action: 'load_more_posts',
            offset: offset
        }).done(function(response) {
            if ($.trim(response) === '') {
                button.remove();
            } else {
                $('.pwe-posts-wrapper').append(response);
                button.data('offset', offset + 18);
                button.text('Załaduj więcej');
                button.prop('disabled', false);
            }
        });
    });
});
