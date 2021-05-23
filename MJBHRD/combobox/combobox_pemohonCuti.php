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
// $record = array();
// $record['DATA_VALUE']='- Pilih -';
// $record['DATA_NAME']='- Pilih -';
// $data[]=$record;
$data = "";
$name = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$queryPemohon = "SELECT PJ.JOB_ID 
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
	AND PPF.PERSON_ID=$emp_id";
	//echo $queryPemohon;
	$resultPemohon = oci_parse($con,$queryPemohon);
	oci_execute($resultPemohon);
	$rowPemohon = oci_fetch_row($resultPemohon);
	$jobPemohon = $rowPemohon[0];
	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND UPPER(PPF.FULL_NAME) LIKE '%$name%' ";
}

$query = "SELECT DISTINCT PPF.FULL_NAME AS DATA_VALUE, PPF.FULL_NAME AS DATA_NAME 
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
AND PAF.PRIMARY_FLAG='Y'
AND PPF.CURRENT_EMPLOYEE_FLAG='Y'
AND PJ.JOB_ID IN (SELECT PJ.JOB_ID 
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
AND PPF.CURRENT_EMPLOYEE_FLAG='Y'
AND PPF.PERSON_ID=$emp_id)
$querywhere 
ORDER BY PPF.FULL_NAME ASC";
 //echo $query;
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