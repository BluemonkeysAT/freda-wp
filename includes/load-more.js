jQuery(document).ready(function($) {
    $('#single-cat-load-more').on('click', function() {
        let button = $(this);
        let page = parseInt(button.data('page')) + 1;
        let category = button.data('category');

        $('#load-more-spinner').show(); // Show spinner

        $.ajax({
            url: ajax_params.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'load_more_posts',
                page: page,
                category: category
            },
            success: function(response) {
                $('#load-more-spinner').hide(); // Hide spinner

                if ($.trim(response.html) !== '') {
                    $('.category-recent-posts > .selected-posts').append(response.html);
                    button.data('page', page);

                    if (page >= response.max_pages) {
                        button.hide();
                    }
                } else {
                    button.hide();
                }
            },
            error: function() {
                $('#load-more-spinner').hide(); // Always hide on error too
            }
        });
    });
});
