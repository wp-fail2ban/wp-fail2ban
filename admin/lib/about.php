<?php declare(strict_types=1);
/**
 * About
 *
 * @package wp-fail2ban
 * @since   4.2.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

use          org\lecklider\charles\wordpress\wp_fail2ban\premium\WPf2b;

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
        if (false === ($extra = apply_filters('wp_fail2ban_extra_about_transient', $extra))) {
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
 * Helper: strip blank lines and comments
 *
 * @since  4.4.1
 *
 * @param  array    $file
 *
 * @return array
 */
function strip_comments_blanks(array $file): array
{
    return array_filter($file, function ($item) {
        return !empty($item) && ('#' != $item[0]);
    });
}

/**
 * Addon-info helper
 *
 * @since  4.4.1
 *
 * @param  string   $prefix
 * @param  string   $free
 * @param  string   $premium
 *
 * @return string
 */
function getAddonInfo(string $name, string $prefix, string $free, string $premium, string $desc): string
{
    if (defined("{$prefix}_FILE")) {
        $fn = '\\'.constant("{$prefix}_NS").'\\get_extra_spash_info';
        $html = (function_exists($fn))
            ? $fn()
            : sprintf('<p>%s.</p>', __('Active', 'wp-fail2ban'));

    } elseif (file_exists(WP_PLUGIN_DIR."/{$free}/addon.php") ||
              file_exists(WP_PLUGIN_DIR."/{$premium}/addon.php"))
    {
        $html = sprintf('<p><span class="inactive">%s.</span></p>', __('Inactive', 'wp-fail2ban'));

    } else {
        $html = "<p>$desc</p><p>";
        $html .= sprintf(
            '<a href="https://%s.com/?utm_source=extra-about" target="_blank">%s</a>',
            $free,
            __('More Info') // use WP translation
        );
        $url = network_admin_url('update.php?action=install-plugin&plugin='.$free);
        $url = wp_nonce_url($url, 'install-plugin_'.$free);
        $url = esc_url($url);
        $html .= sprintf(
            '<a class="button button-small" href="%s">%s</a>',
            $url,
            __('Install Now') // use WP translation
        );
        $html .= '</p>';
    }

    return "<dt>$name</dt><dd>$html</dd>";
}

/**
 * Helper: Blocklist info
 *
 * @since  4.4.1
 *
 * @return string
 */
function getBlocklistInfo(): string
{
    return getAddonInfo(
        'Blocklist',
        'WP_FAIL2BAN_ADDON_BLOCKLIST',
        'wpf2b-addon-blocklist',
        'wp-fail2ban-addon-blocklist',
        __('A collaborative preemptive blocklist.', 'wp-fail2ban')
    );
}

/**
 * Helper: Contact Form 7 info
 *
 * @since  5.0.0
 *
 * @return string
 */
function getContactForm7Info(): string
{
    return (defined('WPCF7_VERSION'))
        ? getAddonInfo(
            'for Contact Form 7',
            'WP_FAIL2BAN_ADDON_CF7',
            'wp-fail2ban-addon-contact-form-7',
            'wp-fail2ban-addon-contact-form-7-premium',
            __('', 'wp-fail2ban')
        )
        : '';
}

/**
 * Helper: Gravity Forms info
 *
 * @since  5.0.0
 *
 * @return string
 */
function getGravityFormsInfo(): string
{
    return (class_exists('GFCommon'))
        ? getAddonInfo(
            'for Gravity Forms',
            'WP_FAIL2BAN_ADDON_GRAVITY_FORMS',
            'wp-fail2ban-addon-gravity-forms',
            'wp-fail2ban-addon-gravity-forms-premium',
            __('', 'wp-fail2ban')
        )
        : '';
}

/**
 * Info about the DB status
 *
 * @since  5.0.0    Refactored to use Site Health test
 *
 * @return string   HTML
 */
function getDbInfo(): string
{
    $shi = premium\SiteHealth::get_instance();
    $results = $shi->get_test_premium_db();

    if ('good' == $results['status']) {
        $fmt = <<<HTML
<p><span class="wpf2b-ok"><span class="dashicons dashicons-yes"></span> %s:</span> %s</p>
HTML;
        $html =sprintf($fmt, __('OK'), strip_tags($results['description']));

    } else {
        $fmt = <<<HTML
<p class="error">%s</p>
<p>%s</p>
HTML;
        $html = sprintf(
            $fmt,
            __('MISSING - ACTION REQUIRED.', 'wp-fail2ban'),
            sprintf(
                /* translators: %s: <a href> internals */
                __('Be sure to <b>backup your database <u>BEFORE</u></b> clicking <a %s>here</a> to re-initialise.', 'wp-fail2ban'),
                'href="?page=wpf2b-settings&action=force-activation"'
            )
        );
    }

    return $html;
}

/**
 * Info about the Cloudflare IP list
 *
 * @since  5.0.0
 *
 * @return string   HTML
 */
