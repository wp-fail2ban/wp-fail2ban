<?php
/**
 * WP fail2ban features
 *
 * @package wp-fail2ban
 * @since   4.0.0
 * @php     5.3
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Hook: plugins_loaded
 *
 * Run slightly earlier than the main hook
 *
 * @since 4.3.0
 *
 * @return void
 */
function plugins_loaded__early()
{
    Config::load();
}
add_action('plugins_loaded', __NAMESPACE__.'\plugins_loaded__early', 9);

/**
 * Load all enabled features.
 *
 * @since 4.3.0
 *
 * @return void
 */
function plugins_loaded()
{
    /**
     * Core
     *
     * @since 4.3.0
     */
    add_action('authenticate', __NAMESPACE__.'\core\authenticate', 1, 3);
    add_action('wp_login', __NAMESPACE__.'\core\wp_login', 10, 2);
    add_action('wp_login_failed', __NAMESPACE__.'\core\wp_login_failed');

    /**
     * Comments
     *
     * @since 4.0.0     Refactored
     * @since 3.5.0
     */
    if (defined('WP_FAIL2BAN_LOG_COMMENTS') && true === WP_FAIL2BAN_LOG_COMMENTS) {
        add_filter('notify_post_author', __NAMESPACE__.'\feature\notify_post_author', 10, 2);

        if (defined('WP_FAIL2BAN_LOG_COMMENTS_EXTRA')) {
            if (WP_FAIL2BAN_LOG_COMMENTS_EXTRA & WPF2B_EVENT_COMMENT_NOT_FOUND) {
                add_action('comment_id_not_found', __NAMESPACE__.'\feature\comment_id_not_found');
            }
            if (WP_FAIL2BAN_LOG_COMMENTS_EXTRA & WPF2B_EVENT_COMMENT_CLOSED) {
                add_action('comment_closed', __NAMESPACE__.'\feature\comment_closed');
            }
            if (WP_FAIL2BAN_LOG_COMMENTS_EXTRA & WPF2B_EVENT_COMMENT_TRASH) {
                add_action('comment_on_trash', __NAMESPACE__.'\feature\comment_on_trash');
            }
            if (WP_FAIL2BAN_LOG_COMMENTS_EXTRA & WPF2B_EVENT_COMMENT_DRAFT) {
                add_action('comment_on_draft', __NAMESPACE__.'\feature\comment_on_draft');
            }
            if (WP_FAIL2BAN_LOG_COMMENTS_EXTRA & WPF2B_EVENT_COMMENT_PASSWORD) {
                add_action('comment_on_password_protected', __NAMESPACE__.'\feature\comment_on_password_protected');
            }
        }
    }

    /**
     * Password
     *
     * @since 4.0.0     Refactored
     * @since 3.5.0
     */
    if (defined('WP_FAIL2BAN_LOG_PASSWORD_REQUEST') && true === WP_FAIL2BAN_LOG_PASSWORD_REQUEST) {
        add_action('retrieve_password', __NAMESPACE__.'\feature\retrieve_password');
    }


    /**
     * Spam
     *
     * @since 4.0.0     Refactored
     * @since 3.5.0
     */
    if (defined('WP_FAIL2BAN_LOG_SPAM') && true === WP_FAIL2BAN_LOG_SPAM) {
        add_action('comment_post', __NAMESPACE__.'\feature\log_spam_comment', 10, 2);
        add_action('wp_set_comment_status', __NAMESPACE__.'\feature\log_spam_comment', 10, 2);
    }

    /**
     * User enumeration
     *
     * @since 4.0.0     Refactored
     * @since 2.1.0
     */
    if (defined('WP_FAIL2BAN_BLOCK_USER_ENUMERATION') && true === WP_FAIL2BAN_BLOCK_USER_ENUMERATION) {
        add_filter('parse_request', __NAMESPACE__.'\feature\parse_request', 1);
        add_filter('rest_user_query', __NAMESPACE__.'\feature\rest_user_query', 10, 2);
        add_filter('oembed_response_data', __NAMESPACE__.'\feature\oembed_response_data', PHP_INT_MAX-1, 4);
    }

    /**
     * Users
     *
     * @since 4.3.0 Better test
     * @since 4.0.0     Refactored
     * @since 2.0.0
     */
    if ((defined('WP_FAIL2BAN_BLOCKED_USERS') && WP_FAIL2BAN_BLOCKED_USERS) ||
        (defined('WP_FAIL2BAN_BLOCK_USERNAME_LOGIN') && WP_FAIL2BAN_BLOCK_USERNAME_LOGIN))
    {
        add_filter('authenticate', __NAMESPACE__.'\feature\block_users', 1, 3);
    }

    /**
     * Set up for plugins
     *
     * @since 4.3.0
     */
    add_action('wp_fail2ban_register_plugin', __NAMESPACE__.'\feature\register_plugin', 1, 2);
    add_action('wp_fail2ban_register_message', __NAMESPACE__.'\feature\register_message', 1, 2);
    add_action('wp_fail2ban_log_message', __NAMESPACE__.'\feature\log_message', 1, 3);
}
/**
 * Load nice and early.
 *
 * @since 4.3.0
 */
add_action('plugins_loaded', __NAMESPACE__.'\plugins_loaded');

/**
 * Things we need a current user for.
 *
 * @since 4.3.0
 */
function init()
{
    /**
     * @since 4.3.0 Check for logged in
     * @since 4.2.5 Check for admin
     */
    if (!is_user_logged_in()) {
        /**
         * XML-RPC
         *
         * @since 4.0.0     Refactored
         * @since 3.0.0
         */
        if (defined('XMLRPC_REQUEST') && true === XMLRPC_REQUEST) {
            add_action('xmlrpc_login_error', __NAMESPACE__.'\feature\xmlrpc_login_error', 10, 2);
            add_filter('xmlrpc_pingback_error', __NAMESPACE__.'\feature\xmlrpc_pingback_error', 5);

            /**
             * @since 4.3.0 Refactored
             * @since 4.0.0 Refactored
             * @since 3.6.0
             */
            if (defined('WP_FAIL2BAN_XMLRPC_LOG') && '' < WP_FAIL2BAN_XMLRPC_LOG) {
                xmlrpc_log(); // @codeCoverageIgnore
            }
            /**
             * @since 4.3.0 Refactored
             * @since 4.0.0 Refactored
             * @since 2.2.0
             */
            if (defined('WP_FAIL2BAN_LOG_PINGBACKS') && true === WP_FAIL2BAN_LOG_PINGBACKS) {
                add_action('xmlrpc_call', __NAMESPACE__.'\feature\xmlrpc_call');
            }
        }
    }

    /**
     * @since 4.3.0 Relocate.
     * @since 4.2.5
     */
    if (defined('WP_ADMIN') && WP_ADMIN) {
        require_once __DIR__.'/admin/admin.php'; // @codeCoverageIgnore
    }
}
add_action('init', __NAMESPACE__.'\init');

/**
 * Init as late as possible.
 *
 * @since 4.3.0.4
 *
 * @return void
 */
function init__late()
{
    /**
     * Let other plugins register their messages
     *
     * @since 4.3.0
     */
    global $wp_fail2ban;
    $wp_fail2ban['plugins'] = [];

    do_action('wp_fail2ban_register');
}
add_action('init', __NAMESPACE__.'\init__late', PHP_INT_MAX);

