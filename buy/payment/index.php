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
		$amount = 100;
		$config = $this->config()->credit( $id, $amount );
		$io = $this->model('credit')->Auth($config);
		$this->mark($io);
		break;
		
	default:
		$this->mark("undefined action: $action");
		break;
}

