=== WP fail2ban - Advanced Security ===
Contributors: invisnet
Author URI: https://invis.net/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0
Plugin URI: https://wp-fail2ban.com/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0
Tags: fail2ban, login, security, syslog, brute force, protection
Requires at least: 4.2
Tested up to: 7.1
Stable tag: 5.5.0
Requires PHP: 7.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

*WP fail2ban* uses `fail2ban` to protect your WordPress site.

== Description ==

= Got a firewall? Use it. =

WP fail2ban gives `fail2ban` the information it needs to block attackers at the firewall. `fail2ban` is a server-side service that bans abusive IP addresses; WP fail2ban tells it about brute-force attacks, user enumeration, comment abuse, spam, and more.

Once an address is banned, the firewall stops its requests before they reach your site.

= Free protection from the usual suspects =

The Free edition covers the attacks WordPress sites see every day:

* **Brute-force attacks** — failed logins and attempts using unknown or blocked usernames give `fail2ban` the information it needs to act.
* **User enumeration** — blocks common ways bots discover valid usernames before trying to log in.
* **Comment abuse and spam** — catches suspicious comment attempts and can act on comments marked as spam.
* **Pingbacks and trackbacks** — turns abusive or failed requests into useful signals for `fail2ban`.
* **Blocklist** — the free Blocklist can stop known hostile IP addresses *before* they attack your site.

