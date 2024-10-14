<?php declare(strict_types=1);
/**
 * WP fail2ban activation
 *
 * @package wp-fail2ban
 * @since   4.3.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

// @codeCoverageIgnoreStart

defined('ABSPATH') or exit;

\register_activation_hook(WP_FAIL2BAN_FILE, function () {
    define('WP_FAIL2BAN_ACTIVATING', true);

    foreach (get_mu_plugins() as $plugin => $data) {
        if (0 === strpos($data['Name'], 'WP fail2ban')) {
            $wp_f2b_ver = substr(WP_FAIL2BAN_VER, 0, strrpos(WP_FAIL2BAN_VER, '.'));
            $error_msg = '<h1>'.__('Cannot activate WP fail2ban', 'wp-fail2ban').'</h1>';
            $mu_file = WPMU_PLUGIN_DIR.'/'.$plugin;
            if (is_link($mu_file)) {
                if (false === ($link = readlink($mu_file)) ||
                    false === ($path = realpath($mu_file)))
                {
                    $h3 = __('A broken symbolic link was found in <tt>mu-plugins</tt>:');
                    $error_msg .= <<< HTML
<h3>{$h3}</h3>
<p><tt>{$mu_file}</tt></p>
HTML;
                } elseif (WP_FAIL2BAN_FILE == $path) {
                    // OK, we're linking to ourself
                    break;
                } else {
                    $mu_file = str_replace('/', '/<wbr>', $mu_file);
                    $mu_file = substr($mu_file, strlen(WPMU_PLUGIN_DIR)-1);

                    $h3 = __('A conflicting symbolic link was found in <tt>mu-plugins</tt>:');
                    $error_msg .= <<< HTML
<h3>{$h3}</h3>
<style>
table { text-align: center; }
td { width: 50%; }
th { font-size: 200%; }
td, th { font-family: monospace; }
span.tt { font-weight: bold; }
</style>
<table>
  <tr>
    <td>{$mu_file}</td>
    <th>&DoubleRightArrow;</th>
    <td>{$link}</td>
  </tr>
  <tr>
    <td colspan="3"><span class="tt">&equiv;</span> <span>{$path}</span></td>
  </tr>
  <tr>
    <td colspan="3"></td>
  </tr>
</table>
HTML;
                }

            } else {
                $mu_file = str_replace('/', '/<wbr>', $mu_file);
                $mu_file = substr($mu_file, strlen(WPMU_PLUGIN_DIR)-1);

                $h3 = __('A conflicting file was found in <tt>mu-plugins</tt>:');
                $error_msg .= <<< HTML
<h3>{$h3}</h3>
<p><tt>{$mu_file}</tt></p>
HTML;
            }
            $error_msg .= sprintf(
                '<p>%s</p>',
                sprintf(
                    /* translators: 1: <a href> internals */
                    __('Please see the <a %1$s>documentation</a> for how to configure <i>WP fail2ban</i> for <tt>mu-plugins</tt>.', 'wp-fail2ban'),
                    sprintf(
                        'href="%s" target="_blank"',
                        "https://docs.wp-fail2ban.com/en/{$wp_f2b_ver}/configuration.html#mu-plugins-support"
                    )
                )
            );
            $error_msg .= sprintf(
                '<p>%s</p>',
                sprintf(
                    /* translators: %s: <a href> internals */
                    __('Click <a %s>here</a> to return to the plugins page.'),
                    sprintf(
                        'href="%s"',
                        admin_url('plugins.php')
                    )
                )
            );

            deactivate_plugins(plugin_basename(WP_FAIL2BAN_FILE));
            wp_die($error_msg);
        }
    }

    @include_once WP_FAIL2BAN_DIR.'/premium/activation.php';
});

