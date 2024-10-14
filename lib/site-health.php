<?php declare(strict_types=1);
/**
 * WP fail2ban Site Health
 *
 * @package wp-fail2ban
 * @since   4.4.0.8
 * @php     7.4
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

class SiteHealth
{
    protected static $instance = null;

    /**
     * Return an instance of the SiteHealth class, or create one if none exist yet.
     *
     * @since  4.4.0.8
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
     * @see \WP_Site_Health::get_tests()
     *
     * @since  4.4.0.8
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

        return [];
    }
}

