<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Get Action
$action = $this->GetAction();

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Form
$config = $this->config()->form_customer( $id );
$this->form()->AddForm($config);

//	Action
switch( $action ){
	case 'index':
		$this->template('form.phtml');
		break;
		
	case 'confirm':
		if( $this->form()->Secure('form_customer') ){
			//  OK
			$this->template('confirm.phtml');
		}else{
			//  NG
			$this->template('form.phtml');
		}
		break;
		
	case 'commit':
		if( $this->form()->Secure('form_customer') ){
			//  OK
			
			//  Update
			$update = $this->config()->update_customer( $id );
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
			$this->template('form.phtml');
		}
		break;
		
	default:
		$this->mark("undefined action. ($action)");
}
