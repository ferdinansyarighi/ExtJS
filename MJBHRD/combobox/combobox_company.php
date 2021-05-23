<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND upper(HPU.NAME) LIKE '%$pjname%' ";
}

$query = "SELECT DISTINCT 
HPU.ORGANIZATION_ID 
ORG_ID, HPU.NAME ORG_NAME 
FROM 
APPS.HR_OPERATING_UNITS 
HPU 
WHERE 1=1 $querywhere
ORDER BY HPU.NAME ";
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