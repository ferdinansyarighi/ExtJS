<?php
include '../main/koneksi.php';

$query = "SELECT DISTINCT Substr(SEGMENT1, 1, Instr(SEGMENT1, '.') - 1) AS DATA_VALUE, Substr(SEGMENT1, 1, Instr(SEGMENT1, '.') - 1) AS DATA_NAME FROM APPS.MTL_CATEGORIES WHERE STRUCTURE_ID = 1 ORDER BY Substr(SEGMENT1, 1, Instr(SEGMENT1, '.') - 1) ASC";
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