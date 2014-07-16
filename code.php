<?php
add_action('personal_options_update', 'check_display_name');
add_action('edit_user_profile_update', 'check_display_name');
function check_display_name($user_id) {
        global $wpdb;
        $err['display'] = $wpdb->get_var($wpdb->prepare("SELECT COUNT(ID) FROM $wpdb->users WHERE display_name = %s AND ID <> %d", $_POST['display_name'], $_POST['user_id']));
	$err['nick'] = $wpdb->get_var($wpdb->prepare("SELECT COUNT(ID) FROM $wpdb->users as users, $wpdb->usermeta as meta WHERE users.ID = meta.user_id AND meta.meta_key = 'nickname' AND meta.meta_value = %s AND users.ID <> %d", $_POST['nickname'], $_POST['user_id']));
	foreach($err as $key => $e) {
		if($e >= 1) {
			$err[$key] = $_POST['username'];
			add_filter('user_profile_update_errors', "check_{$key}_field", 10, 3);
		}
	}
}
function check_display_field($errors, $update, $user) {
        $errors->add('display_name_error',__('Sorry, Display Name is already in use. It needs to be unique.'));
        return false;
}
function check_nick_field($errors, $update, $user) {
        $errors->add('display_nick_error',__('Sorry, Nickname is already in use. It needs to be unique.'));
        return false;
}
