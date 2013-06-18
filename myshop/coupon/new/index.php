<?php
/* @var $this CouponApp */

//  My shop ID.
$shop_id = $this->GetShopID();

//  Form
$form_config = $this->config()->form_myshop_coupon( $shop_id );
$this->form()->AddForm( $form_config );
$form_name = $form_config->name;
//$this->form()->Clear($form_name);

/*
//	Form (image)
$form_image_config = $this->config()->form_myshop_coupon_image( $shop_id );
$this->form()->AddForm($form_image_config);
$form_image_name = $form_image_config->name;
*/

//$this->d($form_config);//for test
//$this->d(gd_info());

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

		$this->d($_POST);
		
		//テスト用ここから。
		//	Inputからcoupon_image_nnだけ取り出す
		$array = null;
		$n = 0;
		$value = $this->form()->GetInputValueRawAll($form_name);
		$image_format = null;
		foreach ( $value as $key => $val ){
			//if( preg_match( '/coupon_image_??/', $key ) ){
			if( preg_match( '/coupon_image_??/', $key ) and $val !== null ){
				$array[$key] = $val;
				$n = $n + 1;//不要かも？
				
				/*
				//	check mime type
				$info = getimagesize($val);
				$this->d($info);
				if( $info['mime'] !== 'jpeg' and $info['mime'] !== 'png'){
					$this->d($info['mime']);
					$img_format = false;
					//$this->form()->SetInputValue(null, $key, $form_name);//
				}
				*/
			}
		}
		//$this->d($array);//for test
		//$this->d($n);//for test
		//テスト用ここまで。

		
		if(!$this->form()->Secure($form_name) ){
			$data->message  = '入力内容を確かめて下さい。';
			$data->template = 'form.phtml';
		}else{
			$data->template = 'confirm.phtml';
		}
		break;
		
	case 'commit':
		
		//	retrieve 'coupon_image_nn' from Input
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
		//$this->d($array);//for test
		//$this->d($n);//for test

		
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
						//$path_to = $this->ConvertPath("app:/shop/$shop_id/$coupon_id/$n.$ext");
						$path_to = $this->ConvertPath("app:/shop/$shop_id/$coupon_id/$n.jpg");
						$n = $n + 1;
					}else{
						$this->StackError("Does not match extention.");
					}

					//	Create directory if it doesn't exist.
					if( file_exists($new_dir) === false ){
						mkdir($new_dir, 0777, true);
					}
					
					// Check the extention.
					if( $ext === 'jpg' ){
						$img = imagecreatefromjpeg($path_from);
					}elseif( $ext === 'png' ){
						$img = imagecreatefrompng($path_from);
					}else{
						//$this->StackError("jpg または png 形式の画像のみ使用できます。");//エラー処理この方法でOK？
						$data->message  = 'jpg または png 形式の画像のみ使用できます。';
						$data->template = 'form.phtml';
						break;
					}
					
					//	retrieve size of source image.
					$base_size = 320;
					list($src_x, $src_y) = getimagesize($path_from);
						
					//	get original aspect ratio and set new image size.
					if( $src_x > $src_y ){
						$dst_x = $base_size;
						$dst_y = $src_y / ( $src_x / $base_size );
					}else{
						$dst_y = $base_size;
						$dst_x = $src_x / ( $src_y / $base_size);
					}
						
					/*
					// retrieve size of source image.
					$src_x = imagesx($img);
					$src_y = imagesy($img);
					//$this->d($src_x);//for test
					//$this->d($src_y);//for test
					
					// Convert and resize the image.
					$dst_x = 320;//new width
					$dst_y = 240;//new height
					
					if( $src_x < $src_y ){
						list($dst_x, $dst_y) = array($dst_y, $dst_x);
					}
					*/
					
					$new_img = imagecreatetruecolor($dst_x, $dst_y);
					if( imagecopyresampled($new_img, $img, 0,0,0,0,$dst_x, $dst_y, $src_x, $src_y) == false ){
						$this->StackError("Image convert and resize is failed.");
					}
					
					//	Output the resized image to $shop_id/$coupon_id folder.
					$res = imagejpeg($new_img, $path_to);
					if( $res == false ){
						$this->StackError("Image output is failed.");
					}
					
					//	Destroy image.
					imagedestroy($new_img);
					imagedestroy($img);
					
					//$this->d($path_from);//for test
				}
				
				//	Delete temp files from tmp/$shop_id/new folder.
				//$this->d($array);//for test
				foreach( $array as $k => $to_delete ){
					if( file_exists($to_delete) ){
						unlink($to_delete);
					}
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
		//ここにelseの処理を書くか？
		break;
	default:
}

include('index.phtml');
