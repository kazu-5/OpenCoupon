<!-- index.php -->
<?php
/* @var $this CouponApp */

$action = $this->GetAction();
//$this->mark($action);

switch( $action ){
	case 'index':
		
		//  Get record.
		$coupon_id = $this->GetDefaultCouponId();
		$t_coupon  = $this->GetTCoupon($coupon_id);
		$t_shop    = $this->GetTShop($t_coupon['shop_id']);
		
		//$this->d($t_coupon);
		//$this->d($t_shop);
		
		//  Print template.
		$this->template('coupon_top.phtml',array(
				't_coupon'=>$t_coupon,
				't_shop'=>$t_shop,
			)
		);
		break;
}
