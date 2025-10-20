<footer id="footer" class="footer">
	<div class="container">

		<div class="footer__grid">
			<div class="footer__grid-column">
				<?php if (is_active_sidebar('footer-1')) { ?><?php dynamic_sidebar('footer-1'); ?><?php } ?>
			</div>
			<div class="footer__grid-column">
				<?php if (is_active_sidebar('footer-2')) { ?><?php dynamic_sidebar('footer-2'); ?><?php } ?>
			</div>
			<div class="footer__grid-column">
				<?php if (is_active_sidebar('footer-3')) { ?><?php dynamic_sidebar('footer-3'); ?><?php } ?>
			</div>
			<div class="footer__grid-column">
				<div class="widget widget-contacts">
					<div class="widget-title"><?php _e('Contact Us', 'lptheme'); ?></div>
				</div>
			</div>
		</div>
	</div>

	<div class="footer__bottom">
		<div class="container">
			<div class="grid">
				<div class="copyrights">© <?php echo date("Y"); ?> <?php bloginfo('name'); ?>.
					<?php _e('All rights reserved', 'lptheme'); ?>.</div>
			</div>
		</div>
	</div>

</footer>