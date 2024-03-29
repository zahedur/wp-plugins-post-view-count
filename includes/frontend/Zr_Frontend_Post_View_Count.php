<?php
namespace Zr\PostViewCount\frontend;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Zr_Frontend_Post_View_Count
 */
class Zr_Frontend_Post_View_Count
{
    /**
     * Zr_Frontend_Post_View_Count constructor.
     * Initializes the class by hooking into WordPress actions.
     */
    public function __construct()
    {
        // Add shortcode registration action hook
        add_action('init', [$this, 'register_shortcode']);
    }

    /**
     * Registers the shortcode for displaying post view count.
     */
    public function register_shortcode()
    {
        // Create "zr-post-view-count" shortcode.
        add_shortcode('zr-post-view-count', [$this, 'zr_post_view_count']);
    }

    /**
     * Callback function for the [zr-post-view-count] shortcode.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML content for displaying post view count.
     */
    public function zr_post_view_count($atts): string
    {
        // Set default post_id if $atts is not given.
        $zr_atts = shortcode_atts( [
            'post_id' => null,
        ], $atts );

        // Get post total views.
        $view_count = get_post_meta($zr_atts['post_id'], 'zr_view_count', true);

        // If views are null then set 0.
        $view_count = $view_count ? $view_count : 0;

        // Shortcode output data
        $html_content = '';
        if ($zr_atts['post_id'] == null) {
            $html_content .= '<div class="'.esc_attr('zr-post-view-count post-id-missing').'">';
            $html_content .= '<p class="'.esc_attr('zr-post-view-count-number').'"><strong>'. esc_html__('Post ID is required to see total views.', zr_text_domain()) .'</strong></p>';
            $html_content .= '<p class="'.esc_attr('zr-post-view-count-number example').'"><strong>'. esc_html__('Example: [zr-post-view-count post_id="' . 1 . '"]', zr_text_domain()) .'</strong></p>';
            $html_content .= '</div>';
        }else {
            $html_content .= '<div class="'.esc_attr('zr-post-view-count').'">';
            $html_content .= '<h3 class="'.esc_attr('zr-post-view-count-heading').'">'. esc_html__('Post Views', zr_text_domain()) .'</h3>';
            $html_content .= '<h5 class="'.esc_attr('zr-post-view-count-sub-heading').'">'. esc_html__('Total Views', zr_text_domain()) .'</h5>';
            $html_content .= '<p class="'.esc_attr('zr-post-view-count-number').'"><strong>'. esc_html__($view_count, zr_text_domain()) .'</strong></p>';
            $html_content .= '</div>';
        }

        // Return the shortcode output data
        return $html_content;
    }

}