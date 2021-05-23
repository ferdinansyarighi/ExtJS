<?php
include '../main/koneksi.php';

if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere = " AND PPF.FULL_NAME LIKE '%$name%' ";
}

$query = "SELECT  DISTINCT MTM.KARYAWAN_ID DATA_VALUE, PPF.FULL_NAME DATA_NAME
FROM    APPS.PER_PEOPLE_F PPF, MJ_T_MUTASI MTM
WHERE   PPF.PERSON_ID = MTM.KARYAWAN_ID
$querywhere
ORDER   BY DATA_NAME";

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