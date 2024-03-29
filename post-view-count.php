<?php
/*
 * Plugin Name:       Post View Count
 * Plugin URI:        https://zahedur.com
 * Description:       This plugin displays view counts exclusively in the admin panel post list, streamlining post popularity tracking.
 * Version:           1.0.0
 * Author:            Zahedur Rahman
 * Author URI:        https://zahedur.com
 * Text Domain:       zr-post-view-count
 * Domain Path:       /languages
 */

use Zr\PostViewCount\admin\Zr_Admin_Post_View_Count;
use Zr\PostViewCount\admin\Zr_Post_View_Shortcode;
use Zr\PostViewCount\frontend\Zr_Frontend_Post_View_Count;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Load composer autoload file
require_once __DIR__.'/vendor/autoload.php';

/**
 * Class Zr_Post_View_Count
 * Main class for the Post View Count plugin.
 */
class Zr_Post_View_Count {

    /**
     * Zr_Post_View_Count constructor.
     * Initializes the main components related to post view count.
     */
    public function __construct()
    {
        new Zr_Admin_Post_View_Count();
        new Zr_Frontend_Post_View_Count();
        new Zr_Post_View_Shortcode();

        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );

    }


    /**
     * Enqueue styles for the related posts.
     */
    public function enqueue_scripts()
    {
        //Add custom style
        wp_enqueue_style( 'zr-related-posts', plugins_url( 'assets/css/style.css', __FILE__ ) );
    }

}

// Instantiate the Zr_Post_View_Count class to initialize the plugin.
new Zr_Post_View_Count();