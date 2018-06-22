<?php

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 */

if ( ! defined('MESMERIZE_THEME_REQUIRED_PHP_VERSION')) {
    define('MESMERIZE_THEME_REQUIRED_PHP_VERSION', '5.3.0');
}

add_action('after_switch_theme', 'mesmerize_check_php_version');

function mesmerize_check_php_version()
{
    // Compare versions.
    if (version_compare(phpversion(), MESMERIZE_THEME_REQUIRED_PHP_VERSION, '<')) :
        // Theme not activated info message.
        add_action('admin_notices', 'mesmerize_php_version_notice');


        // Switch back to previous theme.
        switch_theme(get_option('theme_switched'));

        return false;
    endif;
}

function mesmerize_php_version_notice()
{
    ?>
    <div class="notice notice-alt notice-error notice-large">
        <h4><?php _e('Mesmerize theme activation failed!', 'mesmerize'); ?></h4>
        <p>
            <?php _e('You need to update your PHP version to use the <strong>Mesmerize</strong>.', 'mesmerize'); ?> <br/>
            <?php _e('Current php version is:', 'mesmerize') ?> <strong>
                <?php echo phpversion(); ?></strong>, <?php _e('and the minimum required version is ', 'mesmerize') ?>
            <strong><?php echo MESMERIZE_THEME_REQUIRED_PHP_VERSION; ?></strong>
        </p>
    </div>
    <?php
}

if (version_compare(phpversion(), MESMERIZE_THEME_REQUIRED_PHP_VERSION, '>=')) {
    require_once get_template_directory() . "/inc/functions.php";

     

    do_action("mesmerize_customize_register_options");
} else {
    add_action('admin_notices', 'mesmerize_php_version_notice');
}

// Enqueue Scripts/Styles for our owl.carousel.2
function agencyclix_add_owlcarousel() {
wp_enqueue_script ( 'jquery' );
wp_enqueue_script( 'owlcarousel', get_template_directory_uri() . '/assets/js/owl.carousel.js', array( 'jquery' ), false, true );
//wp_enqueue_script( 'owlcarousel', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', array( 'jquery' ), false, true );

wp_enqueue_style( 'owlcarousel-style', get_template_directory_uri() . '/assets/css/owl.carousel.min.css' );
}
add_action( 'wp_enqueue_scripts', 'agencyclix_add_owlcarousel' );

/**
 * Register Sidebar
 */
function beyoung_register_sidebars() {
 
    /* Register the primary sidebar. */
    register_sidebar(
        array(
            'id' => 'homepage-sidebar',
            'name' => __( 'Home Sidebar', 'beyoung' ),
            'description' => __( 'Sidebar for homepage.', 'beyoung' ),
               'before_widget' => '<div id="text-%1$s" class="widget widget_text">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>'
        )
    );
}
add_action( 'widgets_init', 'beyoung_register_sidebars' );