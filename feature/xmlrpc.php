<?php
/**
 * XML-RPC functionality
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
 * Catch multiple XML-RPC authentication failures
 *
 * This is redundant in CP and newer versions of WP
 *
 * @see \wp_xmlrpc_server::login()
 *
 * @since 4.3.0 Added action
 * @since 4.0.0 Return $error
 * @since 3.5.0 Refactored for unit testing
 * @since 3.0.0
 *
 * @param \IXR_Error    $error
 * @param \WP_Error     $user
 *
 * @return \IXR_Error
 *
 * @wp-f2b-hard XML-RPC multicall authentication failure
 */
function xmlrpc_login_error($error, $user)
{
    static $attempts = 0;

    if (++$attempts > 1) {
        if (openlog()) {
            syslog(LOG_NOTICE, 'XML-RPC multicall authentication failure');
            closelog();
        }

        do_action(__FUNCTION__, $error, $user);

        bail();
    } else {
        return $error;
    }
} // @codeCoverageIgnore

/**
 * Catch failed pingbacks
 *
 * @see \wp_xmlrpc_server::pingback_error()
 *
 * @since 4.3.0 Added action
 * @since 4.0.0 Return $ixr_error
 * @since 3.5.0 Refactored for unit testing
 * @since 3.0.0
 *
 * @param \IXR_Error    $ixr_error
 *
 * @return \IXR_Error
 *
 * @wp-f2b-hard Pingback error .* generated
 */
function xmlrpc_pingback_error($ixr_error)
{
    if (48 !== $ixr_error->code) {
        if (openlog()) {
            syslog(LOG_NOTICE, 'Pingback error '.$ixr_error->code.' generated');
            closelog();
        }

        do_action(__FUNCTION__, $ixr_error);
    }
    return $ixr_error;
}

/**
 * Log pingbacks
 *
 * @since 4.3.0 Added actions
 * @since 3.5.0 Refactored for unit testing
 * @since 2.2.0
 *
 * @param string    $call
 */
function xmlrpc_call($call)
{
    if ('pingback.ping' == $call) {
        if (openlog('WP_FAIL2BAN_PINGBACK_LOG')) {
            syslog(LOG_INFO, 'Pingback requested');
            closelog();
        }

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
 * @since 4.3.0.9   Removed PHP version check (h/t @stevegrunwell)
 * @since 4.3.0     Refactored
 * @since 4.0.0     Fix: Removed HTTP_RAW_POST_DATA
 *                  https://wordpress.org/support/?p=10971843
 * @since 3.6.0
 *
 * @codeCoverageIgnore
 */
function xmlrpc_log()
{
    if (false === ($fp = fopen(WP_FAIL2BAN_XMLRPC_LOG, 'a+'))) {
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

