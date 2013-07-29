<?php
/* @var $this CouponApp */

if( $this->admin() ){
	$this->SetJson('test',true);
}

if( empty($_FILES['upload_image']) ){
	$this->SetJson('mime',$this->GetEnv('mime'));
	return;
}

//  My shop ID.
$shop_id = $this->GetShopID();

//	Prepare the upload dir.
$upload_dir = $this->ConvertPath("app:/temp/$shop_id/new/");

//	Check uploaded file with ValidateImage()
//★op-coreのValidateInputを利用できないので処理を部分的に移植。input名はハードコードで処理★
$err = null;//エラーメッセージスタック用
if(!isset($_FILES['upload_image'])){
	$err = 'アップロードに失敗しました。';
}

//if($_FILES['upload_image']['error'] == 4){
if($_FILES['upload_image']['error'] == 0){

	//  image info
	if(!$info = getimagesize($_FILES['upload_image']['tmp_name'])){
		$err = '正しい画像ファイルを指定してください。';
	}
	
	if( $info == null or $info == '' or $info == false ){
		$err = '正しい画像ファイルを指定してください。';
	}

	//  image different (does not match mime type)
	$files_mime = $_FILES['upload_image']['type'];
	switch ( $files_mime ){
		case 'image/jpg':
		case 'image/jpeg':
		case 'image/pjpeg':
			$files_mime = 'image/jpeg';
			break; 
		
		case 'image/png':
		case 'image/x-png':
			$files_mime = 'image/png';
			break;
		 
		default:
			break;
	}
	if( $info['mime'] !== $files_mime ){
		$err = '正しい画像ファイルを指定してください。';
	}

	if(!$err){
		//		$width  = $info[0];
		//		$height = $info[1];
		$mime   = $info['mime'];
		//		$size   = $_FILES[$input->name]['size'];
		list($type,$ext) = explode('/',$mime);
		
		//	Check if the file is image file.
		if( $type !== 'image' ){
			$err = '正しい画像ファイルを指定してください。';
		}
	}
	
}else{
	$err = 'アップロードに失敗しました。';
}

//	Show Error (if any)
if( $err !==null ){
	$this->SetJson('error', $err);
	return;
}

//	Retrieve data from $_FILES and set them into local valiables.
$_file    = $_FILES['upload_image'];
$filename = $_file['name'];
$tmp      = $_file['tmp_name'];

//	Form5のファイルアップロード処理を部分的に移植
//	extention
$temp = explode('.',$filename);
$ext  = array_pop($temp);
$op_uniq_id = $this->GetCookie( self::KEY_COOKIE_UNIQ_ID );
$time = microtime(true);//for 'salt'
$path = $upload_dir . md5($filename . $op_uniq_id . $time ).".jpg";

//	Check if the distination dir exists.
if(!file_exists( $dirname = dirname($path) )){
	$this->mark("Does not exists directory. ($dirname)");
	if(!$io = mkdir( $dirname, 0777, true ) ){
		$this->StackError("Failed to make directory. (".dirname($path).")");
		return;
	}
}


//	Image conversion.
//	Reference:
//		http://www.geekpage.jp/web/php-gd/
//		http://redwarcueid.seesaa.net/article/167597752.html

//	Set file path.
$path_from = $tmp;
$path_to   = $path;

//	Extract image data from tmp file, based on original file extension.
$ext = strtolower($ext);
if( $ext === 'jpg' ){
	$img = imagecreatefromjpeg($path_from);
}elseif( $ext === 'png' ){
	$img = imagecreatefrompng($path_from);
}else{
	unlink($path_from);
	$err = 'jpg または png 形式の画像のみ使用できます。';
	$this->SetJson('error', $err);
	return;
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

//	Create new image with dimension resized.
$new_img = imagecreatetruecolor($dst_x, $dst_y);
if( imagecopyresampled($new_img, $img, 0,0,0,0,$dst_x, $dst_y, $src_x, $src_y) == false ){
	//$this->StackError("Image convert and resize is failed.");
	$err = "Image convert and resize is failed.";
}

//	Output the resized image to $shop_id/$coupon_id folder.
$res = imagejpeg($new_img, $path_to);
if( $res == false ){
	//$this->StackError("Image output is failed.");
	$err = "Image output is failed.";
}else{
	$re = unlink($path_from);
	if($re == false){
		$err = 'failed to delete tmp file.';
	}
}

//	Destroy image.
imagedestroy($new_img);
imagedestroy($img);

//	output path info for creating preview image.
$imgpath = $this->ConvertURL($path);
$img_id  = pathinfo($path, PATHINFO_FILENAME);

//	Return results with JSON.
if($err){
	$this->SetJson('error', $err);
}else{
	$this->SetJson('img_path', $imgpath);
	$this->SetJson('img_id', $img_id);
}
