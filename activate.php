<?php

// see if we have any saved settings for this plugin
	$anon_guid = elgg_get_plugin_setting('anon_guid','speak_freely');
	$recaptcha = elgg_get_plugin_setting('recaptcha','speak_freely');
	$public_key = elgg_get_plugin_setting('public_key','speak_freely');
	$private_key = elgg_get_plugin_setting('private_key','speak_freely');
  $usessl = elgg_get_plugin_setting('usessl', 'speak_freely');
	
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
  
  if (empty($usessl)) {
    elgg_set_plugin_setting('usessl', 'no', 'speak_freely');
  }
	
	//get all of the information on our fake user
	$user = get_user($anon_guid);
	
	if(!$user){ // our user is missing, make new one
		$anon_guid = set_anonymous_user();
		//save our fake users guid for the plugin to access
		elgg_set_plugin_setting('anon_guid', $anon_guid, 'speak_freely');
	}