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
		$form_config->input->$input_name->value = ' ログインして購入手続きに進む ';
		
		return $form_config;
	}
	
	function form_register()
	{
		$form_config = new Config;
		
		//  form name
		$form_config->name   = 'form_register';

		//  First name
		$input_name = 'first_name';
		$form_config->input->$input_name->label = '姓';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  Last name
		$input_name = 'last_name';
		$form_config->input->$input_name->label = '名';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  nickname
		$input_name = 'nick_name';
		$form_config->input->$input_name->label = 'ニックネーム';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';

		//  E-mail
		$input_name = 'email';
		$form_config->input->$input_name->label = 'メールアドレス';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		$form_config->input->$name->validate->permit = 'email';

		//  E-mail (confirm)
		$input_name = 'email_confirm';
		$form_config->input->$input_name->label = 'メールアドレス（確認）';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->validate->compare = 'email';
		$form_config->input->$input_name->errors->required = '%sが未入力です。';

		//  Password
		$input_name = 'password';
		$form_config->input->$input_name->label = 'パスワード';
		$form_config->input->$input_name->type  = 'password';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';

		//  Password (confirm)
		$input_name = 'password_confirm';
		$form_config->input->$input_name->label = 'パスワード（確認）';
		$form_config->input->$input_name->type  = 'password';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';

		//  Gender
		$input_name = 'gender';
		$form_config->input->$input_name->label = '性別';
		$form_config->input->$input_name->type  = 'select';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
			//  Empty			
			$form_config->input->$input_name->options->e->value = '';
			//  Male
			$form_config->input->$input_name->options->m->label = '男性';
			$form_config->input->$input_name->options->m->value = 'M';
			//  Female
			$form_config->input->$input_name->options->f->label = '女性';
			$form_config->input->$input_name->options->f->value = 'F';

		//  Prefe
		$input_name = 'pref';
		$form_config->input->$input_name->label = '都道府県';
		$form_config->input->$input_name->type  = 'select';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';

			$form_config->input->$input_name->options->a->value = '';
			$form_config->input->$input_name->options->b->value = '北海道';
			$form_config->input->$input_name->options->c->value = '東京都';

		//  birthday
		$input_name = 'birthday';
		$form_config->input->$input_name->label  = '生年月日';
		$form_config->input->$input_name->joint  = '-';
		$form_config->input->$input_name->cookie = true;
		$form_config->input->$input_name->validate->permit = 'date';
			
			$i = 'year';
			$form_config->input->$input_name->options->$i->type  = 'select';
			$form_config->input->$input_name->options->$i->tail  = '-';
			$form_config->input->$input_name->options->$i->value = '1980';
			
			for( $n=1; $n<=80; $n++){
				$v = date('Y') - $n;
				$form_config->input->$input_name->options->$i->options->$v->value = $v;
			}
				
			$i = 'month';
			$form_config->input->$input_name->options->$i->type  = 'select';
			$form_config->input->$input_name->options->$i->tail  = '-';
			$form_config->input->$input_name->options->$i->validate->required  = true;
				
			for( $n=0; $n<=12; $n++){
				$form_config->input->$input_name->options->$i->options->$n->value = $n ? $n: '';
			}
				
			$i = 'day';
			$form_config->input->$input_name->options->$i->type  = 'select';
			$form_config->input->$input_name->options->$i->validate->required  = true;
			for( $n=0; $n<=31; $n++){
				$form_config->input->$input_name->options->$i->options->$n->value = $n ? $n: '';
			}
			
		//  Agree
		$input_name = 'agree';
		$form_config->input->$input_name->label = '利用規約';
		$form_config->input->$input_name->type  = 'checkbox';
		$form_config->input->$input_name->validate->required = true;
			//  Agree option
			$form_config->input->$input_name->options->yes->label = '利用規約に同意する';
			$form_config->input->$input_name->options->yes->value = 1;
			
		//  submit
		$input_name = 'submit';
		$form_config->input->$input_name->type  = 'submit';
		$form_config->input->$input_name->class  = 'submit';
		$form_config->input->$input_name->style  = 'font-size: 16px;';
		$form_config->input->$input_name->value = ' この内容で仮登録する ';
		
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

