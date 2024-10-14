<?php declare(strict_types=1);
/**
 * WP fail2ban main file
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

require_once __DIR__.'/lib/constants.php'; // @wpf2b exclude[lite]
require_once __DIR__.'/lib/convert-data.php'; // @wpf2b exclude[lite]

require_once __DIR__.'/lib/activation.php';
require_once __DIR__.'/lib/compat.php';
require_once __DIR__.'/lib/loader.php';
require_once __DIR__.'/lib/syslog.php';

require_once __DIR__.'/core.php';
require_once __DIR__.'/feature/comments.php';
require_once __DIR__.'/feature/password.php';
require_once __DIR__.'/feature/plugins.php';
require_once __DIR__.'/feature/spam.php';
require_once __DIR__.'/feature/user-enum.php';
require_once __DIR__.'/feature/user.php';
require_once __DIR__.'/feature/xmlrpc.php';

/**
 * Helper.
 *
 * @since  4.3.2.2      Don't pass by reference
 * @since  4.3.0
 *
 * @param  mixed        $key
 * @param  array        $ary
 * @return mixed|null   Array value if present, null otherwise.
 */
function array_value($key, array $ary)
{
    return (array_key_exists($key, $ary))
        ? $ary[$key]
        : null;
}

/**
 * Graceful immediate exit
 *
 * @since  4.4.0    Add return type
 * @since  4.3.0    Remove JSON support
 * @since  4.0.5    Add JSON support
 * @since  3.5.0    Refactored for unit testing
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function bail(): bool
{
    if (false === apply_filters(__FUNCTION__, true)) {
        return false; // @codeCoverageIgnore
    }

    $execution_method = '\wp_die';

    /**
     * @since 4.3.1
     */
    if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
        global $wp_xmlrpc_server;

        /**
         * If the XML-RPC server doesn't exist the headers aren't set - work around
         */
        if (!is_object($wp_xmlrpc_server)) {
            $execution_method = '\_default_wp_die_handler';
        }
    }

    $execution_method('Forbidden', 'Forbidden', array('exit' => false, 'response' => 403));

    if (defined('PHPUNIT_COMPOSER_INSTALL')) {
        return false; // for testing
    } else {
        exit; // @codeCoverageIgnore
    }
}

/**
 * Helper: check if IP is in list of ranges
 *
 * @since  4.4.0    Add return type
 * @since  4.3.1
 *
 * @param  int|string   $ip     IP
 * @param  array        $ranges
 *
 * @return bool
 */
function ip_in_range($ip, array $ranges): bool
{
    if (is_string($ip)) {
        $ip = ip2long($ip);
    }
    foreach ($ranges as $range) {
        if ('#' == $range[0]) {
            continue;
        } elseif (2 == count($cidr = explode('/', $range))) {
            $net = ip2long($cidr[0]);
            $mask = ~ ( pow(2, (32 - $cidr[1])) - 1 );
        } else {
            $net = ip2long($range);
            $mask = -1;
        }
        if ($net == ($ip & $mask)) {
            return true;
        }
    }

    return false;
}

/**
 * Compute remote IP address
 *
 * @since  4.4.0    Add return type
 * @since  4.3.2.1  Backport fix for warning for empty proxy list (h/t @stevegrunwell)
 *
 * @return string
 *
 * @todo Test me!
 * @codeCoverageIgnore
 */
function remote_addr(): ?string
{
    static $remote_addr = null;

    /**
     * @since 4.0.0
     */
    if (is_null($remote_addr)) {
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            $ip = ip2long($_SERVER['REMOTE_ADDR']);
            $proxies = [];

            /**
             * User-defined proxies, typically upstream nginx
             */
            if (defined('WP_FAIL2BAN_PROXIES') && !empty(WP_FAIL2BAN_PROXIES)) {
                /**
                 * PHP 7 lets you define an array
                 * @since 3.5.4
                 */
                $proxies = (is_array(WP_FAIL2BAN_PROXIES))
                            ? WP_FAIL2BAN_PROXIES
                            : explode(',', WP_FAIL2BAN_PROXIES);
            }

            $proxies = apply_filters(__METHOD__, $proxies);

            if (ip_in_range($ip, $proxies)) {
                return (false === ($len = strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')))
                    ? $_SERVER['HTTP_X_FORWARDED_FOR']
                    : substr($_SERVER['HTTP_X_FORWARDED_FOR'], 0, $len);
            }
        }

        /**
         * For plugins and themes that anonymise requests
         * @since 3.6.0
         */
        $remote_addr = (defined('WP_FAIL2BAN_REMOTE_ADDR'))
            ? WP_FAIL2BAN_REMOTE_ADDR
            : $_SERVER['REMOTE_ADDR'];
    }

    return $remote_addr;
}

