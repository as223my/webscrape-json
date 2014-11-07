<?php
namespace view; 

class HTMLView {

	public function echoHTML($content){
		
		if ($content === NULL){
			throw new \Exception("HTMLView::echoHTML does not allow body to be null");
		}
			
		echo"
		<!DOCTYPE html>
		<html>
			<head>
			<title>Laboration 1</title>
			<meta charset ='utf-8' />
			</head>
			<body>
				$content
			</body>
		</html>";
	}
}