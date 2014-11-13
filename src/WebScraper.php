<?php
  
Class WebScraper{
	
	private $url;
	private $content; 
	private $labels;
	private $countCourses; 
	
	public function __construct(){
		$this->url = "http://coursepress.lnu.se/kurser/"; 	
		$this->content = array(); 
		$this->labels = array('Course name', 'Course link', 'Course code','Curriculum url', 'Course info', 'Latest post', 'Author','Time posted');
	}
	
	public function getChosenData(){
		
		// extra tid att utföra webscrapningen till 5min, för att undvika error. 
		ini_set('max_execution_time', 300);

		// hämtar antal sidor med kurser. 
		$number = $this->checkEndSide($this->getData($this->url)); 
		
		for($i=1; $i<= $number; $i++){
		
			// url till vardera sida för skrapning. 
			$data = $this->getData($this->url."?bpage=".$i);
			
			$dom = new \DOMDocument(); 
	
			// laddar $data som html. 
			if($dom->loadHTML($data)){
				
				$xpath = new \DOMXPath($dom); 
				
				// letar upp alla ul taggar med id blog-lists, i ul leta efter div med klass item-title, välj a-tagg.
				$items = $xpath->query('//ul[@id = "blogs-list"]//div[@class ="item-title"]/a');
			
				foreach ($items as $item) {
					
					// om a tagen innehåller ordet kurs i urlen.
					if (strpos($item->getAttribute("href") ,"kurs") !== false){
						
						$this->countCourses ++; 
						
						// Hämtar information om varje kurs. 
						$manyThings = $this->getCourseInfo($item->getAttribute("href")); 	
				
						$this->content[] = array($this->labels[0] =>utf8_decode($item->nodeValue), $this->labels[1]=>utf8_decode($item->getAttribute("href")), $this->labels[2]=>$manyThings[0],
						$this->labels[3]=>$manyThings[1], $this->labels[4]=>$manyThings[2], $this->labels[5]=>$manyThings[3], $this->labels[6]=>$manyThings[4], $this->labels[7]=>$manyThings[5]); 
					}
				} 
		
			} else{
				die("Fel vid inläsning av HTML"); 
			}
		}

		// ändrar alla null värden i multidimensionella arrayen till "no information".  
		for($i=0; $i<count($this->content); $i++){
			
			foreach ($this->labels as $value){
				if($this->content[$i][$value] == null){
				$this->content[$i][$value] = "no information"; 
				}
			}
		}
		
		// lägger till antal kurser, datum för sista skrapning samt ett timestamp i som placeras över i arrayen content.  
		array_unshift($this->content, array('Number of courses'=>$this->countCourses, 'Last scrape'=>date("y/m/d h:i :s A",time()), 'Timestamp' => time())); 
		return $this->content; 
	}
	
	// returnerar antal sidor som ska skrapas. 
	public function checkEndSide($data){
		
		$dom = new \DOMDocument(); 
	 
		if($dom->loadHTML($data)){
			$xpath = new \DOMXPath($dom); 
			$numbers = $xpath->query('//div[@id = "blog-dir-pag-bottom"]/a[@class ="page-numbers"]');
			
			foreach ($numbers as $item) {
				$arr[] =  utf8_decode($item->nodeValue); 
			}
			return max($arr); 
			
		}else{
			die("Fel vid inläsning av HTML"); 
		}
	}
	
	public function getData($url){
		
		$ch = curl_init(); 
		
		curl_setopt($ch, CURLOPT_URL , $url); 
	
		// talar om att det vi hämtar hem inte ska skrivas ut direkt.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
		// identifierar mig. 
		curl_setopt($ch, CURLOPT_USERAGENT,"as223my"); 
	
		$data = curl_exec($ch);
		curl_close($ch); 
		
		return $data; 
	}
	
	// hämtar all information vi vill ha om en kurs. 
	public function getCourseInfo($url){
		libxml_use_internal_errors(true);
		
		$data = $this->getData($url); 
		
		$dom = new \DOMDocument(); 

		if($dom->loadHTML($data)){
			
			$xpath = new \DOMXPath($dom); 
			
			$courseID = $this->getCourseID($xpath);
			$coursePlanURL = $this->getCoursePlanURL($xpath); 
			$courseText = $this->getCourseText($xpath); 
			$coursePost = $this->getCoursePost($xpath);
			$coursePostAuthor = $this->getCoursePostAuthor($xpath);
			
			$content = array(); 
			$content[] = $courseID; 
			$content[] = $coursePlanURL; 
			$content[] = $courseText; 
			$content[] = $coursePost;
			$content[] = $coursePostAuthor[0]; 
			$content[] = $coursePostAuthor[1]; 
			return $content;
			
		} else{
			die("Fel vid inläsning av HTML!"); 
		}	
	}
	
	// returnerar kurskod. 
	public function getCourseID($xpath){
		
		$id = $xpath->query('//div[@id = "header-wrapper"]//li/a[@title]');
		
		foreach ($id as $item) {
		
			return utf8_decode($item->nodeValue); 
		}
	}
	
	// returnerar kursplan url. 
	public function getCoursePlanURL($xpath){
	
		$a = $xpath->query('//ul[@class= "sub-menu"]//li/a');
		
		foreach ($a as $item) {
			if (strpos($item->getAttribute("href") ,"kursinfo") !== false){
				return utf8_decode($item->getAttribute("href")); 
			}
			if (strpos($item->getAttribute("href") ,"kursplan") !== false){
					return utf8_decode($item->getAttribute("href")); 
			}
		}
	}
	
	// returnerar välkoms texten för kursen.
	public function getCourseText($xpath){
		
		$text = $xpath->query('//div[@class= "entry-content"]/p');
		
		foreach ($text as $item) {
			
			return utf8_decode($item->nodeValue); 
		}
	}
	
	// returnerar rubriken för första inlägget.
	public function getCoursePost($xpath){
		
		$h1 = $xpath->query('//header[@class= "entry-header"]/h1[@class= "entry-title"]');
	
		$count = 0; 
		foreach ($h1 as $item) {
			if($count == 0){
				return utf8_decode($item->nodeValue); 
			}
			$count = 1; 
		}
	}
	
	// returnerar författare och tid till första inlägget. 
	public function getCoursePostAuthor($xpath){
		
		$line = $xpath->query('//header[@class= "entry-header"]/p[@class= "entry-byline"]');
		$human = $xpath->query('//header[@class= "entry-header"]/p[@class= "entry-byline"]/strong');
		
		$count = 0;
		$time = null;
		$author = null;
		
		foreach ($line as $item) {
			if($count == 0){
			
				preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}/', $item->nodeValue, $match);
	       		$time = $match[0];
			}
			$count = 1;
		}
		
		$count = 0;
		foreach ($human as $item) {
			if($count == 0){
				
				$author = utf8_decode($item->nodeValue); 
			}
			$count = 1; 
		}
		
		return array($author, $time); 
	}
}
