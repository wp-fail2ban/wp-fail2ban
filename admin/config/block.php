<?php
/**
 * Settings - Block
 *
 * @package wp-fail2ban
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
     * @since 4.0.0
     */
    public function admin_init()
    {
        // phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
        add_settings_section('wp-fail2ban-users', $this->__['users'],            [$this, 'section'],         'wp-fail2ban-block');
        add_settings_field('user-enumeration',    $this->__['user-enumeration'], [$this, 'userEnumeration'], 'wp-fail2ban-block', 'wp-fail2ban-users');
        add_settings_field('blacklist',           $this->__['blacklist'],        [$this, 'users'],           'wp-fail2ban-block', 'wp-fail2ban-users');
        add_settings_field('username-login',      $this->__['username-login'],   [$this, 'usernames'],       'wp-fail2ban-block', 'wp-fail2ban-users');
        // phpcs:enable
    }

    /**
     * {*inheritDoc}
     *
     * @since 4.3.0
     */
    public function current_screen()
    {
        $fmt = <<<___FMT___
<dl><style>dt{font-weight:bold;}</style>
  <dt>%s</dt>
  <dd><p>%s</p><p>%s</p><p>%s</p>%s</dd>
  <dt>%s</dt>
  <dd><p>%s</p><p>%s</p>%s</dd>
  <dt>%s</dt>
  <dd><p>%s</p><p>%s</p>%s</dd>
</dl>
___FMT___;
        get_current_screen()->add_help_tab([
            'id'      => 'users',
            'title'   => $this->__['users'],
            'content' => sprintf(
                $fmt,
                $this->__['user-enumeration'],
                __('Automated brute-force attacks ("bots") typically start by getting a list of valid usernames ("user enumeration").', 'wp-fail2ban'),
                __('Blocking user enumeration can force attackers to guess usernames, making these attacks much less likely to succeed.', 'wp-fail2ban'),
                __('<strong>N.B.</strong> Some Themes "leak" usernames (for example, via Author profile pages); see <strong>Block username logins</strong> for an alternative.', 'wp-fail2ban'),
                $this->see_also(['WP_FAIL2BAN_BLOCK_USER_ENUMERATION']),
                $this->__['blacklist'],
                __('Automated brute-force attacks ("bots") will often use well-known usernames, e.g. <tt>admin</tt>.', 'wp-fail2ban'),
                __('Blacklisted usernames are blocked early in the login process, reducing server load.', 'wp-fail2ban'),
                $this->see_also(['WP_FAIL2BAN_BLOCKED_USERS']),
                $this->__['username-login'],
                __('It is sometimes not possible to block user enumeration (for example, if your theme provides Author profiles). An alternative is to require users to login with their email address.', 'wp-fail2ban'),
                __('<strong>N.B.</strong> This also applies to Blacklisted Usernames; you must list <em>email addresses</em>, not usernames.', 'wp-fail2ban'),
                $this->see_also(['WP_FAIL2BAN_BLOCK_USERNAME_LOGIN'])
            )
        ]);

        parent::current_screen();
    }

    /**
     * {@inheritDoc}
     *
     * @since 4.0.0
     */
    public function section()
    {
        echo '';
    }

    /**
     * User Enumeration
     *
     * @since 4.0.0
     */
    public function userEnumeration()
    {
        $this->checkbox('WP_FAIL2BAN_BLOCK_USER_ENUMERATION');
    }

    /**
     * Blocked usernames
     *
     * @since 4.0.0
     */
    public function users()
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
     * @since 4.3.0
     */
    public function usernames()
    {
        $this->checkbox('WP_FAIL2BAN_BLOCK_USERNAME_LOGIN');
    }
}

