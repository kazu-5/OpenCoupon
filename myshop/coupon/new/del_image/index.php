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
