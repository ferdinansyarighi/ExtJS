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

$query = "SELECT PPF.PERSON_ID, PPF.FULL_NAME
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.GRADE_ID=1061 AND PPF.FULL_NAME NOT LIKE '%Tes%'
$querywhere
ORDER BY PPF.FULL_NAME";
//echo $query;exit;
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