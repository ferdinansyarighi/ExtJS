<?php
include '../main/koneksi.php';

$name = "";
$querywhere = "";

// $record = array();
// $record['DATA_VALUE']="- Pilih -";
// $record['DATA_NAME']="- Pilih -";
// $data[]=$record;
		
if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND UPPER(LOCATION_CODE) LIKE '%$name%' ";
}

$query = "SELECT DISTINCT LOCATION_ID AS DATA_VALUE, LOCATION_CODE AS DATA_NAME 
FROM APPS.HR_LOCATIONS 
WHERE INACTIVE_DATE IS NULL $querywhere ORDER BY LOCATION_CODE";
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