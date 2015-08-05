<?php
/**
 * The actual slides container, and the slides themselves.
 *
 * @var int $key
 * @var int $slides_count
 * @var string $maybe_min_height A pixel value for CSS min-height on the slides container *if* this slideshow has a slideheight set for it.
 * @var array $slides
 * @var string $click
 * @var string $placeholder 
 *
 * @since 1.9.9
 */
?>
<div id="<?php esc_attr_e( sprintf( 'portfolio-slideshow%s', $key ) ); ?>" class="portfolio-slideshow" style="<?php esc_attr_e( $maybe_min_height ); ?>">

	<?php $count = 0; ?>

	<?php foreach ( $this->slides as $pos => $slide ) : ?>

			<?php ++$count; ?>
			
			<?php $alttext = sprintf( __( 'Slide %s', 'portfolio-slideshow' ), absint( $pos + 1 ) ); ?>
	
			<div class="slideshow-next slideshow-content <?php 0 != $pos ? esc_attr_e( 'not-first' ) : '' ?>">

				<?php
					switch ( $click ) :
	
						case 'openurl' :
							$imagelink = get_post_meta( $slide['image'], '_ps_image_link', true );
	
							if ( $imagelink ) {
								$imagelink = $imagelink . '" target="' . Plugin::get_option( 'click_target' );
							} else {
								$imagelink = 'javascript: void(0);" class="slideshow-next';
							}
							break;
	
						default :
							$imagelink = 'javascript: void(0);" class="slideshow-next';
							break;
	
					endswitch;
				?>
	
				<?php if ( 'false' == $loop && $count - 1 != $pos || 'false' != $loop ) : ?>
					<a href="<?php esc_attr_e( $imagelink ); ?>">
				<?php endif; ?>
	
				<?php $img = wp_get_attachment_image_src( $slide['image'], $size ); ?>
	
				<?php 
					printf( '<img class="psp-active" data-img="%s" src="%s" height="%s" width="%s" alt="%s">',
						esc_attr( $img[0] ),
						esc_attr( $pos < 1 ? $img[0] : $placeholder ),
						esc_attr( $img[2] ),
						esc_attr( $img[1] ),
						esc_attr( sprintf( _x( 'Slide %s', 'Alt text for slide images, where %s is the current slide number as an integer.', 'portfolio-slideshow' ), absint( $pos + 1 ) ) )
					);
				?>

				<?php if ( 'false' == $loop && $count - 1 != $pos || 'false' != $loop ) : ?>
					</a>
				<?php endif; ?>

			</div>

	<?php endforeach; ?>
</div>