<?php declare(strict_types=1);
/**
 * Settings - Logging
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
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
     * Settings page slug
     *
     * @since 4.3.2.1
     */
    const SETTINGS_PAGE = 'wp-fail2ban-logging';

    /**
     * Override Docs link
     *
     * @since 4.3.2.1
     */
    const HELP_LINK_DOCS = 'https://life-with.wp-fail2ban.com/core/configuration/settings/logging/';
    /**
     * Override Reference link
     *
     * @since 4.3.2.1
     */
    const HELP_LINK_REFERENCE = 'https://docs.wp-fail2ban.com/en/'.WP_FAIL2BAN_VER2.'/defines/logging.html';

    /**
     * {@inheritDoc}
     */
    public function __construct()
    {
        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        $this->__['what-where']         = __('What & Where',        'wp-fail2ban');
        $this->__['authentication']     = __('Authentication',      'wp-fail2ban');
        $this->__['comments']           = __('Comments',            'wp-fail2ban');
        $this->__['comment-attempts']   = __('Comment Attempts',    'wp-fail2ban');
        $this->__['spam']               = __('Spam',                'wp-fail2ban');
        $this->__['password-request']   = __('Password Requests',   'wp-fail2ban');
        $this->__['pingbacks']          = __('Pingbacks',           'wp-fail2ban');
        // phpcs:enable

        parent::__construct('logging', __('Logging', 'wp-fail2ban'));
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
        add_settings_section('wp-fail2ban-logging',         $this->__['what-where'],        [$this, 'sectionWhatWhere'],    self::SETTINGS_PAGE);
        add_settings_field('logging-log-authentication',    $this->__['authentication'],    [$this, 'authentication'],      self::SETTINGS_PAGE,    'wp-fail2ban-logging');
        add_settings_field('logging-log-comments',          $this->__['comments'],          [$this, 'comments'],            self::SETTINGS_PAGE,    'wp-fail2ban-logging');
        add_settings_field('logging-log-comment-attempts',  $this->__['comment-attempts'],  [$this, 'commentAttempts'],     self::SETTINGS_PAGE,    'wp-fail2ban-logging');
        add_settings_field('logging-log-spam',              $this->__['spam'],              [$this, 'spam'],                self::SETTINGS_PAGE,    'wp-fail2ban-logging');
        add_settings_field('logging-log-password-request',  $this->__['password-request'],  [$this, 'passwordRequest'],     self::SETTINGS_PAGE,    'wp-fail2ban-logging');
        add_settings_field('logging-log-pingbacks',         $this->__['pingbacks'],         [$this, 'pingbacks'],           self::SETTINGS_PAGE,    'wp-fail2ban-logging');
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
        $this->add_help_tab('what-where', [
            $this->help_entry('authentication', [
                $this->see_also([
                    'WP_FAIL2BAN_AUTH_LOG'
                ], false)
            ]),
            $this->help_entry('comments', [
                $this->see_also([
                    'WP_FAIL2BAN_LOG_COMMENTS',
                    'WP_FAIL2BAN_LOG_COMMENT_ATTEMPTS',
                    'WP_FAIL2BAN_COMMENT_ATTEMPT_LOG'
                ], false)
            ]),
            $this->help_entry('spam', [
                $this->see_also([
                    'WP_FAIL2BAN_LOG_SPAM',
                    'WP_FAIL2BAN_SPAM_LOG'
                ], false)
            ]),
            $this->help_entry('password-request', [
                $this->see_also([
                    'WP_FAIL2BAN_LOG_PASSWORD_REQUEST',
                    'WP_FAIL2BAN_PASSWORD_REQUEST_LOG'
                ], false)
            ]),
            $this->help_entry('pingbacks', [
                $this->see_also([
                    'WP_FAIL2BAN_LOG_PINGBACKS',
                    'WP_FAIL2BAN_PINGBACK_LOG'
                ], false)
            ])
        ]);

        parent::current_screen();
    }

    /**
     * Section summary.
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function sectionWhatWhere(): void
    {
        // noop
    }

    /**
     * Authentication.
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function authentication(): void
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
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function comments(): void
    {
        $this->log(
            'WP_FAIL2BAN_LOG_COMMENTS',
            'WP_FAIL2BAN_COMMENT_LOG'
        );
    }

    /**
     * Attempted Comments.
     *
     * @since  5.0.0
     *
     * @return void
     */
    public function commentAttempts(): void
    {
        $this->log(
            'WP_FAIL2BAN_LOG_COMMENT_ATTEMPTS',
            'WP_FAIL2BAN_COMMENT_ATTEMPT_LOG'
        );
    }

    /**
     * Password request
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function passwordRequest(): void
    {
        $this->log(
            'WP_FAIL2BAN_LOG_PASSWORD_REQUEST',
            'WP_FAIL2BAN_PASSWORD_REQUEST_LOG'
        );
    }

    /**
     * Pingbacks
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function pingbacks(): void
    {
        $this->log(
            'WP_FAIL2BAN_LOG_PINGBACKS',
            'WP_FAIL2BAN_PINGBACK_LOG'
        );
    }

    /**
     * Spam
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function spam(): void
    {
        $this->log(
            'WP_FAIL2BAN_LOG_SPAM',
            'WP_FAIL2BAN_SPAM_LOG'
        );
    }
}

