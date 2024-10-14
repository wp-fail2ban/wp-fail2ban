<?php declare(strict_types=1);
/**
 * WP fail2ban core
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.3.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban\core;

use          org\lecklider\charles\wordpress\wp_fail2ban\Syslog;

defined('ABSPATH') or exit;

/**
 * Catch empty usernames
 *
 * @see \wp_authenticate()
 *
 * @since  4.4.0    Add type hints
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.3.0
 *
 * @param  mixed|null   $user
 * @param  string       $username
 * @param  string       $password
 *
 * @return mixed|null
 *
 * @wp-f2b-soft Empty username
 */
function authenticate($user, string $username, string $password) // : ?mixed
{
    if (empty($username) && isset($_POST['log'])) {
        Syslog::single(LOG_NOTICE, 'Empty username');

        do_action(__FUNCTION__, $user, $username, $password);
    }

    return $user;
}

/**
 * Hook: wp_login
 *
 * @since  4.4.0    Add type hints, return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.3.0    Add action
 * @since  4.1.0    Add REST support
 * @since  3.5.0    Refactored for unit testing
 * @since  1.0.0
 *
 * @param  string   $user_login
 * @param  mixed    $user
 *
 * @return void
 *
 * @codeCoverageIgnore
 */
function wp_login(string $user_login, $user): void
{
    Syslog::single(LOG_INFO, "Accepted password for {$user_login}");

    do_action(__FUNCTION__, $user_login, $user);
}

/**
 * Hook: wp_login_failed
 *
 * @since  4.4.0    Add type hints, return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.3.0.5  Handle empty username
 * @since  4.3.0    Add action
 * @since  4.2.4    Add message filter
 * @since  4.2.0    Change username check
 * @since  4.1.0    Add REST support
 * @since  3.5.0    Refactored for unit testing
 * @since  1.0.0
 *
 * @param  string   $username
 *
 * @return void
 *
 * @wp-f2b-hard Authentication attempt for unknown user .*
 * @wp-f2b-hard REST authentication attempt for unknown user .*
 * @wp-f2b-hard XML-RPC authentication attempt for unknown user .*
 * @wp-f2b-soft Authentication failure for .*
 * @wp-f2b-soft REST authentication failure for .*
 * @wp-f2b-soft XML-RPC authentication failure for .*
 */
function wp_login_failed(string $username): void
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

    Syslog::single(LOG_NOTICE, $msg);

    do_action(__FUNCTION__, $username);
}

