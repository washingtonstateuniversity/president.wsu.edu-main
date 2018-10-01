<?php
/**
 * The template for displaying the voting feature.
 *
 * @since 0.1.0
 */

$vote_tally = WSU\President\Voting_Booth\tally( get_the_ID() );
$voter_ids = get_post_meta( get_the_ID(), '_wsu_votes_ids', true );
$disabled = false;

if ( $voter_ids && is_array( $voter_ids ) && in_array( wp_get_current_user()->ID, $voter_ids, true ) ) {
	$disabled = true;
}
?>

<form class="wsu-voting-booth" data-post-id="<?php echo esc_attr( get_the_ID() ); ?>">

	<div class="vote-tally">Vote tally: <span><?php echo esc_html( $vote_tally ); ?></span></div>

	<?php if ( ! $disabled && is_user_logged_in() ) { ?>

		<label>
			<input type="radio" name="vote" value="for">
			<span class="dashicons dashicons-thumbs-up">Click here to vote for this post</span>
		</label>

		<label>
			<input type="radio" name="vote" value="against">
			<span class="dashicons dashicons-thumbs-down">Click here to vote against this post</span>
		</label>

		<button type="submit" disabled>Vote</button>

		<div class="thank-you" aria-hidden="true">Thank you for casting your vote!</div>

	<?php } elseif ( is_user_logged_in() && $disabled ) { ?>

		<div class="vote-cast">You have already voted on this post.</div>

	<?php } else { ?>

		<div class="vote-cast"><a href="http://facsen.wp.wsu.edu/wp-login.php?redirect_to=<?php echo 'https://' . esc_attr( $_SERVER['HTTP_HOST'] ) . esc_attr( $_SERVER['REQUEST_URI'] ); ?>">Login</a> with WSU Credentials to Vote.</div>

	<?php } ?>

</form>
