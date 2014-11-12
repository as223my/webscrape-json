<?php

class HTMLView{

	public function echoHTML($url){
		
		if ($url === NULL){
			throw new \Exception("HTMLView::echoHTML does not allow body to be null");
		}
			
		echo "
		<!DOCTYPE html>
		<html>
			<head>
			<title>WebScraper</title>
			<meta charset ='utf-8' />
			</head>
			<body>
				<h1>Web Scraper</h1>
				<a href=$url>Result Web Scraper</a>
			</body>
		</html>";
	}
}