<?php

include('NewWorld5.class.php');
$app = new App();
$app->mark();
$app->SetEnv("layout-dir","layout");
$app->SetEnv("layout","default");
$app->SetEnv("template-dir","template");
$app->SetEnv("controller-name","index.php");
$app->Dispatch();
