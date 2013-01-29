<?php
/* @var $this CouponApp */
$action    = $this->GetBuyAction();
$coupon_id = $this->GetCouponId();
$this->mark("action=$action",'debug');
$this->mark("coupon_id=$coupon_id",'debug');

switch( $action ){
	case 'index':
		//  クーポンのrecord
		$record = $this->pdo()->Quick(" t_coupon.coupon_id = $coupon_id ");
		
		//  Formの設定
		$config = $this->config()->form_buy();
		$this->form()->AddForm($config);
		
		//  Check secure
		if( $this->form()->Secure('form_buy') ){
			
			//  Login Check
			if( $id = $this->model('Login')->GetLoginID() ){
				$this->Location("app:/buy/$coupon_id/confirm");
			}else{
				$this->Location('app:/login');
			}
			
		}else{
			include('buy.phtml');
		}
		break;
		
	case 'confirm':
		$id = $this->model('Login')->GetLoginID();
		$config = $this->config()->form_buy_confirm( $id, $coupon_id );
		$this->form()->AddForm($config);
		include('buy_confirm.phtml');
		break;
}
