<?php
  
Class WebScraperFiles{
	
	private $fileString;
	private $returnfileString;
	
	public function __construct(){

		$this->fileString = "./src/webScraperResult.json"; 
		$this->returnfileString = "src/webScraperResult.json";
	}

	public function jsonFileExists(){
		
		$bool = file_exists($this->fileString);
		return $bool; 
	}

	public function getContetJsonFile(){
		
		$content = file_get_contents($this->fileString);
		return json_decode($content, true);
	}
	
	public function getUrlJsonFile(){
		return $this->returnfileString;
	}
	
	/*
	 * postar data till json filen, skapar en ny fil om ingen finns, 
	 * annars töms den existerande filen och ny data skrivs in.
	 */ 
	public function postJsonToFile($json){
		
		if(!$this->jsonFileExists()){
			fopen($this->fileString, "w");
		}
		
		$emptyFile= fopen($this->fileString, "w" );
		fclose($emptyFile);
	
		$file = fopen($this->fileString, "a");
		fwrite($file, json_encode($json,JSON_PRETTY_PRINT));
		
		return $this->returnfileString; 
	}
}