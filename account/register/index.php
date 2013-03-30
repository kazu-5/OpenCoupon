<?php
/* @var $this CouponApp */

//  Init Config
$data = new Config();

//  Init form
$form_config = $this->config()->form_register();
$this->form()->AddForm($form_config);

//  form name
$form_name = $form_config->name;

//  Set data
$data->form_name = $form_name;

//  Action
$action = $this->GetAction();
switch( $action ){
	case 'index':
		$data->template = 'form.phtml';
		break;
		
	case 'confirm':
		//  Check secure
		if(!$this->form()->Secure($form_name) ){
			$data->template = 'form.phtml';
		}else{
			$data->template = 'confirm.phtml';
		}
		break;
		
	case 'commit':
		//  Check secure
		if(!$this->form()->Secure($form_name) ){
			$data->template = 'form.phtml';
		}else{
			//  OK
			//  Print template
			if( $num !== false ){
			
				//  All done.
				$data->template = 'commit.phtml';
			
				//  Send mail
				$identification = md5(microtime());
				$this->SetSession('identification',$identification);
				$mail_config = $this->config()->mail_identification_register($identification);
				$io = $this->Mail($mail_config);
				$this->d($io);
				$this->d($mail_config);
				$this->d($identification);
				
				//  Clear of saved form value.
				//	$this->form()->Clear($form_name);
				
			}else{
				//  No good.
				$data->message = 'エラーが発生しました。';
				$data->template = 'form.phtml';
			}
			
			/*
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
			*/
			// 
			$this->template('commit.phtml',$args);
		}
		break;
}

include('index.phtml');
