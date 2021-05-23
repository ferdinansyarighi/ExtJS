<?php
include '../main/koneksi.php';

$data = "";
$name = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND DISPNAME + ' - ' + KARYANAME LIKE '%$name%' ";
}
$conn = odbc_connect('bioFinger', 'sa', 'merakjaya1234');
if (!$conn) {
	header('Location: HTML/index.html');
	echo ("Koneksi ke Biofinger Gagal !");
	exit;
} 	
$query = "SELECT KARYACODE AS DATA_VALUE
, DISPNAME + ' - ' + KARYANAME AS DATA_NAME 
FROM MastKarya
WHERE 1=1 $querywhere
ORDER BY DISPNAME";

$result = odbc_exec($conn, $query);
if (!$result) {
	$record = array();
	$record['DATA_VALUE']='Gagal Menarik Data';
	$record['DATA_NAME']='Gagal Menarik Data';
	$data[]=$record;
} 
else {
	while(odbc_fetch_row($result))
	{
		$record = array();
		$record['DATA_VALUE']=odbc_result($result, 1);
		$record['DATA_NAME']=odbc_result($result, 2);
		$data[]=$record;
	}
}

echo json_encode($data);
?>