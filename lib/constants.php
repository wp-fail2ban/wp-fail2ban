<?php
/**
 * Constants
 *
 * @package wp-fail2ban
 * @since   4.2.0
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

// @codeCoverageIgnoreStart

defined('ABSPATH') or exit;

// phpcs:disable Generic.Functions.FunctionCallArgumentSpacing
// @codeCoverageIgnore
/**
31 | Test
30 | Plugin
29 |
28 |
27 |
26 |
25 |
24 |
---
23 | Event Class
22 | ..
21 | ..
20 | ..
19 | ..
18 | ..
17 | ..
16 | ..
---
15 | ID
14 | ..
13 | ..
12 | ..
11 | ..
10 | ..
09 | ..
08 | ..
---
07 | ..
06 | ..
05 | ..
04 | ..
03 | ..
02 | ..
01 | Failure
00 | Success
*/

// @codeCoverageIgnoreStart
define('WPF2B_EVENT_A', 0x0004);
define('WPF2B_EVENT_B', 0x0008);
define('WPF2B_EVENT_C', 0x0010);
define('WPF2B_EVENT_D', 0x0020);
define('WPF2B_EVENT_E', 0x0040);
define('WPF2B_EVENT_F', 0x0080);
define('WPF2B_EVENT_G', 0x0100);
define('WPF2B_EVENT_H', 0x0200);
define('WPF2B_EVENT_I', 0x0400);
define('WPF2B_EVENT_J', 0x0800);
define('WPF2B_EVENT_K', 0x1000);
define('WPF2B_EVENT_L', 0x2000);
define('WPF2B_EVENT_M', 0x4000);
define('WPF2B_EVENT_N', 0x8000);

define('WPF2B_EVENT_CLASS_AUTH',                0x00010000);
define('WPF2B_EVENT_CLASS_COMMENT',             0x00020000);
define('WPF2B_EVENT_CLASS_XMLRPC',              0x00040000);
define('WPF2B_EVENT_CLASS_PASSWORD',            0x00080000);
define('WPF2B_EVENT_CLASS_REST',                0x00100000);    /** @since 4.1.0 */
define('WPF2B_EVENT_CLASS_SPAM',                0x00200000);    /** @since 4.2.0 */
define('WPF2B_EVENT_CLASS_BLOCK',               0x00400000);    /** @since 4.3.0 */
define('WPF2B_EVENT_TYPE_PLUGIN',               0x40000000);    /** @since 4.2.0 */
define('WPF2B_EVENT_TYPE_TEST',                 0x80000000);    /** @since 4.2.0 */
define('WPF2B_EVENT_SUCCESS',                   0x00000001);    /** @since 4.3.0 */
define('WPF2B_EVENT_FAILURE',                   0x00000002);    /** @since 4.3.0 */


// @codeCoverageIgnore
/**
 *
 */
// @codeCoverageIgnoreStart
define('WPF2B_EVENT_ACTIVATED',                 0xffffffff);

// @codeCoverageIgnore
/**
 * Auth
 */
// @codeCoverageIgnoreStart
define('WPF2B_EVENT_AUTH_OK',                   WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_SUCCESS);
define('WPF2B_EVENT_AUTH_FAIL',                 WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_FAILURE);
define('WPF2B_EVENT_AUTH_BLOCK_USER',           WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_CLASS_BLOCK | WPF2B_EVENT_A);
define('WPF2B_EVENT_AUTH_BLOCK_USER_ENUM',      WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_CLASS_BLOCK | WPF2B_EVENT_B);
define('WPF2B_EVENT_AUTH_EMPTY_USER',           WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_FAILURE | WPF2B_EVENT_C);
define('WPF2B_EVENT_AUTH_BLOCK_USERNAME_LOGIN', WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_CLASS_BLOCK | WPF2B_EVENT_D); /** @since 4.3.0 */

// @codeCoverageIgnore
/**
 * Comment
 */
// @codeCoverageIgnoreStart
define('WPF2B_EVENT_COMMENT',                   WPF2B_EVENT_CLASS_COMMENT | WPF2B_EVENT_SUCCESS);
define('WPF2B_EVENT_COMMENT_SPAM',              WPF2B_EVENT_CLASS_COMMENT | WPF2B_EVENT_CLASS_SPAM);
//               comment extra
define('WPF2B_EVENT_COMMENT_NOT_FOUND',         WPF2B_EVENT_CLASS_COMMENT | WPF2B_EVENT_FAILURE | WPF2B_EVENT_A);
define('WPF2B_EVENT_COMMENT_CLOSED',            WPF2B_EVENT_CLASS_COMMENT | WPF2B_EVENT_FAILURE | WPF2B_EVENT_B);
define('WPF2B_EVENT_COMMENT_TRASH',             WPF2B_EVENT_CLASS_COMMENT | WPF2B_EVENT_FAILURE | WPF2B_EVENT_C);
define('WPF2B_EVENT_COMMENT_DRAFT',             WPF2B_EVENT_CLASS_COMMENT | WPF2B_EVENT_FAILURE | WPF2B_EVENT_D);
define('WPF2B_EVENT_COMMENT_PASSWORD',          WPF2B_EVENT_CLASS_COMMENT | WPF2B_EVENT_CLASS_PASSWORD | WPF2B_EVENT_FAILURE | WPF2B_EVENT_E);

