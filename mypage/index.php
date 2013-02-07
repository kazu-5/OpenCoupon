<?php


//$switch = $this->SmartArgs[0];
//$this->d($switch);
$action    = $this->GetAction();


//フォームの読み込み(フォーム定義の初期化)
//$this->form('mypage.form.php');

//$account_id	 = $this->GetSession('account_id');

$id = $this->model('Login')->GetLoginID();

if(!$id){
	$this->Location('app:/login');
	include('nologin.phtml');
	return;
}

//	Control
switch( $action ){
	/*
	//メールアドレス変更処理
	//mailaddr_change ➡ mailaddr_confirm ➡ mailaddr_commit
	case 'mailaddr_change':
		include('mailaddr_change.phtml');
		break;

	case 'mailaddr_confirm':
		if( $this->form->Secure('mailaddr_change') ){
			$mailaddr = $this->form->GetInputValue('mailaddr', 'mailaddr_change');
			$mailaddr_confirm = $this->form->GetInputValue('mailaddr_confirm', 'mailaddr_change');

			//バリデーションチェック開始
			if( $mailaddr != $mailaddr_confirm ){
				$error = 'メールアドレスが確認用と一致していません。';
			}

			if($error){
				//	errorの文字列が表示されています。
				//	テストには、$this->mark($error); を使いましょう。
				//	うっかり消し忘れて本番にアップしても（←よくある）エンドユーザーに見えないから。
				//echo 'error';
				include('mailaddr_change.phtml');
				break;
			}

			//確認画面
			include('mailaddr_confirm.phtml');

		}else{
			include('mailaddr_change.phtml');
		}
		break;

	case 'mailaddr_change_mail':
		if( $this->form->Secure('mailaddr_confirm') ){
			$mailaddr = $this->form->GetInputValue('mailaddr', 'mailaddr_change');

			//	暗号作成
			$md5 = md5(time());

			//	セッション登録(メールアドレス)
			//	↓ $this->form->GetInputValue( input名, フォーム名 ); で取得できま
			$this->SetSession('md5',      $md5);
			$this->SetSession('mailaddr',      $mailaddr);

			//URL作成
			$url = 'http://'.$this->GetEnv('fqdn').'/account/mailaddr_change/commit?md5='.$md5;
			//				$this->d($url);

			//メール本文作成
			$body = $this->GetTemplate('mail/mailaddr_change.txt',array('url'=>$url));
			$to = $mailaddr;
			$subject = 'メールアドレス変更を受け付けました';
			$io = mb_send_mail( $to, $subject, $body );

			include('mailaddr_change_mail.phtml');
		}else{
			echo 'エラーです。';
		}
		break;

		//パスワード変更処理
		//password_change ➡ mailaddr_commit ➡ mailaddr_complete
	case 'password_change':
		//変更画面(現在のパスワード、新しいパスワード、確認用新しいパスワード)
		include('password_change.phtml');
		break;

	case 'password_commit':
		if( $this->form->Secure('password_change') ){
			$old_password = $this->form->GetInputValue('old_password', 'password_change');
			$new_password = $this->form->GetInputValue('new_password', 'password_change');
			$new_password_confirm = $this->form->GetInputValue('new_password_confirm', 'password_change');

			//現在のパスワードが正しいかをチェック
			$account_id = $this->GetSession('account_id');
			$select = array();
			$select['table'] = 't_account';
			$select['where']['id'] = $account_id;
			$select['limit'] = 1;
			$t_account = $this->mysql->select($select);
			if( $t_account['password'] != md5($old_password) ){
				$error = 'パスワードが異なります。';
			}

			//確認用の一致チェック
			if( $new_password != $new_password_confirm ){
				$error = 'パスワードが確認用と一致していません。';
			}

			if($error){
				include('password_change.phtml');
				break;
			}

			//	t_account編集
			$update = array();
			$update['table'] = 't_account';
			// PKEY : id
			$update['where']['id'] = $this->GetSession('account_id');
			$update['set']['password'] = md5($new_password);
			//$update['update'] = true; // On Duplicate Update（PKEYが重複していたらUPDATEになる）
			$this->mysql->update($update);

			//パスワード変更完了のメッセージ
			include('password_commit.phtml');
			break;
		}else{
			include('password_change.phtml');
		}
		break;

	case 'password_complete':
		if( $this->form->Secure('password_commit') ){

			include('customer.phtml');
			break;
		}else{
			echo 'エラーです。';
		}
		break;
*/
	default:
		include('index.phtml');
}
