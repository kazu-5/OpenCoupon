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
			$this->d( Toolbox::toArray($insert) );
			$id = $this->pdo()->Insert($insert);
			$this->mark("Account ID:$id");

			//  Insert customer
			
			//  View
			$this->template('form_register_commit.phtml',array('form_name'=>$form_name));
		}
		break;
}