// @codeCoverageIgnore
/**
 * XML-RPC
 */
// @codeCoverageIgnoreStart
define('WPF2B_EVENT_XMLRPC_PINGBACK',           WPF2B_EVENT_CLASS_XMLRPC | WPF2B_EVENT_SUCCESS | WPF2B_EVENT_A);
define('WPF2B_EVENT_XMLRPC_PINGBACK_ERROR',     WPF2B_EVENT_CLASS_XMLRPC | WPF2B_EVENT_FAILURE | WPF2B_EVENT_A);
define('WPF2B_EVENT_XMLRPC_MULTI_AUTH_FAIL',    WPF2B_EVENT_CLASS_XMLRPC | WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_FAILURE | WPF2B_EVENT_B);
define('WPF2B_EVENT_XMLRPC_AUTH_OK',            WPF2B_EVENT_CLASS_XMLRPC | WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_SUCCESS);
define('WPF2B_EVENT_XMLRPC_AUTH_FAIL',          WPF2B_EVENT_CLASS_XMLRPC | WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_FAILURE);

// @codeCoverageIgnore
/**
 * Password
 */
// @codeCoverageIgnoreStart
define('WPF2B_EVENT_PASSWORD_REQUEST',          WPF2B_EVENT_CLASS_PASSWORD | WPF2B_EVENT_SUCCESS);

// @codeCoverageIgnore
/**
 * REST
 * @since 4.1.0
 */
// @codeCoverageIgnoreStart
define('WPF2B_EVENT_REST_AUTH_OK',              WPF2B_EVENT_CLASS_REST | WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_SUCCESS);
define('WPF2B_EVENT_REST_AUTH_FAIL',            WPF2B_EVENT_CLASS_REST | WPF2B_EVENT_CLASS_AUTH | WPF2B_EVENT_FAILURE);

// @codeCoverageIgnore
/**
 *
 */
// @codeCoverageIgnoreStart
define('WPF2B_EVENT_DEACTIVATED',               0x00000000);

// @codeCoverageIgnore
/**
 * @deprecated 4.3.0
 */
// @codeCoverageIgnoreStart
define('WPF2B_EVENT_AUTH_BLOCK_USER__',         WPF2B_EVENT_CLASS_AUTH | 0x0004);   /** @deprecated 4.3.0 */
define('WPF2B_EVENT_AUTH_BLOCK_USER_ENUM__',    WPF2B_EVENT_CLASS_AUTH | 0x0008);   /** @deprecated 4.3.0 */
define('WPF2B_EVENT_COMMENT_SPAM__',            WPF2B_EVENT_CLASS_COMMENT | WPF2B_EVENT_CLASS_SPAM | 0x0001); /** @deprecated 4.3.0 */
define('WPF2B_EVENT_COMMENT_NOT_FOUND__',       WPF2B_EVENT_CLASS_COMMENT | 0x0002); /** @deprecated 4.3.0 */
define('WPF2B_EVENT_COMMENT_CLOSED__',          WPF2B_EVENT_CLASS_COMMENT | 0x0004); /** @deprecated 4.3.0 */
define('WPF2B_EVENT_COMMENT_TRASH__',           WPF2B_EVENT_CLASS_COMMENT | 0x0010); /** @deprecated 4.3.0 */
define('WPF2B_EVENT_COMMENT_DRAFT__',           WPF2B_EVENT_CLASS_COMMENT | 0x0010); /** @deprecated 4.3.0 */
define('WPF2B_EVENT_COMMENT_PASSWORD__',        WPF2B_EVENT_CLASS_COMMENT | WPF2B_EVENT_CLASS_PASSWORD | 0x0020); /** @deprecated 4.3.0 */
define('WPF2B_EVENT_XMLRPC_PINGBACK__',         WPF2B_EVENT_CLASS_XMLRPC | 0x0001);
define('WPF2B_EVENT_XMLRPC_PINGBACK_ERROR__',   WPF2B_EVENT_CLASS_XMLRPC | 0x0002);
define('WPF2B_EVENT_XMLRPC_MULTI_AUTH_FAIL__',  WPF2B_EVENT_CLASS_XMLRPC | WPF2B_EVENT_CLASS_AUTH | 0x0004); /** @deprecated 4.3.0 */
define('WPF2B_EVENT_XMLRPC_AUTH_OK__',          WPF2B_EVENT_CLASS_XMLRPC | WPF2B_EVENT_CLASS_AUTH | 0x0008); /** @deprecated 4.3.0 */
define('WPF2B_EVENT_XMLRPC_AUTH_FAIL__',        WPF2B_EVENT_CLASS_XMLRPC | WPF2B_EVENT_CLASS_AUTH | 0x0010); /** @deprecated 4.3.0 */
// phpcs:enable