**[Need more protection? See what Premium adds →](https://wp-fail2ban.com/premium/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0)**

= How WP fail2ban works =

WP fail2ban records security events in a form `fail2ban` can recognise. `fail2ban` watches those events and applies the rules you configure on the server. When an address crosses a threshold, it can be banned by the host firewall, so later requests are stopped before they reach WordPress.

WP fail2ban ships with filters that work well for typical sites, so most installations can start with them as they are.

The events themselves are deliberately written for humans as well as `fail2ban`: they tell you who is doing what, to what, and why it matters. That makes the logs useful to read directly, and makes it much easier to adapt the supplied filters or write your own if your setup needs something unusual.

WP fail2ban also handles exiting keep-alive connections. When abusive activity should result in a ban, it closes the connection so the client cannot simply keep sending requests over a connection that was opened before the firewall rule was added.

The Blocklist works a little differently: it can give the firewall hostile addresses learned from other participating sites, so your site does not have to see the attack first.

[Installation and server setup →](https://docs.wp-fail2ban.com/en/5.5/installation.html#)

= Premium protection from the unusual suspects =

Premium goes beyond `fail2ban`.

* **Block bots before they attack** — the full Blocklist uses attacks seen across participating sites to give your firewall hostile IP addresses your site has not encountered yet. The free Blocklist helps build that network; Premium gets the full current intelligence available for your site.

* **Block the injection, not the signature** — WP fail2ban's Web Application Firewall (WAF) checks the SQL WordPress is actually about to run. There are no attack signatures: whether it is a zero-day or a well-known exploit, the WAF blocks the injection itself.

* **Investigate after they attack** — Premium keeps a structured history of security events, so you can follow activity by IP address, country, username, event type and more instead of relying only on whatever remains in the system log.

* **Maintain your protection** — some protection depends on external data that changes over time. Premium keeps geolocation data and trusted Cloudflare and Jetpack network ranges up to date automatically.

Premium also adds controls including geo‑blocking, XML‑RPC restrictions, honeypots, and safeguards around sensitive WordPress operations.

[See Premium plans and pricing →](https://wp-fail2ban.com/pricing/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0)

Your firewall can stop an IP address, but it cannot see what that address is doing inside WordPress. WP fail2ban fills that gap: the Free edition gives `fail2ban` the information it needs to act on everyday abuse, while Premium adds protection and intelligence that go beyond `fail2ban`.

If you have questions about hosting, the Blocklist, Free and Premium, version requirements, privacy, or how WP fail2ban stores data, the FAQ below covers the practical details.

== FAQ ==

= Is WP fail2ban “fail2ban for WordPress”? =

**No.** `fail2ban` is the service that watches logs and bans abusive IP addresses at the firewall. WP fail2ban is the bridge between WordPress and `fail2ban`: it tells `fail2ban` what is happening inside WordPress in a form it can act on.

The name dates from 2011, when “WP + something” was commonly understood to mean an integration between WordPress and that thing.

= Why does a dismissed Premium notice sometimes come back? =

**It shouldn’t.**

The Premium notices are provided by Freemius and use WordPress’s normal admin request handling to remember when you dismiss them. If another plugin mishandles that request before WP fail2ban or Freemius sees it, the dismissal is never saved and the notice can appear again.

Because plugins are usually loaded alphabetically, this can be caused by almost any plugin loaded before WP fail2ban. There is very little WP fail2ban can do to work around another plugin breaking the request.

= Will WP fail2ban work with my hosting? =

If you can configure `fail2ban` and the firewall, **yes.**

That normally includes VPSs, dedicated servers, and other hosting where you have control of the operating system. WP fail2ban supplies the WordPress side of the integration; `fail2ban` and the firewall run on the server.

On managed hosting, it depends on what your provider allows. If you cannot install or configure `fail2ban` yourself, ask your host whether they support it or can add the WP fail2ban filters for you.

= What is the Blocklist? =

The WP fail2ban Blocklist uses attacks seen by participating WP fail2ban sites to identify hostile IP addresses.

That means your firewall can block an address before it attacks your site, rather than waiting for your site to see enough activity for `fail2ban` to act.

The Blocklist is built from WP fail2ban’s own data, not third-party threat feeds. The Free edition receives a reduced list; Premium receives the full current intelligence available for your site.

[Learn more about the WP fail2ban Blocklist add-on →](https://wp-fail2ban.com/features/miscellaneous/blocklist/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0)

= Why is the WordPress.org edition 5.x while Premium is 6.x? =

The WordPress.org edition remains on 5.x so it can continue to support PHP 7.4. There are still a lot of WP fail2ban sites running PHP 7.4, even though PHP itself stopped supporting it nearly four years ago.

Premium is based on 6.x and requires PHP 8 or later. It has moved on significantly from 5.x as WP fail2ban continues to develop.

WordPress.org does not provide parallel release channels for different major versions, so 5.x and 6.x cannot both be offered for different PHP versions. Keeping the WordPress.org edition on 5.x is therefore a compromise: it lets development continue on modern PHP without abandoning existing WP fail2ban users who have not yet been able to move away from PHP 7.4.

= What’s the difference between Free and Premium? =

The Free edition covers the everyday WordPress abuse most sites need `fail2ban` to act on.

Premium goes further with the full Blocklist, the Web Application Firewall, persistent event history, geo‑blocking, maintained security data, and additional controls.

[Compare all Free and Premium features →](https://wp-fail2ban.com/features/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0)

= Is WP fail2ban a firewall? =

It depends which kind of firewall you mean.

WP fail2ban is not a network firewall. It tells `fail2ban` when WordPress sees abusive behaviour, and `fail2ban` can then add bans to the server’s stateful network firewall. That `firewall` stops traffic before it reaches WordPress.

Premium also includes a Web Application Firewall (WAF). A WAF works at a different layer: it examines what a request is trying to make WordPress do and can block malicious application-level behaviour, including SQL injection.

The two complement each other. The network firewall blocks hostile sources; the WAF protects WordPress from malicious requests that reach WordPress.

= How is WP fail2ban different from ModSecurity and other WAFs? =

They work at different layers, and they can work together.

ModSecurity and similar web-server WAFs inspect HTTP requests and responses and apply rules before or around the application. That makes them good at rejecting traffic based on what the request itself *looks like*.

WP fail2ban runs inside WordPress, so it can see what the request actually *means* to WordPress: whether a login failed, whether a username exists, whether a comment was rejected as spam, what WordPress operation was attempted, and so on. It turns that application-level knowledge into events that `fail2ban` can act on at the network firewall.

Premium’s WAF uses the same advantage. For SQL injection, for example, it can inspect the SQL WordPress is actually about to execute rather than trying to infer solely from the incoming request whether it contains an attack.

They are complementary, not alternatives: a web-server WAF can reject malicious-looking requests before WordPress sees them; WP fail2ban can act after WordPress has interpreted them and knows what they actually mean.

= Can I use WP fail2ban behind Cloudflare or another reverse proxy? =

Yes, but WP fail2ban needs to know which proxies it can trust.

A reverse proxy means the connection to WordPress comes from the proxy rather than directly from the visitor. WP fail2ban uses `X-Forwarded-For` to recover the real client IP, but only when the connecting address belongs to a proxy you have explicitly trusted. That prevents an attacker simply supplying their own forwarding header.

WP fail2ban deliberately uses only `X-Forwarded-For` for client IP resolution. Keeping a single trusted path through that part of the code reduces the chances of subtle bugs or inconsistent behaviour, and `X-Forwarded-For` is the standard header that reverse proxies are expected to support.

Free lets you configure trusted proxy networks yourself. Premium can keep Cloudflare's published IP ranges up to date automatically, so Cloudflare's edge nodes remain recognised as trusted proxies as its network changes.

The `CF-Connecting-IP` header is not used to determine the client address. WP fail2ban only uses it as a signal that a site appears to be behind Cloudflare, so it can recommend the appropriate QuickStart configuration.

[Read more about how WP fail2ban handles client IP addresses →](https://wp-fail2ban.com/features/miscellaneous/client-ip/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0)

= Does WP fail2ban send my security data anywhere? =

**Not as part of normal operation.**

WP fail2ban’s normal job is local: WordPress generates the event, WP fail2ban records it, and `fail2ban` acts on it on your server.

The Free edition includes Freemius, but sharing usage information with Freemius is optional: you can skip the opt-in and continue using and updating WP fail2ban. Premium uses Freemius for purchasing, licensing, and updates, so that connection is part of using the paid product. Freemius publishes what information it collects and why.

[Freemius data practices](https://freemius.com/privacy/data-practices/)

The Blocklist is a separate add-on and necessarily has its own data exchange; its README explains that separately.

== Installation ==

WP fail2ban has two parts to set up: WordPress needs to generate the events, and `fail2ban` needs to know how to act on them.

= WordPress =

1. Install WP fail2ban from the Plugin Directory and activate it.
2. The Free edition is configured in `wp‑config.php`. The defaults are a good starting point; change them only where your site needs different behaviour.
3. Check the WP fail2ban settings pages to see the effective configuration.

[Configure WP fail2ban →](https://docs.wp-fail2ban.com/en/5.5/configuration.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0)

= fail2ban =

WP fail2ban includes the `wordpress-hard.conf` and `wordpress-soft.conf` filters. Copy them into your `fail2ban` filter directory and configure jails for both.

The hard filter is for events that can normally be banned immediately. The soft filter is for events such as failed logins where several attempts may be reasonable.

Exactly where `fail2ban` reads its logs depends on your operating system and whether it uses traditional syslog files or the systemd journal, so use the installation documentation for the current examples.

[Configure fail2ban →](https://docs.wp-fail2ban.com/en/5.5/configuration/fail2ban.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0)

Once both sides are configured, test the installation before relying on it.

== Changelog ==

= 5.5.0-LTS =
* Add support for closing keep-alive connections on blocked and failed requests (was Premium only).
* Fix Site Health reporting compatible filters as old. [Back-port from 6.3.0]
* Fix blocked-user list matching to be case-insensitive for array lists. [Back-port from 6.3.0]
* Fix password reset logged as success too early. [Back-port from 6.3.0]
* Fix pingback errors not using `WP_FAIL2BAN_PINGBACK_LOG`. [Back-port from 6.3.0]
* Update Freemius library.

= 5.4.2-LTS =
* Update Freemius library.

= 5.4.1-LTS =
* Fix compatibility for WordPress 6.8.
* Fix incorrect dependency requiring PHP>=8.1. [Premium only]
* Add license for Packagist.

= 5.4.0-LTS =
* Add support for Composer. [Canonical only]
* Add support for AspirePress Update. [Canonical only]
* Add support for GitHub Updater. [Canonical only]
* Improve i18n.
* Fix harmless warning in Site Health filters check (h/t Rodolphe).
* Update Freemius library.

= 5.3.4 =
* Improve Site Health filter messages (h/t @yardstudio).
* Update Freemius library.

= 5.3.3 =
* Fix activation bug when `$wp_scripts` is uninitialised. [Premium only]
* Fix world map dashboard widget. [Premium only]
* Update Freemius library.

= 5.3.2 =
* Drop Site Health checks for free add‑ons.

= 5.3.1 =
* Fix regression in plugin message registration.

= 5.3.0 =
* Add `WP_FAIL2BAN_SYSLOG_TAG_HOST` to simplify `fail2ban` configuration with `journald`.
* Fix database upgrade when jQuery not loaded. [Premium only]
* Update Freemius library.

= 5.2.2 =
* Fix regression in Site Health when `exec` is disabled (h/t @ahardy42).
* PHP 8.3: Fix harmless warning on About tab.
* WAF: Add support for themes that update image size options (h/t Jerker Wredenmark). [Premium only]
* Update Freemius library.

= 5.2.1 =
* Fix bug in WAF when not logged in (h/t Rudi Diedrich). [Premium only]
* Site Health: Add support for `open_basedir` (h/t Jaroslav Huba).
* Update Freemius library.

= 5.2.0 =
* WAF: Add new filter file for WAF events. [Premium only]
* WAF: Add feature: prevent unauthorised user deletion. [Premium only].
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
* Add [`WP_FAIL2BAN_USE_AUTHPRIV`](https://docs.wp-fail2ban.com/en/4.4/defines/WP_FAIL2BAN_USE_AUTHPRIV.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) - a single place to switch to `LOG_AUTHPRIV` for systems without `LOG_AUTH`.
* Add [`WP_FAIL2BAN_FREE_ONLY`](https://docs.wp-fail2ban.com/en/4.4/defines/WP_FAIL2BAN_FREE_ONLY.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [`WP_FAIL2BAN_PLUGIN_LOG_OTHER`](https://docs.wp-fail2ban.com/en/4.4/defines/WP_FAIL2BAN_PLUGIN_LOG_OTHER.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) and [`WP_FAIL2BAN_PLUGIN_OTHER_LOG`](https://docs.wp-fail2ban.com/en/4.4/defines/WP_FAIL2BAN_PLUGIN_OTHER_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
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
* Add support for [WP fail2ban Blocklist](https://addons.wp-fail2ban.com/blocklist/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
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
* Add full [multisite support](https://wp-fail2ban.com/features/multisite-networks/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [username login blocking](https://wp-fail2ban.com/features/block-username-logins/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) (force login with email).
* Add [separate logging](https://wp-fail2ban.com/features/empty-username-logging/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) for login attempts with an empty username.
* Improve [user enumeration blocking](https://wp-fail2ban.com/features/block-user-enumeration/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) compatibility with the WordPress block editor (Gutenberg).
* Bump the minimum PHP version to 5.6.

= 4.2.8 =
* Add link to new [support forum](https://forums.invis.net/c/wp-fail2ban/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
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
* Add support for [Remote Tools](https://wp-fail2ban.com/add-ons/remote-tools/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) add-on.
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
* Completed support for [`WP_FAIL2BAN_COMMENT_EXTRA_LOG`](https://docs.wp-fail2ban.com/en/4.2/defines/WP_FAIL2BAN_COMMENT_EXTRA_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add support for 3rd-party plugins; see [Developers](https://docs.wp-fail2ban.com/en/4.2/developers.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
  * Add-on for [Contact Form 7](https://wordpress.org/plugins/wp-fail2ban-addon-contact-form-7/) (experimental).
  * Add-on for [Gravity Forms](https://wordpress.org/plugins/wp-fail2ban-addon-gravity-forms/) (experimental).
* Change logging for known-user with incorrect password; previously logged as unknown user and matched by `hard` filters (due to limitations in older versions of WordPress), now logged as known user and matched by `soft`.
* Bug-fix for email-as-username - now logged correctly and matched by `soft`, not `hard`, filters.
* Bug-fix for regression in code to prevent Free/Premium conflict.

= 4.2.0 =
* Not released.

= 4.1.0 =
* Add separate logging for REST authentication.
* Fix conflict with earlier versions preinstalled in `mu‑plugins`. See [Is *WPf2b* Already Installed?](https://docs.wp-fail2ban.com/en/4.1/installation.html#is-wp-fail2ban-already-installed?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).

= 4.0.5 =
* Add [`WP_FAIL2BAN_COMMENT_EXTRA_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_COMMENT_EXTRA_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [`WP_FAIL2BAN_PINGBACK_ERROR_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PINGBACK_ERROR_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) (future functionality).
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
* Add [`WP_FAIL2BAN_PASSWORD_REQUEST_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PASSWORD_REQUEST_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [`WP_FAIL2BAN_SPAM_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_SPAM_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [`WP_FAIL2BAN_LOG_COMMENTS_EXTRA`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_LOG_COMMENTS_EXTRA.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) - enable logging for attempted comments on posts which are:
  * not found,
  * closed for commenting,
  * in the trash,
  * drafts,
  * password protected
* Block user enumeration via REST API.

= 4.0.0 =
* Not released.

= 3.6.0 =
* The [filter files](https://docs.wp-fail2ban.com/en/4.1/filters.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) are now generated from PHPDoc in the code. There were too many times when the filters were out of sync with the code (programmer error) - this should resolve that by bringing the patterns closer to the code that emits them.
* Added [PHPUnit tests](https://docs.wp-fail2ban.com/en/4.1/tests.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0). Almost 100% code coverage, with the exception of [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) which is quite hard to test properly.
* Bug-fix for [`wordpress-soft.conf`](https://docs.wp-fail2ban.com/en/4.1/filters.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0#wordpress-soft-conf).
* Add [`WP_FAIL2BAN_XMLRPC_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_XMLRPC_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [`WP_FAIL2BAN_REMOTE_ADDR`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_REMOTE_ADDR.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) now supports an array of IPs with PHP 7.
* Moved all documentation to [https://docs.wp-fail2ban.com/](https://docs.wp-fail2ban.com/?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).

= 3.5.3 =
* Bug-fix for [`wordpress-hard.conf`](https://docs.wp-fail2ban.com/en/4.1/filters.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0#wordpress-hard-conf).

= 3.5.1 =
* Bug-fix for [`WP_FAIL2BAN_BLOCK_USER_ENUMERATION`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCK_USER_ENUMERATION.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).

= 3.5.0 =
* Add [`WP_FAIL2BAN_OPENLOG_OPTIONS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_OPENLOG_OPTIONS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [`WP_FAIL2BAN_LOG_COMMENTS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_LOG_COMMENTS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) and [`WP_FAIL2BAN_COMMENT_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_COMMENT_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [`WP_FAIL2BAN_LOG_PASSWORD_REQUEST`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_LOG_PASSWORD_REQUEST.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [`WP_FAIL2BAN_LOG_SPAM`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_LOG_SPAM.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [`WP_FAIL2BAN_TRUNCATE_HOST`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_TRUNCATE_HOST.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* [`WP_FAIL2BAN_BLOCKED_USERS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCKED_USERS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) now supports an array of users with PHP 7.

= 3.0.3 =
* Fix regex in [`wordpress-hard.conf`](https://docs.wp-fail2ban.com/en/4.1/filters.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0#wordpress-hard-conf).

= 3.0.2 =
* Prevent double logging in WP 4.5.x for XML‑RPC authentication failure

= 3.0.1 =
* Fix regex in [`wordpress-hard.conf`](https://docs.wp-fail2ban.com/en/4.1/filters.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0#wordpress-hard-conf).

= 3.0.0 =
* Add [`WP_FAIL2BAN_SYSLOG_SHORT_TAG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_SYSLOG_SHORT_TAG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add [`WP_FAIL2BAN_HTTP_HOST`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_HTTP_HOST.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Log XML‑RPC authentication failure.
* Add better support for MU deployment.

= 2.3.2 =
* Bug-fix [`WP_FAIL2BAN_BLOCKED_USERS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCKED_USERS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).

= 2.3.0 =
* Bug-fix in *experimental* [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) code (thanks to KyleCartmell).

= 2.2.1 =
* Fix stupid mistake with [`WP_FAIL2BAN_BLOCKED_USERS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCKED_USERS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).

= 2.2.0 =
* Custom authentication log is now called [`WP_FAIL2BAN_AUTH_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_AUTH_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add logging for pingbacks; see [`WP_FAIL2BAN_LOG_PINGBACKS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_LOG_PINGBACKS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Custom pingback log is called [`WP_FAIL2BAN_PINGBACK_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PINGBACK_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).

= 2.1.1 =
* Minor bug-fix.

= 2.1.0 =
* Add support for blocking user enumeration; see [`WP_FAIL2BAN_BLOCK_USER_ENUMERATION`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCK_USER_ENUMERATION.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add support for CIDR notation in [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).

= 2.0.1 =
* Bug-fix in *experimental* [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) code.

= 2.0.0 =
* Add *experimental* support for X-Forwarded-For header; see [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).
* Add *experimental* support for regex-based login blocking; see [`WP_FAIL2BAN_BLOCKED_USERS`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCKED_USERS.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).

= 1.2.1 =
* Update FAQ.

= 1.2 =
* Fix harmless warning.

= 1.1 =
* Minor cosmetic updates.

= 1.0 =
* Initial release.

== Upgrade Notice ==

= 5.5.0 =
This is a feature release. You do not need to update your filters from 5.1.0.

= 5.4.2 =
This is a maintenance release.  You do not need to update your filters from 5.1.0.

= 5.4.1 =
This is a bug-fix release.  You do not need to update your filters from 5.1.0.

= 5.4.0.1 =
This is an important bug-fix release. You do not need to update your filters from 5.1.0.

= 5.4.0 =
This is a feature release. You do not need to update your filters from 5.1.0.

= 5.3.4 =
This is a bug-fix release.  You do not need to update your filters from 5.1.0.

= 5.3.3 =
This is a bug-fix release.  You do not need to update your filters from 5.1.0.

= 5.3.2 =
This is a minor release.  You do not need to update your filters from 5.1.0.

= 5.3.1 =
This is a bug-fix release.  You do not need to update your filters from 5.1.0.

= 5.3.0 =
This is a feature release. You do not need to update your filters from 5.1.0.

[//]: # fs_free_only_begin
= 5.2.2 =
This is a minor release. You do not need to update your filters from 5.1.0.
[//]: # fs_free_only_end
[//]: # fs_premium_only_begin
= 5.2.2 =
This is a feature release. If you are using the WAF you will need to check the "WordPress core" setting in the WAF Options section.
[//]: # fs_premium_only_end

= 5.2.1 =
This is a minor release. You do not need to update your filters from 5.1.0.

[//]: # fs_free_only_begin
= 5.2.0 =
This is a minor release. You do not need to update your filters from 5.1.0.
[//]: # fs_free_only_end
[//]: # fs_premium_only_begin
= 5.2.0 =
This is a feature release. If you are using the WAF you will need up update your `fail2ban` filters and create a new jail; otherwise, things will continue to work as before.
[//]: # fs_premium_only_end

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
Bug-fix: disable [`WP_FAIL2BAN_BLOCK_USER_ENUMERATION`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_BLOCK_USER_ENUMERATION.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0) in admin area....

= 3.5.0 =
You will need up update your `fail2ban` filters.

= 3.0.3 =
You will need up update your `fail2ban` filters.

= 3.0.0 =
BREAKING CHANGE: The `fail2ban` filters have been split into two files. You will need up update your `fail2ban` configuration.

= 2.3.0 =
Fix for [`WP_FAIL2BAN_PROXIES`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_PROXIES.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0); if you’re not using it you can safely skip this release.

= 2.2.1 =
Bug-fix.

= 2.2.0 =
BREAKING CHANGE:  `WP_FAIL2BAN_LOG` has been renamed to [`WP_FAIL2BAN_AUTH_LOG`](https://docs.wp-fail2ban.com/en/4.1/defines/WP_FAIL2BAN_AUTH_LOG.html?utm_source=wordpress.org&utm_medium=readme&utm_campaign=wp-fail2ban-premium-5.5.0).

Pingbacks are getting a lot of attention recently, so *WPf2b* can now log them.
The `wordpress.conf` filter has been updated; you will need to update your `fail2ban` configuration.

= 2.1.0 =
The `wordpress.conf` filter has been updated; you will need to update your `fail2ban` configuration.

= 2.0.1 =
Bug-fix in experimental code; still an experimental release.

= 2.0.0 =
This is an experimental release. If your current version is working and you’re not interested in the new features, skip this version - wait for 2.1.0. For those that do want to test this release, note that `wordpress.conf` has changed - you’ll need to copy it to `fail2ban/filters.d` again.

