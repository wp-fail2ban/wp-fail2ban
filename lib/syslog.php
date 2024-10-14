<?php declare(strict_types=1);
/**
 * Syslog wrapper
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.3.4.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

use function org\lecklider\charles\wordpress\wp_fail2ban\core\remote_addr;

defined('ABSPATH') or exit;

abstract class Syslog
{
    /**
     * Helper: Build tag part
     *
     * @since  4.4.0    Add return type
     * @since  4.3.4.0
     *
     * @return string
     */
    protected static function getTag(): string
    {
        return (Config::get('WP_FAIL2BAN_SYSLOG_SHORT_TAG'))
            ? 'wp'
            : 'wordpress';
    }

    /**
     * Helper: Build host part
     *
     * @since  4.4.0    Add return type
     * @since  4.3.4.0
     *
     * @return string
     */
    protected static function getHost(): string
    {
        $host   = (array_key_exists('WP_FAIL2BAN_HTTP_HOST', $_ENV))
                    ? $_ENV['WP_FAIL2BAN_HTTP_HOST'] // @codeCoverageIgnore
                    : $_SERVER['HTTP_HOST'];

        if (is_multisite() && !SUBDOMAIN_INSTALL) {
            if (!is_main_site()) {
                $blog = get_blog_details(get_current_blog_id(), false);
                $host .= '/'.trim($blog->path, '/');
            }
        }

        /**
         * Some varieties of syslogd have difficulty if $host is too long
         * @since 3.5.0
         */
        if (0 < ($len = Config::get('WP_FAIL2BAN_TRUNCATE_HOST'))) {
            $host = substr($host, 0, $len);
        }

        return $host;
    }

    /**
     * Update list of last syslog messages
     *
     * @since  4.4.0    Add return type
     * @since  4.3.4.0
     *
     * @param  int      $level
     * @param  string   $msg
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    protected static function updateLastMessages(int $level, string $msg): void
    {
        if (!Config::get('WP_FAIL2BAN_DISABLE_LAST_LOG')) {
            if (!is_array($last_messages = get_site_option('wp-fail2ban-messages', []))) {
                $last_messages = [];
            }
            $message = [
                'dt' => gmdate('Y-m-d H:i:s'),
                'lvl' => ConvertData::intToSyslogPriorityName($level),
                'msg' => $msg
            ];
            array_unshift($last_messages, $message);
            $last_messages = array_slice($last_messages, 0, 5);
            update_site_option('wp-fail2ban-messages', $last_messages);
        }
    }

    /**
     * Open
     *
     * @since  4.4.0    Add return type
     * @since  4.3.4.0
     *
     * @param  string   $log
     *
     * @return bool
     */
    public static function open(string $log = 'WP_FAIL2BAN_AUTH_LOG'): bool
    {
        $tag    = self::getTag();
        $host   = self::getHost();

        $options    = Config::get('WP_FAIL2BAN_OPENLOG_OPTIONS');
        $facility   = Config::get($log);
        $ident      = "$tag($host)";
        if (null !== ($rv = apply_filters(__METHOD__, null, $ident, $options, $facility))) {
            return $rv;
        }

        if (false === \openlog($ident, $options, $facility)) {
            error_log('WPf2b: Cannot open syslog', 0); // @codeCoverageIgnore

        } else {
            if (defined('WP_FAIL2BAN_TRACE')) {
                error_log('WPf2b: Opened syslog', 0); // @codeCoverageIgnore
            }
            return true;
        }

        return false; // @codeCoverageIgnore
    }

    /**
     * Write
     *
     * @since  4.4.0    Add return type
     * @since  4.3.4.0
     *
     * @param  int          $level
     * @param  string       $msg
     * @param  string|null  $remote_addr
     *
     * @return bool
     */
    public static function write(int $level, string $msg, string $remote_addr = null): bool
    {
        $rv = false;

        if (null === ($rv = apply_filters(__METHOD__, null, $level, $msg, $remote_addr))) {
            $msg .= ' from ';
            $msg .= (is_null($remote_addr))
                        ? remote_addr()
                        : $remote_addr;

            if (false === ($rv = \syslog($level, $msg))) {
                error_log("WPf2b: Cannot write to syslog: '{$msg}'", 0); // @codeCoverageIgnore
            } elseif (defined('WP_FAIL2BAN_TRACE')) {
                error_log("WPf2b: Wrote to syslog: '{$msg}'", 0); // @codeCoverageIgnore
            }
        }

        if (defined('PHPUNIT_COMPOSER_INSTALL')) {
            echo "$level|$msg";
        }

        self::updateLastMessages($level, $msg);

        return $rv ?? false;
    }

    /**
     * Close
     *
     * @since  4.4.0    Add return type
     * @since  4.3.4.0
     *
     * @return bool
     */
    public static function close(): bool
    {
        if (null === ($rv = apply_filters(__FUNCTION__, null))) {
            $rv = \closelog();
        }

        return $rv;
    }

    /**
     * Wrapper: write a single log entry
     *
     * @since  4.4.0    Add return type
     * @since  4.3.4.0
     *
     * @param  int      $level      LOG_DEBUG|LOG_INFO|LOG_NOTICE|LOG_WARNING|LOG_ERR
     * @param  string   $msg
     * @param  string   $log
     * @param  string   $remote_addr
     *
     * @return bool
     */
    public static function single(int $level, string $msg, string $log = 'WP_FAIL2BAN_AUTH_LOG', string $remote_addr = null): bool
    {
        $rv = false;

        if (self::open($log)) {
            $rv = self::write($level, $msg, $remote_addr);
            self::close();
        }

        return $rv;
    }
}

