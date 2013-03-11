<?php
/* @var $this CouponApp */

//  templateに渡すdata
$data = new Config();
$data->form_action  = null;
$data->submit_label = ' この内容で購入 ';

//
$action    = $this->GetBuyAction();
$coupon_id = $this->GetCouponId();
$data->coupon_id = $coupon_id;

//  Formの設定
$form_config = $this->config()->form_buy($coupon_id);
$this->form()->AddForm($form_config);
$data->form_name_buy = $form_config->name;

//  クーポンのrecord
$select = $this->config()->select_coupon($coupon_id);
$data->t_coupon = $this->pdo()->select($select);

//  Action
if( $action !== 'index' ){
	
	//  Get account_id.
	$id = $this->model('Login')->GetLoginID();
	
	//  Check
	if( !$id ){
		//  Does not logged in.
		$this->module('Transfer')->Set('app:/login');
		return;
	}
	
	//  Get address form.
	$seq_no = $this->pdo()->quick("address_seq_no <- t_customer.account_id = $id");
	$config = $this->config()->form_address( $id, $seq_no );
	$this->form()->AddForm($config);
	
	//  Save address-form name.
	$data->form_name_address = $config->name;
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
				$this->module('Transfer')->Set('app:/login');
			}
		}else{
			//  NG
			$this->template('index.phtml',$data);
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
