<?php

//
$id = $this->model('Login')->GetLoginID();
if( !$id ){
	$this->Location('app:/myshop/error-login');
}

$this->template('breadcrumb.phtml');
