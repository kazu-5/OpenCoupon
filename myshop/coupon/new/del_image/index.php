<?php
/* @var $this CouponApp */

$this->SetLayoutName("");
//$this->SetTemplateDir("");
//$this->SetLayoutDir("");

//  My shop ID.
$shop_id = $this->GetShopID();

$upload_dir = $this->ConvertPath("app:/temp/$shop_id/new/");


//	Delete the file specified with ajax.
if( !empty($_POST['to_delete']) ){
	$to_delete = $_POST['to_delete'];
	
	//	Delete file with unlink()
	$ext = '.jpg';
	if( file_exists( $upload_dir.$to_delete.$ext )){
		unlink( $upload_dir.$to_delete.$ext );
		$deleted = $to_delete;
	}else{
		$err = 'ファイルが存在しません。';
		$deleted = $to_delete;
	}
	
}else{
	$err = '削除に失敗しました。';
}


//	Return results with JSON.
if(isset($err) == true) {
	$this->SetJson('error', $err);
}

if(isset($deleted) == true){
	$this->SetJson('deleted', $deleted);
}
