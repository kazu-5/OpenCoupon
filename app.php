<?php

//include('NewWorld5.class.php');
//$app = new App();
include('Coupon.app.php');
$app = new Coupon();

$app->mark();

//  Set environment.
$app->SetEnv("controller-name","index.php");

//  layout
$app->SetEnv("layout-dir","layout");
$app->SetEnv("layout","default");

//  template
$app->SetEnv("template-dir","template");

//  config
$app->config( new CouponConfig() );

//  PDOの取得
$pdo = $app->pdo();
$io = $pdo->Connect($database);

//  coupon
//$app->config( new Coupon() );

//  Do dispatch
$app->Dispatch();
