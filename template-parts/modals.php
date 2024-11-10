<div id="contact-modal" class="lp-modal">
	<div class="modal-content">
		<div class="modal-heading">
			<div class="modal-title"><?php _e( 'Contact Us', 'lptheme' ); ?></div>
			<button class="modal-close"></button>
		</div>
		<div class="modal-body">
			<div class="form-container">
				<?php get_template_part( 'template-parts/forms/form', 'contact' ); ?>
			</div>
		</div>
	</div>
</div>

<div id="callback-modal" class="lp-modal">
	<div class="modal-content">
		<div class="modal-heading">
			<div class="modal-title"><?php _e( 'Callback', 'lptheme' ); ?></div>
			<button class="modal-close"></button>
		</div>
		<div class="modal-body">
			<div class="form-container">
				<?php get_template_part( 'template-parts/forms/form', 'callback' ); ?>
			</div>
		</div>
	</div>
</div>