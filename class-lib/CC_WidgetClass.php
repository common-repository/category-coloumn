<?php

/**
 *
 * Class CC Widget
 *
 * @ Advanced Featured Post Widget
 *
 * building the actual widget
 *
 */
class Category_Column_Widget extends A5_Widget {
	
	private static $options;
	 
	function __construct() {
	
		$widget_opts = array( 'description' => __('Configure the output and looks of the widget. Then display thumbnails and excerpts of posts in your sidebars.', 'category_column') );
		$control_opts = array( 'width' => 400 );
		
		parent::__construct(false, $name = 'Category Column', $widget_opts, $control_opts);
		
		self::$options = get_option('cc_options');
	
	}
	 
	function form($instance) {
		
		// setup some default settings
		
		$defaults = array(
			'title' => NULL,
			'postcount' => 5,
			'offset' => 3,
			'home' => 1,
			'list' => NULL,
			'showcat' => NULL,
			'showcat_txt' => NULL,
			'wordcount' => 3,
			'linespace' => NULL,
			'width' => get_option('thumbnail_size_w'),
			'words' => NULL,
			'line' => 1,
			'line_color' => '#dddddd',
			'style' => NULL,
			'h' => 3,
			'halign' => 'left',
			'imgborder' => NULL,
			'headonly' => NULL
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults );
		
		$title = esc_attr($instance['title']);
		$postcount = esc_attr($instance['postcount']);
		$offset = esc_attr($instance['offset']);
		$home = $instance['home'];
		$list = esc_attr($instance['list']);
		$showcat = $instance['showcat'];
		$showcat_txt = esc_attr($instance['showcat_txt']);
		$wordcount = esc_attr($instance['wordcount']);
		$linespace = $instance['linespace'];
		$width = esc_attr($instance['width']);
		$words = $instance['words'];
		$line = esc_attr($instance['line']);
		$line_color = esc_attr($instance['line_color']);
		$style = esc_attr($instance['style']);
		$h = $instance['h'];
		$halign = $instance['halign'];
		$headonly = $instance['headonly'];
		$imgborder = esc_attr($instance['imgborder']);
		
		$base_id = 'widget-'.$this->id_base.'-'.$this->number.'-';
		$base_name = 'widget-'.$this->id_base.'['.$this->number.']';
		
		a5_text_field($base_id.'title', $base_name.'[title]', $title, __('Title:', 'category_column'), array('space' => true, 'class' => 'widefat'));
		a5_text_field($base_id.'list', $base_name.'[list]', $list, sprintf(__('To exclude certain categories or to show just a special category, simply write their ID&#39;s separated by comma (e.g. %s-5, 2, 4%s will show categories 2 and 4 and will exclude category 5):', 'category_column'), '<strong>', '</strong>'), array('space' => true, 'class' => 'widefat'));
		a5_checkbox($base_id.'showcat', $base_name.'[showcat]', $showcat, __('Check to show the categories in which the post is filed.', 'category_column'), array('space' => true));
		a5_text_field($base_id.'showcat_txt', $base_name.'[showcat_txt]', $showcat_txt, __('Give some text that you want in front of the post&#39;s categtories (i.e &#39;filed under&#39;:', 'category_column'), array('space' => true, 'class' => 'widefat'));
		a5_number_field($base_id.'postcount', $base_name.'[postcount]', $postcount, __('How many posts will be displayed in the sidebar:', 'category_column'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_number_field($base_id.'offset', $base_name.'[offset]', $offset, __('Offset (how many posts are spared out in the beginning):', 'category_column'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_checkbox($base_id.'home', $base_name.'[home]', $home, __('Check to have the offset only on your homepage.', 'category_column'), array('space' => true));
		a5_number_field($base_id.'width', $base_name.'[width]', $width, __('Width of the thumbnail (in px):', 'category_column'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_text_field($base_id.'imgborder', $base_name.'[imgborder]', $imgborder, sprintf(__('If wanting a border around the image, write the style here. %s would make it a black border, 1px wide.', 'category_column'), '<strong>1px solid #000000</strong>'), array('space' => true, 'class' => 'widefat'));
		parent::select_heading($instance, true);
		a5_checkbox($base_id.'headonly', $base_name.'[headonly]', $headonly, __('Check to display only the headline of the post.', 'category_column'), array('space' => true));
		a5_number_field($base_id.'wordcount', $base_name.'[wordcount]', $wordcount, __('In case there is no excerpt defined, how many sentences are displayed:', 'category_column'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_checkbox($base_id.'words', $base_name.'[words]', $words, __('Check to display words instead of sentences.', 'category_column'), array('space' => true));
		a5_checkbox($base_id.'linespace', $base_name.'[linespace]', $linespace, __('Check to have each sentense in a new line.', 'category_column'), array('space' => true));
		a5_number_field($base_id.'line', $base_name.'[line]', $line, __('If you want a line between the posts, this is the height in px (if not wanting a line, leave emtpy):', 'category_column'), array('space' => true, 'size' => 4, 'step' => 1));
		a5_color_field($base_id.'line_color', $base_name.'[line_color]', $line_color, __('The color of the line (e.g. #cccccc):', 'category_column'), array('space' => true, 'size' => 13));
		a5_textarea($base_id.'style', $base_name.'[style]', $style, sprintf(__('Here you can finally style the widget. Simply type something like%1$s%2$sborder-left: 1px dashed;%2$sborder-color: #000000;%3$s%2$sto get just a dashed black line on the left. If you leave that section empty, your theme will style the widget.', 'category_column'), '<strong>', '<br />', '</strong>'), array('space' => true, 'class' => 'widefat', 'style' => 'height: 60px;'));
		a5_resize_textarea(array($base_id.'style'));
	
	} // form
	
	function update($new_instance, $old_instance) {
		
		unset(self::$options['cache'][$this->number]);
			
		global $wpdb;
		
		$update_args = array('option_value' => serialize(self::$options));
		
		$result = $wpdb->update( $wpdb->options, $update_args, array( 'option_name' => 'cc_options' ) );
	
		$instance = $old_instance;
		
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['postcount'] = strip_tags($new_instance['postcount']);
		$instance['offset'] = strip_tags($new_instance['offset']);
		$instance['home'] = $new_instance['home'];
		$instance['list'] = strip_tags($new_instance['list']);
		$instance['showcat'] = @$new_instance['showcat'];
		$instance['showcat_txt'] = strip_tags($new_instance['showcat_txt']); 	
		$instance['wordcount'] = strip_tags($new_instance['wordcount']);
		$instance['width'] = strip_tags($new_instance['width']);
		$instance['words'] = @$new_instance['words'];
		$instance['linespace'] = @$new_instance['linespace'];
		$instance['line'] = strip_tags($new_instance['line']);
		$instance['line_color'] = strip_tags($new_instance['line_color']);
		$instance['style'] = strip_tags($new_instance['style']);
		$instance['h'] = $new_instance['h'];
		$instance['halign'] = $new_instance['halign'];
		$instance['headonly'] = @$new_instance['headonly'];
		$instance['imgborder'] = strip_tags($new_instance['imgborder']);
		
		return $instance;
	
	}
	
	function widget($args, $instance) {
		
		extract( $args );
		
		$eol = "\n";
		
		$title = apply_filters('widget_title', $instance['title']);
		
		if (!empty($instance['style'])) :
				
			$style=str_replace(array("\r\n", "\n", "\r"), '', $instance['style']);
			
			$before_widget = str_replace('>', 'style="'.$style.'">', $before_widget);
		
		endif;
		
		echo $before_widget;
		
		if ( $title ) echo $before_title . $title . $after_title;
	 
		/* This is the actual function of the plugin, it fills the sidebar with the customized excerpts */
		
		$i=1;
		
		$cc_setup['posts_per_page'] = $instance['postcount'];
			
		global $wp_query, $post;
		
		if (is_category() || is_home() || empty($instance['home'])) :
			
			$cc_page = $wp_query->get( 'paged' );
			
			$cc_numberposts = $wp_query->get( 'posts_per_page' );
			
			$cc_offset = (empty($cc_page)) ? $cc_offset=$instance['offset'] : $cc_offset=(($cc_page-1)*$cc_numberposts)+$instance['offset'];
			
			$cc_setup['offset'] = $cc_offset;
		
		endif;
		
		$cc_cat = (is_category()) ? ',-'.get_query_var('cat') : '';
		
		if ($instance['list'] || !empty($cc_cat)) $cc_setup['cat'] = $instance['list'].$cc_cat;
		
		if (is_single()) :
			
			$cc_setup['post__not_in'] = array($wp_query->get_queried_object_id()); 
		
		endif;
		
		$cc_posts = new WP_Query($cc_setup);
		
		$count = 0;
		
		while($cc_posts->have_posts()) :
		
			$cc_posts->the_post();
			
			setup_postdata($post);
		
			if ($instance['showcat']) :
				
				$post_byline = ($instance['showcat_txt']) ? $eol.'<p id="cc_byline-'.$widget_id.'-'.$count.'">'.$eol.$instance['showcat_txt'].' ' : $eol.'<p id="cc_byline-'.$widget_id.'-'.$count.'">';
				
				echo $post_byline;
				
				the_category(', ');
				
				echo $eol.'</p>'.$eol;
				
			endif;
			
			if (isset(self::$options['cache'][$this->number][$post->ID]['tags'])) :
				
				$cc_tags = self::$options['cache'][$this->number][$post->ID]['tags'];
			
			else :
		
				$cc_tags = A5_Image::tags();
				
				self::$options['cache'][$this->number][$post->ID]['tags'] = $cc_tags;
					
				update_option('cc_options', self::$options);
				
			endif;
			
			$cc_image_alt = $cc_tags['image_alt'];
			$cc_image_title = $cc_tags['image_title'];
			$cc_title_tag = $cc_tags['title_tag'];
			
			if (isset(self::$options['cache'][$this->number][$post->ID]['headline'])) :
			
				$cc_headline = self::$options['cache'][$this->number][$post->ID]['headline'];
			
			else :
			
				$cc_headline = '<h'.$instance['h'].' style="text-align: '.$instance['halign'].'">'.$eol.'<a href="'.get_permalink().'" title="'.$cc_title_tag.'">'.get_the_title().'</a>'.$eol.'</h'.$instance['h'].'>';
				
				self::$options['cache'][$this->number][$post->ID]['headline'] = $cc_headline;
						
				update_option('cc_options', self::$options);
				
			endif;
			
			// get thumbnail
			
			if (empty($instance['headonly'])) :
			
				if (isset(self::$options['cache'][$this->number][$post->ID]['image'])) :
			
					$cc_image = self::$options['cache'][$this->number][$post->ID]['image'];
				
				else :
				
					$cc_image = false;
				
					$cc_imgborder = (!empty($instance['imgborder'])) ? ' style="border: '.$instance['imgborder'].';"' : '';
					
					$args = array (
						'id' => $post->ID,
						'width' => $instance['width']
					);
							
					$cc_image_info = A5_Image::thumbnail($args);
					
					if ($cc_image_info) :
							
						$cc_thumb = $cc_image_info[0];
						
						$cc_width = $cc_image_info[1];
				
						$cc_height = ($cc_image_info[2]) ? 'height="'.$cc_image_info[2].'"' : '';
						
						$cc_image_tag = '<img title="'.$cc_image_title.'" src="'.$cc_thumb.'" alt="'.$cc_image_alt.'" class="wp-post-image" width="'.$cc_width.'"'.$cc_height.$cc_imgborder.' />';
						
					else :
				
						$cc_image_tag = '';
						
					endif;
					
					if (!empty($cc_image_tag)) :
				
						$cc_image = '<a href="'.get_permalink().'">'.$cc_image_tag.'</a>'.$eol.'<div style="clear: both;"></div>';
						
						self::$options['cache'][$this->number][$post->ID]['image'] = $cc_image;
						
						update_option('cc_options', self::$options);
						
					endif;
					
				endif;
				
			endif;
						
			if ($cc_image) :
				
				echo $cc_image.$eol.$cc_headline;
			
			else :
					
				/* If there is no picture, show headline and excerpt of the post */
				
				echo $cc_headline;
				
				if (empty($instance['headonly'])) :
					
					/* in case the excerpt is not definded by theme or anything else, the first x sentences of the content are given */
					if (isset(self::$options['cache'][$this->number][$post->ID]['text'])) :
				
						$cc_text = self::$options['cache'][$this->number][$post->ID]['text'];
					
					else :
					
						$type = (empty($instance['words'])) ? 'sentences' : 'words';
							
						$args = array(
							'excerpt' => $post->post_excerpt,
							'content' => $post->post_content,
							'type' => $type,
							'count' => $instance['wordcount'],
							'linespace' => $instance['linespace'],
							'filter' => true
						);
						
						$cc_text = A5_Excerpt::text($args);
						
						self::$options['cache'][$this->number][$post->ID]['text'] = $cc_text;
						
						update_option('cc_options', self::$options);
						
					endif;
					
					if ($cc_text) echo $cc_text;
					
				endif;
				
			endif;
						
			if (!empty($instance['line']) && $i <  $instance['postcount']) :
				
				echo '<hr style="color: '.$instance['line_color'].'; background-color: '.$instance['line_color'].'; height: '.$instance['line'].'px;" />';
				
				$i++;
				
			endif;
				
			$count++; 
				
		endwhile;
		
		wp_reset_postdata();
		
		echo $after_widget;
	
	}
 
} // end of class

add_action('widgets_init', create_function('', 'return register_widget("Category_Column_Widget");'));

?>