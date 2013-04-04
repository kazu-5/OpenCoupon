<?php

/* @var $this CouponApp */

/*
//  Get Action
$action = $this->GetAction();
*/

//	ここでログインチェックしてログイン済み状態の場合にはじく処理が必要かも

//	form setting
$form_config = $this->config()->form_forget_identification();
$this->form()->AddForm($form_config);

//  form name
$form_name = $form_config->name;
$data->form_name = $form_name;

//  Do Action
if( $this->form()->Secure($form_name) ){
	$identification = $this->form()->GetInputValue( 'identification', $form_name );
	if( $identification === $this->GetSession('identification') ){

		//	retrieve account id from db based on email address
		$email  = $this->GetSession('email_forget');
		$config = $this->config()->select_account_email($email);
		$record = $this->pdo()->select($config);
		$this->d($record['id']);
			
		$account_id = $record['id'];
		$this->d($account_id);
			
		//	generate new password
		$password = $this->model('Password')->get();
		$this->d(md5($password));
		
		//  Update
		$update = $this->config()->update_password($account_id, $password);
		$res    = $this->pdo()->update($update);
		$this->d($update);

		if( $res !== false ){
		//	Successfully updated
			
			/*
			//	send new password to $email
			$mail_config = $this->config()->mail_forget($email, $password);
			$this->d($mail_config);
				
			$io = $this->Mail($mail_config);
			$this->d($io);
			*/
			
			$this->d($this->GetSession('identification'));//for test
			$this->d($this->GetSession('email_forget'));//for test
			
			//	clear SESSION (email, password)
			$this->SetSession('identification','');
			$this->SetSession('email_forget','');
			$this->d($this->GetSession('identification'));//for test
			$this->d($this->GetSession('email_forget'));//for test
			
			//	set message for template
			$data->class    = 'blue';
			$data->message  = "パスワードを再設定しました。新しいパスワードは $password です。";
			$data->template = 'commit.phtml';

		}else{
		//	failed to update

			//	set message for template (error)
			$data->class    = 'red';
			$data->message  = 'パスワードの再設定に失敗しました。';
			$data->template = 'form.phtml';
		}

	}else{
		$data->class    = 'red';
		$data->message  = '確認コードが一致しません。もう一度入力してください。';
		$data->template = 'form.phtml';
	}

}else{

	//確認コードを表示する
	$identification = $this->GetSession('identification');
	$this->mark( $identification );
	$this->d($status);

	$data->class    = 'red';
	$data->message  = 'もう一度送信ボタンを押して下さい。';
	$data->template = 'form.phtml';
}

include('index.phtml');