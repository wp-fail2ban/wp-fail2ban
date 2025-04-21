<?php declare(strict_types=1);
/**
 * Long Term Support badge implementation
 *
 * Provides styling and text content specific to the LTS (Long Term Support)
 * badge.
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
 * Long Term Support badge implementation
 * 
 * @since  1.0.0
 */
class LTSBadge extends AbstractBadge
{
    /**
     * Initializes the LTS badge with specific styling and text
     *
     * Sets up the badge color, text content, and tooltip for the Long Term Support indicator.
     *
     * @since  1.5.0   Added $manager parameter
     * @since  1.0.0
     * @param  BadgeManager $manager The badge manager instance
     * @throws void
     */
    public function __construct(BadgeManager $manager)
    {
        parent::__construct($manager);

        $this->color = '#00A0D2';
    }

    /**
     * Get the LTS badge text
     * 
     * @since  1.5.0
     * @throws void
     * @return string The text to display for the LTS badge
     */
    protected function getText(): string
    {
        $plugin_file = $this->manager->plugin_file;
        $text = 'LTS';

        /**
         * Get the LTS badge text; default to 'LTS'
         * 
         * @api
         * @since  1.5.0  Added
         * @param  string $text The text to display for the LTS badge
         * @return string The text to display for the LTS badge
         */
        return apply_filters("WP_fail2ban.lib-badges.lts.text#{$plugin_file}", $text);
    }

    /**
     * Get the LTS badge title
     * 
     * @since  1.5.0
     * @throws void
     * @return string The title to display for the LTS badge
     */
    protected function getTitle(): string
    {
        $plugin_file = $this->manager->plugin_file;
        $title = 'Long-Term Support';

        /**
         * Get the LTS badge title; default to 'Long-Term Support'
         * 
         * @api
         * @since  1.5.0  Added
         * @param  string $title The title to display for the LTS badge
         * @return string The title to display for the LTS badge
         */
        return apply_filters("WP_fail2ban.lib-badges.lts.title#{$plugin_file}", $title);
    }
}