<?php
/* @var $this CouponApp */

$action = $this->GetMyShopAction();
$this->mark($action,'controller');

switch( $action ){
	case 'index':
		
		//  My shop ID.
		$shop_id = $this->GetShopID();
		
		//  My shop info.
		$t_shop  = $this->GetTShop($shop_id);
		
		//  My coupon list.
		$t_list = $this->GetCouponListByShopId($shop_id);
		
		//  Data to be passed.
		$data->t_shop = $t_shop;
		$data->t_list = $t_list;
		//$this->d($data->t_list);
		
		$this->template("index.phtml",$data);
		break;
		
	default:
		if( file_exists("{$action}.phtml") ){
			$this->template("{$action}.phtml");
			return;
		}
		$this->mark("Does not define action. ($action)");
}