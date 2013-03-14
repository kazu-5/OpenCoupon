<?php
/* @var $this CouponApp */

//  For developer notice.
$this->mark('','debug');

//  Login check
if(!$id = $this->model('Login')->GetLoginID()){
	$this->Location('app:/login');
	return false;
}
