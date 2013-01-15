<?php
/* @var $this CouponApp */
$action    = $this->GetBuyAction();
$coupon_id = $this->GetCouponId();
//$this->mark($action);
//$this->mark($coupon_id);

switch( $action ){
	case 'index':
		$record = $this->pdo()->Quick(" t_coupon.coupon_id = $coupon_id ");
		//$this->d( $this->pdo()->qus() );
		$this->d($record);
		//  Formの設定
		$coupon_config = new CouponConfig();
		$config = $coupon_config->form_buy();
		
		$this->form()->AddForm($config);
		
		/*
		if( $this->form()->Secure($form_name) ){
			$this->p('Submit form is successful!!');
		}else{
			$this->form()->debug($form_name);
		}
		*/
		include('buy.html');
		break;
}
