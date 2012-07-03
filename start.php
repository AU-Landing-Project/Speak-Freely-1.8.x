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
	
	// Extend system CSS with our own styles
	elgg_extend_view('css/elgg','speak_freely/css');
  
  // extend the form view to present a comment form to a logged out user
  elgg_extend_view('page/elements/comments', 'comments/forms/speak_freely_post_edit', 1000);

  //add override for anonymous user profile
  elgg_extend_view('profile/details', 'profile/speak_freely_pre_userdetails', 501);

  elgg_register_page_handler('speak_freely','speak_freely_page_handler');
  elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'speak_freely_hover_menu', 1000);
  elgg_register_plugin_hook_handler('email', 'system', 'speak_freely_anon_email');
  
  //register action to save our anonymous comments
  elgg_register_action("comments/anon_add", elgg_get_plugins_path() . "speak_freely/actions/comments/anon_add.php", 'public');

  //register action to save our plugin settings
  elgg_register_action("speak_freely_settings", elgg_get_plugins_path() . "speak_freely/actions/speak_freely_settings.php", 'admin');
}

function speak_freely_pagesetup() {

	if (elgg_get_context() == 'admin' && elgg_is_admin_logged_in()) {
    elgg_register_menu_item('page', array(
         'name' => 'speak_freely',
         'href' => elgg_get_site_url() . 'speak_freely/edit',
         'text' => elgg_echo('speak_freely:settings'),
         'parent_name' => 'settings',
         'section' => 'configure',
     ));
	}
}

function speak_freely_page_handler()
{

	if(include(elgg_get_plugins_path() . "speak_freely/pages/edit.php")){
	  return TRUE;
	}
	
  return FALSE;
}


elgg_register_event_handler('init','system','speak_freely_init');
elgg_register_event_handler('pagesetup','system','speak_freely_pagesetup');
