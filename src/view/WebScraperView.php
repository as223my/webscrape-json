<?php
namespace view; 

class WebScraperView{
	
	public function showUrlJson($url){
		
		$html = "<a href='$url'>Web scrapning JSON!</a>"; 
		
		return $html; 
	}
}
