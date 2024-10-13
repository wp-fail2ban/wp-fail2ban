<?php
/*
Plugin Name: WP fail2ban
Plugin URI: https://charles.lecklider.org/wordpress/wp-fail2ban/
Description: Write all login attempts to syslog for integration with fail2ban.
Version: 1.0
Author: Charles Lecklider
Author URI: https://charles.lecklider.org/
License: GPL2
*/


add_action( 'wp_login',
			function($user_login, $user)
			{
				openlog('wordpress('.$_SERVER['HTTP_HOST'].')',LOG_NDELAY|LOG_PID,LOG_AUTH);
				syslog(LOG_INFO,"Accepted password for $user_login from {$_SERVER['REMOTE_ADDR']}");
			});
add_action( 'wp_login_failed',
			function($username)
			{
				openlog('wordpress('.$_SERVER['HTTP_HOST'].')',LOG_NDELAY|LOG_PID,LOG_AUTH);
				syslog(LOG_NOTICE,"Authentication failure for $username from {$_SERVER['REMOTE_ADDR']}");
			});

