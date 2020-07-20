<?php
  header("Access-Control-Allow-Origin: *");

  include('./db/database.php');

	// header('Content-Type: application/json; charset=utf-8');

  $r_type   = $_GET['v_type'];
  $r_province = '';
  $r_department = '';

	if(isset($_GET['v_province'])){
    $r_province = $_GET['v_province'];
    $r_province = sprintf('%04d', $r_province);
	}
	if(isset($_GET['v_department'])){
    $r_department = $_GET['v_department'];
    $r_department = sprintf('%02d', $r_department);
	}
    
	$query = "";

  if($r_type == "province")
		$query = "SELECT * FROM ccuy_ubigeo_province WHERE department_id = '$r_department'";
	elseif($r_type == "district")
		$query = "SELECT * FROM ccuy_ubigeo_district WHERE province_id = '$r_province' AND department_id = '$r_department'";
	else
		$query = "SELECT * FROM ccuy_ubigeo_department";
  
  //database connection
  $conn = Database::getInstance()->query($query);
  
  //Initialize array variable
	$dbdata = array();
	
	foreach ($conn->results() as $row) {
      $dbdata[]=$row;
   }

  $conn->close();   
	//Print array in JSON format
	echo json_encode($dbdata);
	

