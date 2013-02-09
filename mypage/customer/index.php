<?php

//$switch = $this->SmartArgs[0];
//$this->d($switch);
$action    = $this->GetAction();


//フォームの読み込み(フォーム定義の初期化)
//$this->form('mypage.form.php');

//$account_id	 = $this->GetSession('account_id');

$id = $this->model('Login')->GetLoginID();

/*
 if( $id = $this->model('Login')->GetLoginID() ){
$this->Location("app:/buy/$coupon_id/confirm");
}else{
$this->Location('app:/login');
}
*/

if(!$id){
	$this->Location('app:/login');
	include('nologin.phtml');
	return;
}

//	Control
switch( $action ){
	//メールアドレス変更処理
	//mailaddr_change ➡ mailaddr_confirm ➡ mailaddr_commit
	case 'mailaddr_change':
		include('mailaddr_change.phtml');
		/*
		 if( $this->form->Secure('mailaddr_change') ){
		include('mailaddr_confirm.html');
		//$this->Location('/buy/mailaddr_confirm');
		}
		//変更画面(メールアドレス、確認用メールアドレス)
		include('mailaddr_change.html');
		*/
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

		/*
		 case 'mailaddr_commit':
		if( $this->form->Secure('mailaddr_confirm') ){
		$mailaddr = $this->form->GetInputValue('mailaddr', 'mailaddr_change');

		//	t_account編集
		$update = array();
		$update['table'] = 't_account';
		// PKEY : id
		$update['where']['id'] = $this->GetSession('account_id');
		$update['set']['mailaddr_md5'] = md5($mailaddr);
		$update['set']['mailaddr'] = $this->enc($mailaddr);
		$update['update'] = true; // On Duplicate Update（PKEYが重複していたらUPDATEになる）
		$this->mysql->update($update);

		include('customer.html');

		}else{
		echo 'エラーです。';
		}

		break;
		*/

		//パスワード変更処理
		//password_change ➡ mailaddr_commit ➡ mailaddr_complete
	case 'password_change':
		/*
		 if( $this->form->Secure('password_change') ){
			$this->Location('/mypage/password_commit');
			}
			*/
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

	default:
		include('index.phtml');
}





	if( $this->form()->Secure('customer')  ){
	
		//customer_idを取得
		$config = $this->config()->select_my_customer();
		$t_coupon = $this->pdo()->select($config);
		
		$customer_id = $t_customer['customer_id'];
		
		//	t_customer編集
		$update = array();
		$update['table'] = 't_customer';
		// PKEY : customer_id
		//$update['set']['customer_id']	 = $customer_id;
		$update['where']['account_id'] = $account_id;
		//$update['set']['nickname']		 = $nickname;
		$update['set']['last_name']		 = $this->form->GetInputValue('last_name', 'customer');
		$update['set']['first_name']	 = $this->form->GetInputValue('first_name', 'customer');
		$update['set']['gender']		 = $this->form->GetInputValue('gender', 'customer');
		$update['set']['myarea']		 = $this->form->GetInputValue('myarea', 'customer');
		$year				= $this->form->GetInputValue('year', 'customer');
		$month				= $this->form->GetInputValue('month', 'customer');
		$day				= $this->form->GetInputValue('day', 'customer');
		$update['set']['birthday']		 = $year.'-'.$month.'-'.$day;
		$update['set']['address_seq_no'] = 1; //βリリースでは、住所が一つ
		//$update['update'] = true; // On Duplicate Update（PKEYが重複していたらUPDATEになる）
		$this->mysql->update($update);
	
	
		//	t_address編集
		$update = array();
		$update['table'] = 't_address';
		//$update['set']['customer_id'] = $customer_id; //PKEY
		$update['where']['customer_id'] = $customer_id; //PKEY
		$update['set']['seq_no'] = 1; //PKEY
		$update['set']['postal_code'] = $this->form->GetInputValue('postal_code', 'customer');
		$update['set']['pref'] = $this->form->GetInputValue('pref', 'customer');
		$update['set']['city'] = $this->form->GetInputValue('city', 'customer');
		$update['set']['address'] = $this->form->GetInputValue('address', 'customer');
		$update['set']['building'] = $this->form->GetInputValue('building', 'customer');
	
		//$update['update'] = true; // On Duplicate Update（PKEYが重複していたらUPDATEになる）
		$this->mysql->update($update);
	}
	
	$config = $this->config()->select_my_account();
	$t_account = $this->pdo()->select($config);
	
	$config = $this->config()->select_my_customer();
	$t_customer = $this->pdo()->select($config);
	$customer_id = $t_customer['customer_id'];
	
	$config = $this->config()->select_my_address();
	$t_address = $this->pdo()->select($config);
	//$address_id = $t_address['address_id'];
	//$this->d($t_address);
	
	//		$chip = $this->Enc('暗号化と暗号の復号化');
	//		$this->mark('暗号化='.$chip);
	//		$chip = $this->Dec($chip);
	//		$this->mark('復号化='.$chip);
	
	$mailaddr = $this->Dec($t_account['mailaddr']);
	
	$birthday = explode("-", $t_customer['birthday']);
	$this->d($birthday);
	
	$config = $this->config()->form_customer($t_customer, $t_address);
	$this->form()->AddForm($config);
		
	/*
	$this->form->InitInputValue('last_name',   $t_customer['last_name']);
	$this->form->InitInputValue('first_name',  $t_customer['first_name']);
	$this->form->InitInputValue('postal_code', $t_address['postal_code']);
	$this->form->InitInputValue('pref',		$t_address['pref']);
	$this->form->InitInputValue('city',        $t_address['city']);
	$this->form->InitInputValue('address',     $t_address['address']);
	$this->form->InitInputValue('building',    $t_address['building']);
	$this->form->InitInputValue('myarea',      $t_customer['myarea']);
	$this->form->InitInputValue('year',        $birthday[0]);
	$this->form->InitInputValue('month',       $birthday[1]);
	$this->form->InitInputValue('day',         $birthday[2]);
	$this->form->InitInputValue('gender',      $t_customer['gender']);
	*/
	//		$this->d($_SESSION);
	
	include('customer.phtml');
