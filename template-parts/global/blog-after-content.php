<?php
/**
 * After Loop. ( page.php )
 *
 * @package lptheme
 */

$layout = get_field('main-layout', 'option');

if ($layout == 'left_sidebar'): ?>
    </div>
  </div>

<?php elseif ($layout == 'right_sidebar'): ?>
    </div>
    <?php get_sidebar(); ?>
  </div>

<?php else: ?>
  </div>
</div>

<?php endif; ?>
