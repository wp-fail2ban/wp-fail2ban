<?php
/**
 * Settings - Logging
 *
 * @package wp-fail2ban
 * @since   4.0.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Tab: Logging
 *
 * @since 4.0.0
 */
class TabLogging extends TabLoggingBase
{
    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        $this->__['what-where']         = __('What & Where',        'wp-fail2ban');
        $this->__['authentication']     = __('Authentication',      'wp-fail2ban');
        $this->__['comments']           = __('Comments',            'wp-fail2ban');
        $this->__['spam']               = __('Spam',                'wp-fail2ban');
        $this->__['password-request']   = __('Password Requests',   'wp-fail2ban');
        $this->__['pingbacks']          = __('Pingbacks',           'wp-fail2ban');
        // phpcs:enable

        parent::__construct('logging', __('Logging', 'wp-fail2ban'));
    }

    /**
     * {@inheritDoc}
     *
     * @since 4.0.0
     */
    public function admin_init()
    {
        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        add_settings_section('wp-fail2ban-logging',         $this->__['what-where'],        [$this, 'sectionWhatWhere'],'wp-fail2ban-logging');
        add_settings_field('logging-log-authentication',    $this->__['authentication'],    [$this, 'authentication'],  'wp-fail2ban-logging', 'wp-fail2ban-logging');
        add_settings_field('logging-log-comments',          $this->__['comments'],          [$this, 'comments'],        'wp-fail2ban-logging', 'wp-fail2ban-logging');
        add_settings_field('logging-log-spam',              $this->__['spam'],              [$this, 'spam'],            'wp-fail2ban-logging', 'wp-fail2ban-logging');
        add_settings_field('logging-log-password-request',  $this->__['password-request'],  [$this, 'passwordRequest'], 'wp-fail2ban-logging', 'wp-fail2ban-logging');
        add_settings_field('logging-log-pingbacks',         $this->__['pingbacks'],         [$this, 'pingbacks'],       'wp-fail2ban-logging', 'wp-fail2ban-logging');
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
  <dt>%s</dt><dd>%s</dd>
  <dt>%s</dt><dd>%s</dd>
  <dt>%s</dt><dd>%s</dd>
  <dt>%s</dt><dd>%s</dd>
  <dt>%s</dt><dd>%s</dd>
</dl>
___FMT___;
        get_current_screen()->add_help_tab([
            'id'      => 'what-where',
            'title'   => $this->__['what-where'],
            'content' => sprintf(
                $fmt,
                $this->__['authentication'],
                $this->see_also([
                    'WP_FAIL2BAN_AUTH_LOG'
                ], false),
                $this->__['comments'],
                $this->see_also([
                    'WP_FAIL2BAN_LOG_COMMENTS',
                    'WP_FAIL2BAN_LOG_COMMENTS_EXTRA',
                    'WP_FAIL2BAN_COMMENT_EXTRA_LOG'
                ], false),
                $this->__['spam'],
                $this->see_also([
                    'WP_FAIL2BAN_LOG_SPAM',
                    'WP_FAIL2BAN_SPAM_LOG'
                ], false),
                $this->__['password-request'],
                $this->see_also([
                    'WP_FAIL2BAN_LOG_PASSWORD_REQUEST',
                    'WP_FAIL2BAN_PASSWORD_REQUEST_LOG'
                ], false),
                $this->__['pingbacks'],
                $this->see_also([
                    'WP_FAIL2BAN_LOG_PINGBACKS',
                    'WP_FAIL2BAN_PINGBACK_LOG'
                ], false)
            )
        ]);
        parent::current_screen();
    }

    /**
     * Section summary.
     *
     * @since 4.0.0
     */
    public function sectionWhatWhere()
    {
        // noop
    }

    /**
     * Authentication.
     *
     * @since 4.0.0
     */
    public function authentication()
    {
        printf(
            '<label>%s: %s</label><p class="description">%s</p>',
            __('Use facility', 'wp-fail2ban'),
            $this->getLogFacilities('WP_FAIL2BAN_AUTH_LOG', true),
            Config::desc('WP_FAIL2BAN_AUTH_LOG')
        );
    }

    /**
     * Comments.
     *
     * @since 4.0.0
     */
    public function comments()
    {
        add_filter('wp_fail2ban_log_WP_FAIL2BAN_LOG_COMMENTS', [$this, 'commentsExtra'], 10, 3);

        $this->log(
            'WP_FAIL2BAN_LOG_COMMENTS',
            'WP_FAIL2BAN_COMMENT_LOG',
            ['comments-extra', 'logging-comments-extra-facility']
        );
    }

    /**
     * Comments extra helper - checked.
     *
     * @since 4.0.0
     *
     * @param int   $value  Value to check
     */
    protected function commentExtraChecked($value)
    {
        return checked($value == ($value & Config::get('WP_FAIL2BAN_LOG_COMMENTS_EXTRA')), true, false);
    }

    /**
     * Comments extra helper - disabled.
     *
     * @since 4.0.0
     */
    protected function commentExtraDisabled()
    {
        return 'disabled="disabled';
    }

    /**
     * Comments extra.
     *
     * @since 4.0.0
     *
     * @param string $html          HTML prefixed to output
     * @param string $define_name   Not used
     * @param string $define_log    Not used
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function commentsExtra($html, $define_name, $define_log)
    {
        $fmt = <<< ___HTML___
<table>
  <tr>
    <th>%s</th>
    <td>
      <fieldset id="comments-extra" disabled="disabled">
        <label><input type="checkbox" %s> %s</label><br>
        <label><input type="checkbox" %s> %s</label><br>
        <label><input type="checkbox" %s> %s</label><br>
        <label><input type="checkbox" %s> %s</label><br>
        <label><input type="checkbox" %s> %s</label>
      </fieldset>
    </td>
  </tr>
  <tr>
    <th>%s</th>
    <td>%s</td>
  </tr>
</table>
___HTML___;

        return $html.sprintf(
            $fmt,
            __('Also log:', 'wp-fail2ban'),
            $this->commentExtraChecked(WPF2B_EVENT_COMMENT_NOT_FOUND),
            __('Post not found', 'wp-fail2ban'),
            $this->commentExtraChecked(WPF2B_EVENT_COMMENT_CLOSED),
            __('Comments closed', 'wp-fail2ban'),
            $this->commentExtraChecked(WPF2B_EVENT_COMMENT_TRASH),
            __('Trash post', 'wp-fail2ban'),
            $this->commentExtraChecked(WPF2B_EVENT_COMMENT_DRAFT),
            __('Draft post', 'wp-fail2ban'),
            $this->commentExtraChecked(WPF2B_EVENT_COMMENT_PASSWORD),
            __('Password-protected post', 'wp-fail2ban'),
            __('Use facility:', 'wp-fail2ban'),
            $this->getLogFacilities('WP_FAIL2BAN_COMMENT_EXTRA_LOG', false)
        );
    }

    /**
     * Password request
     *
     * @since 4.0.0
     */
    public function passwordRequest()
    {
        $this->log(
            'WP_FAIL2BAN_LOG_PASSWORD_REQUEST',
            'WP_FAIL2BAN_PASSWORD_REQUEST_LOG'
        );
    }

    /**
     * Pingbacks
     *
     * @since 4.0.0
     */
    public function pingbacks()
    {
        $this->log(
            'WP_FAIL2BAN_LOG_PINGBACKS',
            'WP_FAIL2BAN_PINGBACK_LOG'
        );
    }

    /**
     * Spam
     *
     * @since 4.0.0
     */
    public function spam()
    {
        $this->log(
            'WP_FAIL2BAN_LOG_SPAM',
            'WP_FAIL2BAN_SPAM_LOG'
        );
    }
}

