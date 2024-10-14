<?php
/**
 * Dashboard Widgets
 *
 * @package wp-fail2ban
 * @since   4.3.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

if (!defined('ABSPATH') || (defined('WP_FAIL2BAN_DISABLE_LAST_LOG') && false === WP_FAIL2BAN_DISABLE_LAST_LOG)) {
    return;
}

/**
 * Last 5 Messages Widget
 *
 * @since 4.3.0
 */
function dashboard_widget_last_messages()
{
    if (!is_array($messages = get_site_option('wp-fail2ban-messages', []))) {
        $messages = [];
    }

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
                str_replace(' ', '&nbsp;<wbr>', $message['dt']),
                strtolower($message['lvl']),
                $message['lvl'],
                htmlentities($message['msg'], ENT_SUBSTITUTE|ENT_HTML5, 'UTF-8')
            );
            $alt = !$alt;
        }

    } else {
        printf('<tr><td colspan="3"><em>%s</em></td></tr>', __('No messages found.', 'wp-fail2ban'));
    }
    echo '</tbody>';

    if (null === ($tfoot = apply_filters(__METHOD__.'::tfoot', null))) {
        $dismiss = 'wp-fail2ban-'.WP_FAIL2BAN_VER_SHORT.'-last-5-messages-upgrade';
        if (array_key_exists($dismiss, $_GET)) {
            update_site_option($dismiss, intval($_GET[$dismiss]));
        }
        if (get_site_option($dismiss, 1) ||
            array_key_exists($dismiss.'-debug', $_GET))
        {
            $tfoot .= sprintf(
                '<tr><td colspan="3"><a href="%s">%s</a><div class="dismiss"><a href="%s">%s</a></div></td></tr>',
                network_admin_url('admin.php?page=wp-fail2ban-menu-pricing'),
                __('Upgrade to WP fail2ban Premium for a full event log', 'wp-fail2ban'),
                network_admin_url("?{$dismiss}=0"),
                __('Dismiss')
            );
        }
    }
    echo "<tfoot>{$tfoot}</tfoot>";

    echo '</table>';
}

/**
 * wp_dashboard_setup action hook
 *
 * @since 4.3.0
 *
 * @see https://codex.wordpress.org/Function_Reference/wp_add_dashboard_widget
 */
function wp_dashboard_setup()
{
    if ((!is_multisite() && current_user_can('manage_options')) ||
        (is_network_admin() && current_user_can('manage_network_options')))
    {
        wp_add_dashboard_widget(
            'wp_fail2ban_last_messages',
            __('Last 5 Messages [WPf2b]', 'wp-fail2ban'),
            __NAMESPACE__.'\dashboard_widget_last_messages'
        );
        wp_enqueue_style('wp-fail2ban-last-messages', plugins_url('css/widgets.css', __FILE__));
    }
}
add_action('wp_dashboard_setup', __NAMESPACE__.'\wp_dashboard_setup');
add_action('wp_network_dashboard_setup', __NAMESPACE__.'\wp_dashboard_setup');

