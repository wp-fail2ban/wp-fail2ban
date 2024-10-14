<?php declare(strict_types=1);
/**
 * WP fail2ban
 *
 * Outside the guard for building
 *
 * @package wp-fail2ban
 * @since   4.0.5
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

// @codeCoverageIgnoreStart

if (!defined('WP_FAIL2BAN_VER')) {
    define('WP_FAIL2BAN_VER', '5.0.1');
}
if (!defined('WP_FAIL2BAN_VER_SHORT')) {
    define('WP_FAIL2BAN_VER_SHORT', '5');
}
if (!defined('WP_FAIL2BAN_VER_MEDIUM')) {
    define('WP_FAIL2BAN_VER_MEDIUM', '5.0');
}
if (!defined('WP_FAIL2BAN_VER2')) {
    define('WP_FAIL2BAN_VER2', '5.0');
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

// @codeCoverageIgnoreEnd

