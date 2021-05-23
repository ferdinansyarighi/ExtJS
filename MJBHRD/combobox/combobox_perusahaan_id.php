<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(NAME) LIKE '%$pjname%' ";
}

$query = "SELECT ORGANIZATION_ID DATA_VALUE
, NAME DATA_NAME 
FROM APPS.HR_OPERATING_UNITS
WHERE SYSDATE BETWEEN NVL(DATE_FROM, SYSDATE) AND NVL(DATE_TO, SYSDATE)
$querywhere
ORDER BY NAME ";

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