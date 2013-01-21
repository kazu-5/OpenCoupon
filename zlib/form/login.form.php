<?php

/*
 loginフォーム
 /buy/buy.form.php から移動
 */

$form = array();
$form['name']	= 'login'; // form_name
$form['method'] = 'get';
$form['action'] = '';
$form['errors']['required'] = '%sが未入力です。';

//ログイン
$input = array();
$input['label']   	 = 'メールアドレス';
$input['name'] 	  	 = 'mailaddr';
$input['type']	  	 = 'mailaddr';
$input['value']		 = $this->GetCookie('mailaddr');
$input['required']	 = true;
$form['input'][]	 = $input;

$input = array();
$input['label'] 	 = 'パスワード';
$input['name'] 		 = 'password';
$input['type'] 		 = 'password';
$input['required']   = true;
$form['input'][] 	 = $input;

$input = array();
$input['name'] 		 = 'cookie';
$input['type'] 		 = 'checkbox';
$input['class']		 = 'small';
$input['value']		 = $this->GetCookie('mailaddr') ? 1: 0;

$option = array();
$option['label'] = 'ログイン状態を保持する';
$option['value'] = 1;
$input['options'][] = $option;

$form['input'][] 	= $input;

$input = array();
$input['name'] 		 = 'submit_button';
$input['class']		 = 'input_button';
$input['type'] 		 = 'submit';
$input['value'] 	 = 'ログインして購入手続きに進む';
$form['input'][] 	 = $input;

$input = array();
$input['name'] 		 = 'submit_button_shop';
$input['class']		 = '';
$input['type'] 		 = 'submit';
$input['value'] 	 = 'ログイン';
$form['input'][] 	 = $input;

$_forms[] = $form;

