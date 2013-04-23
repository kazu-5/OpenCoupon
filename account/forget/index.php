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

			//	genarate identification code
			$identification = md5(microtime());
				
			//	store email and identification code to SESSION
			$this->SetSession('identification',$identification);
			$this->SetSession('email_forget',$email);
				
			//	send identification code to $email
			$mail_config = $this->config()->mail_identification_forget($email, $identification);
			$io = $this->Mail($mail_config);
			$this->d($io);//for test
			$this->d($mail_config);//for test

			$data->template = 'commit.phtml';
		}
		break;
		
	default:
		$this->mark("undefined action. ($action)");
}


include 'index.phtml';