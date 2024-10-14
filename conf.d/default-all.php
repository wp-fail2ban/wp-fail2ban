<?php
/**
 *  @package wp-fail2ban\conf.d\default-all
 *  @license GPL3
 *  @version GIT: $Id$
 *  @copyright 2021 Charles Lecklider
 *
 *  Standard WP fail2ban Configuration:
 *    + Default logs
 *    + All enabled
 *
 *  @see https://docs.wp-fail2ban.com/en/4.3/facilities.html    Log Facilities reference
 **/
define('WP_FAIL2BAN_STANDARD_CONFIG_FILE', __FILE__);


/**
 ** Logging
 **
 ** @see https://docs.wp-fail2ban.com/en/4.3/defines/logging.html   Reference
 **/

/// Authentication
    // The default is LOG_AUTH:
//  define('WP_FAIL2BAN_AUTH_LOG', LOG_AUTH);

/// Comments
    define('WP_FAIL2BAN_LOG_COMMENTS', true);
    // The default is LOG_USER:
//  define('WP_FAIL2BAN_COMMENT_LOG', LOG_USER);

    // This include is needed if you want extra comment logging
    if (include __DIR__.'/plugins/wp-fail2ban/lib/constants.php') {
        // The default is LOG_AUTH:
    //  define('WP_FAIL2BAN_COMMENT_EXTRA_LOG', LOG_AUTH);

        define('WP_FAIL2BAN_LOG_COMMENTS_EXTRA',
            WPF2B_EVENT_COMMENT_NOT_FOUND |
            WPF2B_EVENT_COMMENT_CLOSED |
            WPF2B_EVENT_COMMENT_TRASH |
            WPF2B_EVENT_COMMENT_DRAFT |
            WPF2B_EVENT_COMMENT_PASSWORD
        );
    }

/// Spam
    define('WP_FAIL2BAN_LOG_SPAM', true);
    // The default is LOG_AUTH:
//  define('WP_FAIL2BAN_SPAM_LOG', LOG_AUTH);

/// Password requests
    define('WP_FAIL2BAN_LOG_PASSWORD_REQUEST', true);
    // The default is LOG_USER:
//  define('WP_FAIL2BAN_PASSWORD_REQUEST_LOG', LOG_USER);

/// Pingbacks
    define('WP_FAIL2BAN_LOG_PINGBACKS', true);
    // The default is LOG_AUTH:
//  define('WP_FAIL2BAN_PINGBACK_ERROR_LOG', LOG_AUTH);


/**
 ** syslog
 **
 ** @see https://docs.wp-fail2ban.com/en/4.3/defines/syslog.html    Reference
 **/

/// Connection
/** WARNING - do not change unless you understand the implications **/
    // The default is LOG_PID|LOG_NDELAY:
//  define('WP_FAIL2BAN_OPENLOG_OPTIONS', LOG_PID|LOG_NDELAY);

/// Workarounds
/** Only for problematic systems **/
//  define('WP_FAIL2BAN_SYSLOG_SHORT_TAG', true);
//  define('WP_FAIL2BAN_HTTP_HOST', true);
//  define('WP_FAIL2BAN_TRUNCATE_HOST', true);


/**
 ** Block
 **
 ** @see https://docs.wp-fail2ban.com/en/4.3/defines/block.html     Reference
 **/

/// Users
    define('WP_FAIL2BAN_BLOCK_USER_ENUMERATION', true);
    define('WP_FAIL2BAN_BLOCKED_USERS', [
        'admin',
        'root'
    ]);
/** Uncomment to force logins with email addresses ONLY **/
//  define('WP_FAIL2BAN_BLOCK_USERNAME_LOGIN', true);


/**
 ** Remote IPs
 **
 ** @see https://docs.wp-fail2ban.com/en/4.3/defines/remote-ips.html    Reference
 **/

/** Trusted proxy IPs; not needed unless you're using a proxy **/
//  define('WP_FAIL2BAN_PROXIES', ['192.168.0.42', '192.168.42.0/24']);

/** Uncomment if you use CloudFlare: **/
/*  define('WP_FAIL2BAN_PROXIES', [
        '173.245.48.0/20',
        '103.21.244.0/22',
        '103.22.200.0/22',
        '103.31.4.0/22',
        '141.101.64.0/18',
        '108.162.192.0/18',
        '190.93.240.0/20',
        '188.114.96.0/20',
        '197.234.240.0/22',
        '198.41.128.0/17',
        '162.158.0.0/15',
        '104.16.0.0/12',
        '172.64.0.0/13',
        '131.0.72.0/22'
    ]); */


/**
 ** Plugins
 **
 ** @see https://docs.wp-fail2ban.com/en/4.3/defines/plugin.html    Reference
 **/

/// Event Class Facilities
/** Authentication **/
    define('WP_FAIL2BAN_PLUGIN_LOG_AUTH', true);
    // The default is LOG_AUTH:
//  define('WP_FAIL2BAN_PLUGIN_AUTH_LOG', LOG_AUTH);

/** Block **/
    define('WP_FAIL2BAN_PLUGIN_LOG_BLOCK', true);
    // The default is LOG_USER:
//  define('DEFAULT_WP_FAIL2BAN_PLUGIN_BLOCK_LOG', LOG_USER);

/** Uncomment if you use the Blocklist add-on: **/
//  define('DEFAULT_WP_FAIL2BAN_PLUGIN_BLOCK_LOG', LOG_LOCAL3);

/** Comment **/
    define('WP_FAIL2BAN_PLUGIN_LOG_COMMENT', true);
    // The default is LOG_USER:
//  define('WP_FAIL2BAN_PLUGIN_COMMENT_LOG', LOG_USER);

/** Other - NOT USED **/
//  define('WP_FAIL2BAN_PLUGIN_LOG_OTHER', true);
    // The default is LOG_USER:
//  define('WP_FAIL2BAN_PLUGIN_OTHER_LOG', LOG_USER);

/** Password **/
    define('WP_FAIL2BAN_PLUGIN_LOG_PASSWORD', true);
    // The default is LOG_USER:
//  define('WP_FAIL2BAN_PLUGIN_PASSWORD_LOG', LOG_USER);

/** REST **/
    define('WP_FAIL2BAN_PLUGIN_LOG_REST', true);
    // The default is LOG_USER:
//  define('WP_FAIL2BAN_PLUGIN_REST_LOG', LOG_USER);

/** Spam **/
    define('WP_FAIL2BAN_PLUGIN_LOG_SPAM', true);
    // The default is LOG_AUTH:
//  define('WP_FAIL2BAN_PLUGIN_SPAM_LOG', LOG_AUTH);

/** XML-RPC **/
    define('WP_FAIL2BAN_PLUGIN_LOG_XMLRPC', true);
    // The default is LOG_USER:
//  define('WP_FAIL2BAN_PLUGIN_XMLRPC_LOG', LOG_USER);

