<?php declare(strict_types=1);
/**
 * Back-Compat
 *
 * @package wp-fail2ban
 * @since  4.4.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

/**
 * Shim: Wrapper for \openlog
 *
 * @since  4.4.0    Refactor for Syslog class; add type hint, return type
 * @since  3.5.0    Refactored for unit testing
 *
 * @param  string   $log
 *
 * @return bool
 */
function openlog(string $log = 'WP_FAIL2BAN_AUTH_LOG'): bool
{
    return Syslog::open($log);
}

/**
 * Shim: Wrapper for \syslog
 *
 * @since  4.4.0    Refactor for Syslog class; add type hint, return type
 * @since  3.5.0
 *
 * @param  int          $level
 * @param  string       $msg
 * @param  string|null  $remote_addr
 *
 * @return void
 */
function syslog(int $level, string $msg, string $remote_addr = null): bool
{
    return Syslog::write($level, $msg, $remote_addr);
}

/**
 * Shim: Wrapper for \closelog
 *
 * @since  4.4.0    Refactor for Syslog class; add return type
 * @since  4.3.0
 *
 * @return void
 */
function closelog(): bool
{
    return Syslog::close();
}

