<?php
/**
 * Description: Add User Option like Button and other form field And enque script.
 *
 * @package advancenavmenumanager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// add custom option and update it.
add_action( 'wp_nav_menu_item_custom_fields', 'anmm_custom_field_menu', 10, 4 );

/**
 *  For custom field.
 *
 * @param  (int)      $item_id Menu item ID.
 * @param  (WP_Post)  $item  Menu item data object.
 * @param  (int)      $depth Depth of menu item. Used for order and sub menu.
 * @param  (stdClass) $args An object of menu item arguments.
 */
function anmm_custom_field_menu( $item_id, $item, $depth, $args ) {
	$get_menu_detail = wp_get_object_terms( $item_id, 'nav_menu' );
	if ( ! empty( $get_menu_detail ) ) {
		$get_menu = $get_menu_detail[0];
	}
	$menus        = wp_get_nav_menus();
	$current_menu = $get_menu->term_taxonomy_id;
	if ( ! empty( $current_menu ) ) {
		?>
<!-- New interface option for user  -->
<div class="field-custom_menu_meta description-wide" style="margin: 5px 0;">			
		<input type="hidden" name="nav_menu_id_advance_item" value="<?php echo esc_attr( $item_id ); ?>" id="nav_menu_id_advance_item<?php echo esc_attr( $item_id ); ?>"/>
		<input type="hidden" name="current_menu_id" value="<?php echo esc_attr( $current_menu ); ?>" id="current_menu_id<?php echo esc_attr( $item_id ); ?>" />
		<div class="logged-input-holder">
		<?php
		if ( count( $menus ) > 1 ) {
			?>
			<span class="description"><?php esc_html_e( 'Move To', 'advance-nav-menu-manager' ); ?></span>
				<select name="menu_move_select" id="menu_move_select<?php echo esc_attr( $item_id ); ?>">
					<option value="" selected="selected"><?php esc_html_e( 'Select One', 'advance-nav-menu-manager' ); ?></option>
					<?php
					foreach ( $menus as $menuss ) {
						if ( $current_menu !== $menuss->term_id ) {
							?>
		<option value="<?php echo esc_attr( $menuss->term_id ) . '-' . esc_attr( $item_id ); ?>"><?php echo esc_attr( $menuss->name ); ?></option>                                  
							<?php
						}
					}
					?>
					</select>
				<?php
		}

		$all_items     = wp_get_nav_menu_items( (int) $current_menu );
		$have_sub_item = 0;
		foreach ( $all_items as $all_item ) {

			if ( (int) $all_item->menu_item_parent === (int) $item_id ) {
				$have_sub_item = 1;
			}
		}

		if ( $have_sub_item > 0 ) {
			?>
					<label class="have_sub_item-button" for="have_sub_item-switcher-top">
						<input type="checkbox" name="have_sub_item[]" class="" value="yes" id="have_sub_item<?php echo esc_attr( $item_id ); ?>" >
						<span class="have_sub_item-button-label"><?php esc_html_e( 'With Sub Item', 'advance-nav-menu-manager' ); ?></span>
					</label>
				<?php
		}
		?>
				<br><br>
				<?php
				if ( count( $menus ) > 1 ) {
					?>
					<button type="button" name="duplicate_menus" class="button button-primary button-large anmm-duplicate-submit" data-action="move" value="<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Move', 'advance-nav-menu-manager' ); ?></button>
					<button type="button" name="duplicate_menus" class="button button-primary button-large anmm-duplicate-submit" data-action="copy" value="<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Copy', 'advance-nav-menu-manager' ); ?></button>
					<?php
				}
				?>
				<button type="button" name="duplicate_menus" class="button button-primary button-large anmm-duplicate-submit" data-action="duplicate" value="<?php echo esc_attr( $item_id ); ?>"><?php esc_html_e( 'Duplicate', 'advance-nav-menu-manager' ); ?></button>
				<hr>
			</div>
	</div>
		<?php
	}

}

// enqueue script for ajax call and other function.
add_action( 'admin_enqueue_scripts', 'advance_nav_menu_scripts' );
/**
 * Register admin scripts to call ajax function.
 */
function advance_nav_menu_scripts() {

	wp_register_script( 'nav-menu-change', ADVANCENAVMENUMANAGER_PLUGIN_URL . 'assets/js/advance-navmenu-manager.js', array( 'jquery' ), true, true );

	wp_enqueue_script( 'nav-menu-change' );

	wp_localize_script(
		'nav-menu-change',
		'ANM_AJAX_OB',
		array(
			'ajax_url'   => admin_url( 'admin-ajax.php' ),
			'ajax_nonce' => wp_create_nonce( 'ajax_nonce' ),
		)
	);

}
