<?php
/* @var $this CouponApp */

//  Get Action
$action = $this->GetAction();

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Login check has been done in the "setting.php"

//	Control
switch( $action ){
	case 'index':
		$config = $this->config()->form_customer( $id );
		$this->form()->AddForm($config);
		$this->template('index.phtml');
		break;
		
	default:
		$this->mark("undefined action. ($action)");
}
