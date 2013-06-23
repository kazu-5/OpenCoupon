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
//	Form (image) ★画像アップロード用フォーム。現時点ではop-coreが対応していないため未使用★
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

		//	Retrieve values from input and put it into $array.
		$array = null;
		$n = 0;
		$value = $this->form()->GetInputValueRawAll($form_name);
		//$this->d($value);
		$image_format = null;
		foreach ( $value as $key => $val ){
			$array[$key] = $val;
			if( preg_match( '/^image_[a-zA-Z0-9]{32}$/', $key ) and $val !== null ){
				//$array[$key] = $val;
				//$n = $n + 1;
				++$n;
			}
		}
		$this->d($array);//for test
		$this->d($n);//for test

		$err = null;
		$img_min = 5;//★便宜上1に設定してるので、作業完了後に5に戻す。★
		$img_max = 10;
		if( $n < $img_min or $n > $img_max ){
			$err = "画像は $img_min 以上 $img_max 枚まで指定してください。<br>";
		}
		
		//	Retrieve and set data into variables for err check.
		$normal_price     = $array['coupon_normal_price'];
		$sales_price      = $array['coupon_sales_price'];
		$sales_num_top    = $array['coupon_sales_num_top'];
		$sales_num_bottom = $array['coupon_sales_num_bottom'];
		$sales_start      = strtotime($array['coupon_sales_start']);
		$sales_finish     = strtotime($array['coupon_sales_finish']);
		$expire           = strtotime($array['coupon_expire']);
		
		$this->d($sales_start);//for test
		$this->d($sales_finish);//for test
		$this->d($expire);//for test
		
		//	error check.
		if( $normal_price < $sales_price ){
			$err = $err.'販売価格が通常価格を超えています。<br>';
		}
		
		$min_sales_num = 1;
		if( $sales_num_bottom < $min_sales_num ){
			$err = $err."最小販売数は $min_sales_num 枚以上にする必要があります。<br>";
		}
		
		if( $sales_num_top < $sales_num_bottom ){
			$err = $err.'最小販売数が最大販売数を超えています。<br>';
		}
		
		if( $sales_start > $sales_finish ){
			$err = $err.'販売終了日時が販売開始日時より前に設定されています。<br>';
		}

		if( $sales_start == $sales_finish ){
			$err = $err.'販売終了日時と販売開始日時に同じ日時が設定されています。<br>';
		}
		
		if( $expire < $sales_finish ){
			$err = $err.'有効期限が販売終了日時より前に設定されています。<br>';
		}
		
		//	Switch phtml file.
		if(!$this->form()->Secure($form_name) ){
			$data->message  = '入力内容を確かめて下さい。';
			$data->template = 'form.phtml';
		}else if(isset($err)){
			$data->message  = $err;//★エラーメッセージの表示方法について要調整
			$data->template = 'form.phtml';
		}else{
			$data->template = 'confirm.phtml';
		}
		break;
		
		
		
		/* 元のコード
		if(!$this->form()->Secure($form_name) ){
			$data->message  = '入力内容を確かめて下さい。';
			$data->template = 'form.phtml';
		}else{
			$data->template = 'confirm.phtml';
		}
		break;
		*/
		
	case 'commit':
		
		//	retrieve 'image_nn' from Input
		$array = null;
		$n = 0;
		$value = $this->form()->GetInputValueRawAll($form_name);
		foreach ( $value as $key => $val ){
			//if( preg_match( '/coupon_image_??/', $key ) ){
			//if( preg_match( '/^[a-z]{5}_[a-zA-Z0-9]{32}$/', $key ) and $val !== null ){
			if( preg_match( '/^image_[a-zA-Z0-9]{32}$/', $key ) and $val !== null ){
				$array[$key] = $val;//正規表現要修正 
				//$n = $n + 1;//不要かも？
				++$n;
				//$this->d($val);//for test
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
					$path_from = $this->ConvertPath('app:/'.$path_from);
					if( preg_match( '|\.([a-z]{3})$|i', $path_from, $match ) ){
						$ext = $match[1];
						//$path_to = $this->ConvertPath("app:/shop/$shop_id/$coupon_id/$n.$ext");
						$path_to = $this->ConvertPath("app:/shop/$shop_id/$coupon_id/$n.jpg");
						//$n = $n + 1;
						++$n;
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
						
					//	Clear form.
					$this->form()->Clear($form_name);
					
					
					/*
					// Check the extention.
					if( $ext === 'jpg' ){
						$this->d($path_from);//for test
						$this->d($this->ConvertURL('app:/'.$path_from));//for test
						$this->d($this->ConvertPath('app:/'.$path_from));//for test
						
						$path_from = $this->ConvertURL('app:/'.$path_from);//for test
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
					*/
				}

				
				
				/*
				//	Delete temp files from tmp/$shop_id/new folder.
				//$this->d($array);//for test
				foreach( $array as $k => $to_delete ){
					if( file_exists($to_delete) ){
						unlink($to_delete);
					}
				}
				*/
				

				
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
