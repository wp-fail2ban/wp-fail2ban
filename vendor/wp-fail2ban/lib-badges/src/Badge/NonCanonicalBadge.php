<?php declare(strict_types=1);
/**
 * Non-Canonical release badge implementation
 *
 * Provides styling and text content specific to the Non-Canonical badge.
 *
 * @package    wp-fail2ban\lib\badges
 * @author     Charles Lecklider
 * @link       https://src.wp-fail2ban.com/src/lib/badges/
 * @category   WordPress
 * @license    AGPL-3.0-or-later
 * @copyright  2025- Charles Lecklider
 * @since      1.0.0
 */

namespace WP_fail2ban\Lib\Badges\Badge;

use WP_fail2ban\Lib\Badges\BadgeManager;

/**
 * Non-Canonical release badge implementation
 * 
 * @since  1.0.0
 */
class NonCanonicalBadge extends AbstractBadge
{
    /**
     * Initializes the Non-Canonical badge with specific styling and text
     *
     * Sets up the badge color, text content, and tooltip for the Non-Canonical release indicator.
     *
     * @since  1.5.0   Added $manager parameter
     * @since  1.0.0
     * @param  BadgeManager $manager The badge manager instance
     * @throws void
     */
    public function __construct(BadgeManager $manager)
    {
        parent::__construct($manager);

        $this->color = '#F56E28';
    }

    /**
     * Extends the base badge styling with uppercase text transformation
     *
     * @since  1.0.0
     * @param  array<string,string> $extra_styles Additional CSS styles to apply
     * @throws void
     * @return string                             The complete CSS style string
     */
    protected function getStyle(array $extra_styles = []): string
    {
        return parent::getStyle(['text-transform' => 'uppercase']);
    }

    /**
     * Get the Non-Canonical badge text
     * 
     * @since  1.5.0
     * @throws void
     * @return string The text to display for the Non-Canonical badge
     */
    protected function getText(): string
    {
        $plugin_file = $this->manager->plugin_file;
        $text = 'Non-Canonical';

        /**
         * Get the Non-Canonical badge text; default to 'Non-Canonical'
         * 
         * @api
         * @since  1.5.0  Added
         * @param  string $text The text to display for the Non-Canonical badge
         * @return string The text to display for the Non-Canonical badge
         */
        return apply_filters("WP_fail2ban.lib-badges.non-canonical.text#{$plugin_file}", $text);
    }

    /**
     * Get the Non-Canonical badge title
     * 
     * @since  1.5.0
     * @throws void
     * @return string The title to display for the Non-Canonical badge
     */
    protected function getTitle(): string
    {
        $plugin_file = $this->manager->plugin_file;
        $title = 'Non-Canonical release';

        /**
         * Get the Non-Canonical badge title; default to 'Non-Canonical release'
         * 
         * @api
         * @since  1.5.0  Added
         * @param  string $title The title to display for the Non-Canonical badge
         * @return string The title to display for the Non-Canonical badge
         */
        return apply_filters("WP_fail2ban.lib-badges.non-canonical.title#{$plugin_file}", $title);
    }
}