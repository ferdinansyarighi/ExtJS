<?php
include '../main/koneksi.php';

session_start();
$user_id = "";
$username = "";
$emp_id = "";
$emp_name = "";
$io_id = "";
$io_name = "";
$loc_id = "";
$loc_name = "";
$org_id = "";
$org_name = "";
$pos_name = "";
//$pos_id = "";
 if(isset($_SESSION[APP]['user_id']))
  {
	$user_id = $_SESSION[APP]['user_id'];
	$username = $_SESSION[APP]['username'];
	$emp_id = $_SESSION[APP]['emp_id'];
	$emp_name = $_SESSION[APP]['emp_name'];
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
	// $pos_name = $_SESSION[APP]['pos_name'];
	//$pos_id = $_SESSION[APP]['pos_id'];
  }

$name = "";
$querywhere = "";
	
	//ando
	$query = "SELECT DISTINCT ORGANIZATION_ID AS DATA_VALUE, NAME AS DATA_NAME FROM APPS.HR_OPERATING_UNITS 
	where 1=1 and date_to is null 
	ORDER BY NAME";

$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_VALUE']=$row[0];
		$record['DATA_NAME']=$row[1];
		$data[]=$record;
	}

	echo json_encode($data);
?>