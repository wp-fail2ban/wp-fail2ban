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
require_once __DIR__.'/lib/hashes.php';
require_once __DIR__.'/lib/ip.php';
require_once __DIR__.'/lib/loader.php';
require_once __DIR__.'/lib/site-health.php';
require_once __DIR__.'/lib/syslog.php';
require_once __DIR__.'/lib/update.php';

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
 * @since  5.1.0        Add $lookupValue
 * @since  4.3.2.2      Don't pass by reference
 * @since  4.3.0
 *
 * @param  mixed        $key
 * @param  array        $ary
 * @param  bool         $lookupValue    If false shortcut to null return, otherwise check array.
 * @return mixed|null   Array value if present, null otherwise.
 */
function array_value($key, array $ary, bool $lookupValue = true)
{
    return ($lookupValue && array_key_exists($key, $ary))
        ? $ary[$key]
        : null;
}

/**
 * Prevent Keep-Alive connections
 *
 * @since  5.5.0    Moved from premium
 * @since  5.0.0
 *
 * @param  bool $testing
 *
 * @return bool
 */
function prevent_keepalive(bool $testing = false): bool
{
    static $shutdown = true;

    if (!headers_sent()) {
        header('Connection: close');
    }

    if ($shutdown || $testing) {
        register_shutdown_function(__NAMESPACE__.'\shutdown');
        $shutdown = false;
        return true;
    }

    return false;
}

/**
 * Try to finish FastCGI request
 *
 * @since  5.5.0    Moved from premium
 * @since  5.0.0
 *
 * @return void
 *
 * @codeCoverageIgnore
 */
function shutdown(): void
{
    if (function_exists('\fastcgi_finish_request')) {
        fastcgi_finish_request();
    }
}

/**
 * Graceful immediate exit
 *
 * @since  5.5.0    Close keep-alive connections
 * @since  4.4.0    Add return type
 * @since  4.3.0    Remove JSON support
 * @since  4.0.5    Add JSON support
 * @since  3.5.0    Refactored for unit testing
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function bail(): bool
{
    prevent_keepalive();

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

    $execution_method('Forbidden', 'Forbidden', ['exit' => !defined('PHPUNIT_COMPOSER_INSTALL'), 'response' => 403]);

    return false; // for testing
}

/**
 * Check if the current site is compatible with Premium
 *
 * @since  5.5.0
 *
 * @return bool
 */
function is_premium_compatible(): bool
{
    // PHP >= 8.1
    if (version_compare(PHP_VERSION, '8.1', '<')) {
        return false;
    }

    // WordPress >= 6.3
    if (version_compare(get_bloginfo('version'), '6.3', '<')) {
        return false;
    }

    return true;
}