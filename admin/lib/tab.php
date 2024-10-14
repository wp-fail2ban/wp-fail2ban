<?php declare(strict_types=1);
/**
 * Tab base class
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.0.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

/**
 * Tab: Base class
 *
 * @since 4.0.0
 */
abstract class TabBase
{
    /**
     * @since  4.3.2.1
     * @var    string   Help link: Documentation
     */
    const HELP_LINK_DOCS = 'https://life-with.wp-fail2ban.com';
    /**
     * @since  4.3.2.1
     * @var    string   Help link: Reference
     */
    const HELP_LINK_REFERENCE = 'https://docs.wp-fail2ban.com/en/'.WP_FAIL2BAN_VER2;
    /**
     * @since  4.3.2.1
     * @var    string   Help link: Support
     */
    const HELP_LINK_SUPPORT = 'https://forums.invis.net/c/wp-fail2ban/support/';

    /**
     * @var array  Array of Tab objects
     * @todo Set type to array
     */
    protected static $tabs = [];
    /**
     * @var string Default tab slug
     * @todo Set type to string
     */
    protected static $default_tab;
    /**
     * @var TabBase Active tab
     * @todo Set type to TabBase
     */
    protected static $active_tab;

    /**
     * @var string  Tab slug
     * @todo Set type to string
     */
    protected $tab_slug;
    /**
     * @var string  Tab name
     * @todo Set type to string
     */
    protected $tab_name;
    /**
     * @since 4.3.0
     * @var bool    Apply/Reset buttons?
     * @todo Set type to bool
     */
    protected $tab_apply;

    /**
     * @var   int   admin_init priority
     * @since 4.3.0
     * @todo Set type to int
     */
    protected $admin_init_priority = 10;

    /**
     * @since 4.3.3.0
     * @var array   i18n
     * @todo Set tpye to array
     *
     * @phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore
     */
    protected $__ = [];

    /**
     * Hook: admin_init
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     */
    abstract public function admin_init(): void;

    /**
     * Hook: current_screen
     *
     * @since  4.4.0    Add return type
     * @since  4.3.2.1  Add links to override
     * @since  4.3.0
     *
     * @param  string   $linkDocumentation
     * @param  string   $linkSupport
     * @param  string   $linkReference
     *
     * @return void
     */
    public function current_screen(): void
    {
        get_current_screen()->set_help_sidebar(
            '<p><strong>' . __('For more information:') . '</strong></p>' .
            sprintf('<p><a href="%s" rel="noopener" target="_blank">%s</a></p>', static::HELP_LINK_DOCS, __('Documentation', 'wp-fail2ban')).
            sprintf('<p><a href="%s" rel="noopener" target="_blank">%s</a></p>', static::HELP_LINK_REFERENCE, __('Reference', 'wp-fail2ban')).
            sprintf('<p><a href="%s" rel="noopener" target="_blank">%s</a></p>', static::HELP_LINK_SUPPORT, __('Support', 'wp-fail2ban'))
        );
    }

    /**
     * Helper: Create dt/dd pair
     *
     * @since  4.4.0    Add return type
     * @since  4.3.3.0
     *
     * @param  string       $id
     * @param  array        $paras
     * @param  string|null  $title
     *
     * @return string
     */
    protected function help_entry($id, array $paras, $title = null): string
    {
        if (is_null($title)) {
            $title = $this->__[$id];
        }
        $paras  = apply_filters("help-entry::{$id}.paras", $paras);
        $entry  = apply_filters("help-entry::{$id}.pre", '');
        $entry .= "<dt>{$title}</dt><dd>";
        $entry .= $this->process_paras($paras);
        $entry .= '</dd>';
        $entry .= apply_filters("help-entry::{$id}.post", '');

        return $entry;
    }

    /**
     * Helper: Create paragraphs
     *
     * @since  4.4.0    Add return type
     * @since  4.3.3.0
     *
     * @param  array|string $paras
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    protected function process_paras($paras): string
    {
        if (empty($paras)) {
            return '';
        } elseif (is_array($paras)) {
            return '<p>'.join('</p><p>', array_map([$this, 'process_para'], $paras)).'</p>';
        } elseif (is_string($paras)) {
            return $paras;
        } else {
            throw new \InvalidArgumentException();
        }
    }

    /**
     * Helper: Create paragraph content
     *
     * @since  4.4.0    Add return type
     * @since  4.3.3.0
     *
     * @param  array|string     $para
     *
     * @return string
     */
    protected function process_para($para): string
    {
        if (is_array($para)) {
            $para = join('<br>', $para);
        }
        return $para;
    }

