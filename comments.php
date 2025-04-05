<?php
/**
 * The template for displaying comments
 *
 * @package Comic_Theme
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area mt-5">

    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comic_comment_count = get_comments_number();
            if ('1' === $comic_comment_count) {
                printf(
                    esc_html__('1 bình luận cho &ldquo;%1$s&rdquo;', 'comic-theme'),
                    '<span>' . get_the_title() . '</span>'
                );
            } else {
                printf(
                    esc_html(_n('%1$s bình luận cho &ldquo;%2$s&rdquo;', '%1$s bình luận cho &ldquo;%2$s&rdquo;', $comic_comment_count, 'comic-theme')),
                    number_format_i18n($comic_comment_count),
                    '<span>' . get_the_title() . '</span>'
                );
            }
            ?>
        </h2><!-- .comments-title -->

        <div class="comment-list-container">
            <ol class="comment-list">
                <?php
                wp_list_comments(array(
                    'style'      => 'ol',
                    'short_ping' => true,
                    'avatar_size' => 60,
                    'reply_text' => '<i class="fas fa-reply"></i> ' . __('Trả lời', 'comic-theme'),
                    'max_depth'  => 5,
                ));
                ?>
            </ol><!-- .comment-list -->
        </div>

        <?php
        the_comments_navigation(array(
            'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Cũ hơn', 'comic-theme'),
            'next_text' => __('Mới hơn', 'comic-theme') . ' <i class="fas fa-chevron-right"></i>',
        ));

        // If comments are closed and there are comments, let's leave a little note, shall we?
        if (!comments_open()) :
            ?>
            <div class="alert alert-warning">
                <p class="no-comments"><?php esc_html_e('Bình luận đã bị đóng.', 'comic-theme'); ?></p>
            </div>
        <?php
        endif;

    endif; // Check for have_comments().

    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $html_req = ($req ? " required='required'" : '');
    
    $fields = array(
        'author' => '<div class="row"><div class="col-md-6 mb-3"><div class="form-group comment-form-author"><label for="author">' . __('Tên', 'comic-theme') . ($req ? ' <span class="required">*</span>' : '') . '</label><input id="author" name="author" type="text" class="form-control" value="' . esc_attr($commenter['comment_author']) . '" size="30" maxlength="245"' . $html_req . '></div></div>',
        'email' => '<div class="col-md-6 mb-3"><div class="form-group comment-form-email"><label for="email">' . __('Email', 'comic-theme') . ($req ? ' <span class="required">*</span>' : '') . '</label><input id="email" name="email" type="email" class="form-control" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $html_req . '></div></div></div>',
        'url' => '<div class="form-group comment-form-url mb-3"><label for="url">' . __('Website', 'comic-theme') . '</label><input id="url" name="url" type="url" class="form-control" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" maxlength="200"></div>',
        'cookies' => '<div class="form-group form-check comment-form-cookies-consent mb-3"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" class="form-check-input" value="yes"' . (empty($commenter['comment_author_email']) ? '' : ' checked="checked"') . ' /><label class="form-check-label" for="wp-comment-cookies-consent">' . __('Lưu tên, email và website của tôi trong trình duyệt này cho lần bình luận kế tiếp.', 'comic-theme') . '</label></div>',
    );

    $comments_args = array(
        'fields' => $fields,
        'comment_field' => '<div class="form-group comment-form-comment mb-3"><label for="comment">' . __('Bình luận', 'comic-theme') . ' <span class="required">*</span></label><textarea id="comment" name="comment" class="form-control" rows="5" required="required"></textarea></div>',
        'class_form' => 'comment-form needs-validation',
        'class_submit' => 'btn btn-primary',
        'title_reply' => __('Để lại bình luận', 'comic-theme'),
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
        'title_reply_after' => '</h3>',
        'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s"><i class="fas fa-paper-plane me-1"></i> %4$s</button>',
        'submit_field' => '<div class="form-submit">%1$s %2$s</div>',
    );

    comment_form($comments_args);
    ?>

</div><!-- #comments -->
