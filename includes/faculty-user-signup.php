<?php

namespace WSU\President\Faculty_User_Signup;

// If a user authenticates with WSU AD, and they don't exist as a user, add them as a user.
add_filter( 'wsuwp_sso_create_new_user', '__return_true' );
add_action( 'wsuwp_sso_user_created', __NAMESPACE__ . '\\new_user', 10, 1 );
add_action( 'admin_menu', __NAMESPACE__ . '\\user_auto_role' );

/**
 * Add new users created through the SSO plugin to the site as Subscribers,
 * if they are in the Faculty AD group.
 *
 * @since 0.2.0
 *
 * @param int $user_id
 */
function new_user( $user_id ) {
	$user = new \WP_User( $user_id );
	$user_ad_data = WSUWP_SSO_Authentication()->refresh_user_data( $user );

	if ( in_array( 'Employees.Active.Faculty', $user_ad_data['memberof'], true ) ) {
		add_user_to_blog( get_current_blog_id(), $user_id, 'subscriber' );
	}
}
/**
 * Add logged in users in the admin screen to the site,
 * if they are in the Faculty AD group.
 *
 * @since 0.2.0
 */
function user_auto_role() {
	if ( is_user_logged_in() && ! is_user_member_of_blog() ) {
		$user = new \WP_User( get_current_user_id() );
		$user_ad_data = WSUWP_SSO_Authentication()->refresh_user_data( $user );

		if ( in_array( 'Employees.Active.Faculty', $user_ad_data['memberof'], true ) ) {
			add_user_to_blog( get_current_blog_id(), get_current_user_id(), 'subscriber' );
			wp_safe_redirect( admin_url() );

			exit;
		}
	}
}
