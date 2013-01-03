<?php

// カード認証（有効か無効か残高があるかチェック）
$config = new Config();
$config->cardno  = Credit_model::TEST_CARD_NO; // カード番号
$config->cardexp = '14-01'; // カードの有効期限
$config->amount  = 10000; // 引き落とし金額
$result = $this->model('credit')->Auth($config);
$sid = $result->sid;
if( $sid ){
	$this->p("![.blue[このカード番号は有効です。（sid=$sid）]]");
}else{
	$this->p('![.red[このカード番号は無効です]]');
}

// 実際の決済
$config = new Config();
$config->sid = $sid;
$result = $this->model('credit')->Commit($config);
$io = $result->io;
if( $io ){
	$this->p("![.blue[決済が完了しました。]]");
}else{
	$this->p("![.red[決済が失敗しました。({$result->status}: {$result->message})]]");
}

// 決済のキャンセル
$config = new Config();
$config->sid = $sid;
$result = $this->model('credit')->Cancel($config);
$io = $result->io;
if( $io ){
	$this->p("![.blue[決済をキャンセルしました。]]");
}else{
	$this->p("![.red[決済のキャンセルに失敗しました。({$result->status}: {$result->message})]]");
}
