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


$queryDept = "SELECT COUNT(-1) FROM PER_ASSIGNMENTS_F PAF
INNER JOIN PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
WHERE PJ.NAME LIKE '%HRD%'
AND PERSON_ID = $emp_id";
$resultDept = oci_parse($con,$queryDept);
oci_execute($resultDept);
$rowDept = oci_fetch_row($resultDept);
$countDeptHrd=$rowDept[0];

$queryStaf = "SELECT COUNT(-1)  FROM PER_ASSIGNMENTS_F PAF
            INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
            WHERE SYSDATE BETWEEN PG.DATE_FROM AND NVL(PG.DATE_TO, SYSDATE)
            AND PG.GRADE_ID = 1069
            AND PAF.PERSON_ID = $emp_id";
$resultStaf = oci_parse($con,$queryStaf);
oci_execute($resultStaf);
$rowStaf = oci_fetch_row($resultStaf);
$countStaf=$rowStaf[0];

if($countStaf>0){
	$querywhere .= " AND (PPG.SEGMENT1 IN('Plant', 'CP', 'RNT') OR PPG.SEGMENT1 IS NULL) ";
}

if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere .= " AND UPPER(PPF.FULL_NAME) LIKE '%$name%' ";
}
if (isset($_GET['org_id']))
{	if($_GET['org_id']!='All'){
		$orgid = $_GET['org_id'];
		$querywhere .= " AND PAF.ORGANIZATION_ID = $orgid";
	}
}
//echo $querywhere;exit;
if($countDeptHrd>0){
	$query = "SELECT DISTINCT PPF.PERSON_ID AS DATA_VALUE, PPF.FULL_NAME AS DATA_NAME, HL.LOCATION_CODE, PPG.SEGMENT2, PG.NAME
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	inner join APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
	inner join APPS.HR_LOCATIONS HL ON PAF.location_id = hl.location_id
	LEFT JOIN APPS.PAY_PEOPLE_GROUPS PPG ON PAF.PEOPLE_GROUP_ID = PPG.PEOPLE_GROUP_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
	AND UPPER(PPF.FULL_NAME) NOT LIKE '%SALAH%' 
	AND UPPER(PPF.FULL_NAME) NOT LIKE '%TRIAL%'
	$querywhere ORDER BY PPF.FULL_NAME ASC";
	$result = oci_parse($con, $query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$record = array();
			$record['DATA_VALUE']=$row[0];
			$record['DATA_NAME']=$row[1];
			$record['DATA_LOCATION']=$row[2];
			$record['DATA_GAJIAN']=$row[3];
			$record['DATA_GRADE']=$row[4];
			$data[]=$record;
		}
}

	echo json_encode($data);
?>