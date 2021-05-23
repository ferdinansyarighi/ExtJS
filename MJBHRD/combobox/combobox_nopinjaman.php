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
	$querywhere = " AND UPPER(NOMOR_PINJAMAN) LIKE '%$name%' ";
}

if (isset($_GET['person_id']))
{	
	$person_id = $_GET['person_id'];
	
	$querywhere .= " AND PERSON_ID = $person_id ";
}

$query = "SELECT DISTINCT ID AS DATA_VALUE, NOMOR_PINJAMAN AS DATA_NAME FROM MJ.MJ_T_PINJAMAN WHERE 1=1 AND STATUS = 1 $querywhere ORDER BY NOMOR_PINJAMAN ASC";
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