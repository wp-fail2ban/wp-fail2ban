<?php declare(strict_types=1);
/**
 * Back-Compat
 *
 * @package wp-fail2ban
 * @since  4.4.0
 */
namespace {
    /**
     * For ClassicPress and WP < 5.3
     *
     * @codeCoverageIgnore
     */
    if (!function_exists('wp_timezone_string')) {
        /**
         * Retrieves the timezone of the site as a string.
         *
         * Uses the `timezone_string` option to get a proper timezone name if available,
         * otherwise falls back to a manual UTC ± offset.
         *
         * Example return values:
         *
         *  - 'Europe/Rome'
         *  - 'America/North_Dakota/New_Salem'
         *  - 'UTC'
         *  - '-06:30'
         *  - '+00:00'
         *  - '+08:45'
         *
         * @since 5.3.0
         *
         * @return string PHP timezone name or a ±HH:MM offset.
         *
         * @codeCoverageIgnoreStart
         */
        function wp_timezone_string()
        {
            $timezone_string = get_option('timezone_string');

            if ($timezone_string) {
                return $timezone_string;
            }

            $offset  = (float) get_option('gmt_offset');
            $hours   = (int) $offset;
            $minutes = ( $offset - $hours );

            $sign      = ( $offset < 0 ) ? '-' : '+';
            $abs_hour  = abs($hours);
            $abs_mins  = abs($minutes * 60);
            $tz_offset = sprintf('%s%02d:%02d', $sign, $abs_hour, $abs_mins);

            return $tz_offset;
        }
    }
}

namespace org\lecklider\charles\wordpress\wp_fail2ban {
    /**
     * Shim: Wrapper for \openlog
     *
     * @since  4.4.0    Refactor for Syslog class; add type hint, return type
     * @since  3.5.0    Refactored for unit testing
     *
     * @param  string   $log
     *
     * @return bool
     */
    function openlog(string $log = 'WP_FAIL2BAN_AUTH_LOG'): bool
    {
        return Syslog::open($log);
    }

    /**
     * Shim: Wrapper for \syslog
     *
     * @since  4.4.0    Refactor for Syslog class; add type hint, return type
     * @since  3.5.0
     *
     * @param  int          $level
     * @param  string       $msg
     * @param  string|null  $remote_addr
     *
     * @return void
     *
     * @codeCoverageIgnore
     */
    function syslog(int $level, string $msg, string $remote_addr = null): bool
    {
        return Syslog::write($level, $msg, $remote_addr);
    }

    /**
     * Shim: Wrapper for \closelog
     *
     * @since  4.4.0    Refactor for Syslog class; add return type
     * @since  4.3.0
     *
     * @return void
     */
    function closelog(): bool
    {
        return Syslog::close();
    }

    /**
     * Helper: check if IP is in list of ranges
     *
     * @since  5.0.0    Deprecated
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
     * Shim: Wrap new remote_addr() for older add-ons
     *
     * @since  5.0.0
     *
     * @return string
     *
     * @codeCoverageIgnoreStart
     */
    function ___remote_addr(): string
    {
        return (string)core\remote_addr();
    }
}

