<?php
/* @var $this CouponApp */

$this->mark(null,'controller');

if( $id = $this->model('Login')->GetLoginId() ){
	$this->mark('loggedin','controller');
}else{
	$this->mark('Not login','controller');
	$this->template('form_login.phtml');
}


$config = $this->config()->form_login();
$this->form()->AddForm($config);


