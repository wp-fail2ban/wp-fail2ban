<?php declare(strict_types=1);
/**
 * Password-related functionality
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban\feature;

use       org\lecklider\charles\wordpress\wp_fail2ban\Syslog;

defined('ABSPATH') or exit;

/**
 * Log password reset requests
 *
 * @since  5.2.0    Add guard to work around WooCommerce bug
 * @since  4.4.0    Add type hint, return type
 * @since  3.5.0
 *
 * @param  string   $user_login
 *
 * @return void
 *
 * @wp-f2b-extra Password reset requested for .*
 */
function retrieve_password(string $user_login): void
{
    static $already_called = false;

    if (!$already_called) {
        $already_called = true;

        Syslog::single(LOG_NOTICE, "Password reset requested for {$user_login}", 'WP_FAIL2BAN_PASSWORD_REQUEST_LOG');

        do_action(__FUNCTION__, $user_login);
    }
}

