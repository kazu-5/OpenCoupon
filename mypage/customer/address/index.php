<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Get Args
$args = $this->GetArgs();
$seq_no = isset($args[0]) ? $args[0]: null;
$action = isset($args[1]) ? $args[1]: 'index';

//  Data use to template.
$data = new Config();

//  seq_no is check
if(!$seq_no){
	$data->message = '順番号が指定されていません。';
	$this->template('error.phtml',$data);
	return;
}

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Form address
$form_config = $this->config()->form_address( $id, $seq_no );
$form_name   = $form_config->name;
$this->form()->AddForm($form_config);

//  Save form_name
$data->form_name = $form_name;

//	Action
switch( $action ){
	case 'index':
		$this->template('index.phtml',$data);
		break;

	case 'confirm':
		if( $this->form()->Secure($form_name) ){
			//  OK
			$this->template('confirm.phtml',$data);
		}else{
			//  NG
			$this->template('form.phtml',$data);
		}
		break;
				
	default:
		$this->mark("undefined action. ($action)");
}
