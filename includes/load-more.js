jQuery(document).ready(function ($) {
    $(document).on('click', '.single-cat-load-more', function () {
        const button = $(this);
        let page = parseInt(button.data('page')) + 1;
        let category = button.data('category') || null;
        let postsPerPage = button.data('posts-per-page') || 6; // Default to 6 if not set

        $('#load-more-spinner').show();

        $.ajax({
            url: ajax_params.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'load_more_posts',
                page: page,
                category: category,
                posts_per_page: postsPerPage,
            },
            success: function (response) {
                $('#load-more-spinner').hide();

                if (response.success && response.data.html) {
                    $('.archive-load-more').append(response.data.html);
                    button.data('page', page);
                } else {
                    button.hide();
                }
            },
            error: function () {
                $('#load-more-spinner').hide();
            }
        });
    });
});