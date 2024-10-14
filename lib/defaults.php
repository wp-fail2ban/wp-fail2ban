<?php
/**
 * Defaults
 *
 * @package wp-fail2ban
 * @since   4.3.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Define default values
 *
 * @since 4.3.0 Refactored
 *
 * @return void
 */
function init_defaults()
{
    // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
    /**
     * Defaults
     *
     * @since 4.0.0
     */
    define('DEFAULT_WP_FAIL2BAN_OPENLOG_OPTIONS',       LOG_PID|LOG_NDELAY);
    define('DEFAULT_WP_FAIL2BAN_AUTH_LOG',              LOG_AUTH);
    define('DEFAULT_WP_FAIL2BAN_COMMENT_LOG',           LOG_USER);
    define('DEFAULT_WP_FAIL2BAN_PINGBACK_LOG',          LOG_USER);
    define('DEFAULT_WP_FAIL2BAN_PASSWORD_REQUEST_LOG',  LOG_USER);
    define('DEFAULT_WP_FAIL2BAN_SPAM_LOG',              LOG_AUTH);
    /**
     * @since 4.0.5
     */
    define('DEFAULT_WP_FAIL2BAN_COMMENT_EXTRA_LOG',     LOG_AUTH);
    define('DEFAULT_WP_FAIL2BAN_PINGBACK_ERROR_LOG',    LOG_AUTH);
    /**
     * @since 4.2.0
     */
    define('DEFAULT_WP_FAIL2BAN_PLUGIN_AUTH_LOG',       LOG_AUTH);
    define('DEFAULT_WP_FAIL2BAN_PLUGIN_BLOCK_LOG',      LOG_USER);
    define('DEFAULT_WP_FAIL2BAN_PLUGIN_COMMENT_LOG',    LOG_USER);
    define('DEFAULT_WP_FAIL2BAN_PLUGIN_OTHER_LOG',      LOG_USER);
    define('DEFAULT_WP_FAIL2BAN_PLUGIN_PASSWORD_LOG',   LOG_USER);
    define('DEFAULT_WP_FAIL2BAN_PLUGIN_REST_LOG',       LOG_USER);
    define('DEFAULT_WP_FAIL2BAN_PLUGIN_SPAM_LOG',       LOG_AUTH);
    define('DEFAULT_WP_FAIL2BAN_PLUGIN_XMLRPC_LOG',     LOG_USER);
    // phpcs:enable
}

