<?php declare(strict_types=1);
/**
 * Convertors
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.3.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Convert various things to various other things.
 *
 * @since 4.3.0
 */
abstract class ConvertData
{
    /**
     * @since 4.3.0
     *
     * @var array Map priority value to name.
     */
    const PRIORITY2NAME = [
        LOG_CRIT    => 'critical',
        LOG_ERR     => 'error',
        LOG_WARNING => 'warning',
        LOG_NOTICE  => 'notice',
        LOG_INFO    => 'info',
        LOG_DEBUG   => 'debug'
    ];

    /**
     * @var int[] Map syslog facility name to value.
     */
    protected static $FacilityName2Value = [];

    /**
     * Convert: Syslog Priority value to slug.
     *
     * @since  4.4.0    Add type hint, return type
     * @since  4.3.0
     *
     * @param  int          $val    Syslog priority value.
     * @return string|null          Syslog priority slug.
     */
    public static function intToSyslogPrioritySlug(int $val): ?string
    {
        return array_value($val, ConvertData::PRIORITY2NAME);
    }

    /**
     * Convert: Syslog Priority value to name.
     *
     * @since  4.4.0    Add type hint, return type
     * @since  4.3.0
     *
     * @param  int          $val    Syslog priority value.
     * @return string|null          Syslog priority slug.
     */
    public static function intToSyslogPriorityName(int $val): ?string
    {
        return (is_null($name = self::intToSyslogPrioritySlug($val)))
            ? null
            : ucfirst($name);
    }

    /**
     * Helper: Return array of facilities => values.
     *
     * Makes Windows happier.
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0.10
     *
     * @return array
     *
     * @codeCoverageIgnore
     */
    public static function getFacilityNames2Values(): array
    {
        if (empty(self::$FacilityName2Value)) {
            self::$FacilityName2Value = [
                'LOG_AUTH'        => LOG_AUTH,
                'LOG_AUTHPRIV'    => LOG_AUTHPRIV,
                'LOG_CRON'        => LOG_CRON,
                'LOG_DAEMON'      => LOG_DAEMON,
                'LOG_KERN'        => LOG_KERN
            ];
            if (defined('PHP_WINDOWS_VERSION_BUILD')) {
                self::$FacilityName2Value += [
                    'LOG_LOCAL0'      => 128,
                    'LOG_LOCAL1'      => 136,
                    'LOG_LOCAL2'      => 144,
                    'LOG_LOCAL3'      => 152,
                    'LOG_LOCAL4'      => 160,
                    'LOG_LOCAL5'      => 168,
                    'LOG_LOCAL6'      => 176,
                    'LOG_LOCAL7'      => 184
                ];
            } else {
                self::$FacilityName2Value += [
                    'LOG_LOCAL0'      => LOG_LOCAL0,
                    'LOG_LOCAL1'      => LOG_LOCAL1,
                    'LOG_LOCAL2'      => LOG_LOCAL2,
                    'LOG_LOCAL3'      => LOG_LOCAL3,
                    'LOG_LOCAL4'      => LOG_LOCAL4,
                    'LOG_LOCAL5'      => LOG_LOCAL5,
                    'LOG_LOCAL6'      => LOG_LOCAL6,
                    'LOG_LOCAL7'      => LOG_LOCAL7
                ];
            }
            self::$FacilityName2Value += [
                'LOG_LPR'         => LOG_LPR,
                'LOG_MAIL'        => LOG_MAIL,
                'LOG_NEWS'        => LOG_NEWS,
                'LOG_SYSLOG'      => LOG_SYSLOG,
                'LOG_USER'        => LOG_USER,
                'LOG_UUCP'        => LOG_UUCP,
            ];
        }

        return self::$FacilityName2Value;
    }
}

