<?php
/**
 * Spam comments
 *
 * @package wp-fail2ban
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban\feature;

use function org\lecklider\charles\wordpress\wp_fail2ban\openlog;
use function org\lecklider\charles\wordpress\wp_fail2ban\syslog;
use function org\lecklider\charles\wordpress\wp_fail2ban\closelog;

defined('ABSPATH') or exit;

/**
 * Catch comments marked as spam
 *
 * @since 3.5.0
 *
 * @param int       $comment_id
 * @param string    $comment_status
 *
 * @wp-f2b-hard Spam comment \d+
 */
function log_spam_comment($comment_id, $comment_status)
{
    if ('spam' === $comment_status) {
        if (is_null($comment = get_comment($comment_id, ARRAY_A))) {
            /**
             * @todo: decide what to do about this
             */
        } else {
            $remote_addr = (empty($comment['comment_author_IP']))
                ? 'unknown' // @codeCoverageIgnore
                : $comment['comment_author_IP'];

            if (openlog('WP_FAIL2BAN_SPAM_LOG')) {
                syslog(LOG_NOTICE, "Spam comment {$comment_id}", $remote_addr);
                closelog();
            }

            do_action(__FUNCTION__, $comment_id, $comment_status);
        }
    }
}

