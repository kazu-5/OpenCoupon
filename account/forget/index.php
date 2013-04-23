<?php
/* @var $this CouponApp */

//  Get Action
$action = $this->GetAction();

//	redirect to site top if logged in
if ( !$this->model('Login')->GetLoginID() == null ){
	//	TODO: Use, $this->module('Transfer')->Transfer('app:/login');
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

			//	retrieve 'sent records' for the email within last 24H from DB
			$config = $this->config()->select_forget_email($email);
			$record = $this->pdo()->select($config);
			
			//	extract the last sent record
			$last_sent = reset($record);
			
			//	check if the last sent is <= 5 min and sent# is < 3 for past 24H  
			if ((strtotime($last_sent['created']) + date("Z")) <= ( time()-300 ) and count($record) < 3 ){
			
				//	genarate identification code
				$identification = md5(microtime());
				
				//	store email and identification code to SESSION
				$this->SetSession('identification',$identification);
				$this->SetSession('email_forget',$email);
				
				//	get ip address of the client
				$ip = $_SERVER['REMOTE_ADDR'];
				
				//	send identification code to $email
				$mail_config = $this->config()->mail_identification_forget($email, $identification, $ip);
				$io = $this->Mail($mail_config);
			
				//	write email address and ip address to DB
				$insert = $this->config()->insert_forget_email($email, $ip);
				$res    = $this->pdo()->Insert($insert);
			}

			$data->template = 'commit.phtml';
		}
		break;
		
	default:
		$this->mark("undefined action. ($action)");
}

include 'index.phtml';
