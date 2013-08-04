<?php

/**
 * master
 */

class CouponConfig extends ConfigMgr
{
	function __call($name, $args)
	{
		$object = CouponApp;
		$io = method_exists($object, $name);
		if( $io ){
			throw new Exception("$name is CouponApp-method.");
		}
		
		parent::__call($name, $args);
	}
	
	/**
	 * データベースに存在するテーブル定義を元にしてフォームのconfigを作成する。
	 * 
	 * @see ConfigMgr::GenerateFormFromDatabase()
	 * @param string $table_name 定義の元になるテーブル名
	 * @param array  $record input.valueに代入する初期値
	 * @return Config
	 */
	function GenerateFormFromDatabase( $table_name, $record=null )
	{
		//  Init.
		$config = new Config();
		//  Set table name.
		$config->table = $table_name;
		//  Get table structs.
		$struct = $this->pdo()->GetTableStruct($table_name);
		//  Get form config.
		$config = parent::GenerateFormFromDatabase($struct,$record);
		//  Create submit button.
		$config->input->submit->type = 'submit';
		//  Return form config.
		return $config;
	}
	
	//===========================================//
	
	/**
	 * Form Config のテンプレート
	 * 
	 * @return Config
	 */
	private function _form_default()
	{	
		//  Create the config for form.
		$form_config = new Config;
		
		// input submit
		$input_name = 'submit';
		$form_config->input->$input_name->name  = 'submit';
		$form_config->input->$input_name->type  = 'submit';
		$form_config->input->$input_name->value = 'submit';
		$form_config->input->$input_name->class = 'submit';
		
		return $form_config;
	}
	
	/**
	 * 新規登録の際にメールを送信して本人確認を行う。
	 * 
	 * @param  string $identification 
	 * @return Config
	 */
	function mail_identification_register($identification)
	{
		$data = new Config();
		$data->identification = $identification;
	
		$mail_config = new Config();
		$mail_config->to      = $this->form()->GetInputValue('email','form_register');
		$mail_config->from    = 'no-reply@open-coupon.com'; // TODO
		$mail_config->subject = 'オープンクーポン：ユーザ情報の登録';
		$mail_config->message = $this->GetTemplate('mail/identification.phtml',$data);
		
		return $mail_config;
	}
	
	/**
	 * mypageからメールアドレスを変更する際に、メールを送信して本人確認を行う。
	 * 
	 * @param  string $identification
	 * @return Config
	 */
	function mail_identification_email($identification)
	{
		$data = new Config();
		$data->identification = $identification;
		
		$mail_config = new Config();
		$mail_config->to      = $this->form()->GetInputValue('email','form_email');
		$mail_config->from    = 'no-reply@open-coupon.com'; // TODO
		$mail_config->subject = 'オープンクーポン：メールアドレスの変更';
		$mail_config->message = $this->GetTemplate('mail/identification.phtml',$data);
		
		return $mail_config;
	}
	
	function mail_identification_forget($email, $identification, $ip)
	{
		$data = new Config();
		$data->identification = $identification;
		$data->ip             = $ip;
		
		$mail_config = new Config();
		$mail_config->to      = $email;
		$mail_config->from    = 'no-reply@open-coupon.com'; // TODO
		$mail_config->subject = 'オープンクーポン：パスワードの再生成';
		$mail_config->message = $this->GetTemplate('mail/identification_forget.phtml',$data);		
		$mail_config->d();
				
		return $mail_config;
	}

	function mail_forget($email, $password)
	{
		$data = new Config();
		$data->password = $password;
		
		$mail_config = new Config();
		$mail_config->to      = $email;
		$mail_config->from    = 'no-reply@open-coupon.com'; // TODO
		$mail_config->subject = 'オープンクーポン：パスワードの再生成（完了）';
		$mail_config->message = $this->GetTemplate('mail/password_forget.phtml',$data);
		
		return $mail_config;
	}
	
	function password_forget($email)
	{
		//	generate new password
		$new_password = $this->model('Password')->get();
		return $new_password;
	}
	
	/**
	 * default value is 300 (= within 5 min.)
	 * 
	 * @return number
	 */
	function GetForgetLimitSecond()
	{
		$limit_sec = 300;
		return $limit_sec;
	}
	
	/**
	 * default value is 3 (= less than 3 times.)
	 * 
	 * @return number
	 */
	function GetForgetLimitCount()
	{
		$limit_count = 3; 
		return $limit_count;
	}
	
	function form_buy($coupon_id)
	{
		$form_config = self::_form_default();
	
		// form name
		$form_config->name = 'form_buy';
		$form_config->action = '/buy/'.$coupon_id;
		
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
		
		//  submit
		$form_config->input->submit->style = "font-size:18px;";
		
		return $form_config;
	}
	
	/**
	 * Get Form Buy Confirm Config
	 * 
	 * @param  integer $account_id
	 * @param  integer $coupon_id
	 * @return  Config
	 */
	/**
	 * 廃止
	 * 
	function form_buy_confirm( $account_id, $coupon_id )
	{
		$config = $this->form_buy($coupon_id);
		$config->merge( $this->form_address($account_id,$coupon_id) );
		$config->name = 'form_buy_confirm';
		return $config;	
	}
	*/
	
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
		$form_config->action = 'app:/account/register/confirm';
		
		//  First name
		$input_name = 'first_name';
		$form_config->input->$input_name->label = '姓';
		$form_config->input->$input_name->required = true;

		//  Last name
		$input_name = 'last_name';
		$form_config->input->$input_name->label = '名';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  nickname
		$input_name = 'nick_name';
		$form_config->input->$input_name->label = 'ニックネーム';
		$form_config->input->$input_name->tail  = '<br/>';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';

