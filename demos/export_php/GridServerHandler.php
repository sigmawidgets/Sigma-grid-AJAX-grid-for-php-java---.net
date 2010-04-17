<?php

require_once('JSON.php');
define('CONTENTTYPE', "text/html; charset=UTF-8");
define('GT_JSON_NAME', "_gt_json");
define('DATA_ROOT', "data");

function row2Json($row){
		global $JSONUtils ;
		if (!isset($row) || $row==null) return '';
		return $JSONUtils->encode($row);
}

function json2Row($jsonStr){
		global $JSONUtils ;
		return $JSONUtils->decode($jsonStr);
}
	
function getCommonDate( $times=null ){
		return date('Y-m-d H:i:s',!$times?time():$times);
}

function getParameter($key){
		// empty or isset ?
		$value=$_POST[$key];
		$value=isset($value)?$value:(isset($_GET[$key])?$_GET[$key]:NULL);
		if (get_magic_quotes_gpc()){
			$value=stripslashes($value);
		}
		return $value; 
}

//reserved
function getRequestArray(){
}

function getDocumentRoot(){
		#get env variables under apache 
		$document_root = isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] : "";
		#get env variables under IIS
		if( !$document_root ){
		  $sf = str_replace("\\","/",$_SERVER["SCRIPT_FILENAME"]);
		  $sn = $_SERVER["SCRIPT_NAME"];
		  $document_root = str_replace( $sn, "", $sf );
		}
		return $document_root;
}

function debug($msg , $file="/_server_log.log"){
		$msg = print_r( $msg ,true );
		error_log($msg."\n", 3,  getDocumentRoot()."/_server_log.log");
}


class GridServerHandler {
		var $jsonObject =null;
		var $action =null;
		var $recordType =null;
		var $data =null;
		var $exception = null;
		var $pageInfo = array();
		var $exportFileName = 'mydoc';
		var $orientation = 'P';
		var $pageFormats = 'A4';

		//var $columnInfo = array();
		function GridServerHandler($gtJson = false)
		{	
			if ($gtJson==false || empty($gtJson)){
				$gtJson=getParameter(GT_JSON_NAME);
			}
			//echo $gtJson;
			$this->init( $gtJson );
		}

		function init($gtJson = false) {
			global $JSONUtils ;
			if (!empty($gtJson)){
					$this->jsonObject =  $JSONUtils->decode($gtJson);
					$this->action= $this->jsonObject["action"];
					$this->recordType= $this->jsonObject["recordType"].'';
					if ("load"==$this->action){
					  //echo "come here";
						$this->initPageInfo();
						$this->initSortInfo();
						$this->initFilterInfo();
					}else if ("save"==$this->action){
						
					}else if ("export"==$this->action){
						
					}
			}
		}

		function getColumnInfo(){
			$columnInfo_JS = $this->jsonObject["columnInfo"];
			if ($columnInfo_JS!=null){
			}
			return $columnInfo_JS;
		}

		function getDisplayColumnInfo(){
			$columnInfo_JS = $this->getColumnInfo();
			$disColumnInfo = array();
			if ($columnInfo_JS!=null){
				foreach ($columnInfo_JS as $idx => $col) {
					if ( $col['hidden'].''!='1' && $col['hidden'].''!='true' && $col['hidden'].''!='TRUE'){
						array_push($disColumnInfo,$col);
					}
				}
			}
			return $disColumnInfo;
		}

		function initPageInfo(){
			$pageInfo_JS = $this->jsonObject["pageInfo"];
			if ($pageInfo_JS!=null){
				$keys=array(
					"pageSize" ,
					"pageNum" ,
					"totalRowNum" ,
					"totalPageNum" ,
					"startRowNum" ,
					"endRowNum"
				);
				foreach ($keys as $value) {
					$this->pageInfo[$value]=intval($pageInfo_JS[$value]);
				}
			}
		}
		
		function initSortInfo(){
			$JSON = $this->jsonObject["sortInfo"];
			//var_dump($this->jsonObject);
			if ($JSON!=null){
			  $this->sortInfo = $JSON;
			  //echo var_dump($this->sortInfo);
			}
		}
		
