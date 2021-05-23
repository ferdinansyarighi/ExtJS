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
  }
$data = "";
$name = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND UPPER(NAME) LIKE '%$name%' ";
}

$query = "SELECT ID DATA_VALUE, NAME DATA_NAME, STANDARD_START || ' - ' || STANDARD_STOP WORK_SCHEDULE FROM APPS.HXT_SHIFTS
WHERE 1=1 $querywhere 
ORDER BY NAME";

$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_VALUE']=$row[0];
		$record['DATA_NAME']=$row[1];
		$record['DATA_WORK_SCHEDULE']=$row[2];
		$data[]=$record;
	}

	echo json_encode($data);
?>