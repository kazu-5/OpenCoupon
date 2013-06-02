<?php
/* @var $this CouponApp */

//  My shop ID.
$shop_id = $this->GetShopID();

//  Form
$form_config = $this->config()->form_myshop_coupon( $shop_id );
$this->form()->AddForm( $form_config );
$form_name = $form_config->name;
//$this->form()->Clear($form_name);


//$this->d($form_config);//for test

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

		//テスト用ここから。
		//	Inputからcoupon_image_nnだけ取り出す
		$array = null;
		$n = 0;
		$value = $this->form()->GetInputValueRawAll($form_name);
		foreach ( $value as $key => $val ){
			//if( preg_match( '/coupon_image_??/', $key ) ){
			if( preg_match( '/coupon_image_??/', $key ) and $val !== null ){
				$array[$key] = $val;
				$n = $n + 1;//不要かも？
			}
		}
		$this->d($array);//for test
		$this->d($n);//for test
		//テスト用ここまで。
		
		
		//ここから下が本来のコード。
		if(!$this->form()->Secure($form_name) ){
			$data->message  = '入力内容を確かめて下さい。';
			$data->template = 'form.phtml';
		}else{
			$data->template = 'confirm.phtml';
		}
		break;
		
	case 'commit':
		
		//	Inputからcoupon_image_nnだけ取り出す
		$array = null;
		$n = 0;
		$value = $this->form()->GetInputValueRawAll($form_name);
		foreach ( $value as $key => $val ){
			//if( preg_match( '/coupon_image_??/', $key ) ){
			if( preg_match( '/coupon_image_??/', $key ) and $val !== null ){
				$array[$key] = $val;
				$n = $n + 1;//不要かも？
			}
		}
		
		$this->d($array);//for test
		$this->d($n);//for test
		//break;//ここから下が本来のコード。

		
		if( $this->form()->Secure($form_name) ){
			//  Do Insert
			$config = $this->config()->insert_coupon($shop_id);
			$coupon_id = $this->pdo()->insert($config);
			
			//  View result
			if( $coupon_id === false ){
				$data->message = 'Couponレコードの作成に失敗しました。';
			}else{
				
				$n = 1;
				$new_dir = $this->ConvertPath("app:/shop/$shop_id/$coupon_id");
				
				foreach( $array as $k => $path_from ){
					//$path_from = $this->ConvertPath('app:/'.$path_from);
					if( preg_match( '|\.([a-z]{3})$|i', $path_from, $match ) ){
						$ext = $match[1];
						$path_to = $this->ConvertPath("app:/shop/$shop_id/$coupon_id/$n.$ext");
						$n = $n + 1;
					}else{
						$this->StackError("Does not match extention.");
					}

					//	Create directory if it doesn't exist.
					if( file_exists($new_dir) === false ){
						mkdir($new_dir, 0777, true);
					}
					
					//	Check if file moved.
					if(!rename( $path_from, $path_to ) ){
						$this->StackError("File move is failed.");
					}
					
					$this->d($path_from);//for test
				}
				
				//	Clear of form.
				$this->form()->Clear($form_name);
				

				
				/*
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
			*/
			}
		}
		break;
	default:
}

include('index.phtml');
