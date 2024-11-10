<?php if ( ! defined('LP_THEME_DIR')) exit('No direct script access allowed');
//Template Name: Testimonials
get_header();

$post_per_page = 12;

if (get_query_var('paged')) {
  $paged = get_query_var('paged');
} elseif (get_query_var('page')) { 
  $paged = get_query_var('page');
} else {
  $paged = 1;
}

if (!$post_per_page) {
  $post_per_page = get_option('posts_per_page');
}

$post_args = array(
  'posts_per_page' => $post_per_page,
  'orderby'        => 'date',
  'paged'          => $paged,
  'order'          => 'DESC',
  'post_type'      => 'testimonials',
  'post_status'    => 'publish'
);

$query = new WP_Query( $post_args );
if(is_page()) {
  $max_num_pages = $query -> max_num_pages;
} else {
  global $wp_query;
  $query = $wp_query;
  $max_num_pages = false;
}
?>

<style>
/* testimonials */
.testimonials-form {
  --bg: #e3e4e8;
  --fg: #17181c;
  --yellow: #f4a825;
  --yellow-t: rgba(244, 168, 37, 0);
  --bezier: cubic-bezier(0.42,0,0.58,1);
  --trans-dur: 0.3s;
}
.testimonials-grid{
  column-count: 3;
  column-gap: 1.6rem;
}
.testimonials__item{display:flex;flex-direction:column;gap:1.6rem;margin-bottom: 1.6rem;background-color:var(--gray-bg);break-inside: avoid;}
.testimonials__item-text img {max-width: 300px;margin-block: 1.6rem;
}
.testimonials__item-header{display:flex;gap:1rem;align-items:center}
.testimonials__item-name{font-weight:700}
.testimonials__item-image{background:white;border-radius:50%;width:5em;height:5em;display:grid;align-items:center;justify-content:center}
.testimonials__item-image svg{max-width:1.6em;margin:auto}
.testimonials__item-image img{border-radius: 50%;}
.testimonials__item-text{padding:0;border:0;margin:0;quotes:'\201c''\201d'}
.testimonials__item-text::after,.testimonials__item-text::before{font-size:2rem;font-weight:700;line-height:1}
.testimonials__item-text::before{content:open-quote}
.testimonials__item-date{color:var(--gray-2);font-size:1rem}
.testimonials__item-response{display: grid;gap: 0.5rem;padding: 1rem;border: 1px solid var(--border-2);font-size: .9rem;color: var(--gray);}
.file-attachment {
  background-color: var(--gray-bg);
    padding: 1rem 1.6rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 30px;
    cursor: pointer;
}
.file-attachment input {
    display: none;
}
.fileName {
    color: var(--gray-2);
    display: inline-block;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
}
.file-attachment svg {
    width: 22px;
    height: 22px;
}

