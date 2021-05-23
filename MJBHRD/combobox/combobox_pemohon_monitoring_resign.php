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

$querycount = "SELECT DISTINCT PPF.PERSON_ID, PPF.FULL_NAME, PJ.JOB_ID, PJ.NAME, PPF.EMAIL_ADDRESS 
FROM PER_PEOPLE_F PPF, PER_JOBS PJ, PER_ASSIGNMENTS_F PAF, PER_GRADES PG
WHERE PPF.PERSON_ID = PAF.PERSON_ID AND PAF.JOB_ID = PJ.JOB_ID AND PJ.JOB_ID = 26066 
AND PAF.GRADE_ID = PG.GRADE_ID AND PG.NAME LIKE '%Manager%' AND PPF.FULL_NAME LIKE '%$emp_name%'";
$resultcount = oci_parse($con,$querycount);
oci_execute($resultcount);
$rowcount = oci_fetch_row($resultcount);
$jumgen = $rowcount[0];

$querycountMan = "SELECT COUNT(-1) FROM PER_PEOPLE_F PPF, PER_POSITIONS PP, PER_ASSIGNMENTS_F PAF 
WHERE PPF.FULL_NAME LIKE '%$emp_name%' AND PAF.PERSON_ID = PPF.PERSON_ID AND PAF.POSITION_ID = PP.POSITION_ID AND PP.NAME LIKE '%MGR%'";
$resultcountman = oci_parse($con,$querycountMan);
oci_execute($resultcountman);
$rowcountman = oci_fetch_row($resultcountman);
$jumman = $rowcountman[0];

$queryjobid = "SELECT PAF.JOB_ID FROM PER_ASSIGNMENTS_F PAF WHERE PAF.PERSON_ID = $emp_id";
$resultjobid = oci_parse($con,$queryjobid);
oci_execute($resultjobid);
$rowjobid = oci_fetch_row($resultjobid);
$jobid = $rowjobid[0];

if($jumgen >= 1)
{
	$query = "SELECT DISTINCT PPF.FULL_NAME AS DATA_VALUE, PPF.FULL_NAME AS DATA_NAME 
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
	$querywhere ORDER BY PPF.FULL_NAME ASC";
}
else if ($jumman >=1)
{
	$query = "SELECT PPF.PERSON_ID AS DATA_VALUE, PPF.FULL_NAME AS DATA_NAME 
	FROM PER_JOBS PJ, PER_PEOPLE_F PPF, PER_ASSIGNMENTS_F PAF , PER_GRADES PG
	WHERE PPF.PERSON_ID = PAF.PERSON_ID AND PAF.JOB_ID = PJ.JOB_ID AND PAF.JOB_ID = $jobid AND PAF.GRADE_ID = PG.GRADE_ID AND PG.NAME NOT LIKE '%Manager%'
	AND PPF.FULL_NAME NOT LIKE '%TRIAL%'
	AND PPF.FULL_NAME NOT LIKE '%SALAH%' $querywhere
	UNION
	SELECT PPF.PERSON_ID AS DATA_VALUE, PPF.FULL_NAME AS DATA_NAME
	FROM PER_PEOPLE_F PPF
	WHERE PPF.FULL_NAME LIKE '%$emp_name%'
	AND PPF.FULL_NAME NOT LIKE '%TRIAL%'
	AND PPF.FULL_NAME NOT LIKE '%SALAH%' $querywhere";
}
else {
	$query = "SELECT DISTINCT PPF.FULL_NAME AS DATA_VALUE, PPF.FULL_NAME AS DATA_NAME 
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' AND PPF.PERSON_ID = $emp_id
	$querywhere ORDER BY PPF.FULL_NAME ASC";
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