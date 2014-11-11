<?php
namespace model; 

Class WebScraper{
	
	private $url;
	private $content; 
	private $labels;
	
	public function __construct(){
		$this->url = "http://coursepress.lnu.se/kurser/"; 	
		$this->content = array(); 
		$this->labels = array('kursnamn', 'kurslänk', 'kurskod','kursplan', 'kursinfo', 'senaste inlägget', 'författare','tid');
	}
	
	public function getChosenData(){
		ini_set('max_execution_time', 300);

		$number = $this->checkEndSide($this->getData($this->url)); 
		for($i=1; $i<= $number; $i++){
		
			$data = $this->getData($this->url."?bpage=".$i);
			
			$dom = new \DOMDocument(); 
	
			if($dom->loadHTML($data)){
				
				$xpath = new \DOMXPath($dom); 
				// letar upp alla ul taggar med id blog-lists, i ul leta efter div med klass item-title. välj a-tagg.
				$items = $xpath->query('//ul[@id = "blogs-list"]//div[@class ="item-title"]/a');
		
				foreach ($items as $item) {
					
					if (strpos($item->getAttribute("href") ,"kurs") !== false){
					
					$manyThings = $this->getCourseInfo($item->getAttribute("href")); 	
					//var_dump($manyThings); 
					$this->content[] = array($this->labels[0] =>$item->nodeValue, $this->labels[1]=>$item->getAttribute("href"), $this->labels[2]=>$manyThings[0],$this->labels[3]=>$manyThings[1], $this->labels[4]=>$manyThings[2], 
					$this->labels[5]=>$manyThings[3], $this->labels[6]=>$manyThings[4], $this->labels[7]=>$manyThings[5]); 
				
					//echo $item->nodeValue . " --> " .$item->getAttribute("href") . "<br />"; 
					}
				} 
		
			} else{
				die("Fel vid inläsning av HTML"); 
			}
		}

		for($i=0; $i<count($this->content); $i++){
			
			foreach ($this->labels as $value){
				if($this->content[$i][$value] == null){
				$this->content[$i][$value] = "no information"; 
				}
				
			}
		}
		echo json_encode($this->content);
		//var_dump($this->content); 
		//return $this->content;
	}
	
	public function checkEndSide($data){
		
		$dom = new \DOMDocument(); 

		if($dom->loadHTML($data)){
			$xpath = new \DOMXPath($dom); 
			$numbers = $xpath->query('//div[@id = "blog-dir-pag-bottom"]/a[@class ="page-numbers"]');
			
			foreach ($numbers as $item) {
				$arr[] =  $item->nodeValue; 
			}
			return max($arr); 
			
		}else{
			die("Fel vid inläsning av HTML"); 
		}
	}
	
	public function getData($url){
		
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL , $url); 
	
		// Talar om att det vi hämtar hem inte ska skrivas ut direkt.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	
		$data = curl_exec($ch);
		curl_close($ch); 
		
		return $data; 
	}
	
	public function getCourseInfo($url){
		libxml_use_internal_errors(true);
		
		$data = $this->getData($url); 
		
		$dom = new \DOMDocument(); 

		if($dom->loadHTML($data)){
			
			$xpath = new \DOMXPath($dom); 
			
			$a = $this->getCourseID($xpath);
	
			$b = $this->getCoursePlanURL($xpath); 
			$c = $this->getCourseText($xpath); 
			$d = $this->getCoursePost($xpath);
			$e = $this->getCoursePostAuthor($xpath);
			$arra = array(); 
			$arra[] = $a; 
			$arra[] = $b; 
			$arra[] = $c; 
			$arra[] = $d;
			$arra[] = $e[0]; 
			$arra[] = $e[1]; 
			return $arra;
			
		} else{
			die("Fel vid inläsning av HTML!"); 
		}	
	}
	
	public function getCourseID($xpath){
		$id = $xpath->query('//div[@id = "header-wrapper"]//li/a[@title]');
		
		foreach ($id as $item) {
		
			return $item->nodeValue; 
		}
	}
	
	public function getCoursePlanURL($xpath){
	
		$a = $xpath->query('//ul[@class= "sub-menu"]//li/a');
		
		foreach ($a as $item) {
			if (strpos($item->getAttribute("href") ,"kursinfo") !== false){
				return $item->getAttribute("href"); 
			}
			if (strpos($item->getAttribute("href") ,"kursplan") !== false){
					return $item->getAttribute("href"); 
			}
		}
	}
	
	public function getCourseText($xpath){
		
		$text = $xpath->query('//div[@class= "entry-content"]/p');
		
		foreach ($text as $item) {
			
			return $item->nodeValue; 
		}
	}
	
	public function getCoursePost($xpath){
		
		$h1 = $xpath->query('//header[@class= "entry-header"]/h1[@class= "entry-title"]');
	
		
		$count = 0; 
		foreach ($h1 as $item) {
			if($count == 0){
				return $item->nodeValue; 
			}
			$count = 1; 
		}
	}
	
	public function getCoursePostAuthor($xpath){
		$author = $xpath->query('//header[@class= "entry-header"]/p[@class= "entry-byline"]');
		
		$count = 0; 
		foreach ($author as $item) {
			if($count == 0){
				//return $item->nodeValue; 
				
				$string1 = str_replace("av","",$item->nodeValue);
				$string2 = str_replace("Publicerad","",$string1);
				$string3 = str_replace(' ', '', $string2);
				$resultNumbers = substr($string3, 0, 17);
				$string4 = substr($string3, 17);
				
				$doneNumbers = chunk_split($resultNumbers, 12, ' ');
				
				$splitstring = preg_split('/(?=[A-Z])/',$string4);
				$doneString = $splitstring[1] . " " . $splitstring[2];
				
				return array($doneString, $doneNumbers);  
			}
			$count = 1; 
		}
	}
}
