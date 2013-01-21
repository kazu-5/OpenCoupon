<?php

//	購入フォーム
$form = array();
$form['name']	= 'buy'; // form_name
$form['method'] = 'get';
$form['action'] = '/buy/login';

$input = array();
$input['name'] 	  = 'coupon_id';
$input['type']	  = 'hidden';
$form['input'][]  = $input;

$input = array();
$input['name'] 	 = 'quantity';
$input['type']	 = 'select';
$input['id']	 = 'quantity';
$input['style']	 = 'font-size:1em; height:1.5em;';
$input['onchange'] = 'change_quantity();';
$input['value']	 = 1; // フォームのデフォルトは全てvalueで設定できる

for( $i=1; $i<10; $i++){
	$option = array();
	$option['value'] = $i;
	$option['label'] = $i;
	$option['style'] = 'text-align:center;';
	$input['options'][] = $option;
}

$form['input'][]  = $input;

$input = array();
$input['name'] 		= 'submit_button';
$input['class']		= 'input_button';//class="input_button"
$input['type'] 		= 'submit';
$input['value'] 	= 'この内容で購入';
$form['input'][] 	= $input;

$_forms[] = $form;
