<?php

//include('NewWorld5.class.php');
//$app = new App();
include('Coupon.app.php');
$app = new Coupon();

$app->mark();

//  Set environment.
$app->SetEnv("controller-name","index.php");

//  layout
$app->SetEnv("layout-dir","app:/zlib/layout");
$app->SetEnv("layout","default");

//  template
$app->SetEnv("template-dir","app:/zlib/template");

//  Set model directory
$app->SetEnv('model-dir','app:/zlib/model');

//  config
$app->config( new CouponConfig() );

//  PDOの取得
$pdo = $app->pdo();
$io = $pdo->Connect($database);

//  Do dispatch
$app->Dispatch();
