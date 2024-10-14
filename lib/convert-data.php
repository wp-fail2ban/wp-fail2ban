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
     * @var string[] Map Event ID to Slug.
     */
    const EVENT2SLUG = [
        0x00000000                            => 'deactivated',
        WPF2B_EVENT_AUTH_OK                   => 'auth_ok',
        WPF2B_EVENT_AUTH_FAIL                 => 'auth_fail',
        WPF2B_EVENT_AUTH_BLOCK_USER           => 'auth_block_user',
        WPF2B_EVENT_AUTH_BLOCK_USER_ENUM      => 'auth_block_user_enum',
        WPF2B_EVENT_AUTH_BLOCK_USERNAME_LOGIN => 'auth_block_usernames',
        WPF2B_EVENT_AUTH_EMPTY_USER           => 'auth_empty_user',
        WPF2B_EVENT_COMMENT                   => 'comment',
        WPF2B_EVENT_COMMENT_SPAM              => 'comment_spam',
        WPF2B_EVENT_COMMENT_NOT_FOUND         => 'comment_not_found',
        WPF2B_EVENT_COMMENT_CLOSED            => 'comment_closed',
        WPF2B_EVENT_COMMENT_TRASH             => 'comment_trash',
        WPF2B_EVENT_COMMENT_DRAFT             => 'comment_draft',
        WPF2B_EVENT_COMMENT_PASSWORD          => 'comment_password',
        WPF2B_EVENT_XMLRPC_PINGBACK           => 'xmlrpc_pingback',
        WPF2B_EVENT_XMLRPC_PINGBACK_ERROR     => 'xmlrpc_pingback_error',
        WPF2B_EVENT_XMLRPC_MULTI_AUTH_FAIL    => 'xmlrpc_multi_auth_fail',
        WPF2B_EVENT_XMLRPC_AUTH_OK            => 'xmlrpc_auth_ok',
        WPF2B_EVENT_XMLRPC_AUTH_FAIL          => 'xmlrpc_auth_fail',
        WPF2B_EVENT_PASSWORD_REQUEST          => 'password_request',
        WPF2B_EVENT_REST_AUTH_OK              => 'rest_auth_ok',
        WPF2B_EVENT_REST_AUTH_FAIL            => 'rest_auth_fail',
        0xFFFFFFFF                            => 'activated',
    ];

    /**
     * @var int[] Map Event Slug to ID.
     */
    const SLUG2EVENT = array(
        'deactivated'             => 0x00000000,
        'auth_ok'                 => WPF2B_EVENT_AUTH_OK,
        'auth_fail'               => WPF2B_EVENT_AUTH_FAIL,
        'auth_block_user'         => WPF2B_EVENT_AUTH_BLOCK_USER,
        'auth_block_user_enum'    => WPF2B_EVENT_AUTH_BLOCK_USER_ENUM,
        'auth_block_usernames'    => WPF2B_EVENT_AUTH_BLOCK_USERNAME_LOGIN,
        'auth_empty_user'         => WPF2B_EVENT_AUTH_EMPTY_USER,
        'comment_spam'            => WPF2B_EVENT_COMMENT_SPAM,
        'comment_not_found'       => WPF2B_EVENT_COMMENT_NOT_FOUND,
        'comment_closed'          => WPF2B_EVENT_COMMENT_CLOSED,
        'comment_trash'           => WPF2B_EVENT_COMMENT_TRASH,
        'comment_draft'           => WPF2B_EVENT_COMMENT_DRAFT,
        'comment_password'        => WPF2B_EVENT_COMMENT_PASSWORD,
        'xmlrpc_pingback'         => WPF2B_EVENT_XMLRPC_PINGBACK,
        'xmlrpc_pingback_error'   => WPF2B_EVENT_XMLRPC_PINGBACK_ERROR,
        'xmlrpc_multi_auth_fail'  => WPF2B_EVENT_XMLRPC_MULTI_AUTH_FAIL,
        'xmlrpc_auth_ok'          => WPF2B_EVENT_XMLRPC_AUTH_OK,
        'xmlrpc_auth_fail'        => WPF2B_EVENT_XMLRPC_AUTH_FAIL,
        'password_request'        => WPF2B_EVENT_PASSWORD_REQUEST,
        'rest_auth_ok'            => WPF2B_EVENT_REST_AUTH_OK,
        'rest_auth_fail'          => WPF2B_EVENT_REST_AUTH_FAIL,
        'activated'               => 0xFFFFFFFF,
    );

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

