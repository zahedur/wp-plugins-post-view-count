<?php

namespace Zr\PostViewCount\admin;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class Zr_Admin_Post_View_Count
 *
 * This class manages the display and sorting of post view counts in the WordPress admin panel.
 */
class Zr_Admin_Post_View_Count
{
    /**
     * Zr_Admin_Post_View_Count meta key
     */
    public string $meta_key = 'zr_view_count';

    /**
     * Zr_Admin_Post_View_Count constructor.
     *
     * Registers necessary hooks for initializing the functionality.
     */
    public function __construct()
    {
        add_action('init', [$this, 'init']);
    }

    /**
     * Initialization function.
     */
    public function init()
    {
        // Add new "view count" column.
        add_filter('manage_posts_columns', [$this, 'add_view_count_column']);

        // Manage "view count" column.
        add_action('manage_posts_custom_column', [$this, 'manage_view_count_column'], 10, 2);

        // Sortable of count view column.
        add_filter('manage_edit-post_sortable_columns', [$this, 'add_sortable_column']);

        // Apply custom column sorting.
        add_action('pre_get_posts', [$this, 'custom_column_sorting']);

        // Count every post view.
        add_action('wp_head', [$this, 'count_view']);


    }

    /**
     * Add the 'View Count' column to the posts list table.
     *
     * @param array $columns Existing columns in the posts list table.
     * @return array Columns with the added 'View Count' column.
     */
    public function add_view_count_column($columns)
    {
        $columns[$this->meta_key] = __('View Count', zr_text_domain());
        return $columns;
    }

    /**
     * Populate the 'View Count' column with the post view count data.
     *
     * @param string $column Name of the current column.
     * @param int $post_id ID of the current post.
     */
    public function manage_view_count_column($column, $post_id)
    {
        if ($column == $this->meta_key) {
            $view_count = get_post_meta($post_id, $this->meta_key, true);
            $view_count = $view_count ? $view_count : 0;
            echo intval($view_count);
        }
    }

    /**
     * Make the 'View Count' column sortable.
     *
     * @param array $columns Existing sortable columns.
     * @return array Columns with the added sortable 'View Count' column.
     */
    public function add_sortable_column($columns)
    {
        $columns[$this->meta_key] = [$this->meta_key];
        return $columns;
    }

    /**
     * Handle sorting logic for the 'View Count' column.
     *
     * @param WP_Query $query The main WP_Query object.
     */
    public function custom_column_sorting($query)
    {
        if (!is_admin() || !$query->is_main_query()) {
            return;
        }

        $orderby = $query->get('orderby');
        $order = $query->get('order');

        if ($orderby == $this->meta_key) {

            //sorting query
            $meta_query = [
                [
                    'key' => $this->meta_key,
                    'compare' => 'NOT EXISTS',
                ],
                [
                    'key' => $this->meta_key,
                    'compare' => 'EXISTS',
                ],
                'relation' => 'OR',
            ];

            $query->set('meta_query', $meta_query);
            $query->set('orderby', 'meta_value_num');
            $query->set('order', $order);
        }
    }

    /**
     * Increment the view count for a post when viewed.
     */
    public function count_view()
    {
        if (is_single()) {
            $view_count = get_post_meta(get_the_ID(), $this->meta_key, true);
            $view_count = $view_count ? $view_count : 0;
            $view_count++;
            update_post_meta(get_the_ID(), $this->meta_key, $view_count);
        }
    }

}