<?php
/* @var $this CouponApp */

/**
 * Login check is "setting.php" worked.
 * 
if(!$id){
	$this->Location('app:/login');
	include('nologin.phtml');
	return;
}
*/

//  Get action
$action = $this->GetAction();

//  Get login id
$id = $this->model('Login')->GetLoginID();

//	Control
switch( $action ){
	default:
		include('index.phtml');
}
