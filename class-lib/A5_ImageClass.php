<?php

/**
 *
 * Class A5 Images
 *
 * @ A5 Plugin Framework
 * Version: 1.0 beta 20160127
 *
 * Gets the alt and title tag for attachments
 *
 * Gets all thumbnail related stuff
 *
 */

class A5_Image {
	
	public static function tags() {
		
		$id = get_the_ID();
		
		if (has_post_thumbnail()) :
			
			$attachment_id = get_post_thumbnail_id();
			
			$attachment = get_post($attachment_id);
		
		else:
			
			$args = array(
				'post_type' => 'attachment',
				'posts_per_page' => 1,
				'post_status' => null,
				'post_parent' => $id,
				'order' => 'ASC'
			);
			
			$attachments = get_posts( $args );
			
			if ( $attachments ) $attachment = $attachments[0];
			
		endif;
		
		if (!isset($attachment)) return false;
		
		$title = get_the_title($id);
		
		$title_tag = __('Permalink to', 'category_column').' '.esc_attr($title);
				  
		$image_alt = trim(strip_tags( get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true) ));
		
		$image_title = trim(strip_tags( $attachment->post_title ));
	
		$image_alt = (empty($image_alt)) ? esc_attr($title) : esc_attr($image_alt);
		$image_title = (empty($image_title)) ? esc_attr($title) : esc_attr($image_title);
		
		$tags = array(
			'image_alt' => $image_alt,
			'image_title' => $image_title,
			'title_tag' => $title_tag
		);
		
