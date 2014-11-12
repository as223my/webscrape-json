<?php

require_once("src/HTMLView.php");
require_once("src/WebScraperController.php");

$controller = new \WebScraperController();
$view = new \HTMLView();

$url = $controller->doWebScrape();

$view->echoHTML($url); 


   