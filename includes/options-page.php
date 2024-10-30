<?php
if ( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 * Display the settings form on the settings page.
 *
 * Contains the loop to display the settings on the settings page.
 * Warning about theme being activated function.
 * Test if plugin addon is activated.
 * 
 * @uses add_options_page()
 */
class Jsm_Settings_Display {

	/**
	 * Hooks into admin_menu to add_options_page for the settings options
	 * for customizing the login screen
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'jsm_generic_admin_settings_menu_item' ) );
	}

	/**
	 * The add_options_page() called by the add_action() hook in the constructor. Registers the jsm_custom_login_settings function
	 * to display settings.
	 * 
	 * @return void
	 */
	public function jsm_generic_admin_settings_menu_item(){
		$url = explode( '/', plugin_basename( __FILE__ ) );
		$plugin_name = $url[0];
		$plugin_page_url = $plugin_name . '/includes/options-page';
		add_options_page( __( 'Custom Login', 'l7-login-customizer' ), __( 'Custom Login' , 'l7-login-customizer' ), 'manage_options' , $plugin_page_url , array( $this, 'jsm_custom_login_settings' ), '' );
	}

	/**
	 * Displays the warning when addon themes are activated.
	 * 
	 * @param  [array] $addon_array an array of strings.
	 * @return [html]               The warning message in html.
	 */
	public function jsm_theme_warning( $addon_array ) {
		if ( is_array( $addon_array ) ){
			foreach ( $addon_array as $addon ) {
				if ( is_plugin_active( $addon ) ) {
					$plugin_data = get_plugin_data( plugin_dir_path( __FILE__ ) . '../../' . $addon );
					ob_start();
					?>
					<div class="update-nag">
						<p>The <?php echo $plugin_data['Name']; ?> addon is activated.<br />Only the custom CSS and logo options are available when a theme is activated.</p>		
					</div>
					<?php 
					$content = ob_get_contents();
					ob_get_clean();
					return $content;
				}
			}
		}
	}

	/**
	 * Test to see if plugin addons are activated.
	 *
	 * @param   $addon_array Array of addon names.
	 * @return  boolean
	 */
	public function jsm_is_addon_activated( $addon_array ){
		if ( is_array( $addon_array ) ){
			foreach ( $addon_array as $addon ) {
				if ( is_plugin_active( $addon ) ) {
					return true;
				}
			}
			return false;
		}
	}

	/**
	 * Displays the settings fields by calling settings_fields() and do_settings_sections(). Contains the
	 * div that holds the iframe for diplaying the preview of the login screen.  Adds add_thickbox().
	 * @return html
	 */
	public function jsm_custom_login_settings(){

		// Addon theme name array.
		global $l7w_510_addon_theme_array;
		$content = '';
		ob_start();
		?>
		<div class="wrap">
			<div class="row">
				<div class="col-md-8">
					<?php echo $this->jsm_theme_warning( $l7w_510_addon_theme_array ); ?> 
					<?php include_once( plugin_dir_path( __FILE__ ) . '../templates/feedback.inc' );?>
					<form name="l7lc-settings" action="options.php" method="post" >
					<?php 
						settings_fields( 'jsm_custom_login_options' );
						do_settings_sections( 'logo-settings-customizer' );

						// If there is an addon activated.  Don't show these options.
						if ( false == $this->jsm_is_addon_activated( $l7w_510_addon_theme_array ) ){
							do_settings_sections( 'simple-login-customizer' );
							do_settings_sections( 'text-settings-customizer' );
							do_settings_sections( 'button-settings-customizer' );
						}

						do_settings_sections( 'css-settings-customizer' );
					?>
						<input name="Submit" class="button button-primary" type="submit" value="<?php _e( 'Save Changes' , 'l7-login-customizer' ); ?>" />
						<a name="<?php _e( 'Preview of custom login. Saved changes are shown here.', 'l7-login-customizer' ) ?>" href="#TB_inline?width=700&height=700&inlineId=jsm_custom_login_display" class="thickbox"><button class="button button-secondary"><?php _e( 'Preview' , 'l7-login-customizer' ); ?></button></a>
					</form>
				</div>
				<div class="col-md-4">
				</div>
			</div>
		</div>
		<div id="jsm_custom_login_display" style="display:none;">
		     <p>
		          <iframe src="<?php echo esc_url( wp_login_url() ) ?>" scrolling="no" style="width:100%; height:900px; overflow:hidden;"></iframe>
		     </p>
		</div>
		<?php add_thickbox();
		$content .= ob_get_contents();
		ob_end_clean();
		echo $content;
	}
}