    /**
     * Helper: Add help tab
     *
     * @since  4.4.0    Add return type
     * @since  4.3.3.0
     *
     * @param  string           $id
     * @param  array|string     $dl
     * @param  string           $pre
     * @param  string           $post
     *
     * @return void
     */
    protected function add_help_tab($id, $dl, $pre = '', $post = ''): void
    {
        $pre    = apply_filters("help-tab::{$id}.pre", $pre);
        $post   = apply_filters("help-tab::{$id}.post", $post);
        $content  = $this->process_paras($pre);
        if (is_array($dl)) {
            $content .= apply_filters("help-tab::{$id}.dl.pre", "<dl>\n");
            $content .= join("\n", $dl);
            $content .= apply_filters("help-tab::{$id}.dl.post", "</dl>\n");
        } else {
            $content .= apply_filters("help-tab::{$id}.dl.pre", '');
            $content .= $dl;
            $content .= apply_filters("help-tab::{$id}.dl.post", '');
        }
        $content .= $this->process_paras($post);

        get_current_screen()->add_help_tab([
            'id'      => $id,
            'title'   => $this->__[$id],
            'content' => $content
        ]);
    }

    /**
     * Sanitize and store form fields
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0    Refactor
     * @since  4.0.0
     *
     * @param  array    $input      Form fields
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function sanitize(array $input = null): array
    {
        return [];
    }

    /**
     * Contruct.
     *
     * @since  4.4.0    Add type hints
     * @since  4.0.0
     *
     * @param  string   $slug   Tab slug
     * @param  string   $name   Tab name
     * @param  bool     $apply  Show Apply/Reset buttons
     */
    public function __construct(string $slug, string $name, bool $apply = true)
    {
        $this->tab_slug     = $slug;
        $this->tab_name     = $name;
        $this->tab_apply    = $apply;

        self::$tabs[$slug] = $this;

        $this->admin_init();

        add_filter('gettext', [$this, 'gettext'], PHP_INT_MAX, 3);
    }

    /**
     * Hook: gettext
     *
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.0
     *
     * @param  string   $translation
     * @param  string   $text
     * @param  string   $domain
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function gettext(string $translation, string $text, string $domain): string
    {
        return str_replace('___WPF2BVER___', WP_FAIL2BAN_VER2, $translation);
    }

    /**
     * Getter - slug
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return string   Tab slug
     */
    public function getSlug(): string
    {
        return $this->tab_slug;
    }

    /**
     * Getter - name
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return string   Tab name
     */
    public function getName(): string
    {
        return $this->tab_name;
    }

    /**
     * Render settings section
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0    Refactored.
     * @since  4.0.0
     */
    public function render(): void
    {
        do_settings_sections('wp-fail2ban-'.$this->tab_slug);
        $this->render_buttons();
    }

