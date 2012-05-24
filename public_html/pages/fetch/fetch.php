<?php


switch($ACTION) {
	
	
	case 'url':
		
		try {
			
			if(!isset($_REQUEST['url']) || empty($_REQUEST['url'])) throw new Exception('Vous devez spécifier une url');
			
			$url = $_REQUEST['url'];
			$content = file_get_contents($url);
			
			$JSON = Gregory::JSON(array(
				'success' => true,
				'response' => utf8_encode($content)
			),true);
			
		} catch(Exception $e) {
			
			$JSON = Gregory::JSON(array(
				'success' => false,
				'error' => $e->getMessage()
			),true);
			
		}
		
		header('Content-type: text/plain; charset="utf-8"');
		if(isset($_REQUEST['callback'])) echo $_REQUEST['callback'].'('.$JSON.')';
		else echo $JSON;
		exit();
	
	break;
	
	
}
