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
	
	function form_buy()
	{
		// I create form config.
		$form_config = new Config;
	
		// form name
		$form_config->name = 'form_buy';
	
		// input text
		$input_name = 'coupon_id';
		$form_config->input->$input_name->name  = 'coupon_id';
		$form_config->input->$input_name->type  = 'hidden';
		$form_config->input->$input_name->value = 'default';
		
		// input text
		$input_name = 'quantity';
		$form_config->input->$input_name->name  = 'quantity';
		$form_config->input->$input_name->type  = 'select';
		$form_config->input->$input_name->id  = 'quantity';
		$form_config->input->$input_name->style  = 'font-size:1em; height:1.5em;';
		$form_config->input->$input_name->onchange = 'change_quantity();';
		
		for( $i=1; $i<10; $i++){
			$form_config->input->$input_name->options->$i->value = $i;
			$form_config->input->$input_name->options->$i->label = $i;
			$form_config->input->$input_name->options->$i->style = 'text-align:center;';
			
			/*
			$form_config->input->$input_name->option->value = $i;
			$form_config->input->$input_name->option->label = $i;
			*/
			/*
			$option = array();
			$option['value'] = $i;
			$option['label'] = $i;
			$option['style'] = 'text-align:center;';
			$input['options'][] = $option;
			*/
		}
		
		//$form_config->input->$input_name->value = 'default';
		
		/*
		//購入内容
$input = array();
$input['name'] 	 = 'quantity';
$input['type']	 = 'select';
$input['id']	 = 'quantity';
//$input['style']	 = 'width:40px; text-align:center; font-size:30px; font-weight: 900;';
$input['style']	 = 'font-size:1em; height:1.5em;';
$input['onchange'] = 'change_quantity();';
for( $i=1; $i<10; $i++){
	$option = array();
	$option['value'] = $i;
	$option['label'] = $i;
	$option['style'] = 'text-align:center;';
	$input['options'][] = $option;
}
$form['input'][]  = $input; 
		
		 */
		// input submit
		$input_name = 'submit';
		$form_config->input->$input_name->name  = 'submit';
		$form_config->input->$input_name->type  = 'submit';
		$form_config->input->$input_name->class  = 'submit';
		$form_config->input->$input_name->style  = 'font-size: 16px;';
		$form_config->input->$input_name->value = 'この内容で購入';
		
		//  フォームを設定する
		/*
		$this->form()->AddForm($config);
		
		if( $this->form()->Secure($form_name) ){
			$this->p('Submit form is successful!!');
		}else{
			$this->form()->debug($form_name);
		}
		*/
		//	$this->d( Toolbox::toArray($form_config) );
	
		return $form_config;
	}
	
	function select_coupon()
	{
		$config = $this->select();
		$config->table = 't_coupon';
		return $config;
	}

	function select_coupon_default()
	{
		$config = $this->select_coupon();
		$config->order = 'updated desc';
		$config->limit = 1;
		return $config;
	}
	
	function select_buy()
	{
		$config = $this->select();
		$config->table = 't_buy';
		return $config;
	}
	
	function select_shop()
	{
		$config = $this->select();
		$config->table = 't_shop';
		return $config;
	}
	
	function select_account()
	{
		$config = $this->select();
		$config->table = 't_account';
		return $config;
	}
}

