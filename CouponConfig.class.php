<?php

class CouponConfig extends ConfigMgr
{

	//===========================================//
	
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
	//	$form_config->action = '/buy/login'; // URL controll by controller
		
		// input text
		$input_name = 'coupon_id';
		$form_config->input->$input_name->name  = 'coupon_id';
		$form_config->input->$input_name->type  = 'hidden';
		$form_config->input->$input_name->value = 'default';
		
		// input text
		$input_name = 'quantity';
		$form_config->input->$input_name->name  = $input_name;
		$form_config->input->$input_name->type  = 'select';
		$form_config->input->$input_name->validate->required = true;
		$form_config->input->$input_name->style  = 'font-size:1em; height:1.5em;';
		$form_config->input->$input_name->id  = 'quantity';
		//$input['onchange'] = 'change_quantity();';
		$form_config->input->$input_name->onchange  = 'change_quantity();';
		//$form_config->input->$input_name->option->none->value = '';
		
		for( $i=1; $i<10; $i++){
			$form_config->input->$input_name->option->$i->label   = $i;
			$form_config->input->$input_name->option->$i->value   = $i;
			//$option['style'] = 'text-align:center;';
			$form_config->input->$input_name->option->$i->style   = 'text-align:center;';
		}
		
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

	function form_login()
	{
		$form_config = new Config;
	
		// form name
		$form_config->name = 'form_login';
	
		// input text
		$input_name = 'email';
		$form_config->input->$input_name->name  = $input_name;
		$form_config->input->$input_name->type  = 'text';
		$form_config->input->$input_name->label = 'メールアドレス';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		$input_name = 'password';
		$form_config->input->$input_name->name  = $input_name;
		$form_config->input->$input_name->type  = 'text';
		$form_config->input->$input_name->label = 'パスワード';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		/*
		$input_name = 'remember';
		$form_config->input->$input_name->name   = $input_name;
		$form_config->input->$input_name->type   = 'text';
		$form_config->input->$input_name->label  = 'パスワードの保存';
		$form_config->input->$input_name->cookie = true;
		*/
		
		// input submit
		$input_name = 'submit';
		$form_config->input->$input_name->name  = 'submit';
		$form_config->input->$input_name->type  = 'submit';
		$form_config->input->$input_name->class  = 'submit';
		$form_config->input->$input_name->style  = 'font-size: 16px;';
		$form_config->input->$input_name->value = ' ログイン ';
		
		return $form_config;
	}
	
	//===========================================//

	function database()
	{
		$config = parent::database();
	
		$config->database = 'op_coupon';
		$config->user     = 'op_coupon';
	
		return $config;
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

