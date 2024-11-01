<?php
/*
Plugin Name: Code Highlighter Block
Description: <strong>Code Highlighter Block</strong> is simple, light-weight Code Syntax Highlighing plugin that highlights codes of different languages.
Author: Webackstop
Author URI: https://webackstop.com
Version: 1.0.0
Text Domain: code-highlighter-block
License: GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Domain Path:  /languages
*/

if ( !defined( 'ABSPATH' ) ) {
    exit();
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';

/**
 * Admin Assets
 */
function code_highlighter_block_admin_assets( $screen ) {
    if ( $screen == 'tools_page_chb_help' ) {
        wp_enqueue_style( 'chb-admin-css', plugins_url( 'admin/admin.css', __FILE__ ), array(), time(), 'all' );
    }
}
add_action( 'admin_enqueue_scripts', 'code_highlighter_block_admin_assets' );


/**
 * Support Link 
 */
function code_highlighter_block_settings_link( $links ) {
    $ptsp_settings = array( '<a href="' . esc_url( 'https://webackstop.com/submit-ticket' ) . '" target="_blank"  style="color: green; font-weight: bold">Support</a>', );
    return array_merge( $ptsp_settings, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'code_highlighter_block_settings_link' );

/**
 * Admin Support Page
 */
function code_highlighter_block_admin_support_page() {
    add_submenu_page( 'tools.php', __( 'Code Block', 'code-highlighter-block' ), __( 'Code Block', 'code-highlighter-block' ), 'manage_options', 'chb_help', 'chb_admin_page_callback' );
}

add_action( 'admin_menu', 'code_highlighter_block_admin_support_page' );

function chb_admin_page_callback() {
    ?>
    <div class="ptsp_admin_page">
        <div class="ptsp_header">
            <a href="https://webackstop.com/place-order" target="_blank"><?php echo __( 'Hire', 'code-highlighter-block' ); ?></a>
            <a href="https://webackstop.com/submit-ticket" target="_blank"><?php echo __( 'Create Ticket', 'code-highlighter-block' ); ?></a>
        </div>
        <div class="ptsp_support_blocks">
            <div class="single-block">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M7.77 6.76L6.23 5.48.82 12l5.41 6.52 1.54-1.28L3.42 12l4.35-5.24zM7 13h2v-2H7v2zm10-2h-2v2h2v-2zm-6 2h2v-2h-2v2zm6.77-7.52l-1.54 1.28L20.58 12l-4.35 5.24 1.54 1.28L23.18 12l-5.41-6.52z"/></svg>
                </div>
                <div class="help_link">
                    <span><?php echo __( 'Need Help?', 'code-highlighter-block' ); ?></span>
                    <?php echo '<a href="https://webackstop.com/submit-ticket/" target="_blank">' . __( 'Create Support Ticket', 'code-highlighter-block' ) . '</a>'; ?>
                </div>
            </div>
            <div class="single-block">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/></svg>
                </div>
                <div class="help_link">
                    <span><?php echo __( 'Like this plugin?', 'code-highlighter-block' ); ?></span>
                    <?php echo '<a href="https://wordpress.org/plugins/code-highlighter-block/#reviews" target="_blank">' . __( 'Leave a Positive Review', 'code-highlighter-block' ) . '</a>'; ?>
                </div>
            </div>
            <div class="single-block">
                <div class="icon">
                   <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0z" fill="none"/><path d="M0 0h24v24H0z" fill="none"/><path d="M21 8V7l-3 2-3-2v1l3 2 3-2zm1-5H2C.9 3 0 3.9 0 5v14c0 1.1.9 2 2 2h20c1.1 0 1.99-.9 1.99-2L24 5c0-1.1-.9-2-2-2zM8 6c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm6 12H2v-1c0-2 4-3.1 6-3.1s6 1.1 6 3.1v1zm8-6h-8V6h8v6z"/></svg>
                </div>
                <div class="help_link">
                    <span><?php echo __( 'Have a Freelance Work?', 'code-highlighter-block' ); ?></span>
                    <?php echo '<a href="https://webackstop.com/contact/" target="_blank">' . __( 'Contact Us', 'code-highlighter-block' ) . '</a>'; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/*
* Redirecting
*/
function code_highlighter_block_user_redirecting( $plugin ) {
    if ( plugin_basename( __FILE__ ) == $plugin ) {
        wp_redirect( admin_url( 'tools.php?page=chb_help' ) );
        die();
    }
}

add_action( 'activated_plugin', 'code_highlighter_block_user_redirecting' );