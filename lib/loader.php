<?php declare(strict_types=1);
/**
 * Loader
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
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
     * Standard config
     *
     * @since  4.3.2.1  Moved from inline array.
     */
    const CONFIG = [
        'WP_FAIL2BAN_AUTH_LOG' => [ //
            'validate'  => 'intval',
            'unset'     => true,
            'default'   => WPF2B_FACILITY_LOG_AUTH,
            'field'     => [
                'logging',
                'authentication',
                'facility']],
        'WP_FAIL2BAN_LOG_COMMENTS' => [ //
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'comments',
                'enabled']],
        'WP_FAIL2BAN_LOG_COMMENT_ATTEMPTS' => [
            'validate' => 'boolval',
            'unset' => true,
            'field'     => [
                'logging',
                'comment',
                'attempts']],
        'WP_FAIL2BAN_LOG_COMMENTS_EXTRA' => [ //
            'validate'  => 'intval',
            'unset'     => true,
            'field'     => [
                'logging',
                'comments',
                'extra']],
        'WP_FAIL2BAN_COMMENT_LOG' => [ //
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => LOG_USER,
            'field'     => [
                'logging',
                'comments',
                'facility']],
        'WP_FAIL2BAN_COMMENT_ATTEMPT_LOG' => [ //
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => WPF2B_FACILITY_LOG_AUTH,
            'field'     => [
                'logging',
                'comment-attempts',
                'facility']],
        'WP_FAIL2BAN_COMMENT_EXTRA_LOG' => [ //
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => WPF2B_FACILITY_LOG_AUTH,
            'field'     => [
                'logging',
                'comments-extra',
                'facility']],
        'WP_FAIL2BAN_LOG_PASSWORD_REQUEST' => [ //
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'password-request',
                'enabled']],
        'WP_FAIL2BAN_PASSWORD_REQUEST_LOG' => [ //
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => LOG_USER,
            'field'     => [
                'logging',
                'password-request',
                'facility']],
        'WP_FAIL2BAN_LOG_PINGBACKS' => [ //
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'pingback',
                'enabled']],
        'WP_FAIL2BAN_PINGBACK_LOG' => [ //
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => LOG_USER,
            'field'     => [
                'logging',
                'pingback',
                'facility']],
        'WP_FAIL2BAN_LOG_SPAM' => [ //
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'spam',
                'enabled']],
        'WP_FAIL2BAN_SPAM_LOG' => [ //
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => WPF2B_FACILITY_LOG_AUTH,
            'field'     => [
                'logging',
                'spam',
                'facility']],

        /**
         * syslog
         */
        'WP_FAIL2BAN_OPENLOG_OPTIONS' => [
            'validate'  => 'intval',
            'unset'     => true,
            'default'   => LOG_PID|LOG_NDELAY,
            'field'     => [
                'syslog',
                'connection']],
        'WP_FAIL2BAN_SYSLOG_SHORT_TAG' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'syslog',
                'workaround',
                'short_tag']],
        'WP_FAIL2BAN_HTTP_HOST' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'syslog',
                'workaround',
                'http_host']],
        'WP_FAIL2BAN_TRUNCATE_HOST' => [ //
            'validate'  => 'intval',
            'unset'     => true,
            'field'     => [
                'syslog',
                'workaround',
                'truncate_host']],

        /**
         * Block
         */
        'WP_FAIL2BAN_BLOCK_USER_ENUMERATION' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'block',
                'user_enumeration']],
        'WP_FAIL2BAN_BLOCKED_USERS' => [
            'validate'  => __CLASS__.'::str_array_val',
            'unset'     => true,
            'field'     => [
                'block',
                'users']],
        'WP_FAIL2BAN_BLOCK_USERNAME_LOGIN' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'block',
                'usernames']],

        /**
         * Plugins
         */
        'WP_FAIL2BAN_PLUGIN_LOG_AUTH' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'plugins',
                'auth',
                'enabled']],
        'WP_FAIL2BAN_PLUGIN_LOG_BLOCK' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'plugins',
                'block',
                'enabled']],
        'WP_FAIL2BAN_PLUGIN_LOG_COMMENT' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'plugins',
                'comment',
                'enabled']],
        'WP_FAIL2BAN_PLUGIN_LOG_PASSWORD' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'plugins',
                'password',
                'enabled']],
        'WP_FAIL2BAN_PLUGIN_LOG_REST' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'plugins',
                'rest',
                'enabled']],
        'WP_FAIL2BAN_PLUGIN_LOG_SPAM' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'plugins',
                'spam',
                'enabled']],
        'WP_FAIL2BAN_PLUGIN_LOG_XMLRPC' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'plugins',
                'xmlrpc',
                'enabled']],
        'WP_FAIL2BAN_PLUGIN_LOG_OTHER' => [
            'validate'  => 'boolval',
            'unset'     => true,
            'field'     => [
                'logging',
                'plugins',
                'other',
                'enabled']],
        'WP_FAIL2BAN_PLUGIN_AUTH_LOG' => [
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => WPF2B_FACILITY_LOG_AUTH,
            'field'     => [
                'logging',
                'plugins',
                'auth',
                'facility']],
        'WP_FAIL2BAN_PLUGIN_BLOCK_LOG' => [
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => LOG_USER,
            'field'     => [
                'logging',
                'plugins',
                'block',
                'facility']],
        'WP_FAIL2BAN_PLUGIN_COMMENT_LOG' => [
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => LOG_USER,
            'field'     => [
                'logging',
                'plugins',
                'comment',
                'facility']],
        'WP_FAIL2BAN_PLUGIN_PASSWORD_LOG' => [
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => LOG_USER,
            'field'     => [
                'logging',
                'plugins',
                'password',
                'facility']],
        'WP_FAIL2BAN_PLUGIN_REST_LOG' => [
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => LOG_USER,
            'field'     => [
                'logging',
                'plugins',
                'rest',
                'facility']],
        'WP_FAIL2BAN_PLUGIN_SPAM_LOG' => [
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => WPF2B_FACILITY_LOG_AUTH,
            'field'     => [
                'logging',
                'plugins',
                'spam',
                'facility']],
        'WP_FAIL2BAN_PLUGIN_XMLRPC_LOG' => [
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => LOG_USER,
            'field'     => [
                'logging',
                'plugins',
                'xmlrpc',
                'facility']],
        'WP_FAIL2BAN_PLUGIN_OTHER_LOG' => [ //
            'validate'  => 'intval',
            'unset'     => false,
            'default'   => LOG_USER,
            'field'     => [
                'logging',
                'plugins',
                'other',
                'facility']],

        'WP_FAIL2BAN_PROXIES' => [
            'validate'  => __CLASS__.'::validate_ips',
            'unset'     => true,
            'field'     => [
                'remote-ip',
                'proxies']],

        /**
         * Misc
         */
        'WP_FAIL2BAN_DISABLE_LAST_LOG' => [
            'validate'  => 'boolval',
            'unset'     => false,
            'field'     => [
                'misc',
                'last-log',
                'disabled']],
        'WP_FAIL2BAN_SITE_HEALTH_SKIP_FILTERS' => [
            'validate'  => 'boolval',
            'unset'     => false,
            'field'     => [
                'misc',
                'site-health',
                'filters',
                'disabled']],
    ];

    /**
     * @var array Settings
     * @since 4.3.0
     * @todo Set type to ?array
     */
    protected static $settings = null;
    /**
     * @var Config Instance.
     * @since 4.3.0
     * @todo Set type to ?Config
     */
    protected static $instance = null;

    /**
     * @var Cache defines
     * @since 4.3.3
     * @todo Set type to array
     */
    protected static $cache = [];

    /**
     * Construct
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @param  array    $config Existing config options
     *
     * @return Config|null
     */
    public static function load(array $config = []): ?Config
    {
        global $wp_fail2ban;

        if (is_null(self::$instance)) {
            $class = get_called_class();
            self::$instance = new $class();

            $wp_fail2ban['config'] = apply_filters(__METHOD__.'.config', array_merge($config, self::CONFIG));

            static::init();

        } elseif (!empty($config)) {
            $wp_fail2ban['config'] = array_merge($wp_fail2ban['config'], $config);
        }
        return self::$instance;
    }

    /**
     * Helper: for testing
     *
     * @since  4.4.0
     *
     * @return void
     */
    protected static function unload(): void
    {
        global $wp_fail2ban;

        self::$instance = null;
        unset($wp_fail2ban['config']);
    }

    /**
     * Helper: for testing
     *
     * @since  4.4.0
     *
     * @return bool
     */
    protected static function is_loaded(): bool
    {
        return (self::$instance instanceof Config);
    }

    /**
     * Static initialiser
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @return void
     */
    protected static function init(): void
    {
        self::$settings = [];
    }

    /**
     * Validate IP list.
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0    Refactored
     * @since  4.0.0
     *
     * @param  array|string $value
     * @return array|string
     */
    public static function validate_ips($value)
    {
        return self::$instance->validateIPs($value);
    }

    /**
     * Pretend to validate IPs.
     *
     * @see premium\Config\validateIPs()
     *
     * @since  5.0.0    Refactor; handle empty string
     * @since  4.4.1    Always return array
     *
     * @param  array|string $value
     * @return array
     */
    public function validateIPs($value): array
    {
        // Default value
        if (false === $value) {
            return [];
        }

        // Split the string
        if (is_string($value)) {
            $value = array_map('\\trim', explode(',', $value));
        }

        if (is_array($value)) {
            return array_filter($value); // Make sure there are no empty values
        }

        throw new \InvalidArgumentException('Wrong type.');
    }

    /**
     * Validate string or array.
     *
     * @since  4.4.0.4
     *
     * @param  array|string|false   $value
     *
     * @return array|string
     */
    public static function str_array_val($value)
    {
        return self::$instance->validateStringOrArray($value);
    }

    /**
     * Validate string or array.
     *
     * @since  4.4.0.4
     *
     * @param  array|string|false   $value
     *
     * @return array|string
     */
    public function validateStringOrArray($value)
    {
        if (false === $value) {
            return [];

        } elseif (is_array($value) || is_string($value)) {
            return $value;

        } else {
            throw new \InvalidArgumentException();
        }
    }

    /**
     * Helper: filtered get_site_option('wp-fail2ban')
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @param  bool $filter
     * @return array
     */
    public static function settings($filter = true): array
    {
        return self::$instance->getSettings($filter);
    }

    /**
     * Helper: filtered get_site_option('wp-fail2ban')
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @param  bool $filter
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getSettings(bool $filter = true): array
    {
        return self::$settings;
    }

    // phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
    /**
     * Helper: default value
     *
     * @since  4.4.0    Add type hint, refactor for 7.4
     * @since  4.3.4.0  Refactor
     * @since  4.3.0
     *
     * @param  string   $define
     * @return mixed
     */
    public static function get_default(string $define)
    {
        global $wp_fail2ban;

        return $wp_fail2ban['config'][$define]['default'] ?? null;
    }
    // phpcs:enable

    /**
     * Help:er: is defined?
     *
     * @since  4.4.0    Add type hint, return type
     * @since  4.3.0
     *
     * @param  string   $define     Constant name
     * @return bool                 Is defined?
     */
    public static function def(string $define): bool
    {
        return !self::ndef($define);
    }

    /**
     * Helper: is not defined?
     *
     * @since  4.4.0    Add type hint, return type
     * @since  4.3.0
     *
     * @param  string   $define     Constant name
     * @return bool                 Is not defined?
     */
    public static function ndef(string $define): bool
    {
        return self::$instance->getNdef($define);
    }

    /**
     * Helper: is not defined?
     *
     * @since  4.4.0    Add type hint, return type, refactor for 7.4
     * @since  4.3.3    Refactored for lazy-loading
     * @since  4.3.0
     *
     * @param  string   $define     Constant name
     * @return bool                 Is NOT defined?
     */
    public function getNdef(string $define): bool
    {
        global $wp_fail2ban;

        $def = &$wp_fail2ban['config'][$define];
        return $def['ndef'] ?? ($def['ndef'] = !defined($define));
    }

    /**
     * Magic helper
     *
     * @since  4.4.0    Add type hint
     *
     * @return mixed|null
     *
     * @codeCoverageIgnore
     */
    public function __get(string $define) // : ?mixed
    {
        return self::$instance->getter($define);
    }

    /**
     * Helper: get value
     *
     * @since  4.4.0    Add type hints
     * @since  4.3.0
     *
     * @param  string   $define     Constant name
     * @param  array    $settings   Premium: settings to use
     * @return mixed                Constant value
     */
    public static function get(string $define, array $settings = null)
    {
        return self::$instance->getter($define, $settings);
    }

    /**
     * Helper: get value
     *
     * @since  5.0.0    Add filter
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.3    Refactored for lazy-loading
     * @since  4.3.0
     *
     * @param  string   $define     Constant name
     * @param  array    $settings   Premium: settings to use
     *
     * @throws \UnexpectedValueException
     *
     * @return mixed                Constant value
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getter(string $define, array $settings = null)
    {
        if (!isset(self::$cache[$define])) {
            global $wp_fail2ban;

            assert(array_key_exists($define, $wp_fail2ban['config']));
            $def = &$wp_fail2ban['config'][$define];
            assert(is_array($def));

            if (!array_key_exists('validate', $def)) {
                throw new \UnexpectedValueException($define);

            } elseif (!($def['ndef'] = !defined($define))) { // defined($define)
                self::$cache[$define] = $def['validate'](apply_filters($define, constant($define)));

            } else {
                self::$cache[$define] = (isset($def['default']))
                    ? $def['validate'](apply_filters($define, $def['default']))
                    : call_user_func($def['validate'], apply_filters($define, false));
                if (!defined('PHPUNIT_COMPOSER_INSTALL')) {
                    define($define, self::$cache[$define]); // @codeCoverageIgnore
                }
            }
        }

        return self::$cache[$define];
    }

    /**
     * Helper: set value
     *
     * @since  4.4.0    Add return type
     * @since  4.3.2.2
     *
     * @param  string   $define     Constant name
     * @param  mixed    $value      Value to set
     * @param  array    $settings   Premium: settings to use
     * @return bool
     */
    public static function set(string $define, $value, array &$settings = null): bool
    {
        return self::$instance->setter($define, $value, $settings);
    }

    /**
     * Helper: set value
     *
     * @since  4.4.0    Add return type
     * @since  4.3.2.2
     *
     * @param  string   $define     Constant name
     * @param  mixed    $value      Value to set
     * @param  array    $settings   Premium: settings to use
     * @return bool
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setter(string $define, $value, array &$settings = null): bool
    {
        return true;
    }

    /**
     * Helper: get description
     *
     * @since  4.4.0    Add type hint, return type
     * @since  4.3.0
     *
     * @param  string       $define Constant name.
     * @return string|null  Description.
     */
    public static function desc(string $define): ?string
    {
        return self::$instance->getDesc($define);
    }

    /**
     * Helper: get description extended
     *
     * @since  5.1.0
     *
     * @param  string       $define Constant name.
     * @return string|null  Description.
     */
    public static function desc_ex(string $define): ?string
    {
        return self::$instance->getDescEx($define);
    }

    /**
     * Helper: get description
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @param  string       $define     Constant name.
     * @return string|null  Description.
     */
    public function getDesc(string $define): ?string
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
                return __('Trusted IPv4 and IPv6 list.', 'wp-fail2ban');
            default:
                return apply_filters(__METHOD__, null, $define);
        }
    }

    /**
     * Helper: get description extended
     *
     * @since  5.1.0
     *
     * @param  string       $define     Constant name.
     * @return string|null  Description.
     */
    public function getDescEx(string $define): ?string
    {
        switch ($define) {
            default:
                return apply_filters(__METHOD__, null, $define);
        }
    }
}

