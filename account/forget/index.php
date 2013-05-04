<?php
/* @var $this CouponApp */

//  Get Action
$action = $this->GetAction();

//	ここでログインチェックしてログイン済み状態の場合にはじく処理が必要かも

//	form setting
$form_config = $this->config()->form_forget();
$this->form()->AddForm($form_config);

//	form name (= 'form_forget')
$form_name = $form_config->name;

//	data
$data = new Config();
$data->form_name = $form_name;
$data->template = 'form.phtml';

//	デバッグ用機能
//$this->mark('ajoigeoaldo;p');//デバッグ用。本番環境では表示されないようになっている
//$this->d($_SERVER);//配列やオブジェクトをテーブル表示する機能。対象は()内で指定。

//	Action
switch( $action ){
	case 'index':
		$data->template = 'form.phtml';
		break;

	case 'confirm':
		if( $this->form()->Secure($form_name) ){
			//  OK
			$data->template = 'confirm.phtml';
		}else{
			//  NG
			$data->template = 'form.phtml';
		}
		break;

	case 'commit':
		if( $this->form()->Secure($form_name) ){
			//  OK
			
			//	get email address from form
			$email = $this->form()->GetValue('email','form_forget');
			
			//	retrieve account id from db based on email address
			$config = $this->config()->select_account_email($email);
			$record = $this->pdo()->select($config);
			//$this->d($record);
			
			$account_id = $record['id'];
			//$this->d($account_id);
			
			//	generate new password
			$password = $this->model('Password')->get();
			
			//	update db with new password
			$config = $this->config()->update_password($account_id, $password);
			$res = $this->pdo()->update($config);
			//$this->d($res);
			
			//	send new password to $email
			$mail_config = $this->config()->mail_forget($email, $password);
			//$this->d($mail_config);
			
			$res = $this->Mail($mail_config);
			$this->d($res);			

			$this->d($password);
			$data->template = 'commit.phtml';
		}
		break;
		
	default:
		$this->mark("undefined action. ($action)");
}


include 'index.phtml';