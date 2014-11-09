<?php
$data = curl_get_request("http://coursepress.lnu.se/kurser/"); 


$dom = new DOMDocument(); 

if($dom->loadHTML($data)){
	
	$xpath = new DOMXPath($dom); 
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

function curl_get_request($url){
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL , $url); 
	
	// Talar om att det vi hämtar hem inte ska skrivas ut direkt.
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	
	// Exekvera anropet. 
	$data = curl_exec($ch);
	curl_close($ch); 
	
	return $data;
}


/*curl_cookie_handling("login.."); 

function curl_cookie_handling($url){
	
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL , $url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	
	$post_arr = array(
	"code" => "1234"
	); 
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_arr); 
	//curl_setopt($ch, CURLOPT_HEADER, 1); 
	//curl_setopt($ch, CURLINFO_HEADER_OUT,1);
	
	curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__)."/kaka.txt");
	
	$data = curl_exec($ch);  
	curl_close($ch); 
	
	var_dump($data); 
}*()
