<?php declare(strict_types=1);
/**
 * Badge Manager Class
 *
 * Manages the display of badges in WordPress plugin listings.
 *
 * @package wp-fail2ban-lib-badges
 * @since   1.0.0
 */

namespace WP_fail2ban\Lib\Badges;

use WP_fail2ban\Lib\Badges\Badge\LTSBadge;
use WP_fail2ban\Lib\Badges\Badge\CanonicalBadge;
use WP_fail2ban\Lib\Badges\Badge\NonCanonicalBadge;

defined('ABSPATH') or exit;

/**
 * Manages badge display in WordPress plugin listings
 *
 * @phpstan-type BadgeConfigType array{
 *     plugin_file: string,
 *     lts?: bool,
 *     canonical?: bool,
 *     free?: bool,
 *     show?: array{
 *         non-canonical?: bool
 *     }
 * }
 *
 * The BadgeConfig type defines the configuration options for badge display:
 * - lts: When true, displays the Long Term Support (LTS) badge
 * - canonical: When true, displays the Canonical version badge
 * - free: When true, displays the Free/Non-canonical version badge
 * - show: An array of options to control the display of badges:
 *   - non-canonical: When true, displays the Free/Non-canonical version badge
 *
 * All fields are optional and default to false when not specified.
 * 
 * @property-read bool $canonical 
 * @property-read bool $free 
 * @property-read bool $lts 
 * @property-read bool $premium 
 */
class BadgeManager
{
    /** 
     * @var BadgeConfigType
     * @since 1.0.0 
     */
    protected array $config;

    /**
     * Initializes the badge manager with the provided configuration
     *
     * Sets up the configuration and adds the necessary WordPress filter for plugin row meta.
     *
     * @since  1.0.0
     * @api
     * @param  BadgeConfigType    $config          Configuration options for badges
     * @throws void
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        add_filter('plugin_row_meta', [$this, 'filterPluginRowMeta'], PHP_INT_MAX, 4);
    }

    /**
     * Static initializer for BadgeManager
     *
     * @since  1.0.0
     * @api
     * @param  BadgeConfigType  $config  Configuration options for badges
     * @return self                      A new BadgeManager instance
     */
    public static function init(array $config): self
    {
        // Assume no autoloader
        require_once __DIR__.'/Badge/AbstractBadge.php';
        require_once __DIR__.'/Badge/CanonicalBadge.php';
        require_once __DIR__.'/Badge/LTSBadge.php';
        require_once __DIR__.'/Badge/NonCanonicalBadge.php';

        return new self($config);
    }

    /**
     * Generates badge links based on configuration
     * 
     * Creates and renders the appropriate badge objects based on the current
     * configuration settings, combining them into a single HTML string.
     * 
     * @since 1.0.0
     * @throws void
     * @return string Combined HTML string of all configured badges
     */
    public function generateBadgeLinks(): string
    {
        $links = [];

        if ($this->config['lts'] ?? false) {
            $badge = new LTSBadge();
            $links[] = $badge->render();
        }

        if ($this->config['canonical'] ?? false) {
            $badge = new CanonicalBadge();
            $links[] = $badge->render();
        }

        if ($this->config['free'] ?? false) {
            if ($this->config['show']['non-canonical'] ?? true) {
                $badge = new NonCanonicalBadge();
                $links[] = $badge->render();
            }
        }

        return implode('&nbsp;&nbsp;', $links);
    }

    /**
     * Filter callback for 'plugin_row_meta'
     *
     * Adds badge indicators to the plugin row in the WordPress admin.
     * Only modifies the meta for wp-fail2ban.php plugin file.
     *
     * @since  1.0.0
     * @param  array<string>     $links            Array of plugin row meta links
     * @param  string            $plugin_file_name The plugin file name
     * @param  array<mixed>      $plugin_data      Plugin data
     * @param  string            $status           Plugin status
     * @throws void
     * @return array<string>                       Modified array of plugin row meta links
     */
    public function filterPluginRowMeta($links, $plugin_file_name, $plugin_data, $status): array
    {
        if (0 === substr_compare($this->config['plugin_file'], $plugin_file_name, -strlen($plugin_file_name))) {
            $links[] = $this->generateBadgeLinks();
        }

        return $links;
    }

    /**
     * Magic getter for badge configuration properties
     * 
     * @since  1.3.0
     * @param  string  $name  The property name
     * @throws void
     * @return mixed         The property value or null if not found
     */
    public function __get($name)
    {
        switch ($name) {
            case 'canonical':
                return $this->isCanonical();
            case 'free':
                return $this->isFree();
            case 'lts':
                return $this->isLts();
            case 'premium':
                return $this->isPremium();
            default:
                return null;
        }
    }

    /**
     * Checks if the plugin is canonical
     * 
     * @api
     * @since  1.3.0
     * @throws void
     * @return bool True if the plugin is canonical, false otherwise
     */
    public function isCanonical(): bool
    {
        return $this->config['canonical'] ?? false;
    }

    /**
     * Checks if the plugin is free
     * 
     * @api
     * @since  1.3.0
     * @throws void
     * @return bool True if the plugin is free, false otherwise
     */
    public function isFree(): bool
    {
        return $this->config['free'] ?? false;
    }

    /**
     * Checks if the plugin is LTS
     * 
     * @api
     * @since  1.3.0
     * @throws void
     * @return bool True if the plugin is LTS, false otherwise
     */
    public function isLts(): bool
    {
        return $this->config['lts'] ?? false;
    }

    /**
     * Checks if the plugin is premium
     * 
     * @api
     * @since  1.3.0
     * @throws void
     * @return bool True if the plugin is premium, false otherwise
     */
    public function isPremium(): bool
    {
        return $this->config['premium'] ?? false;
    }
}
