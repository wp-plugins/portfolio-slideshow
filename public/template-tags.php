<?php

defined( 'WPINC' ) or die;

/**
 * @since 2.0.0
 *
 * @return string
 */
function portfolio_slideshow_get_slideshow_label_singular() {
	return apply_filters( 'portfolio_slideshow_slideshow_label_singular', __( 'Slideshow', 'portfolio-slideshow' ) );
}

/**
 * @since 2.0.0
 *
 * @return string
 */
function portfolio_slideshow_get_slideshow_label_plural() {
	return apply_filters( 'portfolio_slideshow_slideshow_label_plural', __( 'Slideshows', 'portfolio-slideshow' ) );
}

/**
 * @since 2.0.0
 *
 * @return string
 */
function portfolio_slideshow_get_slide_label_singular() {
	return apply_filters( 'portfolio_slideshow_slide_label_singular', __( 'Slide', 'portfolio-slideshow' ) );
}

/**
 * @since 2.0.0
 *
 * @return string
 */
function portfolio_slideshow_get_slide_label_plural() {
	return apply_filters( 'portfolio_slideshow_slide_label_plural', __( 'Slides', 'portfolio-slideshow' ) );
}

