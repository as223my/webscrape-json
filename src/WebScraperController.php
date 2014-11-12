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
		
		if($this->webScraperFiles->jsonFileExists()){
			
			$contentJsonFile = $this->webScraperFiles->getContetJsonFile();
			$time = $contentJsonFile[0]['Timestamp']; 
			
			$timeNow = time();
			$timeJson = $time + 60*5; 
			
			if($timeJson < $timeNow){
				
				$content = $this->webScraper->getChosenData(); 
				$fileUrl = $this->webScraperFiles->postJsonToFile($content); 
			
				return $fileUrl;
				
			}else{
				return $this->webScraperFiles->getUrlJsonFile();
			}
						
		}else{
			
			$content = $this->webScraper->getChosenData(); 
			$fileUrl = $this->webScraperFiles->postJsonToFile($content); 
			
			return $fileUrl;
		}
		
	}
}
