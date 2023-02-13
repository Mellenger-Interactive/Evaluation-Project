<?php
/**
 * @file
 * Administrative interface for file type configuration.
 */

/**
 * Displays the file type admin overview page.
 */
//Create a list of headers for your Html table (see Drupal 7 docs for theme_table here
function federation_registration_type_list_page() {
	$header = array(
		array('data' => 'Registration Type', 'field' => 'name', 'sort' => 'asc'),
		array('data' => 'Team?', 'field' => 'for_team'),
		array('data' => 'Required Participants', 'field' => 'player_count'),
		array('data' => 'Gender Mix', 'field' => 'gender_mix'),
		array('data' => 'Restricted To', 'field' => 'required_registrant_type'),
		array('data' => 'Status', 'field' => 'is_active', 'sort' => 'asc'),
		);
	//Create the Sql query. This uses various parts of Drupal 7's new DBTNG database abstraction layer.

	$query = db_select('vul_registration_type', 'vrt')
				->extend('PagerDefault') 	//Pager Extender
					->limit(20)				//10 results per page
				->extend('TableSort')		//Sorting Extender
					->orderByHeader($header)//Field to sort on is picked from $header
				->fields ('vrt', array (
					'id',
					'name',
					'is_active',
					'for_team',
					'player_count',
					'gender_mix',
					'required_registrant_type'
				));

	$results = $query
				->execute();

	$rows = array();
	foreach ($results as $registration_type) {

		$rows[] = array(
					'data' => array(
						  l($registration_type->name, 'admin/federation/registration-type/'. $registration_type->id.'/edit'),
						  t(($registration_type->for_team?'yes':'no')),
						  t($registration_type->player_count),
						  t($registration_type->gender_mix),
						  ($registration_type->required_registrant_type == 4)?('Parent'):(($registration_type->required_registrant_type == 3)?('Youth'):(($registration_type->required_registrant_type == 2)?('Player'):('None'))),
						  ($registration_type->is_active)?('Active'):('Inactive')
					)
		);
	}

	//Theme the html table
	$html = theme('table',
				array(
					'header' => $header,
					'rows'=>$rows,
					'caption' => 'Federation Registration Types',	//Optional Caption for the table
					'sticky' => TRUE,						//Optional to indicate whether the table headers should be sticky
					'empty' => 'No registration type created...',		//Optional empty text for the table if resultset is empty
				)
			);

	//Append pager
	$html .= theme('pager',
				array(
					'tags' => array()
				)
	);

	return ($html);
}


