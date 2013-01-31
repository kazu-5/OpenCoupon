<?php

//	都道府県
$prefs = array('北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県');

//ユーザ登録の編集フォーム
$form = array();
$form['name']	= 'customer'; // form_name
$form['method'] = 'post';
$form['action'] = '/mypage/customer';
$form['errors']['required'] = '%sが未入力です。';
$form['errors']['permit']   = '%sに不正な値が入力されています。(%s)';

$input = array();
$input['name'] = 'last_name';
$input['type'] = 'text';
$input['label'] = '姓';
$input['size'] = '10px';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'first_name';
$input['type'] = 'text';
$input['label'] = '名';
$input['size'] = '10px';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'postal_code';
$input['type'] = 'text';
$input['label'] = '郵便番号';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'pref';
$input['type'] = 'select';
//	$input['value'] = '東京都';
$input['validate']['required'] = TRUE;

foreach( $prefs as $pref ){
	$option = array();
	$option['label'] = $pref;
	$option['value'] = $pref;
	$input['options'][] = $option;
}
$form['input'][] = $input;

$input = array();
$input['name'] = 'city';
$input['type'] = 'text';
$input['label'] = '市区町村';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'address';
$input['type'] = 'text';
$input['label'] = '丁目番地';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'building';
$input['type'] = 'text';
$input['label'] = '建物名';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'myarea';
$input['type'] = 'select';
//	$input['value'] = '東京都';
$input['validate']['required'] = TRUE;

foreach( $prefs as $pref ){
	$option = array();
	$option['label'] = $pref;
	$option['value'] = $pref;
	$input['options'][] = $option;
}

$form['input'][] = $input;

$input = array();
$input['name']	 = 'year';
$input['type']	 = 'select';
//	$input['value']	 = 1980;
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
//	$input['value']	 = 6;
$input['validate']['required'] = TRUE;

for($i=1; $i<=12; $i++){
	$option = array();
	$option['label'] = $i;
	$option['value'] = sprintf('%02d',$i);
	$input['options'][] = $option;
}

$form['input'][] = $input;

$input = array();
$input['name']	 = 'day';
$input['type']	 = 'select';
//	$input['value']	 = 15;
$input['validate']['required'] = TRUE;

for($i=1; $i<=31; $i++){
	$option = array();
	$option['label'] = $i;
	$option['value'] = sprintf('%02d',$i);
	$input['options'][] = $option;
}

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
$input['name'] = 'submit_button';
$input['type'] = 'submit';
$input['class'] = 'input_button';
$input['value'] = '変更を保存する';
$form['input'][] = $input;

$_forms[] = $form;

//メールアドレス変更フォーム
$form = array();
$form['name']	= 'mailaddr_change'; // form_name
$form['method'] = 'post';
$form['action'] = '/mypage/mailaddr_confirm';
$form['errors']['required'] = '%sが未入力です。';
$form['errors']['permit']   = '%sに不正な値が入力されています。(%s)';

$input = array();
$input['name'] = 'mailaddr';
$input['type'] = 'text';
$input['class'] = 'mailaddr_input';
$input['label'] = 'メールアドレス';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'mailaddr_confirm';
$input['type'] = 'text';
$input['class'] = 'mailaddr_input';
$input['label'] = 'メールアドレス(確認用)';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'submit_button';
$input['type'] = 'submit';
$input['class'] = 'input_button';
$input['value'] = '入力内容を確認する';
$form['input'][] = $input;

$_forms[] = $form;

//メールアドレス変更確認フォーム
$form = array();
$form['name']	= 'mailaddr_confirm'; // form_name
$form['method'] = 'post';
$form['action'] = '/mypage/mailaddr_change_mail';
$form['errors']['required'] = '%sが未入力です。';
$form['errors']['permit']   = '%sに不正な値が入力されています。(%s)';

$input = array();
$input['name'] = 'submit_button';
$input['type'] = 'submit';
$input['class'] = 'input_button';
$input['value'] = 'この内容で登録する';
$form['input'][] = $input;

$_forms[] = $form;

//パスワード変更フォーム
$form = array();
$form['name']	= 'password_change'; // form_name
$form['method'] = 'post';
$form['action'] = '/mypage/password_commit';
$form['errors']['required'] = '%sが未入力です。';
$form['errors']['permit']   = '%sに不正な値が入力されています。(%s)';

$input = array();
$input['name'] = 'old_password';
$input['type'] = 'text';
$input['class'] = 'password_input';
$input['label'] = '現在のパスワード';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'new_password';
$input['type'] = 'text';
$input['class'] = 'password_input';
$input['label'] = '新しいパスワード';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'new_password_confirm';
$input['type'] = 'text';
$input['class'] = 'password_input';
$input['label'] = '新しいパスワード(確認用)';
$input['validate']['required'] = TRUE;
$form['input'][] = $input;

$input = array();
$input['name'] = 'submit_button';
$input['type'] = 'submit';
$input['class'] = 'input_button';
$input['value'] = '変更する';
$form['input'][] = $input;

$_forms[] = $form;

//パスワード変更完了フォーム
$form = array();
$form['name']	= 'password_commit'; // form_name
$form['method'] = 'post';
$form['action'] = '/mypage/password_complete';
$form['errors']['required'] = '%sが未入力です。';
$form['errors']['permit']   = '%sに不正な値が入力されています。(%s)';

$input = array();
$input['name'] = 'submit_button';
$input['type'] = 'submit';
$input['value'] = '戻る';
$form['input'][] = $input;

$_forms[] = $form;