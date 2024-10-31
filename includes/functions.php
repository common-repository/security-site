<?php
defined('ABSPATH') or die();

function SecuritySite_url( $path = '' )
{
	return plugins_url( $path, SecuritySite_index());
}

function SecuritySite_path( $path = '' )
{
	return dirname(SecuritySite_index()) . ( substr($path,0,1) !== '/' ? '/' : '' ) . $path;
}

function SecuritySite_include( $path_file = '' )
{
	if( $path_file!='' && file_exists( $p = SecuritySite_path('includes/'.$path_file ) ) ) {
		require $p;
		return true;
	}
	return false;
}

function SecuritySite_get_current_url( $has_query_string = true )
{
	$s 		  = $_SERVER;
	$ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
	$host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;

	$uri	=  $s['REQUEST_URI'];
	if( $has_query_string == false ) {
		$uris = explode( '?', $uri );
		$uri = $uris[0];
	}
	
	return esc_url( $protocol . '://' . $host . $uri );
}