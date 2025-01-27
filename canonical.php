<?php declare(strict_types=1);
/**
 * WP fail2ban canonical updater
 *
 * @package wp-fail2ban
 * @since   5.4.0
 */
namespace    org\lecklider\charles\wordpress\wp_fail2ban;

defined('ABSPATH') or exit;

function canonical_has_composer(): bool
{
    if (defined('WP_FAIL2BAN_USING_COMPOSER') &&
        (true === WP_FAIL2BAN_USING_COMPOSER || is_string(WP_FAIL2BAN_USING_COMPOSER)))
    {
        return true;
    }

    $dirs = [
        WP_PLUGIN_DIR,
        WP_CONTENT_DIR,
        ABSPATH,
        dirname(ABSPATH)
    ];

    foreach ($dirs as $dir) {
        if (file_exists("{$dir}/composer.json")) {
            return true;
        }
    }
    return false;
}

function canonical_has_aspireupdate(): bool
{
    return class_exists('AspireUpdate\Controller', false);
}

function canonical_has_gitupdater(): bool
{
    return class_exists('Fragen\Git_Updater\Bootstrap', false);
}

/**
 * Hook: plugins_loaded
 *
 * Run slightly earlier than the slighly earlier hook
 *
 * @since  5.4.0
 *
 * @return void
 */
function plugins_loaded__earlier(): void
{
    /**
     * If the Git Updater plugin is not installed, then we need to set up the update checker.
     */
    if (!canonical_has_composer() &&
        !canonical_has_aspireupdate() &&
        !canonical_has_gitupdater())
    {
        require_once "vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php";

        $myUpdateChecker = \YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
            'https://github.com/wp-fail2ban/wp-fail2ban/',
            WP_FAIL2BAN_FILE,
            'wp-fail2ban'
        );

        /**
         * If the version is not a pre-release version, then we need to set the branch to master.
         */
        if (false === strpos(WP_FAIL2BAN_VER, '-')) {
            //Set the branch that contains the stable release.
            $myUpdateChecker->setBranch('master');
        }
    }
}
add_action('plugins_loaded', __NAMESPACE__.'\plugins_loaded__earlier', 8);

