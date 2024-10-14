<?php declare(strict_types=1);
/**
 * XML-RPC functionality
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban\feature;

use          org\lecklider\charles\wordpress\wp_fail2ban\Syslog;

use function org\lecklider\charles\wordpress\wp_fail2ban\bail;
use function org\lecklider\charles\wordpress\wp_fail2ban\core\remote_addr;

defined('ABSPATH') or exit;

/**
 * Catch multiple XML-RPC authentication failures
 *
 * This is redundant in CP and newer versions of WP
 *
 * @see \wp_xmlrpc_server::login()
 *
 * @since  4.4.0    Add type hints, return type
 * @since  4.3.0    Added action
 * @since  4.0.0    Return $error
 * @since  3.5.0    Refactored for unit testing
 * @since  3.0.0
 *
 * @param  \IXR_Error   $error
 * @param  \WP_Error    $user
 *
 * @return \IXR_Error
 *
 * @wp-f2b-hard XML-RPC multicall authentication failure
 */
function xmlrpc_login_error(\IXR_Error $error, \WP_Error $user): \IXR_Error
{
    static $attempts = 0;

    if (++$attempts > 1) {
        Syslog::single(LOG_NOTICE, 'XML-RPC multicall authentication failure');

        do_action(__FUNCTION__, $error, $user);

        bail(); // Technically this doesn't return, but there seems to be no way to tell PHP Unit
    }
    return $error;
}

/**
 * Catch failed pingbacks
 *
 * @see \wp_xmlrpc_server::pingback_error()
 *
 * @since  5.2.0    Use default facility
 * @since  4.4.0    Add type hints, return type
 * @since  4.3.0    Added action
 * @since  4.0.0    Return $ixr_error
 * @since  3.5.0    Refactored for unit testing
 * @since  3.0.0
 *
 * @param  \IXR_Error   $ixr_error
 *
 * @return \IXR_Error
 *
 * @wp-f2b-hard Pingback error .* generated
 */
function xmlrpc_pingback_error(\IXR_Error $ixr_error): \IXR_Error
{
    if (48 !== $ixr_error->code) {
        Syslog::single(LOG_NOTICE, 'Pingback error '.$ixr_error->code.' generated');

        do_action(__FUNCTION__, $ixr_error);
    }
    return $ixr_error;
}

/**
 * Log pingbacks
 *
 * @since  4.4.0    Add type hint, return type
 * @since  4.3.3    Added Pingback soft filter
 * @since  4.3.0    Added actions
 * @since  3.5.0    Refactored for unit testing
 * @since  2.2.0
 *
 * @param  string   $call
 *
 * @return void
 *
 * @wp-f2b-soft Pingback requested
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function xmlrpc_call(string $call): void
{
    if ('pingback.ping' == $call) {
        Syslog::single(LOG_INFO, 'Pingback requested', 'WP_FAIL2BAN_PINGBACK_LOG');

        do_action(__FUNCTION__.'::pingback.ping');
    }

    do_action(__FUNCTION__, $call);
}

/**
 * Log XML-RPC requests
 *
 * It seems attackers are doing weird things with XML-RPC. This makes it easy to
 * log them for analysis and future blocking.
 *
 * @since  4.4.0    Add return type
 * @since  4.3.2.1  Removed PHP version check (h/t @stevegrunwell)
 * @since  4.3.0    Refactored
 * @since  4.0.0    Fix: Removed HTTP_RAW_POST_DATA
 *                  https://wordpress.org/support/?p=10971843
 * @since  3.6.0
 *
 * @return void
 *
 * @codeCoverageIgnore
 */
function xmlrpc_log(): void
{
    if (false === ($fp = fopen(Config::get('WP_FAIL2BAN_XMLRPC_LOG'), 'a+'))) {
        // TODO: decide whether to log this
    } else {
        fprintf(
            $fp,
            "# ---\n# Date: %s\n# IP: %s\n\n%s\n",
            date(DATE_ATOM),
            remote_addr(),
            file_get_contents('php://input')
        );
        fclose($fp);
    }
}

