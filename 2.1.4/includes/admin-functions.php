<?php

if ( ! defined( 'ABSPATH' ) ){
	exit;
}

/**
 * For namespacing purposes. Contains the register settings and add settings 
 * fields and their coresponding functions as well as the sanitization function.
 * Adds the warning banners when inputs are not correctly set.
 * Uses the admin_init hook to initialize the jsm_custom_login_admin_init function.
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
	 * @return void
	 */
	public function l7wc_admin_init(){
		global $pagenow;
		register_setting( 'jsm_custom_login_options', 'jsm_custom_login_options', array( $this, 'jsm_custom_logo_sanitization' ) );

		// Logo Settings
		add_settings_section( 'jsm_custom_login_logo', 'Logo Settings', array( $this, 'jsm_custom_login_section_logo' ), 'logo-settings-customizer' );
		add_settings_field( 'jsm_custom_logo_text_string',  '<i class="fa fa-upload"></i> Logo Upload', array( $this, 'jsm_custom_login_logo_upload' ), 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'text_string' ) );
		add_settings_field( 'jsm_custom_logo_size',  '<i class="fa fa-arrows-alt"></i> Set Logo Height', array( $this, 'jsm_custom_login_logo_size' ), 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'logo_height' ) );
		add_settings_field( 'jsm_custom_logo_width',  '<i class="fa fa-arrows-alt"></i> Set Logo Width', array( $this, 'jsm_custom_login_logo_width' ), 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'logo_width' ) );
		add_settings_field( 'l7wc_top_form_space', '<i class="fa fa-arrows-alt"></i> Space Logo and Top Form', array( $this, 'jsm_custom_login_setting_logo_top_form' ), 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'l7wc_top_form_space' ) );
		add_settings_field( 'jsm_custom_login_logo_preview', '<i class="fa fa-picture-o"></i> Logo Preview', array( $this, 'jsm_custom_login_setting_logo_preview' ), 'logo-settings-customizer', 'jsm_custom_login_logo' );
		add_settings_field( 'jsm_custom_login_logo_link_url', '<i class="fa fa-link"></i> Logo Link URL', array( $this, 'jsm_custom_login_setting_link_url' ) , 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'link_url' ) );
		add_settings_field( 'jsm_custom_login_logo_hover_title', '<i class="fa fa-globe"></i> Logo Image Title', array( $this, 'jsm_custom_login_setting_hover_title' ) , 'logo-settings-customizer', 'jsm_custom_login_logo', array( 'label_for' => 'hover_title' ) );

		// Page Settings
		add_settings_section( 'jsm_custom_login_main', 'Page Settings', array( $this, 'jsm_custom_login_section_page' ), 'simple-login-customizer' );
		add_settings_field( 'jsm_custom_login_logo_bk_color', '<i class="fa fa-paint-brush"></i> Page&#8217;s Background Color', array( $this, 'jsm_custom_login_setting_bk_color' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'bk_color' ) );
		add_settings_field( 'jsm_custom_login_form', '<i class="fa fa-paint-brush"></i> Form&#8217;s Background Color', array( $this, 'jsm_custom_login_forms_background' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'form_background' ) );
		add_settings_field( 'jsm_custom_login_small_form', '<i class="fa fa-paint-brush"></i> Small Form&#8217;s Background Color', array( $this, 'jsm_custom_login_small_forms_background' ) , 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'small_form_background' ) );
		add_settings_field( 'jsm_custom_login_logo_bk_image', '<i class="fa fa-upload"></i> Background Image', array( $this, 'jsm_custom_login_setting_bk_image' ), 'simple-login-customizer', 'jsm_custom_login_main', array( 'label_for' => 'bk_image' ) );
		add_settings_field( 'jsm_custom_login_bk_image_preview', '<i class="fa fa-picture-o"></i> Background Image Preview', array( $this, 'jsm_custom_login_setting_bk_image_preview' ), 'simple-login-customizer', 'jsm_custom_login_main' );

		// Text Settings
		add_settings_section( 'jsm_custom_login_text', 'Text Settings', array( $this, 'jsm_custom_login_section_text' ), 'text-settings-customizer' );
		add_settings_field( 'jsm_custom_login_logo_text_color', '<i class="fa fa-paint-brush"></i> Text Color', array( $this, 'jsm_custom_login_setting_text_color' ), 'text-settings-customizer', 'jsm_custom_login_text', array( 'label_for' => 'text_color' ) );
		add_settings_field( 'jsm_custom_login_logo_link_text_color', '<i class="fa fa-paint-brush"></i> Link Text Color', array( $this, 'jsm_custom_login_setting_link_text_color' ), 'text-settings-customizer', 'jsm_custom_login_text', array( 'label_for' => 'link_text_color' ) );
		add_settings_field( 'jsm_custom_login_logo_link_text_hover_color', '<i class="fa fa-paint-brush"></i> Link Text Hover Color', array( $this, 'jsm_custom_login_setting_link_text_hover_color' ), 'text-settings-customizer', 'jsm_custom_login_text', array( 'label_for' => 'link_text_hover_color' ) );
		add_settings_field( 'login_logo_hide_link', '<i class="fa fa-paint-brush"></i> Hide Links', array( $this, 'l7lc_login_logo_hide_link' ), 'text-settings-customizer', 'jsm_custom_login_text', array( 'label_for' => 'login_logo_hide_link' ) );
		add_settings_field( 'login_logo_hide_backtoblog', '<i class="fa fa-paint-brush"></i> Hide Back to Blog Link', array( $this, 'l7lc_login_logo_hide_backtoblog' ), 'text-settings-customizer', 'jsm_custom_login_text', array( 'label_for' => 'login_logo_hide_backtoblog' ) );

		// Css Settings
		add_settings_section( 'jsm_custom_login_css', 'CSS Settings', array( $this, 'jsm_custom_login_section_text' ), 'css-settings-customizer' );
		add_settings_field( 'jsm_custom_login_css', '<i class="fa fa-css3"></i> Custom CSS', array( $this, 'jsm_custom_login_setting_css_input' ) , 'css-settings-customizer', 'jsm_custom_login_css', array( 'label_for' => 'text_area' ) );

		// Replace text in logo upload.
		if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) {
			add_filter( 'gettext', array( $this, 'jsm_custom_login_replace_thickbox_text' )  , 1, 3 );
		}

		// Change the link URL filter
    	add_filter( 'login_headerurl', array( $this, 'l7wc_logo_link_change' ) );
	}

	/**
	 * Changes the link of the logo.  
	 * @return string 
	 */
	public function l7wc_logo_link_change(){
		$options = get_option( 'jsm_custom_login_options' );
		$link_url = $options['l7wc_link_url'];
		return "http://layer7web.com";
	}

	/**
	 * Explanations about the logo customizing section. Simple text description
	 * of the settings section jsm_custom_login_main
	 * @return html
	 */
	public function jsm_custom_login_section_page() {
	}

	/**
	 * Explanations about the logo settings. Simple text description
	 * of the settings section.
	 * @return html
	 */
	public function jsm_custom_login_section_logo() {
	}

	/**
	 * Explanations about the text settings. Simple text description
	 * of the settings section.
	 * @return html
	 */
	public function jsm_custom_login_section_text() {
	}

	/**
	 * Logo Upload. This contains the logo upload text field, logo upload button
	 * and short description.
	 * @return html
	 */
	public function jsm_custom_login_logo_upload(){
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'text_string' );
		$content = "<div class='input-group'>
        				<span class='input-group-btn'>
        					<button class='btn btn-info' id='upload_logo_button' type='button'>Select</button>
        				</span>
        				<input id='text_string' name='jsm_custom_login_options[text_string]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' placeholder='Logo url here or click select' style='width:150%;'/>
        			</div>";
		$content .= "<span class='description'>Upload or choose an image for the logo.</span>";
		echo $content;
	}

	/**
	 * Callback to collect the logo size and display it if it is set.
	 * @return html
	 */
	public function jsm_custom_login_logo_size() {
		$options = get_option( 'jsm_custom_login_options' );
		$logo_size = $this->jsm_custom_login_isset( $options, 'logo_height' );
		$content = "<div class='input-group'><span class='input-group-addon'><i>Height</i></span><input id='logo_height' name='jsm_custom_login_options[logo_height]' type='text' value='" . esc_attr( $logo_size ) . "' class='form-control' /></div>";
		$content .= "<span class='description'>Example: 100px</span>";
		echo $content;
	}

	/**
	 * Callback to collect the logo width and display it if it is set.
	 * @return html
	 */
	public function jsm_custom_login_logo_width() {
		$options = get_option( 'jsm_custom_login_options' );
		$logo_width = $this->jsm_custom_login_isset( $options, 'logo_width' );
		$content = "<div class='input-group'><span class='input-group-addon'><i>Width</i></span><input id='logo_width' name='jsm_custom_login_options[logo_width]' type='text' value='" . esc_attr( $logo_width ) . "' class='form-control' /></div>";
		$content .= "<span class='description'>Example: 100px</span>";
		echo $content;
	}

	/**
	 * Show the current logo for preview.  Displays the currently set logo.  
	 * @return html 
	 */
	public function jsm_custom_login_setting_logo_preview() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'text_string' );
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
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'link_url' );
		$content = "<div class='input-group'><span class='input-group-addon'><i></i></span><input id='link_url' name='jsm_custom_login_options[link_url]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' style='width:150%;' /></div>";
		$content .= '<span class="description">http://example.com</span>';
		echo $content;
	}

	/**
	 * Display and fill the form field for the Logo image title.  The 
	 * value variable is escaped using esc_attr().
	 * @return html
	 */
	public function jsm_custom_login_setting_hover_title() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'hover_title' );
		$output = "<div class='input-group'><span class='input-group-addon'><i></i></span><input id='hover_title' name='jsm_custom_login_options[hover_title]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' style='width:150%;'/></div>";
		echo $output;
	}

	/**
	 * Display and fill the field for the space between the logo and the top of the small form. 
	 * This setting is important because when logo's are uploaded, depending on their size, the space between
	 * the logo and the top of the form is to large.
	 */
	public function jsm_custom_login_setting_logo_top_form() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'l7wc_top_form_space' );
		$output = "<div class='input-group'><span class='input-group-addon'><i>Height</i></span><input id='l7wc_top_form_space' name='jsm_custom_login_options[l7wc_top_form_space]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control'/></div>";
		$output .= "<span class='description'>Distance from the bottom of the logo and the top of the inner form.<br />Example: 100px</span>";
		echo $output;
	}

	/**
	 * The entire page's background color. Page's Background Color. 
	 * @return html
	 */
	public function jsm_custom_login_setting_bk_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'bk_color' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='bk_color' name='jsm_custom_login_options[bk_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Option for the background color of the large form. This is the big container
	 * that contains all of the links and login fields.  Set to #fff as default in 
	 * the css. 
	 * @return html 
	 */
	public function jsm_custom_login_forms_background() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'form_background' );
		$output = "<div class='input-group color-picker colorpicker-element'><span class='input-group-addon'><i></i></span><input id='form_background' name='jsm_custom_login_options[form_background]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Small form option for background color. This is the part of the
	 * form that does not include the logo or the other links on the bottom.
	 * This is the text fields container with class .login form.
	 * @return html 
	 */
	public function jsm_custom_login_small_forms_background() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'small_form_background' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='small_form_background' name='jsm_custom_login_options[small_form_background]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Background image upload.
	 * @return html
	 */
	public function jsm_custom_login_setting_bk_image() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'bk_image' );
		$this->jsm_custom_login_isset( $options, 'image_pos' );
		$content = "<div class='input-group'>
        				<span class='input-group-btn'>
        					<button class='btn btn-info' id='upload_bk_image_button' type='button'>Select</button>
        				</span>
        				<input id='bk_image' name='jsm_custom_login_options[bk_image]' type='text' value='" . esc_attr( $text_string ) . "' class='form-control' placeholder='Image url here or click select.' style='width:150%;'/>
					</div>
					<div name='jsm_custom_login_options[image_pos]' class='input-group'>	
						<select class='form-control' id='image_pos' name='jsm_custom_login_options[image_pos]'>
						  <option value='repeat' ";
		$content .= ( $options['image_pos'] == 'repeat' ) ? 'selected' : '';
		$content .= ">Repeat</option>
						  <option value='center' ";
		$content .= ( $options['image_pos'] == 'center' ) ? 'selected' : '';
		$content .= '>Center</option>
						</select>	
        			</div>';
	 	echo $content;
	}

	/**
	 * Show the current background image.  Displays the currently chosen and saved background image.
	 * @return html 
	 */
	public function jsm_custom_login_setting_bk_image_preview() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'bk_image' );
		?>
		<div id="upload_bk_image_preview" style="min-height: 100px;">
	    	<img style="max-width:100%;" src="<?php echo esc_url( $text_string ); ?>" />
		</div>
	<?php
	}

	/**
	 * The Text Color Option
	 * @return html
	 */
	public function jsm_custom_login_setting_text_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'text_color' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='text_color' name='jsm_custom_login_options[text_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Link text color. 
	 * @return html
	 */
	public function jsm_custom_login_setting_link_text_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'link_text_color' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='link_text_color' name='jsm_custom_login_options[link_text_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Link text hover color. For the links to register and return to homepage.
	 * @return html 
	 */
	public function jsm_custom_login_setting_link_text_hover_color() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'link_text_hover_color' );
		$output = "<div class='input-group color-picker'><span class='input-group-addon'><i></i></span><input id='link_text_hover_color' name='jsm_custom_login_options[link_text_hover_color]' type='text' value='" . esc_attr( $text_string ). "' class='form-control' /></div>";
		$output .= "<span class='description'>6 digit hex color code</span>";
		echo $output;
	}

	/**
	 * Hide the links on the bottom of the form, not the back to blog link.
	 * @return html
	 */
	public function l7lc_login_logo_hide_link() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'login_logo_hide_link' );
		'1' == $text_string ? $checked = 'checked': $checked = '';
		$output = '<input type="checkbox" value="1" id="login_logo_hide_link" name="jsm_custom_login_options[login_logo_hide_link]"' . $checked . ' >';
		echo $output;
	}

	/**
	 * Hide the backtoblog link on the bottom of the form.
	 * @return html
	 */
	public function l7lc_login_logo_hide_backtoblog(){
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'login_logo_hide_backtoblog' );
		'1' == $text_string ? $checked = 'checked': $checked = '';
		$output = '<input type="checkbox" value="1" id="login_logo_hide_backtoblog" name="jsm_custom_login_options[login_logo_hide_backtoblog]"' . $checked . ' >';
		echo $output;
	}

	/**
	 * Display and fill the text area for the custom css. This text is sanitized using
	 * the wp_kses() function.  This is redundant due to the variable $text_string being 
	 * run through the esc_html() function. Code sniffer calls for the return value to be escaped.
	 * @return html
	 */
	public function jsm_custom_login_setting_css_input() {
		$options = get_option( 'jsm_custom_login_options' );
		$text_string = $this->jsm_custom_login_isset( $options, 'text_area' );
		$content = "<textarea id='text_area' name='jsm_custom_login_options[text_area]' type='textarea' rows='14' cols='50'>" . esc_attr( $text_string ) . '</textarea>';
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
	 * Change text in file upload to make sense with what action that is taking place. The thickbox
	 * button shows 'Insert into Post' which is not user friendly since they are uploading an image
	 * to the settings page.  This filters the 'Insert into Post' and changes it to 'Select Image'.
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
	 * This function makes sure none of the inputs are harmful. It takes
	 * the input array of the form fields and uses various apropriate sanitization funtions to clean the 
	 * inputs.
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
  			add_settings_error( 'jsm_custom_logo_text_string', 'invalid-url-logo', 'You have entered an invalid logo URL.' );
		}

		/**
		 * For height of the Logo. Sanitize and validation with error.
		 */ 
		$arg_array['logo_height'] = esc_html( $input['logo_height'] );
		if ( ( ! preg_match( "/^[0-9]*px$/", $arg_array['logo_height'] ) ) && ( $arg_array['logo_height'] != '' ) ) {
  			add_settings_error( 'jsm_custom_logo_size', 'invalid-logo-height', 'You have entered an invalid logo height. Please enter height in this format: \'100<b>px</b>\'.' );
		}

		/**
		 * For logo width sanitization and validation. 
		 */
		$arg_array['logo_width'] = esc_html( $input['logo_width'] );
		if ( ( ! preg_match( "/^[0-9]*px$/", $arg_array['logo_width'] ) ) && ( $arg_array['logo_width'] != '' ) ) {
  			add_settings_error( 'jsm_custom_logo_width', 'invalid-logo-width', 'You have entered an invalid logo width. Please enter width in this format: \'100<b>px</b>\'.' );
		}

		/**
		 * The link url (the link applied to the logo). Sanitized and validated with error message.
		 */
		$arg_array['link_url'] = esc_url( $input['link_url'] );
		if ( ( ! preg_match( "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $arg_array['link_url'] ) ) && ( $arg_array['link_url'] != '' ) ) {
  			add_settings_error( 'jsm_custom_login_logo_link_url', 'invalid-url-link', 'You have entered an invalid link URL.' );
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
		if ( ( ! preg_match( "/^[0-9]*px$/", $arg_array['l7wc_top_form_space'] ) ) && ( $arg_array['l7wc_top_form_space'] != '' ) ) {
  			add_settings_error( 'l7wc_top_form_space', 'invalid-logo-height', 'You have entered an invalid Space to Top of Form value. Please enter value in this format: \'100<b>px</b>\'.' );
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
  			add_settings_error( 'jsm_custom_login_logo_bk_image', 'bad-bk_image', 'You have entered an invalid background image URL.' );
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
		 * CSS text_area sanitization. This is the custom css field.
		 */
		$arg_array['text_area'] = esc_html( $input['text_area'] );

		return $arg_array;
	}

	/**
	 * Check the array element to make sure it is set.  If it is not make it empty.
	 * This avoids errors on install.  There is a better way to set default options.
	 * Look it up.
	 * @param  [array] $array_key 
	 * @return [string] setting returned
	 */
	public function jsm_custom_login_isset( &$options, $key ){
		if ( isset( $options[$key] ) ){
			return $options[$key];
		}

		/**
		 * Logo Height and Width logic, fixes the background-size bug. 
		 * If one field is left empty it is set to auto.
		 */
		if ( '' == trim( $options['logo_height'] ) ){
			$options['logo_height'] == 'auto';
		}
		if ( '' == trim( $options['logo_width'] ) ){
			$options['logo_width'] == 'auto';
		}
		
		/**
		 * Add default settings here.
		 */
		switch ($key) {
			case 'text_string':
				$options[$key] = '';
				break;
			case 'link_text_color':
				$options[$key] = '';
				break;
			case 'link_url':
				$options[$key] = '';
				break;
			case 'hover_title':
				$options[$key] = '';
				break;
			case 'text_color':
				$options[$key] = '';
				break;
			case 'form_background':
				$options[$key] = '';
				break;
			case 'small_form_background':
				$options[$key] = '';
				break;
			case 'link_text_hover_color':
				$options[$key] = '';
				break;
			case 'l7wc_top_form_space':
				$options[$key] = '';
				break;
			case 'login_logo_hide_link':
				$options[$key] = '';
				break;
			case 'login_logo_hide_backtoblog':
				$options[$key] = '';
				break;
			default:
				$options[$key] = '';
		}
		return $options[$key];
	}
}
