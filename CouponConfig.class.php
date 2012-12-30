<?php

class CouponConfig extends ConfigMgr
{
	function database()
	{
		$config = parent::database();

		$config->database = 'op_coupon';
		$config->user     = 'op_coupon';
		
		return $config;
	}
	
	function form_test()
	{
		// I create form config.
		$form_config = new Config;
		
		// form name
		$form_config->name = 'form_test';
		
		// input text
		$input_name = 'test';
		$form_config->input->$input_name->name  = 'test';
		$form_config->input->$input_name->type  = 'text';
		$form_config->input->$input_name->value = 'default';
		
		// input submit
		$input_name = 'submit';
		$form_config->input->$input_name->name  = 'submit';
		$form_config->input->$input_name->type  = 'submit';
		$form_config->input->$input_name->value = 'submit';
		
	//	$this->d( Toolbox::toArray($form_config) );
		
		return $form_config;
	}
	
}

