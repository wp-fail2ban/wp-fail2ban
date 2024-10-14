<?php declare(strict_types=1);
/**
 * Comment logging
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 *
 * @wp-f2b-soft Comment attempt on .* post \d+
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban\feature;

use       org\lecklider\charles\wordpress\wp_fail2ban\Config;
use       org\lecklider\charles\wordpress\wp_fail2ban\Syslog;

defined('ABSPATH') or exit;

/**
 * Log new comment
 *
 * @since  4.4.0    Add type hints, return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  3.5.0
 *
 * @param  bool $maybe_notify
 * @param  int  $comment_ID
 *
 * @return bool
 *
 * @wp-f2b-extra Comment \d+
 */
function notify_post_author(bool $maybe_notify, int $comment_ID): bool
{
    Syslog::single(LOG_INFO, "Comment {$comment_ID}", 'WP_FAIL2BAN_COMMENT_LOG');

    do_action(__FUNCTION__, $maybe_notify, $comment_ID);

    return $maybe_notify;
}

/**
 * Attempted/extra log helper
 *
 * @since  5.0.0
 *
 * @return string
 */
function _get_comment_attempt_log(): string
{
    // New setting defined, use new log
    if (true === Config::get('WP_FAIL2BAN_LOG_COMMENT_ATTEMPTS')) {
        return 'WP_FAIL2BAN_COMMENT_ATTEMPT_LOG';

    } elseif (Config::get('WP_FAIL2BAN_LOG_COMMENTS_EXTRA')) {
        return 'WP_FAIL2BAN_COMMENT_EXTRA_LOG';

    } else {
        return 'WP_FAIL2BAN_COMMENT_ATTEMPT_LOG';
    }
}

/**
 * Log attempted comment on non-existent post
 *
 * @since  5.0.0    Use attempted/extra log helper
 * @since  4.4.0    Add type hint, return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.0.0
 *
 * @param  int  $comment_post_ID
 *
 * @return void
 */
function comment_id_not_found(int $comment_post_ID): void
{
    Syslog::single(LOG_NOTICE, "Comment attempt on non-existent post {$comment_post_ID}", _get_comment_attempt_log());

    do_action(__FUNCTION__, $comment_post_ID);
}

/**
 * Log attempted comment on closed post
 *
 * @since  5.0.0    Use attempted/extra log helper
 * @since  4.4.0    Add type hint, return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.0.0
 *
 * @param  int  $comment_post_ID
 *
 * @return void
 */
function comment_closed(int $comment_post_ID): void
{
    Syslog::single(LOG_NOTICE, "Comment attempt on closed post {$comment_post_ID}", _get_comment_attempt_log());

    do_action(__FUNCTION__, $comment_post_ID);
}

/**
 * Log attempted comment on trashed post
 *
 * @since  5.0.0    Use attempted/extra log helper
 * @since  4.4.0    Add type hint, return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.0.2    Fix message
 * @since  4.0.0
 *
 * @param  int  $comment_post_ID
 *
 * @return void
 */
function comment_on_trash(int $comment_post_ID): void
{
    Syslog::single(LOG_NOTICE, "Comment attempt on trashed post {$comment_post_ID}", _get_comment_attempt_log());

    do_action(__FUNCTION__, $comment_post_ID);
}

/**
 * Log attempted comment on draft post
 *
 * @since  5.0.0    Use attempted/extra log helper
 * @since  4.4.0    Add type hint, return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.0.2    Fix message
 * @since  4.0.0
 *
 * @param  int $comment_post_ID
 *
 * @return void
 */
function comment_on_draft(int $comment_post_ID): void
{
    Syslog::single(LOG_NOTICE, "Comment attempt on draft post {$comment_post_ID}", _get_comment_attempt_log());

    do_action(__FUNCTION__, $comment_post_ID);
}

/**
 * Log attempted comment on password-protected post
 *
 * @since  5.0.0    Use attempted/extra log helper
 * @since  4.4.0    Add type hint, return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.0.2    Fix message
 * @since  4.0.0
 *
 * @param  int $comment_post_ID
 *
 * @return void
 */
function comment_on_password_protected(int $comment_post_ID): void
{
    Syslog::single(LOG_NOTICE, "Comment attempt on password-protected post {$comment_post_ID}", _get_comment_attempt_log());

    do_action(__FUNCTION__, $comment_post_ID);
}

