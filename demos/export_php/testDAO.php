<?php
  //Developers need to implement this following function.
  //Typically, it should connect to the database and return an 2d array.
	function getTestData($max=10){

		$data=array();
		$gl=array('br','fr','us');
		
			$record=array('no' => "31" , 'name' => 'abc31' , 'age' => "23", 
					'gender' => 'br', 'english' => "55",'math' => "38");
			array_push($data,$record);
			$record=array('no' => 32 , 'name' => 'abc32' , 'age' => 34, 
					'gender' => 'fr', 'english' => 65,'math' => 45);
			array_push($data,$record);
			$record=array('no' => 33 , 'name' => 'abc33' , 'age' => 25, 
					'gender' => 'br', 'english' => 98,'math' => 99);
			array_push($data,$record);
			$record=array('no' => 34 , 'name' => 'abc34' , 'age' => 24, 
					'gender' => 'us', 'english' => 23,'math' => 77);
			array_push($data,$record);
			$record=array('no' => 35 , 'name' => 'abc35' , 'age' => 23, 
					'gender' => 'fr', 'english' => 67,'math' => 55);
			array_push($data,$record);
			$record=array('no' => 36 , 'name' => 'abc36' , 'age' => 26, 
					'gender' => 'fr', 'english' => 42,'math' => 29);
			array_push($data,$record);
			$record=array('no' => 37 , 'name' => 'abc37' , 'age' => 22, 
					'gender' => 'us', 'english' => 69,'math' => 40);
			array_push($data,$record);
			$record=array('no' => 38 , 'name' => 'abc38' , 'age' => 22, 
					'gender' => 'br', 'english' => 97,'math' => 99);
			array_push($data,$record);
			$record=array('no' => 39 , 'name' => 'abc39' , 'age' => 23, 
					'gender' => 'us', 'english' => 63,'math' => 66);
			array_push($data,$record);
			$record=array('no' => 40 , 'name' => 'abc40' , 'age' => 25, 
					'gender' => 'br', 'english' => 88,'math' => 76);
			array_push($data,$record);
			
			$record=array('no' => 41 , 'name' => 'abc36' , 'age' => 26, 
					'gender' => 'fr', 'english' => 42,'math' => 29);
			array_push($data,$record);
			$record=array('no' => 42 , 'name' => 'abc37' , 'age' => 22, 
					'gender' => 'us', 'english' => 69,'math' => 40);
			array_push($data,$record);
			$record=array('no' => 43 , 'name' => 'abc38' , 'age' => 22, 
					'gender' => 'br', 'english' => 97,'math' => 99);
			array_push($data,$record);
			$record=array('no' => 44 , 'name' => 'abc39' , 'age' => 23, 
					'gender' => 'us', 'english' => 63,'math' => 66);
			array_push($data,$record);
			$record=array('no' => 45 , 'name' => 'abc40' , 'age' => 25, 
					'gender' => 'br', 'english' => 88,'math' => 76);
			array_push($data,$record);
			$record=array('no' => 46 , 'name' => 'abc33' , 'age' => 25, 
					'gender' => 'br', 'english' => 98,'math' => 99);
			array_push($data,$record);
			$record=array('no' => 47 , 'name' => 'abc34' , 'age' => 24, 
					'gender' => 'us', 'english' => 23,'math' => 77);
			array_push($data,$record);
			$record=array('no' => 48 , 'name' => 'abc35' , 'age' => 23, 
					'gender' => 'fr', 'english' => 67,'math' => 55);
			array_push($data,$record);
			$record=array('no' => 49 , 'name' => 'abc36' , 'age' => 26, 
					'gender' => 'fr', 'english' => 42,'math' => 29);
			array_push($data,$record);
			$record=array('no' => 50 , 'name' => 'abc37' , 'age' => 22, 
					'gender' => 'us', 'english' => 69,'math' => 40);
			array_push($data,$record);
			
			$record=array('no' => 51 , 'name' => 'abc39' , 'age' => 23, 
					'gender' => 'us', 'english' => 63,'math' => 66);
			array_push($data,$record);
			$record=array('no' => 52 , 'name' => 'abc40' , 'age' => 25, 
					'gender' => 'br', 'english' => 88,'math' => 76);
			array_push($data,$record);
			$record=array('no' => 53 , 'name' => 'abc33' , 'age' => 25, 
					'gender' => 'br', 'english' => 98,'math' => 99);
			array_push($data,$record);
			$record=array('no' => 54 , 'name' => 'abc34' , 'age' => 24, 
					'gender' => 'us', 'english' => 23,'math' => 77);
			array_push($data,$record);
			$record=array('no' => 55 , 'name' => 'abc35' , 'age' => 23, 
					'gender' => 'fr', 'english' => 67,'math' => 55);
			array_push($data,$record);		
			
		return $data;	
	}
	
	function getTestDataForMore($max=10){
    $data = getTestData();
    for($i=0;$i<count($data);$i++){
        $data[$i]['total'] = $data[$i]['english'] + $data[$i]['math'];
        $data[$i]['detail'] = 'something more...';
    }
    return $data;
  }


?>