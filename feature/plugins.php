<?php declare(strict_types=1);
/**
 * Library functions
 *
 * @package wp-fail2ban
 * @since   4.4.0   Require PHP 7.4
 * @since   4.2.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban\feature;

use       org\lecklider\charles\wordpress\wp_fail2ban\Syslog;

defined('ABSPATH') or exit;

/**
 * Register plugin
 *
 * @since  4.4.0    Add type hints
 * @since  4.3.2.1  Reduce maximum slug length to 200; caused "index too long" with ancient versions of MySQL
 * @since  4.2.0
 *
 * @param  string   $slug   Plugin slug. This must be the actual plugin slug. Maximum length is 200 which should be more than enough.
 * @param  string   $name   Plugin display name. This should be an unescaped string - HTML is allowed.
 *
 * @return int|false        ID
 */
function register_plugin(string $slug, string $name)
{
    global $wp_fail2ban;

    if (200 < strlen($slug)) {
        throw new \LengthException('slug too long');
    }

    if (200 < strlen($name)) {
        throw new \LengthException('name too long');
    }

    if (!array_key_exists('plugins', $wp_fail2ban) || !is_array($wp_fail2ban['plugins'])) {
        $wp_fail2ban['plugins'] = []; //@codeCoverageIgnore
    }

    if (array_key_exists($slug, $wp_fail2ban['plugins'])) {
        return $wp_fail2ban['plugins'][$slug];
    }

    if (is_null($rv = apply_filters(__FUNCTION__, null, $slug, $name))) {
        static $id = 0;

        return ($wp_fail2ban['plugins'][$slug] = [
            'id'        => ++$id,
            'name'      => $name,
            'messages'  => []
        ]);
    } else {
        return $rv; // @codeCoverageIgnore
    }
}

/**
 * Check if plugin is registered.
 *
 * @since  4.4.0    Add type hint, return type
 * @since  4.2.0
 *
 * @param  string   $plugin_slug
 *
 * @return bool
 */
function is_registered_plugin(string $plugin_slug): bool
{
    global $wp_fail2ban;

    return (array_key_exists('plugins', $wp_fail2ban) && is_array($wp_fail2ban['plugins']) && array_key_exists($plugin_slug, $wp_fail2ban['plugins']));
}

/**
 * @since  4.4.0    Add return type
 * @since  4.3.0
 *
 * @see register_message()
 *
 * @param  string   $plugin_slug
 * @param  array    $msgs
 *
 * @return void
 *
 * @codeCoverageIgnore
 */
function register_messages(string $plugin_slug, array $msgs): void
{
    foreach ($msgs as $msg) {
        register_message($plugin_slug, $msg);
    }
}

/**
 * Register plugin message.
 *
 * @since  5.0.0    Add event_desc
 * @since  4.4.0    Add type hint, return type
 * @since  4.2.0
 *
 * @param  string   $plugin_slug
 * @param  array    $msg    [
 *                      string  slug:           Message slug
 *                      string  fail:           hard|soft|extra
 *                      int     facility:       syslog facility
 *                      int     priority:       syslog priority
 *                      string  event_class:    Event Class
 *                      string  event_desc:     Event Description
 *                      int     event_id:       Event ID
 *                      string  message:        Message with placeholders
 *                          HOST:   Remote IP
 *                          USER:   Current user name
 *                      array   vars:       Array of [name => regex] pairs
 * @return void
 */
