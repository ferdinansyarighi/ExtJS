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

$name = "";
$querywhere = "";
		
if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND UPPER(NO_PENGAJUAN) LIKE '%$name%' ";
}

$querycount = "SELECT COUNT(-1) FROM MJ.MJ_T_RESIGN WHERE STATUS = 1 AND APPROVAL_MANAGER_HRD = 'Approved' 
	AND APPROVAL_MANAGER = 'Approved' $querywhere";
$resultcount = oci_parse($con,$querycount);
oci_execute($resultcount);
$rowcount = oci_fetch_row($resultcount);
$jumgen = $rowcount[0];

if($jumgen >= 1)
{
	$query = "SELECT DISTINCT NO_PENGAJUAN AS DATA_VALUE, NO_PENGAJUAN AS DATA_NAME FROM MJ.MJ_T_RESIGN WHERE STATUS = 1 AND APPROVAL_MANAGER_HRD = 'Approved' 
	AND APPROVAL_MANAGER = 'Approved' $querywhere";
}
else {
	$query = "SELECT '--- Tidak Ada Resign Yang Sudah Di Approve ---' AS DATA_VALUE, '---Tidak Ada Pengajuan Yang Telah Di Approve ---' AS DATA_VALUE FROM DUAL";	
}

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