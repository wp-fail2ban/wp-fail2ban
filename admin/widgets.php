<?php declare(strict_types=1);
/**
 * Dashboard Widgets
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.3.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

if (!defined('ABSPATH') || (defined('WP_FAIL2BAN_DISABLE_LAST_LOG') && false === WP_FAIL2BAN_DISABLE_LAST_LOG)) {
    return;
}

/**
 * Last 5 Messages Widget
 *
 * @since  4.4.0    Add return type
 * @since  4.3.0
 *
 * @return void
 */
function dashboard_widget_last_messages(): void
{
    $messages = dashboard_widget_last_messages_data();

    printf(
        '<table><thead><tr><th class="dt">%s</th><th>%s</th><th>%s</th></tr></thead><tbody>',
        __('Date/Time', 'wp-fail2ban'),
        __('Priority', 'wp-fail2ban'),
        __('Message', 'wp-fail2ban')
    );
    if (count($messages)) {
        $alt = true;
        foreach ($messages as $message) {
            printf(
                '<tr class="%s"><td class="dt"><nobr><tt>%s&nbsp;Z</tt></nobr></td><td class="priority-%s">%s</td><td>%s</td></tr>',
                $alt ? 'alternate' : '',
                $message['dt'],
                strtolower($message['lvl']),
                $message['lvl'],
                $message['msg']
            );
            $alt = !$alt;
        }

    } else {
        printf('<tr><td colspan="3"><em>%s</em></td></tr>', __('No messages found.', 'wp-fail2ban'));
    }
    echo '</tbody>';

    if (null === ($tfoot = apply_filters(__METHOD__.'::tfoot', null))) {
        $dismiss = 'wp-fail2ban-'.WP_FAIL2BAN_VER2.'-last-5-messages-upgrade';
        if (array_key_exists($dismiss, $_GET)) {
            update_site_option($dismiss, intval($_GET[$dismiss]));
        }
        if (get_site_option($dismiss, 1) ||
            array_key_exists($dismiss.'-debug', $_GET))
        {
            $tfoot .= sprintf(
                '<tr><td colspan="3"><a href="%s">%s</a><div class="dismiss"><a href="%s" style="text-decoration: none;" title="%s">%s</a></div></td></tr>',
                network_admin_url('admin.php?page=wp-fail2ban-menu-pricing'),
                __('Upgrade for a full log of new events', 'wp-fail2ban'),
                network_admin_url("?{$dismiss}=0"),
                'Dismiss',
                '<span class="dashicons dashicons-no-alt"></span>'
            );
        }
    }
    echo "<tfoot>{$tfoot}</tfoot>";

    echo '</table>';
}

/**
 * Helper: get last messages data
 *
 * @since  4.4.0    Add return type
 * @since  4.3.4.0
 *
 * @return array
 */
function dashboard_widget_last_messages_data(): array
{
    $data = [];

    if (!is_array($messages = get_site_option('wp-fail2ban-messages', []))) {
        $messages = [];
    }
    foreach ($messages as $message) {
        $data[] = [
            'dt'  => str_replace(' ', '&nbsp;<wbr>', $message['dt']),
            'lvl' => $message['lvl'],
            'msg' => str_replace('&colon;', '&colon;<wbr>', htmlentities($message['msg'], ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8'))
        ];
    }

    return $data;
}

/**
 * Hook: heartbeat_received
 *
 * @since  5.0.0    Remove return type and $response type to handle other broken plugins
 * @since  4.4.0    Add return type
 * @since  4.3.4.0
 *
 * @param  array    $response
 * @param  array    $data
 *
 * @return array
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function heartbeat_received($response, array $data, string $screen_id)
{
    if (isset($data['wp_fail2ban']) && 'wp_fail2ban_last_messages' == $data['wp_fail2ban']) {
        $response['wp-fail2ban-last-messages'] = dashboard_widget_last_messages_data();
    }
    return $response;
}
add_filter('heartbeat_received', __NAMESPACE__.'\heartbeat_received', 10, 3);

/**
 * wp_dashboard_setup action hook
 *
 * @since  4.4.0    Add return type
 * @since  4.3.0
 *
 * @return void
 *
 * @see https://codex.wordpress.org/Function_Reference/wp_add_dashboard_widget
 */
function wp_dashboard_setup(): void
{
    if ((!is_multisite() && current_user_can('manage_options')) ||
        (is_network_admin() && current_user_can('manage_network_options')))
    {
        wp_add_dashboard_widget(
            'wp_fail2ban_last_messages',
            __('<span>[<i>WPf2b</i>] Last 5 Messages</span>', 'wp-fail2ban'),
            __NAMESPACE__.'\dashboard_widget_last_messages'
        );
        wp_enqueue_style('wp-fail2ban-last-messages', plugins_url('css/widgets.css', __FILE__));
        wp_enqueue_script('wp-fail2ban-last-messages', plugins_url('js/widgets.js', __FILE__), ['jquery']);
    }
}
add_action('wp_dashboard_setup', __NAMESPACE__.'\wp_dashboard_setup');
add_action('wp_network_dashboard_setup', __NAMESPACE__.'\wp_dashboard_setup');

