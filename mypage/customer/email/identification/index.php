<?php
/* @var $this CouponApp */

//  Init Config
$data = new Config();

//  Init Form
$form_config = $this->config()->form_email();
$this->form()->AddForm($form_config);

$form_config = $this->config()->form_email_identification();
$this->form()->AddForm($form_config);

//  form name
$form_name = $form_config->name;
$data->form_name = $form_name;

//  Do Action
if( $this->form()->Secure($form_name) ){
	$identification = $this->form()->GetInputValue( 'identification', $form_name );
	if( $identification === $this->GetSession('identification') ){
		
		//  Update
		$update = $this->config()->update_email();
		$num = $this->pdo()->update($update);
		
		if( $num !== false ){
			$data->class    = 'blue';
			$data->message  = 'メールアドレスを変更しました。';
			$data->template = 'commit.phtml';
		}else{
			$data->class    = 'red';
			$data->message  = 'メールアドレスの変更に失敗しました。';
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
	
	//確認コードを表示する
	$identification = $this->GetSession('identification');
	$this->mark( $identification );
	$this->d($status);
	
	$data->class    = 'red';
	$data->message  = 'もう一度送信ボタンを押して下さい。';
	$data->template = 'form.phtml';
}

include('index.phtml');
