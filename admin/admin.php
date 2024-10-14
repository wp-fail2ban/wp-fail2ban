<?php declare(strict_types=1);
/**
 * Admin
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

require_once __DIR__.'/config.php';
require_once __DIR__.'/widgets.php';
require_once __DIR__.'/lib/about.php';

/**
 * Helper: Add a new submenu "under" the Freemius "Add-Ons" submenu
 *
 * @since  4.4.0        Add type hints
 * @since  4.3.2.1      Backport from 4.3.4.0
 *
 * @param  string       $page_title The text to be displayed in the title tags of the page when the menu
 *                                  is selected.
 * @param  string|null  $capability The capability required for this menu to be displayed to the user.
 * @param  string       $menu_slug  The slug name to refer to this menu by. Should be unique for this menu
 *                                  and only include lowercase alphanumeric, dashes, and underscores characters
 *                                  to be compatible with sanitize_key().
 * @param  callable     $function   The function to be called to output the content for this page.
 * @return false|string     The resulting page's hook_suffix, or false if the user does not have the capability required.
 */
function add_wpf2b_addon_page(string $page_title, ?string $capability, string $menu_slug, $function)
{
    global $submenu;

    $menu_title = " - $page_title";

    if (!$capability) {
        $capability = (is_multisite())
            ? 'manage_network_options'
            : 'manage_options';
    }

    $parent = (wf_fs()->is_activation_mode()) ? null : 'wp-fail2ban-menu';
    $sub_menu = 'wp-fail2ban-menu-addons';

    if ($hook = add_submenu_page($parent, $page_title, $menu_title, $capability, $menu_slug, $function)) {
        foreach ($submenu as &$menu) {
            if (isset($menu[0]) && $menu[0][2] == 'wp-fail2ban-menu') {
                $new_submenu    = [];
                $menu_item      = array_pop($menu);

                // Find the submenu we're appending to
                for ($i = 0; $i < count($menu) && $sub_menu != $menu[$i][2]; $i++) {
                    $new_submenu[] = $menu[$i];
                }

                if ($i < count($menu)) {
                    $new_submenu[] = $menu[$i++];
                }

                // Find the menu item alphabetically before the new item
                for (; $i < count($menu) && isset($menu[$i][0]) && 0 === strpos($menu[$i][0], ' - ') && 0 > strcmp($menu[$i][0], $menu_title); $i++) {
                    $new_submenu[] = $menu[$i];
                }

                $new_submenu[] = $menu_item;

                for (; $i < count($menu); $i++) {
                    $new_submenu[] = $menu[$i];
                }

                $menu = $new_submenu;

                break;
            }
        }
    }

    return $hook;
}

/**
 * Output readme.txt to standard About format
 *
 * @since  4.4.0    Add type hints, return type
 * @since  4.3.2.2
 *
 * @param  string   $ver    Version stem, e.g. 4.3.2
 * @param  string   $file   Full path to readme.txt
 *
 * @return void
 */
function readme(string $ver, string $file): void
{
    if (file_exists($file)) {
        $rm = file($file, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
        $inChangelog = $inList = $inSubList = false;
        foreach ($rm as $line) {
            if ('== Changelog ==' == $line) {
                $inChangelog = true;
                echo "<dl>\n";
                continue;
            }
            if ($inChangelog) {
                if (preg_match('/^= (.*) =$/', $line, $matches) &&
                    ('Develop' == $matches[1] || substr($matches[1], 0, strlen($ver)) == $ver))
                {
                    if ($inList) {
                        echo "</ul></dd>\n";
                    }
                    echo "<dt>{$matches[1]}</dt>\n";
                    echo "<dd><ul>\n";
                    $inList = true;

                } elseif (preg_match('/^(\s*)\* (.*)$/', $line, $matches)) {
                    $li = $matches[2];
                    $li = preg_replace('/\[(.*?)\]\((.*?)\)(\.)?/', '<a href="$2" target="_blank" rel="noopener">$1 <span class="dashicons dashicons-external"></span></a>', $li);
                    $li = preg_replace('/`(.*?)`/', '<tt>$1</tt>', $li);
                    $li = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $li);
                    $li = preg_replace('/\*(.*?)\*/', '<i>$1</i>', $li);
                    $li = preg_replace('|\((h/t .*?)\)|', '<span class="ht">$1</span>', $li);
                    $li = preg_replace('/^(Fix|Deprecate )/', '<span style="color:red">$1</span>', $li);
                    $li = preg_replace('/^(Update )/', '<span style="color:purple">$1</span>', $li);
                    $li = preg_replace('/^(Site Health: )/', '<span style="color:blue">$1</span>', $li);
                    $li = str_replace('[Premium only]', '<span style="color: grey; font-style: italic; font-weight: bold">Premium only</span>', $li);
                    if (!$inSubList && strlen($matches[1])) {
                        echo '<ul>';
                        $inSubList = true;
                    } elseif ($inSubList && !strlen($matches[1])) {
                        echo '</ul>';
                        $inSubList = false;
                    }
                    echo "<li>{$li}</li>\n";

                } else {
                    if ($inList) {
                        echo "</ul></dd>\n";
                        $inList = false;
                    }
                    echo "</dl>\n";
                    $inChangelog = false;

                    break;
                }
            }
        }
    } else {
        echo "<p>Error: Cannot open <tt>readme.txt</tt></p>\n";
    }
}

