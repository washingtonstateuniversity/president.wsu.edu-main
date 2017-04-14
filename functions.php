<?php

add_filter( 'getarchives_where', 'wsu_president_filter_archives' );

function wsu_president_filter_archives( $sql_where ) {
	return $sql_where . " AND post_date > '2016-03-27 00:00:00' ";
}

add_filter( 'ppp_nonce_life', 'wsu_president_filter_ppp_nonce_life' );
/**
 * Extends the length of a public post preview URL.
 *
 * @return int
 */
function wsu_president_filter_ppp_nonce_life() {
	return 60 * 60 * 24 * 5;
}
