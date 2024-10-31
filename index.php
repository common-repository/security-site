<?php
/*
Plugin Name: Security Site
Description: AntiSpam, Check Browser, Security Site.
Author: MoiVui
Author URI: http://photoboxone.com/donate/?developer=moivui
Version: 1.0.3
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') or die();

function SecuritySite_index()
{
	return __FILE__;
}

require( dirname(__FILE__). '/includes/functions.php');

if( is_admin() ) {
	
	
	
} else {

	SecuritySite_include( 'site.php' );
	
}

