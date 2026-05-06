<?php if (! defined('LP_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * SEO columns in admin posts/pages list (lp_meta_title, lp_meta_description)
 * ------------------------------------------------------------------------------------------------
 */

if (! class_exists('LP_SEO_Columns')) :

class LP_SEO_Columns
{
    public function __construct()
    {
        $post_types = $this->get_post_types();

        foreach ($post_types as $post_type) {
            add_filter("manage_{$post_type}_posts_columns",       [$this, 'add_columns']);
            add_action("manage_{$post_type}_posts_custom_column", [$this, 'render_column'], 10, 2);
            add_filter("manage_edit-{$post_type}_sortable_columns", [$this, 'sortable_columns']);
        }

        add_action('admin_head', [$this, 'column_styles']);
    }

    private function get_post_types()
    {
        $types = get_post_types(['public' => true], 'names');
        return array_values($types);
    }

    public function add_columns($columns)
    {
        $new = [];

        foreach ($columns as $key => $label) {
            $new[$key] = $label;
            if ($key === 'title') {
                $new['lp_seo_title']       = __('SEO Title', 'lptheme');
                $new['lp_seo_description'] = __('SEO Description', 'lptheme');
            }
        }

        return $new;
    }

    private function chip($len, $good_min, $good_max, $warn_min, $warn_max)
    {
        if ($len >= $good_min && $len <= $good_max) {
            $bg = '#d1fae5'; $color = '#065f46';
        } elseif ($len < $warn_min || $len > $warn_max) {
            $bg = '#fee2e2'; $color = '#991b1b';
        } else {
            $bg = '#fef3c7'; $color = '#92400e';
        }
        return '<span class="lp-seo-chip" style="background:' . $bg . ';color:' . $color . '">' . $len . '</span>';
    }

    public function render_column($column, $post_id)
    {
        if ($column === 'lp_seo_title') {
            $value = get_post_meta($post_id, 'lp_meta_title', true);
            if ($value) {
                $len = mb_strlen($value);
                echo $this->chip($len, 50, 60, 30, 70);
                echo '<span class="lp-seo-text">' . esc_html($value) . '</span>';
            } else {
                echo '<span style="color:#ccc">—</span>';
            }
        }

        if ($column === 'lp_seo_description') {
            $value = get_post_meta($post_id, 'lp_meta_description', true);
            if ($value) {
                $len     = mb_strlen($value);
                $preview = $len > 100 ? mb_substr($value, 0, 100) . '…' : $value;
                echo $this->chip($len, 120, 160, 70, 180);
                echo '<span class="lp-seo-text">' . esc_html($preview) . '</span>';
            } else {
                echo '<span style="color:#ccc">—</span>';
            }
        }
    }

    public function sortable_columns($sortable)
    {
        $sortable['lp_seo_title']       = 'lp_meta_title';
        $sortable['lp_seo_description'] = 'lp_meta_description';
        return $sortable;
    }

    public function column_styles()
    {
        $screen = get_current_screen();
        if (!$screen || $screen->base !== 'edit') return;
        ?>
        <style>
            .column-lp_seo_title       { width: 18%; }
            .column-lp_seo_description { width: 24%; }
            .lp-seo-chip {
                display: inline-block;
                font-size: 11px;
                font-weight: 600;
                line-height: 1;
                padding: 2px 6px;
                border-radius: 10px;
                margin-right: 5px;
                vertical-align: middle;
                white-space: nowrap;
            }
            .lp-seo-text {
                font-size: 12px;
                line-height: 1.4;
                vertical-align: middle;
                color: inherit;
            }
        </style>
        <?php
    }
}

new LP_SEO_Columns();

endif;
