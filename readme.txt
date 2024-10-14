=== WP fail2ban - Advanced Security Plugin ===
Contributors: invisnet
Author URI: https://invis.net/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1
Plugin URI: https://wp-fail2ban.com/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1
Tags: fail2ban, login, security, syslog, brute force, protection, classicpress
Requires at least: 4.2
Tested up to: 6.3
Stable tag: 5.2.1
Requires PHP: 7.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

*WP fail2ban* uses `fail2ban` to protect your WordPress site.

== Description ==

[fail2ban](http://www.fail2ban.org/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) is one of the simplest and most effective security measures you can implement to protect your WordPress site.

*WP fail2ban* provides the link between WordPress and `fail2ban`:

    Oct 17 20:59:54 foobar wordpress(www.example.com)[1234]: Authentication failure for admin from 192.168.0.1
    Oct 17 21:00:00 foobar wordpress(www.example.com)[2345]: Accepted password for admin from 192.168.0.1

*WPf2b* comes with three `fail2ban` filters: `wordpress-hard.conf`, `wordpress-soft.conf`, and `wordpress-extra.conf`. These are designed to allow a split between immediate banning (hard) and the traditional more graceful approach (soft), with extra rules for custom configurations.

= Features =

* **Failed Login Attempts**
  The very first feature of *WPf2b*: logging failed login attempts so the IP can be banned. Just as useful today as it was then.
  
* **Block User Enumeration**
  One of the most common precursors to a password-guessing brute force attack is [user enumeration](https://wp-fail2ban.com/features/block-user-enumeration/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1). *WPf2b* can block it, stopping the attack before it starts.

* **Block username logins**
  Sometimes it's not possible to block user enumeration (for example, if your theme provides Author profiles). *WPf2b* can require users to login with their email address instead of their username.

* **Blocking Users**
  Anther of the older *WPf2b* features: the login process can be aborted for specified usernames.
  Say a bot collected your site's usernames before you blocked user enumeration. Once you’ve changed all the usernames, add the old ones to the list; anything using them will trigger a "hard" fail.

* **Empty Username Login Attempts**
  Some bots will try to login without a username; harmless, but annoying. These attempts are logged as a "soft" fail so the more persistent bots will be banned.

* **Spam**
  *WPf2b* will log a spammer's IP address as a "hard" fail when their comment is marked as spam; the Premium version will also log the IP when Akismet discards "obvious" spam.

* **Attempted Comments**
  Some spam bots try to comment on everything, even things that aren't there. *WPf2b* detects these and logs them as a "hard" fail.

* **Pingbacks**
  Pingbacks are a great feature, but they can be abused to attack the rest of the WWW. Rather than disable them completely, *WPf2b* effectively rate-limits potential attackers by logging the IP address as a "soft" fail.

* **Block XML‑RPC Requests** [Premium]
  The only reason most sites need XML‑RPC (other than Pingbacks) is for Jetpack; *WPf2b* Premium can block XML‑RPC while allowing Jetpack and/or Pingbacks.

* **Block Countries** [Premium]
  Sometimes you just need a bigger hammer - if you’re seeing nothing but attacks from some countries, block them!

* **Cloudflare and Proxy Servers**
  *WPf2b* will work with [Cloudflare](https://wp-fail2ban.com/features/cloudflare-and-proxy-servers/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1), and the Premium version will automatically update the list of Cloudflare IP addresses.
  You can also configure your own list of trusted proxies.

* **syslog Dashboard Widget**
  Ever wondered what's being logged? The dashboard widget shows the last 5 messages; the Premium version keeps a full history to help you analyse and prevent attacks.

* **Site Health Check**
  *WPf2b* will (try to) check that your `fail2ban` configuration is sane and that the filters are up to date; out-of-date filters are the primary cause of *WPf2b* not working as well as it can.
  When did you last run the Site Health tool?

* **`mu-plugins` Support**
  *WPf2b* can easily be configured as a "must-use plugin" - see [Configuration](https://docs.wp-fail2ban.com/en/5.2/configuration.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1#mu-plugins-support).

* **API to Extend *WPf2b***
  If your plugin can detect behaviour which should be blocked, why reinvent the wheel?

* **Event Hooks** [Premium]
  Need to do something special when *WPf2b* detects a particular event? [There's a hook for that](https://docs.wp-fail2ban.com/en/5.2/developers/events.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

= Premium =

* Web Application Firewall (WAF)
* Akismet support.
* Block XML‑RPC while allowing Jetpack and/or Pingbacks.
* Block Countries.
* Auto-update Cloudflare IPs.
* Event log.
* Event hooks.

== Installation ==

1. Install via the Plugin Directory, or upload to your plugins directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Edit `wp‑config.php` to suit your needs - see [Configuration](https://docs.wp-fail2ban.com/en/5.2/configuration.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

== Changelog ==

= 5.2.1 =
* Fix bug in WAF when not logged in (h/t Rudi Diedrich). [Premium only]
* Site Health: Add support for `open_basedir` (h/t Jaroslav Huba).
* Update Freemius library.

= 5.2.0 =
* Add new filter file for WAF events. [Premium only]
* Add WAF feature: prevent unauthorised user deletion. [Premium only].
* Fix untrusted proxy message.
* Work-around for WooCommerce bug causing double logging of password reset requests (h/t @anuja).
* Site Health: Improve notifications.

= 5.1.1 =
* Update Freemius library.

= 5.1.0 =
* **Web Application Firewall**. [Premium only]
* Add PTR record lookup to event report. [Premium only]
* Improve report performance. [Premium only]
* Site Health: Check `fail2ban` is running.
* Site Health: Obsolete Filters - Detect DigitalOcean Droplet and link to documentation.
* Fix harmless warning (h/t @Yavor).
* Fix warning caused by bug in Google Sitekit (h/t @DaWolfey).
* Update Freemius library.

= 5.0.1 =
* Tweak Site Health notifications.
* Update Freemius library.

= 5.0.0 "Delphi" =
* **IPv6 support**.
* **Akismet support**. [Premium only]
* **Auto-update Cloudflare IPs**. [Premium only]
* **Event hooks**. [Premium only]
* **Performance improvements**:
  * Improve reports. [Premium only]
  * Cache IP lists. [Premium only]
  * Cache Plugin API message registration. [Premium only]
* Site Health: Check installed filters against previous versions.
* Moved "Authentication attempt for unknown user" to `wordpress-soft.conf`.
* Moved "extra" Comment messages to `wordpress-soft.conf`.
* Show date/time in local timezone (h/t @geniusmedia). [Premium only]
* Deprecate `WP_FAIL2BAN_LOG_COMMENTS_EXTRA` and `WP_FAIL2BAN_COMMENT_EXTRA_LOG`; use `WP_FAIL2BAN_LOG_COMMENT_ATTEMPTS` and `WP_FAIL2BAN_COMMENT_ATTEMPT_LOG` instead.
* Update Freemius library.

Please [read the notes](https://wp-fail2ban.com/blog/2023/01/16/upgrading-to-version-5/) **before** upgrading.

= 4.4.0.9 =
* Preparation for v5: prevent auto-updating across major release.
* Update Freemius library.

= 4.4.0.8 =
* Back-port fix for `mu-plugins` activation.
* Update Freemius library.

= 4.4.0.7 =
* Back-port fix for type error in menu-fixer when viewing Event Log (h/t @geniusmedia). [Premium only]
* Back-port fixes for event summaries. [Premium only]
* Update Freemius library.

= 4.4.0.6 =
* Fix initialisation error in event log. [Premium only]
* Fix type error in event log when no events available. [Premium only]
* Update Freemius library.

= 4.4.0.5 =
* Fix type error on Remote IPs tab with no MaxMind database configured (h/t @Tobias‑Conrad). [Premium only]
* Update Freemius library.

= 4.4.0.4 =
* Fix warning with array of blocked users (h/t @Znuff).
* Fix reports. [Premium only]

= 4.4.0.3 =
* Fix type error (h/t @brianshim).

= 4.4.0.2 =
* Add [`WP_FAIL2BAN_USE_AUTHPRIV`](https://docs.wp-fail2ban.com/en/4.4/defines/WP_FAIL2BAN_USE_AUTHPRIV.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) - a single place to switch to `LOG_AUTHPRIV` for systems without `LOG_AUTH`.
* Add [`WP_FAIL2BAN_FREE_ONLY`](https://docs.wp-fail2ban.com/en/4.4/defines/WP_FAIL2BAN_FREE_ONLY.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [`WP_FAIL2BAN_PLUGIN_LOG_OTHER`](https://docs.wp-fail2ban.com/en/4.4/defines/WP_FAIL2BAN_PLUGIN_LOG_OTHER.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) and [`WP_FAIL2BAN_PLUGIN_OTHER_LOG`](https://docs.wp-fail2ban.com/en/4.4/defines/WP_FAIL2BAN_PLUGIN_OTHER_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Improve performance.
* Moved cron event to update trusted Cloudflare IP ranges to the Cloudflare add-on. [Premium only]
* Add support for Pingbacks while blocking XML‑RPC. [Premium only]
* Update Freemius library.

= 4.3.2.2 =
* Add cron event to update trusted Cloudflare IP ranges weekly. [Premium only]
* Add cron event to update trusted Jetpack IP ranges weekly. [Premium only]
* Add cron event to update MaxMind database weekly. [Premium only]
* Workaround for missing `syslog` constants in Windows (h/t @dmarkowicz).
* Clarify upgrade message on Last 5 Messages widget. [Free only]
* Merge About and Status tabs. [Premium only]
* Update Freemius library.

= 4.3.2.1 =
* Add support for [WP fail2ban Blocklist](https://addons.wp-fail2ban.com/blocklist/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add new [Standard Configurations](https://github.com/wp-fail2ban/conf.d/).
* Improve Help links.
* Fix logging checkboxes [Premium only].
* Fix incorrect constant for disabling last messages (h/t @kermina).
* Fix false positive with blocking user enumeration when a Contributor tries to list posts by another user.
* Fix index issue with ancient versions of MySQL.
* Fix harmless warning with a defined but empty `WP_FAIL2BAN_PROXIES` (h/t @stevegrunwell).
* Back-port new Block event class.
* Update Freemius library.
* Change to GPLv3 with additional terms as per Section 7.

= 4.3.2.0 =
* Add support for blocking by Country. [Premium only]
* Add XML‑RPC blocking; allow trusted IPs and Jetpack (h/t @mhweb). [Premium only]

= 4.3.0.9 =
* Fix incorrect constant for disabling last messages (h/t @kermina).
* Fix false positive with blocking user enumeration when a Contributor tries to list posts by another user.
* Fix index issue with ancient versions of MySQL. [Premium only]
* Fix harmless warning with a defined but empty `WP_FAIL2BAN_PROXIES` (h/t @stevegrunwell).
* Back-port new Block event class.
* Update Freemius library.

= 4.3.0.8 =
* Workaround issue with user enumeration blocking being triggered by Gutenberg pre‑loading Author list. (h/t @brrrrrrrt) [WordPress only]

= 4.3.0.7 =
* Finish refactoring to allow inclusion of constants in `wp‑config.php` (h/t @iCounsellor).
* Fix MaxMind database update. [Premium only]

= 4.3.0.6 =
* Fix Forbidden error on Posts page for roles below Editor when user enumeration blocking enabled. [WordPress only]

= 4.3.0.5 =
* Fix empty username detection for multisite.
* Fix harmless warning when activating new multisite install.
* Fix esoteric edge-case where `wp‑load.php` is loaded via a script run from the CLI in a directory with a `functions.php` file.

= 4.3.0.4 "Columbo" =
* Add new dashboard widget: last 5 `syslog` messages.
* Add full [multisite support](https://wp-fail2ban.com/features/multisite-networks/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [username login blocking](https://wp-fail2ban.com/features/block-username-logins/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) (force login with email).
* Add [separate logging](https://wp-fail2ban.com/features/empty-username-logging/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) for login attempts with an empty username.
* Improve [user enumeration blocking](https://wp-fail2ban.com/features/block-user-enumeration/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) compatibility with the WordPress block editor (Gutenberg).
* Bump the minimum PHP version to 5.6.

= 4.2.8 =
* Add link to new [support forum](https://forums.invis.net/c/wp-fail2ban/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Fix user enumeration conflict with Gutenberg (h/t @dinghy).
* Fix notices wrt admin menu (h/t @marioivangf).
* Fix harmless XDebug notice (h/t @dinghy).
* Update Freemius library.

= 4.2.7.1 =
* Fix error when blocking user enumeration via `oembed` (h/t @wordpressfab).

= 4.2.7 =
* Fix error when blocking user enumeration via REST.
* Fix buttons on Settings tabs.

= 4.2.6 =
* Add support for [Remote Tools](https://wp-fail2ban.com/add-ons/remote-tools/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) add-on.
* Add support for the new ClassicPress security page.
* Improved user enumeration blocking.

= 4.2.5.1 =
* Fix premium activation issue with PHP < 7.0.

= 4.2.5 =
* Properly fix PHP 5.3 support; tested on CentOS 6. Does not support any UI or Premium features.
* Fix potential issue with `WP_FAIL2BAN_BLOCK_USER_ENUMERATION` if calling REST API or XML‑RPC from admin area.

= 4.2.4 =
* Add filter for login failed message.
* Fix logging spam comments from admin area.
* Fix Settings link from Plugins page.
* Update Freemius library

= 4.2.3 =
* Workaround for some versions of PHP 7.x that would cause `define()`s to be ignored.
* Add config note to settings tabs.
* Fix documentation links.

= 4.2.2 =
* Fix 5.3 compatibility.

= 4.2.1 =
* Completed support for [`WP_FAIL2BAN_COMMENT_EXTRA_LOG`](https://docs.wp-fail2ban.com/en/4.2/defines/WP_FAIL2BAN_COMMENT_EXTRA_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add support for 3rd-party plugins; see [Developers](https://docs.wp-fail2ban.com/en/4.2/developers.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
  * Add-on for [Contact Form 7](https://wordpress.org/plugins/wp-fail2ban-addon-contact-form-7/) (experimental).
  * Add-on for [Gravity Forms](https://wordpress.org/plugins/wp-fail2ban-addon-gravity-forms/) (experimental).
* Change logging for known-user with incorrect password; previously logged as unknown user and matched by `hard` filters (due to limitations in older versions of WordPress), now logged as known user and matched by `soft`.
* Bug-fix for email-as-username - now logged correctly and matched by `soft`, not `hard`, filters.
* Bug-fix for regression in code to prevent Free/Premium conflict.

= 4.2.0 =
* Not released.

= 4.1.0 =
* Add separate logging for REST authentication.
* Fix conflict with earlier versions preinstalled in `mu‑plugins`. See [Is *WPf2b* Already Installed?](https://docs.wp-fail2ban.com/en/4.1/installation.html#is-wp-fail2ban-already-installed?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

= 4.0.5 =
* Add [`WP_FAIL2BAN_COMMENT_EXTRA_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_COMMENT_EXTRA_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [`WP_FAIL2BAN_PINGBACK_ERROR_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PINGBACK_ERROR_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) (future functionality).
* Change `WP_FAIL2BAN_LOG_SPAM` to use `LOG_NOTICE`.
* Change `WP_FAIL2BAN_SPAM_LOG` to `LOG_AUTH`.
* Change `WP_FAIL2BAN_LOG_COMMENTS_EXTRA` events to use `LOG_NOTICE` by default.
* Fix conflict with 3.x in `mu-plugins`.

= 4.0.2 =
* Fix PHP 5.3 compatibility.
* Bug-fix for `WP_FAIL2BAN_LOG_COMMENTS_EXTRA`.
* Bug-fix for `WP_FAIL2BAN_REMOTE_ADDR` summary.

= 4.0.1 =
* Add extra features via Freemius. **This is entirely optional.** *WPf2b* works as before, including new features listed here.
* Add settings summary page (Settings -> WP fail2ban).
* Add [`WP_FAIL2BAN_PASSWORD_REQUEST_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PASSWORD_REQUEST_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [`WP_FAIL2BAN_SPAM_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_SPAM_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [`WP_FAIL2BAN_LOG_COMMENTS_EXTRA`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_LOG_COMMENTS_EXTRA.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) - enable logging for attempted comments on posts which are:
  * not found,
  * closed for commenting,
  * in the trash,
  * drafts,
  * password protected
* Block user enumeration via REST API.

= 4.0.0 =
* Not released.

= 3.6.0 =
* The [filter files](https://docs.wp-fail2ban.com/en/4.1/filters.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) are now generated from PHPDoc in the code. There were too many times when the filters were out of sync with the code (programmer error) - this should resolve that by bringing the patterns closer to the code that emits them.
* Added [PHPUnit tests](https://docs.wp-fail2ban.com/en/4.1/tests.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1). Almost 100% code coverage, with the exception of [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) which is quite hard to test properly.
* Bug-fix for [`wordpress-soft.conf`](https://docs.wp-fail2ban.com/en/4.1/filters.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1#wordpress-soft-conf).
* Add [`WP_FAIL2BAN_XMLRPC_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_XMLRPC_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [`WP_FAIL2BAN_REMOTE_ADDR`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_REMOTE_ADDR.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) now supports an array of IPs with PHP 7.
* Moved all documentation to [https://docs.wp-fail2ban.com/](https://docs.wp-fail2ban.com/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

= 3.5.3 =
* Bug-fix for [`wordpress-hard.conf`](https://docs.wp-fail2ban.com/en/4.1/filters.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1#wordpress-hard-conf).

= 3.5.1 =
* Bug-fix for [`WP_FAIL2BAN_BLOCK_USER_ENUMERATION`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCK_USER_ENUMERATION.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

= 3.5.0 =
* Add [`WP_FAIL2BAN_OPENLOG_OPTIONS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_OPENLOG_OPTIONS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [`WP_FAIL2BAN_LOG_COMMENTS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_LOG_COMMENTS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) and [`WP_FAIL2BAN_COMMENT_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_COMMENT_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [`WP_FAIL2BAN_LOG_PASSWORD_REQUEST`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_LOG_PASSWORD_REQUEST.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [`WP_FAIL2BAN_LOG_SPAM`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_LOG_SPAM.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [`WP_FAIL2BAN_TRUNCATE_HOST`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_TRUNCATE_HOST.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* [`WP_FAIL2BAN_BLOCKED_USERS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCKED_USERS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) now supports an array of users with PHP 7.

= 3.0.3 =
* Fix regex in [`wordpress-hard.conf`](https://docs.wp-fail2ban.com/en/4.1/filters.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1#wordpress-hard-conf).

= 3.0.2 =
* Prevent double logging in WP 4.5.x for XML‑RPC authentication failure

= 3.0.1 =
* Fix regex in [`wordpress-hard.conf`](https://docs.wp-fail2ban.com/en/4.1/filters.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1#wordpress-hard-conf).

= 3.0.0 =
* Add [`WP_FAIL2BAN_SYSLOG_SHORT_TAG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_SYSLOG_SHORT_TAG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add [`WP_FAIL2BAN_HTTP_HOST`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_HTTP_HOST.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Log XML‑RPC authentication failure.
* Add better support for MU deployment.

= 2.3.2 =
* Bug-fix [`WP_FAIL2BAN_BLOCKED_USERS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCKED_USERS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

= 2.3.0 =
* Bug-fix in *experimental* [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) code (thanks to KyleCartmell).

= 2.2.1 =
* Fix stupid mistake with [`WP_FAIL2BAN_BLOCKED_USERS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCKED_USERS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

= 2.2.0 =
* Custom authentication log is now called [`WP_FAIL2BAN_AUTH_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_AUTH_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add logging for pingbacks; see [`WP_FAIL2BAN_LOG_PINGBACKS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_LOG_PINGBACKS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Custom pingback log is called [`WP_FAIL2BAN_PINGBACK_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PINGBACK_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

= 2.1.1 =
* Minor bug-fix.

= 2.1.0 =
* Add support for blocking user enumeration; see [`WP_FAIL2BAN_BLOCK_USER_ENUMERATION`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCK_USER_ENUMERATION.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add support for CIDR notation in [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

= 2.0.1 =
* Bug-fix in *experimental* [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) code.

= 2.0.0 =
* Add *experimental* support for X-Forwarded-For header; see [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).
* Add *experimental* support for regex-based login blocking; see [`WP_FAIL2BAN_BLOCKED_USERS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCKED_USERS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

= 1.2.1 =
* Update FAQ.

= 1.2 =
* Fix harmless warning.

= 1.1 =
* Minor cosmetic updates.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 5.2.0 =
This is a minor release. You do not need to update your filters from 5.1.0.


= 5.1.1 =
This is a minor release. You do not need to update your filters from 5.1.0.

= 5.1.0.5 =
This is a feature release. To take advantage of the new features you will need up update your `fail2ban` filters; existing filters will continue to work as before.

= 5.0.1 =
This is a minor release. You do not need to update your filters from 5.0.0.

= 5.0.0 =
PLEASE READ THE NOTES BEFORE UPGRADING.
This is a major feature release. You must update your filters.

= 4.4.0.9 =
This is a minor release. You do not need to update your filters from 4.4.0.

= 4.4.0.8 =
This is a bug-fix release. You do not need to update your filters from 4.4.0.

= 4.4.0.7 =
This is a Premium-only bug-fix release. You do not need to update your filters from 4.4.0.

= 4.4.0.6 =
This is a bug-fix release. You do not need to update your filters from 4.4.0.

= 4.4.0.5 =
This is a bug-fix release. You do not need to update your filters from 4.4.0.

= 4.4.0.4 =
This is a bug-fix release. You do not need to update your filters from 4.4.0.

= 4.4.0.3 =
This is a bug-fix release. You do not need to update your filters from 4.4.0.

= 4.4.0.2 =
This is a feature release. To take advantage of the new features, including the Blocklist Add-on, you will need up update your `fail2ban` filters; existing filters will continue to work as before.
**Blocklist Add-on users**: Please disable the Blocklist plugin before upgrading *WP fail2ban*, then upgrade the Blocklist plugin and reactivate it.

= 4.3.2.2 =
This is a bug-fix release. You do not need to update your filters from 4.3.2.0.

= 4.3.2.1 =
This is a bug-fix release. You do not need to update your filters from 4.3.2.0.

= 4.3.2.0 =
This is a feature release. To take advantage of the new features you will need up update your `fail2ban` filters; existing filters will continue to work as before. Premium users: Please backup your database before upgrading.

= 4.3.0.7 =
This is a bug-fix release. You do not need to update your filters from 4.3.0. Premium users: Please update your MaxMind database.

= 4.3.0.6 =
This is a bug-fix release. You do not need to update your filters from 4.3.0.

= 4.3.0.5 =
This is a bug-fix release. You do not need to update your filters from 4.3.0.

= 4.3.0.4 =
This is a feature release. To take advantage of the new features you will need up update your `fail2ban` filters; existing filters will continue to work as before. Premium users: Please backup your database before upgrading.

= 4.2.8 =
This is a bug-fix release. You do not need to update your filters from 4.1.0.

= 4.2.7.1 =
This is a bug-fix release. You do not need to update your filters from 4.1.0.

= 4.2.7 =
This is a bug-fix release. You do not need to update your filters from 4.1.0.

= 4.2.6 =
This is a minor release. You do not need to update your filters from 4.1.0.

= 4.2.5.1 =
This is a premium-only patch release. If you are on PHP 7.0 or later you do not need to upgrade.

= 4.2.5 =
This is a minor release. You do not need to update your filters from 4.1.0.

= 4.2.4 =
This is a minor release. You do not need to update your filters from 4.1.0.

= 4.2.3 =
This is a bug-fix release. You do not need to update your filters from 4.1.0.

= 4.2.2 =
You do not need to update your filters from 4.1.0.

= 4.2.1 =
You do not need to update your filters from 4.1.0.

= 4.1.0 =
To take advantage of the new features you will need up update your `fail2ban` filters; existing filters will continue to work as before.

= 4.0.5 =
This is a security fix (Freemius SDK): all 4.x users are strongly advised to upgrade immediately. You do not need to update your filters from 4.0.1.

= 4.0.4 =
This is a bug-fix. You do not need to update your filters from 4.0.1.

= 4.0.3 =
This is a bug-fix. You do not need to update your filters from 4.0.1.

= 4.0.2 =
This is a bug-fix. You do not need to update your filters from 4.0.1.

= 4.0.1 =
To take advantage of the new features you will need up update your `fail2ban` filters; existing filters will continue to work as before.

= 3.6.0 =
You will need up update your `fail2ban` filters.

= 3.5.3 =
You will need up update your `fail2ban` filters.

= 3.5.1 =
Bug-fix: disable [`WP_FAIL2BAN_BLOCK_USER_ENUMERATION`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCK_USER_ENUMERATION.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1) in admin area....

= 3.5.0 =
You will need up update your `fail2ban` filters.

= 3.0.3 =
You will need up update your `fail2ban` filters.

= 3.0.0 =
BREAKING CHANGE: The `fail2ban` filters have been split into two files. You will need up update your `fail2ban` configuration.

= 2.3.0 =
Fix for [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1); if you’re not using it you can safely skip this release.

= 2.2.1 =
Bug-fix.

= 2.2.0 =
BREAKING CHANGE:  `WP_FAIL2BAN_LOG` has been renamed to [`WP_FAIL2BAN_AUTH_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_AUTH_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.2.1).

Pingbacks are getting a lot of attention recently, so *WPf2b* can now log them.
The `wordpress.conf` filter has been updated; you will need to update your `fail2ban` configuration.

= 2.1.0 =
The `wordpress.conf` filter has been updated; you will need to update your `fail2ban` configuration.

= 2.0.1 =
Bug-fix in experimental code; still an experimental release.

= 2.0.0 =
This is an experimental release. If your current version is working and you’re not interested in the new features, skip this version - wait for 2.1.0. For those that do want to test this release, note that `wordpress.conf` has changed - you’ll need to copy it to `fail2ban/filters.d` again.

