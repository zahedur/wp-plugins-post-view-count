<?php
namespace Zr\PostViewCount\admin;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Zr_Post_View_Shortcode
 * Handles the display of shortcode in the admin panel's post list for the Post View Count plugin.
 */
class Zr_Post_View_Shortcode
{
    /**
     * Zr_Post_View_Shortcode constructor.
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
        // Add new "View Count Shortcode" column.
        add_filter('manage_posts_columns', [$this, 'add_shortcode_column']);

        // Manage "View Count Shortcode" column.
        add_action('manage_posts_custom_column', [$this, 'manage_shortcode_column'], 10, 2);

    }

    /**
     * Adds the shortcode column to the posts list.
     *
     * @param array $columns An array of column names.
     * @return array Modified array of column names.
     */
    public function add_shortcode_column($columns) {
        $columns['zr_view_count_shortcode'] = __('View Count Shortcode', zr_text_domain());
        return $columns;
    }


    /**
     * Populates the shortcode column with the appropriate shortcode.
     *
     * @param string $column The name of the column.
     * @param int $post_id The ID of the current post.
     */
    public function manage_shortcode_column($column, $post_id) {
        if ($column == 'zr_view_count_shortcode') {
            $shortcode = '[zr-post-view-count post_id="' . $post_id . '"]';
            echo esc_html($shortcode);
        }
    }
    
}