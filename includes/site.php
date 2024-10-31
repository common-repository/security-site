<?php
defined('ABSPATH') or die();

function SecuritySite_check_now()
{
    if( 
        is_admin() 
        || preg_match( '/(wp-login).php/i', $_SERVER['REQUEST_URI'] )
    ) {
        return;
    }

    if( $_SERVER['QUERY_STRING'] != '' ) {
        $s = $_SERVER['QUERY_STRING'];

        if( SecuritySite_value_has_chars($s) ) {
            SecuritySite_redirect_to();
        }
        
        $allow_keys = explode( ',', 's,redirect_to' );
        if( count($_GET) ) {
            foreach ($_GET as $key => $value) {
    
                if( in_array( $key, $allow_keys ) == false ) {
                    SecuritySite_redirect_to();
                }
                
                if( $value!='' ) {
                    if( SecuritySite_value_has_chars($value) ) {
                        SecuritySite_redirect_to();
                    }
                    if( $value != sanitize_text_field($value) ) {
                        SecuritySite_redirect_to();
                    }
                    if( $value != wp_strip_all_tags($value) ) {
                        SecuritySite_redirect_to();
                    }
                }
            }
        }
    }

    if( count($_POST) ) {
        foreach( $_POST as $key => $value) {
            if( SecuritySite_value_has_chars($value) ) {
                $_POST[$key] = wp_strip_all_tags( sanitize_text_field($value) );
                break;
            }
        }
    }
    else if
    ( 
        preg_match( '/wp-json/i', $_SERVER['REQUEST_URI'] ) 
        && preg_match( '/contact-form-7/i', $_SERVER['REQUEST_URI'] )
    )
    {
        SecuritySite_redirect_to( home_url() );
    }

}
add_action('after_setup_theme', 'SecuritySite_check_now', 1 );

function SecuritySite_check_sendmail( $phpmailer = false )
{
    if( $phpmailer == false ) return $phpmailer;

    if( count($_POST) ) {
        foreach( $_POST as $key => $value) {
            if( SecuritySite_value_has_chars($value) ) {
                $phpmailer->Body = wp_strip_all_tags( $phpmailer->Body );
                break;
            }
        }
    }

    return $phpmailer;
}
add_action( 'phpmailer_init', 'SecuritySite_check_sendmail', 1 );

function SecuritySite_value_replace_chars( $value = '' )
{
    $chars = explode(',', '],[,},{,(,),|,<,>,",\',"' );
    
    return str_replace( $chars, '', $value );
}

function SecuritySite_value_has_chars( $value = '' )
{
    $chars = explode(',', '],[,},{,(,),",|,\',<,>,/,?' );
    
    foreach( $chars as $char ) {
        if( strpos( $value, $char ) >-1 ) {
            return true;
        }
    }
    
    return false;
}

function SecuritySite_redirect_to( $url = '' )
{
    if( $url == '' ) {
        $url = SecuritySite_get_current_url( $has_query_string = false );
    }
    
    wp_redirect( esc_url( $url ) );
    die();
    exit;
}