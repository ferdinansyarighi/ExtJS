<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(plant_name) LIKE '%$pjname%' ";
}

$query = "select distinct Location_id, plant_name from mj_plant_v
where 1=1
$querywhere
ORDER BY plant_name";
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