		function initFilterInfo(){
			$JSON = $this->jsonObject["filterInfo"];
			if ($JSON!=null){
			  $this->filterInfo = $JSON;
			}
		}

		function setData($data) {
			$this->data = $data;
		}


		function getTotalRowNum() {
			return intval($this->pageInfo["totalRowNum"]);
		}

		function setTotalRowNum($totalRowNum) {
			$this->pageInfo["totalRowNum"]=intval($totalRowNum);
		}

		function getLoadResponseJSON(){
			$json=array();
			$json[DATA_ROOT]=$this->data;
			$json["pageInfo"]=$this->pageInfo;
			$json["exception"]=$this->exception;
			return $json;
		}

		function getLoadResponseText(){
			global $JSONUtils ;
			$json=$this->getLoadResponseJSON();
			$jstr=$json==null?"": $JSONUtils->encode($json);
			return $jstr;
		}

		function printLoadResponseText(){
			echo $this->getLoadResponseText();
		}
		
		function getExportFileName(){
			$fileName=getParameter('exportFileName');
			return !empty($fileName)?$fileName:$this->exportFileName;
		}


		function object2array($var){
			$type= gettype($var);
			if ($type=='object'){
				return get_object_vars($var);
			}
			return $var;
		}

		function exportGrid( $data, $baseImgPath='') {
			$type= getParameter('exportType');
			$this->exportFileName = $this->getExportFileName();
			if ($type=='pdf'){
				$this->exportPDF($baseImgPath);
			}else if ($type=='xml'){
				$this->exportXML($data);
			}

		}

		function exportXML( $data ) {
			$this->exportFileName = $this->getExportFileName();
			$cols=$this->getDisplayColumnInfo();

			$xml=array('<root>');

			foreach ( $data as $idx => $record ){
				$record = $this->object2array($record);
				$recordA= array();
				array_push($xml,"\t<row>" );
        foreach ( $cols as $idx => $col ){
					$fieldName=$col['fieldIndex'];
					$f= $record[$fieldName];
					$f= empty($f)?'':$f;
					array_push($xml,"\t\t<".$fieldName.'>'.htmlspecialchars($f).'</'.$fieldName.'>');	
				}	
				array_push($xml,"\t</row>" );
			}	
			array_push($xml,'</root>' );
			
			$this->downloadTextFile($this->exportFileName.'.xml', join($xml,"\n") );
		}

		function exportXLS( $data) {
			require_once('ExcelExport.php');
			$this->exportFileName = $this->getExportFileName();
			$cols=$this->getDisplayColumnInfo();

			$xls = new ExcelExport();
			
			$colHeader= array();
			foreach ( $cols as $idx => $col ){
				array_push($colHeader,$col['header'] );		
			}	
			$xls->addRow( $colHeader );
			foreach ( $data as $idx => $record ){
				$record = $this->object2array($record);
				$recordA= array();
				foreach ( $cols as $idx => $col ){
					$f= $record[$col['fieldIndex']];
					array_push($recordA,empty($f)?'':$f );		
				}	
				$xls->addRow( $recordA );
			}	
		
			$xls->download($this->exportFileName.'.xls');
		}
		
		function parseCSVCell( $cell) {
		  $val=$cell;
		  if(!is_numeric($val) && is_string($val)) {
			$val= empty($val)?'':$val;
			$val= str_replace("\n","\r", str_replace("\r\n","\r",$val) );
			$val= str_replace('"','""',$val);
			$val= '"'.$val.'"';
		  }
		  return $cell;
		}

