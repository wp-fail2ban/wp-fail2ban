<?php
/*
Plugin Name: WP fail2ban
Plugin URI: https://charles.lecklider.org/wordpress/wp-fail2ban/
Description: Write all login attempts to syslog for integration with fail2ban.
Version: 2.0.0
Author: Charles Lecklider
Author URI: https://charles.lecklider.org/
License: GPL2
*/

/*  Copyright 2012-13  Charles Lecklider  (email : wordpress@charles.lecklider.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace org\lecklider\charles\wp_fail2ban;

function openlog()
{
	\openlog('wordpress('.$_SERVER['HTTP_HOST'].')',
			 LOG_NDELAY|LOG_PID,
			 defined(WP_FAIL2BAN_LOG) ? WP_FAIL2BAN_LOG : LOG_AUTH);
}

function bail()
{
	ob_end_clean();
	header('HTTP/1.0 403 Forbidden');
	header('Content-Type: text/plain');
	exit('Forbidden');
}

function remote_addr()
{
	$ip = $_SERVER['REMOTE_ADDR'];

	if (defined('WP_FAIL2BAN_PROXIES')) {
		if (array_key_exists($_SERVER,'HTTP_X_FORWARDED_FOR')) {
			if (in_array($ip, explode(',',WP_FAIL2BAN_PROXIES) )) {
				$ip = (false===($len = strpos($_SERVER['HTTP_X_FORWARDED_FOR'],',')))
						? $_SERVER['HTTP_X_FORWARDED_FOR']
						: substr($_SERVER['HTTP_X_FORWARDED_FOR'],0,$len);
			} else {
				bail();
			}
		}
	}

	return $ip;
}

if (defined('WP_FAIL2BAN_BLOCKED_USERS')) {
	add_action( 'authenticate',
				function($user, $username, $password)
				{
					if (!empty($username) && preg_match('/'.WP_FAIL2BAN_BLOCKED_USERS.'/i', $username)) {
						openlog();
						\syslog(LOG_NOTICE,"Blocked authentication attempt for $username from ".remote_addr());
						bail();
					}

					return $user;
				},1,3);
}
add_action( 'wp_login',
			function($user_login, $user)
			{
				openlog();
				\syslog(LOG_INFO,"Accepted password for $user_login from ".remote_addr());
			},10,2);
add_action( 'wp_login_failed',
			function($username)
			{
				openlog();
				\syslog(LOG_NOTICE,"Authentication failure for $username from ".remote_addr());
			});

