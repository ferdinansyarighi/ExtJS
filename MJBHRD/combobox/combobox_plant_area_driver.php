<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
$queryUnion = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(PLANT_AREA) LIKE '%$pjname%' ";
}
if (isset($_GET['hd_id']))
{	
	$idgroup = $_GET['hd_id'];
	$queryUnion = " UNION
	SELECT MNR.ID
	, MNR.PLANT_AREA || '  ' || TO_CHAR(MNR.EFFECTIVE_START_DATE, 'DD-MM-YYYY') || ' s/d ' || TO_CHAR(NVL(MNR.EFFECTIVE_END_DATE, TO_DATE('4712-12-31', 'YYYY-MM-DD')), 'DD-MM-YYYY') PLANT_AREA
	FROM MJ.MJ_M_NOMINAL_RITASI_DRIVER MNR
	WHERE MNR.ID = $idgroup ";
}

$query = "SELECT ID
, PLANT_AREA
FROM (
	SELECT MNR.ID
	, MNR.PLANT_AREA || '  ' || TO_CHAR(MNR.EFFECTIVE_START_DATE, 'DD-MM-YYYY') || ' s/d ' || TO_CHAR(NVL(MNR.EFFECTIVE_END_DATE, TO_DATE('4712-12-31', 'YYYY-MM-DD')), 'DD-MM-YYYY') PLANT_AREA
	FROM MJ.MJ_M_NOMINAL_RITASI_DRIVER MNR
	WHERE NVL(MNR.EFFECTIVE_END_DATE, TO_DATE('4712-12-31', 'YYYY-MM-DD')) > SYSDATE
	$queryUnion
)
WHERE 1=1 
$querywhere
ORDER BY PLANT_AREA";
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