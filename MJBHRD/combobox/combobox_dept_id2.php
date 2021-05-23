<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND CASE WHEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) IS NULL THEN UPPER(REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2)) ELSE UPPER(REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3)) END LIKE '%$pjname%' ";
}

$query = "SELECT DISTINCT PJ.JOB_ID DATA_VALUE
, CASE WHEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) IS NULL THEN REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) ELSE REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 3) END DATA_NAME
FROM APPS.PER_JOBS PJ
WHERE 1=1 $querywhere
ORDER BY DATA_NAME";

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