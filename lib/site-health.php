<?php declare(strict_types=1);
/**
 * WP fail2ban Site Health
 *
 * @package wp-fail2ban
 * @since   5.0.0
 * @php     7.4
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

class SiteHealth
{
    const PREFIX = '[WP fail2ban] ';

    const FAIL2BAN_PATHS = [
        '/etc/fail2ban',
        '/usr/local/etc/fail2ban'
    ];

    protected static $instance = null;

    /**
     * Return an instance of the SiteHealth class, or create one if none exist yet.
     *
     * @since  5.0.0
     *
     * @return SiteHealth
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new SiteHealth();
        }
        return self::$instance;
    }

    /**
     * Should we skip checking the filters?
     *
     * @since  5.2.1
     *
     * @return bool
     */
    public static function should_skip_filters(): bool
    {
        if (Config::get('WP_FAIL2BAN_SITE_HEALTH_SKIP_FILTERS')) {
            return true;
        }

        if (!empty($open_basedir = ini_get('open_basedir'))) {
            $path = \untrailingslashit(self::get_fail2ban_path());

            return (false !== strpos($open_basedir, $path));
        }

        return false;
    }

    /**
     * @see \WP_Site_Health::get_tests()
     *
     * @since  5.0.0
     *
     * @return array    The list of tests to run.
     */
    public static function get_tests(array $tests): array
    {
        $instance = self::get_instance();

        $tests['direct']['wp_fail2ban_mu_ensure_active'] = [
            'label' => 'Ensure standard plugin activated when Must-Use',
            'test'  => [$instance, 'get_test_mu_ensure_activated']
        ];

        $tests['direct']['wp_fail2ban_log_comments_extra_deprecaed'] = [
            'label' => 'WP_FAIL2BAN_LOG_COMMENTS_EXTRA deprecated',
            'test'  => [$instance, 'get_test_log_comments_extra_deprecated']
        ];
        $tests['direct']['wp_fail2ban_comments_extra_log_deprecaed'] = [
            'label' => 'WP_FAIL2BAN_COMMENTS_EXTRA_LOG deprecated',
            'test'  => [$instance, 'get_test_comments_extra_log_deprecated']
        ];

        $tests['direct']['wp_fail2ban_running'] = [
            'label' => 'fail2ban running',
            'test'  => [$instance, 'get_test_fail2ban_running']
            ];
        if (!self::should_skip_filters()) {
            $tests['direct']['wp_fail2ban_filter_obsolete'] = [
                'label' => 'WP fail2ban obsolete filters',
                'test'  => [$instance, 'get_test_filter_obsolete']
            ];
            $tests['direct']['wp_fail2ban_filter_modified'] = [
                'label' => 'WP fail2ban modified filters',
                'test'  => [$instance, 'get_test_filter_modified']
            ];
            $tests['direct']['wp_fail2ban_filter_missing'] = [
                'label' => 'WP fail2ban missing filters',
                'test'  => [$instance, 'get_test_filter_missing']
            ];
        }
        if (!defined('WP_FAIL2BAN_SITE_HEALTH_SKIP_RECOMMEND') ||
            ( is_array(WP_FAIL2BAN_SITE_HEALTH_SKIP_RECOMMEND) &&
              true !== WP_FAIL2BAN_SITE_HEALTH_SKIP_RECOMMEND['addon']['wpf2b-addon-blocklist'] ?? false))
        {
            $tests['direct']['wp_fail2ban_blocklist_installed'] = [
                'label' => 'WP fail2ban Blocklist installed',
                'test'  => [$instance, 'get_test_blocklist_installed']
            ];
        }
        if (!defined('WP_FAIL2BAN_SITE_HEALTH_SKIP_RECOMMEND') ||
            ( is_array(WP_FAIL2BAN_SITE_HEALTH_SKIP_RECOMMEND) &&
              true !== WP_FAIL2BAN_SITE_HEALTH_SKIP_RECOMMEND['addon']['wp-fail2ban-addon-contact-form-7'] ?? false))
        {
            $tests['direct']['wp_fail2ban_cf7_installed'] = [
                'label' => 'Add-on for Contact Form 7 installed',
                'test'  => [$instance, 'get_test_cf7_installed']
            ];
        }
        if (!defined('WP_FAIL2BAN_SITE_HEALTH_SKIP_RECOMMEND') ||
            ( is_array(WP_FAIL2BAN_SITE_HEALTH_SKIP_RECOMMEND) &&
              true !== WP_FAIL2BAN_SITE_HEALTH_SKIP_RECOMMEND['addon']['wp-fail2ban-addon-gravity-forms'] ?? false))
        {
            $tests['direct']['wp_fail2ban_gf_installed'] = [
                'label' => 'Add-on for Gravity Forms installed',
                'test'  => [$instance, 'get_test_gf_installed']
            ];
        }

        return $tests;
    }

    /**
     * Is the "normal" plugin activated if we're running as Must-Use?
     *
     * @since  4.4.0.8
     *
     * @return array    Empty
     */
    public function get_test_mu_ensure_activated()
    {
        foreach (get_mu_plugins() as $plugin => $data) {
            if (0 === strpos($data['Name'], 'WP fail2ban')) {
                // MU plugin
                //
                // Make sure the "normal" plugin is activated, if installed that way
                $plugin = plugin_basename(WP_FAIL2BAN_FILE);

                if (array_key_exists($plugin, get_plugins()) && !is_plugin_active($plugin)) {
                    activate_plugin(
                        $plugin,
                        '',     // don't redirect anywhere
                        false,
                        true    // don't call activation hooks
                    );
                }
                break;
            }
        }

        return false;
    }

    /**
     * Is WP_FAIL2BAN_LOG_COMMENTS_EXTRA defined?
     *
     * Constant has been deprecated.
     *
     * @since  5.0.0
     *
     * @return array    The test result.
     */
    public function get_test_log_comments_extra_deprecated()
    {
        if (Config::ndef('WP_FAIL2BAN_LOG_COMMENTS_EXTRA')) {
            return false;
        }

        return [
                           /* translators: %s: 'WP_FAIL2BAN_LOG_COMMENTS_EXTRA' (simplifies custom dictionary) */
            'label'     => self::PREFIX.sprintf(__('%s is deprecated', 'wp-fail2ban'), 'WP_FAIL2BAN_LOG_COMMENTS_EXTRA'),
            'status'    => 'critical',
            'badge'     => [
                'label' => __('Security'),
                'color' => 'blue'
            ],
            'description'   => sprintf(
                '<p>%s</p><p>%s</p>',
                sprintf(
                    /* translators: %s: 'WP_FAIL2BAN_LOG_COMMENT_ATTEMPTS' (simplifies custom dictionary) */
                    __('It has been replaced by %s - please update your configuration.', 'wp-fail2ban'),
                    sprintf(
                        '<a href="https://docs.wp-fail2ban.com/en/%s/defines/constants/WP_FAIL2BAN_LOG_COMMENT_ATTEMPTS.html"><tt>WP_FAIL2BAN_LOG_COMMENT_ATTEMPTS</tt></a>',
                        WP_FAIL2BAN_VER2
                    ),
                ),
                sprintf(
                    /* translators: %s: 'WP_FAIL2BAN_LOG_COMMENTS_EXTRA' (simplifies custom dictionary) */
                    __('%s will be removed in version 6.0.', 'wp-fail2ban'),
                    '<tt>WP_FAIL2BAN_LOG_COMMENTS_EXTRA</tt>'
                )
            ),
            'actions'       => '',
            'test'          => 'wp_fail2ban_log_comments_extra_deprecaed'
        ];
    }

    /**
     * Is WP_FAIL2BAN_COMMENTS_EXTRA_LOG defined?
     *
     * Constant has been deprecated.
     *
     * @since  5.0.0
     *
     * @return array    The test result.
     */
    public function get_test_comments_extra_log_deprecated()
    {
        if (Config::ndef('WP_FAIL2BAN_COMMENTS_EXTRA_LOG')) {
            return false;
        }

        return [
                           /* translators: %s: 'WP_FAIL2BAN_COMMENTS_EXTRA_LOG' (simplifies custom dictionary) */
            'label'     => self::PREFIX.sprintf(__('%s is deprecated', 'wp-fail2ban'), 'WP_FAIL2BAN_COMMENTS_EXTRA_LOG'),
            'status'    => 'critical',
            'badge'     => [
                'label' => __('Security'),
                'color' => 'blue'
            ],
            'description'   => sprintf(
                '<p>%s</p><p>%s</p>',
                sprintf(
                    /* translators: %s: 'WP_FAIL2BAN_COMMENT_ATTEMPT_LOG' (simplifies custom dictionary) */
                    __('It has been replaced by %s - please update your configuration.', 'wp-fail2ban'),
                    sprintf(
                        '<a href="https://docs.wp-fail2ban.com/en/%s/defines/constants/WP_FAIL2BAN_COMMENT_ATTEMPT_LOG.html"><tt>WP_FAIL2BAN_COMMENT_ATTEMPT_LOG</tt></a>',
                        WP_FAIL2BAN_VER2
                    ),
                ),
                sprintf(
                    /* translators: %s: 'WP_FAIL2BAN_COMMENTS_EXTRA_LOG' (simplifies custom dictionary) */
                    __('%s will be removed in version 6.0.', 'wp-fail2ban'),
                    '<tt>WP_FAIL2BAN_COMMENTS_EXTRA_LOG</tt>'
                )
            ),
            'actions'       => '',
            'test'          => 'wp_fail2ban_comments_extra_log_deprecaed'
        ];
    }

    /**
     * Attempt to find the fail2ban install path
     *
     * @since  5.0.0
     *
     * @param  string   $suffix Subdirectory to test for
     *
     * @return string|null  Existing path to fail2ban dir, or null if none found
     */
    public static function get_fail2ban_path(string $suffix = ''): ?string
    {
        $fail2ban_path = null;

        if (defined('WP_FAIL2BAN_INSTALL_PATH')) {
            $path = trailingslashit(WP_FAIL2BAN_INSTALL_PATH).$suffix;
            if (is_dir($path)) {
                $fail2ban_path = $path;
            }

        } else {
            $paths = array_map(function ($e) use ($suffix) {
                return trailingslashit($e).$suffix;
            }, self::FAIL2BAN_PATHS);

            foreach ($paths as $path) {
                if (is_dir($path)) {
                    $fail2ban_path = $path;
                    break;
                }
            }
        }

        return $fail2ban_path;
    }

    /**
     * Do the filters actually need to be updated?
     *
     * 5.0.x => 5.1.y: NO
     *
     * @since  5.1.0
     *
     * @param  string   $ver    Version of existing filter
     *
     * @return bool
     */
    protected function check_filter_needs_update(string $ver, string $filter, array &$reasons): ?bool
    {
        list($major, $minor, $patch) = explode('.', $ver);

        /* Specific version update logic */
        switch ($major) {
            case 4:
                switch ($minor) {
                    case 4:
                        $rv = null;

                        switch ($filter) {
                            case 'hard':
                                // [hard] Untrusted X-Forwarded-For header
                                if (count(Config::get('WP_FAIL2BAN_PROXIES'))) {
                                    $reasons[] = __('Untrusted proxies will not be blocked.', 'wp-fail2ban');
                                    $rv = true;
                                }
                                break;
                            case 'soft':
                                // [soft] Comment attempt on .* post \d+
                                if (Config::get('WP_FAIL2BAN_LOG_COMMENT_ATTEMPTS') ||
                                    Config::get('WP_FAIL2BAN_LOG_COMMENTS_EXTRA') > 0)
                                {
                                    $reasons[] = __('Attempted comments will not be blocked.', 'wp-fail2ban');
                                    $rv = true;
                                }
                                break;
                        }
                        return $rv;
                }
                break;
        }

        /* Always update for major version changes that aren't handled above */
        if ($major != WP_FAIL2BAN_VER_MAJOR) {
            return true;
        }

        return apply_filters(__METHOD__, false, $major, $minor, $patch);
    }

    /**
     * Check all the standard filters for obsolete version or modification
     *
     * @since  5.0.0
     *
     * @param  ?array   &$flags Summary of findings
     *
     * @return array|null   Results of the checks
     */
    protected function check_filters(?array &$flags): ?array
    {
        static $status = [
            'obsolete' => false,
            'custom' => false,
            'unknown' => false,
            'partial' => false
        ];
        static $failures = false;

        if (false === $failures) {
            if (null === ($filter_d = self::get_fail2ban_path('filter.d'))) {
                $failures = null;

            } else {
                $failures = [];
                $filter_files = [
                    'hard',
                    'soft',
                    'extra'
                ];

                foreach ($filter_files as $filter) {
                    $filter_file = "{$filter_d}/wordpress-{$filter}.conf";

                    // Exists and we can get the contents
                    if (is_readable($filter_file)) {
                        $installed_file = sha1_file($filter_file);
                        $local_file = sha1_file(WP_FAIL2BAN_DIR."/filters.d/wordpress-{$filter}.conf");

                        if ($installed_file == $local_file) {
                            // OK - identical

                        } elseif (array_key_exists($installed_file, WP_FAIL2BAN_HASHES) &&
                                  array_key_exists($filter, WP_FAIL2BAN_HASHES[$installed_file]))
                        {
                            $ver = WP_FAIL2BAN_HASHES[$installed_file][$filter];
                            $reasons = [];
                            switch ($this->check_filter_needs_update($ver, $filter, $reasons)) {
                                case true:
                                    $failures[$filter] = [
                                        'status' => 'obsolete',
                                        'version' => $ver,
                                        'reasons' => $reasons
                                    ];
                                    $status['obsolete'] = true;
                                    break;
                                case null:
                                    $failures[$filter] = [
                                        'status' => 'old',
                                        'version' => $ver
                                    ];
                                    $status['old'] = true;
                                    break;
                                case false:
                                    // OK - compatible
                                    break;
                            }

                        } else {
                            $failures[$filter] = [
                                'status' => 'custom',
                                'version' => null
                            ];
                            $status['custom'] = true;
                        }

                    // Exists, but can't get contents
                    } elseif (is_file($filter_file)) {
                        $failures[$filter] = [
                            'status' => 'unknown',
                            'version' => null
                        ];
                        $status['unknown'] = true;

                    // Does not exist
                    } else {
                        $failures[$filter] = [
                            'status' => 'missing',
                            'version' => null
                        ];
                        $status['partial'] = true;
                    }
                }
            }
        }

        $flags = $status;

        return $failures;
    }

    /**
     * Is fail2ban running?
     *
     * For now, just try systemctl.
     *
     * @since  5.1.0
     *
     * @return array    The test result.
     */
    public function get_test_fail2ban_running()
    {
        $results = [
            'label'     => __('fail2ban is running', 'wp-fail2ban'),
            'status'    => 'good',
            'badge'     => [
                'label' => __('Security'),
                'color' => 'blue'
            ],
            'description'   => sprintf('<p>%s</p>', __('<tt>fail2ban</tt> is running.', 'wp-fail2ban')),
            'actions'       => '',
            'test'          => 'wp_fail2ban_running'
        ];

        if (file_exists('/usr/bin/systemctl')) {
            $output = [];

            // get the active status; there is no output
            if (false === exec('/usr/bin/systemctl is-active --quiet fail2ban', $output, $rv)) {
                return false;
            }
            // get the status
            if (false === exec('/usr/bin/systemctl status --quiet fail2ban', $output)) {
                return false;
            }

            if ($rv) { // 0 is active
                $results['label']       = __('fail2ban is not running', 'wp-fail2ban');
                $results['status']      = 'critical';
                $results['description'] = sprintf(
                    /* translators: %s: fail2ban */
                    __('%s is not running - your server is unprotected.', 'wp-fail2ban'),
                    '<tt>fail2ban</tt>'
                );
                $results['actions']     = sprintf(
                    '<p><a href="https://life-with.wp-fail2ban.com/core/maintenance/fail2ban/starting/digitalocean-wordpress-droplet/" target="_blank" rel="noopener">%s<span class="dashicons dashicons-external"></span></a></p>',
                    sprintf(
                        /* translators: %s: fail2ban */
                        __('Enable %s', 'wp-fail2ban'),
                        '<tt>fail2ban</tt>'
                    )
                );
            }
            $results['description'] .= '<pre>'.join("\n", $output).'</pre>';

        } else {
            // for now don't try anything else
            return false;
        }

        $results['label'] = self::PREFIX.$results['label'];

        return $results;
    }

    /**
     * Common messages about updating filters
     *
     * @since  5.2.0
     *
     * @param  bool $asap
     *
     * @return string
     */
    protected function update_filters_asap(bool $asap = true): string
    {
        $output = ($asap)
            ? sprintf(
                '<p>%s</p>',
                sprintf(
                    /* translators: %s: fail2ban */
                    __('You should update your %s filters as soon as possible. This is usually done by your server administrator.', 'wp-fail2ban'),
                    '<code>fail2ban</code>'
                )
            )
            : sprintf(
                '<p>%s</p>',
                sprintf(
                    /* translators: %s: fail2ban */
                    __('You should update your %s filters. This is usually done by your server administrator.', 'wp-fail2ban'),
                    '<code>fail2ban</code>'
                )
            );
        if (file_exists('/opt/digitalocean/bin/droplet-agent')) {
            // Probably running DO droplet
            $output .= sprintf(
                /* translators: 1: "Life With WP fail2ban", 2: "DigitalOcean WordPress Droplet" */
                __('It looks like you&rsquo;re using a %1$s; step-by-step instructions for updating the filters can be found on the %2$s site.', 'wp-fail2ban'),
                '<b>DigitalOcean WordPress Droplet</b>',
                '&ldquo;<a href="https://life-with.wp-fail2ban.com/core/maintenance/updating-filters/digitalocean-wordpress-droplet/" rel="noopener" target="_blank">Life With WP fail2ban</a>&rdquo;<span class="dashicons dashicons-external"></span>'
            );
        }
        $output .= sprintf(
            '<p><a href="%s" target="_blank" rel="noopener">%s</a><span class="dashicons dashicons-external"></span></p>',
            sprintf(
                'https://docs.wp-fail2ban.com/en/%s/maintenance.html',
                WP_FAIL2BAN_VER2
            ),
            __('Learn more about updating filters.', 'wp-fail2ban')
        );

        return $output;
    }

    /**
     * Are the fail2ban filters current?
     *
     * This test will not work if we do not have access to fail2ban/filter.d;
     * e.g. if we're running chroot'd
     *
     * @since  5.0.1    Drop cron nag
     * @since  5.0.0
     *
     * @return array    The test result.
     */
    public function get_test_filter_obsolete()
    {
        $results = [
            'label'     => __('The filters are up to date', 'wp-fail2ban'),
            'status'    => 'good',
            'badge'     => [
                'label' => __('Security'),
                'color' => 'blue'
            ],
            'description'   => sprintf('<p>%s</p>', __('You are using the latest <em>WP fail2ban</em> filters.', 'wp-fail2ban')),
            'actions'       => '',
            'test'          => 'wp_fail2ban_filter_obsolete'
        ];

        $failures = $this->check_filters($status);

        if (is_null($failures)) {
            $results['label']       = __('The filters could not be checked', 'wp-fail2ban');
            $results['status']      = 'recommended';
            $results['description'] = sprintf(
                '<p>%s</p><p>%s</p>',
                sprintf(
                    /* translators: %s: fail2ban */
                    __('Your %s install could not be found.', 'wp-fail2ban'),
                    '<code>fail2ban</code>'
                ),
                __('This may be expected behaviour, depending on your server configuration. You should ask your server administrator to review the documentation linked below and take any appropriate action.', 'wp-fail2ban')
            );
            $results['actions']     = sprintf(
                '<p><a href="%s" target="_blank" rel="noopener">%s</a><span class="dashicons dashicons-external"></span></p>',
                sprintf(
                    'https://docs.wp-fail2ban.com/en/%s/configuration/site-health-tool.html',
                    WP_FAIL2BAN_VER2
                ),
                __('Configuring the Site Health tool', 'wp-fail2ban')
            );

        } elseif (empty($failures)) {
            // Good - nothing to do

        } elseif ($status['obsolete']) {
            $results['label'] = __('One or more of your fail2ban filters are obsolete', 'wp-fail2ban');
            $results['status'] = 'critical';
            $output = sprintf(
                '<p>%s</p>',
                sprintf(
                    /* translators: %s: fail2ban. */
                    __('Using the latest version of the %s filters is critical for correct behaviour. Obsolete filters may cause users to be blocked incorrectly, or attackers not to be detected.', 'wp-fail2ban'),
                    '<code>fail2ban</code>'
                )
            );
            $output .= '<ul>';
            foreach ($failures as $filter => $failure) {
                switch ($failure['status']) {
                    case 'obsolete':
                        $output .= '<li><span class="dashicons dashicons-warning" style="color: #dc3232"></span> '.sprintf(
                            /* translators: %s: The filter name. */
                            __('%s is <strong>obsolete</strong> (version %s)', 'wp-fail2ban'),
                            "<code>wordpress-{$filter}.conf</code>",
                            $failure['version']
                        );
                        if (count($failure['reasons'] ?? [])) {
                            $output .= ': '.join('; ', array_map(function ($v) {
                                return rtrim($v, '.');
                            }, $failure['reasons']));
                        }
                        $output .= '.</li>';
                        break;
                }
            }
            $output .= '</ul>';
            $output .= $this->update_filters_asap();
            $results['description'] = $output;

        } elseif ($status['old']) {
            $results['label'] = __('One or more of your fail2ban filters are out of date, but compatible', 'wp-fail2ban');
            $results['description'] = __('Your filters are compatible with your current configuration. There is no need to update them at this time.', 'wp-fail2ban');
            $results['old'] = 'old';

        } elseif ($status['custom']) {
            $results['status'] = 'custom';

        } elseif ($status['partial']) {
            $results['status'] = 'partial';
        }

        $results['label'] = self::PREFIX.$results['label'];

        return $results;
    }

    /**
     * Are the fail2ban filters modifed?
     *
     * Custom filter files should have a different name.
     *
     * This test will not work if we do not have access to fail2ban/filter.d;
     * e.g. if we're running chroot'd
     *
     * @since  5.0.0
     *
     * @return array    The test result.
     */
    public function get_test_filter_modified()
    {
        // The filter_obsolete has already failed to run
        if (is_null($failures = $this->check_filters($status))) {
            return false;
        }

        $results = [
            'label'     => __('The filters have not been modified', 'wp-fail2ban'),
            'status'    => 'good',
            'badge'     => [
                'label' => __('Security'),
                'color' => 'blue'
            ],
            'description'   => sprintf('<p>%s</p>', __('The standard <em>WP fail2ban</em> filters are installed.', 'wp-fail2ban')),
            'actions'       => '',
            'test'          => 'wp_fail2ban_filter_modified'
        ];

        if (empty($failures)) {
            // Good - nothing to do

        } elseif ($status['custom']) {
            $results['label'] = 'One or more of your filters have been modified';
            $results['status'] = 'recommended';
            $output = sprintf(
                '<p>%s</p>',
                sprintf(
                    /* translators: %s: the documentation */
                    __('You should not modify the standard configuration files. Please refer to %s on how to create custom filters.', 'wp-fail2ban'),
                    sprintf(
                        '<a href="https://docs.wp-fail2ban.com/en/%s/configuration/fail2ban.html#custom-filters" target="_blank">%s</a><span class="dashicons dashicons-external"></span>',
                        WP_FAIL2BAN_VER2,
                        __('the documentation', 'wp-fail2ban')
                    )
                )
            );
            $output .= '<ul>';
            foreach ($failures as $filter => $failure) {
                switch ($failure['status']) {
                    case 'custom':
                        $output .= '<li><span class="dashicons dashicons-flag" style="color: #f56e28"></span> '.sprintf(
                            /* translators: %s: The filter file name. */
                            __('%s has been modified.', 'wp-fail2ban'),
                            "<code>wordpress-{$filter}.conf</code>",
                        ).'</li>';
                        break;
                }
            }
            $output .= '</ul>';
            $results['description'] = $output;
        }

        $results['label'] = self::PREFIX.$results['label'];

        return $results;
    }

    /**
     * Are any of the fail2ban filters missing?
     *
     * This test will not work if we do not have access to fail2ban/filter.d;
     * e.g. if we're running chroot'd
     *
     * @since  5.0.0
     *
     * @return array    The test result.
     */
    public function get_test_filter_missing()
    {
        // The filter_obsolete has already failed to run
        if (is_null($failures = $this->check_filters($status))) {
            return false;
        }

        $results = [
            'label'     => __('The filters are all present', 'wp-fail2ban'),
            'status'    => 'good',
            'badge'     => [
                'label' => __('Security'),
                'color' => 'blue'
            ],
            'description'   => sprintf('<p>%s</p>', __('All the <em>WP fail2ban</em> filters are installed.', 'wp-fail2ban')),
            'actions'       => '',
            'test'          => 'wp_fail2ban_filter_missing'
        ];

        if (empty($failures)) {
            // Good - nothing to do

        } elseif ($status['partial']) {
            $results['label'] = 'One or more of your filters are missing';
            $results['status'] = 'recommended';
            $output = sprintf(
                '<p>%s</p>',
                sprintf(
                    /* translators: %s: the documentation */
                    __('You should include all the standard configuration files. Please refer to %s.', 'wp-fail2ban'),
                    sprintf(
                        '<a href="https://docs.wp-fail2ban.com/en/%s/configuration/fail2ban.html#updating" target="_blank">%s</a><span class="dashicons dashicons-external"></span>',
                        WP_FAIL2BAN_VER2,
                        __('the documentation', 'wp-fail2ban')
                    )
                )
            );
            $output .= '<ul>';
            foreach ($failures as $filter => $failure) {
                switch ($failure['status']) {
                    case 'missing':
                        $output .= '<li><span class="dashicons dashicons-flag" style="color: #dc3232"></span> '.sprintf(
                            /* translators: %s: The filter file name. */
                            __('%s is missing.', 'wp-fail2ban'),
                            "<code>wordpress-{$filter}.conf</code>",
                        ).'</li>';
                        break;
                }
            }
            $output .= '</ul>';
            $results['description'] = $output;
        }

        $results['label'] = self::PREFIX.$results['label'];

        return $results;
    }

    /**
     * Is WP fail2ban Blocklist installed and activated?
     *
     * @since  5.0.0
     *
     * @return array    The test result.
     */
    public function get_test_blocklist_installed(): array
    {
        $results = [
            'label'     => '',
            'status'    => '',
            'badge'     => [
                'label' => __('Security'),
                'color' => 'blue'
            ],
            'description'   => '',
            'actions'       => '',
            'test'          => 'wp_fail2ban_blocklist_installed'
        ];

        if (defined('WP_FAIL2BAN_ADDON_BLOCKLIST_VER')) { // Installed and activated?
            $results['label']       = __('Blocklist is installed and activated', 'wp-fail2ban');
            $results['status']      = 'good';
            $results['description'] = '';

        } else {
            $results['status'] = 'recommended';
            $installed = false;

            // Check if it's installed
            if (file_exists(WP_PLUGIN_DIR.'/wpf2b-addon-blocklist')) {
                $installed = 'wpf2b-addon-blocklist';
            } elseif (file_exists(WP_PLUGIN_DIR.'/wp-fail2ban-addon-blocklist')) {
                $installed = 'wp-fail2ban-addon-blocklist';
            }

            if ($installed) {
                $plugin = $installed.'/addon.php';
                $results['label']       = __('The Blocklist add-on is not activated', 'wp-fail2ban');
                $results['description'] = '';
                $results['actions']     = sprintf(
                    '<p><a href="%s">%s</a></p><p><a href="https://forums.invis.net/c/wp-fail2ban-blocklist/support/" rel="noopener noreferrer" target="_blank">%s</a></p>',
                    esc_url(wp_nonce_url(network_admin_url("plugins.php?action=activate&plugin={$plugin}"), 'activate-plugin_'.$plugin)),
                    __('Activate WP fail2ban Blocklist', 'wp-fail2ban'),
                    __('Get support', 'wp-fail2ban')
                );

            } else {
                $results['label']       = __('The Blocklist add-on is not installed', 'wp-fail2ban');
                $results['description'] = sprintf(
                    '<p>%s</p>',
                    sprintf(
                        __('%s is a collaborative preemptive blocklist - it protects your site <em>before</em> it&rsquo;s attacked.', 'wp-fail2ban'),
                        '<strong>WP fail2ban Blocklist</strong>'
                    )
                );
                $url = network_admin_url('update.php?action=install-plugin&plugin=wpf2b-addon-blocklist');
                $url = wp_nonce_url($url, 'install-plugin_wpf2b-addon-blocklist');
                $url = esc_url($url);
                $results['actions'] = sprintf(
                    '<p><a href="%s">%s</a></p><p><a href="https://addons.wp-fail2ban.com/addon/blocklist/" rel="noopener noreferrer" target="_blank">%s</a></p>',
                    $url,
                    __('Install WP fail2ban Blocklist', 'wp-fail2ban'),
                    __('Learn more about WP fail2ban Blocklist', 'wp-fail2ban')
                );
            }
        }

        $results['label'] = self::PREFIX.$results['label'];

        return $results;
    }

    /**
     * Is a WP fail2ban Add-on installed and activated?
     *
     * @since  5.0.0
     *
     * @return array|false  The test result.
     */
    protected function get_test_addon_installed(array $params)
    {
        $results = [
            'label'     => '',
            'status'    => '',
            'badge'     => [
                'label' => __('Security'),
                'color' => 'blue'
            ],
            'description'   => '',
            'actions'       => '',
            'test'          => $params['test']
        ];

        if (defined($params['active']['define'])) { // Installed and activated?
            $results['label']       = sprintf(__('%s is installed and activated', 'wp-fail2ban'), $params['name']);
            $results['status']      = 'good';
            $results['description'] = '';

        } else {
            $results['status'] = 'recommended';
            $installed = false;

            // Check if it's installed
            if (file_exists(WP_PLUGIN_DIR.'/'.$params['slug']['free'])) {
                $installed = $params['slug']['free'];
            } elseif (file_exists(WP_PLUGIN_DIR.'/'.$params['slug']['premium'])) {
                $installed = $params['slug']['premium'];
            }

            if ($installed) {
                $plugin = $installed.'/addon.php';
                $results['label']       = sprintf(__('The %s add-on is not activated', 'wp-fail2ban'), $params['name']);
                $results['description'] = $params['inactive']['description'];
                $results['actions']     = sprintf(
                    '<p><a href="%s">%s</a></p>',
                    esc_url(wp_nonce_url(network_admin_url("plugins.php?action=activate&plugin={$plugin}"), 'activate-plugin_'.$plugin)),
                    sprintf(__('Activate the %s add-on', 'wp-fail2ban'), $params['name'])
                );

            } else {
                $results['label']       = sprintf(__('The %s is not installed', 'wp-fail2ban'), $params['name']);
                $results['description'] = $params['missing']['description'];
                $url = network_admin_url('update.php?action=install-plugin&plugin='.$params['slug']['free']);
                $url = wp_nonce_url($url, 'install-plugin_'.$params['slug']['free']);
                $url = esc_url($url);
                $results['actions'] = sprintf(
                    '<p><a href="%s">%s</a></p>',
                    $url,
                    sprintf(__('Install %s', 'wp-fail2ban'), $params['name'])
                );
            }

            $results['label'] = self::PREFIX.$results['label'];
        }

        return $results;
    }

    public function get_test_cf7_installed()
    {
        $results = false;

        if (defined('WPCF7_VERSION')) {
            $results = $this->get_test_addon_installed([
                'test'  => 'wp_fail2ban_cf7_installed',
                'name'  => __('Contact Form 7', 'wp-fail2ban'),
                'slug' => [
                    'free'      => 'wp-fail2ban-addon-contact-form-7',
                    'premium'   => 'wp-fail2ban-addon-contact-form-7-premium'
                ],
                'active' => [
                    'define'    => 'WP_FAIL2BAN_ADDON_CF7_VER'
                ],
                'inactive' => [
                    'description'   => '',
                ],
                'missing' => [
                    'description'   => ''
                ]
            ]);
        }

        return $results;
    }

    public function get_test_gf_installed()
    {
        $results = false;

        if (class_exists('\GFCommon')) {
            $results = $this->get_test_addon_installed([
                'test'  => 'wp_fail2ban_gf_installed',
                'name'  => __('Gravity Forms', 'wp-fail2ban'),
                'slug' => [
                    'free'      => 'wp-fail2ban-addon-gravity-forms',
                    'premium'   => 'wp-fail2ban-addon-gravity-forms-premium'
                ],
                'active' => [
                    'define'    => 'WP_FAIL2BAN_ADDON_GRAVITY_FORMS_VER'
                ],
                'inactive' => [
                    'description'   => '',
                ],
                'missing' => [
                    'description'   => ''
                ]
            ]);
        }

        return $results;
    }
}

