<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(REGEXP_SUBSTR(NAME, '[^.]+', 1, 3)) LIKE '%$pjname%' ";
}

$query = "SELECT DISTINCT JOB_ID AS DATA_VALUE
, REGEXP_SUBSTR(NAME, '[^.]+', 1, 3) AS DATA_NAME 
FROM APPS.PER_JOBS
WHERE 1=1 $querywhere
ORDER BY REGEXP_SUBSTR(NAME, '[^.]+', 1, 3)";

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