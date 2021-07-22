<?php
/**
 * Plugin Name: Freistil Caching
 * Description: Enforce caching and ignore cookies
 * Version: 1.0.1
 * Author: Palasthotel <rezeption@palasthotel.de> (in person: Edward Bock)
 * Author URI: http://www.palasthotel.de
 * Requires at least: 5.0
 * Tested up to: 5.7.2
 * License: http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @copyright Copyright (c) 2021, Palasthotel
 *
 */

namespace Palasthotel\WordPress\FreistilCaching;

const ENFORCE_CACHE_COOKIE_NAME  = "cache_enforcer";
const ENFORCE_CACHE_COOKIE_VALUE = "on";

/**
 * set cookie
 */
function setEnforceCacheCookie() {
	if(!headers_sent()){
		setcookie( ENFORCE_CACHE_COOKIE_NAME, ENFORCE_CACHE_COOKIE_VALUE, 0, COOKIEPATH, COOKIE_DOMAIN );
	}
}

/**
 * delete cookie
 */
function unsetEnforceCacheCookie() {
	if(!headers_sent()){
		setcookie( ENFORCE_CACHE_COOKIE_NAME, "", time() - 3600 );
		setcookie( ENFORCE_CACHE_COOKIE_NAME, "", time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
	}
}

/**
 * on init
 */
function init() {
	if ( is_user_logged_in() ) {
		unsetEnforceCacheCookie();
	} else {
		setEnforceCacheCookie();
	}
}
add_action( 'init', __NAMESPACE__.'\init');

/**
 * when user was successfully logged in
 *
 */
function wp_login() {
	unsetEnforceCacheCookie();
}
add_action( 'wp_login', __NAMESPACE__.'\wp_login' );

/**
 * when logged out
 */
function wp_logout() {
	setEnforceCacheCookie();
}
add_action( 'wp_logout', __NAMESPACE__.'\wp_logout');
