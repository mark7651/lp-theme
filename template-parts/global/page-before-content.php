<?php
/**
 * Before Page Loop ( page.php )
 *
 * @package lptheme
 */
?>
<?php if (!is_front_page()):?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="titlebar">
                <h1><?php the_title()?></h1>
                <?php lptheme_breadcrumbs(); ?>
            </div>
        </div>
    </div>
</div>
<?php endif;?>