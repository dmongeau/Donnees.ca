<?php

/*
 *
 * Useful data
 *
 * Accessible directly from the Gregory Class
 * $Gregory->usefulData
 * or
 * Gregory::get()->usefulData
 *
 */
$app->data = array();

$app->data['formats'] = array(
	
	'csv' => array(
		'name' => 'CSV',
		'extensions' => array('csv')
	),
	'json' => array(
		'name' => 'JSON',
		'extensions' => array('json')
	),
	'xml' => array(
		'name' => 'XML',
		'extensions' => array('xml')
	),
	'xls' => array(
		'name' => 'Excel',
		'extensions' => array('xls','xlsx')
	),
	'html' => array(
		'name' => 'HTML',
		'extensions' => array('html','htm')
	),
	'other' => array(
		'name' => 'Autre',
		'extensions' => array()
	)
	
);