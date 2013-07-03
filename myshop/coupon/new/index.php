<?php
/* @var $this CouponApp */

//  My shop ID.
$shop_id = $this->GetShopID();

//  Form
$form_config = $this->config()->form_myshop_coupon( $shop_id );
$this->form()->AddForm( $form_config );
$form_name = $form_config->name;

//  Action
$action = $this->GetAction();

//	data
$data = new Config();
$data->template = 'form.phtml';


//	Retrieve img data from input (if any). 
$array_img = array();
if($_POST){
	//$value = $this->form()->GetInputValueRawAll($form_name);//op-coreのformにする場合はこのように記述
	$value = $_POST;
	foreach ( $value as $key => $val ){
		if( preg_match( '/^image_[a-zA-Z0-9]{32}$/', $key ) and $val !== null ){
			$key = str_replace("image_", "", $key);
			$array_img[$key] = $val;
		}
	}
	
	//	Set img data into $data.
	$data->array_img = $array_img;
}


//	Switch to appropriate logic based on $action.
switch( $action ){
	case 'index':
		//	Set template file.
		$data->template = 'form.phtml';
		break;

	case 'confirm':
		
		//	Retrieve values from input and put it into $array.
		$array = array();
		$n     = 0;
		$value = $this->form()->GetInputValueRawAll($form_name);
		foreach ( $value as $key => $val ){
			$array[$key] = $val;
			if( preg_match( '/^image_[a-zA-Z0-9]{32}$/', $key ) and $val !== null ){
				++$n;
			}
		}
		
		//	Initialize $err (for error message).
		$err = null;
		
		//	Check if # of image file(s) is correct.
		$img_min =  5;//	# of minimum image file(s).
		$img_max = 10;//	# of max image file(s).
		if( $n < $img_min or $n > $img_max ){
			$err = "画像は $img_min 以上 $img_max 枚まで指定してください。<br>";
		}
		
		//	Retrieve and set data into variables for error check.
		$normal_price     = $array['coupon_normal_price'];
		$sales_price      = $array['coupon_sales_price'];
		$sales_num_top    = $array['coupon_sales_num_top'];
		$sales_num_bottom = $array['coupon_sales_num_bottom'];
		$sales_start      = strtotime($array['coupon_sales_start']);
		$sales_finish     = strtotime($array['coupon_sales_finish']);
		$expire           = strtotime($array['coupon_expire']);
		$sales_person_num = $array['coupon_person_num'];

		//	Error check
		//	Check if normal price > sales price.
		if( $normal_price < $sales_price ){
			$err = $err.'販売価格が通常価格を超えています。<br>';
		}
		
		//	Check if minimum sales # is correctly set.
		$min_sales_num = 1;
		if( $sales_num_bottom < $min_sales_num ){
			$err = $err."最小販売数は $min_sales_num 枚以上にする必要があります。<br>";
		}
		
		//	Check if maximum sales # < minimum sales #.
		if( $sales_num_top < $sales_num_bottom ){
			$err = $err.'最小販売数が最大販売数を超えています。<br>';
		}
		
		//	Check if the dates are correctly set.
		$current = time();
		if( $sales_start < $current or $sales_finish < $current or $expire < $current ){
			$err = $err.'日時または有効期限に過去の日時が設定されています。<br>';
		}
		
		//	Check if the end date is prior to the start date.
		if( $sales_start > $sales_finish ){
			$err = $err.'販売終了日時が販売開始日時より前に設定されています。<br>';
		}

		//	Check if the end date and start date is the same.
		if( $sales_start == $sales_finish ){
			$err = $err.'販売終了日時と販売開始日時に同じ日時が設定されています。<br>';
		}
		
		//	Check if the expiration date is prior to the sales end date.
		if( $expire < $sales_finish or $expire < $sales_start ){
			$err = $err.'有効期限が販売開始日時または販売終了日時より前に設定されています。<br>';
		}
		
		//	Check if the 'sales person num' is correctly set.
		if( $sales_person_num == 0 ){
			$err = $err."1 人が購入できる枚数は 1 枚以上にする必要があります。<br>";
		}elseif( $sales_person_num > $sales_num_top ){
			$err = $err."1 人が購入できる枚数が最大販売数を超えています。<br>";
		}
		
		
		//	Set appropriate phtml file and message based on the result of error check.
		if(!$this->form()->Secure($form_name) ){
			$data->message   = '入力内容を確かめて下さい。';
			$data->template  = 'form.phtml';
		}else if(isset($err)){
			$data->message   = $err;
			$data->template  = 'form.phtml';
		}else{
			$data->template  = 'confirm.phtml';
		}
		break;
		
	case 'commit':
		
		//	retrieve 'image_[a-zA-Z0-9]{32}$' from Input
		$array = null;
		$value = $this->form()->GetInputValueRawAll($form_name);
		foreach ( $value as $key => $val ){
			if( preg_match( '/^image_[a-zA-Z0-9]{32}$/', $key ) and $val !== null ){
				$array[$key] = $val; 
			}
		}
		
		//	Insert coupon data into DB.
		if( $this->form()->Secure($form_name) ){
			//  Do Insert
			$config = $this->config()->insert_coupon($shop_id);
			$coupon_id = $this->pdo()->insert($config);
			
			//  Show result
			if( $coupon_id === false ){
				$data->message = 'Coupon レコードの作成に失敗しました。';
			}else{
				
				//	If no error, move img files into $shop_id/$coupon_id folder with new name.
				$n = 1; 
				$new_dir = $this->ConvertPath("app:/shop/$shop_id/$coupon_id");
				
				foreach( $array as $k => $path_from ){
					$path_from = $this->ConvertPath('app:/'.$path_from);
					if( preg_match( '|\.([a-z]{3})$|i', $path_from, $match ) ){
						$ext = $match[1];
						$path_to = $this->ConvertPath("app:/shop/$shop_id/$coupon_id/$n.jpg");
						++$n;
					}else{
						//$this->StackError("Does not match extention.");
						$data->message = '拡張子が一致しません。';
					}

					//	Create directory if it doesn't exist.
					if( file_exists($new_dir) === false ){
						mkdir($new_dir, 0777, true);
					}
					
					//	Check if file is moved.
					if(!rename( $path_from, $path_to ) ){
						//$this->StackError("File move is failed.");
						$data->message = 'ファイルの移動に失敗しました。';
					}
						
				}
				
				//	Clear form.
				$this->form()->Clear($form_name);
				
				//	Clear the 'selected image file' data.
				$array_img = array();
				unset($data->array_img);
			}
		}
		
		break;
	default:
}

include('index.phtml');
