<?php
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
    foreach (get_mu_plugins() as $plugin => $data) {
        if (0 === strpos($data['Name'], 'WP fail2ban')) {
            $wp_f2b_ver = substr(WP_FAIL2BAN_VER, 0, strrpos(WP_FAIL2BAN_VER, '.'));
            $error_msg = __('<h1>Cannot activate WP fail2ban</h1>', 'wp-fail2ban');
            $mu_file = WPMU_PLUGIN_DIR.'/'.$plugin;
            if (is_link($mu_file)) {
                if (false === ($link = readlink($mu_file)) ||
                    false === ($path = realpath($mu_file)))
                {
                    $h3 = __('A broken symbolic link was found in <tt>mu-plugins</tt>:');
                    $error_msg .= <<<__ERROR__
<h3>{$h3}</h3>
<p><tt>{$mu_file}</tt></p>
__ERROR__;
                } elseif (WP_FAIL2BAN_FILE == $path) {
                    // OK, we're linking to ourself
                } else {
                    $mu_file = str_replace('/', '/<wbr>', $mu_file);
                    $mu_file = substr($mu_file, strlen(WPMU_PLUGIN_DIR)-1);

                    $h3 = __('A conflicting symbolic link was found in <tt>mu-plugins</tt>:');
                    $error_msg .= <<<__ERROR__
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
__ERROR__;
                }

            } else {
                $mu_file = str_replace('/', '/<wbr>', $mu_file);
                $mu_file = substr($mu_file, strlen(WPMU_PLUGIN_DIR)-1);

                $h3 = __('A conflicting file was found in <tt>mu-plugins</tt>:');
                $error_msg .= <<<__ERROR__
<h3>{$h3}</h3>
<p><tt>{$mu_file}</tt></p>
__ERROR__;
            }
            $error_msg .= sprintf(
                __('<p>Please see the <a href="%s" target="_blank">documentation</a> for how to configure %s for <tt>mu-plugins</tt>.</p>'),
                "https://docs.wp-fail2ban.com/en/{$wp_f2b_ver}/configuration.html#mu-plugins-support",
                $wpf2b
            );
            $error_msg .= sprintf(__('<p>Click <a href="%s">here</a> to return to the plugins page.</p>'), admin_url('plugins.php'));

            deactivate_plugins(plugin_basename(WP_FAIL2BAN_FILE));
            wp_die($error_msg);
        }
    }

    @include_once WP_FAIL2BAN_DIR.'/premium/activation.php';
});