function getCloudflareInfo(): string
{
    if (Config::get('WP_FAIL2BAN_EX_PROXY_CLOUDFLARE')) {
        $cf = [];
        if (!empty($cf['IPs'] = Config::get('WP_FAIL2BAN_EX_PROXY_CLOUDFLARE_IPS'))) {
            if (0 < ($lu = Config::get('WP_FAIL2BAN_EX_PROXY_CLOUDFLARE_IPS_UPDATED'))) {
                $tz = new \DateTimeZone(wp_timezone_string());
                $dt = new \DateTimeImmutable("@{$lu}", $tz);
                $lu = sprintf('<span class="wpf2b-ok"><span class="dashicons dashicons-yes"></span> %s:</span> %s', __('OK'), $dt->format('Y/m/d H:i:s O'));
            } else {
                $lu = sprintf('<span class="wpf2b-error"><em>%s</em></span>', __('Last update unknown', 'wp-fail2bam'));
            }

            // There's space for 2 columns of CIDR IPv4 addresses, so first filter by length
            $short_ips = array_filter($cf['IPs'], function ($ip) {
                return (18 >= strlen($ip));
            });
            $long_ips = array_filter($cf['IPs'], function ($ip) {
                return (18 < strlen($ip));
            });
            $l = array_slice($short_ips, 0, (int)ceil(count($short_ips)/2));
            $r = array_slice($short_ips, count($l));
            $fmt = <<<HTML
<p>${lu}<p>
<table id="cloudflare-ips">
  <tr>
    <td>%s</td>
    <td>&nbsp;</td>
    <td>%s</td>
  </tr>
  <tr>
    <td colspan="3">%s</td>
  </tr>
</table>
HTML;
            $html = sprintf($fmt, join('<br>', $l), join('<br>', $r), join("<br>", $long_ips));
        } else {
            $html = __('Not set.', 'wp-fail2ban');
        }
    } else {
        $html = '<span class="dashicons dashicons-ellipsis"></span> '.__('Not enabled.', 'wp-fail2ban');
    }

    return $html;
}

/**
 * li helper
 *
 * @since  5.2.1
 *
 * @param  string   $class_wpf2b
 * @param  string   $class_dashicon
 * @param  string   $blurb
 */
function li(string $class_wpf2b, string $class_dashicon, string $blurb): void
{
    echo <<<HTML
<li><span class="wpf2b-{$class_wpf2b}"><span class="dashicons dashicons-{$class_dashicon}"></span> {$blurb}</span></li>
HTML;
}

/**
 * About content
 *
 * @since  4.2.0
 *
 * @return void
 */
function about(): void
{
    $wp_f2b_ver = WP_FAIL2BAN_VER2;
    $extra = _get_extra_about();
    $utm = '?utm_source=about&utm_medium=about&utm_campaign='.WP_FAIL2BAN_VER;

    $logo_box = [
        'title' => 'WP fail2ban',
        'logo'  => plugins_url('assets/icon.png', WP_FAIL2BAN_FILE),
        'links' => [
            'Blog'   => "https://wp-fail2ban.com/blog/{$utm}",
//            'Guide'     => "https://life-with.wp-fail2ban.com/{$utm}",
            'Reference' => "https://docs.wp-fail2ban.com/en/{$wp_f2b_ver}/{$utm}",
            'Support'   => "https://forums.invis.net/c/wp-fail2ban/support/{$utm}"
        ]
    ];

    if (wf_fs()->can_use_premium_code()) {
        $info = [
            'db'         => getDbInfo(),
            'cloudflare' => getCloudflareInfo()
        ];
    }

    ?>
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content">
        <div class="meta-box-sortables ui-sortable">
          <?=$extra?>
          <div class="postbox" id="4-4-0">
            <h2>Version <?=WP_FAIL2BAN_VER2?></h2>
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
                <dd><?=$info['db']?></dd>
                <dt><?=__('Cloudflare IPs', 'wp-fail2ban')?></dt>
                <dd><?=$info['cloudflare']?></dd>
                <?php endif; ?>

                <?php if (!defined('WP_FAIL2BAN_SITE_HEALTH_SKIP_FILTERS')): ?>
                <dt>Filters</dt>
                <dd>
                    <ul class="filters"><?php
                    $run_site_health = false;
                    $shi = SiteHealth::get_instance();
                    $obs = $shi->get_test_filter_obsolete();
                    switch ($obs['status']) {
                        case 'good':
                            // OK
                            li('ok', 'yes', __('Up to date.', 'wp-fail2ban'));
                            break;
                        case 'recommended':
                            li('warning', 'warning', __('Status unknown.', 'wp-fail2ban'));
                            $run_site_health = true;
                            break;
                        case 'critical':
                            li('error', 'no', __('Obsolete filter(s) found.', 'wp-fail2ban'));
                            $run_site_health = true;
                            break;
                        case 'old':
                            li('warning', 'warning', __('Outdated but compatible filter(s).', 'wp-fail2ban'));
                            break;
                    }

                    $mod = $shi->get_test_filter_modified();
                    if (empty($mod)) {
                        // obj already failed
                    } elseif ('good' == $mod['status']) {
                        // OK
                        li('ok', 'yes', __('Not modified.', 'wp-fail2ban'));
                    } else {
                        li('warning', 'warning', __('Modified filter(s) found.', 'wp-fail2ban'));
                        $run_site_health = true;
                    }
                    echo ' ';

                    $mis = $shi->get_test_filter_missing();
                    if (empty($mis)) {
                        // obj already failed
                    } elseif ('good' == $mis['status']) {
                        // OK
                        li('ok', 'yes', __('All filters present.', 'wp-fail2ban'));
                    } else {
                        li('error', 'no', __('Incomplete.', 'wp-fail2ban'));
                        $run_site_health = true;
                    }

                    if ($run_site_health) {
                        printf('</ul><p><span class="wpf2b-error"><span class="dashicons dashicons-star-filled"></span> %s</span></p>', __('Run Site Health Tool.', 'wp-fail2ban'));
                    } else {
                        echo '</ul>';
                    }
                    ?>
                </dd>
                <?php endif; ?>
              </dl>
            </div>
          </div>
          <div class="postbox addons">
            <div class="inside">
              <h3>Add-Ons</h3>
              <dl>
                <?=getBlocklistInfo()?>
                <?=getContactForm7Info()?>
                <?=getGravityFormsInfo()?>
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

