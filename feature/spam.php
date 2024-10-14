<?php declare(strict_types=1);
/**
 * Spam comments
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban\feature;

use       org\lecklider\charles\wordpress\wp_fail2ban\Syslog;

defined('ABSPATH') or exit;

/**
 * Catch comments marked as spam
 *
 * @since  4.4.0    Add type hints, return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  3.5.0
 *
 * @param  int      $comment_id
 * @param  mixed    $comment_status
 *
 * @return void
 *
 * @wp-f2b-hard Spam comment \d+
 */
function log_spam_comment(int $comment_id, $comment_status): void
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

            Syslog::single(LOG_NOTICE, "Spam comment {$comment_id}", 'WP_FAIL2BAN_SPAM_LOG', $remote_addr);

            do_action(__FUNCTION__, $comment_id, $comment_status);
        }
    }
}

