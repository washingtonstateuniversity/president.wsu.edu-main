<?php
/**
 * The template for displaying comments.
 *
 * @since 0.1.7
 */

?>
<h2 class="comments-title"><?php WSU\President\Comments\display_title(); ?></h2>

<div class="comment-list">
	<?php wp_list_comments( WSU\President\Comments\list_arguments() ); ?>
</div>

<?php
the_comments_pagination();
