<?php
/* @var $this CouponApp */

//  Get Action
$action = $this->GetAction();


//	redirect to site top if logged in
if ( !$this->model('Login')->GetLoginID() == null ){
	header("location:/");
}


//	form setting
$form_config = $this->config()->form_forget();
$this->form()->AddForm($form_config);


//	form name (= 'form_forget')
$form_name = $form_config->name;


//	data
$data = new Config();
$data->form_name = $form_name;


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

			
			//ここにDB照会して送信間隔や上限回数判定する処理をif文で入れる
			
			$config = $this->config()->select_forget_email($email);
			$record = $this->pdo()->select($config);
			$this->d($record);//for test
			
			
			//ここから下をif文で囲む予定
			
			//	genarate identification code
			$identification = md5(microtime());
				
			//	store email and identification code to SESSION
			$this->SetSession('identification',$identification);
			$this->SetSession('email_forget',$email);
				
			//	send identification code to $email
			$mail_config = $this->config()->mail_identification_forget($email, $identification);
			$io = $this->Mail($mail_config);
			//$this->d($io);//for test
			//$this->d($mail_config);//for test
			
			
			//ここにDBのt_forgetに書き込む処理を入れる
			
			//この辺までif文書で囲む予定
			

			$data->template = 'commit.phtml';
			
		}else{
			//  NG
			$data->template = 'form.phtml';
		}
		break;

	default:
		$this->mark("undefined action. ($action)");
}


include 'index.phtml';