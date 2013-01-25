<?php
/* @var $this CouponApp */

$this->mark(null,'controller');

//  Init form
$config = $this->config()->form_login();
$this->form()->AddForm($config);

//  Check login
if( $id = $this->model('Login')->GetLoginId() ){
	$this->p('既にログインしています。');
}else{
	//  Check secure
	if( $this->form()->Secure('form_login') ){
		//  Check account
		$email = $this->form()->GetValue('email',   'form_login');
		$pass  = $this->form()->GetValue('password','form_login');
		$email = md5($email);
		$pass  = md5($pass);
		
		$password = $this->pdo()->quick("password <- t_account.email_md5 = $email");
		if( $pass === $password ){
			$this->p('ログインしました。');
			return;
		}else{
			$this->p('パスワードが一致しません。');
		}
	}
	$this->template('form_login.phtml');
}
