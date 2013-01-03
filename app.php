<?php

include('NewWorld5.class.php');
$app = new App();
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

//  Do dispatch
$app->Dispatch();
