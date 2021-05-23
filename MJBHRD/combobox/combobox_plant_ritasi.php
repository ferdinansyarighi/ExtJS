<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(HOU.NAME) LIKE '%$pjname%' ";
}
if (isset($_GET['tipe']))
{	
	$tipe = $_GET['tipe'];
	
	$queryTipe = "SELECT TIPE_PLANT 
	FROM MJ.MJ_M_NOMINAL_RITASI_DRIVER 
	WHERE ID = $tipe";
	$resultTipe = oci_parse($con, $queryTipe);
	oci_execute($resultTipe);
	while($rowTipe = oci_fetch_row($resultTipe))
	{
		$tipeName = $rowTipe[0];
	}

	$querywhere .= " AND UPPER(HOU.ATTRIBUTE2) LIKE '%$tipeName%' ";
}

$query = "SELECT  DISTINCT HOU.ORGANIZATION_ID LOC_ID, HOU.NAME LOC_NAME
FROM    APPS.HR_ORGANIZATION_UNITS HOU
WHERE   HOU.ORGANIZATION_ID IN 
    (
        SELECT     ORGANIZATION_ID
        FROM     APPS.HR_ORGANIZATION_INFORMATION
        WHERE    ORG_INFORMATION3=81
            AND ORG_INFORMATION_CONTEXT='Accounting Information'
    )
AND HOU.ORGANIZATION_ID <> 81
$querywhere
ORDER BY HOU.NAME";

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