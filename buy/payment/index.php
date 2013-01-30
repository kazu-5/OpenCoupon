<?php
/* @var $this CouponApp */
$action = $this->GetAction();

//  Init credit card form
$config = $this->config()->form_payment();
$this->form()->AddForm($config);

//  Get ID
$id = $this->model('Login')->GetLoginId();
if(empty($id)){
	$this->mark("Does not loggedin.");
	return;
}

//  Switch action.
switch( $action ){
	case 'index':
		include('form_payment.phtml');
		break;
		
	case 'execute':
		
		//  Init
		$amount = 100;
		$config = $this->config()->credit( $id, $amount );
		
		//  Execute
		if( $io = $this->model('credit')->Auth($config) ){
			$io = $this->model('credit')->Commit($config);
		}
	//	$this->d( Toolbox::toArray($config) );
		
		$io      = $config->io;
		$sid     = $config->sid; // 決済ID. このIDを決済(Commit)する
		$uid     = $config->uid; // UserID. このIDで決済できるようになる
		$status  = $config->status;
		$message = $config->message;
		
		
		
		break;
		
	default:
		$this->mark("undefined action: $action");
		break;
}

