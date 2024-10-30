<?php

/**
 *
 * Class CC Dynamic CSS
 *
 * Extending A5 Dynamic Files
 *
 * Presses the dynamical CSS of the Category Column Widget into a virtual style sheet
 *
 */

class CC_DynamicCSS extends A5_DynamicFiles {
	
	private static $options = array();
	
	function __construct() {
		
		self::$options = get_option('cc_options');
		
		if (!array_key_exists('inline', self::$options)) self::$options['inline'] = false;
		
		if (!array_key_exists('priority', self::$options)) self::$options['priority'] = false;
		
		if (!array_key_exists('compress', self::$options)) self::$options['compress'] = true;
		
		$this->a5_styles('wp', 'all', self::$options['inline'], self::$options['priority']);
		
		$cc_styles = self::$options['css_cache'];
		
		if (!$cc_styles) :
		
			$eol = (self::$options['compress']) ? '' : "\n";
			$tab = (self::$options['compress']) ? '' : "\t";
			
			$css_selector = 'widget_category_column_widget[id^="category_column_widget"]';
			
			$cc_styles = (!self::$options['compress']) ? $eol.'/* CSS portion of the Category Column */'.$eol.$eol : '';
			
			if (!empty(self::$options['css'])) :
			
				$style = $eol.$tab.str_replace('; ', ';'.$eol.$tab, str_replace(array("\r\n", "\n", "\r"), ' ', self::$options['css']));
			
				$cc_styles .= parent::build_widget_css($css_selector, '').'{'.$eol.$tab.$style.$eol.'}'.$eol;
				
			endif;
			
			$cc_styles .= parent::build_widget_css($css_selector, 'img').'{'.$eol.$tab.'height: auto;'.$eol.$tab.'max-width: 100%;'.$eol.'}'.$eol;
			
			self::$options['css_cache'] = $cc_styles;
			
			update_option('cc_options', self::$options);
			
		endif;
		
		parent::$wp_styles .= $cc_styles;

	}
	
} // CC_Dynamic CSS

?>