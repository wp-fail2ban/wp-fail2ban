<?php declare(strict_types=1);
/**
 * Config
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Tab: Logging Base class
 *
 * @since 4.3.0
 */
abstract class TabLoggingBase extends TabBase
{
    /**
     * Helper - build drop-down list of facilities
     *
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.0
     *
     * @param  string   $def    Name of define for selected value
     * @param  string   $str    Opening select html
     *
     * @return string
     */
    protected function getLogFacilitiesOptions(string $def, string $str): string
    {
        $default = Config::get_default($def);
        $value = Config::get($def);

        foreach (ConvertData::getFacilityNames2Values() as $name => $facility) {
            $str .= sprintf(
                '<option value="%s" %s>%s%s</option>',
                $facility,
                selected($value, $facility, false),
                $name,
                ($facility == $default) ? __(' (default)') : ''
            );
        }
        $str .= '</select>';

        return $str;
    }

    /**
     * Helper - drop-down list of facilities
     *
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.0    Refactored.
     * @since  4.0.0
     *
     * @param  string   $def        Name of define for selected value
     * @param  bool     $_enabled   Enabled?
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function getLogFacilities(string $def, bool $_enabled = false): string
    {
        return $this->getLogFacilitiesOptions($def, '<select disabled="disabled">');
    }

    /**
     * Log helper - enable/disable+facility
     *
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.0    Refactored
     * @since  4.2.0    Moved to Tab
     * @since  4.0.0
     *
     * @param  string   $define_name    Name of define to enable logging
     * @param  string   $define_log     Name of define for log facility
     * @param  array    $toggle         Array of IDs to sync toggle state
     * @param  bool     $echo           Echo?
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function log(string $define_name, string $define_log, array $toggle = [], bool $echo = true): string
    {
        $enabled = (true === Config::get($define_name)); // @TODO
        $fmt = <<< HTML
<input type="checkbox" disabled="disabled" %s> <label>%s</label>,
<label>%s:</label> %s
HTML;
        $html = sprintf(
            $fmt,
            checked($enabled, true, false),
            __('Enable logging', 'wp-fail2ban'),
            __('use facility', 'wp-fail2ban'),
            $this->getLogFacilities($define_log)
        );
        $html .= sprintf('<p class="description">%s</p>', Config::desc($define_name));
        $rv = apply_filters("wp_fail2ban_log_{$define_name}", $html, $define_name, $define_log);
        if ($echo) {
            echo $rv;
        }
        return $rv;
    }
}

