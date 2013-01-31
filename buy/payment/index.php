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

//  Get Coupon ID
$cid = $this->GetCouponID();

//  debug
$this->mark("account_id: $id");
$this->mark("coupon_id: $cid");

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
		$this->d( Toolbox::toArray($config) );
	
		if( !$io ){
			$this->StackError("Payment failed.");
			return;
		}
		
		//  
		$io      = $config->io;
		$sid     = $config->sid; // 決済ID. このIDを決済(Commit)する
		$uid     = $config->uid; // UserID. このIDで決済できるようになる
		$status  = $config->status;
		$message = $config->message;
		
		//  insert t_buy
		$insert = $this->config()->insert_buy( $id, $cid, $sid );
		$this->pdo()->insert($insert);
		
		//  update t_customer.uid
		$update = $this->config()->update_uid();
		$this->pdo()->update($update);
		
		break;
		
	default:
		$this->mark("undefined action: $action");
		break;
}