/* rating */
.rating{
  border: none;
  padding: 0;
}
.rating__display {
  font-size: 1em;
  font-weight: 500;
  min-height: 1.25em;
  position: absolute;
  top: 100%;
  width: 100%;
  text-align: center;
}
.rating__stars {
  display: flex;
  padding-bottom: 0.375em;
  position: relative;
}
.rating__star {
  display: block;
  overflow: visible;
  pointer-events: none;
  width: 2.5em;
  height: 2.5em;
}
.rating__star-ring, .rating__star-fill, .rating__star-line, .rating__star-stroke {
  animation-duration: 1s;
  animation-timing-function: ease-in-out;
  animation-fill-mode: forwards;
}
.rating__star-ring, .rating__star-fill, .rating__star-line {
  stroke: var(--yellow);
}
.rating__star-fill {
  fill: var(--yellow);
  transform: scale(0);
  transition: fill var(--trans-dur) var(--bezier), transform var(--trans-dur) var(--bezier);
}
.rating__star-line {
  stroke-dasharray: 12 13;
  stroke-dashoffset: -13;
}
.rating__star-stroke {
  stroke: #c7cad1;
  transition: stroke var(--trans-dur);
}
.rating__label {
  cursor: pointer;
  padding: 0.125em;
}
.rating__label--delay1 .rating__star-ring, .rating__label--delay1 .rating__star-fill, .rating__label--delay1 .rating__star-line, .rating__label--delay1 .rating__star-stroke {
  animation-delay: 0.05s;
}
.rating__label--delay2 .rating__star-ring, .rating__label--delay2 .rating__star-fill, .rating__label--delay2 .rating__star-line, .rating__label--delay2 .rating__star-stroke {
  animation-delay: 0.1s;
}
.rating__label--delay3 .rating__star-ring, .rating__label--delay3 .rating__star-fill, .rating__label--delay3 .rating__star-line, .rating__label--delay3 .rating__star-stroke {
  animation-delay: 0.15s;
}
.rating__label--delay4 .rating__star-ring, .rating__label--delay4 .rating__star-fill, .rating__label--delay4 .rating__star-line, .rating__label--delay4 .rating__star-stroke {
  animation-delay: 0.2s;
}
.rating__input {
  position: absolute;
  -webkit-appearance: none;
  appearance: none;
}
.rating__input:hover ~ [data-rating]:not([hidden]) {
  display: none;
}
.rating__input-1:hover ~ [data-rating="1"][hidden], .rating__input-2:hover ~ [data-rating="2"][hidden], .rating__input-3:hover ~ [data-rating="3"][hidden], .rating__input-4:hover ~ [data-rating="4"][hidden], .rating__input-5:hover ~ [data-rating="5"][hidden], .rating__input:checked:hover ~ [data-rating]:not([hidden]) {
  display: block;
}
.rating__input-1:hover ~ .rating__label:first-of-type .rating__star-stroke, .rating__input-2:hover ~ .rating__label:nth-of-type(-n + 2) .rating__star-stroke, .rating__input-3:hover ~ .rating__label:nth-of-type(-n + 3) .rating__star-stroke, .rating__input-4:hover ~ .rating__label:nth-of-type(-n + 4) .rating__star-stroke, .rating__input-5:hover ~ .rating__label:nth-of-type(-n + 5) .rating__star-stroke {
  stroke: var(--yellow);
  transform: scale(1);
}
.rating__input-1:checked ~ .rating__label:first-of-type .rating__star-ring, .rating__input-2:checked ~ .rating__label:nth-of-type(-n + 2) .rating__star-ring, .rating__input-3:checked ~ .rating__label:nth-of-type(-n + 3) .rating__star-ring, .rating__input-4:checked ~ .rating__label:nth-of-type(-n + 4) .rating__star-ring, .rating__input-5:checked ~ .rating__label:nth-of-type(-n + 5) .rating__star-ring {
  animation-name: starRing;
}
.rating__input-1:checked ~ .rating__label:first-of-type .rating__star-stroke, .rating__input-2:checked ~ .rating__label:nth-of-type(-n + 2) .rating__star-stroke, .rating__input-3:checked ~ .rating__label:nth-of-type(-n + 3) .rating__star-stroke, .rating__input-4:checked ~ .rating__label:nth-of-type(-n + 4) .rating__star-stroke, .rating__input-5:checked ~ .rating__label:nth-of-type(-n + 5) .rating__star-stroke {
  animation-name: starStroke;
}
.rating__input-1:checked ~ .rating__label:first-of-type .rating__star-line, .rating__input-2:checked ~ .rating__label:nth-of-type(-n + 2) .rating__star-line, .rating__input-3:checked ~ .rating__label:nth-of-type(-n + 3) .rating__star-line, .rating__input-4:checked ~ .rating__label:nth-of-type(-n + 4) .rating__star-line, .rating__input-5:checked ~ .rating__label:nth-of-type(-n + 5) .rating__star-line {
  animation-name: starLine;
}
.rating__input-1:checked ~ .rating__label:first-of-type .rating__star-fill, .rating__input-2:checked ~ .rating__label:nth-of-type(-n + 2) .rating__star-fill, .rating__input-3:checked ~ .rating__label:nth-of-type(-n + 3) .rating__star-fill, .rating__input-4:checked ~ .rating__label:nth-of-type(-n + 4) .rating__star-fill, .rating__input-5:checked ~ .rating__label:nth-of-type(-n + 5) .rating__star-fill {
  animation-name: starFill;
}
.rating__input-1:not(:checked):hover ~ .rating__label:first-of-type .rating__star-fill, .rating__input-2:not(:checked):hover ~ .rating__label:nth-of-type(2) .rating__star-fill, .rating__input-3:not(:checked):hover ~ .rating__label:nth-of-type(3) .rating__star-fill, .rating__input-4:not(:checked):hover ~ .rating__label:nth-of-type(4) .rating__star-fill, .rating__input-5:not(:checked):hover ~ .rating__label:nth-of-type(5) .rating__star-fill {
  fill: var(--yellow-t);
}
.rating__sr {
  clip: rect(1px, 1px, 1px, 1px);
  overflow: hidden;
  position: absolute;
  width: 1px;
  height: 1px;
}

