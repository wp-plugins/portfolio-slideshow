<?php

namespace Portfolio_Slideshow;

defined( 'WPINC' ) or die;

class Shortcode {

	public static function do_shortcode( $atts ) {

		static $i = 0;

		$args = shortcode_atts( [
			'size'             => Plugin::get_option( 'size' ),
			'nowrap'           => Plugin::get_option( 'loop' ),
			'loop'             => Plugin::get_option( 'loop' ),
			'speed'            => Plugin::get_option( 'speed' ),
			'trans'            => Plugin::get_option( 'trans' ),
			'timeout'          => Plugin::get_option( 'timeout' ),
			'exclude_featured' => Plugin::get_option( 'exclude_featured' ),
			'autoplay'         => Plugin::get_option( 'autoplay' ),
			'pagerpos'         => Plugin::get_option( 'pagerpos' ),
			'navpos'           => Plugin::get_option( 'navpos' ),
			'showcaps'         => Plugin::get_option( 'showcaps' ),
			'showtitles'       => Plugin::get_option( 'showtitles' ),
			'showdesc'         => Plugin::get_option( 'showdesc' ),
			'click'            => Plugin::get_option( 'click' ),
			'fluid'            => Plugin::get_option( 'fluid' ),
			'thumbs'           => '',
			'slideheight'      => '',
			'id'               => '',
			'exclude'          => '',
			'include'          => ''
		], $atts, 'portfolio_slideshow' );
		
		wp_enqueue_style( 'ps-public-css' );
		wp_enqueue_script( 'ps-public-js' );

		$slideshow = new Slideshow( $args );
		
		return $slideshow->the_slideshow();
	}
}