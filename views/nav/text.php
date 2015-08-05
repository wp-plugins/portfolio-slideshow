<?php
/**
 * The text navigation style.
 *
 * @var int $key This slideshow's key.
 *
 * @since 2.0.0
 */
?>
<div id="<?php esc_attr_e( sprintf( 'slideshow-nav%s', $key ) ); ?>" class="slideshow-nav">
	<a class="pause" style="display:none" href="javascript:void(0);">Pause</a>
	<a class="play" href="javascript:void(0);">Play</a>
	<a class="restart" style="display:none" href="javascript: void(0);">Play</a>
	<a class="slideshow-prev" href="javascript: void(0);">Prev</a>
	<span class="sep">|</span>
	<a class="slideshow-next" href="javascript: void(0);">Next</a>
	<span class="<?php esc_attr_e( sprintf( 'slideshow-info%s', $key ) ); ?> slideshow-info"></span>
</div><!-- .slideshow-nav -->