@media (prefers-color-scheme: dark) {
  :root {
    --bg: #17181c;
    --fg: #e3e4e8;
  }

  .rating {
    margin: auto;
  }
  .rating__star-stroke {
    stroke: #454954;
  }
}
@keyframes starRing {
  from, 20% {
    animation-timing-function: ease-in;
    opacity: 1;
    r: 8px;
    stroke-width: 16px;
    transform: scale(0);
  }
  35% {
    animation-timing-function: ease-out;
    opacity: 0.5;
    r: 8px;
    stroke-width: 16px;
    transform: scale(1);
  }
  50%, to {
    opacity: 0;
    r: 16px;
    stroke-width: 0;
    transform: scale(1);
  }
}
@keyframes starFill {
  from, 40% {
    animation-timing-function: ease-out;
    transform: scale(0);
  }
  60% {
    animation-timing-function: ease-in-out;
    transform: scale(1.2);
  }
  80% {
    transform: scale(0.9);
  }
  to {
    transform: scale(1);
  }
}
@keyframes starStroke {
  from {
    transform: scale(1);
  }
  20%, to {
    transform: scale(0);
  }
}
@keyframes starLine {
  from, 40% {
    animation-timing-function: ease-out;
    stroke-dasharray: 1 23;
    stroke-dashoffset: 1;
  }
  60%, to {
    stroke-dasharray: 12 13;
    stroke-dashoffset: -13;
  }
}

@media (max-width: 768px) {.testimonials-grid{column-count: 2;}}
@media (max-width: 468px) {.testimonials-grid{column-count: 1;}}
</style>

<main role="main" class="main-content">
  <div class="page-section">
    <div class="container">
      <?php lp_breadcrumbs(); ?>
      <div class="title-wrapper">
          <div class="section-title">
              <h1><?php the_title()?></h1>
          </div>
      </div>
    </div>
  </div>

  <div class="section-testimonials">
    <div class="container">
      <div class="testimonials-grid">
      <?php if($query -> have_posts()): while ($query -> have_posts()) : $query -> the_post(); ?>
          <?php get_template_part('/template-parts/testimonial-item'); ?>
          <?php endwhile; wp_reset_postdata(); else:
        get_template_part('templates/content', 'none');
        endif; ?>
      </div>
      <?php lp_paging_nav($max_num_pages); ?>
    </div>
  </div>

  <div class="section bg-white">
    <div class="container">
    
      <div class="heading-holder center">
          <div class="section-heading"><?php echo translate_pll('Оставить отзыв', 'Залишити відгук'); ?></div>
        </div>

      <div class="grid two-cols">
        <div class="col">

          <?php while ( have_posts() ) : the_post(); ?>
              <div class="second-subheading">
                <?php the_content(); ?>
              </div>
            <?php endwhile; ?>
        </div>

        <div class="col">
          <div class="testimonials-form">
            <?php echo do_shortcode('[testimonials-form]');?>
          </div>
        </div>

      </div>
    
    </div>
  </div>
</main>

<script>
  window.addEventListener("DOMContentLoaded",() => {
	const starRating = new StarRating("form");
});

class StarRating {
	constructor(qs) {
		this.ratings = [
			{id: 1, name: "Terrible"},
			{id: 2, name: "Bad"},
			{id: 3, name: "OK"},
			{id: 4, name: "Good"},
			{id: 5, name: "Excellent"}
		];
		this.rating = null;
		this.el = document.querySelector(qs);

		this.init();
	}
	init() {
		this.el?.addEventListener("change",this.updateRating.bind(this));
		try {
			this.el?.reset();
		} catch (err) {
			console.error("Element isn’t a form.");
		}
	}
	updateRating(e) {
		Array.from(this.el.querySelectorAll(`[for*="rating"]`)).forEach(el => {
			el.className = "rating__label";
		});

		const ratingObject = this.ratings.find(r => r.id === +e.target.value);
		const prevRatingID = this.rating?.id || 0;

		let delay = 0;
		this.rating = ratingObject;
		this.ratings.forEach(rating => {
			const { id } = rating;

			// add the delays
			const ratingLabel = this.el.querySelector(`[for="rating-${id}"]`);

			if (id > prevRatingID + 1 && id <= this.rating.id) {
				++delay;
				ratingLabel.classList.add(`rating__label--delay${delay}`);
			}

			// hide ratings to not read, show the one to read
			const ratingTextEl = this.el.querySelector(`[data-rating="${id}"]`);

			if (this.rating.id !== id)
				ratingTextEl.setAttribute("hidden",true);
			else
				ratingTextEl.removeAttribute("hidden");
		});
	}
}
</script>
<?php get_footer(); 