/**
* Form builder; Create and display the field
* settings form.
*/
function registration_type_form($form, &$form_state, $sid = NULL) {
	if($sid) {
		$myRegistrationType = registration_type_load($sid);
	}

	// Registration Type Name
	$form['federation_registration_type_name'] = array(
  	'#type' => 'textfield',
  	'#title' => t('Name'),
  	'#description' => t('The name of the registration type'),
  	'#size' => 50,
  	'#maxlength' => 120,
  	'#required' => TRUE,
  	'#default_value' => (isset($myRegistrationType->name)?$myRegistrationType->name:''),
	);

	$form['federation_registration_type_is_active'] = array(
			'#type' => 'radios',
			'#title' => 'Status',
			'#description' => t('Set to inactive to prevent use in new league registration periods'),
			'#required' => true,
			'#options' => array('0' => 'Inactive', '1' => 'Active'),
			'#default_value' => (isset($myRegistrationType->is_active)?$myRegistrationType->is_active:1),
		);

	$form['federation_registration_type_display_name'] = array(
  	'#type' => 'textfield',
  	'#title' => t('Display Text - Participants'),
  	'#description' => t('The name displayed to participants when they register'),
  	'#size' => 50,
  	'#maxlength' => 120,
  	'#default_value' => (isset($myRegistrationType->display_name)?$myRegistrationType->display_name:''),

	);

  $form['federation_registration_type_display_name_parents'] = array(
    '#type' => 'textfield',
    '#title' => t('Display Text - Parent'),
    '#description' => t('The name displayed to parents when they are registering their child'),
    '#size' => 50,
    '#maxlength' => 120,
    '#default_value' => isset($myRegistrationType->display_name_parents) ? $myRegistrationType->display_name_parents : '',
  );

	$form['federation_registration_type_for_team'] = array(
  	'#type' => 'checkbox',
  	'#title' => t('Team Type?'),
  	'#description' => t('The name of the registration type'),
  	'#size' => 50,
  	'#maxlength' => 120,
  	'#required' => TRUE,
  	'#default_value' => (isset($myRegistrationType->for_team)?$myRegistrationType->for_team:0),
	);

	$checked = isset($form_state['values']['federation_registration_type_for_team'])?$form_state['values']['federation_registration_type_for_team']:(isset($myRegistrationType->for_team)?$myRegistrationType->for_team:0);
	$form['federation_registration_type_for_team'] = array(
	'#type' => 'checkbox',
	'#title' => t('Team Type?'),
	'#default_value' => $checked,
	);

	$checked2 = isset($form_state['values']['federation_registration_type_is_returning'])?$form_state['values']['federation_registration_type_is_returning']:(isset($myRegistrationType->is_returning)?$myRegistrationType->is_returning:0);

	$form['federation_registration_type_is_returning'] = array(
	'#type' => 'checkbox',
	'#title' => t('Returning Only?'),
	'#default_value' => $checked2,
	'#states' => array(
		'visible' => array(
			'input[name="federation_registration_type_for_team"]' => array('checked' => true)
			)
		)
	);
	$form['federation_registration_type_player_count'] = array(
		'#type' => 'textfield',
		'#title' => t('Required Participants'),
		'#default_value' => (isset($myRegistrationType->player_count)?$myRegistrationType->player_count:1),
		'#attributes' => array('class' => array('field-numeric-small')),
	);
	$gender_codes = vul_gender_codes(true);
	$code_array = array();
	foreach($gender_codes as $gc) {
    	$code_array[] = $gc->gender_code;
	}
	$code_array = natural_language_join($code_array,'or');
	$form['federation_registration_type_gender_mix'] = array(
		'#type' => 'textfield',
		'#title' => t('Required Gender Mix'),
		'#size' => 20,
		'#maxlength' => 20,
		'#default_value' => (isset($myRegistrationType->gender_mix)?$myRegistrationType->gender_mix:''),
		'#description' => t('The required gender mix for this registration type (using Gender Match not Gender identity). Based on Gender Codes ('.$code_array.'). To require at least one (F)emale, specify: F. To require Male & Female, specify F-M. Separate allowed options with commas (For instance, a trio could be specified as F OR as M-F-F,F-F-F,M-M-F). These rules will be evaluated during registration.'),
		'#states' => array(
		'visible' => array(
			'input[name="federation_registration_type_for_team"]' => array('checked' => false)
			)
		)
	);

	$form['federation_registration_type_required_registrant_type'] = array(
				'#type' => 'radios',
				'#title' => 'Restrict registration to the following type?',
				'#required' => true,
				'#options' => array('0' => 'No Restriction', '2' => 'Adult Player', '3' => 'Youth Player',  '4' => 'Parent'),
				'#default_value' => (isset($form_state['values'])?($form_state['values']['federation_registration_type_required_registrant_type']):(0)),
			);


	// the ID
	$form['federation_registration_type_id'] = array(
	'#type' => 'hidden',
	'#default_value' => ((isset($myRegistrationType->id))?$myRegistrationType->id:''),
	);

	// Submit button
	$form['submit'] = array(
	'#type' => 'submit',
	'#value' => t('Save settings'),
	);

	return $form;
}


function registration_type_form_validate($form,&$form_state) {

    // we're going to sort the gender_mix
    if(strlen($form_state['values']['federation_registration_type_gender_mix'])) {
        $gender_array = explode(',', strtoupper($form_state['values']['federation_registration_type_gender_mix']));
        $fixed_array = array();
        foreach($gender_array as $mix) {
            $mix_array = explode('-',trim($mix));
            asort($mix_array);
            $fixed_array[] = implode('-',$mix_array);
        }
        $form_state['values']['federation_registration_type_gender_mix'] = implode(',',$fixed_array);
    }
}


function registration_type_form_submit($form, $form_state){
	$values = array(
		'name' => $form_state['values']['federation_registration_type_name'],
		'is_active' => $form_state['values']['federation_registration_type_is_active'],
		'display_name' => $form_state['values']['federation_registration_type_display_name'],
    'display_name_parents' => $form_state['values']['federation_registration_type_display_name_parents'],
		'for_team' => $form_state['values']['federation_registration_type_for_team'],
		'is_returning' => $form_state['values']['federation_registration_type_is_returning'],
		'player_count' => $form_state['values']['federation_registration_type_player_count'],
		'gender_mix' => $form_state['values']['federation_registration_type_gender_mix'],
		'required_registrant_type' => $form_state['values']['federation_registration_type_required_registrant_type'],
	);
	if($form_state['values']['federation_registration_type_id']) {
		$update = db_update('vul_registration_type')
			-> fields($values)
			-> condition('id',$form_state['values']['federation_registration_type_id'])
			->execute();
	} else {
		$insert = db_insert('vul_registration_type')
		-> fields($values)
		->execute();
	}

	drupal_set_message(t('Settings have been saved'));
}
