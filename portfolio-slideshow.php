<?php
/*
 * Plugin Name: Portfolio Slideshow
 * Plugin URI: http://wordpress.org/plugins/portfolio-slideshow
 * Description: Build elegant, responsive slideshows in seconds.
 * Author: George Gecewicz
 * Version: 1.9.9
 * Author URI: http://twitter.com/ggwicz
 * License: GPLv2 or later
 * Text Domain: portfolio-slideshow
 *
 * Copyright 2015 George Gecewicz
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 */

define( '__PORTFOLIO_SLIDESHOW_PLUGIN_FILE__', __FILE__ );

if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
		esc_html_e( 'Portfolio Slideshow requires PHP 5.3 or higher. Contact your web host or system administrator to upgrade the active version of PHP on your site.', 'portfolio-slideshow' );
		exit();

		deactivate_plugins( __PORTFOLIO_SLIDESHOW_PLUGIN_FILE__ );
	}
}

require_once( dirname( __PORTFOLIO_SLIDESHOW_PLUGIN_FILE__ ) . '/lib/Plugin.php' );