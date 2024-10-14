<?php declare(strict_types=1);
/**
 * Settings - Block
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Tab: Block
 *
 * @since 4.0.0
 */
class TabBlock extends TabBase
{
    /**
     * Settings page slug
     *
     * @since 4.3.2.1
     */
    const SETTINGS_PAGE = 'wp-fail2ban-block';

    /**
     * Override Docs link
     *
     * @since 4.3.2.1
     */
    const HELP_LINK_DOCS = 'https://life-with.wp-fail2ban.com/core/configuration/settings/block/';
    /**
     * Override Reference link
     *
     * @since 4.3.2.1
     */
    const HELP_LINK_REFERENCE = 'https://docs.wp-fail2ban.com/en/'.WP_FAIL2BAN_VER2.'/defines/block.html';

    /**
     * {@inheritDoc}
     *
     * @since 4.0.0
     */
    public function __construct()
    {
        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        $this->__['users']              = __('Users',                  'wp-fail2ban');
        $this->__['user-enumeration']   = __('Block User Enumeration', 'wp-fail2ban');
        $this->__['blacklist']          = __('Blacklisted Usernames',  'wp-fail2ban');
        $this->__['username-login']     = __('Block username logins',  'wp-fail2ban');
        // phpcs:enable

        parent::__construct('block', __('Block', 'wp-fail2ban'));
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
        add_settings_section('wp-fail2ban-users', $this->__['users'],            [$this, 'section'],         self::SETTINGS_PAGE);
        add_settings_field('user-enumeration',    $this->__['user-enumeration'], [$this, 'userEnumeration'], self::SETTINGS_PAGE, 'wp-fail2ban-users');
        add_settings_field('blacklist',           $this->__['blacklist'],        [$this, 'users'],           self::SETTINGS_PAGE, 'wp-fail2ban-users');
        add_settings_field('username-login',      $this->__['username-login'],   [$this, 'usernames'],       self::SETTINGS_PAGE, 'wp-fail2ban-users');
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
        $this->add_help_tab('users', [
            $this->help_entry('user-enumeration', [
                __('Automated brute-force attacks ("bots") typically start by getting a list of valid usernames ("user enumeration").', 'wp-fail2ban'),
                __('Blocking user enumeration can force attackers to guess usernames, making these attacks much less likely to succeed.', 'wp-fail2ban'),
                sprintf(
                    /* translators: %s: 'Block username logins' */
                    __('<b>N.B.</b> Some Themes "leak" usernames (for example, via Author profile pages); see %s for an alternative.', 'wp-fail2ban'),
                    sprintf('<b>%s</b>', $this->__['username-login'])
                ),
                $this->see_also(['WP_FAIL2BAN_BLOCK_USER_ENUMERATION'])
            ]),
            $this->help_entry('blacklist', [
                __('Automated brute-force attacks ("bots") will often use well-known usernames, e.g. <tt>admin</tt>.', 'wp-fail2ban'),
                __('Blacklisted usernames are blocked early in the login process, reducing server load.', 'wp-fail2ban'),
                $this->see_also(['WP_FAIL2BAN_BLOCKED_USERS'])
            ]),
            $this->help_entry('username-login', [
                __('It is sometimes not possible to block user enumeration (for example, if your theme provides Author profiles). An alternative is to require users to login with their email address.', 'wp-fail2ban'),
                __('<b>N.B.</b> This also applies to Blacklisted Usernames; you must list <em>email addresses</em>, not usernames.', 'wp-fail2ban'),
                $this->see_also(['WP_FAIL2BAN_BLOCK_USERNAME_LOGIN'])
            ])
        ]);

        parent::current_screen();
    }

    /**
     * {@inheritDoc}
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     */
    public function section(): void
    {
        echo '';
    }

    /**
     * User Enumeration
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function userEnumeration(): void
    {
        $this->checkbox('WP_FAIL2BAN_BLOCK_USER_ENUMERATION');
    }

    /**
     * Blocked usernames
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return void
     */
    public function users(): void
    {
        if (defined('WP_FAIL2BAN_BLOCKED_USERS')) {
            if (is_array(WP_FAIL2BAN_BLOCKED_USERS)) {
                $value = join(', ', WP_FAIL2BAN_BLOCKED_USERS);
            } else {
                $value = WP_FAIL2BAN_BLOCKED_USERS;
            }
        } else {
            $value = '';
        }
        printf(
            '<input class="regular-text" type="text" disabled="disabled" value="%s">',
            esc_attr($value)
        );
    }

    /**
     * Block username logins
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @return void
     */
    public function usernames(): void
    {
        $this->checkbox('WP_FAIL2BAN_BLOCK_USERNAME_LOGIN');
    }
}

