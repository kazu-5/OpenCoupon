<?php
/* @var $this CouponApp */

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
</head>
<body>
<?php

//  My shop ID.
$shop_id = $this->GetShopID();

//$upload_dir = './';
$upload_dir = $this->ConvertPath("app:/temp/$shop_id/new/");
$filename   = $_FILES['upload_image']['name'];
move_uploaded_file($_FILES['upload_image']['tmp_name'], $upload_dir.$filename);

$upload_dir = $this->ConvertURL("app:/temp/$shop_id/new/");
$imgpath = $upload_dir.$filename;
$img_id  = pathinfo($filename, PATHINFO_FILENAME);
?>
<script type="text/javascript">

//	for preview image.
//	create div for preview img.
div_image = parent.document.createElement('div');

image = parent.document.createElement('img');
image.src = '<?php print ($imgpath);?>';
image.width  = 100;
image.height =  75; 

div_image.appendChild(image);

//	create a delete button.
span_del_button = parent.document.createElement('span');
span_del_button.className = 'image_del_button';
del_button = parent.document.createElement('a');
del_button.href = "javascript:del_image('<?php print ($img_id);?>')";
del_button.appendChild(parent.document.createTextNode('[x]'));
span_del_button.appendChild(del_button);

//	create div for delete button.
div_catch = parent.document.createElement('div');
div_catch.appendChild(span_del_button);

//	create a wrapping div.
div_all = parent.document.createElement('div');
div_all.className = 'uploaded_image';
div_all.id = '<?php print ($img_id);?>';

//	add img and button to the wrapping div.
div_all.appendChild(div_image);
div_all.appendChild(div_catch);

container = parent.document.getElementById('uploaded_image');

container.appendChild(div_all);

parent.document.getElementById('form_coupon_image').reset();


//for hidden input of main form.
input_image = parent.document.createElement('input');
input_image.id = '<?php print ($img_id);?>_image';
input_image.type = 'hidden';
input_image.name = 'image';//ここ要修正かも
input_image.value = '<?php print ($img_id);?>';

//ここにキャッチコピー用のinput作成が入るが使わないので省略

form = parent.document.getElementsByTagName('form')[0];//formにidを設定できないためタグ名と順序で取得
form.appendChild(input_image);


</script>

</body>
</html>