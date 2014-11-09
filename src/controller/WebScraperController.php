<?php

namespace controller; 

require_once("./src/model/WebScraper.php");
require_once("./src/view/WebScraperView.php");
	
class WebScraperController{
	
	private $webScraper; 
	private $webScraperView; 
	
	public function __construct(){
		$this->webScraper = new \model\WebScraper();
		$this->webScraperView = new \view\WebScraperView();
	}
	
	public function doWebScrape(){
		
		$this->webScraper->getData(); 
		
		
		return $this->webScraperView->showUrlJson("#"); 
	}
}
