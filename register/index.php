<?php
/* @var $this CouponApp */

//  Init form
$form_name = 'form_register';
$config = $this->config()->form_register();
$this->form()->AddForm($config);

//  Check secure
if( $this->form()->Secure($form_name) ){
	$this->mark('secure');
	$this->template('form_register_confirm.phtml',array('form_name'=>$form_name));
//	$this->template('form_register.phtml',array('form_name'=>$form_name));
}else{
	$this->mark('not secure');
	$this->template('form_register.phtml',array('form_name'=>$form_name));
}

//  debug
//$this->form()->debug('form_register');
