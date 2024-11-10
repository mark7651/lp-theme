<form id="form-testimonials" method="POST" action="<?php echo esc_url( get_permalink() ); ?>" class="lp-form form-testimonials"
  novalidate="novalidate" enctype="multipart/form-data">

  <div class="input-group">
    <input type="text" id="Name" name="client-name" value="" autocomplete="off"
      placeholder="<?php _e( 'Your name', 'lptheme' ); ?>" required="required">
  </div>

  <div class="input-group">
    <input type="email" id="Email" name="client-email" value="" autocomplete="off"
      placeholder="<?php _e( 'Email', 'lptheme' ); ?>" required="required">
  </div>

  <div class="input-group">
    <textarea name="client-message" id="Message" cols="30" rows="5" placeholder="<?php _e( 'Your Testimonial', 'lptheme' ); ?>"
      autocomplete="off" required="required"></textarea>
  </div>

  <label for="fileAttachment" class="file-attachment">
        <span><?php _e( 'Ваше фото', 'lptheme' ); ?></span>
        <input type="file" id="fileAttachment" name="attachment">
        <span id="fileName" class="filename"></span>
        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path id="plus" d="M12.9949 24V2M2 12.9956H24" stroke="#8C88BE" stroke-width="3" stroke-linecap="round" />
    </svg>
  </label>

  <div class="input-group grid">
    <fieldset class="rating">
    <div class="rating__stars">
		<input id="rating-1" class="rating__input rating__input-1" type="radio" name="rating" value="1">
		<input id="rating-2" class="rating__input rating__input-2" type="radio" name="rating" value="2">
		<input id="rating-3" class="rating__input rating__input-3" type="radio" name="rating" value="3">
		<input id="rating-4" class="rating__input rating__input-4" type="radio" name="rating" value="4">
		<input id="rating-5" class="rating__input rating__input-5" type="radio" name="rating" value="5">
		<label class="rating__label" for="rating-1">
			<svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
				<g transform="translate(16,16)">
					<circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8" transform="scale(0)" />
				</g>
				<g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<g transform="translate(16,16) rotate(180)">
						<polygon class="rating__star-stroke" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="none" />
						<polygon class="rating__star-fill" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="#000" />
					</g>
					<g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
						<polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
					</g>
				</g>
			</svg>
			<span class="rating__sr">1 star—Terrible</span>
		</label>
		<label class="rating__label" for="rating-2">
			<svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
				<g transform="translate(16,16)">
					<circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8" transform="scale(0)" />
				</g>
				<g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<g transform="translate(16,16) rotate(180)">
						<polygon class="rating__star-stroke" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="none" />
						<polygon class="rating__star-fill" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="#000" />
					</g>
					<g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
						<polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
					</g>
				</g>
			</svg>
			<span class="rating__sr">2 stars—Bad</span>
		</label>
		<label class="rating__label" for="rating-3">
			<svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
				<g transform="translate(16,16)">
					<circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8" transform="scale(0)" />
				</g>
				<g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<g transform="translate(16,16) rotate(180)">
						<polygon class="rating__star-stroke" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="none" />
						<polygon class="rating__star-fill" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="#000" />
					</g>
					<g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
						<polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
					</g>
				</g>
			</svg>
			<span class="rating__sr">3 stars—OK</span>
		</label>
		<label class="rating__label" for="rating-4">
			<svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
				<g transform="translate(16,16)">
					<circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8" transform="scale(0)" />
				</g>
				<g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<g transform="translate(16,16) rotate(180)">
						<polygon class="rating__star-stroke" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="none" />
						<polygon class="rating__star-fill" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="#000" />
					</g>
					<g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
						<polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
					</g>
				</g>
			</svg>
			<span class="rating__sr">4 stars—Good</span>
		</label>
		<label class="rating__label" for="rating-5">
			<svg class="rating__star" width="32" height="32" viewBox="0 0 32 32" aria-hidden="true">
				<g transform="translate(16,16)">
					<circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16" r="8" transform="scale(0)" />
				</g>
				<g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<g transform="translate(16,16) rotate(180)">
						<polygon class="rating__star-stroke" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="none" />
						<polygon class="rating__star-fill" points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07" fill="#000" />
					</g>
					<g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
						<polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16" />
						<polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16" />
					</g>
				</g>
			</svg>
			<span class="rating__sr">5 stars—Excellent</span>
		</label>
		<p class="rating__display" data-rating="1" hidden>Terrible</p>
		<p class="rating__display" data-rating="2" hidden>Bad</p>
		<p class="rating__display" data-rating="3" hidden>OK</p>
		<p class="rating__display" data-rating="4" hidden>Good</p>
		<p class="rating__display" data-rating="5" hidden>Excellent</p>
	</div>
  </fieldset>
  </div>

  <button class="btn btn-primary submit-btn" name="submit" type="submit">
  <?php _e( 'Send Testimonial', 'lptheme' ); ?>
  </button>

  <input type="hidden" name="form_name" value="<?php _e( 'Testimonials form', 'lptheme' ); ?>">
  <div class="notification-box"></div>

</form>
