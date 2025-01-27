<?php declare(strict_types=1);
/**
 * Long Term Support badge implementation
 *
 * Provides styling and text content specific to the LTS (Long Term Support)
 * version indicator badge.
 *
 * @package wp-fail2ban-lib-badges
 * @since   1.0.0
 */

namespace WP_fail2ban\Lib\Badges\Badge;

class LTSBadge extends AbstractBadge
{
    /**
     * Initializes the LTS badge with specific styling and text
     *
     * Sets up the badge color, text content, and tooltip for the Long Term Support indicator.
     *
     * @since 1.0.0
     * @throws void
     */
    public function __construct()
    {
        $this->color = '#00A0D2';
        $this->text = __('LTS', 'wp-fail2ban');
        $this->title = __('Long-Term Support', 'wp-fail2ban');
    }
} 