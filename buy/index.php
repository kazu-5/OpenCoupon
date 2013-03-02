<?php
/* @var $this CouponApp */

//
$action    = $this->GetBuyAction();
$coupon_id = $this->GetCouponId();
$this->mark("action=$action",'debug');
$this->mark("coupon_id=$coupon_id",'debug');

//  Formの設定
$form_config = $this->config()->form_buy($coupon_id);
$this->form()->AddForm($form_config);

//  クーポンのrecord
$select = $this->config()->select_coupon($coupon_id);
$record = $this->pdo()->select($select);

//  templateに渡すdata
$data = new Config();
$data->coupon_id    = $coupon_id;
$data->record       = $record;
$data->form_name    = $form_config->name;
$data->form_action  = null;
$data->submit_label = ' この内容で購入 ';

//  Action
if( $action !== 'index' ){
	//  Get account_id.
	$id = $this->model('Login')->GetLoginID();
	//  Check
	if( !$id ){
		//  Does not logged in.
		$this->Location('app:/login');
	}
	//  Get address form.
	$seq_no = $this->pdo()->quick("address_seq_no <- t_customer.account_id = $id");
	$config = $this->config()->form_address( $id, $seq_no );
	$this->form()->AddForm($config);
}

//  Do action
switch( $action ){
	case 'index':
		//  Check secures
		if( $this->form()->Secure($form_config->name) ){
			//  OK
			//  Login Check
			if( $id = $this->model('Login')->GetLoginID() ){
				$this->Location("app:/buy/$coupon_id/address");
			}else{
				$this->Location('app:/login');
			}
		}else{
			//  NG
			include('form_buy.phtml');
		}
		break;
		
	case 'address':
		//  Check login
		if( $id = $this->model('Login')->GetLoginID() ){
			//  OK
			include('address.phtml');
		}else{
			//  NG
			include('error_login.phtml');
		}
		break;

	case 'confirm':
		//  Check login
		if( $id = $this->model('Login')->GetLoginID() ){
			//  OK
			$this->Template('confirm.phtml',$data);
		}else{
			//  NG
			include('buy_login_error.phtml');
		}
		break;
	
	case 'commit':
		//  Check login
		if($id = $this->model('Login')->GetLoginID()){
			//  OK
			
			//  Check secure
			if( /* $this->form()->Secure('form_buy') and $this->form()->Secure('form_address') */ true ){
				//  OK
				
				//  Insert Address
				$config = $this->config()->insert_address( $id );
				$id = $this->pdo()->insert($config);
			//	$this->mark($id);
				
				if( $id !== false ){
					$url = $this->ConvertURL('app:/buy/payment');
					include('buy_commit_success.phtml');
				}else{
					include('buy_commit_failed.phtml');
				}
				
			}else{
				//  NG
				$this->form()->debug('form_buy');
				$this->form()->debug('form_address');
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
