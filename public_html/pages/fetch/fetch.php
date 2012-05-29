<?php


switch($ACTION) {
	
	
	case 'file':
		
		try {
			
			if(isset($_FILES['file']) && isset($_FILES['file']['size']) && (int)$_FILES['file']['size'] > 0) $type = 'file';
			else $type = 'url';
			
			$response = array(
				'type' => $type
			);
			switch($type) {
				
				case 'file':
					$file = $_FILES['file'];
					$response['name'] = $file['name'];
					$response['size'] = $file['size'];
					$response['content'] = file_get_contents($file['tmp_name']);
					$response['extension'] = pathinfo($file['name'], PATHINFO_EXTENSION);
				break;
				
				case 'url':
					if(!isset($_REQUEST['url']) || empty($_REQUEST['url'])) throw new Exception('Vous devez spécifier une url');
					$response['name'] = $_REQUEST['url'];
					$response['content'] = file_get_contents($response['name']);
					$response['size'] = mb_strlen($response['content']);
					$response['extension'] = pathinfo(parse_url($_REQUEST['url'],PHP_URL_PATH), PATHINFO_EXTENSION);
				break;
				
			}
			
			if(!isUTF8($response['content'])) $response['content'] = utf8_encode($response['content']);
			
			$JSON = Gregory::JSON(array(
				'success' => true,
				'response' => $response
			),true);
			
		} catch(Exception $e) {
			
			$JSON = Gregory::JSON(array(
				'success' => false,
				'error' => $e->getMessage()
			),true);
			
		}
		
		/*header('Content-type: text/plain; charset="utf-8"');
		if(isset($_REQUEST['callback'])) echo $_REQUEST['callback'].'('.$JSON.')';
		else echo $JSON;
		exit();*/
		
		$this->setLayout(null);
		header('Content-type: text/html; charset="utf-8"');
		?>
		<html>
			<head>
				<script type="text/javascript">
					window.parent.fileImportCallback(<?=$JSON?>);
				</script>
			</head>
			<body></body>
		</html>
		<?php
		
	break;
	
	
}

function isUTF8($string){
    return preg_match('%(?:
    [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
    |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
    |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
    |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
    |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
    |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
    |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
    )+%xs', $string);
}
