<?php
  
Class WebScraperFiles{
	
	private $timefile;
	private $fileString;
	private $returnfileString;
	
	public function __construct(){
		$this->timefile = "./src/time.txt"; 
		$this->fileString = "./src/webScraperResult.json"; 
		$this->returnfileString = "src/webScraperResult.json";
	}
	
	public function jsonFileExists(){
		
		$bool = file_exists($this->fileString);
		return $bool; 
	}
	
	public function getUrlJsonFile(){
		return $this->returnfileString;
	}
	
	public function postJsonToFile($json){
		
		if(!$this->jsonFileExists()){
			fopen($this->fileString, "w");
		}
		
		$emptyFile= fopen($this->fileString, "w" );
		fclose($emptyFile);
	
		$file = fopen($this->fileString, "a");
		fwrite($file, $json);
		
		return $this->returnfileString; 
	}
	
	public function timeFileExists(){
		
		$bool = file_exists($this->timefile);
		return $bool; 
		
	}
	
	public function addTime(){
		
		$time = time() + 60*5; 
		if($this->timeFileExists()){
			
			$emptyFile= fopen($this->timefile, "w" );
			fclose($emptyFile);
	
			$file = fopen($this->timefile, "a");
			fwrite($file, $time);
			
		}else{
			
			$file = fopen($this->timefile, "w");
			fwrite($file, $time);
		}
		
	}
	
	public function checkTime($newTime){
				
		if($this->timeFileExists()){
			$file = fopen($this->timefile, "r");
			$oldTime = fgets($file);
			fclose($file);
			
			if((int)$oldTime > $newTime){
				return false; 
			}else{
				return true; 
			}
		}
		
		return false; 
	}
}