    /**
     * Render settings section buttons
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @return void
     */
    protected function render_buttons(): void
    {
        printf(
            '<hr><p><strong>%s:</strong> %s<br>%s</p>',
            __('Note', 'wp-fail2ban'),
            __('The Free version of <em>WP fail2ban</em> is configured by defining constants in <code>wp-config.php</code>;
                these tabs reflect those values.', 'wp-fail2ban'),
            __('Upgrade to the Premium version to enable this interface.', 'wp-fail2ban')
        );
    }

    /**
     * Helper: is this the active tab?
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @return bool
     */
    protected function isActiveTab(): bool
    {
        return ($this->tab_name == self::getActiveTab()->getName());
    }

    /**
     * Helper - tab
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @param  string   $slug   Tab slug
     *
     * @return TabBase          Tab
     */
    public static function getTab($slug): TabBase
    {
        return self::$tabs[$slug];
    }

    /**
     * Helper - set the default tab.
     *
     * @since  4.4.0    Add type hint, return type
     * @since  4.3.0
     *
     * @param  string   $default    Default tab slug
     *
     * @return void
     */
    public static function setDefaultTab(string $default): void
    {
        self::$default_tab = $default;
    }

    /**
     * Helper - current tab
     *
     * @since  4.4.0    Add return type
     * @since  4.0.0
     *
     * @return TabBase  Tab
     */
    public static function getActiveTab(): TabBase
    {
        if (!empty(self::$active_tab)) {
            return self::$active_tab;
        }

        return (self::$active_tab = (array_key_exists($_GET['tab'] ?? '--', self::$tabs))
            ? self::$tabs[$_GET['tab']]
            : self::$tabs[self::$default_tab]
        );
    }

    /**
     * Helper - tab name
     *
     * @since  4.4.0    Add type hint, return type
     * @since  4.0.0
     *
     * @param  string   $slug   Tab slug
     *
     * @return string           Tab name
     */
    public static function getTabName(string $slug): string
    {
        return self::getTab($slug)->getName();
    }

    /**
     * Helper - tab exists?
     *
     * @since  4.4.0    Add type hint, return type
     * @since  4.3.0
     *
     * @param  string   $slug   Tab slug
     *
     * @return bool
     */
    public static function tabExists(string $slug): bool
    {
        return array_key_exists($slug, self::$tabs);
    }

    /**
     * Link to documentation
     *
     * @since  5.0.0    Simplify
     * @since  4.4.0    Add type hint, return type
     * @since  4.3.0    Protected
     * @since  4.2.0
     *
     * @param  string   $define
     *
     * @return string
     */
    protected function doc_link(string $define): string
    {
        $link = <<< HTML
<a href="%s/defines/constants/%s.html"
   style="text-decoration: none;"
   target="_blank"
   title="%s">%s<span class="dashicons dashicons-external"
                      style="vertical-align: text-bottom"></span></a>
HTML;

        return sprintf($link, self::HELP_LINK_REFERENCE, $define, __('Reference', 'wp-fail2ban'), $define);
    }

    /**
     * Standard list of links to docs
     *
     * @since  4.4.0    Add return type
     * @since  4.3.0
     *
     * @param  array    $defines    List of defines
     * @param  bool     $para       Wrap in <p>
     *
     * @return string   HTML
     */
    protected function see_also(array $defines, $para = true): string
    {
        $html = sprintf('<em>%s</em>', __('See also:', 'wp-fail2ban'));
        if (1 == count($defines)) {
            $html .= '&nbsp;'.$this->doc_link($defines[0]);
        } else {
            $html .= sprintf(
                '<br>&nbsp;&mdash;&nbsp;%s',
                implode('<br>&nbsp;&mdash;&nbsp;', array_map(function ($i) {
                    return $this->doc_link($i);
                }, $defines))
            );
        }
        if ($para) {
            $html = '<p>'.$html.'</p>';
        }
        return $html;
    }

    /**
     * id="%s" Helper
     *
     * @since  4.4.0    Add type hint, return type
     * @since  4.3.0    Moved here.
     * @since  4.0.0
     *
     * @param  string   $define
     *
     * @return string
     */
    protected function field_name(string $define): string
    {
        global $wp_fail2ban;

        return 'wp-fail2ban['.join('][', $wp_fail2ban['config'][$define]['field']).']';
    }

    /**
     * name="%s" Helper
     *
     * @since  4.4.0    Add $suffix, type hint, return type
     * @since  4.3.0    Moved here.
     * @since  4.0.0
     *
     * @param  string       $define
     * @param  string|null  $suffix
     *
     * @return string
     */
    protected function field_id(string $define, string $suffix = null): string
    {
        global $wp_fail2ban;

        $rv = join('-', $wp_fail2ban['config'][$define]['field']);
        if (!empty($suffix)) {
            $rv .= '-'.$suffix;
        }
        return $rv;
    }

    /**
     * Helper: checked()
     *
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.0
     *
     * @param  string   $define
     * @param  mixed    $current
     * @param  bool     $echo
     *
     * @return string
     */
    protected function def_checked(string $define, $current = true, bool $echo = true): string
    {
        return checked(Config::get($define), $current, $echo);
    }

    /**
     * NDEF disabled helper
     *
     * @since  4.4.0    Add filter, type hints, return type
     * @since  4.3.0    Add $override; moved here.
     * @since  4.0.0
     *
     * @param  string   $define
     * @param  bool     $override
     *
     * @return string
     */
    protected function ndef_disabled(string $define, bool $override = false): string
    {
        return disabled(Config::def($define) || apply_filters("{$define}.ndef_disabled", $override), true, false);
    }

    /**
     * Display standard checkbox
     *
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.0
     *
     * @param  string   $define     Constant
     * @param  bool     $show_desc  Show description?
     * @param  string   $plan       Freemius plan
     * @param  bool     $echo       Echo?
     *
     * @return string   HTML
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function checkbox(string $define, bool $show_desc = true, string $plan = 'bronze', bool $echo = true): string
    {
        $html = sprintf(
            '<input type="checkbox" disabled="disabled" %s>',
            checked(Config::get($define), true, false)
        );
        if ($show_desc) {
            $html = '<label>'.$html.' <span>'.$this->description($define, false).'</span></label>';
        }
        if ($echo) {
            echo $html;
        }
        return $html;
    }

    /**
     * Display standard radio buttons
     *
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.3
     *
     * @param  array|string     $define
     * @param  bool             $show_desc
     * @param  string           $plan
     * @param  bool             $echo
     * @param  string           $cssClass
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function radio($define, bool $show_desc = true, string $plan = 'bronze', bool $echo = true): string
    {
        global $wp_fail2ban;

        if (is_array($define)) {
            extract($define);
        }

        $html = '';
        foreach ($wp_fail2ban['config'][$define]['values'] as $value) {
            $checked = $this->def_checked($define, $value, false);
            if ($show_desc) {
                $html .= "<p><label><input type=\"radio\" disabled=\"disabled\" {$checked}> ".$this->description("{$define}.{$value}", false).'</label>';
            } else {
                $html .= "<p><input type=\"radio\" disabled=\"disabled\" {$checked}>";
            }
            $html .= '</p>';
        }
        if ($echo) {
            echo $html;
        }
        return $html;
    }

    /**
     * Display standard text input
     *
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.0
     *
     * @param  string   $define     Constant
     * @param  bool     $show_desc  Show description?
     * @param  string   $plan       Freemius plan
     * @param  bool     $echo       Echo?
     * @param  string   $value      Text value
     * @param  string   $cssClass   CSS class
     *
     * @return string   HTML
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function inputText(string $define, bool $show_desc = true, string $plan = 'bronze', bool $echo = true, string $value = null, string $cssClass = 'regular-text', string $type = 'text'): string
    {
        if (is_null($value)) {
            $value = Config::get($define);
        }
        $html = sprintf(
            '<a name="%s"></a><input class="%s" type="%s" value="%s" disabled>',
            $this->field_id($define),
            $cssClass,
            $type,
            esc_attr($value)
        );
        if ($show_desc) {
            $html .= sprintf('<p class="description">%s</p>', Config::desc($define));
        }
        if ($echo) {
            echo $html;
        }
        return $html;
    }

    /**
     * Helper: input type=email
     *
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.2.2
     *
     * @param  string   $define     Constant
     * @param  bool     $show_desc  Show description?
     * @param  string   $plan       Freemius plan
     * @param  bool     $echo       Echo?
     * @param  string   $value      Text value
     * @param  string   $cssClass   CSS class
     *
     * @return string   HTML
     */
    protected function inputEmail(
        string $define,
        bool $show_desc = true,
        string $plan = 'bronze',
        bool $echo = true,
        string $value = null,
        string $cssClass = 'regular-text'
    ) : string {
        return $this->inputText($define, $show_desc, $plan, $echo, $value, $cssClass, 'email');
    }

    /**
     * Helper: input type=password
     *
     * @since  4.4.0    Add return type
     * @since  4.3.2.2
     *
     * @param  string   $define     Constant
     * @param  bool     $show_desc  Show description?
     * @param  string   $plan       Freemius plan
     * @param  bool     $echo       Echo?
     * @param  string   $value      Text value
     * @param  string   $cssClass   CSS class
     *
     * @return string   HTML
     */
    protected function inputPassword(
        string $define,
        bool $show_desc = true,
        string $plan = 'bronze',
        bool $echo = true,
        string $value = null,
        string $cssClass = 'regular-text'
    ) : string {
        return $this->inputText($define, $show_desc, $plan, $echo, $value, $cssClass, 'password');
    }

    /**
     * Helper: setting description
     *
     * @since  4.4.0    Add type hints, return type
     * @since  4.3.0
     *
     * @param  string   $define
     * @param  bool     $echo
     *
     * @return string
     */
    protected function description(string $define, bool $echo = true): string
    {
        if (!is_null($desc = Config::desc($define))) {
            if ($echo) {
                echo '<p class="description">'.$desc.'</p>';
            }
            return $desc;
        } else {
            return '';
        }
    }

    /**
     * Helper: setting description extended
     *
     * @since  5.1.0
     *
     * @param  string   $define
     * @param  bool     $echo
     *
     * @return string
     */
    protected function description_ex(string $define, bool $echo = true): string
    {
        if (!is_null($desc = Config::desc_ex($define))) {
            if ($echo) {
                echo '<p class="description">'.$desc.'</p>';
            }
            return $desc;
        } else {
            return '';
        }
    }
}

