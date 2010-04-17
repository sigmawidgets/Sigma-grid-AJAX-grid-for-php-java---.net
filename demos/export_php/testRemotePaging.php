<?php

//This is sigma grid exporting handler 
require_once('GridServerHandler.php');
//This is a php file for data feeding
require_once('testDAO.php');
//To create grid exporting instant.
$gridHandler = new GridServerHandler();

$type = getParameter('exportType');
//to get the data from data base. //
$data1 = getTestData();

$start = $gridHandler->pageInfo["startRowNum"];
$pageSize = $gridHandler->pageInfo["pageSize"];

$data2 = array_slice($data1, $start, $pageSize);

//for grid presentation
$gridHandler->setData($data2);
$gridHandler->setTotalRowNum(count($data1));
$gridHandler->printLoadResponseText();


?>