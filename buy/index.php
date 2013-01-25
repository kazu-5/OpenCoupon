<?php
/* @var $this CouponApp */
$action    = $this->GetBuyAction();
$coupon_id = $this->GetCouponId();
$this->mark($action,'debug');
$this->mark($coupon_id,'debug');

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
				$this->Location('app:/buy/confirm');
			}else{
				$this->Location('app:/login');
			}
			
		}else{
			include('buy.html');
		}
		break;
}
