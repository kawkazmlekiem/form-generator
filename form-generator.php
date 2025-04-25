<?php
/*
 * Plugin Name:       Form Generator
 * Description:       A plugin for adding simple form to a website.
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      8.2.27
 * Author:            Klaudia Łaskawiec
 * Text Domain:       form-generator
 * Domain Path:       /languages
 */

 // Make sure we don't expose any info if called directly 
if(!function_exists('add_action')) {
    echo 'Wrong URL :(';
    exit;
}

// Setup
define('FG_PLUGIN_DIR', plugin_dir_path(__FILE__));

// Includes
include(FG_PLUGIN_DIR . 'includes/register-blocks.php');
include(FG_PLUGIN_DIR . 'includes/render.php');
include(FG_PLUGIN_DIR . 'includes/handle-endpoint.php');

// Hooks
add_action('init', 'fg_register_blocks');
add_action( 'rest_api_init', 'fg_register_routes');
add_action('enqueue_block_assets', 'fg_form_scripts');

// REST API new endpoint
function fg_register_routes() {
    register_rest_route( 'my-endpoint', '/1', array(
        'methods'  => 'POST',
        'callback' => 'fg_handle_form_submission',
    ) );
}

// Form handler custom script
function fg_form_scripts() {
    wp_enqueue_script(
        'fg-custom-form-script',
        plugin_dir_url(__FILE__) . 'form-handler.js',
        [], 
        null,
        true
    );
    wp_localize_script('fg-custom-form-script', 'form', [
        'restUrl' => esc_url(rest_url('my-endpoint/1'))
    ]);
}
