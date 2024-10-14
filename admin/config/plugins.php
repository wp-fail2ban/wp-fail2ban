<?php
/**
 * Settings - Plugins
 *
 * @package wp-fail2ban
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
     * {@inheritDoc}
     */
    public function __construct()
    {
        parent::__construct('plugins', __('Plugins', 'wp-fail2ban'));
    }

    /**
     * {@inheritDoc}
     *
     * @since 4.0.0
     */
    public function admin_init()
    {
        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        add_settings_section('wp-fail2ban-plugins', __('Event Class Facilities', 'wp-fail2ban'), [$this, 'sectionLoggingEventClasses'],      'wp-fail2ban-plugins');
        add_settings_field('plugins-log-auth',      __('Authentication',         'wp-fail2ban'), [$this, 'auth'],     'wp-fail2ban-plugins', 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-block',     __('Block',                  'wp-fail2ban'), [$this, 'block'],    'wp-fail2ban-plugins', 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-comment',   __('Comment',                'wp-fail2ban'), [$this, 'comment'],  'wp-fail2ban-plugins', 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-password',  __('Password',               'wp-fail2ban'), [$this, 'password'], 'wp-fail2ban-plugins', 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-rest',      __('REST',                   'wp-fail2ban'), [$this, 'rest'],     'wp-fail2ban-plugins', 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-spam',      __('Spam',                   'wp-fail2ban'), [$this, 'spam'],     'wp-fail2ban-plugins', 'wp-fail2ban-plugins');
        add_settings_field('plugins-log-xmlrpc',    __('XML-RPC',                'wp-fail2ban'), [$this, 'xmlrpc'],   'wp-fail2ban-plugins', 'wp-fail2ban-plugins');
        // phpcs:enable
    }

    /**
     * {@inheritDoc}
     *
     * @since 4.3.0
     */
    public function current_screen()
    {
    }

    /**
     * Section summary.
     *
     * @since 4.2.0
     */
    public function sectionLoggingEventClasses()
    {
        echo __('Facilities to use for plugin-generated messages. The defaults follow the Core defaults.', 'wp-fail2ban');
    }

    /**
     * Auth
     *
     * @since 4.2.0
     */
    public function auth()
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_AUTH', 'WP_FAIL2BAN_PLUGIN_AUTH_LOG');
    }

    /**
     * Block
     *
     * @since   4.3.0.9 Backport from 4.3.4.0
     */
    public function block()
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_BLOCK', 'WP_FAIL2BAN_PLUGIN_BLOCK_LOG');
    }

    /**
     * Comment
     *
     * @since 4.2.0
     */
    public function comment()
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_COMMENT', 'WP_FAIL2BAN_PLUGIN_COMMENT_LOG');
    }

    /**
     * Password
     *
     * @since 4.2.0
     */
    public function password()
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_PASSWORD', 'WP_FAIL2BAN_PLUGIN_PASSWORD_LOG');
    }

    /**
     * REST
     *
     * @since 4.2.0
     */
    public function rest()
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_REST', 'WP_FAIL2BAN_PLUGIN_REST_LOG');
    }

    /**
     * Spam
     *
     * @since 4.2.0
     */
    public function spam()
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_SPAM', 'WP_FAIL2BAN_PLUGIN_SPAM_LOG');
    }

    /**
     * XML-RPC
     *
     * @since 4.2.0
     */
    public function xmlrpc()
    {
        $this->log('WP_FAIL2BAN_PLUGIN_LOG_XMLRPC', 'WP_FAIL2BAN_PLUGIN_XMLRPC_LOG');
    }
}

