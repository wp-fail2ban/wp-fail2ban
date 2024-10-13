=== WP fail2ban ===
Contributors: invisnet
Author URI: https://charles.lecklider.org/
Plugin URI: https://charles.lecklider.org/wordpress/fail2ban/
Tags: fail2ban, security, syslog, login
Requires at least: 3.4.0
Tested up to: 3.4.2
Stable tag: 1.0
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
	`action = pf`  
	`logpath = /var/log/auth.log`

1. Reload or restart `fail2ban`

There are no options to configure.

== Changelog ==

= 1.0 =
Initial release.
