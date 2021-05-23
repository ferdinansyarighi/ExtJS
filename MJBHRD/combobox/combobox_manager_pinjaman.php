<?php
include '../main/koneksi.php';

$name = "";
$pemohon = "";
$querywhere = "";

$record = array();
$record['DATA_VALUE']='- Pilih -';
$record['DATA_VALUE_ID']='- Pilih -';
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
	$pemohon = str_replace("'", "''", $_GET['pemohon']);
	$pemohon = strtoupper($pemohon);
	$queryPemohon = "SELECT PJ.JOB_ID 
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' AND PAF.EFFECTIVE_END_DATE > SYSDATE AND UPPER(PPF.FULL_NAME) LIKE '$pemohon'";

	$resultPemohon = oci_parse($con,$queryPemohon);
	oci_execute($resultPemohon);
	$rowPemohon = oci_fetch_row($resultPemohon);
	$jobPemohon = $rowPemohon[0];
	$querywhere .= " AND PAF.JOB_ID=$jobPemohon ";
}

$queryCount = "SELECT COUNT(-1)
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
AND UPPER(PPF.FULL_NAME) LIKE '%$pemohon%'
AND paf.PRIMARY_FLAG = 'Y'
AND PP.NAME LIKE '%MGR%'";
//echo $queryPemohon;
$resultCount = oci_parse($con,$queryCount);
oci_execute($resultCount);
$rowCount = oci_fetch_row($resultCount);
$count    = $rowCount[0];

if($count==0)
{
	$query = "SELECT DISTINCT PPF.FULL_NAME || ' - ' || REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) AS DATA_VALUE
	, PPF.FULL_NAME || ' - ' || REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) AS DATA_NAME, PPF.PERSON_ID AS DATA_VALUE_ID
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
	AND (PP.NAME LIKE '0.%' OR PP.NAME LIKE '%MGR%') $querywhere 
	--AND PPF.PERSON_ID=9879";
	//echo $query;
}
else{
	$query = "SELECT DISTINCT PPF.FULL_NAME || ' - ' || REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) AS DATA_VALUE
	, PPF.FULL_NAME || ' - ' || REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) AS DATA_NAME, PPF.PERSON_ID AS DATA_VALUE_ID
	FROM APPS.PER_PEOPLE_F PPF
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
	INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
	WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
	AND (PP.NAME LIKE '%DIR%') 
	--AND PPF.PERSON_ID=9879";
} 
$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_VALUE']=$row[0];
		$record['DATA_NAME']=$row[1];
		$record['DATA_VALUE_ID']=$row[2];
		$data[]=$record;
	}

	echo json_encode($data);
?>