<?php
/**
 * MySQLにはPDOを使ってアクセスします。
 * 

テスト用のデータベースは以下をインポートして下さい。

CREATE TABLE IF NOT EXISTS `t_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` text CHARACTER SET utf8 NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

 */

$database = $this->config()->database();
$this->d( Toolbox::toArray($database) );

//  PDOの取得
$pdo = $this->pdo();
$io = $pdo->Connect($database);
var_dump($io);

//  SELECTの定義を作成
$config = new Config();
$config->table = 't_test';
$config->where->id = 'not null';
$config->limit = 3;
$config->order = 'timestamp desc';

//  SELECTを実行
$record = $pdo->select($config);
$this->d($record);

//  Debugの方法
$this->mark( $pdo->qu() ); // 最後のSQL文を出力

//  INSERTの定義を作成
$config = new Config();
$config->table = 't_test';
$config->set->text = '新規レコードの作成テスト';

//  INSERTを実行
$id = $pdo->insert($config);
$this->mark('id='.$id);

//  UPDATEの定義を作成
$config = new Config();
$config->table = 't_test';
$config->set->text = 'レコードの更新テスト';
$config->where->id = $id -1;
$config->limit = 1;

//  UPDATEを実行
$num = $pdo->update($config);
$this->mark('num='.$num);



