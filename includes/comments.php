<?php

namespace WSU\President\Comments;

add_filter( 'comment_form_fields', __NAMESPACE__ . '\\form_fields' );

/**
 * Removes the URL field from the comment form.
 * Repositions the comment text area below the name and email fields.
 *
 * @since  0.1.0
 * @param  array $fields The default comment form fields.
 * @return array
 */
function form_fields( $fields ) {
	$comment_field = $fields['comment'];

	unset( $fields['url'] );
	unset( $fields['comment'] );

	$fields['comment'] = $comment_field;

	return $fields;
}

/**
 * Returns the comments title.
 *
 * @since  0.1.0
 * @return string
 */
function display_title() {
	$comments_number = get_comments_number();

	if ( '1' === $comments_number ) {
		/* translators: %s: post title */
		printf( esc_html( _x( 'One Reply to &ldquo;%s&rdquo;', 'comments title', 'twentyseventeen' ) ), get_the_title() );
	} else {
		printf(
			/* translators: 1: number of comments, 2: post title */
			esc_html( _nx(
				'%1$s Reply to &ldquo;%2$s&rdquo;',
				'%1$s Replies to &ldquo;%2$s&rdquo;',
				$comments_number,
				'comments title',
				'twentyseventeen'
			) ),
			absint( number_format_i18n( $comments_number ) ),
			get_the_title()
		);
	}
}

/**
 * Returns the arguments for the `wp_list_comments` function.
 *
 * @since  0.1.0
 * @return array
 */
function list_arguments() {
	return array(
		'max_depth' => 2,
		'style' => 'div',
		'type' => 'comment',
		'avatar_size' => 0,
		'format' => 'html5',
	);
}

/**
 * Returns the arguments for the `comment_form` function.
 *
 * @since  0.1.0
 * @return array
 */
function form_arguments() {
	return array(
		'fields' => array(
			'author' => '<p class="comment-form-author"><label for="author">Name</label> <input id="author" name="author" value="" maxlength="245" required="required" type="text"></p>',
			'email' => '<p class="comment-form-email"><label for="email">Email Address</label> <input id="email" name="email" value="" maxlength="100" required="required" type="email"></p>',
		),
		'comment_field' => '<p class="comment-form-comment"><label for="comment">Comments</label> <textarea id="comment" name="comment" maxlength="65525" aria-required="true" required="required"></textarea></p>',
		'comment_notes_before' => '',
		'title_reply' => '',
		'title_reply_to' => '',
		'title_reply_before' => '',
		'title_reply_after' => '',
		'cancel_reply_before' => '',
		'cancel_reply_after' => '',
		'cancel_reply_link' => '',
		'label_submit' => 'Submit comment',
		'format' => 'html5',
	);
}
