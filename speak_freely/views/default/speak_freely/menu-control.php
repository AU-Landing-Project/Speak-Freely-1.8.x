<?php

/**
 * Menu group
 *
 * @uses $vars['items']
 * @uses $vars['class']
 * @uses $vars['name']
 * @uses $vars['section']
 * @uses $vars['show_section_headers']
 */

$links = $vars['items'];

// need to test if our menu is the dropdown from the anonymous user profile image
$anon_guid = get_plugin_setting('anon_guid','speak_freely');
$user = get_user($anon_guid);


//parse URL and look for our username
for($i=0; $i<count($links); $i++){
	
	// don't want to call methods on it after this
	if(strpos($vars['items'][$i]->getContent(), "blog/owner/$user->username")
		|| strpos($vars['items'][$i]->getContent(), "profile/$user->username")
		|| strpos($vars['items'][$i]->getContent(), "action/friends/add?friend=$anon_guid")
		|| strpos($vars['items'][$i]->getContent(), "profile%2F$user->username")
		|| strpos($vars['items'][$i]->getContent(), "messages/compose?send_to=$anon_guid")
		|| strpos($vars['items'][$i]->getContent(), "action/admin/user/ban?guid=$anon_guid")
		|| strpos($vars['items'][$i]->getContent(), "action/admin/user/delete?guid=$anon_guid")
		// and here we're checking for the "Embed" link on the comment form when not logged in
		|| (!isloggedin() && strpos($vars['items'][$i]->getContent(), "embed?"))
	){
		unset($vars['items'][$i]);
	}
	
}

array_values($vars['items']);
//var_dump($vars['items']);