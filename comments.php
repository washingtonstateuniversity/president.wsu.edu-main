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

	<?php if ( have_comments() ) { ?>

		<h2 class="comments-title"><?php WSU\President\Comments\display_title(); ?></h2>

		<div class="comment-list">
			<?php wp_list_comments( WSU\President\Comments\list_arguments() ); ?>
		</div>

		<?php the_comments_pagination(); ?>

	<?php } ?>

	<?php
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
