<?php
include '../main/koneksi.php';

	$query = "SELECT NAMA_AREA AS DATA_VALUE, NAMA_AREA AS DATA_NAME FROM MJ.MJ_M_AREA WHERE STATUS='A' AND APP_ID=" . APPCODE . " ";
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