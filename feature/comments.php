<?php
/**
 * Comment logging
 *
 * @package wp-fail2ban
 * @since 4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban\feature;

use function org\lecklider\charles\wordpress\wp_fail2ban\openlog;
use function org\lecklider\charles\wordpress\wp_fail2ban\syslog;
use function org\lecklider\charles\wordpress\wp_fail2ban\closelog;

defined('ABSPATH') or exit;

/**
 * Log new comment
 *
 * @since 3.5.0
 *
 * @param bool $maybe_notify
 * @param int  $comment_ID
 *
 * @return bool
 *
 * @wp-f2b-extra Comment \d+
 */
function notify_post_author($maybe_notify, $comment_ID)
{
    if (openlog('WP_FAIL2BAN_COMMENT_LOG')) {
        syslog(LOG_INFO, "Comment {$comment_ID}");
        closelog();
    }

    do_action(__FUNCTION__, $maybe_notify, $comment_ID);

    return $maybe_notify;
}

/**
 * Log attempted comment on non-existent post
 *
 * @since 4.0.0
 *
 * @param int $comment_post_ID
 *
 * @wp-f2b-extra Comment post not found \d+
 */
function comment_id_not_found($comment_post_ID)
{
    if (openlog('WP_FAIL2BAN_COMMENT_EXTRA_LOG')) {
        syslog(LOG_NOTICE, "Comment post not found {$comment_post_ID}");
        closelog();
    }

    do_action(__FUNCTION__, $comment_post_ID);
}

/**
 * Log attempted comment on closed post
 *
 * @since 4.0.0
 *
 * @param int $comment_post_ID
 *
 * @wp-f2b-extra Comments closed on post \d+
 */
function comment_closed($comment_post_ID)
{
    if (openlog('WP_FAIL2BAN_COMMENT_EXTRA_LOG')) {
        syslog(LOG_NOTICE, "Comments closed on post {$comment_post_ID}");
        closelog();
    }

    do_action(__FUNCTION__, $comment_post_ID);
}

/**
 * Log attempted comment on trashed post
 *
 * @since 4.0.2 Fix message
 * @since 4.0.0
 *
 * @param int $comment_post_ID
 *
 * @wp-f2b-extra Comment attempt on trash post \d+
 */
function comment_on_trash($comment_post_ID)
{
    if (openlog('WP_FAIL2BAN_COMMENT_EXTRA_LOG')) {
        syslog(LOG_NOTICE, "Comment attempt on trash post {$comment_post_ID}");
        closelog();
    }

    do_action(__FUNCTION__, $comment_post_ID);
}

/**
 * Log attempted comment on draft post
 *
 * @since 4.0.2 Fix message
 * @since 4.0.0
 *
 * @param int $comment_post_ID
 *
 * @wp-f2b-extra Comment attempt on draft post \d+
 */
function comment_on_draft($comment_post_ID)
{
    if (openlog('WP_FAIL2BAN_COMMENT_EXTRA_LOG')) {
        syslog(LOG_NOTICE, "Comment attempt on draft post {$comment_post_ID}");
        closelog();
    }

    do_action(__FUNCTION__, $comment_post_ID);
}

/**
 * Log attempted comment on password-protected post
 *
 * @since 4.0.2 Fix message
 * @since 4.0.0
 *
 * @param int $comment_post_ID
 *
 * @wp-f2b-extra Comment attempt on password-protected post \d+
 */
function comment_on_password_protected($comment_post_ID)
{
    if (openlog('WP_FAIL2BAN_COMMENT_EXTRA_LOG')) {
        syslog(LOG_NOTICE, "Comment attempt on password-protected post {$comment_post_ID}");
        closelog();
    }

    do_action(__FUNCTION__, $comment_post_ID);
}

