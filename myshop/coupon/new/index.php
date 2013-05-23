<?php
/* @var $this CouponApp */

//  My shop ID.
$shop_id = $this->GetShopID();

//  Form
$form_config = $this->config()->form_myshop_coupon( $shop_id );
$this->form()->AddForm( $form_config );
$form_name = $form_config->name;
//$this->form()->Clear($form_name);

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
		if(!$this->form()->Secure($form_name) ){
			$data->message  = '入力内容を確かめて下さい。';
			$data->template = 'form.phtml';
		}else{
			$data->template = 'confirm.phtml';
		}
		break;
		
	case 'commit':
		if( $this->form()->Secure($form_name) ){
			//  Do Insert
			$config = $this->config()->insert_coupon($shop_id);
			$coupon_id = $this->pdo()->insert($config);
			
			//  View result
			if( $coupon_id === false ){
				$data->message = 'Couponレコードの作成に失敗しました。';
			}else{
				//	Get image path.
				$path_from = $this->form()->GetInputValue('coupon_image',$form_name);
				$path_from = $this->ConvertPath('app:/'.$path_from);
				if( preg_match( '|\.([a-z]{3})$|i', $path_from, $match ) ){
					$ext = $match[1];
					$path_to = $this->ConvertPath("app:/shop/$shop_id/$coupon_id/1.$ext");
				}else{
					$this->StackError("Does not match extention.");
				}
				
				//	Create directory.
				mkdir($this->ConvertPath("app:/shop/$shop_id/$coupon_id"));
				
				//	Check if file moved.
				if(!rename( $path_from, $path_to ) ){
					$this->StackError("File move is failed.");
				}
				
				//	Clear of form.
				$this->form()->Clear($form_name);
				
				//	Transfer
			//	$this->Location("app://myshop/coupon/edit/$coupon_id");
			}
		}
		break;
	default:
}

include('index.phtml');
