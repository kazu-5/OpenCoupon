<?php
/* @var $this CouponApp */

$this->mark('','controller');
$temp = array();
$temp['POST']    = $_POST;
$temp['GET']     = $_GET;
$temp['REQUEST'] = $_REQUEST;
$this->d($temp,'debug');

//  Init form
$config = $this->config()->form_register();
$this->form()->AddForm($config);

//  Check secure
if( $this->form()->Secure('form_register') ){
	$this->template('form_register_confirm.phtml');
}else{
	$this->template('form_register.phtml');
}

//  debug
$this->form()->debug('form_register');