function register_message(string $plugin_slug, array $msg): void
{
    if (!is_registered_plugin($plugin_slug)) {
        throw new \InvalidArgumentException('plugin not registered');
    }

    $args = [];

    if (null === apply_filters(__FUNCTION__.'::get', null, $plugin_slug, $msg)) {
        global $wp_fail2ban;
        $event_classes = [
            'auth'      => WPF2B_EVENT_CLASS_AUTH,
            'comment'   => WPF2B_EVENT_CLASS_COMMENT,
            'password'  => WPF2B_EVENT_CLASS_PASSWORD,
            'rest'      => WPF2B_EVENT_CLASS_REST,
            'spam'      => WPF2B_EVENT_CLASS_SPAM,
            'xmlrpc'    => WPF2B_EVENT_CLASS_XMLRPC,
            'block'     => WPF2B_EVENT_CLASS_BLOCK,
            'other'     => WPF2B_EVENT_CLASS_OTHER,
        ];

        if (!array_key_exists('slug', $msg)) {
            throw new \InvalidArgumentException("Missing 'slug'");
        }
        if (!is_string($msg['slug'])) {
            throw new \InvalidArgumentException("'slug' must be string");
        }

        if (!array_key_exists('fail', $msg)) {
            throw new \InvalidArgumentException("Missing 'fail'");
        }
        if (!in_array($msg['fail'], ['hard', 'soft', 'extra'])) {
            throw new \UnexpectedValueException("'fail' must be one of 'hard', 'soft', 'extra'");
        }
        $args['fail'] = $msg['fail'];

        if (!array_key_exists('priority', $msg)) {
            throw new \InvalidArgumentException("Missing 'priority'");
        }
        if (!in_array($msg['priority'], [
            LOG_CRIT,
            LOG_ERR,
            LOG_WARNING,
            LOG_NOTICE,
            LOG_INFO,
            LOG_DEBUG
        ])) {
            throw new \UnexpectedValueException("Invalid 'priority'");
        }
        $args['priority'] = $msg['priority'];

        if (!array_key_exists('event_class', $msg)) {
            throw new \InvalidArgumentException("Missing 'event_class'");
        }
        if (!array_key_exists($event_class = strtolower($msg['event_class']), $event_classes)) {
            throw new \UnexpectedValueException("Invalid 'event_class'");
        }
        $args['class'] = $event_class;
        $event_class = $event_classes[$event_class];

        $args['desc'] = (string)($msg['event_desc'] ?? 'n/a');

        if (!array_key_exists('event_id', $msg)) {
            throw new \InvalidArgumentException("Missing 'event_id'");
        }
        if (($msg['event_id'] & 0x0000FFFF) !== $msg['event_id']) {
            throw new \UnexpectedValueException("Invalid 'event_id'");
        }
        $args['event_id'] = WPF2B_EVENT_TYPE_PLUGIN | $event_class | $msg['event_id'];

        if (!array_key_exists('message', $msg)) {
            throw new \InvalidArgumentException("Missing 'message'");
        }
        if (!is_string($msg['message'])) {
            throw new \UnexpectedValueException("Invalid 'message'");
        }
        $args['message'] = $msg['message'];

        if (!array_key_exists('vars', $msg)) {
            throw new \InvalidArgumentException("Missing 'vars'");
        }
        if (!is_array($msg['vars'])) {
            throw new \UnexpectedValueException("Invalid 'vars'");
        }
        $args['vars'] = $msg['vars'];

        $args = apply_filters(__FUNCTION__.'::set', $args, $plugin_slug, $msg);
    }

    $wp_fail2ban['plugins'][$plugin_slug]['messages'][$msg['slug']] = $args;
}

/**
 * Check if message is registered.
 *
 * NB: Assumes plugin is registered.
 *
 * @since  4.4.0    Add type hints, return type
 * @since  4.2.0
 *
 * @param  string   $plugin_slug
 * @param  string   $message_slug
 *
 * @return bool
 */
function is_registered_plugin_message(string $plugin_slug, string $message_slug): bool
{
    global $wp_fail2ban;

    return (isset($wp_fail2ban['plugins'][$plugin_slug]['messages']) &&
            array_key_exists($message_slug, $wp_fail2ban['plugins'][$plugin_slug]['messages']));
}

/**
 * Log plugin message.
 *
 * @since  4.4.0    Add type hints, return type
 * @since  4.3.4.0  Refactor to use Syslog::single
 * @since  4.2.0
 *
 * @param  string   $plugin_slug    Plugin slug for registered message
 * @param  string   $message_slug   Message slug for registered message
 * @param  array    $vars           Substitution vars
 *
 * @return void
 */
function log_message(string $plugin_slug, string $message_slug = null, array $vars = []): void
{
    global $wp_fail2ban;

    if (!is_registered_plugin($plugin_slug)) {
        throw new \InvalidArgumentException('plugin not registered');
    }

    if (!is_registered_plugin_message($plugin_slug, $message_slug)) {
        throw new \InvalidArgumentException('message not registered');
    }

    $args = $wp_fail2ban['plugins'][$plugin_slug]['messages'][$message_slug];
    $msg = $args['message'];
    foreach (array_keys($args['vars']) as $name) {
        if (array_key_exists($name, $vars)) {
            $msg = str_replace("___{$name}___", $vars[$name], $msg);
        }
    }

    Syslog::single($args['priority'], "($plugin_slug) $msg", sprintf('WP_FAIL2BAN_PLUGIN_%s_LOG', strtoupper($args['class'])));

    do_action(__FUNCTION__, $plugin_slug, $message_slug, $vars, $args);
}

/**
 * Hook event-to-name filter.
 *
 * @since  5.0.0
 *
 * @param  array    $event2name
 *
 * @return array
 */
function event2name(array $event2name): array
{
    global $wp_fail2ban;

    $event2name['plugins'] = [];

    if (array_key_exists('plugins', $wp_fail2ban)) {
        foreach ($wp_fail2ban['plugins'] as $plugin_slug => $info) {
            $event2name['plugins'][$info['id']] = [];
            foreach ($info['messages'] as $message_slug => $message_info) {
                $event2name['plugins'][intval($info['id'])][$message_info['event_id']] = strtoupper($plugin_slug.'_'.$message_slug);
            }
        }
    }

    return $event2name;
}

