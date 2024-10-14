<?php
/**
 * Blocked user functionality
 *
 * @package wp-fail2ban
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban\feature;

use function org\lecklider\charles\wordpress\wp_fail2ban\bail;
use function org\lecklider\charles\wordpress\wp_fail2ban\openlog;
use function org\lecklider\charles\wordpress\wp_fail2ban\syslog;
use function org\lecklider\charles\wordpress\wp_fail2ban\closelog;

defined('ABSPATH') or exit;

/**
 * Catch blocked users
 *
 * @see \wp_authenticate()
 *
 * @since 4.3.0 Add blocking username logins
 * @since 3.5.0 Refactored for unit testing
 * @since 2.0.0
 *
 * @param mixed|null    $user
 * @param string        $username
 * @param string        $password
 *
 * @return mixed|null
 *
 * @wp-f2b-hard Blocked authentication attempt for .*
 * @wp-f2b-hard Blocked username authentication attempt for .*
 */
function block_users($user, $username, $password)
{
    if (!empty($username)) {
        if (defined('WP_FAIL2BAN_BLOCK_USERNAME_LOGIN') && WP_FAIL2BAN_BLOCK_USERNAME_LOGIN) {
            if (is_email($username)) {
                // OK!
            } else {
                if (openlog()) {
                    syslog(LOG_NOTICE, "Blocked username authentication attempt for {$username}");
                    closelog();
                }

                do_action(__FUNCTION__.'.block_username_login', $user, $username, $password);

                return bail(); // for testing
            }
        }

        if (defined('WP_FAIL2BAN_BLOCKED_USERS') && !empty(WP_FAIL2BAN_BLOCKED_USERS)) {
            /**
             * @since 3.5.0 Arrays allowed in PHP 7
             */
            $matched = (is_array(WP_FAIL2BAN_BLOCKED_USERS))
                ? in_array($username, WP_FAIL2BAN_BLOCKED_USERS)
                : preg_match('/'.WP_FAIL2BAN_BLOCKED_USERS.'/i', $username);

            if ($matched) {
                if (openlog()) {
                    syslog(LOG_NOTICE, "Blocked authentication attempt for {$username}");
                    closelog();
                }

                do_action(__FUNCTION__.'.blocked_users', $user, $username, $password);

                return bail(); // for testing
            }
        }
    }

    return $user;
}

