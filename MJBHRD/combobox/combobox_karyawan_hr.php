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
// $pos_name = "";
// $pos_id = "";
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
$queryname = "";
$plant = "";
$orgid ="";
$data ="";
// $querywhere2 = "";

		
if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND UPPER(FULL_NAME) LIKE '%$name%' ";
	$queryname = " AND UPPER(NAMA) LIKE '%$name%' ";
} 	

$query = "SELECT DISTINCT PPF.PERSON_ID, PPF.FULL_NAME
			FROM APPS.PER_JOBS PJ, APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_PEOPLE_F PPF
			WHERE PAF.PERSON_ID=PPF.PERSON_ID
			AND PJ.JOB_ID = PAF.JOB_ID
			AND PJ.NAME LIKE '%HRD%'  
			AND PAF.EFFECTIVE_END_DATE > SYSDATE
			AND PAF.PRIMARY_FLAG='Y'
			--AND PAF.POSITION_ID = 126061
			$querywhere";
                // echo $query;

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