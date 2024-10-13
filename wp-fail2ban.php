<?php
/*
Plugin Name: WP fail2ban
Plugin URI: https://charles.lecklider.org/wordpress/wp-fail2ban/
Description: Write all login attempts to syslog for integration with fail2ban.
Version: 1.2
Author: Charles Lecklider
Author URI: https://charles.lecklider.org/
License: GPL2
*/

/*  Copyright 2012  Charles Lecklider  (email : wordpress@charles.lecklider.org)

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


add_action( 'wp_login',
			function($user_login, $user)
			{
				openlog('wordpress('.$_SERVER['HTTP_HOST'].')',LOG_NDELAY|LOG_PID,LOG_AUTH);
				syslog(LOG_INFO,"Accepted password for $user_login from {$_SERVER['REMOTE_ADDR']}");
			},10,2);
add_action( 'wp_login_failed',
			function($username)
			{
				openlog('wordpress('.$_SERVER['HTTP_HOST'].')',LOG_NDELAY|LOG_PID,LOG_AUTH);
				syslog(LOG_NOTICE,"Authentication failure for $username from {$_SERVER['REMOTE_ADDR']}");
			});

