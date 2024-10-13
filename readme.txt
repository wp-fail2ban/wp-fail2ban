=== WP fail2ban ===
Contributors: invisnet
Author URI: https://charles.lecklider.org/
Plugin URI: https://charles.lecklider.org/wordpress/wp-fail2ban/
Tags: fail2ban, security, syslog, login
Requires at least: 3.4.0
Tested up to: 3.6
Stable tag: 2.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Write all login attempts to syslog for integration with fail2ban.

== Description ==

[fail2ban](http://www.fail2ban.org/) is one of the simplest and most effective security measures you can implement to prevent brute-force password-guessing attacks.

*WP fail2ban* logs all login attempts, whether successful or not, to syslog using LOG_AUTH. To make log parsing as simple as possible *WPf2b* uses the same format as sshd. For example:

	Oct 17 20:59:54 foobar wordpress(www.example.com)[1234]: Authentication failure for admin from 192.168.0.1
	Oct 17 21:00:00 foobar wordpress(www.example.com)[2345]: Accepted password for admin from 192.168.0.1

*WPf2b* comes with a `fail2ban` filter, `wordpress.conf`.

Requires PHP 5.3 or later.

== Installation ==

1. Upload the plugin to your plugins directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Copy `wordpress.conf` to your `fail2ban/filters.d` directory
1. Edit `jail.local` to include something like:

	`[wordpress]`
	`enabled = true`
	`filter = wordpress`
	`logpath = /var/log/auth.log`

1. Reload or restart `fail2ban`

You may want to set WP_FAIL2BAN_BLOCK_USER_ENUMERATION, WP_FAIL2BAN_PROXIES and/or WP_FAIL2BAN_BLOCKED_USERS; see the FAQ for details.

== Frequently Asked Questions ==

= WP_FAIL2BAN_BLOCK_USER_ENUMERATION - what's it all about? =

Brute-forcing WP requires knowing a valid username. Unfortunately, WP makes this all but trivial.

Based on a suggestion from *geeklol* and a plugin by *ROIBOT*, *WPf2b* can now block user enumeration attempts. Just add the following to `wp-config.php`:

	define('WP_FAIL2BAN_BLOCK_USER_ENUMERATION',true);

= WP_FAIL2BAN_PROXIES - what's it all about? =

The idea here is to list the IP addresses of the trusted proxies that will appear as the remote IP for the request. When defined:

*	If the remote address appears in the `WP_FAIL2BAN_PROXIES` list, *WPf2b* will log the IP address from the `X-Forwarded-For` header
*	If the remote address does not appear in the `WP_FAIL2BAN_PROXIES` list, *WPf2b* will return a 403 error
*	If there's no X-Forwarded-For header, *WPf2b* will behave as if `WP_FAIL2BAN_PROXIES` isn't defined

To set `WP_FAIL2BAN_PROXIES`, add something like the following to `wp-config.php`:

	define('WP_FAIL2BAN_PROXIES','192.168.0.42,192.168.42.0/24');

*WPf2b* doesn't do anything clever with the list - beware of typos!

= WP_FAIL2BAN_BLOCKED_USERS - what's it all about? =

The bots that try to brute-force WordPress logins aren't that clever (no doubt that will change), but they may only make one request per IP every few hours in an attempt to avoid things like `fail2ban`. With large botnets this can still create significant load.

Based on a suggestion from *jmadea*, *WPf2b* now allows you to specify a regex that will shortcut the login process if the requested username matches.

For example, putting the following in `wp-config.php`:

	define('WP_FAIL2BAN_BLOCKED_USERS','^admin$');

will block any attempt to log in as `admin` before most of the core WordPress code is run. Unless you go crazy with it, a regex is usually cheaper than a call to the database so this should help keep things running during an attack.

*WPf2b* doesn't do anything to the regex other than make it case-insensitive.

= Why is fail2ban complaining on my flavour of Linux? =

Depending on your `fail2ban` configuration, you may need to add a line like:

	port = http,https

to the `[wordpress]` section in `jail.local`.

== Changelog ==

= 2.1.0 =
*	Add support for blocking user enumeration; see `WP_FAIL2BAN_BLOCK_USER_ENUMERATION`
*	Add support for CIDR notation in `WP_FAIL2BAN_PROXIES`.

= 2.0.1 =
*	Bugfix in *experimental* `WP_FAIL2BAN_PROXIES` code.

= 2.0.0 =
*	Add *experimental* support for X-Forwarded-For header; see `WP_FAIL2BAN_PROXIES`
*	Add *experimental* support for regex-based login blocking; see `WP_FAIL2BAN_BLOCKED_USERS`

= 1.2.1 =
*	Update FAQ.

= 1.2 =
*	Fix harmless warning.

= 1.1 =
*	Minor cosmetic updates.

= 1.0 =
*	Initial release.

== Upgrade Notice ==

= 2.1.0 =
The `wordpress.conf` filter has been updated; you will need to update your `fail2ban` configuration.

= 2.0.1 =
Bugfix in experimental code; still an experimental release.

= 2.0.0 =
This is an experimental release. If your current version is working and you're not interested in the new features, skip this version - wait for 2.1.0. For those that do want to test this release, note that `wordpress.conf` has changed - you'll need to copy it to `fail2ban/filters.d` again.
