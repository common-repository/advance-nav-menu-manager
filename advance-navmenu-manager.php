<?php
/**
 * Plugin Name: Advance Nav Menu Manager
 * Description: Advance Navmenu Manager to manage menu for move copy or duplicate menu item
 * Author:      KrishaWeb
 * Version:     1.0
 * Text Domain: advance-nav-menu-manager
 * Domain Path: /languages
 *
 * @package advancenavmenumanager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ADVANCENAVMENUMANAGER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ADVANCENAVMENUMANAGER_FILE', __FILE__ );
define( 'ADVANCENAVMENUMANAGER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * When a plugin is install, this action is called.
 */
function advance_nav_menu_manager_install() {
	// Enter your code here.
}
register_activation_hook( ADVANCENAVMENUMANAGER_FILE, 'advance_nav_menu_manager_install' );
/**
 * When a plugin is deactivated, this action is called.
 */
function advance_nav_menu_manager_uninstall() {
	// Enter your code here.
}
register_deactivation_hook( ADVANCENAVMENUMANAGER_FILE, 'advance_nav_menu_manager_uninstall' );
/**
 * Initialize plugin.
 */
function advance_nav_menu_manager_initialize() {
	if ( is_admin() ) {
		// we are in admin mode.
		require_once ADVANCENAVMENUMANAGER_PLUGIN_DIR . '/include/class-advancenavmenumanager.php';
		// Class calling.
		$advancenavmenumanager_obj = new Advance_Nav_Menu_Manager();
	}
}
add_action( 'init', 'advance_nav_menu_manager_initialize' );
/**
 * Initialize.
 */
function advance_nav_menu_manager_load_text_domain() {
    // Register taxtdomain.
    load_plugin_textdomain( 'advance-nav-menu-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'advance_nav_menu_manager_load_text_domain' );


