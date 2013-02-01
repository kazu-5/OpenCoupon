<?php
/* @var $this CouponApp */

$action = $this->GetMyShopAction();
$this->mark($action,'controller');

switch( $action ){
	case 'index':
		$this->template("index.phtml");
		break;
		
	default:
		if( file_exists("{$action}.phtml") ){
			$this->template("{$action}.phtml");
			return;
		}
		$this->mark("Does not define action. ($action)");
}