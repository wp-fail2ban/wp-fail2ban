<?php declare(strict_types=1);
/**
 * User enumeration
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban\feature;

use          org\lecklider\charles\wordpress\wp_fail2ban\Syslog;

use function org\lecklider\charles\wordpress\wp_fail2ban\array_value;
use function org\lecklider\charles\wordpress\wp_fail2ban\bail;

defined('ABSPATH') or exit;

/**
 * Common enumeration handling
 *
 * @since  4.4.0    Add return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.3.0    Remove JSON support
 * @since  4.1.0    Add JSON support
 * @since  4.0.0
 *
 * @return bool
 *
 * @codeCoverageIgnore
 *
 * @wp-f2b-hard Blocked user enumeration attempt
 */
function _log_bail_user_enum(): bool
{
    Syslog::single(LOG_NOTICE, 'Blocked user enumeration attempt');

    do_action(__FUNCTION__);

    return bail();
}

/**
 * Catch traditional user enum
 *
 * @see \WP::parse_request()
 *
 * @since  4.4.0    Add type hint
 * @since  4.3.2.1  Backport from 4.3.4.0
 * @since  4.3.0.6  Ignore `author` if it's the current user
 * @since  4.3.0    Refactored to make XDebug happy; h/t @dinghy
 *                  Changed cap to 'edit_others_posts'
 * @since  3.5.0    Refactored for unit testing
 * @since  2.1.0
 *
 * @param \WP   $query
 *
 * @return \WP|bool
 */
function parse_request(\WP $query)
{
    /**
     * Is there an author in the query?
     */
    if (is_null($author = array_value('author', $query->query_vars))) {
        return $query;

    /**
     * Are they asking about themselves?
     */
    } elseif (get_current_user_id() == intval($author)) {
        return $query;

    /**
     * Does the user have enough privileges this doesn't matter?
     */
    } elseif (current_user_can('edit_others_posts')) {
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
 * @since  4.4.0    Add return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.3.0    Change to 'edit_others_posts'
 * @since  4.0.0
 *
 * @param  array            $prepared_args
 * @param  \WP_REST_Request $request
 *
 * @return array|\WP_Error
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function rest_user_query(array $prepared_args, \WP_REST_Request $request)
{
    /**
     * ClassicPress and pre-WP 5.4: this is all that's needed
     */
    if (current_user_can('edit_others_posts')) {
        return $prepared_args;

    /**
     * >= 5.x WordPress tries to pre-load the list of Authors,
     * regardless of the current user's role or capabilities.
     *
     * Returning 403 seems not to break anything, but we don't
     * want to trigger fail2ban.
     */
    } elseif (is_user_logged_in() &&
              array_key_exists('who', $prepared_args) &&
              'authors' == $prepared_args['who'])
    {
        Syslog::single(LOG_DEBUG, 'Blocked authors enumeration');

        return bail();

    /**
     * TODO: is there some other esoteric case to handle?
     */
    } else {
        // noop
    }

    return _log_bail_user_enum();
}

/**
 * Catch oembed user info
 *
 * @see \get_oembed_response_data()
 *
 * @since  4.4.0    Add type hints, return type
 * @since  4.2.7
 *
 * @param  array    $data   The response data.
 * @param  WP_Post  $post   The post object.
 * @param  int      $width  The requested width.
 * @param  int      $height The calculated height.
 *
 * @return array
 *
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function oembed_response_data(array $data, \WP_Post $post, int $width, int $height): array
{
    unset($data['author_name']);
    unset($data['author_url']);

    return $data;
}

/**
 * Return empty user sitemap
 *
 * Bots can guess the sitemap name so return an empty list.
 *
 * @since  5.0.0
 *
 * @param  $null    null
 * @param  int      $page_num
 *
 * @return array    Empty array
 *
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function wp_sitemaps_users_pre_url_list($null, int $page_num): array
{
    return [];
}

/**
 * Return zero pages
 *
 * This removes the list of users from the sitemap to make search engines happier.
 *
 * @since  5.0.0
 *
 * @param  $null    null
 *
 * @return int      0
 *
 * @codeCoverageIgnore
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function wp_sitemaps_users_pre_max_num_pages($null): int
{
    return 0;
}

/**
 * Drop users sitemap provider
 *
 * @since  5.0.0
 *
 * @param  WP_Sitemaps_Provider $provider Instance of a WP_Sitemaps_Provider.
 * @param  string               $name     Name of the sitemap provider.
 *
 * @return bool false
 */
function wp_sitemaps_add_provider($provider, $name)
{
    return ('users' == $name)
        ? false
        : $provider;
}

