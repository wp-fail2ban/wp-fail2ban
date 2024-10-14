<?php
/*
 * Plugin Name: WP fail2ban
 * Plugin URI: https://wp-fail2ban.com/
 * Description: Write a myriad of WordPress events to syslog for integration with fail2ban.
 * Text Domain: wp-fail2ban
 * Version: 4.3.0.9
 * Author: Charles Lecklider
 * Author URI: https://invis.net/
 * License: GPLv2
 * SPDX-License-Identifier: GPL-2.0
 * Requires PHP: 5.6
 * Network: true
 *
  */

/*
 *  Copyright 2012-20  Charles Lecklider  (email : wordpress@charles.lecklider.org)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License, version 2, as
 *  published by the Free Software Foundation.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * WP fail2ban
 *
 * @package wp-fail2ban
 */
namespace org\lecklider\charles\wordpress\wp_fail2ban;

// @codeCoverageIgnoreStart

defined('ABSPATH') or exit;

require_once 'constants.php';
require_once 'freemius.php';

