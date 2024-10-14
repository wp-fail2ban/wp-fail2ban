<?php
/**
 * Tab base class
 *
 * @package wp-fail2ban
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
     * @var array  Array of Tab objects
     */
    protected static $tabs = [];
    /**
     * @var string Default tab slug
     */
    protected static $default_tab;
    /**
     * @var string Active tab slug
     */
    protected static $active_tab;

    /**
     * @var string  Tab slug
     */
    protected $tab_slug;
    /**
     * @var string  Tab name
     */
    protected $tab_name;
    /**
     * @since 4.3.0
     * @var bool    Apply/Reset buttons?
     */
    protected $tab_apply;

    /**
     * @var   int   admin_init priority
     * @since 4.3.0
     */
    protected $admin_init_priority = 10;

    /**
     * Hook: admin_init
     *
     * @since 4.0.0
     */
    abstract public function admin_init();

    /**
     * Hook: current_screen
     *
     * @since 4.3.0
     */
    public function current_screen()
    {
        get_current_screen()->set_help_sidebar(
            '<p><strong>' . __('For more information:') . '</strong></p>' .
            '<p>' . __('<a href="https://docs.wp-fail2ban.com">Documentation</a>', 'wp-fail2ban') . '</p>' .
            '<p><a href="https://forums.invis.net/c/wp-fail2ban/support/">'.__('Support').'</a></p>'
        );
    }

    /**
     * Sanitize and store form fields
     *
     * @since 4.3.0 Refactor
     * @since 4.0.0
     *
     * @param  array    $input      Form fields
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function sanitize(array $input = null)
    {
        return [];
    }

    /**
     * Contruct.
     *
     * @since 4.0.0
     *
     * @param string    $slug   Tab slug
     * @param string    $name   Tab name
     * @param bool      $apply  Show Apply/Reset buttons
     */
    public function __construct($slug, $name, $apply = true)
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
     * @since 4.3.0
     *
     * @param  string   $translation
     * @param  string   $text
     * @param  string   $domain
     * @return string
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function gettext($translation, $text, $domain)
    {
        return str_replace('___WPF2BVER___', WP_FAIL2BAN_VER_SHORT, $translation);
    }

    /**
     * Getter - slug
     *
     * @since 4.0.0
     *
     * @return string   Tab slug
     */
    public function getSlug()
    {
        return $this->tab_slug;
    }

    /**
     * Getter - name
     *
     * @since 4.0.0
     *
     * @return string   Tab name
     */
    public function getName()
    {
        return $this->tab_name;
    }

    /**
     * Render settings section
     *
     * @since 4.3.0    Refactored.
     * @since 4.0.0
     */
    public function render()
    {
        do_settings_sections('wp-fail2ban-'.$this->tab_slug);
        $this->render_buttons();
    }

    /**
     * Render settings section buttons
     *
     * @since  4.3.0
     */
    protected function render_buttons()
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
     * @since 4.3.0
     *
     * @return bool
     */
    protected function isActiveTab()
    {
        return ($this->tab_name == self::getActiveTab()->getName());
    }

    /**
     * Helper - tab
     *
     * @since 4.0.0
     *
     * @param  string   $slug   Tab slug
     * @return Tab              Tab
     */
    public static function getTab($slug)
    {
        return self::$tabs[$slug];
    }

    /**
     * Helper - set the default tab.
     *
     * @since 4.3.0
     *
     * @param  string   $default    Default tab slug
     */
    public static function setDefaultTab($default)
    {
        self::$default_tab = $default;
    }

    /**
     * Helper - current tab
     *
     * @since 4.0.0
     *
     * @return TabBase  Tab
     */
    public static function getActiveTab()
    {
        if (!empty(self::$active_tab)) {
            return self::$active_tab;
        }

        return (self::$active_tab = (array_key_exists(@$_GET['tab'], self::$tabs))
            ? self::$tabs[$_GET['tab']]
            : self::$tabs[self::$default_tab]
        );
    }

    /**
     * Helper - tab name
     *
     * @since 4.0.0
     *
     * @param  string   $slug   Tab slug
     * @return string           Tab name
     */
    public static function getTabName($slug)
    {
        return self::getTab($slug)->getName();
    }

    /**
     * Helper - tab exists?
     *
     * @since 4.3.0
     *
     * @param  string   $slug   Tab slug
     * @return bool
     */
    public static function tabExists($slug)
    {
        return array_key_exists($slug, self::$tabs);
    }

    /**
     * Link to documentation
     *
     * @since 4.3.0 Protected
     * @since 4.2.0
     *
     * @param  string   $define
     * @return string
     */
    protected function doc_link($define)
    {
        static $wp_f2b_ver;

        if (empty($wp_f2b_ver)) {
            $wp_f2b_ver = substr(WP_FAIL2BAN_VER, 0, strrpos(WP_FAIL2BAN_VER, '.'));
        }

        return sprintf('<a href="https://docs.wp-fail2ban.com/en/%s/defines/constants/%s.html" style="text-decoration: none;" target="_blank" title="%s">%s<span class="dashicons dashicons-external" style="vertical-align: text-bottom"></span></a>', $wp_f2b_ver, $define, __('Documentation', 'wp-fail2ban'), $define);
    }

    /**
     * Standard list of links to docs
     *
     * @since 4.3.0
     *
     * @param  array    $defines    List of defines
     * @param  bool     $para       Wrap in <p>
     * @return string   HTML
     */
    protected function see_also(array $defines, $para = true)
    {
        $html = sprintf(
            '<em>%s</em>&nbsp;&nbsp;%s',
            __('See also:', 'wp-fail2ban'),
            implode('&nbsp;/&nbsp;', array_map(function ($i) {
                return $this->doc_link($i);
            }, $defines))
        );
        if ($para) {
            $html = '<p>'.$html.'</p>';
        }
        return $html;
    }

    /**
     * id="%s" Helper
     *
     * @since 4.3.0 Moved here.
     * @since 4.0.0
     *
     * @param string $define
     *
     * @return string
     */
    protected function field_name($define)
    {
        global $wp_fail2ban;

        return 'wp-fail2ban['.join('][', $wp_fail2ban['config'][$define]['field']).']';
    }

    /**
     * name="%s" Helper
     *
     * @since 4.3.0 Moved here.
     * @since 4.0.0
     *
     * @param string $define
     *
     * @return string
     */
    protected function field_id($define)
    {
        global $wp_fail2ban;

        return join('-', $wp_fail2ban['config'][$define]['field']);
    }

    /**
     * Helper: checked()
     *
     * @since 4.3.0
     *
     * @param  string   $define
     * @param  bool     $current
     * @param  bool     $echo
     * @return mixed
     */
    protected function def_checked($define, $current = true, $echo = true)
    {
        return checked(Config::get($define), $current, $echo);
    }

    /**
     * NDEF disabled helper
     *
     * @since 4.3.0 Add $override; moved here.
     * @since 4.0.0
     *
     * @param  string   $define
     * @param  bool     $override
     * @return string
     */
    protected function ndef_disabled($define, $override = false)
    {
        return disabled(Config::def($define) || $override, true, false);
    }

    /**
     * Display standard checkbox
     *
     * @since 4.3.0
     *
     * @param string    $define     Constant
     * @param bool      $show_desc  Show description?
     * @param string    $plan       Freemius plan
     * @param bool      $echo       Echo?
     *
     * @return string   HTML
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function checkbox($define, $show_desc = true, $plan = 'bronze', $echo = true)
    {
        $html = sprintf(
            '<input type="checkbox" disabled="disabled" %s>',
            checked(constant($define), true, false)
        );
        if ($show_desc) {
            $html = '<label>'.$html.' '.$this->description($define, false).'</label>';
        }
        if ($echo) {
            echo $html;
        }
        return $html;
    }

    /**
     * Display standard text input
     *
     * @since 4.3.0
     *
     * @param string    $define     Constant
     * @param string    $value      Text value
     * @param bool      $show_desc  Show description?
     * @param string    $plan       Freemius plan
     * @param bool      $echo       Echo?
     * @param string    $cssClass   CSS class
     *
     * @return string   HTML
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function inputText($define, $value = '', $show_desc = true, $plan = 'bronze', $echo = true, $cssClass = 'regular-text')
    {
        $html = sprintf(
            '<input class="%s" type="text" value="%s" %s>',
            $cssClass,
            esc_attr($value),
            $this->ndef_disabled($define)
        );
        if ($show_desc) {
            $html = '<label>'.$html.' '.$this->description($define, false).'</label>';
        }
        if ($echo) {
            echo $html;
        }
        return $html;
    }

    /**
     * Helper: setting description
     *
     * @since 4.3.0
     *
     * @param  string   $define
     * @param  bool     $echo
     *
     * @return string
     */
    protected function description($define, $echo = true)
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
}

