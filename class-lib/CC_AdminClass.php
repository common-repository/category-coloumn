<?php

/**
 *
 * Class Category Column Widget Admin
 *
 * @ A5 Category Column Widget
 *
 * building admin page
 *
 */
class CC_Admin extends A5_OptionPage {
	
	const language_file = 'category_column';
	
	static $options;
	
	function __construct() {
	
		add_action('admin_init', array($this, 'initialize_settings'));
		add_action('admin_menu', array($this, 'add_admin_menu'));
		if (WP_DEBUG == true) add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
		
		self::$options = get_option('cc_options');
		
	}
	
	/**
	 *
	 * Make debug info collapsable
	 *
	 */
	function enqueue_scripts($hook){
		
		if ($hook != 'settings_page_category-column-settings') return;
		
		wp_enqueue_script('dashboard');
		
		if (wp_is_mobile()) wp_enqueue_script('jquery-touch-punch');
		
	}
	
	/**
	 *
	 * Add options-page for single site
	 *
	 */
	function add_admin_menu() {
		
		add_options_page('Category Column '.__('Settings', 'category_column'), '<img alt="" src="'.plugins_url('category-coloumn/img/a5-icon-11.png').'"> Category Column', 'administrator', 'category-column-settings', array($this, 'build_options_page'));
		
	}
	
	/**
	 *
	 * Actually build the option pages
	 *
	 */
	function build_options_page() {
		
		$eol = "\n";
		
		self::open_page('Category Column', __('http://wasistlos.waldemarstoffel.com/plugins-fur-wordpress/category-column-plugin', 'category_column'), 'category-coloumn', __('Plugin Support', 'category_column'));
		
		self::open_form('options.php');
		
		settings_fields('cc_options');
		do_settings_sections('cc_style');
		submit_button();
		
		if (WP_DEBUG === true) :
		
			self::open_tab();
			
			self::sortable('deep-down', self::debug_info(self::$options, __('Debug Info', 'category_column')));
		
			self::close_tab();
		
		endif;
		
		self::close_page();
		
	}
	
	/**
	 *
	 * Initialize the admin screen of the plugin
	 *
	 */
	function initialize_settings() {
		
		register_setting( 'cc_options', 'cc_options', array($this, 'validate') );
		
		add_settings_section('cc_settings', __('Styling of the widgets', 'category_column'), array($this, 'display_section'), 'cc_style');
		
		add_settings_field('cc_css', __('Widget container:', 'category_column'), array($this, 'css_field'), 'cc_style', 'cc_settings', array(__('You can enter your own style for the widgets here. This will overwrite the styles of your theme.', 'category_column'), __('If you leave this empty, you can still style every instance of the widget individually.', 'category_column')));
		
		add_settings_field('cc_compress', __('Compress Style Sheet:', 'category_column'), array($this, 'compress_field'), 'cc_style', 'cc_settings', array(__('Click here to compress the style sheet.', 'category_column')));
		
		add_settings_field('cc_inline', __('Debug:', 'category_column'), array($this, 'inline_field'), 'cc_style', 'cc_settings', array(__('If you can&#39;t reach the dynamical style sheet, you&#39;ll have to diplay the styles inline. By clicking here you can do so.', 'category_column')));
		
		$cachesize = count(self::$options['cache']);
		
		$entry = ($cachesize > 1) ? __('entries', 'category_column') : __('entry', 'category_column');
		
		if ($cachesize > 0) add_settings_field('cc_reset', sprintf(__('Empty cache (%d %s):', 'category_column'), $cachesize, $entry), array($this, 'reset_field'), 'cc_style', 'cc_settings', array(__('You can empty the plugin&#39;s cache here, if necessary.', 'category_column')));
		
		add_settings_field('cc_resize', false, array($this, 'resize_field'), 'cc_style', 'cc_settings');
	
	}
	
	function display_section() {
		
		self::tag_it(__('Just put some css code here.', 'category_column'), 'p');
	
	}
	
	function css_field($labels) {
		
		echo $labels[0].'</br>'.$labels[1].'</br>';
		
		a5_textarea('css', 'cc_options[css]', @self::$options['css'], false, array('rows' => 7, 'cols' => 35));
		
	}
	
	function compress_field($labels) {
		
		a5_checkbox('compress', 'cc_options[compress]', @self::$options['compress'], $labels[0]);
		
	}
	
	function inline_field($labels) {
		
		a5_checkbox('inline', 'cc_options[inline]', @self::$options['inline'], $labels[0]);
		
	}
	
	function reset_field($labels) {
		
		a5_checkbox('reset_options', 'cc_options[reset_options]', @self::$options['reset_options'], $labels[0]);
		
	}
	
	function resize_field() {
		
		a5_resize_textarea('css');
		
	}
		
	function validate($input) {
		
		self::$options['css']=trim($input['css']);
		self::$options['compress'] = isset($input['compress']) ? true : false;
		self::$options['inline'] = isset($input['inline']) ? true : false;
		
		if (isset($input['reset_options'])) :
		
			self::$options['cache'] = array();
			
			add_settings_error('cc_options', 'empty-cache', __('Cache emptied.', 'category_column'), 'updated');
			
		endif;
		
		self::$options['css_cache'] = '';
		
		return self::$options;
	
	}

} // end of class

?>