<?php
/**
 *Comment functionality
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Matt Beckett
 * @copyright University of Athabasca 2011
 */

/**
 *
 */
include_once 'lib/functions.php';

function speak_freely_init() {

	// Load system configuration
	global $CONFIG;
	
	// Extend system CSS with our own styles
	elgg_extend_view('page/elements/head','speak_freely/metatags', 1000);

	// Load the language file
	register_translations($CONFIG->pluginspath . "speak_freely/languages/");

	// see if we have any saved settings for this plugin
	$anon_guid = elgg_get_plugin_setting('anon_guid','speak_freely');
	$recaptcha = elgg_get_plugin_setting('recaptcha','speak_freely');
	$public_key = elgg_get_plugin_setting('public_key','speak_freely');
	$private_key = elgg_get_plugin_setting('private_key','speak_freely');
	
	//if we don't have a public key, set default
	if(empty($public_key)){
		elgg_set_plugin_setting('public_key', '6LfviMMSAAAAACXdnUPHLHheWkAYIJ-m-8QAOy6R', 'speak_freely');
	}
	
	//if we don't have a private key, set default
	
	if(empty($private_key)){
		elgg_set_plugin_setting('private_key', '6LfviMMSAAAAAIi_StJYyPXfRggSR9nEKPqkVqvU', 'speak_freely');
	}
	
	// if we don't have a setting for recaptcha, default to yes, better to have it than not if unsure
	if(empty($recaptcha)){
		elgg_set_plugin_setting('recaptcha', 'yes', 'speak_freely');	
	}
	
	//get all of the information on our fake user
	$user = get_user($anon_guid);
	
	if(!$user){ // our user is missing, make new one
		$anon_guid = set_anonymous_user();
		//save our fake users guid for the plugin to access
		elgg_set_plugin_setting('anon_guid', $anon_guid, 'speak_freely');
	}

elgg_register_page_handler('speak_freely','speak_freely_page_handler');
elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'speak_freely_hover_menu', 1000);

}

function speak_freely_pagesetup() {

	if (elgg_get_context() == 'admin' && elgg_is_admin_logged_in()) {
	  $item = new ElggMenuItem('speak_freely', elgg_echo('speak_freely:settings'), elgg_get_site_url() . 'speak_freely/edit.php');
	  $item->setParent('settings');
	  elgg_register_menu_item('page', $item);
	}
}

function speak_freely_page_handler()
{
	global $CONFIG;

	if(include(elgg_get_plugins_path() . "speak_freely/pages/edit.php")){
	  return TRUE;
	}
	
  return FALSE;
}


elgg_register_event_handler('init','system','speak_freely_init');
elgg_register_event_handler('pagesetup','system','speak_freely_pagesetup');

//register action to save our anonymous comments
elgg_register_action("comments/anon_add", elgg_get_plugins_path() . "speak_freely/actions/comments/anon_add.php", 'public');

//register action to save our plugin settings
elgg_register_action("speak_freely_settings", elgg_get_plugins_path() . "speak_freely/actions/speak_freely_settings.php", 'admin');


// extend the form view to present a comment form to a logged out user
elgg_extend_view('page/elements/comments', 'comments/forms/speak_freely_post_edit', 1000);

//add override for anonymous user profile
elgg_extend_view('profile/details', 'profile/speak_freely_pre_userdetails', 501);
?>