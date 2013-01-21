<?php

//新規登録フォーム
$form = array();
$form['name']	= 'register'; // form_name
$form['method'] = 'post';
$form['action'] = '/account/register/sendmail';
$form['errors']['required'] = '%sが未入力です。';
$form['errors']['permit']   = '%sに不正な値が入力されています。(%s)';

$input = array();
$input['name'] = 'last_name';
$input['type'] = 'text';
$input['label'] = '姓';
$input['validate']['required'] = TRUE;
$input['size'] = '10';
$form['input'][] = $input;

$input = array();
$input['name'] = 'first_name';
$input['type'] = 'text';
$input['label'] = '名';
$input['validate']['required'] = TRUE;
$input['size'] = '10';
$form['input'][] = $input;

$input = array();
$input['name'] = 'nickname';
$input['type'] = 'text';
$input['label'] = 'ニックネーム';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'mailaddr';
$input['type'] = 'text';
$input['label'] = 'メールアドレス';
$input['validate']['required'] = TRUE;
$input['validate']['permit']   = 'email';
$form['input'][] = $input;

$input = array();
$input['name'] = 'mailaddr_confirm';
$input['type'] = 'text';
$input['label'] = 'メールアドレス（確認用）';
$input['validate']['required'] = TRUE;
$input['validate']['permit']   = 'email';
$form['input'][] = $input;

$input = array();
$input['name'] = 'password';
$input['type'] = 'password';
$input['label'] = 'パスワード';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'password_confirm';
$input['type'] = 'password';
$input['label'] = 'パスワード（確認用）';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'gender';
$input['type'] = 'radio';
$input['label'] = '性別';
$input['validate']['required'] = TRUE;

$option = array();
$option['label'] = '男性';
$option['value'] = 'M';
$input['options'][] = $option;

$option = array();
$option['label'] = '女性';
$option['value'] = 'F';
$input['options'][] = $option;

$form['input'][] = $input;

$input = array();
$input['name']	 = 'year';
$input['type']	 = 'select';
$input['value']	 = 1980;
$input['validate']['required'] = TRUE;

for($i=1900; $i<=2000; $i++){
	$option = array();
	$option['label'] = $i;
	$option['value'] = $i;
	$input['options'][] = $option;
}

$form['input'][] = $input;

$input = array();
$input['name']	 = 'month';
$input['type']	 = 'select';
$input['value']	 = 6;
$input['validate']['required'] = TRUE;

for($i=1; $i<=12; $i++){
	$option = array();
	$option['label'] = $i;
	$option['value'] = $i;
	$input['options'][] = $option;
}

$form['input'][] = $input;

$input = array();
$input['name']	 = 'day';
$input['type']	 = 'select';
$input['value']	 = 15;
$input['validate']['required'] = TRUE;

for($i=1; $i<=31; $i++){
	$option = array();
	$option['label'] = $i;
	$option['value'] = $i;
	$input['options'][] = $option;
}

$form['input'][] = $input;

$input = array();
$input['name'] = 'myarea';
$input['type'] = 'select';
$input['value'] = '東京都';
$input['validate']['required'] = TRUE;

$myareas = array('北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県');

foreach( $myareas as $myarea ){
	$option = array();
	$option['label'] = $myarea;
	$option['value'] = $myarea;
	$input['options'][] = $option;
}

$form['input'][] = $input;

$input = array();
$input['name'] = 'agree';
$input['type'] = 'checkbox';
$input['save'] = false;
$input['value'] = 1;
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'submit_button';
$input['class']	= 'input_button';//class="input_button"
$input['type'] = 'submit';
$input['value'] = 'この内容で仮登録する';
$form['input'][] = $input;

$input = array();
$input['name'] 	  = 'coupon_id';
$input['type']	  = 'hidden';
$form['input'][]  = $input;
$_forms[] = $form;
