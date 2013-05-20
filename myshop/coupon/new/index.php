<?php
/* @var $this CouponApp */

//  My shop ID.
$shop_id = $this->GetShopID();

//  Form
$form_config = $this->config()->form_coupon( $shop_id );
$this->form()->AddForm( $form_config );
$form_name = $form_config->name;
$this->form()->Clear($form_name);
//$this->d($_POST);

//  Action
$action = $this->GetAction();

//	data
$data = new Config();
$data->template = 'form.phtml';

switch( $action ){
	case 'index':
		$data->template = 'form.phtml';
		break;

	case 'confirm':
		if(!$this->form()->Secure('form_coupon') ){
			$data->message  = '入力内容を確かめて下さい。';
			$data->template = 'form.phtml';
		}else{
			$data->template = 'confirm.phtml';
		}
		break;
		
	case 'commit':
		if( $this->form()->Secure('form_coupon') ){
			
			//  Do Insert
			$config = $this->config()->insert_coupon($shop_id);
			$coupon_id = $this->pdo()->insert($config);
			
			//  View result
			if( $coupon_id === false ){
				$data->message = 'Couponレコードの作成に失敗しました。';
			}else{
				
				$path = $this->form()->GetInputValueRaw('coupon_image',$form_name);
				$this->mark($path);
				
				$this->form()->Clear($form_name);
				
				return;
				
			//	$this->Location("app://myshop/coupon/edit/$coupon_id");
			}
		}
		break;
	default:
}

include('index.phtml');
