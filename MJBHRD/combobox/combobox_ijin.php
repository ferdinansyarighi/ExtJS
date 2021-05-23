<?php
include '../main/koneksi.php';

$name = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND UPPER(JENIS_IJIN) LIKE '%$name%' ";
}

$query = "SELECT DISTINCT JENIS_IJIN AS DATA_VALUE, JENIS_IJIN AS DATA_NAME FROM MJ.MJ_M_IJIN WHERE 1=1 AND STATUS='A' $querywhere ORDER BY JENIS_IJIN ASC";
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