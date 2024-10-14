<?php declare(strict_types=1);
/**
 * About
 *
 * @package wp-fail2ban
 * @since   4.2.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Pull in extra "about" information
 *
 * @since  4.3.0
 *
 * @return string
 */
function _get_extra_about()
{
    $extra = '';

    /**
     * Don't make a remote call if the user hasn't opted in
     */
    if (!wf_fs()->is_tracking_prohibited()) {
        $extra = get_site_transient('wp_fail2ban_extra_about');
        if (false === apply_filters('wp_fail2ban_extra_about_transient', $extra)) {
            $url = apply_filters('wp_fail2ban_extra_about_url', 'https://wp-fail2ban.com/extra-about/?version='.WP_FAIL2BAN_VER);
            if (!is_wp_error($rv = wp_remote_get($url))) {
                /**
                 * Try not to fetch more than once per day
                 */
                set_site_transient('wp_fail2ban_extra_about', $rv['body'], DAY_IN_SECONDS);

                $extra = $rv['body'];
            }
        }
    }

    return $extra;
}

/**
 * Helper to provide wrapper
 *
 * @since  4.3.0.10
 *
 * @return void
 */
function welcome()
{
    ?>
<div class="wrap" id="wp-fail2ban">
  <h1 class="wp-inline-header">&nbsp;</h1>
    <?php about(); ?>
</div>
    <?php
}

/**
 * About content
 *
 * @since  4.2.0
 *
 * @return void
 */
function about()
{
    global $wpdb;

    $wp_f2b_ver = WP_FAIL2BAN_VER_SHORT;
    $extra = _get_extra_about();
    $utm = '?utm_source=about&utm_medium=about&utm_campaign='.WP_FAIL2BAN_VER;

    $logo_box = [
        'title' => 'WP fail2ban',
        'logo'  => plugins_url('assets/icon.svg', WP_FAIL2BAN_FILE),
        'links' => [
            'Blog'   => "https://wp-fail2ban.com/blog/{$utm}",
//            'Guide'     => "https://life-with.wp-fail2ban.com/{$utm}",
            'Reference' => "https://docs.wp-fail2ban.com/en/{$wp_f2b_ver}/{$utm}",
            'Support'   => "https://forums.invis.net/c/wp-fail2ban/support/{$utm}"
        ]
    ];

    if (wf_fs()->can_use_premium_code()) {
        $table = $wpdb->base_prefix.'fail2ban_log';
        $db_table = ($table == $wpdb->get_var("SHOW TABLES LIKE '$table'"))
            ? sprintf('<p>OK: %d entries.</p>', $wpdb->get_var("SELECT COUNT(*) FROM `$table`;"))
            : __('<p>MISSING - ACTION REQUIRED.</p><p>Be sure to <b>backup your database <u>BEFORE</u></b> clicking <a href="?page=wpf2b-settings&action=force-activation">here</a> to re-initialise.</p>', 'wp-fail2ban');
    }

    if (defined('WP_FAIL2BAN_ADDON_BLOCKLIST_FILE')) {
        $addon_blocklist = '<p>Active.</p>';
    } elseif (file_exists(WP_PLUGIN_DIR.'/wpf2b-addon-blocklist/addon.php') ||
              file_exists(WP_PLUGIN_DIR.'/wp-fail2ban-addon-blocklist/addon.php'))
    {
        $addon_blocklist = '<p>Inactive.</p>';
    } else {
        $addon_blocklist = '<p>Not installed.</p>';
    }

    if (defined('WP_FAIL2BAN_ADDON_CLOUDFLARE_FILE')) {
        $addon_cloudflare = '<p>Active.</p>';
    } elseif (file_exists(WP_PLUGIN_DIR.'/wp-fail2ban-addon-cloudflare/addon.php') ||
              file_exists(WP_PLUGIN_DIR.'/wp-fail2ban-addon-cloudflare/addon.php'))
    {
        $addon_cloudflare = '<p>Inactive.</p>';
    } else {
        $addon_cloudflare = '<p>Not installed.</p>';
    }

    ?>
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content">
        <div class="meta-box-sortables ui-sortable">
          <?=$extra?>
          <div class="postbox" id="4-4-0">
            <h2>Version <?=WP_FAIL2BAN_VER_SHORT?></h2>
            <div class="inside">
              <section class="premium">

              </section>
              <hr>
              <section>
    <?php readme(WP_FAIL2BAN_VER_SHORT, WP_FAIL2BAN_DIR.'/readme.txt'); ?>
              </section>
            </div>
          </div>
        </div>
      </div>
      <div id="postbox-container-1" class="postbox-container">
        <div class="meta-box-sortables">
          <?php logo_box($logo_box); ?>
          <div class="postbox status">
            <div class="inside">
              <h3>Status</h3>
              <dl>
                <?php if (wf_fs()->can_use_premium_code()): ?>
                <dt><?=__('Database table', 'wp-fail2ban')?></dt>
                <dd><?=$db_table?></dd>
                <?php endif; ?>
                <dt><?=__('Blocklist Add-on', 'wp-fail2ban')?></dt>
                <dd><?=$addon_blocklist?></dd>
                <dt><?=__('Cloudflare Add-on', 'wp-fail2ban')?></dt>
                <dd><?=$addon_cloudflare?></dd>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>
    &nbsp;
  </div>
    <?php
}

/**
 * Helper - logo box
 *
 * @since  4.4.0    Add type hints, return type
 * @since  4.3.2.2
 *
 * @return void
 */
function logo_box(array $args): void
{
    ?>
          <div class="postbox alt">
            <img src="<?=$args['logo']?>"?>
            <h1><?=$args['title']?></h1>
            <div class="links">
              <ul>
    <?php foreach ($args['links'] as $name => $url): ?>
                <li><a href="<?=$url?>" rel="noopener" target="_blank"><?=$name?></a></li>
    <?php endforeach; ?>
              </ul>
            </div>
          </div>
    <?php
}

