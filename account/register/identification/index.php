<?php
/* @var $this CouponApp */

//  Init Config
$data = new Config();

//  Init Form
$form_config = $this->config()->form_register();
$this->form()->AddForm($form_config);

$form_config = $this->config()->form_register_identification();
$this->form()->AddForm($form_config);

//  form name
$form_name = $form_config->name;
$data->form_name = $form_name;

//  Do Action
if( $this->form()->Secure($form_name) ){
	$identification = $this->form()->GetInputValue( 'identification', $form_name );
	if( $identification === $this->GetSession('identification') ){
		
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
		
		/*
		//  Update
		$update = $this->config()->update_email();
		$num = $this->pdo()->update($update);
		*/
		
		if( $num !== false ){
			$data->class    = 'blue';
			$data->message  = 'ユーザ登録を完了しました。';
			$data->template = 'commit.phtml';
		}else{
			$data->class    = 'red';
			$data->message  = 'ユーザ登録に失敗しました。';
			$data->template = 'form.phtml';
		}
		
	}else{
		$data->class    = 'red';
		$data->message  = '確認コードが一致しません。';
		$data->template = 'form.phtml';
	}
}else{
	switch( $status = $this->form()->GetStatus($form_name) ){
		case '':
			break;
		default:
	}
	$this->d($status);
	$this->d($this->GetSession('identification'));
	$data->class    = 'red';
	$data->message  = 'もう一度送信ボタンを押して下さい。';
	$data->template = 'form.phtml';
}

include('index.phtml');
