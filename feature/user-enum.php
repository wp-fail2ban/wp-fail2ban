<?php
/**
 * User enumeration
 *
 * @package wp-fail2ban
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban\feature;

use function org\lecklider\charles\wordpress\wp_fail2ban\array_value;
use function org\lecklider\charles\wordpress\wp_fail2ban\bail;
use function org\lecklider\charles\wordpress\wp_fail2ban\openlog;
use function org\lecklider\charles\wordpress\wp_fail2ban\syslog;
use function org\lecklider\charles\wordpress\wp_fail2ban\closelog;

defined('ABSPATH') or exit;

/**
 * Common enumeration handling
 *
 * @since 4.3.0 Remove JSON support
 * @since 4.1.0 Add JSON support
 * @since 4.0.0
 *
 * @param bool  $is_json
 *
 * @return \WP_Error
 *
 * @wp-f2b-hard Blocked user enumeration attempt
 */
function _log_bail_user_enum()
{
    if (openlog()) {
        syslog(LOG_NOTICE, 'Blocked user enumeration attempt');
        closelog();
    }

    do_action(__FUNCTION__);

    return bail();
}

/**
 * Catch traditional user enum
 *
 * @see \WP::parse_request()
 *
 * @since 4.3.0.9   Backport from 4.3.4.0
 * @since 4.3.0.6   Ignore `author` if it's the current user
 * @since 4.3.0     Refactored to make XDebug happy; h/t @dinghy
 *                  Changed cap to 'edit_others_posts'
 * @since 3.5.0     Refactored for unit testing
 * @since 2.1.0
 *
 * @param \WP   $query
 *
 * @return \WP
 */
function parse_request($query)
{
    /**
     * Is there an author in the query?
     */
    if (is_null($author = array_value('author', $query->query_vars))) {
        return $query;

    /**
     * Does the user have enough privileges this doesn't matter?
     */
    } elseif (current_user_can('edit_others_posts')) {
        return $query;

    /**
     * Are they asking about themselves?
     */
    } elseif (get_current_user_id() == intval($author)) {
        return $query;

    /**
     * OK, we have an unprivileged user asking about a different user
     */
    } else {
        global $pagenow;

        /**
         * `edit.php` allows Contributors to list posts by other users
         */
        if (is_admin() && 'edit.php' == $pagenow && current_user_can('edit_posts')) {
            return $query;

        /**
         * TODO: is there some other esoteric case to handle?
         */
        } else {
            // noop
        }
    }

    return _log_bail_user_enum();
}

/**
 * Catch RESTful user list
 *
 * @see \WP_REST_Users_Controller::get_items()
 *
 * @since 4.3.0 Change to 'edit_others_posts'
 * @since 4.0.0
 *
 * @param array             $prepared_args
 * @param \WP_REST_Request  $request
 *
 * @return array|\WP_Error
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function rest_user_query($prepared_args, $request)
{
    /**
     * ClassicPress and pre-WP 5.4: this is all that's needed
     */
    if (current_user_can('edit_others_posts')) {
        return $prepared_args;
    }

    /**
     * ClassicPress or pre-5.4 Wordpress - bail
     */
    if (function_exists('classicpress_version') ||
        version_compare(get_bloginfo('version'), '5.4', '<'))
    {
        return _log_bail_user_enum();
    }

    /**
     * >= 5.x WordPress tries to pre-load the list of Authors,
     * regardless of the current user's role or capabilities.
     *
     * Returning 403 seems not to break anything, but we don't
     * want to trigger fail2ban.
     */
    if (is_user_logged_in() &&
        array_key_exists('who', $prepared_args) &&
        'authors' == $prepared_args['who'])
    {
        if (openlog()) {
            syslog(LOG_DEBUG, 'Blocked authors enumeration');
            closelog();
        }

        return bail();
    }

    return _log_bail_user_enum();
}

/**
 * Catch oembed user info
 *
 * @see \get_oembed_response_data()
 *
 * @since 4.2.7
 *
 * @param array   $data   The response data.
 * @param WP_Post $post   The post object.
 * @param int     $width  The requested width.
 * @param int     $height The calculated height.
 *
 * @return array
 *
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function oembed_response_data($data, $post, $width, $height)
{
    unset($data['author_name']);
    unset($data['author_url']);

    return $data;
}

