<?php
/**
 * WP fail2ban core
 *
 * @package wp-fail2ban
 * @since   4.3.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban\core;

use function org\lecklider\charles\wordpress\wp_fail2ban\openlog;
use function org\lecklider\charles\wordpress\wp_fail2ban\syslog;
use function org\lecklider\charles\wordpress\wp_fail2ban\closelog;

defined('ABSPATH') or exit;

/**
 * Catch empty usernames
 *
 * @see \wp_authenticate()
 *
 * @since 4.3.0
 *
 * @param mixed|null    $user
 * @param string        $username
 * @param string        $password
 *
 * @return mixed|null
 *
 * @wp-f2b-soft Empty username
 */
function authenticate($user, $username, $password)
{
    if (empty($username) && isset($_POST['log'])) {
        if (openlog()) {
            syslog(LOG_NOTICE, 'Empty username');
            closelog();
        }

        do_action(__FUNCTION__, $user, $username, $password);
    }

    return $user;
}

/**
 * Hook: wp_login
 *
 * @since 4.3.0     Add action
 * @since 4.1.0     Add REST support
 * @since 3.5.0     Refactored for unit testing
 * @since 1.0.0
 *
 * @param string    $user_login
 * @param mixed     $user
 *
 * @codeCoverageIgnore
 */
function wp_login($user_login, $user)
{
    if (openlog()) {
        syslog(LOG_INFO, "Accepted password for {$user_login}");
        closelog();
    }

    do_action(__FUNCTION__, $user_login, $user);
}

/**
 * Hook: wp_login_failed
 *
 * @since 4.3.0.5   Handle empty username
 * @since 4.3.0     Add action
 * @since 4.2.4     Add message filter
 * @since 4.2.0     Change username check
 * @since 4.1.0     Add REST support
 * @since 3.5.0     Refactored for unit testing
 * @since 1.0.0
 *
 * @param string    $username
 *
 * @wp-f2b-hard Authentication attempt for unknown user .*
 * @wp-f2b-hard REST authentication attempt for unknown user .*
 * @wp-f2b-hard XML-RPC authentication attempt for unknown user .*
 * @wp-f2b-soft Authentication failure for .*
 * @wp-f2b-soft REST authentication failure for .*
 * @wp-f2b-soft XML-RPC authentication failure for .*
 */
function wp_login_failed($username)
{
    $username = trim($username);

    if (empty($username)) {
        $msg    = 'Empty username';
        $filter = '::empty';

    } else {
        global $wp_xmlrpc_server;

        if (defined('REST_REQUEST')) {
            $msg    = 'REST a';
            $filter = '::REST';
        } elseif ($wp_xmlrpc_server) {
            $msg    = 'XML-RPC a';
            $filter = '::XML-RPC';
        } else {
            $msg    = 'A';
            $filter = '';
        }

        $msg .= (wp_cache_get($username, 'useremail') || wp_cache_get(sanitize_user($username), 'userlogins'))
                ? "uthentication failure for {$username}"
                : "uthentication attempt for unknown user {$username}";
    }

    $msg  = apply_filters("wp_fail2ban::wp_login_failed{$filter}", $msg);

    if (openlog()) {
        syslog(LOG_NOTICE, $msg);
        closelog();
    }

    do_action(__FUNCTION__, $username);
}

