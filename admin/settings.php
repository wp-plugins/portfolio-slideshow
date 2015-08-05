<?php

namespace Portfolio_Slideshow;

/**
 * Markup for the Portfolio Slideshow settings page and tabs.
 *
 * @since 1.9.9
 */
defined( 'ABSPATH' ) or die;

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_and_behavior'; ?>

<div class="wrap portfolio-slideshow-settings-wrap">
	
	<h2><?php _e( 'Portfolio Slideshow', 'portfolio-slideshow' ); ?></h2>
	
	<h2 class="nav-tab-wrapper">
		
		<?php foreach ( Settings::get_tabs() as $tab_slug => $tab_name ) : ?>
		
		<a href="<?php echo add_query_arg( 'tab', $tab_slug, remove_query_arg( 'settings-updated' ) ); ?>" class="nav-tab <?php echo $active_tab == $tab_slug ? 'nav-tab-active' : ''; ?>">
			<?php esc_html_e( $tab_name ); ?>
		</a>

		<?php endforeach; ?>
	</h2>

	<div id="tab_container">
		<form method="post" action="options.php">
		
		<?php
			
			switch ( $active_tab ) :

				case 'display_and_behavior' :
				
				settings_fields( 'portfolio_slideshow_options' ); ?>
				
				<h3><?php esc_html_e( 'Display Settings', 'portfolio-slideshow' ); ?></h3>
				<table class="form-table">
					<?php do_settings_fields( 'portfolio_slideshow', 'portfolio_slideshow_display' ); ?>
				</table>
	
				<h3><?php esc_html_e( 'Behavior Settings', 'portfolio-slideshow' ); ?></h3>
				<table class="form-table">
					<?php do_settings_fields( 'portfolio_slideshow', 'portfolio_slideshow_behavior' ); ?>
				</table>
				<?php break; ?>

			<?php case 'pager_and_navigation' : ?>

				<?php settings_fields( 'portfolio_slideshow_options' ); ?>

				<h3><?php esc_html_e( 'Navigation Settings', 'portfolio-slideshow' ); ?></h3>
				<table class="form-table">
					<?php do_settings_fields( 'portfolio_slideshow', 'portfolio_slideshow_navigation' ); ?>
				</table>

				<?php break; ?>

			<?php case 'documentation' : ?>
				<?php require_once 'documentation.php'; ?>
				<?php break; ?>

		<?php endswitch; ?>

		<?php if ( 'documentation' !== $active_tab ) : ?>
			<?php submit_button(); ?>
		<?php endif; ?>

		</form>
	</div><!-- #tab_container-->

</div><!-- .wrap -->