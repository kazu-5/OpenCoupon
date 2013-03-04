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
$data = new Config();
$data->message = $message;
$this->template('index.phtml',$data);

$this->module('Transfer')->test();
