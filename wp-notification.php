<?php
/*
Plugin Name: WP-Notification
Plugin URI: http://wp-learning.net
Description: Show custom notifications on the dashboard
Version: 1.6
Author: Tomek
Author URI: http://wp-learning-net
Text Domain: wp-notification
Domain Path: /lang
*/

load_plugin_textdomain( 'wp-notification', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );

function wp_notification_menu() {
	global $current_user, $wp_admin_bar;
	$user_info = get_userdata($current_user->ID);
	$role = implode(', ', $user_info->roles);
	if(get_option($role."_notices_login") != $current_user->user_login || get_option("custom_notices_text") == true ){
		$wp_admin_bar->add_menu( array(
			'parent' => 'top-secondary',
			'id'     => 'notification-menu',
			'title'  => '<span class="ab-icon dashicons dashicons-warning"></span>' . __('New notification','wp-notification'),
			'href'   => false,
			'meta'   =>  array( 'class' => 'notification-admin-menu' )
		) );
	}
	if(get_option($role."_notices_login") != $current_user->user_login){
		if(get_option($role."_notices_text") == true ){
			if(get_option($role."_notices_notification_type") == "error" ){
				$type = '#DC3232';
			} else if(get_option($role."_notices_notification_type") == "warning" ){
				$type = '#FFB900';
			} else if(get_option($role."_notices_notification_type") == "success" ){
				$type = '#46B450';
			} else if(get_option($role."_notices_notification_type") == "info" ){
				$type = "#00A0D2";
			}
			$wp_admin_bar->add_menu( array(
				'parent' => 'notification-menu',
				'id'     => 'notification-submenu',
				'title'  => '<span class="notification-type" style="background:'.$type.';color:black;font-weight:bold;;padding:5px">!</span><span class="notification-content" style="padding-left:5px">'.strip_tags(stripslashes(get_option($role."_notices_text"))).'</span>',
				'href'   => false,
				'meta'   =>  array( 'class' => 'notification-admin-submenu' )
			) );
		}
	}
	if(get_option("custom_notices_text") == true ){
		if(get_option("custom_notices_notification_type") == "error" ){
			$type = '#DC3232';
		} else if(get_option("custom_notices_notification_type") == "warning" ){
			$type = '#FFB900';
		} else if(get_option("custom_notices_notification_type") == "success" ){
			$type = '#46B450';
		} else if(get_option("custom_notices_notification_type") == "info" ){
			$type = "#00A0D2";
		}
		$wp_admin_bar->add_menu( array(
			'parent' => 'notification-menu',
			'id'     => 'notification-submenu1',
			'title'  => '<span class="notification-type" style="background:'.$type.';color:black;font-weight:bold;;padding:5px">!</span><span class="notification-content" style="padding-left:5px">'.strip_tags(stripslashes(get_option("custom_notices_text"))).'</span>',
			'href'   => false,
			'meta'   =>  array( 'class' => 'notification-admin-submenu' )
		) );
	}
}

function wp_notification_core() {
	global $current_screen, $current_user;
	$user_info = get_userdata($current_user->ID);
	$role = implode(', ', $user_info->roles);
	if(get_option($role."_notices_text") == true ){
		if(get_option($role."_notices_hide") == true ){
			$hide = ' is-dismissible';
		}
		if(get_option($role."_notices_notification_type") == "error" ){
			$type = 'notice notice-error';
		} else if(get_option($role."_notices_notification_type") == "warning" ){
			$type = 'notice notice-warning';
		} else if(get_option($role."_notices_notification_type") == "success" ){
			$type = 'notice notice-success';
		} else if(get_option($role."_notices_notification_type") == "info" ){
			$type = 'notice notice-info';
		}
		if( $current_screen->id == get_option($role."_notices_location") ) {
			?><div class="<?php echo $type.$hide ?>"><p><?php echo stripslashes(get_option($role."_notices_text")) ?></p></div><?php
		} else if ( get_option($role."_notices_location") == 'all' ) {
			?><div class="<?php echo $type.$hide ?>"><p><?php echo stripslashes(get_option($role."_notices_text")) ?></p></div><?php
		}
	}
	if(get_option("custom_notices_text") == true ){
		if(get_option("custom_notices_hide") == true ){
			$hide = ' is-dismissible';
		}
		if(get_option("custom_notices_notification_type") == "error" ){
			$type = 'notice notice-error';
		} else if(get_option("custom_notices_notification_type") == "warning" ){
			$type = 'notice notice-warning';
		} else if(get_option("custom_notices_notification_type") == "success" ){
			$type = 'notice notice-success';
		} else if(get_option("custom_notices_notification_type") == "info" ){
			$type = 'notice notice-info';
		}
		if( $current_screen->id == get_option("custom_notices_location") ) {
			?><div class="<?php echo $type.$hide ?>"><p><?php echo stripslashes(get_option("custom_notices_text")) ?></p></div><?php
		} else if ( get_option("custom_notices_location") == 'all' ) {
			?><div class="<?php echo $type.$hide ?>"><p><?php echo stripslashes(get_option("custom_notices_text")) ?></p></div><?php
		}
	}
}

