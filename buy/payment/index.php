<?php
/* @var $this CouponApp */
$action = $this->GetAction();

switch( $action ){
	case 'index':
		$config = $this->config()->form_payment();
		$this->form()->AddForm($config);
		include('form_payment.phtml');
		break;
		
	default:
		$this->mark("undefined action: $action");
		break;
}

