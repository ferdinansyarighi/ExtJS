<?php
include '../main/koneksi.php';

if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND UPPER( STATUS_DOK ) LIKE '%$name%' ";
}

$query = "
SELECT  DISTINCT STATUS_DOK DATA_VALUE, STATUS_DOK DATA_NAME
FROM    MJ_T_MUTASI
WHERE   STATUS_DOK IS NOT NULL
$querywhere
ORDER   BY STATUS_DOK";

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