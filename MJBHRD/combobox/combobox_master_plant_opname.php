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

$pjname = "";
$querywhere = "";
$data='';

$query = "SELECT DISTINCT HL.LOCATION_ID AS DATA_VALUE
, HL.LOCATION_CODE AS DATA_NAME 
FROM APPS.HR_LOCATIONS HL
INNER JOIN MJ.MJ_MASTER_OPNAME MMO ON HL.LOCATION_ID = MMO.PLANT
INNER JOIN PER_PEOPLE_F PPF ON MMO.PERSON_ID = PPF.PERSON_ID  
WHERE LOCATION_ID IN (SELECT DISTINCT LOCATION_ID FROM APPS.PER_ASSIGNMENTS_F WHERE EFFECTIVE_END_DATE > SYSDATE)
AND PPF.PERSON_ID = $emp_id
ORDER BY LOCATION_CODE";
// echo $query;exit;

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