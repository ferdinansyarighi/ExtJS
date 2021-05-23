<?php
include '../main/koneksi.php';

$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(NAME) LIKE '%$pjname%' ";
}

if (isset($_GET['shift']))
{	
	$shift_id = $_GET['shift'];
	$querywhere .= " AND ID = $shift_id ";
}

$query = "SELECT ID, NAME FROM
APPS.HXT_WEEKLY_WORK_SCHEDULES_FMV
WHERE SYSDATE BETWEEN DATE_FROM AND NVL(DATE_TO, SYSDATE)
$querywhere
ORDER BY NAME";
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