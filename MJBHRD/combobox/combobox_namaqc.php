<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(PPF.FULL_NAME) LIKE '%$pjname%' ";
}

$query = "SELECT DISTINCT PPF.PERSON_ID, PPF.FULL_NAME, PP.NAME
FROM APPS.PER_PEOPLE_F PPF
    INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID
    INNER JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID = PP.POSITION_ID
WHERE TO_DATE(SYSDATE) BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE
    AND TO_DATE(SYSDATE) BETWEEN PAF.EFFECTIVE_START_DATE AND PAF.EFFECTIVE_END_DATE
    --AND UPPER(PP.NAME) LIKE '%QC%'
$querywhere
ORDER BY PPF.FULL_NAME";

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