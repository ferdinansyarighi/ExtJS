<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND upper(PG.NAME) LIKE '%$pjname%' ";
}

$record = array();
$record['DATA_VALUE']='All';
$record['DATA_NAME']='All';
$data[]=$record;

$query = "SELECT DISTINCT PG.GRADE_ID, PG.NAME GRADE_NAME 
FROM APPS.PER_GRADES PG 
WHERE SYSDATE BETWEEN PG.DATE_FROM AND NVL(PG.DATE_TO, SYSDATE) 
$querywhere
ORDER BY PG.NAME ";
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