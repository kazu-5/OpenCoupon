<?php

//  Measure the execution time.
$st = microtime(true);

//  Include OnePiece-Framework
include('OnePiece5.class.php');
$app = new CouponApp();

//  Set environment.
$app->SetControllerName("index.php");

//  Setting
$app->SetSettingName("setting.php");

//  layout
$app->SetLayoutDir("app:/zlib/layout");
$app->SetLayoutName("default");

//  template
$app->SetTemplateDir("app:/zlib/template");

//  Set model directory
$app->SetModelDir('app:/zlib/model');

//  Set module directory
$app->SetModuleDir('app:/zlib/module');

//  config
$app->config();

//  PDO Initialized
$database = $app->config()->database();
$io = $app->pdo()->Connect($database);

//  If failed to connect database.
if(!$io){
	$app->d( Toolbox::toArray($database) );
	exit(0);
}

// Access test
// $record = $app->pdo()->Quick(' t_test.id=1 ');
// $app->d($record);

//  Do dispatch
$app->Dispatch();

//  Measure the execution time.
$en = microtime(true);
printf('<div>Execute time is %s seconds.</div>', $en - $st );
