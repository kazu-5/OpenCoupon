<?php
/* @var $this CouponApp */

$this->SetLayoutName("");
//$this->SetTemplateDir("");
//$this->SetLayoutDir("");


//  My shop ID.
$shop_id = $this->GetShopID();

$upload_dir = $this->ConvertPath("app:/temp/$shop_id/new/");

if( !empty($_POST['to_delete']) ){
	$to_delete = $_POST['to_delete'];
	
	//$this->d($to_delete);
	
	//	unlink()をここに記述。ファイル存在チェックとかして、なかったらエラー返す。
	if( file_exists( $upload_dir.$to_delete.'.jpg' )){
		unlink( $upload_dir.$to_delete.'.jpg' );//現状、jpgしか消せない。要修正。
		$re = $to_delete.' is deleted.';//ダミー
		$deleted = $to_delete;
	}else{
		$re  = 'file does not exist.';
		$err = 'file does not exist.';
	}
	
}else{
	$re = 'failed to delete.';
	$err = 'file does not exist.';
}


//	return results with JSON.
$this->SetJson('message', $re);
if(isset($err) == true){
	$this->SetJson('err', $err);
}
if(isset($deleted) == true){
	$this->SetJson('deleted', $deleted);
}



/*
//	ここで戻り値に使うJSON用の配列作る。

$return = array();
$return['message'] = $re;
$return['path'] = $to_delete;
$return = json_encode($return);

//$return = $re;

header( 'Content-Type: text/html; charset=utf-8' );//これだとOCのレイアウト全体が返される。
print $return;
*/


//$this->SetHeader($return);
//print $to_delete;

//$data->header  = 'Content-Type: application/json; charset=utf-8';
//$data->message = $return;



/*
print '<?xml version="1.0" encoding="ISO-8859-1"?><response>';

echo "<message>" . $re . "</message>";
echo "<err>" . $err . "</err>";
echo '</response>';
*/
