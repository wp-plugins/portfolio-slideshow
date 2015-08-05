<?php
/**
 * The "thumbs" pager style.
 *
 * @var int $key This slideshow's key.
 * @var array $slides The slides for this slideshow.
 *
 * @since 2.0.0
 */
?>
<div class="pscarousel">
	<div id="<?php esc_attr_e( sprintf( 'pager%s', $key ) ); ?>" class="pager items clearfix">
	<?php foreach ( $slides as $pos => $slide ) : ?>
		<?php echo wp_get_attachment_image( $slide['image'], 'thumbnail', false, false ); ?>
	<?php endforeach; ?>
	</div>
</div>