<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(LOCATION_CODE) LIKE '%$pjname%' ";
}

$query = "
SELECT	DISTINCT LOCATION_ID AS DATA_VALUE, LOCATION_CODE AS DATA_NAME 
FROM 	APPS.HR_LOCATIONS
WHERE   UPPER( LOCATION_CODE ) NOT LIKE '%SALAH%'
$querywhere
ORDER BY LOCATION_CODE
";

/*
WHERE 	1 = 1 
AND		LOCATION_ID IN 
		(	SELECT	DISTINCT LOCATION_ID 
			FROM 	APPS.PER_ASSIGNMENTS_F 
			WHERE 	EFFECTIVE_END_DATE > SYSDATE
		) 
*/


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