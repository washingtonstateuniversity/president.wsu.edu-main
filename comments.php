<?php
/**
 * The template for displaying comments and the comment form.
 *
 * Modified from Twenty Seventeen.
 *
 * @since 0.1.0
 */

// Bail if the post is password protected and the user hasn't entered one.
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php
	// Display the comment list.
	if ( have_comments() ) {
		get_template_part( 'parts/comment-list' );
	}

	// Display a notice if comments are closed and there are comments.
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
		?><p class="no-comments">Comments are closed.</p><?php
	}

	// Only allow comments from logged-in users.
	if ( comments_open() ) {
		comment_form( WSU\President\Comments\form_arguments() );
	}
	?>

</div>
