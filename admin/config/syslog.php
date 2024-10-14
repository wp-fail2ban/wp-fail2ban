<?php declare(strict_types=1);
/**
 * Settings - syslog
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Tab: Syslog
 *
 * @since 4.0.0
 */
class TabSyslog extends TabBase
{
    /**
     * Settings page slug
     *
     * @since 4.3.2.1
     */
    const SETTINGS_PAGE = 'wp-fail2ban-syslog';

    /**
     * Override Docs link
     *
     * @since 4.3.2.1
     */
    const HELP_LINK_DOCS = 'https://life-with.wp-fail2ban.com/core/configuration/settings/syslog/';
    /**
     * Override Reference link
     *
     * @since 4.3.2.1
     */
    const HELP_LINK_REFERENCE = 'https://docs.wp-fail2ban.com/en/'.WP_FAIL2BAN_VER2.'/defines/syslog.html';

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        $this->__['wp-fail2ban-connection']     = __('Connection',      'wp-fail2ban');
        $this->__['syslog-connection-options']  = __('Options',         'wp-fail2ban');
        $this->__['wp-fail2ban-workarounds']    = __('Workarounds',     'wp-fail2ban');
        $this->__['syslog-workarounds']         = __('Options',         'wp-fail2ban');
        $this->__['workarounds-short-tag']      = __('Short Tag',       'wp-fail2ban');
        $this->__['workarounds-specify-host']   = __('Specify Host',    'wp-fail2ban');
        $this->__['workarounds-truncate_host']  = __('Truncate Host',   'wp-fail2ban');
        // phpcs:enable

        parent::__construct('syslog', 'syslog');
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
        add_settings_section('wp-fail2ban-connection',  $this->__['wp-fail2ban-connection'],    [$this, 'sectionConnection'],   self::SETTINGS_PAGE);
        add_settings_field('syslog-connection-options', $this->__['syslog-connection-options'], [$this, 'connection'],          self::SETTINGS_PAGE,    'wp-fail2ban-connection');

        add_settings_section('wp-fail2ban-workarounds', $this->__['wp-fail2ban-workarounds'],   [$this, 'sectionWorkarounds'],  self::SETTINGS_PAGE);
        add_settings_field('syslog-workarounds',        $this->__['syslog-workarounds'],        [$this, 'workarounds'],         self::SETTINGS_PAGE,    'wp-fail2ban-workarounds');
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
        $pre = [
            __('Used to indicate what logging options will be used when generating a log message.', 'wp-fail2ban')
        ];
        $post = [
            $this->see_also(['WP_FAIL2BAN_OPENLOG_OPTIONS'])
        ];
        $fmt = <<< HTML
<table><style>th{text-align:left;vertical-align:top;}</style>
  <tr><th scope="row">LOG_CONS</th><td>%s</td></tr>
  <tr><th scope="row">LOG_NDELAY</th><td>%s</td></tr>
  <tr><th scope="row">LOG_ODELAY</th><td>%s</td></tr>
  <tr><th scope="row">LOG_PERROR</th><td>%s</td></tr>
  <tr><th scope="row">LOG_PID</th><td>%s</td></tr>
</table>
HTML;
        $content = sprintf(
            $fmt,
            __('if there is an error while sending data to the system logger, write directly to the system console', 'wp-fail2ban'),
            __('open the connection to the logger immediately', 'wp-fail2ban'),
            __('(default) delay opening the connection until the first message is logged', 'wp-fail2ban'),
            __('print log message also to standard error', 'wp-fail2ban'),
            __('include PID with each message', 'wp-fail2ban')
        );
        $this->add_help_tab('wp-fail2ban-connection', $content, $pre, $post);

