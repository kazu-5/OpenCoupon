<?php
/* @var $this CouponApp */

$this->mark(null,'controller');

//  Init form
$config = $this->config()->form_login();
$this->form()->AddForm($config);

$config = $this->config()->form_register();
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
		
		list($id,$password) = $this->pdo()->quick("id, password <- t_account.email_md5 = $email");
		$this->mark("$id, $password");
		if( $pass === $password ){
			//  OK
			$this->model('Login')->SetLoginId($id);
			$cid = $this->GetCouponID();
			$url = $this->ConvertUrl("app:/buy/$cid");
			$this->template('login_success.phtml',array('url'=>$url));
			return;
		}else{
			//  NG
			$this->p('パスワードが一致しません。');
		}
	}
	
	$this->template('form_login.phtml');
	$this->template('form_register.phtml');
}