		//  E-mail
		$input_name = 'email';
		$form_config->input->$input_name->label = 'メールアドレス';
		$form_config->input->$input_name->tail  = '<br/>';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		$form_config->input->$input_name->validate->permit = 'email';
		
		//  E-mail (confirm)
		$input_name = 'email_confirm';
		$form_config->input->$input_name->label = 'メールアドレス（確認）';
		$form_config->input->$input_name->tail  = '<br/>';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->validate->compare = 'email';
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  Password
		$input_name = 'password';
		$form_config->input->$input_name->label = 'パスワード';
		$form_config->input->$input_name->type  = 'password';
		$form_config->input->$input_name->tail  = '<br/>';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  Password (confirm)
		$input_name = 'password_confirm';
		$form_config->input->$input_name->label = 'パスワード（確認）';
		$form_config->input->$input_name->type  = 'password';
		$form_config->input->$input_name->tail  = '<br/>';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->validate->compare = 'password';
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
			
		//  Pref
		$input_name = 'favorite_pref';
		$form_config->input->$input_name->label = '都道府県';
		$form_config->input->$input_name->type  = 'select';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
			
			$form_config->input->$input_name->options = $this->model('JapanesePref')->UsedToForms();
		
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
		$form_config->input->$input_name->type   = 'submit';
		$form_config->input->$input_name->class  = 'submit';
		$form_config->input->$input_name->style  = 'font-size: 16px;';
		$form_config->input->$input_name->value  = ' この内容で仮登録する ';
		
