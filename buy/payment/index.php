<?php
/* @var $this CouponApp */

$action    = $this->GetAction();
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
	$this->model('Transfer')->Set('app:/login')->Get();
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

//  Save payment coupon id
if(!$payment_coupon_id = $this->GetSession('payment_coupon_id')){
	$this->SetSession('payment_coupon_id',$coupon_id);
}else{
	if( $payment_coupon_id != $coupon_id ){
		$this->mark("Does not match payment_coupon_id.");
		return;
	}
}

//	Start transaction
$this->pdo()->Transaction();

try{	
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
			
			//	Check error
			if( !$io ){
				throw new OpAppException("Payment is failed.");
			}
			
			//  
			$io      = $config->io;
			$sid     = $config->sid; // 決済ID. このIDを決済(Commit)する
			$uid     = $config->uid; // UserID. このIDで決済できるようになる
			$status  = $config->status;
			$message = $config->message;
			
			//  insert t_buy
			$insert = $this->config()->insert_buy( $id, $cid, $sid );
			if(!$id = $this->pdo()->insert($insert) ){
				throw new OpAppException("Database's insert is failed.");
			}
			
			//  update t_customer.uid
			$update = $this->config()->update_uid( $id, $uid );
			$io = $this->pdo()->update($update);
			if(  $io === false ){
				throw new OpAppException("Database's update t_customer.uid is failed.");
			}
			
			//  print thanks page
			include("thanks.phtml");
			
			//  All completed
			$this->form()->clear('form_buy');
			$this->form()->clear('form_address');
			$this->form()->clear('form_payment');
			break;
			
		case 'cancel':
			$this->SetSession('payment_coupon_id',null);
			$this->mark('Canceled this coupon.');
			break;
			
		default:
			$this->mark("undefined action: $action");
			break;
	}

}catch( OpAppException $e ){
	//	Rollback
	$this->pdo()->Rollback();
	
	//	Error page
	$data = new Config();
	$data->message = $e->getMessage();
	$this->Template('error.phtml',$data);
}

$this->pdo()->Commit();
