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
		break;
}
