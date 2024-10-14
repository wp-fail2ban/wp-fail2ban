<?php
/**
 * Loader
 *
 * @package wp-fail2ban
 * @since   4.2.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

if (defined('PHPUNIT_COMPOSER_INSTALL')) {
    return;

} elseif (!defined('ABSPATH')) {
    exit;
}

/**
 * Config
 *
 * @since 4.2.0
 */
class Config
{
    /**
     * @var array Settings
     * @since 4.3.0
     */
    protected static $settings = null;
    /**
     * @var Config Instance.
     * @since 4.3.0
     */
    protected static $instance = null;

    /**
     * Construct
     *
     * @since 4.3.0
     *
     * @param array $config Existing config options
     */
    public static function load(array $config = [])
    {
        if (is_null(self::$instance)) {
            global $wp_fail2ban;

            init_defaults();

            $class = get_called_class();
            self::$instance = new $class();

            $wp_fail2ban['config'] = apply_filters(
                __METHOD__.'.config',
                array_merge(
                    $config,
                    array(
                        'WP_FAIL2BAN_AUTH_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'authentication',
                                'facility')),
                        'WP_FAIL2BAN_LOG_COMMENTS' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'comments',
                                'enabled')),
                        'WP_FAIL2BAN_LOG_COMMENTS_EXTRA' => array(
                            'validate'  => 'intval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'comments',
                                'extra')),
                        'WP_FAIL2BAN_COMMENT_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'comments',
                                'facility')),
                        'WP_FAIL2BAN_COMMENT_EXTRA_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'comments-extra',
                                'facility')),
                        'WP_FAIL2BAN_LOG_PASSWORD_REQUEST' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'password-request',
                                'enabled')),
                        'WP_FAIL2BAN_PASSWORD_REQUEST_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'password-request',
                                'facility')),
                        'WP_FAIL2BAN_LOG_PINGBACKS' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'pingback',
                                'enabled')),
                        'WP_FAIL2BAN_PINGBACK_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'pingback',
                                'facility')),
                        'WP_FAIL2BAN_LOG_SPAM' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'spam',
                                'enabled')),
                        'WP_FAIL2BAN_SPAM_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'spam',
                                'facility')),

                        /**
                         * syslog
                         */
                        'WP_FAIL2BAN_OPENLOG_OPTIONS' => array(
                            'validate'  => 'intval',
                            'unset'     => true,
                            'field'     => array(
                                'syslog',
                                'connection')),
                        'WP_FAIL2BAN_SYSLOG_SHORT_TAG' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'syslog',
                                'workaround',
                                'short_tag')),
                        'WP_FAIL2BAN_HTTP_HOST' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'syslog',
                                'workaround',
                                'http_host')),
                        'WP_FAIL2BAN_TRUNCATE_HOST' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'syslog',
                                'workaround',
                                'truncate_host')),

                        /**
                         * Block
                         */
                        'WP_FAIL2BAN_BLOCK_USER_ENUMERATION' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'block',
                                'user_enumeration')),
                        'WP_FAIL2BAN_BLOCKED_USERS' => array(
                            'validate'  => 'strval',
                            'unset'     => true,
                            'field'     => array(
                                'block',
                                'users')),
                        'WP_FAIL2BAN_BLOCK_USERNAME_LOGIN' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'block',
                                'usernames')),

                        /**
                         * Plugins
                         */
                        'WP_FAIL2BAN_PLUGIN_LOG_AUTH' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'auth',
                                'enabled')),
                        'WP_FAIL2BAN_PLUGIN_LOG_BLOCK' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'block',
                                'enabled')),
                        'WP_FAIL2BAN_PLUGIN_LOG_COMMENT' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'comment',
                                'enabled')),
                        'WP_FAIL2BAN_PLUGIN_LOG_PASSWORD' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'password',
                                'enabled')),
                        'WP_FAIL2BAN_PLUGIN_LOG_REST' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'rest',
                                'enabled')),
                        'WP_FAIL2BAN_PLUGIN_LOG_SPAM' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'spam',
                                'enabled')),
                        'WP_FAIL2BAN_PLUGIN_LOG_XMLRPC' => array(
                            'validate'  => 'boolval',
                            'unset'     => true,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'xmlrpc',
                                'enabled')),
                        'WP_FAIL2BAN_PLUGIN_AUTH_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'auth',
                                'facility')),
                        'WP_FAIL2BAN_PLUGIN_BLOCK_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'block',
                                'facility')),
                        'WP_FAIL2BAN_PLUGIN_COMMENT_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'comment',
                                'facility')),
                        'WP_FAIL2BAN_PLUGIN_PASSWORD_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'password',
                                'facility')),
                        'WP_FAIL2BAN_PLUGIN_REST_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'rest',
                                'facility')),
                        'WP_FAIL2BAN_PLUGIN_SPAM_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'spam',
                                'facility')),
                        'WP_FAIL2BAN_PLUGIN_XMLRPC_LOG' => array(
                            'validate'  => 'intval',
                            'unset'     => false,
                            'field'     => array(
                                'logging',
                                'plugins',
                                'xmlrpc',
                                'facility')),

                        'WP_FAIL2BAN_PROXIES' => array(
                            'validate'  => __CLASS__.'::validate_ips',
                            'unset'     => true,
                            'field'     => array(
                                'remote-ip',
                                'proxies')),
                    )
                )
            );

            static::init();
        }
    }

    /**
     * Static initialiser
     *
     * @since 4.3.0
     */
    protected static function init()
    {
        global $wp_fail2ban;

        self::$settings = array();

        foreach ($wp_fail2ban['config'] as $define => $args) {
            if ($wp_fail2ban['config'][$define]['ndef'] = !defined($define)) {
                if (defined("DEFAULT_{$define}")) {
                    define($define, $args['validate'](constant("DEFAULT_{$define}")));
                } else {
                    // bah
                    define($define, call_user_func($args['validate'], false));
                }
            }
        }
    }

    /**
     * Validate IP list.
     *
     * @since 4.3.0 Refactored
     * @since 4.0.0
     *
     * @param  array|string $value
     * @return string
     */
    public static function validate_ips($value)
    {
        return self::$instance->validateIPs($value);
    }

    /**
     * Pretend to validate IPs.
     *
     * @see premium\Config\validateIPs()
     */
    public function validateIPs($value)
    {
        return (false === $value) ? '' : $value;
    }

    /**
     * Helper: filtered get_site_option('wp-fail2ban')
     *
     * @since 4.3.0
     *
     * @param  bool $filter
     * @return array
     */
    public static function settings($filter = true)
    {
        return self::$instance->getSettings($filter);
    }

    /**
     * Helper: filtered get_site_option('wp-fail2ban')
     *
     * @since 4.3.0
     *
     * @param  bool $filter
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getSettings($filter = true)
    {
        return self::$settings;
    }

    // phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    /**
     * Helper: default value
     *
     * @since 4.3.0
     *
     * @param  string   $define
     * @return mixed
     */
    public static function get_default($define)
    {
        $const = "DEFAULT_{$define}";

        return (defined($const))
            ? constant($const)
            : null;
    }
    // phpcs:enable

    /**
     * Help:er: is defined?
     *
     * @since 4.3.0
     *
     * @param  string   $define     Constant name
     * @return bool                 Is defined?
     */
    public static function def($define)
    {
        return !self::ndef($define);
    }

    /**
     * Helper: is not defined?
     *
     * @since 4.3.0
     *
     * @param  string   $define     Constant name
     * @return bool                 Is not defined?
     */
    public static function ndef($define)
    {
        return self::$instance->getNdef($define);
    }

    public function getNdef($define)
    {
        global $wp_fail2ban;

        return @$wp_fail2ban['config'][$define]['ndef'];
    }

    /**
     * Helper: get value
     *
     * @since 4.3.0
     *
     * @param  string   $define     Constant name
     * @param  array    $settings   Premium: settings to use
     * @return mixed                Constant value
     */
    public static function get($define, array $settings = null)
    {
        return self::$instance->getter($define, $settings);
    }

    /**
     * Helper: get value
     *
     * @since 4.3.0
     *
     * @param  string   $define     Constant name
     * @param  array    $settings   Premium: settings to use
     * @return mixed                Constant value
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getter($define, array $settings = null)
    {
        return (defined($define)) ? constant($define) : null;
    }

    /**
     * Helper: get description
     *
     * @since 4.3.0
     *
     * @param  string       $define Constant name.
     * @return string|null  Description.
     */
    public static function desc($define)
    {
        return self::$instance->getDesc($define);
    }

    public function getDesc($define)
    {
        switch ($define) {
            case 'WP_FAIL2BAN_AUTH_LOG':
                return __('Logins and attempted logins.', 'wp-fail2ban');
            case 'WP_FAIL2BAN_LOG_SPAM':
                return __('Log comments marked as spam.', 'wp-fail2ban');
            case 'WP_FAIL2BAN_BLOCK_USER_ENUMERATION':
                return __('Stop attackers listing existing usernames.', 'wp-fail2ban');
            case 'WP_FAIL2BAN_BLOCK_USERNAME_LOGIN':
                return __('Allow <b>email addresses only</b> for login.', 'wp-fail2ban');
            case 'WP_FAIL2BAN_PROXIES':
                return __('Trusted IPv4 list.', 'wp-fail2ban');
            default:
                return null;
        }
    }
}