		return $tags;
	
	} // tags
	
	/**
 	 *
	 * getting the post thumbnail in the size we want as featured image
	 * if there's no thumbnail, an attachment is taken as featured image
	 * if there's no attachment, the first image of the post is taken as featured image
	 * if the variable number is specified all of the above is skipped and we look for that specific image in the post
	 *
	 * @param: $id
	 *
	 * @optional param: [$image_size (i.e 'medium')], [$width], [$height], [$number]
	 * 
	 */
	 
	public static function thumbnail($args) {
		
		extract($args);
		
		if (!isset($image_size) && !isset($height) && (!isset($width) || empty($width))) $image_size = 'thumbnail';
		
		if (!isset($height)) $height = 9999;
		
		if (!isset($image_size)) $image_size = array($width, $height);
		
		if (!isset($number)) :
		
			if (has_post_thumbnail()) $attachment_id = get_post_thumbnail_id();
			
			if (!isset($attachment_id)) :
			
				$args = array(
				'post_type' => 'attachment',
				'posts_per_page' => 1,
				'post_status' => null,
				'post_parent' => $id,
				'order' => 'ASC'
				);
				
				$attachments = get_posts( $args );
				
				if ( $attachments ) $attachment_id = $attachments[0]->ID;
			
			endif;
			
			if (isset($attachment_id)) :
			
				$thumb = self::get_image($attachment_id, $image_size);
			
				if (false === $thumb) unset($thumb, $attachment_id);
					
				else return $thumb;
				
			endif;

		endif;
		
		if (!is_single()) : 
		
			global $more;
			
			$more_old = $more;
			
			$more = 1;
			
		endif;
		
		$content = get_the_content();
		
		$check = do_shortcode($content);
		
		if (!is_single()) $more = $more_old;
		
		$image = preg_match_all('#(?:<a[^>]+?href=["|\'](?P<link_url>[^\s]+?)["|\'][^>]*?>\s*)?(?P<img_tag><img[^>]+?src=["|\'](?P<img_url>[^\s]+?)["|\'].*?>){1}(?:\s*</a>)?#is', $check, $matches);
		
		if (0 == $image && !isset($attachment_id)) :
		
			if (strstr($content, 'gallery')) :
			
				$ids = preg_match_all('#ids=["|\']([^\s]+?)["|\']#is', $content, $matches);
				
				$ids = explode(',', $matches[1][0]);
				
				$attachment_id = trim($ids[0]);
			
			else :
			
				return false;
				
			endif;
			
		endif;
		
		if (isset($number) || !isset($attachment_id)):
		
			$number = (isset($number)) ? $number : 1;
			
			if ($number == 'last' || $number > count($matches ['img_url'])) $number = count($matches ['img_url']);
			
			if ($number > 0) $number -= 1;
			
			if (0 != $image) :
			
				$img_src = $matches ['img_url'] [$number];
				
				$upload_dir = wp_upload_dir();
				
				if (strstr($img_src, $upload_dir['baseurl'])) $attachment_id = self::get_attachment_id_from_src($img_src);
				
			endif;
			
		endif;
			
		if (isset($attachment_id)) :
		
			$thumb = self::get_image($attachment_id, $image_size);
				
			return $thumb;
		
		endif;
		
		if (!isset($img_src)) return false;
		
		$img_tag = $matches['img_tag'][$number];
		
		$size = self::get_size($img_tag, $img_src);
		
		if (false != $size) :
		
			if ($width > $size['width']) $width = $size['width'];
			
			if ($height != 9999 && $height > $size['height']) $height = $size['height'];
			
			$thumb_width = $size['width'];
			
			$thumb_height = $size['height'];
			
			$ratio = $thumb_width/$thumb_height;
			
			$args = array(
				'ratio' => $ratio,
				'thumb_width' => $thumb_width,
				'thumb_height' => $thumb_height,
				'width' => $width,
				'height' => $height
			);
			
			$new_size = self::calculate_size($args);
			
			$thumb_width = $new_size['width'];
			$thumb_height = $new_size['height'];
			
		else :
		
			$thumb_width = $size['width'];
			$thumb_height = false;
		
		endif;
			
		$thumb = array ($img_src, $thumb_width, $thumb_height);
		
		return $thumb;
	
	} // thumbnail
	
	// getting the image size
	
	private static function get_size($tag, $img) {
		
		// First, check the image tag
		if ( preg_match( '#width=["|\']?([\d%]+)["|\']?#i', $tag, $width_string ) )
			$width = $width_string[1];

		if ( preg_match( '#height=["|\']?([\d%]+)["|\']?#i', $tag, $height_string ) )
			$height = $height_string[1];
			
		if (isset($width) && isset($height)) :
		
			if (!strpos($width, '%') && !strpos($height, '%')) return array('width' => $width, 'height' => $height);
			
		endif;
		
		$image_info = wp_get_image_editor($img);
			
		if ( ! is_wp_error($image_info) ) :
			
			$size = $image_info->get_size();
			
		else :
	
			$uploaddir = wp_upload_dir();
			
			$img = str_replace($uploaddir['baseurl'], $uploaddir['basedir'], $img);
			
			$imgsize = @getimagesize($img);
			
			if (empty($imgsize)) :
			
				if ( ! function_exists( 'download_url' ) ) require_once ABSPATH.'/wp-admin/includes/file.php';
			
				$tmp_image = download_url($img);
				
				if (!is_wp_error($tmp_image)) $imgsize = @getimagesize($tmp_img);
				
				@unlink($tmp_image);
				
			endif;
			
			$size = (!empty($imgsize)) ? array ( 'width' => $imgsize[0], 'height' => $imgsize[1] ) : false;
		
		endif;
		
		return $size;
	
	}
	
	// calculating the new size of the image
	
	private static function calculate_size($args) {
		
		extract($args);
		
		if ($thumb_width && $height != 9999) :
			
			if ($ratio > 1) :
					
				$thumb_height = intval($thumb_height/($thumb_width/$width));
				
				$thumb_width = $width;
					
				else :
				
				$thumb_width = intval($thumb_width/($thumb_height/$height));
				
				$thumb_height = $height;
				
			endif;
			
		else :
		
			$ratio = $thumb_width/$thumb_height;
		
			$thumb_width = $width;
			
			$thumb_height = intval($thumb_width/$ratio);
	
		endif;	
		
		return array('width' => $thumb_width, 'height' => $thumb_height);
	
	}
	
	/**
	 * Get size information for all currently-registered image sizes.
	 *
	 * @global $_wp_additional_image_sizes
	 * @uses   get_intermediate_image_sizes()
	 * @return array $sizes Data for all currently-registered image sizes.
	 */
	private static function get_image_sizes() {
		
		global $_wp_additional_image_sizes;
	
		$sizes = array();
	
		foreach ( get_intermediate_image_sizes() as $_size ) {
			if ( in_array( $_size, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
				$sizes[ $_size ]['width']  = get_option( "{$_size}_size_w" );
				$sizes[ $_size ]['height'] = get_option( "{$_size}_size_h" );
				$sizes[ $_size ]['crop']   = (bool) get_option( "{$_size}_crop" );
			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = array(
					'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
					'height' => $_wp_additional_image_sizes[ $_size ]['height'],
					'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
				);
			}
		}
	
		return $sizes;
	}
	
	/**
	 * Get size information for a specific image size.
	 *
	 * @uses   get_image_sizes()
	 * @param  string $size The image size for which to retrieve data.
	 * @return bool|array $size Size data about an image size or false if the size doesn't exist.
	 */
	private static function get_image_size( $size ) {
		
		$sizes = self::get_image_sizes();
	
		if ( isset( $sizes[ $size ] ) ) return $sizes[ $size ];
	
		return false;
		
	}
	
	// trying to get the attachment id from the image source
	
	private static function get_attachment_id_from_src ($image_src) {

		global $wpdb;
		
		$upload_dir = wp_upload_dir();
		
		$image_src = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $image_src );
		
		$image_src = str_replace( $upload_dir['baseurl'] . '/', '', $image_src );
		
		$id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $image_src ) );
		
		if (!isset($id)) return false;
		
		return $id;

	}
	
	// getting the image source for the thumbnail according to the desired size.
	
	private static function get_image($attachment_id, $image_size) {
		
		if (!is_array($image_size) && is_numeric($image_size)) :
		
			$size[0] = $image_size;
			 
			$size[1] = '9999';
			
		else :
		
			$size = $image_size;
			 
		endif;
		
		$thumb = wp_get_attachment_image_src($attachment_id, $size);
		
		if (!$thumb) return false;
		
		if ($thumb[3] == false) :
		
			if (!is_array($size)) :
			
				$image_size = self::get_image_size($size);
				
				if (false == $image_size) return $thumb;
				
				$size = array($image_size['width'], (true == $image_size['crop']) ? $image_size['height'] : '9999');
			
			endif;
		
			$src = explode(',', wp_get_attachment_image_srcset($attachment_id, $size));
			
			foreach ($src as $img) :
			
				$img_src = explode(' ', trim($img));
				
				$width = str_replace('w', '', $img_src[1]);
				
				if ($size[0] <= $width) :
				
					$image[$width] = $img_src[0];
					
				endif;
			
			endforeach;
			
			if (isset($image)) :
			
				ksort($image);
			
				$thumb[0] = current($image);
				
			endif;
		
		endif;
		
		return $thumb;
		
	}
	
	// Check whether url has status 200 and is image (not in use at the moment)
	 
	public static function check_url($url, $type = false) {
		
		$return = get_headers($url, 1);
		
		switch ($type) :
		
			case 'url' :
			
				if (strstr($return[0], '200')) return true;
			
			break;
			
			case 'image' :
			
				if (strstr($return['Content-Type'], 'image')) return true;
			
			break;
			
			default : 
			
				if (strstr($return[0], '200') && strstr($return['Content-Type'], 'image')) : 
				
					return true;
					
				else : 
				
					return false;
					
				endif;
		
		endswitch;
		
	}
	
}

?>