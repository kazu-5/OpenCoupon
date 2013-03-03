<?php
/* @var $this CouponApp */

//  Init
$action    = $this->GetBuyAction();
$coupon_id = $this->GetCouponId();
$this->mark("action=$action",'debug');
$this->mark("coupon_id=$coupon_id",'debug');

//  クーポンのrecord
$select = $this->config()->select_coupon_list($coupon_id);
$record = $this->pdo()->select($select);

//  templateに渡すdata
$data = new Config();

switch( $action ){
	case 'index':
		$this->template("index.phtml",array('coupon_list'=>$record));
		break;
		
	default:
		$this->mark("undefined action: $action");
}
