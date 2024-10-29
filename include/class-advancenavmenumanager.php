<?php
/**
 * Description: Advance Navmenu Manager To Manage Menu For Move, Copy, Duplicate Or Disable Menu And Menu Item.
 *
 * @package Advance_Nav_Menu_Manager
 */

if ( ! class_exists( 'Advance_Nav_Menu_Manager' ) ) {
	/**
	 * Create a class for adding all the functionality.
	 */
	class Advance_Nav_Menu_Manager {
		/**
		 * Calling class __construct.
		 */
		public function __construct() {

			// Include file.
			require_once ADVANCENAVMENUMANAGER_PLUGIN_DIR . 'include/option.php';
			// Load text domain.
			add_action( 'plugins_loaded', array( $this, 'anmm_load_textdomain' ) );
		}

		/**
		 * Load Text domain.
		 */
		public function anmm_load_textdomain() {
			load_plugin_textdomain( 'anmm', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}
		/**
		 *  For move single menu to other location.
		 *
		 * @param    (int)    $term_menu_value_item    new menu location id.
		 * @param    (int)    $term_value menu item id to move.
		 * @param    (string) $terms_nav_taxnomy The taxonomy slug of new menu.
		 * @return   (int) on success array of id taxonomy else 0 .
		 */
		public function anmm_move_menu_item( $term_menu_value_item, $term_value, $terms_nav_taxnomy ) {

			$return = wp_set_object_terms( (int) $term_menu_value_item, (int) $term_value, $terms_nav_taxnomy );
			return $return[0];
		}

		/**
		 * Function for move menu with all sub item to other location.
		 *
		 * @param   (int)    $menu_id new menu location id.
		 * @param   (int)    $menu_item_id menu item id to move.
		 * @param   (int)    $move_menu_id new menu id where to move.
		 * @param   (string) $terms_nav_taxnomy The taxonomy slug of new menu.
		 * @return  (int) id on success array of id taxonomy else 0 .
		 */
		public function anmm_move_menu_sub_item( $menu_id, $menu_item_id, $move_menu_id, $terms_nav_taxnomy ) {
			// To find child item at last level.
			$all_child_item_id = $this->find_child_item( $menu_id, $menu_item_id );
			if ( ! empty( $all_child_item_id ) ) {
				foreach ( $all_child_item_id as $all_child_item_ids ) {

					wp_set_object_terms( (int) $all_child_item_ids, (int) $move_menu_id, $terms_nav_taxnomy );
				}
			}

			$return = wp_set_object_terms( (int) $menu_item_id, (int) $move_menu_id, $terms_nav_taxnomy );
			return $return[0];
		}

		/**
		 * Function for copy menu with all sub item to other location.
		 *
		 * @param   (int)    $menu_id current menu location id.
		 * @param   (int)    $menu_item_id menu item id to copy.
		 * @param   (int)    $move_menu_id new menu location id.
		 * @param   (string) $terms_nav_taxnomy The taxonomy slug of new menu.
		 * @return  (int) on success array of id taxonomy else 0 .
		 */
		public function anmm_copy_menu_sub_item( $menu_id, $menu_item_id, $move_menu_id, $terms_nav_taxnomy ) {
			$new_menu_id_first = $this->copy_menu_data( $menu_item_id );
			$all_child_item_id = $this->find_child_item( $menu_id, $menu_item_id );
			if ( ! empty( $all_child_item_id ) ) {
				$i = 0;
				foreach ( $all_child_item_id as $all_child_item_ids ) {
					$key_1_value_first = get_post_meta( $all_child_item_ids, '_menu_item_menu_item_parent', true );
					if ( (int) $menu_item_id === (int) $key_1_value_first ) {
						$parent_menu_id = $new_menu_id_first;
					} elseif ( (int) $all_child_item_ids === (int) $key_1_value_first ) {
						$parent_menu_id = $key_1_value;
					} elseif ( (int) $all_child_item_ids === (int) $key_1_value ) {
						$parent_menu_id = $new_menu_id;
					} elseif ( (int) $privous_item_id === (int) $key_1_value_first ) {
						$parent_menu_id = $new_menu_id;
					} else {
						$parent_menu_id = $privous_menu_id;
					}

					$key_1_value     = get_post_meta( $all_child_item_ids, '_menu_item_menu_item_parent', true );
					$privous_item_id = $all_child_item_ids;
					$privous_menu_id = $new_menu_id;
					$new_menu_id     = $this->copy_menu_data( $all_child_item_ids );

					update_post_meta( $new_menu_id, '_menu_item_menu_item_parent', $parent_menu_id );

					wp_set_object_terms( (int) $new_menu_id, (int) $move_menu_id, $terms_nav_taxnomy );
					$i++;
				}
			}

			$return = wp_set_object_terms( (int) $new_menu_id_first, (int) $move_menu_id, $terms_nav_taxnomy );
			return $return[0];
		}

		/**
		 * Function for copy menu with all sub item to other location.
		 *
		 * @param   (int)    $menu_id current menu location id.
		 * @param   (int)    $menu_item_id menu item id to duplicate.
		 * @param   (string) $terms_nav_taxnomy The taxonomy slug of current menu.
		 * @return  (int) on success array of id taxonomy else 0 .
		 */
		public function anmm_duplicate_menu_sub_item( $menu_id, $menu_item_id, $terms_nav_taxnomy ) {
			$new_menu_id_first = $this->copy_menu_data( $menu_item_id );
			$all_child_item_id = $this->find_child_item( $menu_id, $menu_item_id );
			if ( ! empty( $all_child_item_id ) ) {
				foreach ( $all_child_item_id as $all_child_item_ids ) {
					$key_1_value_first = get_post_meta( $all_child_item_ids, '_menu_item_menu_item_parent', true );
					if ( $menu_item_id === (int) $key_1_value_first ) {
						$parent_menu_id = $new_menu_id_first;
					} elseif ( (int) $all_child_item_ids === (int) $key_1_value_first ) {
						$parent_menu_id = $key_1_value;
					} elseif ( (int) $all_child_item_ids === (int) $key_1_value ) {
						$parent_menu_id = $new_menu_id;
					} elseif ( (int) $privous_item_id === (int) $key_1_value_first ) {
						$parent_menu_id = $new_menu_id;
					} else {
						$parent_menu_id = $privous_menu_id;
					}

					$key_1_value     = get_post_meta( $all_child_item_ids, '_menu_item_menu_item_parent', true );
					$privous_item_id = $all_child_item_ids;
					$privous_menu_id = $new_menu_id;
					$new_menu_id     = $this->copy_menu_data( $all_child_item_ids );
					update_post_meta( $new_menu_id, '_menu_item_menu_item_parent', $parent_menu_id );
					wp_set_object_terms( (int) $new_menu_id, (int) $menu_id, $terms_nav_taxnomy );
				}
			}

			$return = wp_set_object_terms( (int) $new_menu_id_first, (int) $menu_id, $terms_nav_taxnomy );
			return $return[0];
		}

		/**
		 * Function for copy single menu only to other location.
		 *
		 * @param   (int)    $term_menu_value_item current menu item id.
		 * @param   (int)    $menu_id new menu location id.
		 * @param   (string) $terms_nav_taxnomy The taxonomy slug of current menu menu.
		 * @return  (int) on success array of id taxonomy else 0 .
		 */
		public function anmm_copy_menu_item( $term_menu_value_item, $menu_id, $terms_nav_taxnomy ) {
			$new_menu_id = $this->copy_menu_data( $term_menu_value_item );
			$return      = wp_set_object_terms( (int) $new_menu_id, (int) $menu_id, $terms_nav_taxnomy );
			return $return[0];
		}

		/**
		 * Function for duplicate single menu item only on same location.
		 *
		 * @param   (int)    $current_menu_id current menu item id.
		 * @param   (int)    $term_value menu item id to copy.
		 * @param   (string) $terms_nav_taxnomy The taxonomy slug of menu.
		 * @return  (int) on success array of id taxonomy else 0 .
		 */
		public function anmm_duplicate_menu_item( $current_menu_id, $term_value, $terms_nav_taxnomy ) {
			$new_menu_id = $this->copy_menu_data( $term_value );
			$return      = wp_set_object_terms( (int) $new_menu_id, (int) $current_menu_id, $terms_nav_taxnomy );
			return $return[0];

		}

		/**
		 * Function for find sub menu item.
		 *
		 * @param   (int) $menu_id current menu item id.
		 * @param   (int) $menu_item_id menu item id to find sub item.
		 * @return  (array) on success array of id taxonomy .
		 */
		public function find_child_item( $menu_id, $menu_item_id ) {
			$nav_menu_item_list = array();
			$items              = wp_get_nav_menu_items( (int) $menu_id );
			foreach ( $items as $itemss ) {
				if ( (int) $itemss->menu_item_parent === (int) $menu_item_id ) {
					$nav_menu_item_list[] = $itemss->ID;
					$children             = $this->find_child_item( $menu_id, $itemss->ID );
					$nav_menu_item_list   = array_merge( $nav_menu_item_list, $children );
				}
			}
			return $nav_menu_item_list;
		}

		/**
		 * Function for copy of post and post meta of current menu item.
		 *
		 * @param   (int) $post_id of current menu item id.
		 * @return  (int) post id.
		 */
		public function copy_menu_data( $post_id ) {
			$title   = get_the_title( $post_id );
			$oldpost = get_post( $post_id );
			if ( 'Untitled' === $title ) {
				$title = '';
			}
			$menu_order = $oldpost->menu_order + 1;

			$post        = array(
				'post_title'  => $title,
				'post_status' => 'publish',
				'post_type'   => $oldpost->post_type,
				'menu_order'  => $menu_order + 1,
				'post_author' => 1,
			);
			$new_post_id = wp_insert_post( $post );
			// Copy post metadata.
			$data = get_post_custom( $post_id );
			foreach ( $data as $key => $values ) {
				foreach ( $values as $value ) {
					add_post_meta( $new_post_id, $key, maybe_unserialize( $value ) );
				}
			}
			return $new_post_id;
		}

	}

}

/**
* Add ajax action for update data.
*/
add_action( 'wp_ajax_anmm_save_menu_data', 'anmm_save_menu_data' );
add_action( 'wp_ajax_nopriv_anmm_save_menu_data', 'anmm_save_menu_data' );

/**
 * Function for change in menu.
 */
function anmm_save_menu_data() {
	$nonce = sanitize_text_field( wp_unslash( isset( $_POST['ajax_nonce'] ) ? $_POST['ajax_nonce'] : '' ) );
	if ( wp_verify_nonce( $nonce, 'ajax_nonce' ) ) {

		$to_do           = sanitize_text_field( wp_unslash( isset( $_POST['to_do'] ) ? $_POST['to_do'] : '' ) );
		$current_menu_id = sanitize_text_field( wp_unslash( isset( $_POST['current_menu_id'] ) ? $_POST['current_menu_id'] : '' ) );
		$menu_item_id    = sanitize_text_field( wp_unslash( isset( $_POST['nav_menu_id_advance_item'] ) ? $_POST['nav_menu_id_advance_item'] : '' ) );
		$menu_move       = sanitize_text_field( wp_unslash( isset( $_POST['menu_move_select'] ) ? $_POST['menu_move_select'] : '' ) );
		$have_sub_item   = sanitize_text_field( wp_unslash( isset( $_POST['have_sub_item'] ) ? $_POST['have_sub_item'] : '' ) );
		$new_menu        = explode( '-', $menu_move );
		$new_menu_id     = $new_menu[0];
		$terms_nav       = get_term( $current_menu_id );
		$anmm_objs       = new Advance_Nav_Menu_Manager();
		if ( 'duplicate' === strval( $to_do ) ) {
			if ( 'yes' === strval( $have_sub_item ) ) {
				$response = $anmm_objs->anmm_duplicate_menu_sub_item( (int) $current_menu_id, (int) $menu_item_id, $terms_nav->taxonomy );
			} else {
				$response = $anmm_objs->anmm_duplicate_menu_item( (int) $current_menu_id, (int) $menu_item_id, $terms_nav->taxonomy );
			}
		} elseif ( 'copy' === strval( $to_do ) ) {
			if ( 'yes' === strval( $have_sub_item ) ) {

				$response = $anmm_objs->anmm_copy_menu_sub_item( (int) $current_menu_id, (int) $menu_item_id, (int) $new_menu_id, $terms_nav->taxonomy );
			} else {
				$response = $anmm_objs->anmm_copy_menu_item( (int) $menu_item_id, (int) $new_menu_id, $terms_nav->taxonomy );
			}
		} elseif ( 'move' === strval( $to_do ) ) {
			if ( 'yes' === strval( $have_sub_item ) ) {
				$response = $anmm_objs->anmm_move_menu_sub_item( (int) $current_menu_id, (int) $menu_item_id, (int) $new_menu_id, $terms_nav->taxonomy );
			} else {
				$response = $anmm_objs->anmm_move_menu_item( (int) $menu_item_id, (int) $new_menu_id, $terms_nav->taxonomy );
			}
		}

		if ( $response > 0 ) {
			$msg = array(
				'result'  => '1',
				'message' => 'Your Change Has Been Applied Successfully.',
			);
		}
	} else {
				$msg = array(
					'result'  => '0',
					'message' => 'Nonce Verification Failed.',
				);
	}
	echo wp_json_encode( $msg );
	wp_die();
}
