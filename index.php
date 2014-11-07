<?php

require_once("src/view/HTMLView.php");
//require_once("src/controller/LoginController.php");

$htmlView = new \view\HTMLView();

//$controller = new \controller\LoginController();
$html = "HAHAHA";//$controller->doControll();

$htmlView ->echoHTML($html);
   