/**
 * Helper: Security and Settings menu
 *
 * @since  4.4.0    Add return type
 * @since  4.3.0
 *
 * @param  string   $capability Capability
 *
 * @return void
 */
function _security_settings(string $capability = 'manage_options'): void
{
    if (function_exists('\add_security_page')) {
        if ($hook = add_security_page(
            'WP fail2ban',
            'WP fail2ban',
            plugin_basename(WP_FAIL2BAN_DIR),
            __NAMESPACE__.'\security'
        )) {
            add_action("load-$hook", function () {
                wp_enqueue_style('wpf2b-admin', plugins_url('css/admin.css', __FILE__));
                apply_filters('wp_fail2ban_init_tabs', false);
                TabBase::setDefaultTab('logging');
                TabBase::getActiveTab()->current_screen();
            });
            if (class_exists(__NAMESPACE__.'\premium\WPf2b')) {
                _settings('about', $capability);
            }
        }
    } else {
        _settings(apply_filters(__METHOD__.'.page', 'logging'), $capability);
    }
}

/**
 * Helper: Settings menu
 *
 * @since  4.4.0    Add return type
 * @since  4.3.0
 *
 * @param  $page        string|null
 * @param  $capability  string
 *
 * @return void
 */
function _settings(string $page = null, string $capability = 'manage_options'): void
{
    if ($hook = add_submenu_page(
        'wp-fail2ban-menu',
        __('Settings', 'wp-fail2ban'),
        __('Settings', 'wp-fail2ban'),
        $capability,
        'wpf2b-settings',
        __NAMESPACE__.'\settings'
    )) {
        add_action("load-$hook", function () use ($page) {
            wp_enqueue_style('wpf2b-admin', plugins_url('css/admin.css', __FILE__));
            apply_filters('wp_fail2ban_init_tabs', false);
            TabBase::setDefaultTab($page);
            TabBase::getActiveTab()->current_screen();
        });
    }
}

/**
 * Register admin menus
 *
 * @since  4.4.0    Add return type
 * @since  4.0.0
 *
 * @return void
 */
function admin_menu(): void
{
    if (!is_multisite()) {
        if ((defined('WP_FAIL2BAN_FREE_ONLY') && false !== WP_FAIL2BAN_FREE_ONLY) || !wf_fs()->can_use_premium_code()) {
            $hook = add_menu_page(
                'WP fail2ban',
                'WP fail2ban',
                'manage_options',
                'wp-fail2ban-menu',
                __NAMESPACE__.'\welcome',
                plugin_dir_url(WP_FAIL2BAN_FILE).'assets/menu.svg'
            );
            add_action("load-$hook", function () {
                wp_enqueue_style('wpf2b-admin', plugins_url('css/admin.css', __FILE__));
            });
            add_action('admin_menu', __NAMESPACE__.'\admin_menu_fix', PHP_INT_MAX);
            do_action('wp_fail2ban_menu');

            _security_settings();
        }
    }
}
add_action('admin_menu', __NAMESPACE__.'\admin_menu');

/**
 * Register network admin menus
 *
 * @since  4.4.0    Add return type
 * @since  4.3.0
 *
 * @return void
 */
function network_admin_menu(): void
{
    if ((defined('WP_FAIL2BAN_FREE_ONLY') && false !== WP_FAIL2BAN_FREE_ONLY) || !wf_fs()->can_use_premium_code()) {
        _network_admin_menu();
    }
}
add_action('network_admin_menu', __NAMESPACE__.'\network_admin_menu');

/**
 * Actual network admin menu handler
 *
 * @since  4.4.0    Add return type
 * @since  4.3.0
 *
 * @return void
 */
function _network_admin_menu(): void
{
    $hook = add_menu_page(
        'WP fail2ban',
        'WP fail2ban',
        'manage_options',
        'wp-fail2ban-menu',
        __NAMESPACE__.'\welcome',
        plugin_dir_url(WP_FAIL2BAN_FILE).'assets/menu.svg'
    );
    add_action("load-$hook", function () {
        wp_enqueue_style('wpf2b-admin', plugins_url('css/admin.css', __FILE__));
    });
    add_action('network_admin_menu', __NAMESPACE__.'\admin_menu_fix', PHP_INT_MAX);
    do_action('wp_fail2ban_menu');

    _security_settings();
}

/**
 * Fix first submenu name.
 *
 * @since  4.4.0    Add return type
 * @since  4.3.0
 *
 * @return void
 */
