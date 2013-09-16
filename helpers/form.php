<?php

/**
 * Helper functions for generating common forms
 */

$simple_form_id = 0;

/**
 * Creates a form for performing a action over post that doesn't 
 * require any input from the user
 * @param $options Options to the submit-button
 * @param $options['form'] Options to the form
 */
function simple_action($action, $text, $data=array(), $options=array()) {
	global $simple_form_id;

	if($data == null) $data = array();

	$form_options = array(
		'action' => $action,
		'layout' => 'unbuffered',
		'class' => 'form-inline'
	);

	if(isset($options['form'])) {
		$form_options = array_merge($form_options , $options['form']);
		unset($options['form']);
	}


	Form::from_array("action_form_$simple_form_id", $data, function($f) use ($text, $data, $options) {
		foreach(array_keys($data) as $k) {
			$f->hidden_field($k);
		}

		$submit_options = array_merge(array('class' => 'link-submit'), $options);

		$f->submit($text, null, $submit_options);
	}, $form_options);
	++$simple_form_id;
}
