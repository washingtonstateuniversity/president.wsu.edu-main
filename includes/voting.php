<?php

namespace WSU\President\Voting_Booth;

add_action( 'add_meta_boxes_post', __NAMESPACE__ . '\\add_meta_boxes' );
add_action( 'save_post_post', __NAMESPACE__ . '\\save_post', 10, 2 );
add_filter( 'wp_ajax_wsu_cast_vote', __NAMESPACE__ . '\\cast_vote' );

/**
 * Adds the metabox used for enabling voting for a post.
 *
 * @since 0.1.0
 */
function add_meta_boxes() {
	add_meta_box(
		'wsu-voting-meta',
		'Voting',
		__NAMESPACE__ . '\\display_voting_meta_box',
		'post',
		'normal',
		'high'
	);
}

/**
 * Adds the metabox used for enabling voting for a post.
 *
 * @since 0.1.0
 * @param WP_Post $post Object for the post currently being edited.
 */
function display_voting_meta_box( $post ) {
	wp_nonce_field( 'save-wsu-votes-meta', '_wsu_votes_meta_nonce' );

	$enabled = get_post_meta( $post->ID, '_wsu_votes', true );
	$tally = tally( $post->ID );
	$votes_for = get_post_meta( $post->ID, '_wsu_votes_for', true );
	$votes_against = get_post_meta( $post->ID, '_wsu_votes_against', true );
	$for_count = ( $votes_for ) ? absint( $votes_for ) : 0;
	$against_count = ( $votes_against ) ? absint( $votes_against ) : 0;

	?>
	<label><input type="checkbox" name="_wsu_votes" value="enabled" <?php checked( 'enabled', $enabled ); ?>> Allow voting</label>
	<p>
		<span class="description">Vote tally:</span> <?php echo esc_html( $tally ); ?>
		(<span class="description">for:</span> <?php echo esc_html( $for_count ); ?>,
		<span class="description">against:</span> <?php echo esc_html( $against_count ); ?>)
	</p>
	<?php
}

/**
 * Saves the flag to allow voting for a post.
 *
 * @since 0.1.0
 * @param int     $post_id ID of the post being saved.
 * @param WP_Post $post    Post object of the post being saved.
 */
function save_post( $post_id, $post ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'auto-draft' === $post->post_status ) {
		return;
	}

	if ( ! isset( $_POST['_wsu_votes_meta_nonce'] ) || ! wp_verify_nonce( $_POST['_wsu_votes_meta_nonce'], 'save-wsu-votes-meta' ) ) {
		return;
	}

	if ( isset( $_POST['_wsu_votes'] ) && 'enabled' === $_POST['_wsu_votes'] ) {
		update_post_meta( $post_id, '_wsu_votes', 'enabled' );
	} else {
		delete_post_meta( $post_id, '_wsu_votes' );
	}
}

/**
 * AJAX callback for vote casting.
 *
 * @since 0.1.0
 */
function cast_vote() {
	check_ajax_referer( 'wsu-voting', 'nonce' );

	// Bail if the current user is not logged in.
	if ( ! is_user_logged_in() ) {
		return;
	}

	// Bail if the necessary data wasn't POSTed.
	if ( ! isset( $_POST['post_id'] ) || ! isset( $_POST['vote'] ) ) {
		return;
	}

	$post_id = absint( $_POST['post_id'] );

	// Bail if this post doesn't exist.
	if ( false === get_post_status( $post_id ) ) {
		return;
	}

	$voter_ids = get_post_meta( $post_id, '_wsu_votes_ids', true );

	// Bail if this user has already voted on the given post.
	if ( $voter_ids && is_array( $voter_ids ) && in_array( wp_get_current_user()->ID, $voter_ids, true ) ) {
		return;
	}

	$vote = sanitize_text_field( $_POST['vote'] );

	// Bail if the POSTed `vote` value isn't either `for` or `against`.
	if ( ! in_array( $vote, array( 'for', 'against' ), true ) ) {
		return;
	}

	// Update the voter ID meta value to include the current user's ID.
	$voter_ids[] = absint( wp_get_current_user()->ID );

	update_post_meta( $post_id, '_wsu_votes_ids', $voter_ids );

	if ( 'for' === $vote ) {
		$new_count = absint( get_post_meta( $post_id, '_wsu_votes_for', true ) ) + 1;
		update_post_meta( $post_id, '_wsu_votes_for', $new_count );
	} elseif ( 'against' === $vote ) {
		$new_count = absint( get_post_meta( $post_id, '_wsu_votes_against', true ) ) + 1;
		update_post_meta( $post_id, '_wsu_votes_against', $new_count );
	}

	$new_tally = tally( $post_id );

	$data = array(
		'new_tally' => $new_tally,
	);

	echo wp_json_encode( $data );

	exit();
}

/**
 * Provides the vote tally (for votes minus against votes).
 *
 * @since  0.1.0
 * @param  int $id The ID of the post for which to retrieve the vote tally.
 */
function tally( $id ) {
	$votes_for = get_post_meta( $id, '_wsu_votes_for', true );
	$votes_against = get_post_meta( $id, '_wsu_votes_against', true );
	$for_count = ( $votes_for ) ? absint( $votes_for ) : 0;
	$against_count = ( $votes_against ) ? absint( $votes_against ) : 0;
	$vote_count = absint( $for_count ) - absint( $against_count );

	return $vote_count;
}
