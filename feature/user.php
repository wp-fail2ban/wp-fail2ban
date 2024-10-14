<?php declare(strict_types=1);
/**
 * Blocked user functionality
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban\feature;

use          org\lecklider\charles\wordpress\wp_fail2ban\Config;
use          org\lecklider\charles\wordpress\wp_fail2ban\Syslog;

use function org\lecklider\charles\wordpress\wp_fail2ban\bail;

defined('ABSPATH') or exit;

/**
 * Catch blocked users
 *
 * @see \wp_authenticate()
 *
 * @since  4.4.0    Add type hints
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.3.0    Add blocking username logins
 * @since  3.5.0    Refactored for unit testing
 * @since  2.0.0
 *
 * @param  mixed|null   $user
 * @param  string       $username
 * @param  string       $password
 *
 * @return mixed|null
 *
 * @wp-f2b-hard Blocked authentication attempt for .*
 * @wp-f2b-soft Blocked username authentication attempt for .*
 */
function block_users($user, string $username, string $password) // : ?mixed
{
    if (!empty($username)) {
        if (Config::get('WP_FAIL2BAN_BLOCK_USERNAME_LOGIN')) {
            if (is_email($username)) {
                // OK!
            } else {
                Syslog::single(LOG_NOTICE, "Blocked username authentication attempt for {$username}");

                do_action(__FUNCTION__.'.block_username_login', $user, $username, $password);

                return bail(); // for testing
            }
        }

        if (!empty(Config::get('WP_FAIL2BAN_BLOCKED_USERS'))) {
            /**
             * @since 3.5.0 Arrays allowed in PHP 7
             */
            $blocked_users = Config::get('WP_FAIL2BAN_BLOCKED_USERS');
            $matched = (is_array($blocked_users))
                ? in_array($username, $blocked_users)
                : preg_match('/'.$blocked_users.'/i', $username);

            if ($matched) {
                Syslog::single(LOG_NOTICE, "Blocked authentication attempt for {$username}");

                do_action(__FUNCTION__.'.blocked_users', $user, $username, $password);

                return bail(); // for testing
            }
        }
    }

    return $user;
}

