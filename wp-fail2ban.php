<?php declare(strict_types=1);
/*
 * Plugin Name: WP fail2ban
 * Plugin URI: https://wp-fail2ban.com/
 * Description: Write a myriad of WordPress events to syslog for integration with fail2ban.
 * Text Domain: wp-fail2ban
 * Version: 5.2.1
 * Author: Charles Lecklider
 * Author URI: https://invis.net/
 * License: GPLv3
 * SPDX-License-Identifier: GPL-3.0
 * Requires PHP: 7.4
 * Network: true
 *
   */

/*
 *  Copyright 2012-23  Charles Lecklider  (email : wordpress@invis.net)
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  See <https://www.gnu.org/licenses/> for a copy of the GNU General
 *  Public License.
 *
 *  ADDITIONAL TERMS per GNU General Public License version 3 Section 7
 *
 *  The origin of this software must not be misrepresented; you must
 *  not claim that you wrote the original software. Altered source
 *  versions must be plainly marked as such, and must not be
 *  misrepresented as being the original software. This notice may
 *  not be removed or altered from any source distribution. See
 *  <https://wp-fail2ban.com/license/> for more information.
 */

/**
 * WP fail2ban
 *
 * @package wp-fail2ban
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

// @codeCoverageIgnoreStart

defined('ABSPATH') or exit;

require_once __DIR__.'/constants.php';
require_once __DIR__.'/freemius.php';

// @codeCoverageIgnoreEnd

