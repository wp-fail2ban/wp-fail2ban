<?php declare(strict_types=1);
/**
 * Non-Canonical release badge implementation
 *
 * Provides styling and text content specific to the Non-Canonical version
 * indicator badge, including uppercase text transformation.
 *
 * @package wp-fail2ban-lib-badges
 * @since   1.0.0
 */

namespace WP_fail2ban\Lib\Badges\Badge;

class NonCanonicalBadge extends AbstractBadge
{
    /**
     * Initializes the Non-Canonical badge with specific styling and text
     *
     * Sets up the badge color, text content, and tooltip for the Non-Canonical release indicator.
     *
     * @since 1.0.0
     * @throws void
     */
    public function __construct()
    {
        $this->color = '#F56E28';
        $this->text = __('Non-Canonical', 'wp-fail2ban');
        $this->title = __('Non-Canonical release', 'wp-fail2ban');
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
}