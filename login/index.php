<?php
/* @var $this CouponApp */

//  init
$data = new Config();

//  form login
$config = $this->config()->form_login();
$this->form()->AddForm($config);

//  form register
$config = $this->config()->form_register();
$this->form()->AddForm($config);

//  Check login
if( $id = $this->model('Login')->GetLoginId() ){
	$data->message = '既にログインしています。';
	$this->template('index.phtml',$data);
	return;
}

//  Check secure
if( $this->form()->Secure('form_login') ){
	
	//  Get submit value
	$email = $this->form()->GetValue('email',   'form_login');
	$pass  = $this->form()->GetValue('password','form_login');
	
	//  Convert to md5
	$email = md5($email);
	$pass  = md5($pass);
	
	//  Get registered value.
	list( $id, $password ) = $this->pdo()->quick("id, password <- t_account.email_md5 = $email");
	
	$config = new Config();
	$config->table = 't_account';
	$config->column->password = 'password';
	$config->where->email_md5 = $email;
	$record = $this->pdo()->Select($config);
	
	//  Check password.
	if( $pass === $password ){
		//  OK
		$this->model('Login')->SetLoginId($id);
	//	$cid = $this->GetCouponID();
	//	$url = $this->ConvertUrl("app:/buy/$cid");
	//	$this->template('login_success.phtml',array('url'=>$url));
	//	$this->module('Transfer')->Forward();
		include('login_ok.phtml');
		return;
	}else{
		//  NG
		$data->message = 'パスワードが一致しません。';
	}
}

//  Print default template
$this->template('index.phtml',$data);
