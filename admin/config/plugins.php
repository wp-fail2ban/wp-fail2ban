<?php declare(strict_types=1);
/**
 * Settings - Plugins
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.2.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Tab: Plugins
 *
 * @since 4.2.0
 */
class TabPlugins extends TabLoggingBase
{
    /**
     * Settings page slug
     *
     * @since 4.3.2.1
     */
    const SETTINGS_PAGE = 'wp-fail2ban-plugins';

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct('plugins', __('Plugins', 'wp-fail2ban'));
    }

    /**
     * {@inheritDoc}
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function admin_init(): void
    {
        do_action(__METHOD__.'.before');

        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        add_settings_section('wp-fail2ban-plugins', __('Event Class Facilities', 'wp-fail2ban'), [$this, 'sectionLoggingEventClasses'], self::SETTINGS_PAGE);
        add_settings_field('plugins-log-auth',      __('Authentication',         'wp-fail2ban'), [$this, 'auth'],                       self::SETTINGS_PAGE, 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-block',     __('Block',                  'wp-fail2ban'), [$this, 'block'],                      self::SETTINGS_PAGE, 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-comment',   __('Comment',                'wp-fail2ban'), [$this, 'comment'],                    self::SETTINGS_PAGE, 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-password',  __('Password',               'wp-fail2ban'), [$this, 'password'],                   self::SETTINGS_PAGE, 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-rest',      __('REST',                   'wp-fail2ban'), [$this, 'rest'],                       self::SETTINGS_PAGE, 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-spam',      __('Spam',                   'wp-fail2ban'), [$this, 'spam'],                       self::SETTINGS_PAGE, 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-xmlrpc',    __('XML‑RPC',                'wp-fail2ban'), [$this, 'xmlrpc'],                     self::SETTINGS_PAGE, 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-other',     __('Other',                  'wp-fail2ban'), [$this, 'other'],                      self::SETTINGS_PAGE, 'wp-fail2ban-plugins');
        // phpcs:enable

        do_action(__METHOD__.'.after');
    }

    /**
     * {@inheritDoc}
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @return void
     */
    public function current_screen(): void
    {
    }

    /**
     * Section summary.
     *
     * @since  4.4.0    Add return type
     * @since  4.2.0
     *
     * @return void
     */
    public function sectionLoggingEventClasses(): void
    {
        echo __('Facilities to use for plugin-generated messages. The defaults follow the Core defaults.', 'wp-fail2ban');
    }

    /**
     * Auth
     *
     * @since  4.4.0    Add return type
     * @since  4.2.0
     *
     * @return void
     */
    public function auth(): void
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_AUTH', 'WP_FAIL2BAN_PLUGIN_AUTH_LOG');
    }

    /**
     * Block
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0.9  Backport from 4.3.4.0
     *
     * @return void
     */
    public function block(): void
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_BLOCK', 'WP_FAIL2BAN_PLUGIN_BLOCK_LOG');
    }

    /**
     * Comment
     *
     * @since  4.4.0    Add return type
     * @since  4.2.0
     *
     * @return void
     */
    public function comment(): void
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_COMMENT', 'WP_FAIL2BAN_PLUGIN_COMMENT_LOG');
    }

    /**
     * Password
     *
     * @since  4.4.0    Add return type
     * @since  4.2.0
     *
     * @return void
     */
    public function password(): void
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_PASSWORD', 'WP_FAIL2BAN_PLUGIN_PASSWORD_LOG');
    }

    /**
     * REST
     *
     * @since  4.4.0    Add return type
     * @since  4.2.0
     *
     * @return void
     */
    public function rest(): void
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_REST', 'WP_FAIL2BAN_PLUGIN_REST_LOG');
    }

    /**
     * Spam
     *
     * @since  4.4.0    Add return type
     * @since  4.2.0
     *
     * @return void
     */
    public function spam(): void
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_SPAM', 'WP_FAIL2BAN_PLUGIN_SPAM_LOG');
    }

    /**
     * XML-RPC
     *
     * @since  4.4.0    Add return type
     * @since  4.2.0
     *
     * @return void
     */
    public function xmlrpc(): void
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_XMLRPC', 'WP_FAIL2BAN_PLUGIN_XMLRPC_LOG');
    }

    /**
     * Other
     *
     * @since  4.4.0
     *
     * @return void
     */
    public function other(): void
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_OTHER', 'WP_FAIL2BAN_PLUGIN_OTHER_LOG');
    }
}

