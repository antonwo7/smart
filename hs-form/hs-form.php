<?php
/*
Plugin Name: HS-Form
Plugin URI: http://lynart.com
Description: Краткое описание плагина.
Version: 1.0
Author: Антон
Author URI: http://lynart.com
*/

define( 'HSFORM_DIR', untrailingslashit( dirname( __FILE__ ) ) );
define( 'HSFORM_CSS', plugins_url( '/assets/css/styles.css', __FILE__) );

register_activation_hook(__FILE__, 'hsf_activation' );
add_action( 'admin_menu', 'hsf_create_menu' ); 
add_action( 'admin_init', 'hsf_register_settings' );
add_action( 'wp_enqueue_scripts',  'hsf_styles' );

require_once  HSFORM_DIR . '/classes/HSForm.php';

if (!function_exists('hsf_styles')) {
	function hsf_styles(){
		wp_enqueue_style( 'styles', HSFORM_CSS);
	}
}

if (!function_exists('hsf_activation')) {
	function hsf_activation() {
		$hsf_params = array(
			'hsf_link' => '',
			'hsf_key' => '',
			'hsf_email_admin' => ''
		);
		
		update_option( 'hsf_params', $hsf_params );
	}
}

if (!function_exists('hsf_create_menu')) {
	function hsf_create_menu(){
		add_menu_page( __('HS-Form Options'), __('HS-Form Options'), 'manage_options', 'hsf_params_menu', 'hsf_params_set_function', plugins_url( 'images/icon.png', __FILE__ ) ); 	
	}
}

if (!function_exists('hsf_params_set_function')) {
	function hsf_params_set_function(){
		include 'templates/admin/hsform-params.php';
	}
}

if (!function_exists('hsf_register_settings')) {
	function hsf_register_settings(){
		register_setting( 'hsf_params_group', 'hsf_params', 'hsf_params_sanitize' );
	}
}

if (!function_exists('hsf_register_settings')) {
	function hsf_register_settings( $input ) {
		foreach($input as $key => $value){
			$input[$key] = sanitize_text_field( $input[$key] );
		}

		return $input;
	}
}

register_deactivation_hook( __FILE__ , 'hsf_plugin_deactivate' );
if (!function_exists('hsf_plugin_deactivate')) {
	function hsf_plugin_deactivate() {
		delete_option( 'hsf_params' );
	}
}

if (!function_exists('e')) {
	function e($value){
		return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
	}
}


add_shortcode( 'hsform' , 'hsf_shortcode_function' );
if (!function_exists('hsf_shortcode_function')) {
	function hsf_shortcode_function($atts, $content){
		$class = $atts['class'];
		$button_text = $atts['buttontext'];
		
		$hsf_params = get_option( 'hsf_params' );
		extract($hsf_params);

		ob_start();
		include 'templates/hsform-shortcode.php';
		$out = ob_get_contents();
		ob_end_clean();
		
		return $out;
	}
}

?>