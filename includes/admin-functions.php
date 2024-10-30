<?php
if ( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 * Contains the register settings and add settings fields
 *  
 * Logo link change functions.  Contains the display functions for the 
 * options and their coresponding functions as well as the sanitization function.
 * Adds the warning banners when inputs are not correctly set.
 * Uses the admin_init hook to initialize the jsm_custom_login_admin_init function.
 * Contains the default settings method.
 *
 * @uses  register_settings()
 * @uses  add_settings_section()
 * @uses  add_settings_field()
 */
class Jsm_Custom_Login_Functions {

	/**
	 * Calls the hook admin_init to Register settings, add settings section 
	 * and add the settings field.
	 */
	public function __construct(){
		add_action( 'admin_init', array( $this, 'l7wc_admin_init' ) );
	}

	/**
	 * Register the settings, create settings section, add settings fields.
	 * 
	 * @return void
	 */
	public function l7wc_admin_init(){
		global $pagenow;
		register_setting( 'jsm_custom_login_options', 'jsm_custom_login_options', array( $this, 'jsm_custom_logo_sanitization' ) );

		// Logo Settings
		add_settings_section( 'jsm_custom_login_logo', __( 'Logo Settings', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_section_logo' ), 'logo-settings-customizer' );
		add_settings_field( 'jsm_custom_logo_text_string',  '<i class="fa fa-upload"></i> ' . __( 'Logo Upload' , 'l7-login-customizer' ), array( $this, 'jsm_custom_login_logo_upload' ), 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'text_string' ) );
		add_settings_field( 'jsm_custom_logo_size',  '<i class="fa fa-arrows-alt"></i> ' . __( 'Set Logo Height', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_logo_size' ), 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'logo_height' ) );
		add_settings_field( 'jsm_custom_logo_width',  '<i class="fa fa-arrows-alt"></i> ' . __( 'Set Logo Width', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_logo_width' ), 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'logo_width' ) );
		add_settings_field( 'l7wc_top_form_space', '<i class="fa fa-arrows-alt"></i> ' . __( 'Space Logo and Top Form', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_logo_top_form' ), 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'l7wc_top_form_space' ) );
		add_settings_field( 'jsm_custom_login_logo_preview', '<i class="fa fa-picture-o"></i> ' . __( 'Logo Preview', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_logo_preview' ), 'logo-settings-customizer', 'jsm_custom_login_logo' );
		add_settings_field( 'jsm_custom_login_logo_link_url', '<i class="fa fa-link"></i> ' . __( 'Logo Link URL', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_link_url' ) , 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'link_url' ) );
		add_settings_field( 'jsm_custom_login_logo_hover_title', '<i class="fa fa-globe"></i> ' . __( 'Logo Image Title', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_hover_title' ) , 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'hover_title' ) );

		// Page Settings
		add_settings_section( 'jsm_custom_login_main', __( 'Page Settings', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_section_page' ), 'simple-login-customizer' );
		add_settings_field( 'jsm_custom_login_logo_bk_color', '<i class="fa fa-paint-brush"></i> ' . __( 'Page&#8217;s Background Color', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_bk_color' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'bk_color' ) );
		add_settings_field( 'jsm_custom_login_form', '<i class="fa fa-paint-brush"></i> ' . __( 'Form&#8217;s Background Color', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_forms_background' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'form_background' ) );
		add_settings_field( 'jsm_custom_login_small_form', '<i class="fa fa-paint-brush"></i> ' . __( 'Small Form&#8217;s Background Color', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_small_forms_background' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'small_form_background' ) );
		add_settings_field( 'jsm_custom_login_logo_bk_image', '<i class="fa fa-upload"></i> ' . __( 'Background Image', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_bk_image' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'bk_image' ) );
		add_settings_field( 'jsm_custom_login_bk_image_preview', '<i class="fa fa-picture-o"></i> ' . __( 'Background Image Preview', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_bk_image_preview' ), 'simple-login-customizer', 'jsm_custom_login_main' );

		// Text Settings
		add_settings_section( 'jsm_custom_login_text', __( 'Text Settings', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_section_text' ), 'text-settings-customizer' );
		add_settings_field( 'jsm_custom_login_logo_text_color', '<i class="fa fa-paint-brush"></i> ' . __( 'Text Color', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_text_color' ), 'text-settings-customizer', 'jsm_custom_login_text', array( 'label_for' => 'text_color' ) );
		add_settings_field( 'jsm_custom_login_logo_link_text_color', '<i class="fa fa-paint-brush"></i> ' . __( 'Link Text Color', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_link_text_color' ), 'text-settings-customizer', 'jsm_custom_login_text', array( 'label_for' => 'link_text_color' ) );
		add_settings_field( 'jsm_custom_login_logo_link_text_hover_color', '<i class="fa fa-paint-brush"></i> ' . __( 'Link Text Hover Color', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_link_text_hover_color' ), 'text-settings-customizer', 'jsm_custom_login_text', array( 'label_for' => 'link_text_hover_color' ) );
		add_settings_field( 'login_logo_hide_link', '<i class="fa fa-paint-brush"></i> ' . __( 'Hide Links', 'l7-login-customizer' ), array( $this, 'l7lc_login_logo_hide_link' ), 'text-settings-customizer', 'jsm_custom_login_text', array( 'label_for' => 'login_logo_hide_link' ) );
		add_settings_field( 'login_logo_hide_backtoblog', '<i class="fa fa-paint-brush"></i> ' . __( 'Hide Back to Blog Link', 'l7-login-customizer' ), array( $this, 'l7lc_login_logo_hide_backtoblog' ), 'text-settings-customizer', 'jsm_custom_login_text', array( 'label_for' => 'login_logo_hide_backtoblog' ) );

		// Button Settings
		add_settings_section( 'jsm_custom_button_settings', __( 'Submit Button Settings', 'l7-login-customizer' ), array( $this, 'l7lc_custom_submit_button_desc' ), 'button-settings-customizer' );
		add_settings_field( 'l7lc_submit_button', '<i class="fa fa-paint-brush"></i> ' . __( 'Submit Button Color', 'l7-login-customizer' ), array( $this, 'l7lc_login_submit_button' ), 'button-settings-customizer', 'jsm_custom_button_settings', array( 'label_for' => 'submit_button' ) );
		add_settings_field( 'l7lc_submit_button_border', '<i class="fa fa-paint-brush"></i> ' . __( 'Submit Button Border Color', 'l7-login-customizer' ), array( $this, 'l7lc_login_submit_button_border' ), 'button-settings-customizer', 'jsm_custom_button_settings', array( 'label_for' => 'submit_button_border' ) );

		// Css Settings
		add_settings_section( 'jsm_custom_login_css', __( 'CSS Settings', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_section_text' ), 'css-settings-customizer' );
		add_settings_field( 'jsm_custom_login_css', '<i class="fa fa-css3"></i> ' . __( 'Custom CSS', 'l7-login-customizer' ), array( $this, 'jsm_custom_login_setting_css_input' ) , 'css-settings-customizer', 'jsm_custom_login_css', array( 'label_for' => 'text_area' ) );

		// Replace text in logo upload.
		if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
			add_filter( 'gettext', array( $this, 'jsm_custom_login_replace_thickbox_text' )  , 1, 3 );
		}

	}

	/**
	 * Explanations about the logo customizing section. Simple text description
	 * 
	 * of the settings section jsm_custom_login_main
	 * @return string
	 */
	public function jsm_custom_login_section_page() {
	}

	/**
	 * Explanations about the logo settings. Simple text description
	 * 
	 * of the settings section.
	 * @return string
	 */
	public function jsm_custom_login_section_logo() {
	}

	/**
	 * Explanations about the text settings. Simple text description
	 * 
	 * of the settings section.
	 * @return string
	 */
	public function jsm_custom_login_section_text() {
	}

	/**
	 * Explanations about the logo settings.
	 * 
	 * @return string
	 */
	public function l7lc_custom_submit_button_desc() {
	}

	/**
	 * Logo Upload. 
	 * 
	 * This contains the logo upload text field, logo upload button
	 * and short description.
	 * 
	 * @return string
	 */
	public function jsm_custom_login_logo_upload(){
		$text_string = $this->l7lc_default_settings( 'text_string' );

		// Add the remove button when a logo url is set.
		if ( '' != $text_string ) {
			$remove_button = "<button class='btn btn-danger' id='js-remove-logo' type='button'>" . __( 'Remove', 'l7-login-customizer' ) . '</button>';
		}
		else {
			$remove_button = '';
		}

		/** 
		 * JavaScript clears the text field before submit when removing the
		 * Logo URL.
		 */
		$content = "<div class='input-group'>
        				<span class='input-group-btn'>
        					<button class='btn btn-info' id='upload_logo_button' type='button'>" . __( 'Select', 'l7-login-customizer' ) . '</button>
        					' . $remove_button . "
        				</span>
        				<input id='text_string' name='jsm_custom_login_options[text_string]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' placeholder='Logo url here or click select' style='width:150%;'/>
        			</div>";
		$content .= "<span class='description'>" . __( 'Upload or choose an image for the logo.', 'l7-login-customizer' ) . '</span>';
		echo $content;
	}

	/**
	 * Callback to collect the logo size and display it if it is set.
	 * @return html
	 */
	public function jsm_custom_login_logo_size() {
		$logo_size = $this->l7lc_default_settings( 'logo_height' );
		$content = "<div class='input-group'><span class='input-group-addon'><i>" . __( 'Height' , 'l7-login-customizer' ) . "</i></span><input id='logo_height' name='jsm_custom_login_options[logo_height]' type='text' value='" . esc_attr( $logo_size ) . "' class='form-control' /></div>";
		$content .= "<span class='description'>" . __( 'Example: 100px' , 'l7-login-customizer' ) . '</span>';
		echo $content;
	}

	/**
	 * Callback to collect the logo width and display it if it is set.
	 * @return html
	 */
	public function jsm_custom_login_logo_width() {
		$logo_width = $this->l7lc_default_settings( 'logo_width' );
		$content = "<div class='input-group'><span class='input-group-addon'><i>" . __( 'Width', 'l7-login-customizer' ) . "</i></span><input id='logo_width' name='jsm_custom_login_options[logo_width]' type='text' value='" . esc_attr( $logo_width ) . "' class='form-control' /></div>";
		$content .= "<span class='description'>" . __( 'Example: 100px' , 'l7-login-customizer' ) . '</span>';
		echo $content;
	}

	/**
	 * Show the current logo for preview.  Displays the currently set logo.  
	 * @return html 
	 */
	public function jsm_custom_login_setting_logo_preview() {
		$text_string = $this->l7lc_default_settings( 'text_string' );
		$content = "<div id='upload_logo_preview' style='min-height: 100px;'>
	    	<img style='max-width:100%;' src='" . esc_attr( $text_string ) . "' />
		</div>";
		echo $content;
	}

	/**
	 * Display and fill the form field for the logo link url.  This is the where the user is directed
	 * when they click on the logo. This uses wp_kses() to sanitize the output.  
	 * The value variable is run through the esc_attr() function as well.
	 * @return html
	 */
	public function jsm_custom_login_setting_link_url() {
		$text_string = $this->l7lc_default_settings( 'link_url' );
		$content = "<div class='input-group'><span class='input-group-addon'><i></i></span><input id='link_url' name='jsm_custom_login_options[link_url]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' style='width:150%;' /></div>";
		$content .= '<span class="description">' . __( 'http://example.com', 'l7-login-customizer' ) . '</span>';
		echo $content;
	}

	/**
	 * Display and fill the form field for the Logo image title.  The 
	 * value variable is escaped using esc_attr().
	 * @return html
	 */
	public function jsm_custom_login_setting_hover_title() {
		$text_string = $this->l7lc_default_settings( 'hover_title' );
		$output = "<div class='input-group'><span class='input-group-addon'><i></i></span><input id='hover_title' name='jsm_custom_login_options[hover_title]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' style='width:150%;'/></div>";
		echo $output;
	}

	/**
	 * Display and fill the field for the space between the logo and the top of the small form. 
	 * This setting is important because when logo's are uploaded, depending on their size, the space between
	 * the logo and the top of the form is to large.
	 */
	public function jsm_custom_login_setting_logo_top_form() {
		$text_string = $this->l7lc_default_settings( 'l7wc_top_form_space' );
		$output = "<div class='input-group'><span class='input-group-addon'><i>" . __( 'Height', 'l7-login-customizer' ) . "</i></span><input id='l7wc_top_form_space' name='jsm_custom_login_options[l7wc_top_form_space]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control'/></div>";
		$output .= "<span class='description'>" . esc_html( __( 'Distance from the bottom of the logo and the top of the inner form.', 'l7-login-customizer' ) ) . '<br />' . esc_html( __( 'Example: 100px', 'l7-login-customizer' ) ) . '</span>';
		echo $output;
	}

	/**
	 * The entire page's background color. Page's Background Color. 
	 * 
	 * @return string
	 */
	public function jsm_custom_login_setting_bk_color() {
		$text_string = $this->l7lc_default_settings( 'bk_color' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='bk_color' name='jsm_custom_login_options[bk_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>" . __( '6 digit hex color code', 'l7-login-customizer' ) . '</span>';
		echo $output;
	}

	/**
	 * Option for the background color of the large form. 
	 * 
	 * This is the big container
	 * that contains all of the links and login fields.  Set to #fff as default in 
	 * the css.
	 *  
	 * @return string 
	 */
	public function jsm_custom_login_forms_background() {
		$text_string = $this->l7lc_default_settings( 'form_background' );
		$output = "<div class='input-group color-picker colorpicker-element'><span class='input-group-addon'><i></i></span><input id='form_background' name='jsm_custom_login_options[form_background]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>" . __( '6 digit hex color code' , 'l7-login-customizer' ) . '</span>';
		echo $output;
	}

	/**
	 * Small form option for background color. 
	 * 
	 * This is the part of the
	 * form that does not include the logo or the other links on the bottom.
	 * This is the text fields container with class .login form.
	 * 
	 * @return string 
	 */
	public function jsm_custom_login_small_forms_background() {
		$text_string = $this->l7lc_default_settings( 'small_form_background' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='small_form_background' name='jsm_custom_login_options[small_form_background]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>" . __( '6 digit hex color code', 'l7-login-customizer' ) . '</span>';
		echo $output;
	}

	/**
	 * Background image upload.
	 * 
	 * @return string
	 */
	public function jsm_custom_login_setting_bk_image() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->l7lc_default_settings( 'bk_image' );

		// Add the remove button when a logo url is set.
		if ( '' != $text_string ) {
			$remove_button = "<button class='btn btn-danger' id='js-remove-bk' type='button'>" . __( 'Remove', 'l7-login-customizer' ) . '</button>';
		}
		else {
			$remove_button = '';
		}

		$content = "<div class='input-group'>
        				<span class='input-group-btn'>
        					<button class='btn btn-info' id='upload_bk_image_button' type='button'>" . __( 'Select', 'l7-login-customizer' ) .'</button>
        					' . $remove_button . "
        				</span>
        				<input id='bk_image' name='jsm_custom_login_options[bk_image]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' placeholder='Image url here or click select.' style='width:150%;'/>
					</div>
					<div name='jsm_custom_login_options[image_pos]' class='input-group'>	
						<select class='form-control' id='image_pos' name='jsm_custom_login_options[image_pos]'>
						  <option value='repeat' ";
		$content .= ( $options['image_pos'] == 'repeat' ) ? 'selected' : '';
		$content .= '>' . __( 'Repeat', 'l7-login-customizer' ) . "</option>
						  <option value='center' ";
		$content .= ( $options['image_pos'] == 'center' ) ? 'selected' : '';
		$content .= '>' . __( 'Center', 'l7-login-customizer' ) . '</option>
						</select>	
        			</div>';
		echo $content;
	}

	/**
	 * Show the current background image.  
	 * 
	 * Displays the currently chosen and saved background image.
	 * 
	 * @return string
	 */
	public function jsm_custom_login_setting_bk_image_preview() {
		$text_string = $this->l7lc_default_settings( 'bk_image' );
?>
		<div id="upload_bk_image_preview" style="min-height: 100px;">
	    	<img style="max-width:100%;" src="<?php echo esc_url( $text_string ); ?>" />
		</div>
<?php
	}

	/**
	 * The Text Color Option
	 * 
	 * @return string
	 */
	public function jsm_custom_login_setting_text_color() {
		$text_string = $this->l7lc_default_settings( 'text_color' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='text_color' name='jsm_custom_login_options[text_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>" . __( '6 digit hex color code', 'l7-login-customizer' ) . '</span>';
		echo $output;
	}

	/**
	 * Link text color.
	 *  
	 * @return string
	 */
	public function jsm_custom_login_setting_link_text_color() {
		$text_string = $this->l7lc_default_settings( 'link_text_color' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='link_text_color' name='jsm_custom_login_options[link_text_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>" . __( '6 digit hex color code', 'l7-login-customizer' ) . '</span>';
		echo $output;
	}

	/**
	 * Link text hover color. For the links to register and return to homepage.
	 * 
	 * @return string
	 */
	public function jsm_custom_login_setting_link_text_hover_color() {
		$text_string = $this->l7lc_default_settings( 'link_text_hover_color' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='link_text_hover_color' name='jsm_custom_login_options[link_text_hover_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>" . __( '6 digit hex color code', 'l7-login-customizer' ) . '</span>';
		echo $output;
	}

	/**
	 * Hide the links on the bottom of the form, not the back to blog link.
	 * 
	 * @return string
	 */
	public function l7lc_login_logo_hide_link() {
		$text_string = $this->l7lc_default_settings( 'login_logo_hide_link' );
		'1' == $text_string ? $checked = 'checked': $checked = '';
		$output = '<input type="checkbox" value="1" id="login_logo_hide_link" name="jsm_custom_login_options[login_logo_hide_link]"' . $checked . ' >';
		echo $output;
	}

	/**
	 * Hide the backtoblog link on the bottom of the form.
	 * 
	 * @return string
	 */
	public function l7lc_login_logo_hide_backtoblog(){
		$text_string = $this->l7lc_default_settings( 'login_logo_hide_backtoblog' );
		'1' == $text_string ? $checked = 'checked': $checked = '';
		$output = '<input type="checkbox" value="1" id="login_logo_hide_backtoblog" name="jsm_custom_login_options[login_logo_hide_backtoblog]"' . $checked . ' >';
		echo $output;
	}

	/**
	 * Submit button color.
	 * 
	 * @return  string 
	 */
	public function l7lc_login_submit_button(){
		$text_string = $this->l7lc_default_settings( 'submit_button' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='submit_button' name='jsm_custom_login_options[submit_button]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' style='width:150%;'/></div>";
		echo $output;
	}

	/**
	 * Submit button border color.
	 * 
	 * @return  string
	 */
	public function l7lc_login_submit_button_border(){
		$text_string = $this->l7lc_default_settings( 'submit_button_border' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='submit_button_border' name='jsm_custom_login_options[submit_button_border]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' style='width:150%;'/></div>";
		echo $output;
	}

	/**
	 * Display and fill the text area for the custom css. 
	 * 
	 * This text is sanitized using
	 * the wp_kses() function.  This is redundant due to the variable $text_string being 
	 * run through the esc_html() function. Code sniffer calls for the return value to be escaped.
	 * 
	 * @return string
	 */
	public function jsm_custom_login_setting_css_input() {
		$text_string = $this->l7lc_default_settings( 'text_area' );
		$content = "<textarea id='text_area' name='jsm_custom_login_options[text_area]' type='textarea' rows='14' cols='50'>" . esc_attr( apply_filters( 'l7w-content-css', $text_string ) ) . '</textarea>';
		$allowed = array(
						'textarea' => array(
							'id' => array(),
							'name' => array(),
							'type' => array(),
							'rows' => array(),
							'cols' => array()
						)
					);
		echo wp_kses( $content, $allowed );
	}

	/**
	 * Change text in file upload to make sense with what action that is taking place. 
	 * 
	 * The thickbox
	 * button shows 'Insert into Post' which is not user friendly since they are uploading an image
	 * to the settings page.  This filters the 'Insert into Post' and changes it to 'Select Image'.
	 * 
	 * @param  string  the text of the thickbox
	 * @return string
	 */
	public function jsm_custom_login_replace_thickbox_text( $translated_text, $text, $domain ) {
		if ( 'Insert into Post' == $text ) {
			$referer = strpos( wp_get_referer(), 'media-upload.php' );
			if ( false != $referer ) {
				return 'Select Image';
			}
		}
		return $translated_text;
	}

	/**
	 * Sanitization of setting inputs. Error messages and validation.
	 * 
	 * This function makes sure none of the inputs are harmful. It takes
	 * the input array of the form fields and uses various apropriate sanitization funtions to clean the 
	 * inputs.
	 * 
	 * @param  array 	setting inputs
	 * @return array    sanitized and validated setting inputs.
	 */
	public function jsm_custom_logo_sanitization( $input ){
		$arg_array = get_option( 'jsm_custom_login_options' );

		/**
		 * Logo upload URL. Sanitized and validated.
		 */
		$arg_array['text_string'] = esc_url( $input['text_string'] );
		if ( ( ! preg_match( "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $arg_array['text_string'] ) ) && ( $arg_array['text_string'] != '' ) ) {
			add_settings_error( 'jsm_custom_logo_text_string', 'invalid-url-logo', __( 'You have entered an invalid logo URL.', 'l7-login-customizer' ) );
		}

		/**
		 * For height of the Logo. Sanitize and validation with error.
		 */
		$arg_array['logo_height'] = esc_html( $input['logo_height'] );
		if ( ( ! preg_match( '/^[0-9]*px$/', $arg_array['logo_height'] ) ) && ( $arg_array['logo_height'] != '' ) ) {
			add_settings_error( 'jsm_custom_logo_size', 'invalid-logo-height', __( 'You have entered an invalid logo height. Please enter height in this format: \'100<b>px</b>\'.', 'l7-login-customizer' ) );
		}

		/**
		 * For logo width sanitization and validation. 
		 */
		$arg_array['logo_width'] = esc_html( $input['logo_width'] );
		if ( ( ! preg_match( '/^[0-9]*px$/', $arg_array['logo_width'] ) ) && ( $arg_array['logo_width'] != '' ) ) {
			add_settings_error( 'jsm_custom_logo_width', 'invalid-logo-width', __( 'You have entered an invalid logo width. Please enter width in this format: \'100<b>px</b>\'.', 'l7-login-customizer' ) );
		}

		/**
		 * The link url (the link applied to the logo). Sanitized and validated with error message.
		 */
		$arg_array['link_url'] = esc_url( $input['link_url'] );
		if ( ( ! preg_match( "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $arg_array['link_url'] ) ) && ( $arg_array['link_url'] != '' ) ) {
			add_settings_error( 'jsm_custom_login_logo_link_url', 'invalid-url-link', __( 'You have entered an invalid link URL.', 'l7-login-customizer' ) );
		}

		/**
		 * Hide the links variable sanitized.
		 */
		$arg_array['login_logo_hide_link'] = esc_html( $input['login_logo_hide_link'] );

		/**
		 * Hide the backtoblog link variable sanitized.
		 */
		$arg_array['login_logo_hide_backtoblog'] = esc_html( $input['login_logo_hide_backtoblog'] );

		/**
		 * Text for the title
		 */
		$arg_array['hover_title'] = esc_html( $input['hover_title'] );

		/**
		 * Space between logo and top of form setting
		 */
		$arg_array['l7wc_top_form_space'] = esc_html( $input['l7wc_top_form_space'] );
		if ( ( ! preg_match( '/^[0-9]*px$/', $arg_array['l7wc_top_form_space'] ) ) && ( $arg_array['l7wc_top_form_space'] != '' ) ) {
			add_settings_error( 'l7wc_top_form_space', 'invalid-logo-height', __( 'You have entered an invalid Space to Top of Form value. Please enter the value in this format: \'100<b>px</b>\'.', 'l7-login-customizer' ) );
		}

		/**
		 * For pages background color. Sanitation
		 */
		$arg_array['bk_color'] = esc_html( $input['bk_color'] );

		/**
		 * Form background color hex value. Sanitation.
		 */
		$arg_array['form_background'] = esc_html( $input['form_background'] );

		/**
		 * Small form background hex value.
		 */
		$arg_array['small_form_background'] = esc_html( $input['small_form_background'] );

		/**
		 * Background image URL. Sanitize it and then validate it to make sure it is valid.
		 * Show the Error if it is not valid.
		 */
		$arg_array['bk_image'] = esc_url_raw( $input['bk_image'] );
		if ( ( ! preg_match( "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $arg_array['bk_image'] ) ) && ( $arg_array['bk_image'] != '' ) ) {
			add_settings_error( 'jsm_custom_login_logo_bk_image', 'bad-bk_image', __( 'You have entered an invalid background image URL.', 'l7-login-customizer' ) );
		}

		/**
		 * Image position logic. Repeat or center.
		 */
		if ( ( 'repeat' != $input['image_pos'] ) && ( 'center' != $input['image_pos'] ) ) {
			$arg_array['image_pos'] = 'repeat';
		}
		else {
			$arg_array['image_pos'] = esc_html( $input['image_pos'] );
		}

		/**
		 * Text color hex value.
		 */
		$arg_array['text_color'] = esc_html( $input['text_color'] );

		/**
		 * Custom CSS link text color hex value
		 */
		$arg_array['link_text_color'] = esc_html( $input['link_text_color'] );

		/**
		 * Custom CSS link text color hex value
		 */
		$arg_array['link_text_hover_color'] = esc_html( $input['link_text_hover_color'] );

		/**
		 * Submit Button color.
		 */
		$arg_array['submit_button'] = esc_html( $input['submit_button'] );

		/**
		 * Submit Button border color.
		 */
		$arg_array['submit_button_border'] = esc_html( $input['submit_button_border'] );

		/**
		 * CSS text_area sanitization. This is the custom css field.
		 */
		$arg_array['text_area'] = esc_html( $input['text_area'] );

		return $arg_array;
	}

	/**
	 * Takes the option setting key and returns the value if it is set. 
	 * 
	 * If it
	 * is not set then it returns the default value. If the default key is not
	 * in the array it returns false.
	 * 
	 * @param  	string $key the key value of the setting
	 * @return 	string
	 * @return  boolean    
	 */
	public function l7lc_default_settings( $key ) {
		$options = get_option( 'jsm_custom_login_options' );
		$defaults = array(
			'text_string'     			=> '',
			'logo_height'				=> '100px',
			'logo_width'				=> '',
			'text_area'					=> '',
			'link_text_color'			=> '#999',
			'link_url'					=> 'https://layer7web.com',
			'hover_title'				=> '',
			'text_color'				=> '#777',
			'form_background'   		=> '',
			'small_form_background'		=> '',
			'bk_image'					=> '',
			'bk_color'					=> '#fff',
			'link_text_hover_color'		=> '#0091cd',
			'l7wc_top_form_space'		=> '100px',
			'login_logo_hide_link'		=> '',
			'login_logo_hide_backtoblog' => '',
			'submit_button_border'		=> '#0073AA',
			'submit_button'				=> '#0091CD',
		);

		$options_defaults = wp_parse_args( $options, $defaults );

		if ( isset( $options_defaults[$key] ) ) {
			return $options_defaults[$key];
		}
		else {
			return false;
		}
	}
}
