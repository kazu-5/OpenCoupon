<?php
/* @var $this CouponApp */

if( $id = $this->model('Login')->GetLoginID() ){
	$io = $this->model('Login')->Logout();
	if( $io ){
		$message = 'ログアウトしました。';
	}else{
		$message = 'ログアウトに失敗しました。';
	}
}else{
	$message = 'ログインしていません。';
}

//  Transfer module
$data = new Config();
$data->class   = 'red';
$data->message = $message;
$this->module('Transfer')->Set('app:/login',$data);
