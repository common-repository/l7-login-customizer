<?php
if ( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 * Display the settings form on the settings page.
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
	 * @return void
	 */
	public function jsm_generic_admin_settings_menu_item(){
		$url = explode( "/", plugin_basename( __FILE__ ) );
		$plugin_name = $url[0];
		$plugin_page_url = $plugin_name . "/includes/options-page";
		add_options_page( 'Custom Login', 'Custom Login', 'manage_options' , $plugin_page_url , array( $this, 'jsm_custom_login_settings' ), '' );
	}

	/**
	 * Displays the settings fields by calling settings_fields() and do_settings_sections(). Contains the
	 * div that holds the iframe for diplaying the preview of the login screen.  Adds add_thickbox().
	 * @return html
	 */
	public function jsm_custom_login_settings(){
		$content = '';
		ob_start();
		?>
		<div class="wrap">
			<div class="row">
				<div class="col-md-8">
					<form action="options.php" method="post" >
					<?php
						settings_fields( 'jsm_custom_login_options' );
						do_settings_sections( 'logo-settings-customizer' );
						do_settings_sections( 'simple-login-customizer' );
						do_settings_sections( 'text-settings-customizer' );
						do_settings_sections( 'css-settings-customizer' );
					?>
						<input name="Submit" class="button button-primary" type="submit" value="Save Changes" />
						<a name="Preview of custom login. Saved changes are shown here." href="#TB_inline?width=700&height=700&inlineId=jsm_custom_login_display" class="thickbox"><button class="button button-secondary">Preview</button></a>
					</form>
				</div>
			</div>
			<div class="row">
			 	<div class="col-md-12" style="text-align:center;">
				 Comments? - <a href="mail:jeff@fusioncode.org">Layer 7 Web</a>
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