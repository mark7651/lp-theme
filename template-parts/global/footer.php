<footer id="main-footer">
	<div class="container">
	<div class="async-template-part" data-template-part="footer"></div>
		<div class="footer-row">
			<div class="footer-column">
				<?php if ( is_active_sidebar( 'footer-1' ) ) { ?><?php dynamic_sidebar('footer-1'); ?><?php } ?>
			</div>
			<div class="footer-column">
				<?php if ( is_active_sidebar( 'footer-2' ) ) { ?><?php dynamic_sidebar('footer-2'); ?><?php } ?>
			</div>
			<div class="footer-column">
				<?php if ( is_active_sidebar( 'footer-3' ) ) { ?><?php dynamic_sidebar('footer-3'); ?><?php } ?>
			</div>
			<div class="footer-column">
				<div class="widget widget-contacts">
					<div class="widget-title"><?php _e( 'Contact Us', 'lptheme' ); ?></div>

					<div class="contact-list">

					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="footer-bottom">
		<div class="container">
			<div class="grid">
				<div class="col">
					<div class="copyrights">© <?php echo date("Y"); ?> <?php bloginfo( 'name' ); ?>.
						<?php _e( 'All rights reserved', 'lptheme' ); ?>.</div>
				</div>
			</div>
		</div>
	</div>

</footer>