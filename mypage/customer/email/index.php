<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Get Action
$action = $this->GetAction();

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Form
$form_config = $this->config()->form_password( $id );
$this->form()->AddForm($form_config);

//  form name
$form_name = $form_config->name;

//  data
$data = new Config();
$data->form_name = $form_name;

//	Action
switch( $action ){
	case 'index':
		$this->template('form.phtml',$data);
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
		
	case 'commit':
		if( $this->form()->Secure($form_name) ){
			//  OK
			
			//  Update
			$update = $this->config()->update_password( $id );
			$num = $this->pdo()->update($update);
			
			if( $num !== false ){
				$this->template('commit.phtml');
			}else{
				$data = new Config();
				$data->message = 'エラーが発生しました。';
				$this->template('form.phtml',$data);
			}
		}else{
			//  NG
			$this->template('form.phtml',$data);
		}
		break;
		
	default:
		$this->mark("undefined action. ($action)");
}
