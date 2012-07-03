<?php

/**
 *
 * Presents a comment form to a non-logged in person
 */
	// there is some bug with the extended view where this will be called multiple times
	// use a token counter to make sure that form is only displayed the first time

if (isset($vars['entity']) && !elgg_is_logged_in()) {
  
  if (elgg_is_sticky_form('speak_freely')) {
	extract(elgg_get_sticky_values('speak_freely'));
	elgg_clear_sticky_form('speak_freely');
  }
	
	if($speakfreelyformcounter != 1){  //first time, create the form..
		$speakfreelyformcounter = 1; // now this variable is set, so the form shouldn't replicate
		
		require_once(elgg_get_plugins_path() . 'speak_freely/lib/recaptchalib.php');
		$publickey = elgg_get_plugin_setting('public_key', 'speak_freely'); // you got this from the signup page

		$form_body = "<div class=\"contentWrapper\">";
		$form_body .= "<label>" . elgg_echo('speak_freely:name') . "<br> " . elgg_view('input/text', array('name' => 'anon_name', 'value' => $anon_name, 'id' => 'speak_freely_name_field')) . "</label> (" . elgg_echo('speak_freely:required') . ")<br><br>";
		$form_body .= "<p class='longtext_editarea'><label>".elgg_echo("generic_comments:text")."<br />" . elgg_view('input/longtext',array('name' => 'generic_comment', 'value' => $generic_comment)) . "</label></p>";
		$form_body .= "<p>" . elgg_view('input/hidden', array('name' => 'entity_guid', 'value' => $vars['entity']->getGUID()));

		// if we have set recaptcha then display the output
		if(elgg_get_plugin_setting('recaptcha','speak_freely') == "yes"){
			$recaptcha_style = elgg_get_plugin_setting('recaptcha_style','speak_freely');
			if(empty($recaptcha_style)){
				$recaptcha_style = "red"; // set default	
			} 
 
			$form_body .= "<script type=\"text/javascript\">";
			$form_body .= "var RecaptchaOptions = {";
			$form_body .= "theme : '$recaptcha_style'";
			$form_body .= "};";
			$form_body .= "</script>"; 
			
      $usessl = elgg_get_plugin_setting('usessl', 'speak_freely');
      $ssl = FALSE;
      if ($usessl == 'yes') {
        $ssl = TRUE;
      }
			$form_body .= recaptcha_get_html($publickey, '', $ssl);
		}

		$form_body .= elgg_view('input/submit', array('value' => elgg_echo("speak_freely:post:comment"))) . "</p></div>";

		// output the form
		echo elgg_view('input/form', array('body' => $form_body, 'action' => "{$vars['url']}action/comments/anon_add"));
	}
}