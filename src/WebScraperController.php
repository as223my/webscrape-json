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
		
		// Kollar om en json fil existerar, om inte påbörjas skrapningen direkt.  
		if($this->webScraperFiles->jsonFileExists()){
			
			// hämtar innehållet i json filen. 
			$contentJsonFile = $this->webScraperFiles->getContetJsonFile();
			$time = $contentJsonFile[0]['Timestamp']; 
			
			$timeNow = time();
			$timeJson = $time + 60*5; 
			
			// Om det har gått mer än 5min sen senaste skrapningen. 
			if($timeJson < $timeNow){
				
				// skrapar data. 
				$content = $this->webScraper->getChosenData(); 
				$fileUrl = $this->webScraperFiles->postJsonToFile($content); 

				// returnerar url till json filen med dess innehåll till vyn. 
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
