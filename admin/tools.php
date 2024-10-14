<?php
/**
 * Tools
 *
 * @package wp-fail2ban
 * @since   4.3.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Proxy for api.wp-fail2ban.com
 *
 * @since 4.2.6
 */
function remote_tools()
{
    ?>
<div class="wrap">
    <h1><?=__('Remote Tools (&beta;)', 'wp-fail2ban')?></h1>
    <hr class="wp-header-end">
    <?php
    if (function_exists(__NAMESPACE__.'\addons\remote_tools\tab')) {
        addons\remote_tools\tab();
    } elseif (defined('WP_FAIL2BAN_ADDON_REMOTE_TOOLS_NS') && function_exists(WP_FAIL2BAN_ADDON_REMOTE_TOOLS_NS.'\tab')) {
        $func = WP_FAIL2BAN_ADDON_REMOTE_TOOLS_NS.'\tab';
        $func();
    } else {
        ?>
    <h2 class="nav-tab-wrapper wp-clearfix">
        <a class="nav-tab nav-tab-active" href="#"><?=__('Overview', 'wp-fail2ban')?></a>
    </h2>
    <div class="card">
        <h2>Remote Tools Add-on</h2>
        <p>This add-on provides features that make life with WP fail2ban easier, all from a remote server. This gives access to valuable but infrequently used tools without bloating the core plugin.</p>
        <p>The first of these is a <strong>Custom Filter Tool</strong> (CFT).</p>
        <blockquote>
            <p>The filter files included are intended only as a starting point for those who want <em>WPf2b</em> to work &ldquo;out of the box&rdquo;.</p>
            <p>There is no &ldquo;one size fits all&rdquo; configuration possible for <em>fail2ban</em> - what may be a soft failure for one site should be treated as a hard failure for another, and vice versa.</p>
        </blockquote>
        <p>You could simply edit the filter files included, but it&lsquo;s surprisingly easy to make a mistake; I learned this the hard way with earlier versions of <em>WPf2b</em>.... The CFT removes most of the opportunities for human error - always a good thing!</p>
        <hr>
        <p>The Remote Tools Add-on is available from the <a href="<?php echo admin_url('admin.php?page=wp-fail2ban-addons') ?>">Add-Ons menu</a>.</p>
    </div>
        <?php
    }

    ?>
</div>
    <?php
}

