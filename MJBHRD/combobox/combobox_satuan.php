<?php
include '../main/koneksi.php';

$query = "SELECT UOM_CODE DATA_VALUE, UOM_CODE DATA_NAME FROM APPS.MTL_UNITS_OF_MEASURE_TL ORDER BY UOM_CODE";
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