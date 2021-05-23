<?php
include '../main/koneksi.php';

if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND UPPER( TIPE ) LIKE '%$name%' ";
}

$query = "
SELECT  DISTINCT TIPE DATA_VALUE, TIPE DATA_NAME
FROM    MJ_T_MUTASI
WHERE   TIPE IS NOT NULL
$querywhere
ORDER   BY TIPE";

$result = oci_parse( $con, $query );
oci_execute( $result );
while( $row = oci_fetch_row( $result ) )
{
	$record = array();
	$record['DATA_VALUE']=$row[0];
	$record['DATA_NAME']=$row[1];
	$data[]=$record;
}

echo json_encode($data);
?>