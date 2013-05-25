<?php
/* @var $this CouponApp */

$data = new Config();
$data->message = null;

if( $id = $this->model('Login')->GetLoginID() ){
	$io = $this->model('Login')->Logout();
	if( $io ){
		$data->message = 'ログアウトしました。';
	}else{
		$data->message = 'ログアウトに失敗しました。';
	}
}else{
	$data->message = 'ログインしていません。';
}

include('index.phtml');

