<?php
/**
 * Config
 *
 * @package wp-fail2ban
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

require_once 'lib/tab.php';
require_once 'lib/logging.php';
require_once 'config/block.php';
require_once 'config/logging.php';
require_once 'config/plugins.php';
require_once 'config/remote-ips.php';
require_once 'config/syslog.php';

/**
 * Init
 *
 */
function init_tabs($init)
{
    if (!$init) {
        new TabBlock();
        new TabLogging();
        new TabPlugins();
        new TabRemoteIPs();
        new TabSyslog();
    }
    return true;
}
add_filter('wp_fail2ban_init_tabs', __NAMESPACE__.'\init_tabs');

/**
 * Display settings messages.
 *
 * @since 4.3.0
 */
function admin_notices()
{
    $screen = get_current_screen();
    switch ($screen->id) {
        case 'security_page_wp-fail2ban-premium':
        case 'wp-fail2ban_page_wpf2b-settings':
            settings_errors();
            break;
    }
}
add_action('admin_notices', __NAMESPACE__.'\admin_notices');

/**
 * Get network settings messages.
 *
 * @since 4.3.0
 */
function network_admin_notices()
{
    $screen = get_current_screen();
    switch ($screen->id) {
        case 'security_page_wp-fail2ban-premium-network':
        case 'wp-fail2ban_page_wpf2b-settings-network':
            if ($transients = get_site_transient('settings_errors')) {
                global $wp_settings_errors;

                $wp_settings_errors = array_merge((array)$wp_settings_errors, $transients);
                delete_site_transient('settings_errors');
            }
            settings_errors();
            break;
    }
}
add_action('network_admin_notices', __NAMESPACE__.'\network_admin_notices');

/**
 * Render Security settings.
 *
 * @since 4.3.0
 */
function security()
{
    $tabs = [
        'logging',
        'syslog',
        'block',
        'remote-ips',
        'plugins'
    ];
    $tabs = apply_filters(__METHOD__.'.tabs', $tabs);
    $page = apply_filters(__METHOD__.'.page', plugin_basename(WP_FAIL2BAN_DIR));

    render_tabs($tabs, $page);
}

/**
 * Render Settings.
 *
 * @since 4.0.0
 */
function settings()
{
    $tabs = [];

    if (!function_exists('\add_security_page')) {
        $tabs = [
            'logging',
            'syslog',
            'block',
            'remote-ips'
        ];
        if (version_compare(PHP_VERSION, '5.6.0', '>=')) {
            $tabs[] = 'plugins';
        }
    }
    $tabs = apply_filters(__METHOD__.'.tabs', $tabs);

    render_tabs($tabs, 'wpf2b-settings');
}

/**
 * Render Tabs.
 *
 * @since 4.3.0
 *
 * @param array     $tabs       List of slugs of tabs to render
 * @param string    $menu       Menu slug
 */
function render_tabs(array $tabs, $menu)
{
    $active_tab = TabBase::getActiveTab();

    ?>
<div class="wrap">
    <?=apply_filters(__METHOD__.'.title', sprintf('<h1>%s</h1>', __('Settings', 'wp-fail2ban')))?>
  <hr class="wp-header-end">

  <h2 class="nav-tab-wrapper wp-clearfix">
    <?php
    foreach ($tabs as $slug) {
        $class = 'nav-tab';
        if ($active_tab->getSlug() == $slug) {
            $class .= ' nav-tab-active';
        }
        $params = apply_filters(__METHOD__.'.params', [
            'page' => $menu,
            'tab' => $slug
        ]);
        printf('<a class="%s" href="?%s">%s</a>', $class, http_build_query($params), TabBase::getTabName($slug));
    }
    ?>
  </h2>

    <?php
    // Because the settings API was never finished we need an ugly hack
    $action = sprintf(
        '%s?tab=%s',
        admin_url(is_network_admin()
            ? 'admin-post.php'
            : 'options.php'),
        $active_tab->getSlug()
    );
    ?>

  <form action="<?=$action?>" method="post">
    <?php
    settings_fields('wp-fail2ban');
    $active_tab->render();
    ?>
  </form>
</div>
    <?php
}

/**
 * Helper: filtered defined(...)
 *
 * @since 4.3.0
 *
 * @param  string   $define
 * @return mixed
 */
function have_defined($define)
{
    return apply_filters(__NAMESPACE__.'\have_defined', defined($define), $define);
}

