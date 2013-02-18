<?php
/* @var $this CouponApp */

$action    = $this->GetBuyAction();
$coupon_id = $this->GetCouponId();
$this->mark("action=$action",'debug');
$this->mark("coupon_id=$coupon_id",'debug');

//  Init credit card form
$form_config = $this->config()->form_payment();
$this->form()->AddForm($form_config);

//  Check login ID
$id = $this->model('Login')->GetLoginId();
if(empty($id)){
	$this->mark("Not logged in.");
	return;
}

//  Check coupon_id
if(empty($coupon_id)){
	$this->mark("Not set coupon_id.");
	return;
}

//  Get quantity
$form_config = $this->config()->form_buy($coupon_id);
$this->form()->AddForm($form_config);
$quantity = $this->form()->GetValue('quantity',$form_config->name);
if(empty($quantity)){
	$this->mark("Not set quantity.");
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
		$this->d( Toolbox::toArray($config), 'debug');
	
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
		$update = $this->config()->update_uid( $id, $uid );
		$io = $this->pdo()->update($update);
		if(  $io === false ){
			$this->StackError("update t_customer.uid failed");
			return false;
		}
		
		//  print thanks page
		include("thanks.phtml");
		
		//  All completed
		$this->form()->clear('form_buy');
		$this->form()->clear('form_address');
		$this->form()->clear('form_payment');
		
		break;
		
	default:
		$this->mark("undefined action: $action");
		break;
}

