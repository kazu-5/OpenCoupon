<?php
/* @var $this CouponApp */

//	redirect to site top if logged in
if ( !$this->model('Login')->GetLoginID() == null ){
	
	//	clear session data
	$this->SetSession('identification','');
	$this->SetSession('email_forget','');
	
	//	redirect to toppage
	header("location:/");
}


//	form setting
$form_config = $this->config()->form_forget_identification();
$this->form()->AddForm($form_config);


//  form name
$form_name = $form_config->name;
$data->form_name = $form_name;


//  Do Action
if( $this->form()->Secure($form_name) ){
	$identification = $this->form()->GetInputValue( 'identification', $form_name );
	if( $identification !== null and $identification !=='' and $identification === $this->GetSession('identification') ){
		
		//	retrieve account id from db based on email address
		$email  = $this->GetSession('email_forget');
		$config = $this->config()->select_account_email($email);
		$record = $this->pdo()->select($config);
		
		//	check whether the $email is exist in DB 
		if( !empty( $record ) ){
			
			//	extract an id with the $email
			$account_id = $record['id'];
			
			//	generate new password
			$password = $this->model('Password')->get();
			
			//  Update
			$update = $this->config()->update_password($account_id, $password);
			$res    = $this->pdo()->update($update);
				
		}else{
			$res = false;
		}
		
		if( $res !== false and $res !== 0 ){
		//	Successfully updated
			
			//	set message for template
			$data->class    = 'blue';
			$data->message  = "パスワードを再設定しました。新しいパスワードは $password です。";
			$data->template = 'commit.phtml';

		}else{
		//	failed to update

			//	set message for template (error)
			$data->class    = 'red';
			$data->message  = 'パスワードの再設定に失敗しました。';
			$data->template = 'failure.phtml';
		}

		//	clear SESSION (email, password)
		$this->SetSession('identification','');
		$this->SetSession('email_forget','');
		
	}else{
		$data->class    = 'red';
		$data->message  = '確認コードが一致しません。もう一度入力してください。';
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