        $this->add_help_tab('wp-fail2ban-workarounds', [
            $this->help_entry('workarounds-short-tag', [
                __('Some syslog implementations assume that the first part of the message (the tag) won&lsquo;t exceed some (small) number of characters. This option tells <em>WPf2b</em> to use <tt>wp</tt> instead of <tt>wordpress</tt>, thereby saving 7 characters; this may be enough to make syslog happy.', 'wp-fail2ban')
            ]),
            $this->help_entry('workarounds-specify-host', [
                sprintf(
                    /* translators: %s: <a href> internals */
                    __('"Short Tag" may not be enough, so this allows you to specify the hostname. See the <a %s>documentation</a> for more details.', 'wp-fail2ban'),
                    'href="https://docs.wp-fail2ban.com/en/___WPF2BVER___/defines/constants/WP_FAIL2BAN_HTTP_HOST.html" target="_blank">'
                )
            ]),
            $this->help_entry('workarounds-truncate_host', [
                __('When all else fails, this allows you to truncate the hostname after a number of characters.', 'wp-fail2ban'),
                __('<b>N.B.</b> This may be removed in a future release; it was broken prior to 4.3 and there were no bug reports, so it seems likely absolutely no-one is using it.', 'wp-fail2ban'),
                $this->see_also(['WP_FAIL2BAN_TRUNCATE_HOST'])
            ])
        ], [
            sprintf(
                /* translators: %s: <a href> internals */
                __('<tt>syslog</tt> was only <a %s>standardised</a> in 2009, so unfortunately there are still implementations that need some help.', 'wp-fail2ban'),
                'href="https://tools.ietf.org/html/rfc5424" target="_blank">'
            ),
            __('By far the most common limitation is the length of the initial information fields; these options provide ways to shorten the data in those fields.', 'wp-fail2ban'),
        ]);

        parent::current_screen();
    }

    /**
     * Connection section blurb.
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function sectionConnection(): void
    {
        echo '';
    }

    /**
     * Connection.
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0    Refactor to premium.
     * @since  4.0.0
     *
     * @return void
     */
    public function connection(): void
    {
        $options = Config::get('WP_FAIL2BAN_OPENLOG_OPTIONS');
        $fmt = <<< HTML
<fieldset>
  <label><input type="checkbox" disabled="disabled" %s> <code>LOG_CONS</code></label><br>
  <label><input type="checkbox" disabled="disabled" %s> <code>LOG_PERROR</code></label><br>
  <label><input type="checkbox" disabled="disabled" %s> <code>LOG_PID</code> <em>(%s)</em></label><br>
  <label><input type="radio" disabled="disabled" %s> <code>LOG_NDELAY</code> <em>(%s)</em></label><br>
  <label><input type="radio" disabled="disabled" %s> <code>LOG_ODELAY</code></label>
</fieldset>
HTML;
        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing, PSR2.Methods.FunctionCallSignature.MultipleArguments
        printf(
            $fmt,
            checked($options & LOG_CONS,   LOG_CONS,   false),
            checked($options & LOG_PERROR, LOG_PERROR, false),
            checked($options & LOG_PID,    LOG_PID,    false), __('default'),
            checked($options & LOG_NDELAY, LOG_NDELAY, false), __('default'),
            checked($options & LOG_ODELAY, LOG_ODELAY, false)
        );
        // phpcs:enable
    }

    /**
     * Workarounds section blurb.
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function sectionWorkarounds(): void
    {
        echo '';
    }

    /**
     * Workarounds.
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0    Refactor to premium.
     * @since  4.0.0
     *
     * @return void
     */
    public function workarounds(): void
    {
        $fmt = <<< HTML
<fieldset>
<label><input type="checkbox" disabled="disabled" %s> %s</label>
<br>
<label><input type="checkbox" disabled="disabled" %s> %s</label>
<br>
<label><input type="checkbox" disabled="disabled" %s> %s</label>
</fieldset>
HTML;
        printf(
            $fmt,
            checked(Config::get('WP_FAIL2BAN_SYSLOG_SHORT_TAG'), true, false),
            __('Short Tag', 'wp-fail2ban'),
            checked(Config::get('WP_FAIL2BAN_HTTP_HOST'), true, false),
            __('Specify Host', 'wp-fail2ban'),
            checked(Config::get('WP_FAIL2BAN_TRUNCATE_HOST'), true, false),
            __('Truncate Host', 'wp-fail2ban')
        );
    }
}

