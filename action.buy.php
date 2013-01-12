<?php

class buy extends Coupon {
	
	function __construct( &$app, $args=null )
	{
		$this->mark();
		
		$this->env   = & $app->env;
		$this->form  = & $app->form;
		$this->mysql = & $app->mysql;
		
		//	フォームの読み込み(フォーム定義の初期化)
		$this->form('buy.form.php');
	}
	
	function Action(){
		
		//
		$action = $this->GetAction();
		$this->mark($action);
		
		//	
		if(!$coupon_id = $this->GetCouponId()){ return false; }
		
		//
		switch($action){
			case '':
				$this->doIndex();
				break;
				
			case 'input':
				$this->doInput();
				break;
				
			case 'confirm':
				$this->doConfirm();
				break;
				
			case 'payment':
				$this->doPayment();
				break;
				
			default:
				$this->mark("undefined action.(action=$action)");
				break;
		}
	}
	
	function GetAction(){
		
		$args = $_SERVER['NEW_WORLD']['args'];
		
		if( preg_match('/^([0-9]+)$/', @$args[0], $match) ){
			$action = '';
			$coupon_id = $match[1];
			$this->SetSession('coupon_id',$coupon_id);
		}else{
			$action = @$args[0];
			$coupon_id = $this->GetSession('coupon_id');
		}
		
		$this->coupon_id = $coupon_id;
		
		return $action;
	}
	
	function GetCouponRecord( $coupon_id ){
		
		$select['table'] = 't_coupon';
		$select['where']['coupon_id'] = $coupon_id;
		$select['limit'] = 1;
		
		return $this->mysql->select($select);
	}
	
	function GetCouponId(){
		
		if(!$coupon_id = $this->coupon_id){
			$message = 'クーポンIDが選択されていません';
			$message_color = 'red';
			include('error.html');
			return false;
		}
		
		return $coupon_id;
	}
	
	function checkLogin(){
		if( !$this->isLoggedin() ){
			include($this->GetEnv('op-approot') . '/zlib/template/login_form.html');
			return false;
		}
		return true;
	}
	
	function doIndex(){
		$this->form('~/zlib/form/buy.form.php');
		
		//	クーポンID
		$coupon_id = $this->coupon_id;
		
		//	初期設定（InitInputValueは最初に1回だけ値を初期化する。SetInputValueは毎回設定する）
		$this->form->InitInputValue('quantity',$quantity, 'buy');
		
		//	これはいるんだっけ？
		$this->form->SetInputValue( 'coupon_id', $coupon_id, 'buy' );
		
		//	クーポン情報を取得
		$record = $this->GetCouponRecord( $coupon_id );
		
		$buy_flag = true;
		$this->SetSession('buy_flag',      $buy_flag);
		$this->d($buy_flag);
		
		$register_flag = false;
		$this->SetSession('register_flag',$register_flag);
		//	
		include('buy.html');
	}
	
	function doInput(){
		$this->form('~/zlib/form/buy.form.php');
		
		//	login check
		if(!$this->checkLogin()){ return false; }
		
		//	logged in.
		$coupon_id = $this->form->GetInputValue('coupon_id', 'buy');
		$quantity  = $this->form->GetInputValue('quantity',  'buy');
		
		$account_id	 = $this->GetSession('account_id');
		$customer	 = $this->GetTCustomerByAccountId($account_id);
		$seq_no		 = $this->GetAddressSeqNoByAccountId( $account_id );
		$address	 = $this->GetTAddressByAccountId( $account_id, $seq_no );
		
		$this->form->SetInputValue('quantity',$quantity,'confirm');
		
		$this->form->InitInputValue('last_name',	$address['last_name'],		'confirm');
		$this->form->InitInputValue('first_name',	$address['first_name'],		'confirm');
		$this->form->InitInputValue('postal_code',	$address['postal_code'],	'confirm');
		$this->form->InitInputValue('pref',			$address['pref'],			'confirm');
		$this->form->InitInputValue('city',			$address['city'],			'confirm');
		$this->form->InitInputValue('address',		$address['address'],		'confirm');
		$this->form->InitInputValue('building',		$address['building'],		'confirm');
		
		if($address){
			$this->form->InitInputValue( 'last_name',   $address['last_name'],   'confirm');
			$this->form->InitInputValue( 'first_name',  $address['first_name'],  'confirm');
			$this->form->InitInputValue( 'postal_code', $address['postal_code'], 'confirm');
			$this->form->InitInputValue( 'pref',        $address['pref'],        'confirm');
			$this->form->InitInputValue( 'city',        $address['city'],        'confirm');
			$this->form->InitInputValue( 'address',     $address['address'],     'confirm');
			$this->form->InitInputValue( 'building',    $address['building'],    'confirm');
		}
		
		//	クーポン情報を取得
		$record = $this->GetCouponRecord( $coupon_id );
		$register_flag = $this->GetSession('register_flag');
		//	ページ
		include('buy_confirm.html');
	}
	
	function doConfirm(){
		
		//	Login check
		if(!$this->checkLogin()){ return false; }
		
		//	Form check
		if(!$this->form->CheckForm('confirm')){
			$this->doInput();
			return false;
		}
							
		//住所をフォームより取得
		$last_name	 = $this->form->getInputValue('last_name');
		$first_name	 = $this->form->getInputValue('first_name');
		$postal_code = $this->form->getInputValue('postal_code');
		$pref		 = $this->form->getInputValue('pref');
		$city		 = $this->form->getInputValue('city');
		$address	 = $this->form->getInputValue('address');
		$building	 = $this->form->getInputValue('building');

		//	DBに登録するID
		$account_id	 = $this->GetSession('account_id');
		$seq_no		 = 1;// 申し込む度に住所が増えるので常に1にしておく // $this->GetNextSeqNoOfTAddressByCustomerId($customer_id);
		
		//	お客様情報をDBに登録
		$insert = array();
		$insert['table'] = 't_address';
		$insert['set']['customer_id']	 = $account_id;	// PKEY
		$insert['set']['seq_no']		 = $seq_no;		// PKEY
		$insert['set']['last_name']		 = $last_name;
		$insert['set']['first_name']	 = $first_name;
		$insert['set']['postal_code']	 = $postal_code;
		$insert['set']['pref']			 = $pref;
		$insert['set']['city']			 = $city;
		$insert['set']['address']		 = $address;
		$insert['set']['building']		 = $building;
		$insert['update'] = true; // On Duplicate Update（PKEYが重複していたらUPDATEになる）
		$io = $this->mysql->insert($insert);
		
		if( $io ){
			$this->location('/buy/payment');
		}else{
			$this->StackError("Insert Error.");
			$message = sprintf('システムエラーが発生しました。(%s: %s)', __CLASS__, __LINE__ );
			$message_color = 'red';
			include('error.html');
			return false;
		}
	}
	
	function doPayment(){
		
		//	Login check
		if(!$this->checkLogin()){ return false; }
		
		//	アカウントID
		$account_id = $this->GetSession('account_id');
		
//		$this->d('ip_user_id='.$this->GetIP_USER_ID( $account_id, '8008', '0213' ));
//		$this->d( $this->GetItemSoldNum( $coupon_id ) .'<='. $this->GetItemStockNum( $coupon_id ) );

		//	payment フォームの初期化
		$this->form('form.payment.php');
		
		//	チェック
		if( $this->form->Secure('payment') or false ){
			include('auth.php');
		}
		
		//	View
		if( $this->GetSession('payment') ){
			include('thanks.html');
		}else{
			include('payment.html');
		}
		
		$this->mark("serial_no=".@$serial_no);
	}
}
