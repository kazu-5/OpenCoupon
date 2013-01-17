<?php

include('Coupon.app.php');
$app = new CouponApp();

//  Set environment.
$app->SetEnv("controller-name","index.php");

//  layout
$app->SetEnv("layout-dir","app:/layout");
$app->SetEnv("layout","default");

//  template
$app->SetEnv("template-dir","app:/template");

//  Set model directory
$app->SetEnv('model-dir','app:/model');

//  config
$app->config( new CouponConfig() );

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
