<?php
include '../main/koneksi.php';

// $conn = odbc_connect('bioFinger', 'absen', 'absen123'); //Server 38
$conn = odbc_connect('bioFinger', 'sa', 'merakjaya1234'); //Server 70
if (!$conn) {
	echo "Connection MSSQL Black failed.";
	
	exit;
}

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND DEPARTNAME LIKE '%$pjname%' ";
}

$query = "SELECT DISTINCT DEPARTCODE AS DATA_VALUE
, DEPARTNAME AS DATA_NAME 
FROM MastDepart
WHERE 1=1 $querywhere
ORDER BY DEPARTNAME";

$result = odbc_exec($conn, $query);
while(odbc_fetch_row($result))
{
	$record = array();
	$record['DATA_VALUE']=odbc_result($result, 1);
	$record['DATA_NAME']=odbc_result($result, 2);
	$data[]=$record;
}

echo json_encode($data);
?>