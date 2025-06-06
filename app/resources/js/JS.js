$('.like-button').on('click', function () {
    const postId = $(this).data('post-id');

    $.ajax({
        url: '/like/toggle',
        type: 'POST',
        data: {
            post_id: postId,
            _token: $('meta[name="csrf-token"]').attr('content'),
        },
        success: function (data) {
            if (data.liked) {
                $('.like-button').addClass('liked');
            } else {
                $('.like-button').removeClass('liked');
            }
            $('.like-count').text(data.count);
        }
    });
});

$('#comment-form').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/comments',
        method: 'POST',
        data: $(this).serialize(),
        success: function(data) {
            $('#comment-list').append(`
                <div class="comment">
                    <strong>${data.user_name}</strong>: ${data.comment.comment}
                </div>
            `);
            $('#comment-input').val('');
        }
    });
});
