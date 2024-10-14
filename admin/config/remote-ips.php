<?php
/**
 * Settings - Remote IPs
 *
 * @package wp-fail2ban
 * @since   4.0.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Tab: Remote IPs
 *
 * @since 4.0.0
 */
class TabRemoteIPs extends TabBase
{
    /**
     * {@inheritDoc}
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        $this->__['wp-fail2ban-proxies']    = __('Proxies', 'wp-fail2ban');
        $this->__['remote-ips-proxies']     = __('IP list', 'wp-fail2ban');

        parent::__construct('remote-ips', __('Remote IPs', 'wp-fail2ban'));
    }

    /**
     * {@inheritDoc}
     *
     * @since 4.0.0
     */
    public function admin_init()
    {
        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        add_settings_section('wp-fail2ban-proxies', $this->__['wp-fail2ban-proxies'], [$this, 'section'], 'wp-fail2ban-remote-ips');
        add_settings_field('remote-ips-proxies',    $this->__['remote-ips-proxies'],  [$this, 'proxies'], 'wp-fail2ban-remote-ips', 'wp-fail2ban-proxies');
        // phpcs:enable
    }

    /**
     * {@inheritDoc}
     *
     * @since 4.3.0
     */
    public function current_screen()
    {
        $fmt = <<<___FMT___
<dl><style>dt{font-weight:bold;}</style>
  <dt>%s</dt>
  <dd><p>%s</p><p>%s</p>%s</dd>
</dl>
___FMT___;
        get_current_screen()->add_help_tab([
            'id'      => 'remote-ips-proxies',
            'title'   => $this->__['wp-fail2ban-proxies'],
            'content' => sprintf(
                $fmt,
                $this->__['remote-ips-proxies'],
                __('A list of IPv4 addresses in CIDR notation. The list of CloudFlare IPs can be found <a href="https://www.cloudflare.com/ips-v4" rel="noopener" target="_blank">here</a>', 'wp-fail2ban'),
                __('<strong>NB:</strong> IPv6 is not yet supported.', 'wp-fail2ban'),
                $this->doc_link('WP_FAIL2BAN_PROXIES')
            )
        ]);
        parent::current_screen();
    }

    /**
     * Section blurb.
     *
     * @since 4.0.0
     */
    public function section()
    {
        echo '';
    }

    /**
     * Helper - multi-line string from proxies list.
     *
     * @since 4.3.0
     *
     * @return string
     */
    protected function proxies_value()
    {
        $proxies = Config::get('WP_FAIL2BAN_PROXIES');
        return (is_array($proxies))
            ? join("\n", $proxies)
            : join("\n", array_map('trim', explode(',', $proxies)));
    }

    /**
     * Proxies.
     *
     * @since 4.3.0     Refactored.
     * @since 4.0.0
     */
    public function proxies()
    {
        printf(
            '<fieldset><textarea class="code" cols="20" rows="10" disabled="disabled">%s</textarea></fieldset>',
            esc_html($this->proxies_value())
        );
        $this->description('WP_FAIL2BAN_PROXIES');
    }
}

