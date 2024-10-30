<?php
/**
 * Plugin Name: l7 Login Customizer
 * Plugin URI: https://layer7web.com
 * Description: For customizing the login, logout, and register pages. Add a custom logo and background image easily. 
 * Version: 2.1.4
 * Author: Jeffrey S. Mattson
 * Author URI: https://layer7web.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/*
Copyright 2015 Jeffrey S. Mattson (email : jeff@fusioncode.org)
This program is free software; you can redistribute it and/ or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

See the GNU General Public License for more details. You should
have received a copy of the GNU General Public License along with this
program; if not, write to the Free Software Foundation, Inc.,
51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( is_admin() ){
	include_once( plugin_dir_path( __FILE__ ) . 'includes/admin-functions.php' );
	include_once( plugin_dir_path( __FILE__ ) . 'includes/options-page.php' );
}

if ( ! class_exists( 'JsmGenericClass' )  ) {
	class JsmGenericClass {

		/**
		 * Adds action to insert the custom css into the head of the login page. Adds filters to
		 * change the header URL as well as the header title and creates a settings link for the
		 * plugin page for easy navigation. 
		 */
		public function __construct() {
			add_action( 'login_head', array( $this, 'jsm_my_login_head' ) );
			add_filter( 'login_headerurl', array( $this, 'l7wc_logo_link_change' ), 15 );
			add_filter( 'login_headertitle', array( $this, 'l7wc_logo_title' ), 15 );
			add_action( 'admin_enqueue_scripts', array( $this, 'jsm_custom_login_options_enqueue_scripts' ), 15 );

			$plugin = plugin_basename( __FILE__ );
			add_filter( "plugin_action_links_$plugin", array( $this, 'jsm_custom_login_settings_link') );
		}

		/**
		 * Changes the link of the logo.  
		 * @return string 
		 */
		public function l7wc_logo_link_change(){
			$options = get_option( 'jsm_custom_login_options' );
			return $options['l7wc_link_url'];
		}

		/**
		 * Changes the hover title.
		 * @return string 
		 */
		public function l7wc_logo_title(){
			$options = get_option( 'jsm_custom_login_options' );
			return $options['l7wc_hover_title'];
		}

		/**
		 * Takes an array of links for the plugin page and adds a new settings link to it
		 * It will display on the "installed plugins page"
		 * @param  [array] $links Array of links for the plugins page.
		 * @return [array]        Returns the link array with the new link added.
		 */
		function jsm_custom_login_settings_link( $links ) {
			$url = explode( '/', plugin_basename( __FILE__ ) );
			$plugin_name = $url[0];
			$settings_link = '<a href="options-general.php?page=' . esc_attr( $plugin_name ) . '/includes/options-page">Settings</a>';
			array_unshift( $links, $settings_link );
			return $links;
		}

		/**
		 * Uploader pop-up box, bootstrap, font-awesome, main-css. 
		 * Enqueue the scripts and styles using the Admin hook only enqueued on the Admin side
		 * and only on the plugins settings page.
		 * @return void
		 */
		public function jsm_custom_login_options_enqueue_scripts() {
			$url = explode( '/', plugin_basename( __FILE__ ) );
			$plugin_name = $url[0];
			$settings_page_url = 'settings_page_' . $plugin_name . '/includes/options-page';
			wp_register_script( 'l7wc-upload', plugins_url( 'assets/js/jsm-upload.js', __FILE__ ), array('jquery', 'media-upload', 'thickbox') );
			wp_register_script( 'l7wc-bootstrap', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ), array('jquery') );
			wp_register_script( 'l7wc-bootstrap-color', plugins_url( 'assets/dist/js/bootstrap-colorpicker.js', __FILE__ ), array('jquery') );
			wp_register_script( 'l7wc-bootstrap-checkbox', plugins_url( 'assets/js/bootstrap-checkbox.min.js', __FILE__ ), array('jquery') );
			if ( $settings_page_url == get_current_screen() -> id ) {
				wp_enqueue_script( 'l7wc-upload' );
				wp_enqueue_script( 'l7wc-bootstrap' );
				wp_enqueue_script( 'l7wc-bootstrap-color' );
				wp_enqueue_script( 'l7wc-bootstrap-checkbox' );
			}

			// For Font Awesome, Bootstrap, and main css styles.
			wp_register_style( 'jsm-font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
			wp_register_style( 'jsm-main-css' , plugins_url( 'assets/css/jsm-main.css', __FILE__ ) );
			wp_register_style( 'bootstrap-css' , plugins_url( 'assets/css/bootstrap.css', __FILE__ ) );
			wp_register_style( 'bootstrap-color', plugins_url( 'assets/dist/css/bootstrap-colorpicker.css', __FILE__ ) );
			if ( $settings_page_url == get_current_screen() -> id ) {
				wp_enqueue_style( 'jsm-font-awesome' );
				wp_enqueue_style( 'bootstrap-css' );
				wp_enqueue_style( 'bootstrap-color' );
				wp_enqueue_style( 'jsm-main-css' );
			}
		}

		/**
		 * This is where are the settings are put together into CSS. The settings values are inserted into
		 * the content variable and returned as sanitized CSS code.
		 * @return [html]  Sanitized css inside of <style> tags
		 */
		public function jsm_my_login_head() {
			$options = get_option( 'jsm_custom_login_options' );

			/**
			 * If there is not background image url selected (on install) then don't write anything into the css.
			 * This way the standard wp logo will show on install.
			 */
			if ( $options['text_string'] != '' ){

				// CSS values for the logo image
				$login_logo_image_css_values = 'background-image: url(' . $options['text_string'] . ');';
			}
			else {
				$login_logo_image_css_values = '';
			}

			/**
			 * Size for logo image. Backgound-size take two values. width and height. 
			 */
			$login_logo_image_css_values .= 'background-size:' . $options['logo_width'] . ' ' . $options['logo_height'] . ';';

			/**
			 * Logo image container height. Controls the distance between top of text fields form and the logo
			 */
			$login_logo_image_container_css_values = 'height:' . $options['l7wc_top_form_space'] . ' !important;';
			$login_logo_image_container_css_values .= 'width:inherit;';

			// Login form background
			$login_logo_form_background = 'background-color:' . $options['form_background'] . ';';
			$login_logo_form_background .= 'padding:30px 0 0;';
			$login_logo_form_background .= 'margin-top:30px;';

			// Login small form background
			$login_logo_small_form_background = 'background-color:' . $options['small_form_background'] . ';';

			// Link hover color
			$login_logo_form_link_hover = 'color:' . $options['link_text_hover_color'] . ';';

			/**
			 * Logic to decide whether or not to hide the links.  If the input is 1 then the links should
			 * be hidden.
			 * @var string
			 */
			if ('1' == $options['login_logo_hide_link'] ){
				$login_logo_hide_link = 'display:none;';
			}
			else {
				$login_logo_hide_link = '';
			}

			/**
			 * Logic to decide whether or not to hide the backtoblog link.  If the input is 1 then the links should
			 * be hidden.
			 * @var string
			 */
			if ('1' == $options['login_logo_hide_backtoblog'] ){
				$login_logo_hide_backtoblog = 'display:none;';
			}
			else {
				$login_logo_hide_backtoblog = '';
			}

			//Custom CSS
			$login_logo_custom_css = $options['text_area'];

			// If the text field is not blank. Then add the hex value.
			if ( $options['link_text_color'] != '' ){
				$login_link_css_values = 'color:' . $options['link_text_color'] . ';';
			}

			// Color of the page background
			if ( $options['bk_color'] != '' ){
				$body_html_css_values .= 'background-color:' . $options['bk_color'] . ';';
			}

			// Color of the text in the login form.
			if ( $options['text_color'] != '' ){
				$login_label_css_values = 'color:' . $options['text_color'] . ' !important;';
			}

			// If the text field is not blank add the image URL. If repeat/center is selected
			if ( $options['bk_image'] != '' ){
				$body_html_css_values .= 'background-image: url(' . $options['bk_image'] . ') !important;';
				if ( 'repeat' == $options['image_pos'] ){
					$body_html_css_values .= 'background-repeat: repeat;';
				}
				else {
					$body_html_css_values .= 'background-repeat: no-repeat;  background-position: center;';
				}
			}
			
			/**
			 * Initialize array. This array will hold the css values.
			 */ 
			$css_content = array();

			// The body and html tags.
			$css_content['body_html'] = 'body, html{ background-color:transparent !important; }';
			$css_content['body_html'] .= 'html {' . $body_html_css_values . '}';

			// The login nav and back to blog a links.
			$css_content['login_link'] = '.login #nav a, .login #backtoblog a {' . $login_link_css_values . '}';

			// The login nav and back to blog a links color on hover.
			$css_content['login_link_hover'] = '.login #nav a:hover, .login #backtoblog a:hover {' . $login_logo_form_link_hover . '}';

			// Hide the links option
			$css_content['login_hide_links'] = '.login #nav {' . $login_logo_hide_link . '}';
			$css_content['login_logo_hide_backtoblog'] = '#backtoblog {' . $login_logo_hide_backtoblog . '}';
			// The login label
			$css_content['login_label'] = '.login label {' . $login_label_css_values . '}';

			// Login logo image.
			$css_content['login_logo_image'] = 'body.login #login h1 a {' . $login_logo_image_css_values . '}';

			// Login logo container
			$css_content['login_logo_container'] = '.login h1 a {' . $login_logo_image_container_css_values . '}';

			/**
			 * When first installed we want the large login form to have a transparent background.
			 * This is the form that is on top of the larger id of #login.  When the color of
			 * the #login is chosen the these should be set to transparent.
			 */
			if ( '' == $options['form_background'] ){

				// Small Login Form Background set to white large set to transparent
				$css_content['login_form'] = '#login {background:transparent;}';
				$css_content['small_login_form'] = '.login form {background:#fff;}';

				// Login Message background set to white
				$css_content['login_message'] = '.login .message {background:#fff;}';
			}

			// Form Background
			$css_content['form_background'] = '#login {' . $login_logo_form_background . '}';

			// Small Form Background
			$css_content['small_login_form'] = '.login form {' . $login_logo_small_form_background . '}';

			// Custom CSS
			$css_content['text_area'] = $login_logo_custom_css;

			ob_start();
			?>
			<style>
				<?php
				foreach ( $css_content as $css ){
					echo esc_html( $css );
				}
				?> 
			</style>
			<?php
			$content .= ob_get_contents();
			ob_end_clean();
			echo $content;
		}
	}
}

// Initialize Plugin Object
if ( class_exists( 'JsmGenericClass' ) && ! isset( $JsmGenericClass ) ) {
	$JsmGenericClass = new JsmGenericClass();
}

// Create Settings Page
if ( class_exists( 'Jsm_Settings_Display' ) && ! isset( $jsm_settings_display ) ) {
	$jsm_settings_display = new Jsm_Settings_Display();
}

// Create the Settings Function Object
if ( class_exists( 'Jsm_Custom_Login_Functions' ) && ! isset( $jsm_custom_login_functions ) ) {
	$jsm_custom_login_functions = new Jsm_Custom_Login_Functions();
}