function options_save() {
	if ( false != $_REQUEST['settings-updated'] ) : ?>
	<div class="updated fade"><p><strong><?php _e( 'Options saved', 'wp-notification' ); ?></strong></p></div>
	<?php endif;
}

function notices_footer() {
	?>
	<p class="submit"><input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes','wp-notification'); ?>"></p>
    </form>
	<strong>
	<?php _e('A plugin by ','wp-notification'); ?><a target="_blank" href="http://wp-learning.net"><em>Tomek</em></a> <a target="_blank" href="https://www.paypal.me/tomekmaestro"><?php _e('Donation','wp-notification'); ?></a>
	</strong>
	<?php
}

function adminiszrator_notices_settings_page() {
	settings_fields('administrator_notice_settings_page');
	do_settings_sections('administrator_notice_settings_page');
	?>
    <h3><?php _e('Administrator notice','wp-notification') ?></h3>
	<?php
	$admin_noice = stripslashes(get_option("administrator_notices_text"));
	wp_editor( $admin_noice, 'administrator_notices_text', $settings = array('textarea_name' => 'administrator_notices_text','textarea_rows' => '5') );
	?>
	<div><label for="administrator_notices_location">
	<?php _e('Where to display?','wp-notification'); ?> <select style="width:300px" name="administrator_notices_location" id="administrator_notices_location">
		<?php $anl_value = get_option('administrator_notices_location'); ?>
		<option value="0" <?php if ($anl_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="all" <?php if ($anl_value=='all') { echo 'selected'; } ?>><?php _e('All Page','wp-notification'); ?></option>
		<option value="dashboard" <?php if ($anl_value=='dashboard') { echo 'selected'; } ?>><?php _e('Dashboard','wp-notification'); ?></option>
		<option value="update-core" <?php if ($anl_value=='update-core') { echo 'selected'; } ?>><?php _e('Updates','wp-notification'); ?></option>
		<option value="edit-post" <?php if ($anl_value=='edit-post') { echo 'selected'; } ?>><?php _e('Posts','wp-notification'); ?></option>
		<option value="post" <?php if ($anl_value=='post') { echo 'selected'; } ?>><?php _e('Add New','wp-notification'); ?></option>
		<option value="edit-category" <?php if ($anl_value=='edit-category') { echo 'selected'; } ?>><?php _e('Categories','wp-notification'); ?></option>
		<option value="edit-post_tag" <?php if ($anl_value=='edit-post_tag') { echo 'selected'; } ?>><?php _e('Tags','wp-notification'); ?></option>
		<option value="upload" <?php if ($anl_value=='upload') { echo 'selected'; } ?>><?php _e('Media','wp-notification'); ?></option>
		<option value="media" <?php if ($anl_value=='media') { echo 'selected'; } ?>><?php _e('Add New','wp-notification'); ?></option>
		<option value="edit-page" <?php if ($anl_value=='edit-page') { echo 'selected'; } ?>><?php _e('Pages','wp-notification'); ?></option>
		<option value="page" <?php if ($anl_value=='page') { echo 'selected'; } ?>><?php _e('Add New','wp-notification'); ?></option>
		<option value="edit-comments" <?php if ($anl_value=='edit-comments') { echo 'selected'; } ?>><?php _e('Comments','wp-notification'); ?></option>
		<option value="themes" <?php if ($anl_value=='themes') { echo 'selected'; } ?>><?php _e('Appearance','wp-notification'); ?></option>
		<option value="widgets" <?php if ($anl_value=='widgets') { echo 'selected'; } ?>><?php _e('Widgets','wp-notification'); ?></option>
		<option value="nav-menus" <?php if ($anl_value=='nav-menus') { echo 'selected'; } ?>><?php _e('Menus','wp-notification'); ?></option>
		<option value="appearance_page_custom-header" <?php if ($anl_value=='appearance_page_custom-header') { echo 'selected'; } ?>><?php _e('Header','wp-notification'); ?></option>
		<option value="appearance_page_custom-background" <?php if ($anl_value=='appearance_page_custom-background') { echo 'selected'; } ?>><?php _e('Background','wp-notification'); ?></option>
		<option value="theme-editor" <?php if ($anl_value=='theme-editor') { echo 'selected'; } ?>><?php _e('Editor','wp-notification'); ?></option>
		<option value="plugins" <?php if ($anl_value=='plugins') { echo 'selected'; } ?>><?php _e('Plugins','wp-notification'); ?></option>
		<option value="plugin-install" <?php if ($anl_value=='plugin-install') { echo 'selected'; } ?>><?php _e('Add New','wp-notification'); ?></option>
		<option value="plugin-editor" <?php if ($anl_value=='plugin-editor') { echo 'selected'; } ?>><?php _e('Editor','wp-notification'); ?></option>
		<option value="users" <?php if ($anl_value=='users') { echo 'selected'; } ?>><?php _e('Users','wp-notification'); ?></option>
		<option value="user" <?php if ($anl_value=='user') { echo 'selected'; } ?>><?php _e('Add New','wp-notification'); ?></option>
		<option value="profile" <?php if ($anl_value=='profile') { echo 'selected'; } ?>><?php _e('Your Profile','wp-notification'); ?></option>
		<option value="tools" <?php if ($anl_value=='toolt') { echo 'selected'; } ?>><?php _e('Tools','wp-notification'); ?></option>
		<option value="import" <?php if ($anl_value=='import') { echo 'selected'; } ?>><?php _e('Import','wp-notification'); ?></option>
		<option value="export" <?php if ($anl_value=='expört') { echo 'selected'; } ?>><?php _e('Export','wp-notification'); ?></option>
		<option value="options-general" <?php if ($anl_value=='options-general') { echo 'selected'; } ?>><?php _e('Settings','wp-notification'); ?></option>
		<option value="options-writing" <?php if ($anl_value=='options-writing') { echo 'selected'; } ?>><?php _e('Writing','wp-notification'); ?></option>
		<option value="options-reading" <?php if ($anl_value=='options-reading') { echo 'selected'; } ?>><?php _e('Reading','wp-notification'); ?></option>
		<option value="options-discussion" <?php if ($anl_value=='options-discussion') { echo 'selected'; } ?>><?php _e('Discussion','wp-notification'); ?></option>
		<option value="options-media" <?php if ($anl_value=='options-media') { echo 'selected'; } ?>><?php _e('Media','wp-notification'); ?></option>
		<option value="options-permalink" <?php if ($anl_value=='options-permalink') { echo 'selected'; } ?>><?php _e('Permalinks','wp-notification'); ?></option>
	</select> 
	</label></div>
	<div><label for="administrator_notices_notification_type">
	<?php _e('Notification type?','wp-notification'); ?> <select style="width:300px" name="administrator_notices_notification_type" id="administrator_notices_notification_type">
		<?php $annt_value = get_option('administrator_notices_notification_type'); ?>
		<option value="0" <?php if ($annt_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="error" <?php if ($annt_value=='error') { echo 'selected'; } ?>><?php _e('Error','wp-notification'); ?></option>
		<option value="warning" <?php if ($annt_value=='warning') { echo 'selected'; } ?>><?php _e('Warning','wp-notification'); ?></option>
		<option value="success" <?php if ($annt_value=='success') { echo 'selected'; } ?>><?php _e('Success','wp-notification'); ?></option>
		<option value="info" <?php if ($annt_value=='info') { echo 'selected'; } ?>><?php _e('Info','wp-notification'); ?></option>
	</select> 
	</label></div>
	<div><label for="administrator_notices_hide">
	<input type="checkbox" name="administrator_notices_hide" id="administrator_notices_hide" value="1" <?php checked(get_option('administrator_notices_hide')); ?>"><?php _e('Display "Hide" button?','wp-notification') ?>
	</label></div>
	<div><label for="administrator_notices_admin_bar">
	<input type="checkbox" name="administrator_notices_admin_bar" id="administrator_notices_admin_bar" value="1" <?php checked(get_option('administrator_notices_admin_bar')); ?>"><?php _e('Show "New notification" menu in administrator bar','wp-notification') ?>
	</label></div>
	<?php
}

function editor_notices_settings_page() {
	settings_fields('editor_notice_settings_page');
	do_settings_sections('editor_notice_settings_page');
	?>
    <h3><?php _e('Editor notice','wp-notification') ?></h3>
	<?php
	$editor_noice = stripslashes(get_option("editor_notices_text"));
	wp_editor( $editor_noice, 'editor_notices_text', $settings = array('textarea_name' => 'editor_notices_text','textarea_rows' => '5') );
	?>
	<div><label for="editor_notices_location">
	<?php _e('Where to display?','wp-notification'); ?><select style="width:300px" name="editor_notices_location" id="editor_notices_location">
		<?php $enl_value = get_option('editor_notices_location'); ?>
		<option value="0" <?php if ($enl_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="all" <?php if ($enl_value=='all') { echo 'selected'; } ?>><?php _e('All Page','wp-notification'); ?></option>
		<option value="dashboard" <?php if ($enl_value=='dashboasd') { echo 'selected'; } ?>><?php _e('Dashboard','wp-notification'); ?></option>
		<option value="edit-post" <?php if ($enl_value=='edit-post') { echo 'selected'; } ?>><?php _e('Posts','wp-notification'); ?></option>
		<option value="post" <?php if ($enl_value=='post') { echo 'selected'; } ?>><?php _e('Add New','wp-notification'); ?></option>
		<option value="upload" <?php if ($enl_value=='upload') { echo 'selected'; } ?>><?php _e('Media','wp-notification'); ?></option>
		<option value="media" <?php if ($enl_value=='media') { echo 'selected'; } ?>><?php _e('Add New','wp-notification'); ?></option>
		<option value="edit-comments" <?php if ($enl_value=='edit-comments') { echo 'selected'; } ?>><?php _e('Comments','wp-wp-notification'); ?></option>
		<option value="profile" <?php if ($enl_value=='profile') { echo 'selected'; } ?>><?php _e('Profile','wp-notification'); ?></option>7
		<option value="tools" <?php if ($enl_value=='tools') { echo 'selected'; } ?>><?php _e('Tools','wp-notification'); ?></option>
	</select> 
	</label></div>
	<div><label for="editor_notices_notification_type">
	<?php _e('Notification type?','wp-notification'); ?> <select style="width:300px" name="editor_notices_notification_type" id="editor_notices_notification_type">
		<?php $ennt_value = get_option('editor_notices_notification_type'); ?>
		<option value="0" <?php if ($ennt_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="error" <?php if ($ennt_value=='error') { echo 'selected'; } ?>><?php _e('Error','wp-notification'); ?></option>
		<option value="warning" <?php if ($ennt_value=='warning') { echo 'selected'; } ?>><?php _e('Warning','wp-notification'); ?></option>
		<option value="success" <?php if ($ennt_value=='success') { echo 'selected'; } ?>><?php _e('Success','wp-notification'); ?></option>
		<option value="info" <?php if ($ennt_value=='info') { echo 'selected'; } ?>><?php _e('Info','wp-notification'); ?></option>
	</select> 
	</label></div>
	<div><label for="editor_notices_hide">
	<input type="checkbox" name="editor_notices_hide" id="editor_notices_hide" value="1" <?php checked(get_option('editor_notices_hide')); ?>"><?php _e('Display "Hide" button?','wp-notification') ?>
	</label></div>
	<div><label for="editor_notices_admin_bar">
	<input type="checkbox" name="editor_notices_admin_bar" id="editor_notices_admin_bar" value="1" <?php checked(get_option('editor_notices_admin_bar')); ?>"><?php _e('Show "New notification" menu in administrator bar','wp-notification') ?>
	</label></div>
	<?php
}

function author_notices_settings_page() {
	settings_fields('author_notice_settings_page');
	do_settings_sections('author_notice_settings_page');
	?>
    <h3><?php _e('Author notice','wp-notification') ?></h3>
	<?php
	$author_noice = stripslashes(get_option("author_notices_text"));
	wp_editor( $author_noice, 'author_notices_text', $settings = array('textarea_name' => 'author_notices_text','textarea_rows' => '5') );
	?>
	<div><label for="author_notices_location">
	<?php _e('Where to display?','wp-notification'); ?><select style="width:300px" name="author_notices_location" id="author_notices_location">
		<?php $anl_value = get_option('author_notices_location'); ?>
		<option value="0" <?php if ($anl_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="all" <?php if ($anl_value=='all') { echo 'selected'; } ?>><?php _e('All Page','wp-notification'); ?></option>
		<option value="dashboard" <?php if ($anl_value=='dashboard') { echo 'selected'; } ?>><?php _e('Dashboard','wp-notification'); ?></option>
		<option value="edit-post" <?php if ($anl_value=='edit-post') { echo 'selected'; } ?>><?php _e('Posts','wp-notification'); ?></option>
		<option value="post" <?php if ($anl_value=='post') { echo 'selected'; } ?>><?php _e('Add New','wp-notification'); ?></option>
		<option value="upload" <?php if ($anl_value=='upload') { echo 'selected'; } ?>><?php _e('Media','wp-notification'); ?></option>
		<option value="media" <?php if ($anl_value=='media') { echo 'selected'; } ?>><?php _e('Add New','wp-notification'); ?></option>
		<option value="edit-comments" <?php if ($anl_value=='edit-comments') { echo 'selected'; } ?>><?php _e('Comments','wp-notification'); ?></option>
		<option value="profile" <?php if ($anl_value=='profile') { echo 'selected'; } ?>><?php _e('Profile','wp-notification'); ?></option>7
		<option value="tools" <?php if ($anl_value=='tools') { echo 'selected'; } ?>><?php _e('Tools','wp-notification'); ?></option>
	</select>
	</label></div>
	<div><label for="author_notices_notification_type">
	<?php _e('Notification type?','wp-notification'); ?> <select style="width:300px" name="author_notices_notification_type" id="author_notices_notification_type">
		<?php $annt_value = get_option('author_notices_notification_type'); ?>
		<option value="0" <?php if ($annt_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="error" <?php if ($annt_value=='error') { echo 'selected'; } ?>><?php _e('Error','wp-notification'); ?></option>
		<option value="warning" <?php if ($annt_value=='warning') { echo 'selected'; } ?>><?php _e('Warning','wp-notification'); ?></option>
		<option value="success" <?php if ($annt_value=='success') { echo 'selected'; } ?>><?php _e('Success','wp-notification'); ?></option>
		<option value="info" <?php if ($annt_value=='info') { echo 'selected'; } ?>><?php _e('Info','wp-notification'); ?></option>
	</select> 
	</label></div>
	<div><label for="author_notices_hide">
	<input type="checkbox" name="author_notices_hide" id="author_notices_hide" value="1" <?php checked(get_option('author_notices_hide')); ?>"><?php _e('Display "Hide" button?','wp-notification') ?>
	</label></div>
	<div><label for="author_notices_admin_bar">
	<input type="checkbox" name="author_notices_admin_bar" id="author_notices_admin_bar" value="1" <?php checked(get_option('author_notices_admin_bar')); ?>"><?php _e('Show "New notification" menu in administrator bar','wp-notification') ?>
	</label></div>
	<?php
}

function contributor_notices_settings_page() {
	settings_fields('contributor_notice_settings_page');
	do_settings_sections('contributor_notice_settings_page');
	?>
    <h3><?php _e('Contributor notification','wp-notification') ?></h3>
	<?php
	$contributor_noice = stripslashes(get_option("contributor_notices_text"));
	wp_editor( $contributor_noice, 'contributor_notices_text', $settings = array('textarea_name' => 'contributor_notices_text','textarea_rows' => '5') );
	?>
	<div><label for="contributor_notices_location">
	<?php _e('Where to display?','wp-notification'); ?><select style="width:300px" name="contributor_notices_location" id="contributor_notices_location">
		<?php $cnl_value = get_option('contributor_notices_location'); ?>
		<option value="0" <?php if ($cnl_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="all" <?php if ($cnl_value=='all') { echo 'selected'; } ?>><?php _e('All Page','wp-notification'); ?></option>
		<option value="dashboard" <?php if ($cnl_value=='dashboard') { echo 'selected'; } ?>><?php _e('Dashboard','wp-notification'); ?></option>
		<option value="edit-post" <?php if ($cnl_value=='edit-post') { echo 'selected'; } ?>><?php _e('Posts','wp-notification'); ?></option>
		<option value="post" <?php if ($cnl_value=='post') { echo 'selected'; } ?>><?php _e('Add New','wp-notification'); ?></option>
		<option value="edit-comments" <?php if ($cnl_value=='edit-comments') { echo 'selected'; } ?>><?php _e('Comments','wp-notification'); ?></option>
		<option value="profile" <?php if ($cnl_value=='profile') { echo 'selected'; } ?>><?php _e('Profile','wp-notification'); ?></option>7
		<option value="tools <?php if ($cnl_value=='tools') { echo 'selected'; } ?>"><?php _e('Tools','wp-notification'); ?></option>
	</select> 
	</label></div>
	<div><label for="contributor_notices_notification_type">
	<?php _e('Notification type?','wp-notification'); ?> <select style="width:300px" name="contributor_notices_notification_type" id="contributor_notices_notification_type">
		<?php $cnnt_value = get_option('contributor_notices_notification_type'); ?>
		<option value="0" <?php if ($cnnt_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="error" <?php if ($cnnt_value=='error') { echo 'selected'; } ?>><?php _e('Error','wp-notification'); ?></option>
		<option value="warning" <?php if ($cnnt_value=='warning') { echo 'selected'; } ?>><?php _e('Warning','wp-notification'); ?></option>
		<option value="success" <?php if ($cnnt_value=='success') { echo 'selected'; } ?>><?php _e('Success','wp-notification'); ?></option>
		<option value="info" <?php if ($cnnt_value=='info') { echo 'selected'; } ?>><?php _e('Info','wp-notification'); ?></option>
	</select> 
	</label></div>
	<div><label for="contributor_notices_hide">
	<input type="checkbox" name="contributor_notices_hide" id="contributor_notices_hide" value="1" <?php checked(get_option('contributor_notices_hide')); ?>"><?php _e('Display "Hide" button?','wp-notification') ?>
	</label></div>
	<div><label for="contributor_notices_admin_bar">
	<input type="checkbox" name="contributor_notices_admin_bar" id="contributor_notices_admin_bar" value="1" <?php checked(get_option('contributor_notices_admin_bar')); ?>"><?php _e('Show "New notification" menu in administrator bar','wp-notification') ?>
	</label></div>
	<?php
}

function subscriber_notices_settings_page() {
	settings_fields('subscriber_notice_settings_page');
	do_settings_sections('subscriber_notice_settings_page');
	?>
    <h3><?php _e('Subscriber notice','wp-notification') ?></h3>
	<?php
	$subscriber_noice = stripslashes(get_option("subscriber_notices_text"));
	wp_editor( $subscriber_noice, 'subscriber_notices_text', $settings = array('textarea_name' => 'subscriber_notices_text','textarea_rows' => '5') );
	?>
	<div><label for="subscriber_notices_location">
	<?php _e('Where to display?','wp-notification'); ?><select style="width:300px" name="subscriber_notices_location" id="subscriber_notices_location">
		<?php $snl_value = get_option('subscriber_notices_location'); ?>
		<option value="0" <?php if ($snl_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="all" <?php if ($snl_value=='all') { echo 'selected'; } ?>><?php _e('All Page','wp-notification'); ?></option>
		<option value="dashboard" <?php if ($snl_value=='dashboard') { echo 'selected'; } ?>><?php _e('Dashboard','wp-notification'); ?></option>
		<option value="profile" <?php if ($snl_value=='profile') { echo 'selected'; } ?>><?php _e('Profile','wp-notification'); ?></option>7
	</select> 
	</label></div>
	<div><label for="subscriber_notices_notification_type">
	<?php _e('Notification type?','wp-notification'); ?> <select style="width:300px" name="subscriber_notices_notification_type" id="subscriber_notices_notification_type">
		<?php $snnt_value = get_option('subscriber_notices_notification_type'); ?>
		<option value="0" <?php if ($snnt_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="error" <?php if ($snnt_value=='error') { echo 'selected'; } ?>><?php _e('Error','wp-notification'); ?></option>
		<option value="warning" <?php if ($snnt_value=='error') { echo 'selected'; } ?>><?php _e('Warning','wp-notification'); ?></option>
		<option value="success" <?php if ($snnt_value=='success') { echo 'selected'; } ?>><?php _e('Success','wp-notification'); ?></option>
		<option value="info" <?php if ($snnt_value=='info') { echo 'selected'; } ?>><?php _e('Info','wp-notification'); ?></option>
	</select> 
	</label></div>
	<div><label for="subscriber_notices_hide">
	<input type="checkbox" name="subscriber_notices_hide" id="subscriber_notices_hide" value="1" <?php checked(get_option('subscriber_notices_hide')); ?>"><?php _e('Display "Hide" button?','wp-notification') ?>
	</label></div>
	<div><label for="subscriber_notices_admin_bar">
	<input type="checkbox" name="subscriber_notices_admin_bar" id="subscriber_notices_admin_bar" value="1" <?php checked(get_option('subscriber_notices_admin_bar')); ?>"><?php _e('Show "New notification" menu in administrator bar','wp-notification') ?>
	</label></div>
	<?php
}

function custom_notices_settings_page() {
	settings_fields('custom_notice_settings_page');
	do_settings_sections('custom_notice_settings_page');
	?>
    <h3><?php _e('Custom notice','wp-notification') ?></h3>
	<?php
	$custom_notice = stripslashes(get_option("custom_notices_text"));
	wp_editor( $custom_notice, 'custom_notices_text', $settings = array('textarea_name' => 'custom_notices_text','textarea_rows' => '5') );
	?>
	<div><label for="custom_notices_location">
	<?php _e('Where to display?','wp-notification'); ?> <select style="width:300px" name="custom_notices_location" id="custom_notices_location">
		<?php $cnl_value = get_option('custom_notices_location'); ?>
		<option value="0" <?php if ($cnl_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="all" <?php if ($cnl_value=='all') { echo 'selected'; } ?>><?php _e('All Page','wp-notification'); ?></option>
		<option value="dashboard" <?php if ($cnl_value=='dashboard') { echo 'selected'; } ?>><?php _e('Dashboard','wp-notification'); ?></option>
	</select> 
	</label></div>
	<div><label for="custom_notices_notification_type">
	<?php _e('Notification type?','wp-notification'); ?> <select style="width:300px" name="custom_notices_notification_type" id="custom_notices_notification_type">
		<?php $cnnt_value = get_option('custom_notices_notification_type'); ?>
		<option value="0" <?php if ($cnnt_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<option value="error" <?php if ($cnnt_value=='error') { echo 'selected'; } ?>><?php _e('Error','wp-notification'); ?></option>
		<option value="warning" <?php if ($cnnt_value=='warning') { echo 'selected'; } ?>><?php _e('Warning','wp-notification'); ?></option>
		<option value="success" <?php if ($cnnt_value=='success') { echo 'selected'; } ?>><?php _e('Success','wp-notification'); ?></option>
		<option value="info" <?php if ($cnnt_value=='info') { echo 'selected'; } ?>><?php _e('Info','wp-notification'); ?></option>
	</select> 
	</label></div>
	<div><label for="custom_notices_login">
	<?php _e('User name?','wp-notification'); ?> <select style="width:300px" name="custom_notices_login" id="custom_notices_login">
		<?php $cnl1_value = get_option('custom_notices_login'); ?>
		<option value="0" <?php if ($cnl1_value=='0') { echo 'selected'; } ?>><?php _e('Please select one!','wp-notification'); ?></option>
		<?php
			foreach(get_users('orderby=login') as $user){
				echo ( '<option value="'.$user->user_login.'" ' );
				echo ( ( $user->user_login == $cnl1_value ) ? 'selected' : '' );
				echo ( '>'.ucfirst($user->user_login).'</option>' );
			}
		?>
	</select>
	</label></div>
	<div><label for="custom_notices_hide">
	<input type="checkbox" name="custom_notices_hide" id="custom_notices_hide" value="1" <?php checked(get_option('custom_notices_hide')); ?>"><?php _e('Display "Hide" button?','wp-notification') ?>
	</label></div>
	<div><label for="custom_notices_admin_bar">
	<input type="checkbox" name="custom_notices_admin_bar" id="custom_notices_admin_bar" value="1" <?php checked(get_option('custom_notices_admin_bar')); ?>"><?php _e('Show "New notification" menu in administrator bar','wp-notification') ?>
	</label></div>
	<?php
}

function wp_notification_settings() {
?>
	<div style="display:table;width:100%">
		<div style="display:table-cell"><h2><?php _e('WP-Notification settings','wp-notification') ?></h2></div>
		<div style="display:table-cell;text-align:right"><strong><?php _e('Show custom notifications on the dashboard','wp-notification') ?></strong></div>
	</div>    
	<form method="post" action="options.php">
	<?php
	options_save();
	$active_notice_tab = isset($_GET['tab']) ? $_GET['tab'] : 'administrator';
	if(isset($_GET['tab']))
		$active_notice_tab = $_GET['tab'];
	?>
	<h2 class="nav-tab-wrapper">
	<a href="?page=wp_notification_settings&tab=administrator" class="nav-tab"><?php _e("Administrator","wp-notification") ?></a>
	<a href="?page=wp_notification_settings&tab=author" class="nav-tab"><?php _e("Author","wp-notification") ?></a>
	<a href="?page=wp_notification_settings&tab=editor" class="nav-tab"><?php _e("Editor","wp-notification") ?></a>
	<a href="?page=wp_notification_settings&tab=contributor" class="nav-tab"><?php _e("Contributor","wp-notification") ?></a>
	<a href="?page=wp_notification_settings&tab=subscriber" class="nav-tab"><?php _e("Subscriber","wp-notification") ?></a>
	<a href="?page=wp_notification_settings&tab=custom" class="nav-tab"><?php _e("Custom","wp-notification") ?></a>
	</h2>
	<?php
	if($active_notice_tab == 'administrator') { 
		adminiszrator_notices_settings_page();
	} if($active_notice_tab == 'author') {
		author_notices_settings_page();
	} if($active_notice_tab == 'editor') {
		editor_notices_settings_page();
	} if($active_notice_tab == 'contributor') {
		contributor_notices_settings_page();
	} if($active_notice_tab == 'subscriber') {
		subscriber_notices_settings_page();
	} if($active_notice_tab == 'custom') {
		custom_notices_settings_page();
	}
	notices_footer();
}

function wp_notification_submenu() {
	add_submenu_page('themes.php',__('WP-Notification', 'wp-notification'),__('WP-Notification', 'wp-notification'),'administrator','wp_notification_settings','wp_notification_settings');}

function wp_notification_init() {
    register_setting('administrator_notice_settings_page','administrator_notices_text');
	register_setting('administrator_notice_settings_page','administrator_notices_notification_type');
	register_setting('administrator_notice_settings_page','administrator_notices_hide');
	register_setting('administrator_notice_settings_page','administrator_notices_location');
	register_setting('administrator_notice_settings_page','administrator_notices_admin_bar');
    register_setting('author_notice_settings_page','author_notices_text');
	register_setting('author_notice_settings_page','author_notices_notification_type');
	register_setting('author_notice_settings_page','author_notices_hide');
	register_setting('author_notice_settings_page','author_notices_location');
	register_setting('author_notice_settings_page','author_notices_admin_bar');
    register_setting('editor_notice_settings_page','editor_notices_text');
	register_setting('editor_notice_settings_page','editor_notices_notification_type');
	register_setting('editor_notice_settings_page','editor_notices_hide');
	register_setting('editor_notice_settings_page','editor_notices_location');
	register_setting('editor_notice_settings_page','editor_notices_admin_bar');
    register_setting('contributor_notice_settings_page','contributor_notices_text');
	register_setting('contributor_notice_settings_page','contributor_notices_notification_type');
	register_setting('contributor_notice_settings_page','contributor_notices_hide');
	register_setting('contributor_notice_settings_page','contributor_notices_location');
	register_setting('contributor_notice_settings_page','contributor_notices_admin_bar');
    register_setting('subscriber_notice_settings_page','subscriber_notices_text');
	register_setting('subscriber_notice_settings_page','subscriber_notices_notification_type');
	register_setting('subscriber_notice_settings_page','subscriber_notices_hide');
	register_setting('subscriber_notice_settings_page','subscriber_notices_location');
	register_setting('subscriber_notice_settings_page','subscriber_notices_admin_bar');
    register_setting('custom_notice_settings_page','custom_notices_text');
	register_setting('custom_notice_settings_page','custom_notices_notification_type');
	register_setting('custom_notice_settings_page','custom_notices_hide');
	register_setting('custom_notice_settings_page','custom_notices_location');
	register_setting('custom_notice_settings_page','custom_notices_login');
	register_setting('custom_notice_settings_page','custom_notices_admin_bar');
}

function activate_wp_notification() {
	add_option('administrator_notices_text','','','');
	add_option('administrator_notices_notification_type','0','','');
	add_option('administrator_notices_hide','0','','');
	add_option('administrator_notices_location','0','','');
	add_option('administrator_notices_admin_bar','0','','');
	add_option('author_notices_text','','','');
	add_option('author_notices_notification_type','0','','');
	add_option('author_notices_hide','0','','');
	add_option('author_notices_location','0','','');
	add_option('author_notices_admin_bar','0','','');
	add_option('editor_notices_text','','','');
	add_option('editor_notices_notification_type','0','','');
	add_option('editor_notices_hide','0','','');
	add_option('editor_notices_location','0','','');
	add_option('editor_notices_admin_bar','0','','');
	add_option('contributor_notices_text','','','');
	add_option('contributor_notices_notification_type','0','','');
	add_option('contributor_notices_hide','0','','');
	add_option('contributor_notices_location','0','','');
	add_option('contributor_notices_admin_bar','0','','');
	add_option('subscriber_notices_text','','','');
	add_option('subscriber_notices_notification_type','0','','');
	add_option('subscriber_notices_hide','0','','');
	add_option('subscriber_notices_location','0','','');
	add_option('subscriber_notices_admin_bar','0','','');
	add_option('custom_notices_text','','','');
	add_option('custom_notices_notification_type','0','','');
	add_option('custom_notices_hide','0','','');
	add_option('custom_notices_location','0','','');
	add_option('custom_notices_login','0','','');
	add_option('custom_notices_admin_bar','0','','');
}

function delete_wp_notification() {
    delete_option('administrator_notices_text');
	delete_option('administrator_notices_notification_type');
	delete_option('administrator_notices_hide');
	delete_option('administrator_notices_location');
	delete_option('administrator_notices_admin_bar');
    delete_option('author_notices_text');
	delete_option('author_notices_notification_type');
	delete_option('author_notices_hide');
	delete_option('author_notices_location');
	delete_option('author_notices_admin_bar');
    delete_option('editor_notices_text');
	delete_option('editor_notices_notification_type');
	delete_option('editor_notices_hide');
	delete_option('editor_notices_location');
	delete_option('editor_notices_admin_bar');
    delete_option('contributor_notices_text');
	delete_option('contributor_notices_notification_type');
	delete_option('contributor_notices_hide');
	delete_option('contributor_notices_location');
	delete_option('contributor_notices_admin_bar');
    delete_option('subscriber_notices_text');
	delete_option('subscriber_notices_notification_type');
	delete_option('subscriber_notices_hide');
	delete_option('subscriber_notices_location');
	delete_option('subscriber_notices_admin_bar');
    delete_option('custom_notices_text');
	delete_option('custom_notices_notification_type');
	delete_option('custom_notices_hide');
	delete_option('custom_notices_location');
	delete_option('custom_notices_login');
	delete_option('custom_notices_admin_bar');
}

add_action('admin_bar_menu','wp_notification_menu'); 
add_action('admin_notices','wp_notification_core'); 
add_action('admin_menu','wp_notification_submenu');
add_action('admin_init','wp_notification_init');
register_activation_hook( __FILE__, 'activate_wp_notification' );
register_deactivation_hook( __FILE__, 'delete_wp_notification' );
?>