<?php
/**
 * Password-related functionality
 *
 * @package wp-fail2ban
 * @since 4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban\feature;

use function org\lecklider\charles\wordpress\wp_fail2ban\openlog;
use function org\lecklider\charles\wordpress\wp_fail2ban\syslog;
use function org\lecklider\charles\wordpress\wp_fail2ban\closelog;

defined('ABSPATH') or exit;

/**
 * Log password reset requests
 *
 * @since 3.5.0
 *
 * @param string    $user_login
 *
 * @wp-f2b-extra Password reset requested for .*
 */
function retrieve_password($user_login)
{
    if (openlog('WP_FAIL2BAN_PASSWORD_REQUEST_LOG')) {
        syslog(LOG_NOTICE, "Password reset requested for {$user_login}");
        closelog();
    }

    do_action(__FUNCTION__, $user_login);
}

