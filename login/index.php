<?php
/* @var $this CouponApp */

//  Check if logged-in.
if( $id = $this->model('Login')->GetLoginId() ){
	$data->message = '既にログインしています。';
	$this->template('index.phtml',$data);
	return;
}

//  init
$data = new Config();

//  form login
$config = $this->config()->form_login();
$this->form()->AddForm($config);

//  form register
$config = $this->config()->form_register();
$this->form()->AddForm($config);

//  Check secure
if( $this->form()->Secure('form_login') ){
	
	//  Get submit value
	$email = $this->form()->GetValue('email',   'form_login');
	$pass  = $this->form()->GetValue('password','form_login');
	
	//  Get registered value.
	$select = $this->config()->select_login($email,$pass);
	$record = $this->pdo()->select($select);
	
	//  Check password.
	if( $record ){
		//  OK
		$this->model('Login')->SetLoginId($record['id']);
		include('login_ok.phtml');
		return;
	}else{
		//  NG
		$data->message = 'パスワードが一致しません。';
	}
}

//  Print default template
$this->template('index.phtml',$data);

