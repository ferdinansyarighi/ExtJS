<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
$queryUnion = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(LOCATION_GROUP) LIKE '%$pjname%' ";
}
if (isset($_GET['hd_id']))
{	
	$idgroup = $_GET['hd_id'];
	$queryUnion = " UNION
	SELECT MNR.ID
	, MNR.LOCATION_GROUP || '  ' || TO_CHAR(MNR.EFFECTIVE_START_DATE, 'DD-MM-YYYY') || ' s/d ' || TO_CHAR(NVL(MNR.EFFECTIVE_END_DATE, TO_DATE('4712-12-31', 'YYYY-MM-DD')), 'DD-MM-YYYY') LOCATION_GROUP
	FROM MJ.MJ_M_NOMINAL_RITASI MNR
	WHERE MNR.ID = $idgroup ";
}

$query = "SELECT ID
, LOCATION_GROUP
FROM (
	SELECT MNR.ID
	, MNR.LOCATION_GROUP || '  ' || TO_CHAR(MNR.EFFECTIVE_START_DATE, 'DD-MM-YYYY') || ' s/d ' || TO_CHAR(NVL(MNR.EFFECTIVE_END_DATE, TO_DATE('4712-12-31', 'YYYY-MM-DD')), 'DD-MM-YYYY') LOCATION_GROUP
	FROM MJ.MJ_M_NOMINAL_RITASI MNR
	WHERE NVL(MNR.EFFECTIVE_END_DATE, TO_DATE('4712-12-31', 'YYYY-MM-DD')) > SYSDATE
	$queryUnion
)
WHERE 1=1 
$querywhere
ORDER BY LOCATION_GROUP";
// echo $query;
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