<?php
/* @var $this CouponApp */
$action    = $this->GetBuyAction();
$coupon_id = $this->GetCouponId();
$this->mark("action=$action",'debug');
$this->mark("coupon_id=$coupon_id",'debug');

//  Formの設定
$config = $this->config()->form_buy($coupon_id);
$this->form()->AddForm($config);

//  Action
switch( $action ){
	case 'index':
		//  クーポンのrecord
		$select = $this->config()->select_coupon($coupon_id);
		$record = $this->pdo()->select($select);
		
		//  Check secure
		if( $this->form()->Secure('form_buy') ){
			//  OK
			//  Login Check
			if( $id = $this->model('Login')->GetLoginID() ){
				$this->Location("app:/buy/$coupon_id/confirm");
			}else{
				$this->Location('app:/login');
			}
		}else{
			//  NG
			include('buy.phtml');
		}
		break;
		
	case 'confirm':
		//  Check login
		if( $id = $this->model('Login')->GetLoginID() ){
			//  OK
			$config = $this->config()->form_address( $id );
			$this->form()->AddForm($config);
			include('buy_confirm.phtml');
		}else{
			//  NG
			include('buy_login_error.phtml');
		}
		break;

	case 'reconfirm':
		//  Check login
		if( $id = $this->model('Login')->GetLoginID() ){
			//  OK
			$config = $this->config()->form_address( $id );
			$this->form()->AddForm($config);
			
			include('buy_reconfirm.phtml');
		}else{
			//  NG
			include('buy_login_error.phtml');
		}
		break;
	
	case 'commit':
		//  Check login
		if($id = $this->model('Login')->GetLoginID()){
			//  OK
			//  Init form config
			$config = $this->config()->form_buy_confirm( $id, $coupon_id );
			$this->form()->AddForm($config);
			
			//  Check secure
			if( $this->form()->Secure('form_buy_confirm') ){
				//  OK
				//  Insert Address
				$config = $this->config()->insert_address( $id );
				$id = $this->pdo()->insert($config);
				
				if( $id !== false ){
					$url = $this->ConvertURL('app:/buy/payment');
					include('buy_commit_success.phtml');
				}else{
					include('buy_commit_failed.phtml');
				}
				
			}else{
				//  NG
				include('buy_confirm.phtml');
			}
		}else{
			//  NG
			include('buy_login_error.phtml');
			include('buy.phtml');
		}
		break;
	default:
		$this->mark("action=$action");
}