		function exportCSV( $data) {
			$this->exportFileName = $this->getExportFileName();
			$cols=$this->getDisplayColumnInfo();

			$csv=array();

			$colHeader= array();
			foreach ( $cols as $idx => $col ){
				array_push($colHeader,$this->parseCSVCell($col['header']) );		
			}	
			
			array_push($csv,join($colHeader,",") );		

			foreach ( $data as $idx => $record ){
				$record = $this->object2array($record);
				$recordA= array();
				foreach ( $cols as $idx => $col ){
					$f= $record[$col['fieldIndex']];
					array_push($recordA,  $this->parseCSVCell($f) );		
				}	
				array_push($csv, join($recordA,",") );
			}	
		
			$this->downloadTextFile($this->exportFileName.'.csv', join($csv,"\n") );
		}

		
		function downloadTextFile($fileName,$text){
					header('Content-Length: '.strlen($text));
					header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
					header("Content-Type: application/force-download");
					header("Content-Type: application/octet-stream");
					header("Content-Type: application/download");
					header('Content-Disposition: attachment; filename="'.$fileName.'"');
					header('Cache-Control: private, max-age=0, must-revalidate');
					header('Pragma: public');
					ini_set('zlib.output_compression','0');
					echo $text;
		}

		function exportPDF( $orientation="P",$pageFormats="A4",$baseImgPath='') {
			$this->exportFileName = $this->getExportFileName();
			$this->exportHTML2PDF($orientation,$pageFormats,$baseImgPath);
		}

		function exportHTML2PDF($orientation,$pageFormats, $baseImgPath) {

			require_once(dirname(__FILE__).'/html2pdf/html2pdf.class.php');

			ob_start();
		?>

		<style type="text/css">
		.gt-table {
			border: solid 0px #000000;
			border-left:1px; 
			border-top:1px;
			width: 100%;
		}
		.gt-table th {
			background-color: #eeeeee;
			border-right:1px; border-bottom:1px;
		}

		.gt-table td {
			border-right:1px; border-bottom:1px;
		}

		.gt-inner {
			width: 100%;
		}

		.gt-inner-right {
		  text-align : right;
		}


		</style>

		<?php
			$template_style = ob_get_clean();
			$_pageD='10mm';
			$tableHTML= getParameter('__gt_html');
			//$headS = strpos($tableHTML, '<!-- gt : head start  -->')+strlen('<!-- gt : head start  -->');
			$headE = strpos($tableHTML, '<!-- gt : head end  -->') +strlen('<!-- gt : head end  -->'); 
			$tableStartHTML = substr($tableHTML , 0,$headE );
			
			$tableHTML= str_replace('.gt-grid ','',$tableHTML);
			$tableHTML=str_replace('<!-- gt : page separator  -->','</tbody></table></page><page backtop="'.$_pageD.'" backbottom="'.$_pageD.'">'.$tableStartHTML.'<tbody>',$tableHTML);
			
					
			//debug ( $template_style );
						//debug ("----------------\n");
			//debug ( $tableHTML );
			////////////////////////////////////////////////////////////////////////
			//begin !!the following lines exist for enhance exporting performance
      ////////////////////////////////////////////////////////////////////////
			
			preg_match_all('/\.([a-z0-9_\-]+)\s+\{(.*?)display:none;(.*?)\}/', $tableHTML, $result, PREG_SET_ORDER);
	    $patternArray = array();
	    $replaceArray = array();
      for ($matchi = 0; $matchi < count($result); $matchi++) {
		     $patternArray[$matchi] ='/<td\s+class="([a-z0-9_\-]+\s+)*' . $result[$matchi][1] . '(\s+[a-z0-9_\-]+)*\s*"[^>]*>(.*?)<\/td>/';
		     //debug($patternArray[$matchi] ."\n");
		     $replaceArray[$matchi] = '';
	    }
	    $tableHTML = preg_replace($patternArray,$replaceArray,$tableHTML);
	    
      ////////////////////////////////////////////////////////////////////////
			//end !!
      ////////////////////////////////////////////////////////////////////////
	    //debug ("----------------\n");
			
			//debug ( $tableHTML );

			$page_content = '<page backtop="'.$_pageD.'" backbottom="'.$_pageD.'">' .
				$template_style.$tableHTML.'</page>';

			$pdf = new HTML2PDF($orientation,$pageFormats,'en',false,$baseImgPath);
			$pdf->WriteHTML($page_content, false);
			$pdf->pdf->Output($this->exportFileName.'.pdf', 'D');
		}

}




?>