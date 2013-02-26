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
		
	case 'sendmail':
		if( $this->form()->Secure($form_name) ){
			//  OK
			
			//  Send mail
			$mail_config = $this->config()->mail_identification();
			$this->Mail($mail_config);
			
			//  Form
			$form_config = $this->config()->form_email_identification();
			$this->form()->AddForm($form_config);
			
			//  Print form
			$data->form_name = $form_config->name;
			$this->template('sendmail.phtml',$data);
		}else{
			//  NG
			$this->template('form.phtml',$data);
		}
		break;
		
	case 'commit':
		if( $this->form()->Secure($form_name) ){
			//  OK
			
			//  Update
			$update = $this->config()->update_email( $id );
			$num = $this->pdo()->update($update);
			
			//  Print template
			if( $num !== false ){

				//  Clear of saved form value.
				$this->form()->Clear($form_name);
					
				//  All done.
				$this->template('commit.phtml');
			}else{
				//  No good.
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
