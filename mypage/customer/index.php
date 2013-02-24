<?php
/* @var $this CouponApp */

//  Login check has been done in the "setting.php"

//  Get Action
$action = $this->GetAction();

//  Get ID
$id = $this->model('Login')->GetLoginID();

//  Form
$config = $this->config()->form_customer($id);
$this->form()->AddForm($config);

$config = $this->config()->form_address_change($id, 1);
$this->form()->AddForm($config);

$config = $this->config()->form_address($id, 1);
$this->form()->AddForm($config);

//  Button
$config = $this->config()->button_add_address();
$this->form()->AddForm($config);


//$records = $this->config()->select_address($id);
//  customer table
$config = $this->config()->select_address($account_id);
$t_address = $this->pdo()->select($config);
$this->d($t_address);

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
			$this->d($update);
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
	
	case 'add':
		if( $this->form()->Secure('button_add_address') ){
			//  OK
			//  Update
			/*
			$update = $this->config()->update_address( $id);
			$num = $this->pdo()->update($update);
			*/
			
			$this->template('add.phtml');
		}else{
			//  NG
			$this->template('form.phtml');
		}
		break;
		
	case 'add_confirm':
		if( $this->form()->Secure('form_address') ){
			//  OK
			// Insert
			$insert = $this->config()->insert_address($id);
			$num = $this->pdo()->insert($insert);
			
			//  Update
			/*
			$update = $this->config()->update_address( $id);
			$num = $this->pdo()->update($update);
			*/
				
			$this->template('add_confirm.phtml');
		}else{
			//  NG
			$this->template('form.phtml');
		}
		break;
	
	/*
	case 'address_input':
		if( $this->form()->Secure('button_add_address') ){
			//  OK
			$update = $this->config()->insert_address( $id );
		}else{
			//  NG
		}
		break;
	
	case 'address_add':
		if( $this->form()->Secure('button_add_address') ){
			//  OK
			$update = $this->config()->insert_address( $id );
		}else{
			//  NG
		}
		break;
	*/
			
	default:
		$this->mark("undefined action. ($action)");
}
