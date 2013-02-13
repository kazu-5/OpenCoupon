<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Get Action
$action = $this->GetAction();

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Form
$config = $this->config()->form_customer( $id );
$this->form()->AddForm($config);

//	Control
switch( $action ){
	case 'index':
		$this->template('index.phtml');
		break;
		
	case 'confirm':
		$this->template('confirm.phtml');
		break;
		
	case 'commit':
		$this->template('commit.phtml');
		break;
		
	default:
		$this->mark("undefined action. ($action)");
}
