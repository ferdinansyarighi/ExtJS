<?php
include '../main/koneksi.php';

$name = "";
$pemohon = "";
$querywhere = "";

$record = array();
$record['DATA_VALUE']='0';
$record['DATA_NAME']='- Pilih -';
$data[]=$record;

if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere .= " AND UPPER(PPF.FULL_NAME || ' - ' || REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3)) LIKE '%$name%' ";
}


$query = "SELECT DISTINCT PPF.PERSON_ID AS DATA_VALUE
, PPF.FULL_NAME || ' - ' || REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) AS DATA_NAME
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
AND PAF.PRIMARY_FLAG = 'Y'
AND PJ.name like '%FIN%' AND PG.name like '%Manager%' 
$querywhere
ORDER BY DATA_NAME ";
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