<?php
include '../main/koneksi.php';

// $record = array();
// $record['DATA_VALUE']='- Pilih -';
// $record['DATA_NAME']='- Pilih -';
// $data[]=$record;

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(TL.DESCRIPTION) LIKE '%$pjname%' ";
}

$query = "SELECT TL.DESCRIPTION DATA_VALUE, TL.DESCRIPTION DATA_NAME 
FROM APPS.FND_FLEX_VALUES_VL VL, APPS.FND_FLEX_VALUES_TL TL
WHERE FLEX_VALUE_SET_ID=1016207 $querywhere
AND VL.FLEX_VALUE_ID=TL.FLEX_VALUE_ID";

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