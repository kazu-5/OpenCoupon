<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Get Action
$action = $this->GetAction();

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Form
$form_config = $this->config()->form_email( $id );
$this->form()->AddForm($form_config);

//  form name
$form_name = $form_config->name;

//  data
$data = new Config();
$data->form_name = $form_name;

//	Action
switch( $action ){
	case 'index':
		$data->template = 'form.phtml';
		break;
		
	case 'confirm':
		if( $this->form()->Secure($form_name) ){
			//  OK
			$data->template = 'confirm.phtml';
		}else{
			//  NG
			$data->template = 'form.phtml';
		}
		break;
		
	case 'commit':
		if( $this->form()->Secure($form_name) ){
			//  OK
			
			//  Print template
			if( $num !== false ){
				
				//  All done.
				$data->template = 'commit.phtml';

				//  Send mail
				$identification = md5(microtime());
				$this->SetSession('identification',$identification);
				$mail_config = $this->config()->mail_identification_email($identification);
				$io = $this->Mail($mail_config);
				$this->d($io);
				$this->d($mail_config);

				//  Clear of saved form value.
			//	$this->form()->Clear($form_name);
				
			}else{
				//  No good.
				$data->message = 'エラーが発生しました。';
				$data->template = 'form.phtml';
			}
		}else{
			//  NG
			$data->template = 'form.phtml';
		}
		break;
		
	default:
		$this->mark("undefined action. ($action)");
}

include('index.phtml');

