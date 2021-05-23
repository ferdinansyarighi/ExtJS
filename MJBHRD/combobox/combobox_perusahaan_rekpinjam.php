<?php
include '../main/koneksi.php';

// $record = array();
// $record['DATA_VALUE']='- Pilih -';
// $record['DATA_NAME']='- Pilih -';
// $data[]=$record;

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(NAME) LIKE '%$pjname%' ";
}

$query = "SELECT ORGANIZATION_ID AS DATA_VALUE, NAME AS DATA_NAME
FROM HR_ORGANIZATION_UNITS
WHERE 1=1 $querywhere";

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