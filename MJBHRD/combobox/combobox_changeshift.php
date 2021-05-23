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
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND UPPER(PPF.FULL_NAME) LIKE '%$name%' ";
}

$query = "SELECT DISTINCT PPF.PERSON_ID,
PPF.FULL_NAME,
PPF.FIRST_NAME,
PPF.LAST_NAME,
PPF.MIDDLE_NAMES,
PAF.ASSIGNMENT_ID,
PAF.ORGANIZATION_ID
ORG_ID, HOU.NAME COMPANY_NAME, 
PAF.JOB_ID, PJ.NAME
DEPT_NAME,
PAF.POSITION_ID, PP.NAME POSTION_NAME, 
PAF.LOCATION_ID,
HL.LOCATION_CODE,
PAF.GRADE_ID, PG.NAME GRADE
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID
INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
INNER JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID = PP.POSITION_ID
INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
WHERE SYSDATE BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND SYSDATE BETWEEN PAF.EFFECTIVE_START_DATE 
AND PAF.EFFECTIVE_END_DATE 
--AND PAF.ORGANIZATION_ID = NVL(:P_ORG_ID,PAF.ORGANIZATION_ID)
--AND PAF.LOCATION_ID = NVL(:P_LOCATION_ID, PAF.LOCATION_ID)
--AND PAF.JOB_ID = NVL(:P_JOB_ID, PAF.JOB_ID)
--AND PAF.POSITION_ID = NVL(:P_POSITION_ID, PAF.POSITION_ID)
$querywhere 
ORDER BY PPF.FIRST_NAME, PPF.LAST_NAME";
 //echo $query;
$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_VALUE']=$row[5];
		$record['DATA_NAME']=$row[1];
		$record['DATA_COMPANY']=$row[7];
		$record['DATA_GRADE']=$row[13];
		$record['DATA_LOCATION']=$row[15];
		$data[]=$record;
	}

	echo json_encode($data);
?>