		return $form_config;
	}

	function form_forget()
	{
		$form_config = self::_form_default(__FUNCTION__);
		
		//  form name
		$form_config->name   = 'form_forget';

		//  email
		$input_name = 'email';
		$form_config->input->$input_name->label = 'メールアドレス';
		$form_config->input->$input_name->type  = 'text';
		$form_config->input->$input_name->validate->required = true;
		$form_config->input->$input_name->validate->permit   = 'email';
		$form_config->input->$input_name->error->required    = '$labelが未入力です。';

		return $form_config;
	}
	
	function form_forget_identification()
	{
		$form_config = self::_form_default(__FUNCTION__);
		
		//  form name
		$form_config->name   = 'form_forget_identification';
		
		//  identification code
		$input_name = 'identification';
		$form_config->input->$input_name->label = '確認コード';
		
		return $form_config;
	}
	
	/**
	 * 登録しようとしているメールアドレスが本人かキーコードを送信し、入力して貰って本人確認を行う。
	 *
	 */
	function form_register_identification()
	{
		$form_config = self::_form_default(__FUNCTION__);
	
		//  Form
		$form_config->name = 'form_register_identification';
	
		//  key code
		$input_name = 'identification';
		$form_config->input->$input_name->label = '確認コード';
	
		return $form_config;
	}
	
	function form_address( $account_id, $seq_no=null )
	{
		$form_config = self::_form_default();
		
		//  form name
		$form_config->name   = 'form_address';
		if( $seq_no ){
			$form_config->name .= "_$seq_no";
		}
		
		//  First name
		$input_name = 'first_name';
		$form_config->input->$input_name->label = '名';
		//$form_config->input->$input_name->value = $record[$input_name];
		$form_config->input->$input_name->required = true;
		
		//  Last name
		$input_name = 'last_name';
		$form_config->input->$input_name->label = '姓';
		//$form_config->input->$input_name->value = $record[$input_name];
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  postcode
		$input_name = 'zipcode';
		$form_config->input->$input_name->label = '郵便番号';
		//$form_config->input->$input_name->value = $record[$input_name];
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  pref
		$input_name = 'pref';
		$form_config->input->$input_name->type  = 'select';
		$form_config->input->$input_name->label = '都道府県';
		//$form_config->input->$input_name->value = $this->model('JapanesePref')->GetIndex($t_address['pref']);
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		$form_config->input->$input_name->options = $this->model('JapanesePref')->UsedToForms();

		//  city
		$input_name = 'city';
		$form_config->input->$input_name->label = '市区町村';
		//$form_config->input->$input_name->value = $record[$input_name];
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  address
		$input_name = 'address';
		$form_config->input->$input_name->label = '丁目番地';
		//$form_config->input->$input_name->value = $record[$input_name];
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  building
		$input_name = 'building';
		$form_config->input->$input_name->label = '建物名';
		//$form_config->input->$input_name->value = $record[$input_name];
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  submit
		$input_name = 'submit';
		$form_config->input->$input_name->type   = 'submit';
		$form_config->input->$input_name->class  = 'submit';
		$form_config->input->$input_name->style  = 'font-size: 16px;';
		$form_config->input->$input_name->value  = ' 入力内容を確認する ';

		//  Set saved value from database.
		if( $seq_no ){
			$select = $this->select_address( $account_id, $seq_no );
			$record = $this->pdo()->select($select);
			foreach($record as $input_name => $value){
				if( isset($form_config->input->$input_name) ){
					$form_config->input->$input_name->value = $value;
				}
			}
		//	$this->d( $record );
		}
		
		//  seq_no
		$input_name = 'seq_no';
		$form_config->input->$input_name->value  = $seq_no;
		
		//  Set saved value from database.
		if( $seq_no ){
			$select = $this->select_address( $account_id, $seq_no );
			$record = $this->pdo()->select($select);
			foreach($record as $input_name => $value){
				if( isset($form_config->input->$input_name) ){
					$form_config->input->$input_name->value = $value;
				}
			}
		//	$this->d( $record );
		}
		
		return $form_config;
	}
	
	function form_payment()
	{
		$form_config = new Config;
		
		//  form name
		$form_config->name   = 'form_payment';
		$form_config->action = 'ctrl:/execute';
		
		//  card no
		$input_name = 'card_no';
		$form_config->input->$input_name->label    = "カード番号";
		$form_config->input->$input_name->required = true;
		
		//  exp year
		$input_name = 'exp_yy';
		$form_config->input->$input_name->label = "カード有効期限（年）";
		$form_config->input->$input_name->type  = 'select';
		$form_config->input->$input_name->options = $this->model('Helper')->GetFormOptionsDateYear();
		$form_config->input->$input_name->required = true;
		
		//  exp month
		$input_name = 'exp_mm';
		$form_config->input->$input_name->label = "カード有効期限（月）";
		$form_config->input->$input_name->type  = 'select'; 
		$form_config->input->$input_name->options = $this->model('Helper')->GetFormOptionsDateMonth();
		$form_config->input->$input_name->required = true;
					
		//  csc
		$input_name = 'csc';
		$form_config->input->$input_name->label = "セキュリティコード";
		$form_config->input->$input_name->required = true;

		/*
		//  支払い方法？（リボ、分割？）
		$input_name = 'paymode';
		$form_config->input->$input_name->label = "paymode";
		$form_config->input->$input_name->required = true;
		
		//  支払い方法？（回数？）
		$input_name = 'incount';
		$form_config->input->$input_name->label = "incount";
		$form_config->input->$input_name->validate->required = true;
		*/
		
		//  submit
		$input_name = 'submit';
		$form_config->input->$input_name->type   = 'submit';
		$form_config->input->$input_name->class  = 'submit';
		$form_config->input->$input_name->style  = 'font-size: 16px;';
		$form_config->input->$input_name->value  = ' 決済 ';
		
		return $form_config;
	}
	
	function form_customer( $account_id )
	{
		//  customer table
		$config = $this->select_customer( $account_id );
		$t_customer = $this->pdo()->select( $config );
		
		//  Init form_config
		$form_config = new Config;
		
		//  form name
		$form_config->name   = 'form_customer';
		$form_config->action = '';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		$form_config->input->$input_name->errors->permit = '%sに不正な値が入力されています。(%s)';
		
		//  First name
		$input_name = 'first_name';
		$form_config->input->$input_name->type = 'text';
		$form_config->input->$input_name->label = '名';
		$form_config->input->$input_name->value = $t_customer['first_name'];
		$form_config->input->$input_name->required = true;
		
		//  Last name
		$input_name = 'last_name';
		$form_config->input->$input_name->type = 'text';
		$form_config->input->$input_name->label = '姓';
		$form_config->input->$input_name->value = $t_customer['last_name'];
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		/*
		
		//  favorite_pref
		$input_name = 'favorite_pref';
		$form_config->input->$input_name->type = 'select';
		$form_config->input->$input_name->label = 'お気に入り（都道府県）';
		$form_config->input->$input_name->value = $t_customer['favorite_pref'];
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		$form_config->input->$input_name->options = $this->model('JapanesePref')->UsedToForms();
		
		//  favorite_city
		$input_name = 'favorite_city';
		$form_config->input->$input_name->type = 'select';
		$form_config->input->$input_name->label = 'お気に入り（市区町村）';
		$form_config->input->$input_name->value = $t_customer['favorite_city'];
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		*/
		
		//  birthday
		$input_name = 'birthday';
		$form_config->input->$input_name->label  = '生年月日';
	//	$form_config->input->$input_name->value = $t_customer['birthday']; // TODO: Auto recovery
		$form_config->input->$input_name->joint  = '-';
		$form_config->input->$input_name->cookie = true;
		$form_config->input->$input_name->validate->permit = 'date';
		
		//  TODO: 元に戻すのも自動化する
		$birthday = explode('-', $t_customer['birthday']);
		
		$i = 'year';
		$form_config->input->$input_name->options->$i->type  = 'select';
		$form_config->input->$input_name->options->$i->tail  = '-';
		$form_config->input->$input_name->options->$i->value = $birthday[0];
		
		for( $n=1; $n<=80; $n++){
			$v = date('Y') - $n;
			$form_config->input->$input_name->options->$i->options->$v->value = $v;
		}
		
		$i = 'month';
		$form_config->input->$input_name->options->$i->type  = 'select';
		$form_config->input->$input_name->options->$i->tail  = '-';
		$form_config->input->$input_name->options->$i->value = $birthday[1];
		$form_config->input->$input_name->options->$i->validate->required  = true;
			
		for( $n=0; $n<=12; $n++){
			$form_config->input->$input_name->options->$i->options->$n->value = $n ? $n: '';
		}
			
		$i = 'day';
		$form_config->input->$input_name->options->$i->type  = 'select';
		$form_config->input->$input_name->options->$i->value = $birthday[2];
		$form_config->input->$input_name->options->$i->validate->required  = true;
		for( $n=0; $n<=31; $n++){
			$form_config->input->$input_name->options->$i->options->$n->value = $n ? $n: '';
		}
		
		//  Gender
		$input_name = 'gender';
		$form_config->input->$input_name->label = '性別';
		$form_config->input->$input_name->type  = 'select';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		$form_config->input->$input_name->value = $t_customer['gender'];
			
			//  Empty
			$form_config->input->$input_name->options->e->value = '';
			//  Male
			$form_config->input->$input_name->options->m->label = '男性';
			$form_config->input->$input_name->options->m->value = 'M';
			//  Female
			$form_config->input->$input_name->options->f->label = '女性';
			$form_config->input->$input_name->options->f->value = 'F';
			
		//  submit
		$input_name = 'submit';
		$form_config->input->$input_name->type   = 'submit';
		$form_config->input->$input_name->class  = 'submit';
		$form_config->input->$input_name->style  = 'font-size: 16px;';
		$form_config->input->$input_name->value  = '変更を保存する';
		
		return $form_config;
	}

	function form_address_change( $account_id, $seq_no )
	{
		//  address table
		$config = $this->select_address($account_id);
		$t_address = $this->pdo()->select($config);
	
		//  form name
		$form_config->name   = 'form_address_change';
		
		//  First name
		$input_name = 'first_name';
		$form_config->input->$input_name->label = '名';
		$form_config->input->$input_name->value = $record[$input_name];
		$form_config->input->$input_name->required = true;
		
		//  Last name
		$input_name = 'last_name';
		$form_config->input->$input_name->label = '姓';
		$form_config->input->$input_name->value = $record[$input_name];
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		//  zipcode
		$input_name = 'zipcode';
		$form_config->input->$input_name->label = '郵便番号';
		$form_config->input->$input_name->value = $t_address['zipcode'];
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
	
		//  pref
		$input_name = 'pref';
		$form_config->input->$input_name->type  = 'select';
		$form_config->input->$input_name->label = '都道府県';
		$form_config->input->$input_name->value = $this->model('JapanesePref')->GetIndex($t_address['pref']);
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		$form_config->input->$input_name->options = $this->model('JapanesePref')->UsedToForms();
	
		//  city
		$input_name = 'city';
		$form_config->input->$input_name->type = 'text';
		$form_config->input->$input_name->label = '市区町村';
		$form_config->input->$input_name->value = $t_address['city'];
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
	
		//  address
		$input_name = 'address';
		$form_config->input->$input_name->type = 'text';
		$form_config->input->$input_name->label = '丁目番地';
		$form_config->input->$input_name->value = $t_address['address'];
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
	
		//  building
		$input_name = 'building';
		$form_config->input->$input_name->type = 'text';
		$form_config->input->$input_name->label = '建物名';
		$form_config->input->$input_name->value = $t_address['building'];
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
	
		//  submit
		$input_name = 'submit';
		$form_config->input->$input_name->type   = 'submit';
		$form_config->input->$input_name->class  = 'submit';
		$form_config->input->$input_name->style  = 'font-size: 16px;';
		$form_config->input->$input_name->value  = '変更を保存する';
	
		return $form_config;
	}
	
	function form_email()
	{
		$form_config = self::_form_default(__FUNCTION__);
		
		//  Current email address
		$id = $this->model('Login')->GetLoginID();
		$email = $this->pdo()->quick("email <- t_account.id = $id");
		//  TODO:
		//$email = $this->model('Blowfish')->Decript($email);
		$bf = new Blowfish();
		$email = $bf->Decrypt($email);
		
		//  form name
		$form_config->name   = 'form_email';
		
		//  email current
		$input_name = 'email_current';
		$form_config->input->$input_name->label  = '現在のメールアドレス';
		$form_config->input->$input_name->type   = 'text';
		$form_config->input->$input_name->value  = $email;
		$form_config->input->$input_name->readonly = true; //  TODO: write testcase
		
		//  email
		$input_name = 'email';
		$form_config->input->$input_name->label = 'メールアドレス';
		$form_config->input->$input_name->type  = 'text';
		$form_config->input->$input_name->validate->required = true;
		$form_config->input->$input_name->validate->permit   = 'email';
		$form_config->input->$input_name->error->required    = '$labelが未入力です。';
		
		//  email confirm
		$input_name = 'email_confirm';
		$form_config->input->$input_name->label = 'メールアドレス（確認用）';
		$form_config->input->$input_name->type  = 'text';
		$form_config->input->$input_name->validate->required = true;
		$form_config->input->$input_name->validate->compare  = 'email';
		$form_config->input->$input_name->error->required    = '$labelが未入力です。';
		$form_config->input->$input_name->error->compare     = '$labelが一致しません。';
		
		return $form_config;
	}

	/**
	 * 登録しようとしているメールアドレスが本人かキーコードを送信し、入力して貰って本人確認を行う。
	 *
	 */
	function form_email_identification()
	{
		$form_config = self::_form_default(__FUNCTION__);
		
		//  Form
		$form_config->name = 'form_identification';
		
		//  key code
		$input_name = 'identification';
		$form_config->input->$input_name->label = '確認コード';
		
		return $form_config;
	}
	
	function form_shop( $shop_id )
	{
		if( empty($shop_id) ){
			return false;
		}
		
		//  t_shop record
		$config = parent::select('t_shop');
		$config->where->shop_id = $shop_id;
		$config->limit = 1;
		$record = $this->pdo()->select( $config );
	//	$this->d($record);
		
		//  t_shop struct
		$config = $this->GenerateFormFromDatabase('t_shop',$record);
		
		//  Added form name
		$config->name = 'form_shop';
	//	$this->d( Toolbox::toArray($config) );
	
		return $config;
	}
		
	function form_shop_photo( $shop_id )
	{
		if( empty($shop_id) ){
			return false;
		}
		
		//  Get default config. 
		$config = $this->_form_default();
		
		//  form setting
		$config->name = 'shop_photo';
		
		//  input setting
		$input_name = 'shop_photo_1';
		$config->input->$input_name->type = 'file';
		$config->input->$input_name->validate->permit = 'image';
		
		/*
		for( $i=1; $i<10; $i++ ){
			$name = 'shop_photo_' . $i;
			$config->input->$name->type = 'file';
			//	$config->input->$name->tail = '<br/>';
			$config->input->$name->save->dir  = "app:/shop/$shop_id";
			$config->input->$name->save->name = $i;
		}
		*/
		
		//  Check saved image url.
		$select = $this->select_photo( $shop_id, 0, $seq_no );
		$record = $this->pdo()->select($select);
		
		//  Set save dir/name. 
		$config->input->$input_name->save->dir  = "app:/shop/$shop_id";
		$config->input->$input_name->save->name = '1';
		
		//  Set saved file path.
		if( $record['url'] ){
			$config->input->$input_name->value = $record['url'];
		}
		
		return $config;
	}
	
	function form_myshop_coupon( $shop_id, $coupon_id=null )
	{
		if(!$shop_id ){
			$this->StackError('Empty shop_id.');
			return false;
		}
		
		if( $coupon_id ){
			//  t_coupon record
			$record = $this->pdo()->quick("t_coupon.coupon_id = $coupon_id");
		}else{
			$record = null;
		}
		
		//  t_coupon struct
		$form_config = $this->GenerateFormFromDatabase('t_coupon',$record);
		
		//  Init shop_id
		$form_config->input->shop_id->value = $shop_id;
		
		//  Added form name
		$form_config->name = 'form_coupon' . $coupon_id;
		//$form_config->id = 'form_coupon';
		
		$input_name = 'coupon_title';
		$form_config->input->$input_name->label  = 'タイトル';
	//	$form_config->input->$input_name->type   = 'text';
	//	$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->value	 = 'New coupon title';
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		$input_name = 'coupon_description';
		$form_config->input->$input_name->label  = '説明';
	//	$form_config->input->$input_name->type   = 'text';
	//	$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->value	 = "Coupon's description.";
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		
		$input_name = 'coupon_normal_price';
		$form_config->input->$input_name->label  = '通常価格';
	//	$form_config->input->$input_name->type   = 'text';
	//	$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->value	 = 1000;
		$form_config->input->$input_name->errors->required = '%sが未入力です。';
		$form_config->input->$input_name->validate->permit = 'integer';
		$form_config->input->$input_name->validate->range = '1-';
	//	$form_config->input->$input_name->error->{'permit-integer'} = 'Only integer. (not decimal)';
	//	$form_config->input->$input_name->error->{'permit-numeric'} = 'Only numeric.';
		
		$input_name = 'coupon_sales_price';
		$form_config->input->$input_name->label  = '販売価格';
		$form_config->input->$input_name->value	 = 1000;
	//	$form_config->input->$input_name->type   = 'text';
	//	$form_config->input->$input_name->required = true;
	//	$form_config->input->$input_name->errors->required = '%sが未入力です。';
	//	$form_config->input->$input_name->validate->permit = 'integer';
	//	$form_config->input->$input_name->validate->range = '1-';
	//	$form_config->input->$input_name->error->{'permit-integer'} = 'Only integer. (not decimal)';
	//	$form_config->input->$input_name->error->{'permit-numeric'} = 'Only numeric.';
		
		$input_name = 'coupon_sales_num_top';
		$form_config->input->$input_name->label  = '最大販売数';
		$form_config->input->$input_name->value	 = 100;
		/*
		$form_config->input->$input_name->type   = 'text';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->validate->permit = 'integer';
		$form_config->input->$input_name->validate->range = '1-';
		$form_config->input->$input_name->error->{'permit-integer'} = 'Only integer. (not decimal)';
		$form_config->input->$input_name->error->{'permit-numeric'} = 'Only numeric.';
		*/
		
		$input_name = 'coupon_sales_num_bottom';
		$form_config->input->$input_name->label  = '最小販売数';
		$form_config->input->$input_name->value	 = 80;
		/*
		$form_config->input->$input_name->type   = 'text';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->validate->permit = 'integer';
		$form_config->input->$input_name->validate->range = '1-';
		$form_config->input->$input_name->error->{'permit-integer'} = 'Only integer. (not decimal)';
		$form_config->input->$input_name->error->{'permit-numeric'} = 'Only numeric.';
		*/
		
		$input_name = 'coupon_sales_start';
		$form_config->input->$input_name->label  = '販売開始日時';
		$form_config->input->$input_name->value	 = date('Y-m-d H:i:s',strtotime("+1hours"));
		/*
		$form_config->input->$input_name->type   = 'text';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->validate->permit = 'datetime';
		*/
		$form_config->input->$input_name->error->required = '$labelが未入力です。';
		$form_config->input->$input_name->error->{'permit-datetime'} = '$labelが日時ではありません。（$value）';
		
		$input_name = 'coupon_sales_finish';
		$form_config->input->$input_name->label  = '販売終了日時';
		$form_config->input->$input_name->value	 = date('Y-m-d H:i:s',strtotime("+6hours"));
		/*
		$form_config->input->$input_name->type   = 'text';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->validate->permit = 'datetime';
		*/
		$form_config->input->$input_name->error->required = '$labelが未入力です。';
		$form_config->input->$input_name->error->{'permit-datetime'} = '$labelが日時ではありません。（$value）';
		
		$input_name = 'coupon_expire';
		$form_config->input->$input_name->label  = '有効期限';
		$form_config->input->$input_name->value	 = date('Y-m-d H:i:s',strtotime("+24hours"));
		/*
		$form_config->input->$input_name->type   = 'text';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->validate->permit = 'datetime';
		*/
		$form_config->input->$input_name->error->required = '$labelが未入力です。';
		$form_config->input->$input_name->error->{'permit-datetime'} = '$labelが日時ではありません。（$value）';
		
		$input_name = 'coupon_person_num';
		$form_config->input->$input_name->label  = '一人が購入できる枚数';
		$form_config->input->$input_name->value	 = 10;
		/*
		$form_config->input->$input_name->type   = 'text';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->error->{'permit-numeric'} = 'Only numeric.';
		*/
		
		//  submit
		$input_name = 'submit';
		$form_config->input->$input_name->type   = 'submit';
		$form_config->input->$input_name->class  = 'submit';
		$form_config->input->$input_name->style  = 'font-size: 16px;';
		//$form_config->input->$input_name->onClick  = 'return CheckImgCountMin()';
		$form_config->input->$input_name->value  = '登録する';
		
		return $form_config;
		
		/*
		//  form name
		$form_config->name   = 'form_email';
		
		//  email current
		$input_name = 'email_current';
		$form_config->input->$input_name->label  = '現在のメールアドレス';
		$form_config->input->$input_name->type   = 'text';
		$form_config->input->$input_name->value  = $email;
		$form_config->input->$input_name->readonly = true; //  TODO: write testcase
		*/
	}

	
	//	以下の定義は現在未使用。画像用フォームでop-coreを使用しない場合は要削除。
	function form_myshop_coupon_image ( $shop_id, $coupon_id=null )
	{
		if(!$shop_id ){
			$this->StackError('Empty shop_id.');
			return false;
		}

		$form_config = new Config();
		
		//  Init shop_id
		$form_config->input->shop_id->value = $shop_id;//不要？
		
		$form_config->name   = 'form_coupon_image';
		
		$form_config->target = 'targetFrame';
		$form_config->id     = 'form_coupon_image';

		$input_name = 'max_file_size';
		$form_config->input->$input_name->type   = 'hidden';
		$form_config->input->$input_name->label  = 'max_file_size';
		$form_config->input->$input_name->value	 = 2000000;
		
		$input_name = 'upload_image';
		$form_config->input->$input_name->label  = 'クーポンのイメージ';
		$form_config->input->$input_name->type   = 'file';
		$form_config->input->$input_name->required = true;
		$form_config->input->$input_name->save->dir = $this->ConvertPath("app:/temp/$shop_id/new/");
		$form_config->input->$input_name->validate->permit = 'image';

		//  submit
		$input_name = 'submit';
		$form_config->input->$input_name->type   = 'submit';
		//$form_config->input->$input_name->class  = 'submit';
		//$form_config->input->$input_name->style  = 'font-size: 16px;';
		$form_config->input->$input_name->value  = '画像をアップロード';
		
		return $form_config;
	}
	
	
	function form_password( $account_id )
	{
		$form_config = new Config();
		
		$form_config->name = 'form_password';
		
		$input_name = 'password';
		$form_config->input->$input_name->label = 'パスワード';
		$form_config->input->$input_name->type  = 'password';
		$form_config->input->$input_name->validate->required = true;
		
		$input_name = 'password_confirm';
		$form_config->input->$input_name->label = 'パスワード（確認用）';
		$form_config->input->$input_name->type = 'password';
		$form_config->input->$input_name->validate->required = true;
		$form_config->input->$input_name->validate->compare = 'password';
		$form_config->input->$input_name->error->compare = 'パスワードが一致しません';
		
		$input_name = 'submit';
		$form_config->input->$input_name->type = 'submit';
		
		return $form_config;
	}
	
	function button_add_address()
	{
		$form_config = new Config();
		
		$form_config->name = 'button_add_address';
		
		//  submit
		$input_name = 'submit';
		$form_config->input->$input_name->type   = 'submit';
		$form_config->input->$input_name->class  = 'submit';
		$form_config->input->$input_name->style  = 'font-size: 16px;';
		$form_config->input->$input_name->value  = ' 住所を追加する ';
		
		return $form_config;
	}
	//===========================================//
	
	function credit( $id, $amount )
	{
		$config = new Config();
		
		//  Get email
		$qu = " email <- t_account.id = $id ";
		$email = $this->pdo()->Quick($qu);
		
		$cardno = $this->form()->GetValue('card_no', 'form_payment');
		$exp_yy = $this->form()->GetValue('exp_yy',  'form_payment');
		$exp_mm = $this->form()->GetValue('exp_mm',  'form_payment');
		$cardexp = "$exp_yy-$exp_mm";
		
		//  Create config
		$config->email   = $email;
		$config->cardno  = $cardno;
		$config->cardexp = $cardexp;
		$config->amount  = $amount;
		
		return $config;
	}
	
	//===========================================//

	static function database()
	{
		$config = parent::database();
	
		$config->database = 'op_coupon';
		$config->user     = 'op_coupon';
	
		return $config;
	}
	
	function select_login( $email, $password )
	{
		if(empty($email)){
			$this->StackError("Empty email.");
			return new Config();
		}
		
		$select = parent::select('t_account');
		$select->where->email_md5 = md5($email);
		$select->where->password  = md5($password);
		$select->limit = 1;
		return $select;
	}
	
	function select_coupon_list( $coupon_id=null )
	{
		//  Init
		$limit  = 10;
		$page   = isset($_GET['page']) ? $_GET['page'] : 0;
		$offset = $limit * $page;
		
		//  Create select config.
		$config = self::select_coupon( $coupon_id );
		$config->where->coupon_sales_start  = '< '.date('Y-m-d H:i:s'/*, time() + date('Z') */);
		$config->where->coupon_sales_finish = '> '.date('Y-m-d H:i:s'/*, time() + date('Z') */);
		if( $coupon_id ){
			$config->where->coupon_id = $coupon_id;
			$config->limit = 2;
		}else{
			$config->limit  = $limit;
			$config->offset = $offset;
		}
		
		//  alias (Is this necessary?)
		//  $config->column->coupon_normal_price = 'coupon_normal_price'; 
		
		return $config;
	}
	
	function select_coupon( $coupon_id=null )
	{
		$config = parent::select('t_coupon');
		if( $coupon_id ){
			$config->where->coupon_id = $coupon_id;
			$config->limit = 1;
		}else{
			//	$config->order = '';
		}
		
		return $config;
	}

	function select_coupon_default()
	{
		$config = $this->select_coupon();
		$config->order = 'updated desc';
		$config->limit = 1;
		return $config;
	}
	
	function select_shop()
	{
		$config = $this->select();
		$config->table = 't_shop';
		return $config;
	}
	
	/**
	 * ここにコメントを入力する
	 * 
	 * @param  string $id
	 * @param  string $email
	 * @return Config
	 */
	function select_account( $id=null, $email )
	{
		$config = $this->select();
		$config->table = 't_account';
		
		if( $id ){
			$config->where->id = $id;
			$config->limit = 1;
		}
		
		return $config;
	}
	
	/**
	 * 
	 * @return unknown
	 */
	function select_account_mine()
	{
		//  Get Login id
		$id = $this->model('Login')->GetLoginID();
		
		//  Get config
		$config = $this->select_account($id);
		
		return $config;
	}
	
	function select_buy( $id=null )
	{
		if(!$id){
			$id = $this->model('Login')->GetLoginID();
		}
		
		$config = parent::select();
		$config->table = 't_buy';
		
		if( $id ){
			$config->where->account_id = $id;
		}
		
		return $config;
	}

	//  ↓これは本来不要ですが、ラッパーの作り方の勉強として残しました。
	//   こうしておけば、仕様を変更しても互換性を維持できます。
	//  （この規模のサイトだと不要ですが、大きいサイトだと影響の範囲が予想できないため）
	function select_my_buy()
	{
		$id = $this->model('Login')->GetLoginID();
		if(!$id){
			$this->StackError("Login ID is empty.");
			return false;
		}
		return $this->select_buy($id);
	}
	
	function select_one_coupon($coupon_id)
	{
		$config = $this->select();
		$config->table = 't_coupon';
		$config->coupon_id = $coupon_id;
		$config->limit = 1;
		
		return $config;
	}
	
	function select_customer( $id )
	{
		$config = $this->select();
		$config->table = 't_customer';
		$config->account_id = $id;
		$config->limit = 1;
		
		return $config;
	}
	
	function select_my_customer()
	{
		$id = $this->model('Login')->GetLoginID();
		return $this->select_customer($id);
	}
	
	/*
	function select_my_account()
	{
		$id = $this->model('Login')->GetLoginID();
		$config = $this->select();
		$config->table = 't_account';
		$config->where->id = $id;
		$config->limit = 1;
		
		return $config;
	}
	*/
	
	function select_account_email( $email )
	{
		$config = $this->select();
		$config->table = 't_account';
		$config->where->email_md5 = md5($email);
		$config->limit = 1;
		
		return $config;//dbに問い合わせはしていない。
	}
	
	function select_address( $id, $seq_no=null )
	{
		$config = parent::select('t_address');
		$config->table = 't_address';
		$config->where->account_id = $id;
		if( $seq_no ){
			$config->where->seq_no = $seq_no;
			$config->limit = 1;
		}
		return $config;
	}
	
	function select_address_seq_no( $id )
	{
		$config = self::select_address($id);
		unset($config->where->deleted);
		$config->agg->count = 'account_id';
		$config->limit = 1;
		return $config;
	}
	
	function select_my_address()
	{
		$id = $this->model('Login')->GetLoginID();
		return self::select_address( $id, 1 );
	}
	
	function select_photo( $shop_id, $coupon_id, $seq_no )
	{
		$config = parent::select('t_photo');
		$config->limit  = 1;
		return $config; 
	}
	
	function select_forget_email( $email )
	{
		$date = date( 'Y-m-d H:i:s', strtotime( '-1 day' ) - date("Z") );
		
		$config = $this->select();
		$config->table = 't_forget';
		$config->where->email_forget = md5($email);
		$config->where->created = ">= $date";
		$config->order = 'created DESC'; 
		return $config;
	}
	
	function insert_account()
	{
		$_post = $this->form()->GetInputValueAll('form_register');
		
		$blowfish = new Blowfish();
		
		$config = parent::insert('t_account');
		$config->set->email_md5 = md5($_post->email);
		$config->set->email = $blowfish->Encrypt($_post->email);
		$config->set->password = md5($_post->password);
		
		return $config;
	}
	
	function insert_customer( $account_id )
	{
		//  Check
		if(!$account_id){
			$this->StackError("acount_id is empty.");
			return false;
		}
		
		//  Init set
		$_post = $this->form()->GetInputValueAll('form_register');
		
		$config = parent::insert('t_customer');
		
		$config->set->account_id	 = $account_id;
		$config->set->nick_name		 = $_post->nick_name;
		$config->set->last_name		 = $_post->last_name;
		$config->set->first_name	 = $_post->first_name;
		$config->set->gender		 = $_post->gender;
		$config->set->favorite_pref	 = $_post->favorite_pref;
		$config->set->birthday		 = $_post->birthday;
		
		return $config;
	}

	function insert_address( $account_id )
	{
		if(!$account_id){
			$this->StackError("acount_id is empty.");
			return false;
		}
		
		//  Posted value.
		$set = $this->form()->GetInputValueAll('form_address');
		$set = $this->Decode($set);
		
		//  Get seq_no
		$select = $this->config()->select_address_seq_no($account_id);
		$record = $this->pdo()->select($select);
		$seq_no = $record['COUNT(account_id)'];
		
		//  Added
		$set->account_id = $account_id;
		$set->seq_no     = $seq_no + 1;
		
		// TODO:
		// $this->pdo()->Quick("sum(account_id) <- t_address.account_id = $account_id");
		
		//  
		$config = parent::insert('t_address');
		$config->set = $set;
		$config->update = false;
		
		return $config;
	}
	
	function insert_buy( $cid, $num, $sid )
	{
		//  Check
		if( !$cid ){
			$this->StackError('coupon_id is empty.');
		}else if( !$num ){
			$this->StackError('num is empty.');
		}else if( !$sid ){
			$this->StackError('sid is empty.');
		}
		if( !$cid or !$num or !$sid ){
			return false;
		}
		
		//  Init
		$config = parent::insert('t_buy');
		
		//  table name
		$config->table = 't_buy';
		
		//  Get varlue from form
		$aid = $this->model('Login')->GetLoginID();
		$cid = $cid;
		$num = $num;
		$sid = $sid;
		
		//  Set
		$config->set->account_id = $aid;
		$config->set->coupon_id  = $cid;
		$config->set->num        = $num;
		$config->set->sid        = $sid;
		
		return $config;
	}
	
	function insert_coupon( $shop_id )
	{
		if(!$shop_id){
			$this->StackError("shop_id is empty.");
			return false;
		}
		
		$value = $this->form()->GetInputValueRawAll('form_coupon');
		
		foreach ( $value as $key => $val ){
			if( preg_match( '/^image_[a-z0-9]{32}$/i', $key ) ){
				unset( $value[$key] );
			}
		}
		unset($value->submit);
		unset($value->submit_button);
		
		$config = parent::insert('t_coupon');
		$config->set->shop_id = $shop_id;
		$config->set = $value;
		return $config;
	}
	
	function insert_photo( $shop_id, $coupon_id, $seq_no, $path )
	{
		$config = new Config();
		$config->set->shop_id = $shop_id;
		$config->set->seq_no  = $seq_no;
		$config->set->url     = $this->Path2URL($path);
		$config->update       = true;
		return $config;
	}
	
	function insert_forget_email( $email, $ip )
	{
		$config = parent::insert('t_forget');
		$config->set->ip_address   = $ip;
		$config->set->email_forget = md5($email);
		return $config; 
	}
	
	function update_uid( $aid, $uid )
	{
		if(!$aid){
			$this->StackError("account_id is empty.");
			return false;
		}
		
		if(!$uid){
			$this->StackError("uid is empty.");
			return false;
		}
		
		//  Init
		$config = parent::update('t_customer');
		
		//  Set
		$config->set->uid = $uid;
		$config->where->account_id = $aid;
		$config->limit = 1;
		
		return $config;
	}
	
	function update_shop( $shop_id, $form_name )
	{
		if(!$form_name){
			$this->StackError("form_name is empty.");
			return false;
		}
		
		//  Init
		$config = parent::update('t_shop');
		
		//  Get submitted form value
		$value = $this->form()->GetInputValueRawAll($form_name);
		
		//  Set
		$config->set = $value;
		$config->where->shop_id = $shop_id;
		$config->limit = 1;
		
		return $config;
	}
	
	function update_coupon( $coupon_id, $form_name )
	{
		if(!$coupon_id){
			$this->StackError("coupon_id is empty.");
			return false;
		}

		if(!$form_name){
			$this->StackError("form_name is empty.");
			return false;
		}
		
		$config = parent::update('t_coupon');
		
		//  Get submitted form value
		$value = $this->form()->GetInputValueRawAll($form_name);
		unset($value->coupon_id);
		
		//  Setting
		$config->set = $value;
		$config->where->coupon_id = $coupon_id;
		$config->limit = 1;
		$config->update = true;
		
		return $config;
	}
	
	function update_customer( $account_id )
	{
		$set = $this->form()->GetInputValueAll('form_customer');
		unset($set->submit);
		
		$config = parent::update('t_customer');
		$config->where->account_id = $account_id;
		$config->limit = 1;
		$config->set = $set;
		
		return $config;
	}
	
	function update_address( $account_id, $seq_no )
	{
		$form_name = "form_address_{$seq_no}";
		$set = $this->form()->GetInputValueAll($form_name);
		
		$config = parent::update('t_address');
		$config->where->account_id = $account_id;
		$config->where->seq_no     = $seq_no;
		$config->limit = 1;
		$config->set = $set;
		
		return $config;
	}

	function update_email()
	{
		//  Init value
		$id = $this->model('Login')->GetLoginID();
		$email = $this->form()->GetInputValue('email','form_email');
		//$email = $this->model('Blowfish')->Encrypt($email); // TODO: to modeling
		$bf = new Blowfish();
		
		//  Create config
		$config = parent::update('t_account');
		$config->where->id = $id;
		$config->limit = 1;
		$config->set->email = $bf->Encrypt($email);
		$config->set->email_md5 = md5($email);
		
		return $config;
	}
	
	function update_password( $account_id, $password = null )
	{
		if( !$password ){
			//  Get submit value from form.
			$password = $this->form()->GetValue('password','form_password');
		}
		
		//  Create config
		$config = parent::update('t_account');
		$config->where->id = $account_id;
		$config->limit = 1;
		$config->set->password = md5($password);
		
		return $config;
	}
	
	function update_password_forget( $account_id, $password )
	{
		
		//  Encrypt
		$browfish = new Blowfish();
		$password = $browfish->Encrypt($password);
		
		//  Create config
		$config = parent::update('t_account');
		$config->where->id = $account_id;
		$config->limit = 1;
		$config->set->password = $password;
		
		return $config;
		
	}
	
	function delete_coupon( $coupon_id )
	{
		if(!$coupon_id){
			$this->StackError("coupon_id is empty.");
			return false;
		}
		
		$config = parent::delete('t_coupon');

		//  Setting
		$config->where->coupon_id = $coupon_id;
		$config->limit = 1;
		
		return $config;
	}
}
