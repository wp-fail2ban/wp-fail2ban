<?php
/**
 * WP fail2ban
 *
 * Outside the guard for building
 *
 * @since 4.0.5
 *
 * @package wp-fail2ban
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

// @codeCoverageIgnoreStart

if (!defined('WP_FAIL2BAN_VER')) {
    define('WP_FAIL2BAN_VER', '4.3.0.9');
}
if (!defined('WP_FAIL2BAN_VER_SHORT')) {
    define('WP_FAIL2BAN_VER_SHORT', '4.3');
}
if (!defined('WP_FAIL2BAN_DIR')) {
    define('WP_FAIL2BAN_DIR', __DIR__);
}
if (!defined('WP_FAIL2BAN_FILE')) {
    define('WP_FAIL2BAN_FILE', __DIR__.'/wp-fail2ban.php');
}
if (!defined('WP_FAIL2BAN_NS')) {
    define('WP_FAIL2BAN_NS', __NAMESPACE__);
}

