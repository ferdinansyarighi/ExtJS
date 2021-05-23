<?php
include '../main/koneksi.php';

$name = "";
$pemohon = "";
$querywhere = "";

$record = array();
// $record['DATA_VALUE']='- Pilih -';
// $record['DATA_VALUE_ID']='- Pilih -';
// $record['DATA_NAME']='- Pilih -';
// $data[]=$record;

if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere .= " AND UPPER(PPF.FULL_NAME) LIKE '%$name%' ";
}

if (isset($_GET['dari'])) {
	$department = $_GET['dari'];
	if ($department != '' && $department != 'null') {
		$querywhere .= "AND PJ.NAME LIKE '%$department%'";
	}
}

$query = "SELECT DISTINCT --PPF.PERSON_ID ,PPF.FULL_NAME || ' - ' || REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) AS DATA_VALUE
		PPF.PERSON_ID , PPF.FULL_NAME
		FROM APPS.PER_PEOPLE_F PPF, APPS.PER_ASSIGNMENTS_F PAF,APPS.PER_GRADES PG, APPS.PER_JOBS PJ
		WHERE PPF.PERSON_ID = PAF.PERSON_ID
		AND PAF.GRADE_ID = PG.GRADE_ID
		AND PAF.JOB_ID = PJ.JOB_ID
		AND (PG.NAME like 'I %' or PG.NAME like 'II %')
		AND SYSDATE BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE
		AND SYSDATE BETWEEN PAF.EFFECTIVE_START_DATE AND PAF.EFFECTIVE_END_DATE
		AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		$querywhere ";
	//echo $query; 
$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_VALUE']=$row[0];
		$record['DATA_NAME']=$row[1];
		// $record['DATA_VALUE_ID']=$row[2];
		$data[]=$record;
	}

	echo json_encode($data);
?>