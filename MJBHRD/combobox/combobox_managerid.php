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
	$querywhere .= " AND UPPER(PPF.FULL_NAME) LIKE '%$name%' ";
}
if (isset($_GET['pemohon']))
{	
	$pemohon = $_GET['pemohon'];
	$pemohon = strtoupper($pemohon);
	$queryPemohon = "SELECT PJ.JOB_ID 
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' AND UPPER(PPF.FULL_NAME) LIKE '$pemohon'";
	//echo $queryPemohon;
	$resultPemohon = oci_parse($con,$queryPemohon);
	oci_execute($resultPemohon);
	$rowPemohon = oci_fetch_row($resultPemohon);
	$jobPemohon = $rowPemohon[0];
	$querywhere .= " AND PAF.JOB_ID=$jobPemohon ";
}

$query = "SELECT DISTINCT PPF.PERSON_ID AS DATA_VALUE
, PPF.FULL_NAME || ' - ' || REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) AS DATA_NAME
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND (PP.NAME LIKE '0.%' OR PP.NAME LIKE '%SPV%') $querywhere ";
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