<?php

require_once __DIR__ . '/includes/comments.php';
require_once __DIR__ . '/includes/voting.php';

add_filter( 'spine_child_theme_version', 'wsu_president_theme_version' );
add_filter( 'getarchives_where', 'wsu_president_filter_archives' );
add_filter( 'ppp_nonce_life', 'wsu_president_filter_ppp_nonce_life' );

/**
 * Provides a theme version for use in cache busting.
 *
 * @since  0.1.0
 * @return string
 */
function wsu_president_theme_version() {
	return '0.1.0';
}

/**
 * Only display monthly archive links from March 2016.
 *
 * @since  0.0.3
 * @param  string $sql_where Portion of SQL query containing the WHERE clause.
 * @return string
 */
function wsu_president_filter_archives( $sql_where ) {

	// Bail if the site isn't under the president.wsu.edu domain.
	if ( 'president.wsu.edu' !== get_site()->domain ) {
		return $sql_where;
	}

	return $sql_where . " AND post_date > '2016-03-27 00:00:00' ";
}

/**
 * Extends the length of a public post preview URL.
 *
 * @since  0.0.4
 * @return int
 */
function wsu_president_filter_ppp_nonce_life() {
	return 60 * 60 * 24 * 5;
}
