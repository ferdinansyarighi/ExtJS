<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(NAME) LIKE '%$pjname%' ";
}

if (isset($_GET['tipe']))
{	
	$vTipe = $_GET['tipe'];
	$vDept_id = $_GET['dept_id'];
	
	
	// Penambahan tipe "Mutasi dan Promosi" dan "Mutasi dan Demosi" oleh Yuke di 27 Sept 2018.
	
	
	if ( $vTipe == 'Promosi' || $vTipe == 'Demosi' ) {
		$querywhere .= " AND JOB_ID = '$vDept_id' ";
	} else if ( $vTipe == 'Mutasi' || $vTipe == 'Mutasi dan Promosi' || $vTipe == 'Mutasi dan Demosi' ) {
		$querywhere .= " AND JOB_ID <> '$vDept_id' ";
	}
}



$query = "SELECT DISTINCT JOB_ID AS DATA_VALUE
, NAME AS DATA_NAME 
FROM APPS.PER_JOBS
WHERE 1=1 AND UPPER(NAME) NOT LIKE '%OLD%'
$querywhere
ORDER BY NAME";

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