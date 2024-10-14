<?php
/**
 * WP fail2ban main file
 *
 * @package wp-fail2ban
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

require_once __DIR__.'/lib/constants.php'; // @wpf2b exclude[lite]
require_once __DIR__.'/lib/convert-data.php'; // @wpf2b exclude[lite]

require_once __DIR__.'/lib/defaults.php';
require_once __DIR__.'/lib/activation.php';
require_once __DIR__.'/lib/loader.php';

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
 * @since 4.3.0
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
 * Wrapper for \openlog
 *
 * @since 3.5.0 Refactored for unit testing
 *
 * @param string $log
 */
function openlog($log = 'WP_FAIL2BAN_AUTH_LOG')
{
    $tag    = (defined('WP_FAIL2BAN_SYSLOG_SHORT_TAG') && true === WP_FAIL2BAN_SYSLOG_SHORT_TAG)
                ? 'wp' // @codeCoverageIgnore
                : 'wordpress';
    $host   = (array_key_exists('WP_FAIL2BAN_HTTP_HOST', $_ENV))
                ? $_ENV['WP_FAIL2BAN_HTTP_HOST'] // @codeCoverageIgnore
                : $_SERVER['HTTP_HOST'];
    if (is_multisite() && !SUBDOMAIN_INSTALL) {
        /**
         * @todo Test me!
         */
        // @codeCoverageIgnoreStart
        if (!is_main_site()) {
            $blog = get_blog_details(get_current_blog_id(), false);
            $host .= '/'.trim($blog->path, '/');
        } // @codeCoverageIgnoreEnd
    }
    /**
     * Some varieties of syslogd have difficulty if $host is too long
     * @since 3.5.0
     */
    if (defined('WP_FAIL2BAN_TRUNCATE_HOST') && 1 < intval(WP_FAIL2BAN_TRUNCATE_HOST)) {
        $host = substr($host, 0, intval(WP_FAIL2BAN_TRUNCATE_HOST));
    }
    /**
     * Refactor for unit testing.
     * @since 4.3.0
     */
    $options    = (defined('WP_FAIL2BAN_OPENLOG_OPTIONS'))
        ? WP_FAIL2BAN_OPENLOG_OPTIONS // @codeCoverageIgnore
        : null;
    $ident      = "$tag($host)";
    if (true !== apply_filters(__FUNCTION__, true, $ident, $options, constant($log))) {
        return true; // @codeCoverageIgnore
    } elseif (false === \openlog($ident, $options, constant($log))) {
        error_log('WPf2b: Cannot open syslog', 0); // @codeCoverageIgnore
    } elseif (defined('WP_FAIL2BAN_TRACE')) {
        error_log('WPf2b: Opened syslog', 0); // @codeCoverageIgnore
    } else {
        return true;
    }
} // @codeCoverageIgnore

/**
 * Wrapper for \syslog
 *
 * @since 3.5.0
 *
 * @param int           $level
 * @param string        $msg
 * @param string|null   $remote_addr
 */
function syslog($level, $msg, $remote_addr = null)
{
    if (true === apply_filters(__FUNCTION__, true, $level, $msg, $remote_addr)) {
        $msg .= ' from ';
        $msg .= (is_null($remote_addr))
                    ? remote_addr()
                    : $remote_addr;

        if (false === \syslog($level, $msg)) {
            error_log("WPf2b: Cannot write to syslog: '{$msg}'", 0); // @codeCoverageIgnore
        } elseif (defined('WP_FAIL2BAN_TRACE')) {
            error_log("WPf2b: Wrote to syslog: '{$msg}'", 0); // @codeCoverageIgnore
        }
    }

    if (defined('PHPUNIT_COMPOSER_INSTALL')) {
        echo "$level|$msg";
    }

    /**
     * @since 4.3.0
     */
    if (!defined('WP_FAIL2BAN_DISABLE_LAST_LOG') || true !== WP_FAIL2BAN_DISABLE_LAST_LOG) {
        if (!is_array($last_messages = get_site_option('wp-fail2ban-messages', []))) {
            $last_messages = [];
        }
        $message = [
            'dt' => gmdate('Y-m-d H:i:s'),
            'lvl' => ConvertData::intToSyslogPriorityName($level),
            'msg' => $msg
        ];
        array_unshift($last_messages, $message);
        while (5 < count($last_messages)) {
            array_pop($last_messages); // @codeCoverageIgnore
        }
        update_site_option('wp-fail2ban-messages', $last_messages);
    }
}

/**
 * Wrapper for \closelog
 *
 * @since 4.3.0
 */
function closelog()
{
    if (true === apply_filters(__FUNCTION__, true)) {
        \closelog();
    }
}

/**
 * Graceful immediate exit
 *
 * @since 4.3.0 Remove JSON support
 * @since 4.0.5 Add JSON support
 * @since 3.5.0 Refactored for unit testing
 *
 * @param bool  $is_json
 *
 * @SuppressWarnings(PHPMD.ExitExpression)
 */
function bail()
{
    if (false === apply_filters(__FUNCTION__, true)) {
        return false; // @codeCoverageIgnore
    }

    \wp_die('Forbidden', 'Forbidden', array('exit' => false, 'response' => 403));

    if (defined('PHPUNIT_COMPOSER_INSTALL')) {
        return false; // for testing
    } else {
        exit; // @codeCoverageIgnore
    }
}


/**
 * Compute remote IP address
 *
 * @since   4.3.0.9 Backport fix for warning for empty proxy list (h/t @stevegrunwell)
 *
 * @return string
 *
 * @todo Test me!
 * @codeCoverageIgnore
 */
function remote_addr()
{
    static $remote_addr = null;

    /**
     * @since 4.0.0
     */
    if (is_null($remote_addr)) {
        if (defined('WP_FAIL2BAN_PROXIES') && !empty(WP_FAIL2BAN_PROXIES)) {
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
                $ip = ip2long($_SERVER['REMOTE_ADDR']);
                /**
                 * PHP 7 lets you define an array
                 * @since 3.5.4
                 */
                $proxies = (is_array(WP_FAIL2BAN_PROXIES))
                            ? WP_FAIL2BAN_PROXIES
                            : explode(',', WP_FAIL2BAN_PROXIES);
                foreach ($proxies as $proxy) {
                    if ('#' == $proxy[0]) {
                        continue;
                    } elseif (2 == count($cidr = explode('/', $proxy))) {
                        $net = ip2long($cidr[0]);
                        $mask = ~ ( pow(2, (32 - $cidr[1])) - 1 );
                    } else {
                        $net = ip2long($proxy);
                        $mask = -1;
                    }
                    if ($net == ($ip & $mask)) {
                        return (false === ($len = strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')))
                            ? $_SERVER['HTTP_X_FORWARDED_FOR']
                            : substr($_SERVER['HTTP_X_FORWARDED_FOR'], 0, $len);
                    }
                }
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

