<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND upper(ORG_NAME) LIKE '%$pjname%' ";
}

$query = "select * from (SELECT DISTINCT 
to_char(HPU.ORGANIZATION_ID) 
ORG_ID, HPU.NAME ORG_NAME 
FROM 
APPS.HR_OPERATING_UNITS 
HPU 
union select 'All' ORG_ID, 'All' ORG_NAME from dual)
WHERE 1=1 $querywhere
ORDER BY ORG_NAME ";
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