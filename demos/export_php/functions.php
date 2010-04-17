<?PHP
$fcmonth 	= 5;
$fcyear  	= 2008;
$fcperiod = 6;

function function_checkdate($checkdate,$action) 
{
		if ($checkdate !='') 
		{
   	$checkdate = str_replace(".", "/", $checkdate);
   	$checkdate = str_replace("-", "/", $checkdate);
   	$checkdate = str_replace(",", "/", $checkdate);   	
   	$checkdate = str_replace(" ", "/", $checkdate);  
   	$expldate = explode("/", $checkdate);
   	
   	if (strlen($expldate[0]) == 1) {$expldate[0] = "0".$expldate[0]; $combine = '1';}
   	if (strlen($expldate[1]) == 1) {$expldate[1] = "0".$expldate[1]; $combine = '1';}
   	if (strlen($expldate[2]) == 1) {$expldate[2] = "200".$expldate[2]; $combine = '1';}  
   	if (strlen($expldate[2]) == 2) {$expldate[2] = "20".$expldate[2]; $combine = '1';}
   	if ($expldate[2] == '')        {$expldate[2] = date("Y"); $combine = '1';}
   	if ($combine == '1') {$checkdate = $expldate[0]."/".$expldate[1]."/".$expldate[2];}

   	$datecheck = ereg_replace("1", "0", $checkdate);
    $datecheck = ereg_replace("2", "0", $datecheck);
    $datecheck = ereg_replace("3", "0", $datecheck);
    $datecheck = ereg_replace("4", "0", $datecheck);
    $datecheck = ereg_replace("5", "0", $datecheck);
    $datecheck = ereg_replace("6", "0", $datecheck);
    $datecheck = ereg_replace("7", "0", $datecheck);
    $datecheck = ereg_replace("8", "0", $datecheck);
    $datecheck = ereg_replace("9", "0", $datecheck);
    
   	if ( ($datecheck == '00/00/0000') && (strlen($datecheck) == 10) ) 
   	{
   	$dateformat = "ok";
   	$expldate = explode("/", $checkdate);
    $unixdate = mktime(0, 0, 0, $expldate[1], $expldate[0], $expldate[2]);
    } else 
    {$checkdate = ""; $emptyindicator = "1";}
   
    if ($action == 'date') 		{return($checkdate);}
    if ($action == 'unix') 	  {return($unixdate);}
    if ($action == 'format') 	{return($dateformat);}
		}
}

$i = 1;
$j = 0;
$fcarray = array();
$fcyeararray = array();
$fcmontharray = array();
while ($i <= $fcperiod){
												$fcdate = function_checkdate("1/".$fcmonth."/".$fcyear,"unix") + $j;
												$fcarray[$i] = $i;
												$fcyeararray[$i] 	=	date("Y",$fcdate);																													   			
												$fcmontharray[$i] = date("n",$fcdate);
												// $fcdatearray[$i] 	=	function_checkdate("1/".$fcmonth."/".$fcyear,"unix");
												$fcdatearray[$i] 	=	function_checkdate((date("j",function_checkdate($fcdate,"unix"))."/".date("n",$fcdate)."/".date("Y",$fcdate)),"unix");
												$i++;
												$j = $j + 2678401;
											 }
?>