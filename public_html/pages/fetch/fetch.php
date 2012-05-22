<?php


switch($ACTION) {
	
	
	case 'url':
		
		try {
			
			if(!isset($_REQUEST['url']) || empty($_REQUEST['url'])) throw new Exception('Vous devez spécifier une url');
			
			$url = $_REQUEST['url'];
			$type = NE($_REQUEST,'type','json');
			$content = file_get_contents($url);
			
			$data = $content;
			
			Gregory::JSON(array(
				'success' => true,
				'response' => $data
			));
			
		} catch(Exception $e) {
			
			Gregory::JSON(array(
				'success' => false,
				'error' => $e->getMessage()
			));
			
		}
	
	break;
	
	
}
