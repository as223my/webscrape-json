<?php

namespace model; 

Class WebScraper{
	
	private $url; 
	
	public function __construct(){
		
		$this->url = "http://coursepress.lnu.se/kurser/"; 
	}
	
	public function getData(){
		
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL , $this->url); 
	
		// Talar om att det vi hämtar hem inte ska skrivas ut direkt.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	
		$data = curl_exec($ch);
		curl_close($ch); 
		
		$chosenData = $this->choseData($data);
		return $chosenData; 
	}
	
	public function choseData($data){
		
		$dom = new \DOMDocument(); 

		if($dom->loadHTML($data)){
			
			$xpath = new \DOMXPath($dom); 
			// letar upp alla ul taggar med id blog-lists, i ul leta efter div med klass item-title. välj a-tagg.
			$items = $xpath->query('//ul[@id = "blogs-list"]//div[@class ="item-title"]/a');
			
			foreach ($items as $item) {
				// kolla på dom elements! (nodeValue tex).
				echo $item->nodeValue . " --> " .$item->getAttribute("href") ."<br />"; 
			} 
			//var_dump($items);
		} else{
			die("Fel vid inläsning av HTML"); 
		}
	}
}
