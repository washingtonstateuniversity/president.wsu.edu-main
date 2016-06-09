<?php

add_filter( 'getarchives_where', 'wsu_president_filter_archives' );

function wsu_president_filter_archives( $sql_where ) {
	return $sql_where . " AND post_date > '2016-03-27 00:00:00' ";
}
