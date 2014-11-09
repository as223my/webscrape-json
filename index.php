<?php

require_once("src/view/HTMLView.php");
require_once("src/controller/WebScraperController.php");

$htmlView = new \view\HTMLView();

$controller = new \controller\WebScraperController();
$html = $controller->doWebScrape();

$htmlView ->echoHTML($html);
   