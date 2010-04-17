<?php

//This is sigma grid exporting handler 
require_once('GridServerHandler.php');
//This is a php file for data feeding
require_once('testDAO.php');
//To create grid exporting instant.
$gridHandler = new GridServerHandler();

$type = getParameter('exportType');

if ( $type == 'pdf' ){
	// to use html2pdf to export pdf
	// param1 : Orientation. 'P' for Portrait , 'L' for Landscape
	// param2 : Paper size. Could be A3, A4, A5, LETTER, LEGAL
	// param3 : Relative picture path to this php file
	$gridHandler->exportPDF('P' ,'A4', '../');

}else {
	//to get the data from data base. // 
	$data1 = getTestDataForMore(); 

	if ( $type == 'xml' ){
		//exporting to xml
		$gridHandler->exportXML($data1);
	}else if ( $type == 'xls' ){
		//exporting to xls
		$gridHandler->exportXLS($data1);
	}else if ( $type == 'csv' ){
		//exporting to csv
		$gridHandler->exportCSV($data1);
	}else{
	  $data1 = getTestData();
		//for grid presentation
		$gridHandler->setData($data1);
		$gridHandler->setTotalRowNum(count($data1));
		$gridHandler->printLoadResponseText();
	}
}

?>