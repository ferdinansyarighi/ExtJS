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
$querywhere_hdid = "";
$jobId = "";
$count = 0;
if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere .= " AND UPPER(PPF.FULL_NAME) LIKE '%$name%' ";
}

if (isset($_GET['hdid']))
{	
	$hdid = $_GET['hdid'];
	$querywhere_hdid = " AND ID <> $hdid ";
}

//BACKUP NAI SFE JADI SATU
// $query = "SELECT PAF.JOB_ID
// FROM APPS.PER_PEOPLE_F PPF
// INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
// WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
// AND PPF.PERSON_ID=$emp_id";

$queryHrd = "SELECT COUNT(-1)
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID 
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
AND PJ.JOB_ID = 26066
AND PPF.PERSON_ID =$emp_id";

$resultHrd = oci_parse($con, $queryHrd);
oci_execute($resultHrd);
$rowHrd = oci_fetch_row($resultHrd);
$countHrd = $rowHrd[0];

$querywhere .= "";

// ditutup oleh yuke
/*
if($countHrd > 0){
	$querywhere .= "";
}else{
	
	$querywhere .= " AND PAF.ORGANIZATION_ID=$org_id";
	
	$queryMgr = "SELECT count(-1)
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE and PAF.primary_flag = 'Y'
	INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	AND (PP.NAME LIKE '%MGR%' OR PP.NAME LIKE '%SPI%')
	AND PPF.PERSON_ID=$emp_id";
	$resultMgr = oci_parse($con, $queryMgr);
	oci_execute($resultMgr);
	$row = oci_fetch_row($resultMgr);
	$count = $row[0];
	//echo $queryMgr;exit;
	if($count == 0){
		$querySpv = "SELECT count(-1)
		FROM APPS.PER_PEOPLE_F PPF
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE and PAF.primary_flag = 'Y'
		INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
		WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		AND (PP.NAME LIKE '%SPV%')
		AND PPF.PERSON_ID=$emp_id";
		$resultSpv = oci_parse($con, $querySpv);
		oci_execute($resultSpv);
		$rowSpv = oci_fetch_row($resultSpv);
		$count2 = $rowSpv[0];
		
		if($count2 == 0){
			$query = "SELECT DISTINCT REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1) ||'.'||REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 2) POS
			FROM APPS.PER_PEOPLE_F PPF
			INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE and PAF.primary_flag = 'Y'
			INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
			WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
			AND PPF.PERSON_ID=$emp_id";
			$result = oci_parse($con, $query);
			oci_execute($result);
			while($row = oci_fetch_row($result))
			{
				$jobId=$row[0];
				$querywhere .= "and PP.NAME LIKE '%$jobId%'";
			}
		}else{
			$query = "SELECT DISTINCT REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 1) POS
			FROM APPS.PER_PEOPLE_F PPF
			INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE and PAF.primary_flag = 'Y'
			INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
			WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
			AND PPF.PERSON_ID=$emp_id";
			$result = oci_parse($con, $query);
			oci_execute($result);
			while($row = oci_fetch_row($result))
			{
				$jobId=$row[0];
				$querywhere .= "and PP.NAME LIKE '%$jobId%'";
			}
		}
	}else{
		$query = "SELECT DISTINCT PAF.JOB_ID regex
		FROM APPS.PER_PEOPLE_F PPF
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE and PAF.primary_flag = 'Y'
		INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
		WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		AND PPF.PERSON_ID=$emp_id";
		$result = oci_parse($con, $query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$jobId=$row[0];
			$querywhere .= "AND PAF.JOB_ID=$jobId";
			//$querywhere .= "AND PP.NAME LIKE '%$jobId%'";
		}
	}

}
*/

$query = "SELECT DISTINCT PPF.PERSON_ID AS DATA_VALUE, PPF.FULL_NAME AS DATA_NAME 
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' and PAF.primary_flag = 'Y'
--AND PAF.JOB_ID = $jobId 
AND UPPER(PPF.FULL_NAME) NOT LIKE '%SALAH%' 
AND UPPER(PPF.FULL_NAME) NOT LIKE '%TRIAL%' 
$querywhere 
AND PPF.PERSON_ID NOT IN
(
SELECT  DISTINCT KARYAWAN_ID
FROM    MJ_T_MUTASI
WHERE   STATUS_DOK = 'In process'
$querywhere_hdid
)
ORDER BY PPF.FULL_NAME ASC";

//ECHO $query;EXIT;

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