<?php

require_once("./src/WebScraper.php");
require_once("./src/WebScraperFiles.php");
	
class WebScraperController{

	private $webScraper; 
	private $webScraperFiles; 
	
	public function __construct(){
		
		$this->webScraper = new \WebScraper();
		$this->webScraperFiles = new \WebScraperFiles();
	}
	
	public function doWebScrape(){
		
		$timeNow = time();
				
		if(!$this->webScraperFiles->jsonFileExists() || $this->webScraperFiles->checkTime($timeNow) === true){

			$content = $this->webScraper->getChosenData(); 
			$fileUrl = $this->webScraperFiles->postJsonToFile($content); 
			
			$this->webScraperFiles->addTime(); 
			
			return $fileUrl;
			
		}else{
			
			return $this->webScraperFiles->getUrlJsonFile(); 
		}
		
	}
}
