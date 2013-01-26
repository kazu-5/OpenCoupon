<?php
/* @var $this Coupon */

$this->d($_POST);
$this->d($_GET);
$this->d($_REQUEST);
/*
$io = $this->form()->AddForm('app:/zlib/form/buy.form.php');
$io = $this->form()->AddForm('app:/zlib/form/login.form.php');
$io = $this->form()->AddForm($this->ConvertPath('app:/zlib/form/register.form.php'));
$this->mark($this->ConvertPath('app:/zlib/form/register.form.php'));
*/


$io = $this->form()->AddForm($this->config()->form_buy());
$io = $this->form()->AddForm($this->config()->form_login());
$io = $this->form()->AddForm($this->config()->form_register());

/**
 *  buy formのチェック
 *  	→CheckFormをしないと、送信されたフォームの値をセッションに保存しないため
 *  		→セッションにフォームの送信値を保存するにはCheckFormが必要
 */
if( $this->form()->Secure('form_buy')){
	// OK
	$this->mark();
}else{
	// NG
	$this->mark();
	print $this->form()->status();
	//$this->form()->Error();
	return;
}

// login check
if( $this->form->CheckForm('login') ){

	$mailaddr = $this->form->GetInputValue('mailaddr','login');
	$password = $this->form->GetInputValue('password','login');

	// OK
	$account_id = $this->Login( $mailaddr, $password );

	if( $account_id ){
		//	ログイン成功
		$this->SetMessage('login-ok','ログインしました。');

		//	Cookieにloginメールアドレスを保存する
		$cookie = $this->form->GetInputValue('cookie','login');
		if($cookie['ログイン状態を保持する']){
			$this->SetCookie('mailaddr',$mailaddr);
		}

		//	URL転送
		$this->Location('/buy/input');
	}else{
		//	ログイン失敗
		$this->SetMessage('login-ng','メールアドレスとパスワードが一致しません。');
		include('login.html');
	}
}else{
	// NG

	// フォーム
	include('login.html');
}

$this->form->Error();
