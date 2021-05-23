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
	$dept_name = $_SESSION[APP]['dept_name'];
}
 
 
$name = "";
$querywhere = "";

// $record = array();
// $record['DATA_VALUE']="- Pilih -";
// $record['DATA_NAME']="- Pilih -";
// $data[]=$record;
		
if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND UPPER(NO_BS) LIKE '%$name%' ";
}

if (isset($_GET['person_id']))
{	
	$person_id = $_GET['person_id'];
	$queryTingkat = "SELECT ASSIGNMENT_ID FROM APPS.PER_ASSIGNMENTS_F WHERE PERSON_ID=$person_id";
	// echo $queryTingkat;
	$resultTingkat = oci_parse($con,$queryTingkat);
	oci_execute($resultTingkat);
	$rowTingkat = oci_fetch_row($resultTingkat);
	$assign_id = $rowTingkat[0];
	
	// $querywhere .= " AND ASSIGNMENT_ID = $assign_id ";
	
	$querywhere .= " AND ( ASSIGNMENT_ID = $assign_id OR CREATED_BY = $emp_id )";
	
}

$query = "SELECT DISTINCT ID AS DATA_VALUE, NO_BS AS DATA_NAME FROM MJ.MJ_T_BS WHERE 1=1 AND AKTIF = 'Y' $querywhere ORDER BY NO_BS ASC";

// echo $query; exit;

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