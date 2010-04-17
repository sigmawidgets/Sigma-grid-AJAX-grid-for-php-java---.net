<?php

//This is sigma grid exporting handler 
require_once('GridServerHandler.php');
//This is a php file for data feeding
require_once('testDAO.php');


class Utility { 
    /* 
    * @param array $ary the array we want to sort 
    * @param string $clause a string specifying how to sort the array similar to SQL ORDER BY clause 
    * @param bool $ascending that default sorts fall back to when no direction is specified 
    * @return null 
    */ 
    public static function orderBy(&$ary, $clause, $ascending = true) { 
        $clause = str_ireplace('order by', '', $clause); 
        $clause = preg_replace('/\s+/', ' ', $clause); 
        $keys = explode(',', $clause); 
        $dirMap = array('desc' => 1, 'asc' => -1); 
        $def = $ascending ? -1 : 1; 

        $keyAry = array(); 
        $dirAry = array(); 
        foreach($keys as $key) { 
            $key = explode(' ', trim($key)); 
            $keyAry[] = trim($key[0]); 
            if(isset($key[1])) { 
                $dir = strtolower(trim($key[1])); 
                $dirAry[] = $dirMap[$dir] ? $dirMap[$dir] : $def; 
            } else { 
                $dirAry[] = $def; 
            } 
        } 

        $fnBody = ''; 
        for($i = count($keyAry) - 1; $i >= 0; $i--) { 
            $k = $keyAry[$i]; 
            $t = $dirAry[$i]; 
            $f = -1 * $t; 
            $aStr = '$a[\''.$k.'\']'; 
            $bStr = '$b[\''.$k.'\']'; 
            if(strpos($k, '(') !== false) { 
                $aStr = '$a->'.$k; 
                $bStr = '$b->'.$k; 
            } 

            if($fnBody == '') { 
                $fnBody .= "if({$aStr} == {$bStr}) { return 0; }\n"; 
                $fnBody .= "return ({$aStr} < {$bStr}) ? {$t} : {$f};\n";                
            } else { 
                $fnBody = "if({$aStr} == {$bStr}) {\n" . $fnBody; 
                $fnBody .= "}\n"; 
                $fnBody .= "return ({$aStr} < {$bStr}) ? {$t} : {$f};\n"; 
            } 
        } 

        if($fnBody) { 
            $sortFn = create_function('$a,$b', $fnBody); 
            usort($ary, $sortFn);        
        } 
    } 
} 


//To create grid exporting instant.
$gridHandler = new GridServerHandler();

$type = getParameter('exportType');
//to get the data from data base. //
$data1 = getTestData();

//echo "come here";
if( $gridHandler->sortInfo){
  //echo "sortinfo:true<br>";
  $sortOrder = $gridHandler->sortInfo[0]["sortOrder"];
  //echo var_dump($gridHandler->sortInfo[0]);
  if($sortOrder!="defaultsort"){
    $comp_field = $gridHandler->sortInfo[0]["columnId"];
    //echo "sortOrder:". $comp_field;
    //echo $comp_field . " " . $sortOrder . "\n";
    //usort($data1, "mycompare");
    Utility::orderBy($data1, $comp_field . " " . $sortOrder); 
  }
}

//for grid presentation
$gridHandler->setData($data1);
$gridHandler->setTotalRowNum(count($data1));
$gridHandler->printLoadResponseText();


?>

