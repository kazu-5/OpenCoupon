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
			$last_sent = $record[0];
			
			//	set limit_sec and limit_count for anti-tamper check  
			$limit_sec   = $this->Config()->GetForgetLimitSecond();
			$limit_count = $this->Config()->GetForgetLimitCount();
			
			if( $limit_sec >= 86400 or $limit_sec <= 0 ){
				$limit_sec = 300;
			}
			
			if( $limit_count <=0 ){
				$limit_count = 3;
			}
			
			//	anti-tamper check for interval and sent#
			if ((strtotime($last_sent['created']) + date("Z")) <= ( time()-$limit_sec ) and count($record) < $limit_count ){
				//	OK
				
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
				
				//	show commit page
				$data->template = 'commit.phtml';
			}else{
				//	NG
				
				//	prepare the values for display
				$data->limit_sec   = round($limit_sec / 60);
				$data->limit_count = $limit_count;
				
				//	show error page
				$data->template = 'failure.phtml';
			}
		}
		break;
		
	default:
		$this->mark("undefined action. ($action)");
}

include 'index.phtml';
