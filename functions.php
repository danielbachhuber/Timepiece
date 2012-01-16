<?php
define( 'TIMEPIECE_VERSION', '0.0' );

class Timepiece
{
	
	function __construct() {

		// Set the default width for content
		if ( !isset( $content_width ) )
			$content_width = 584;
		
		add_action( 'after_setup_theme', array( $this, 'action_after_setup_theme' ) );

		// Include our Timepiece special formats in rendering post_class()
		add_filter( 'post_class', array( $this, 'filter_post_class' ), 10, 3 );

		// Enqueue our CSS and Javascript
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );

	}

	/**
	 * Setting up the different features of our theme
	 */
	function action_after_setup_theme() {

		// Add support for featured images
		add_theme_support( 'post-thumbnails' );

		// Add default posts and comments RSS feed links to <head>.
		add_theme_support( 'automatic-feed-links' );

		// Add support for a variety of post formats
		add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image', 'video' ) );
		
	}

	/**
	 * Enqueue our CSS and Javascript
	 */
	function enqueue_scripts_and_styles() {
		
		wp_enqueue_style( 'timepiece', get_stylesheet_uri(), array(), TIMEPIECE_VERSION );
	}

	/**
	 * Include our Timepiece special formats in rendering post_class()
	 *
	 * @since ???
	 *
	 * @return array $classes Our modified post classes
	 */
	function filter_post_class( $classes, $class, $post_id ) {

		$classes[] = 'timepiece-format-' . timepiece_get_format();
		return $classes;
	}

}

global $timepiece;
$timepiece = new Timepiece();


/**
 * Get the format of a post in the loop
 * Allows us to use a select list of categories as post formats too
 *
 * @since ???
 *
 * @return string|
 */
function timepiece_get_format() {
	global $post;
	
	$supported_categories = array(
			'aside' => 'aside',
			'asides' => 'aside',
			'status' => 'status',
			'statuses' => 'status',
			'link' => 'link',
			'links' => 'link',
			'gallery' => 'gallery',
			'galleries' => 'gallery',
			'quote' => 'quote',
			'quotes' => 'quote',
			'photo' => 'image',
			'photos' => 'image',
			'image' => 'image',
			'images' => 'image',
			'video' => 'video',
			'videos' => 'video',
			'post' => 'standard',
			'posts' => 'standard',
		);
	
	// First check to see if there's a matching category
	$post_categories = get_the_category( $post->ID );
	foreach( $post_categories as $category ) {
		if ( array_key_exists( $category->slug, $supported_categories ) )
			return $supported_categories[$category->slug];
	}

	// Otherwise, let's just see what post format we have saved
	$post_format = get_post_format();
	if ( !$post_format )
		$post_format = 'standard';
	return $post_format;
}