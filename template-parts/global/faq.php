<?php if ( ! defined( 'ABSPATH' ) ) { die(); }

/**
 * ------------------------------------------------------------------------------------------------
 * FAQ list
 * ------------------------------------------------------------------------------------------------
 */

 if (have_rows('faq')) : ?>
      
    <ul class="accordeon">
      <?php while (have_rows('faq')): the_row(); ?>
      <li class="accordeon-item js-accordeon-item">
        <div class="accordeon-item__header">
          <div class="accordeon-item__icon"></div>
          <div class="accordeon-item__title"><?php the_sub_field('faq_question'); ?></div>
        </div>

        <div class="accordeon-item__description js-accordeon-description">
          <p><?php the_sub_field('faq_answer'); ?></p>
        </div>
      </li>
      <?php endwhile; ?>
    </ul>


  <?php global $schema;

    $schema = array(
    '@context'   => "https://schema.org",
    '@type'      => "FAQPage",
    'mainEntity' => array()
    );

    if ( have_rows('faq') ) {
        while ( have_rows('faq') ) : the_row();
          $questions = array(
            '@type'          => 'Question',
            'name'           => get_sub_field('faq_question'),
            'acceptedAnswer' => array(
            '@type' => "Answer",
            'text' => get_sub_field('faq_answer')
              ));
            array_push($schema['mainEntity'], $questions);
        endwhile;
      
    function lp_generate_faq_schema ($schema) {
      global $schema;
      echo '<script type="application/ld+json">'. json_encode($schema) .'</script>';
    }

    add_action( 'wp_footer', 'lp_generate_faq_schema', 100 );
  }

  ?>

<?php endif; ?> 