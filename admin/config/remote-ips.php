<?php declare(strict_types=1);
/**
 * Settings - Remote IPs
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
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
     * Settings page slug
     *
     * @since 4.3.2.1
     */
    const SETTINGS_PAGE = 'wp-fail2ban-remote-ips';

    /**
     * Override Docs link
     *
     * @since 4.3.2.1
     */
    const HELP_LINK_DOCS = 'https://life-with.wp-fail2ban.com/core/configuration/settings/remote-ips/';
    /**
     * Override Reference link
     *
     * @since 4.3.2.1
     */
    const HELP_LINK_REFERENCE = 'https://docs.wp-fail2ban.com/en/'.WP_FAIL2BAN_VER2.'/defines/remote-ips.html';

    /**
     * {@inheritDoc}
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        $this->__['wp-fail2ban-proxies']    = __('Proxies', 'wp-fail2ban');
        $this->__['remote-ips-proxies']     = __('Trusted IPs', 'wp-fail2ban');
        // phpcs:enable

        parent::__construct('remote-ips', __('Remote IPs', 'wp-fail2ban'));
    }

    /**
     * {@inheritDoc}
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function admin_init(): void
    {
        do_action(__METHOD__.'.before');

        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        add_settings_section('wp-fail2ban-proxies', $this->__['wp-fail2ban-proxies'],   [$this, 'section'], self::SETTINGS_PAGE);
        add_settings_field('remote-ips-proxies',    $this->__['remote-ips-proxies'],    [$this, 'proxies'], self::SETTINGS_PAGE,    'wp-fail2ban-proxies');
        // phpcs:enable

        do_action(__METHOD__.'.after');
    }

    /**
     * {@inheritDoc}
     *
     * @since  4.4.0    Add return type
     * @since  4.3.3.0  Refactor
     * @since  4.3.0
     *
     * @return void
     */
    public function current_screen(): void
    {
        $this->add_help_tab('wp-fail2ban-proxies', [
            $this->help_entry('remote-ips-proxies', [
                sprintf(
                    /* translators: 1, 2: <a href> internals, links to Cloudflare IP lists */
                    __('A list of IP addresses in full CIDR notation. The list of Cloudflare IPs can be found <a %1$s>here</a> (IPv4) and <a %2$s>here</a> (IPv6).', 'wp-fail2ban'),
                    'href="https://www.cloudflare.com/ips-v4" rel="noopener" target="_blank"',
                    'href="https://www.cloudflare.com/ips-v6" rel="noopener" target="_blank"'
                ),
                __('<b>N.B.</b> Abbreviated IPv4 CIDR like <code>127/8</code> is not supported.', 'wp-fail2ban'),
                $this->doc_link('WP_FAIL2BAN_PROXIES')
            ])
        ]);

        parent::current_screen();
    }

    /**
     * Section blurb.
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function section(): void
    {
        echo '';
    }

    /**
     * Proxies.
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0    Refactored.
     * @since  4.0.0
     *
     * @return void
     */
    public function proxies(): void
    {
        printf(
            '<fieldset><textarea class="code" cols="20" rows="10" disabled="disabled">%s</textarea></fieldset>',
            esc_html($this->proxies_value())
        );
        $this->description('WP_FAIL2BAN_PROXIES');
    }

    /**
     * Helper - multi-line string from proxies list.
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @return string
     */
    protected function proxies_value(): string
    {
        $proxies = Config::get('WP_FAIL2BAN_PROXIES');
        return (is_array($proxies))
            ? join("\n", $proxies)
            : join("\n", array_map('trim', explode(',', $proxies)));
    }
}

