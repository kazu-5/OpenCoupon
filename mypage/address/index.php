<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Get Action
$action = $this->GetAction();

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Form
$config = $this->config()->form_address( $id );
$this->form()->AddForm($config);

//  form name
$form_name = $form_config->name;

//  data
$data = new Config();
$data->form_name = $form_name;

//	Action
switch( $action ){
	case 'index':
		$this->template('form.phtml');
		break;

	case 'confirm':
		if( $this->form()->Secure('form_address') ){
			//  OK
			$this->template('confirm.phtml',$data);
		}else{
			/*
			$this->mark();
			$this->form()->getstatus('form_address');
			$this->form()->debug('form_address');
			*/
			//  NG
			$this->template('form.phtml',$data);
		}
		break;
				
	default:
		$this->mark("undefined action. ($action)");
}
