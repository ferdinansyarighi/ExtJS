<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
$queryUnion = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(HOU.ATTRIBUTE2) LIKE '%$pjname%' ";
}

$query = "SELECT DISTINCT HOU.ATTRIBUTE2 
FROM APPS.HR_ORGANIZATION_UNITS HOU 
WHERE HOU.ATTRIBUTE2 IS NOT NULL
$querywhere
ORDER BY HOU.ATTRIBUTE2";
// echo $query;
$result = oci_parse($con, $query);
oci_execute($result);
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['DATA_VALUE']=$row[0];
	$record['DATA_NAME']=$row[0];
	$data[]=$record;
}

echo json_encode($data);
?>