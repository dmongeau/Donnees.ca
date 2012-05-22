<?php


switch($ACTION) {
	
	
	case 'add':
	
		$this->addStylesheet('/statics/css/modules/collections.add.css');
		$this->addScript('/statics/js/parser.js');
		$this->addScript('/statics/js/modules/collections.add.js');
		$this->addScript('/statics/ace/ace-uncompressed-noconflict.js');
	
		include PATH_MODULE_COLLECTIONS_PUBLIC.'/add.php';
	
	break;
	
	default:
	
		
		
		include PATH_MODULE_COLLECTIONS_PUBLIC.'/index.php';
		
	break;
	
	
}
