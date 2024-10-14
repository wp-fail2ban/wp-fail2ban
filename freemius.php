<?php

declare ( strict_types = 1 );
/**
 * WP fail2ban premium
 *
 * @package wp-fail2ban
 * @since   4.3.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

// @codeCoverageIgnoreStart
defined( 'ABSPATH' ) or exit;
/**
 * Freemius integration
 *
 * @since 4.0.0
 */

if ( function_exists( __NAMESPACE__ . '\\wf_fs' ) ) {
    wf_fs()->set_basename( true, WP_FAIL2BAN_FILE );
    return;
} else {
    /**
     * Create a helper function for easy SDK access.
     *
     * @since 4.0.0
     */
    function wf_fs()
    {
        global  $wf_fs ;
        
        if ( !isset( $wf_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_3072_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_3072_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once __DIR__ . '/vendor/freemius/wordpress-sdk/start.php';
            $wf_fs = fs_dynamic_init( array(
                'id'                             => '3072',
                'slug'                           => 'wp-fail2ban',
                'type'                           => 'plugin',
                'public_key'                     => 'pk_146d2c2a5bee3b157e43501ef8682',
                'is_premium'                     => false,
                'has_addons'                     => true,
                'has_paid_plans'                 => true,
                'trial'                          => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'menu'                           => array(
                'slug'    => 'wp-fail2ban-menu',
                'contact' => !defined( 'WP_FAIL2BAN_FREE_ONLY' ) || false === WP_FAIL2BAN_FREE_ONLY,
                'support' => true,
                'network' => true,
            ),
                'bundle_license_auto_activation' => true,
                'is_live'                        => true,
            ) );
        }
        
        return $wf_fs;
    }
    
    // Init Freemius.
    $fs = wf_fs();
    // Set currency to GBP
    $fs->add_filter( 'default_currency', function () {
        return 'gbp';
    } );
    // Set custom icon
    $fs->add_filter( 'plugin_icon', function () {
        return __DIR__ . '/assets/icon.png';
    } );
    // Set forum URL
    $fs->add_filter( 'support_forum_url', function () {
        return 'https://forums.invis.net/c/wp-fail2ban/';
    } );
    $fs->add_filter( 'show_delegation_option', '__return_false' );
    $fs->add_filter( 'enable_per_site_activation', '__return_false' );
    
    if ( !$fs->is_paying() && defined( 'WP_FAIL2BAN_FREE_ONLY' ) && false !== WP_FAIL2BAN_FREE_ONLY ) {
        $fs->add_filter(
            'show_admin_notice',
            function ( $show, array $msg ) {
            return 'promotion' != $msg['type'];
        },
            WP_FS__DEFAULT_PRIORITY,
            2
        );
    } else {
        // TODO: $fs->add_filter('trial_promotion_message',
    }
    
    $fs->override_i18n( array(
        'yee-haw' => __( 'Congratulations', 'wp-fail2ban' ),
    ) );
    // Signal that SDK was initiated.
    do_action( 'wf_fs_loaded' );
    require_once __DIR__ . '/functions.php';
    require_once __DIR__ . '/init.php';
}
