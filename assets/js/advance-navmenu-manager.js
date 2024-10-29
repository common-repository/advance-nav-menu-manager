jQuery( document ).ready( function( $ ) {

	jQuery( document ).on( 'click', '.anmm-duplicate-submit', function( e ) {
	/**
	 * get all data and variable to change.
	 */
	var classs = jQuery(this).attr('data-action');
	var action_value = jQuery(this).attr('value');
	var new_menu_id = '';
	var have_sub_item='no';				
	have_sub_item_field = jQuery('#have_sub_item'+action_value).is(":checked");
	if( have_sub_item_field ) {
		have_sub_item = 'yes';
	}				
	var nav_menu_id_advance_item = jQuery('#nav_menu_id_advance_item'+action_value).val();
	var current_menu_id = jQuery('#current_menu_id'+action_value).val();
	if(classs=='copy' || classs=='move'){
		new_menu_id = jQuery('#menu_move_select'+action_value).find(":selected").val();
		if(new_menu_id==''){
			alert('Please Select Menu First');
			return false;
		}					
	}
	e.preventDefault();
	var formData = {
		action: 'anmm_save_menu_data',
		to_do: classs,
		current_menu_id: current_menu_id,
		have_sub_item: have_sub_item,
		nav_menu_id_advance_item: nav_menu_id_advance_item,
		ajax_nonce: ANM_AJAX_OB.ajax_nonce,
		menu_move_select: new_menu_id
	}
	/**
	 * ajax request for menu change.
	 */
	jQuery.post(ANM_AJAX_OB.ajax_url, formData,
		function ( response ) {
			var ajax_result = jQuery.parseJSON( response );
			alert(ajax_result.message);
			window.onbeforeunload = null;
			window.location=document.location.href;     
		});
	});

} );
