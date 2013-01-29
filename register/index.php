<?php
/* @var $this CouponApp */

//  Init form
$form_name = 'form_register';
$config = $this->config()->form_register();
$this->form()->AddForm($config);

$action = $this->GetAction();

switch( $action ){
	case '':
	case 'index':
		$this->template('form_register.phtml',array('form_name'=>$form_name));
		break;
		
	case 'confirm':
		//  Check secure
		if(!$this->form()->Secure($form_name) ){
			$this->template('form_register.phtml',array('form_name'=>$form_name));
		}else{
			$this->template('form_register_confirm.phtml',array('form_name'=>$form_name));
		}
		break;
		
	case 'commit':
		//  Check secure
		if(!$this->form()->Secure($form_name) ){
			$this->template('form_register.phtml',array('form_name'=>$form_name));
		}else{
			
			//  Insert account
			$insert = new Config();
			$insert = $this->config()->insert_account();
			$id = $this->pdo()->Insert($insert);
			if( $id === false ){
				$this->StackError("ID is false.");
				return;
			}

			//  Insert customer
			$insert = new Config();
			$insert = $this->config()->insert_customer( $id );
			$id = $this->pdo()->Insert($insert);
			if( $id === false ){
				$this->StackError("ID is false.");
				return;
			}
			
			//  coupon_id
			$coupon_id = $this->GetSession('current_coupon_id');
			
			//  Form clear
			$this->form()->Clear('form_register');
			
			//  Transfer
			$args = array();
			$args['form_name'] = $form_name;
			$args['count']     = 5;
			$args['url']       = $this->ConvertUrl("app:/login");
			$this->template('form_register_commit.phtml',$args);
		}
		break;
}






