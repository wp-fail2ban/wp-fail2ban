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
 * Password reset request logging.
 *
 * Success ("Password reset requested") is deferred until `allow_password_reset`
 * returns a truthy, non-WP_Error value. Key storage and mail send are out of scope.
 *
 * When armed and `allow_password_reset` fails, or `shutdown` runs while still
 * armed, no success log is emitted. Early failures (unknown user, etc.) remain
 * on WordPress's lostpassword flow before arming.
 */

/**
 * Armed user login for a deferred password-reset success log, or null.
 *
 * @since  5.5.0
 *
 * @param  string|null  $set    New value when $setLogin is true
 * @param  bool         $setLogin
 *
 * @return string|null
 */
function password_reset_armed(?string $set = null, bool $setLogin = false): ?string
{
    static $armed = null;

    if ($setLogin) {
        $armed = $set;
    }

    return $armed;
}

/**
 * Clear arming hooks and state.
 *
 * @since  5.5.0
 *
 * @return void
 */
function password_reset_disarm(): void
{
    password_reset_armed(null, true);
    remove_filter('allow_password_reset', __NAMESPACE__.'\allow_password_reset_armed');
    remove_action('shutdown', __NAMESPACE__.'\password_reset_shutdown');
}

/**
 * Arm logging for a password reset request.
 *
 * Does not emit success; waits for `allow_password_reset` or `shutdown`.
 *
 * @since  5.5.0    Defer outcome until allow_password_reset / shutdown
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

    if ($already_called) {
        return;
    }
    $already_called = true;

    password_reset_armed($user_login, true);

    add_filter('allow_password_reset', __NAMESPACE__.'\allow_password_reset_armed', PHP_INT_MAX, 2);
    add_action('shutdown', __NAMESPACE__.'\password_reset_shutdown');
}

/**
 * Resolve an armed password reset request from the allow check.
 *
 * @since  5.5.0
 *
 * @param  bool|\WP_Error  $allow    Whether reset is allowed
 * @param  int             $user_id  User ID being checked
 *
 * @return bool|\WP_Error  Unchanged $allow
 */
function allow_password_reset_armed($allow, $user_id)
{
    $armed = password_reset_armed();
    if (null === $armed) {
        return $allow;
    }

    $user_data = get_user_by('login', $armed);
    if ($user_data instanceof \WP_User && (int) $user_data->ID !== (int) $user_id) {
        return $allow;
    }

    $user_login = $armed;
    password_reset_disarm();

    if (!($allow instanceof \WP_Error) && $allow) {
        password_reset_log_ok($user_login);
    }

    return $allow;
}

/**
 * Drop an armed request if the allow check never ran.
 *
 * @since  5.5.0
 *
 * @return void
 */
function password_reset_shutdown(): void
{
    if (null === password_reset_armed()) {
        return;
    }

    password_reset_disarm();
}

/**
 * Log a successful password reset request (allow granted).
 *
 * @since  5.5.0
 *
 * @param  string  $user_login
 *
 * @return void
 */
function password_reset_log_ok(string $user_login): void
{
    Syslog::single(LOG_NOTICE, "Password reset requested for {$user_login}", 'WP_FAIL2BAN_PASSWORD_REQUEST_LOG');

    do_action('retrieve_password', $user_login);
}
