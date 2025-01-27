<?php declare(strict_types=1);
/**
 * Abstract base class for badge implementation
 *
 * Provides core functionality for rendering styled badge links in the WordPress admin.
 * Handles HTML generation, styling, and proper escaping of badge content.
 *
 * @package wp-fail2ban-lib-badges
 * @since   1.0.0
 */

namespace WP_fail2ban\Lib\Badges\Badge;

abstract class AbstractBadge
{
    /** 
     * @var string The badge color in hex format
     * @since 1.0.0 
     */
    protected string $color;
    
    /** 
     * @var string The text to display in the badge
     * @since 1.0.0 
     */
    protected string $text;
    
    /** 
     * @var string The tooltip text for the badge
     * @since 1.0.0 
     */
    protected string $title;

    /**
     * Renders the badge as HTML
     *
     * Generates and returns the complete HTML markup for displaying the badge,
     * including styling and tooltip.
     *
     * @since 1.0.0
     * @throws void
     * @return string The rendered HTML for the badge
     */
    public function render(): string
    {
        return $this->createLink('', $this->title, $this->getStyle(), $this->text);
    }

    /**
     * Generates the CSS style string for the badge
     *
     * Combines standard badge styles with any additional styles provided
     * and returns them as a formatted CSS string.
     *
     * @since  1.0.0
     * @param  array<string,string> $extra_styles Additional CSS styles to apply
     * @throws void
     * @return string                             The complete CSS style string
     */
    protected function getStyle(array $extra_styles = []): string
    {
        $standard_styles = [
            'border' => "1px solid {$this->color}",
            'color' => $this->color,
            'font-size' => '80%',
            'padding' => '1px 3px'
        ];

        $styles = [];
        foreach (array_merge($standard_styles, $extra_styles) as $k => $v)
        {
            $styles[] = "{$k}: $v";
        }

        return implode('; ', $styles);
    }

    /**
     * Creates an HTML link with the badge styling
     *
     * Generates an HTML link or span element with proper escaping and styling
     * for displaying the badge in the WordPress admin interface.
     *
     * @since  1.0.0
     * @param  string|null        $href   The link URL, or null for no link
     * @param  string             $title  The tooltip text
     * @param  string             $css    The CSS styles
     * @param  string             $text   The badge text
     * @throws void
     * @return string                     The formatted HTML link
     */
    protected function createLink(?string $href, string $title, string $css, string $text): string
    {
        $span = '<span style="'.esc_attr($css).'">'.esc_html($text).'</span>';

        return (null === $href)
            ? $span
            : '<a href="'.esc_attr($href).'" target="_blank" title="'.esc_attr($title).'">'.$span.'</a>';
    }
} 