function admin_menu_fix(): void
{
    global $submenu;

    if (isset($submenu['wp-fail2ban-menu']) && 'WP fail2ban' == @$submenu['wp-fail2ban-menu'][0][0]) {
        $submenu['wp-fail2ban-menu'][0][0] = __('Welcome', 'wp-fail2ban');
    }

    if (defined('WP_FAIL2BAN_FREE_ONLY') && WP_FAIL2BAN_FREE_ONLY) {
        remove_submenu_page('wp-fail2ban-menu', 'wp-fail2ban-menu-pricing');
    }
}

/**
 * Add Settings link on Plugins page
 *
 * @since  4.4.0.8  Activate "normal" plugin if running as MU
 * @since  4.4.0.3  Allow $plugin_data to be null despite spec
 * @since  4.4.0    Add type hints, return type
 * @since  4.2.0
 *
 * @param  string[]     $actions        An array of plugin action links. By default this can include 'activate',
 *                                      'deactivate', and 'delete'.
 * @param  string       $plugin_file    Path to the plugin file relative to the plugins directory.
 * @param  array|null   $plugin_data    An array of plugin data. See `get_plugin_data()`.
 * @param  string       $context        The plugin context. By default this can include 'all', 'active', 'inactive',
 *                                      'recently_activated', 'upgrade', 'mustuse', 'dropins', and 'search'.
 *
 * @return string[]
 *
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
function plugin_action_links(array $actions, string $plugin_file, ?array $plugin_data, string $context): array
{
    if (preg_match("|$plugin_file\$|", WP_FAIL2BAN_FILE) &&
        (!is_multisite() || is_network_admin()))
    {
        foreach (get_mu_plugins() as $plugin => $data) {
            if (0 === strpos($data['Name'], 'WP fail2ban')) {
                // MU plugin
                //
                // * Make sure the "normal" plugin is activated
                // * Remove "Deactivate" and "Delete" links
                $plugin = plugin_basename(WP_FAIL2BAN_FILE);

                if (array_key_exists($plugin, get_plugins()) && !is_plugin_active($plugin)) {
                    activate_plugin(
                        $plugin,
                        '',     // don't redirect anywhere
                        false,
                        true    // don't call activation hooks
                    );
                }

                unset($actions['deactivate']);
                unset($actions['delete']);
                break;
            }
        }

        // No settings tabs for ClassicPress + Free
        if (function_exists('\add_security_page') &&
            !wf_fs()->can_use_premium_code())
        {
            return $actions;
        }

        if (!wf_fs()->is_activation_mode() &&
            (!wf_fs()->can_use_premium_code() ||
            ((is_multisite() && wf_fs()->is_plan_or_trial('silver')) ||
            (!is_multisite() && wf_fs()->is_plan_or_trial('bronze')))))
        {
            $settings = sprintf(
                '<a href="%s?page=wpf2b-settings&tab=about" title="%s">%s</a>',
                network_admin_url('admin.php'),
                __('Settings', 'wp-fail2ban'),
                (function_exists('\add_security_page'))
                    ? '<span class="dashicon dashicons-admin-generic"></span>'
                    : __('Settings', 'wp-fail2ban')
            );
            // Add Settings at the start
            $actions = array_merge([
                'settings' => $settings
            ], $actions);
        }
    }

    return $actions;
}
add_filter('plugin_action_links', __NAMESPACE__.'\plugin_action_links', PHP_INT_MAX, 4);
add_filter('network_admin_plugin_action_links', __NAMESPACE__.'\plugin_action_links', 99999, 4);

/**
 * Add help tab to Dashboard
 *
 * @since  4.4.0    Add return type
 * @since  4.3.0
 *
 * @return void
 */
function admin_head_dashboard(): void
{
    $content = '';

    if ((!is_multisite() && current_user_can('manage_options')) ||
        (is_network_admin() && current_user_can('manage_network_options')))
    {
        $message = __('Shows the last 5 messages sent to <code>syslog</code> - provides simple status at a glance and can be helpful for debugging.', 'wp-fail2ban');
        if (!wf_fs()->can_use_premium_code()) {
            $message .= sprintf(
                /* translators: %s: <a href> internals */
                __('The <a %s>Premium version</a> provides more advanced views.', 'wp-fail2ban'),
                sprintf('href="%s"', network_admin_url('admin.php?page=wp-fail2ban-menu-pricing'))
            );
        }

        $content = sprintf(
            '<p><strong>%s</strong> &mdash; %s.</p>',
            __('Last 5 Messages', 'wp-fail2ban'),
            $message
        );
    }
    if (!empty($content = apply_filters('wp_fail2ban_dashboard_help', $content))) {
        get_current_screen()->add_help_tab([
            'id'        => 'wp-fail2ban',
            'title'     => 'WP fail2ban',
            'content'   => $content
        ]);
    }
}
add_action('admin_head-index.php', __NAMESPACE__.'\admin_head_dashboard', 9999);

