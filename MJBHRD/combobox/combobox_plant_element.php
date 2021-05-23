<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
$org_id=0;
$queryorg_id="";
if (isset($_GET['orgid']))
{	
	$org_id = $_GET['orgid'];
	$queryorg_id = "and ORG_INFORMATION3=$org_id";
}
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND upper(HL.LOCATION_CODE) LIKE '%$pjname%' ";
}

$query = "SELECT  DISTINCT HL.LOCATION_ID, HL.LOCATION_CODE 
FROM    APPS.HR_ORGANIZATION_UNITS HOU 
INNER JOIN APPS.HR_LOCATIONS HL ON HOU.LOCATION_ID = HL.LOCATION_ID 
WHERE   HOU.ORGANIZATION_ID IN ( SELECT ORGANIZATION_ID 
       FROM  APPS.HR_ORGANIZATION_INFORMATION 
        WHERE  1=1 $queryorg_id AND ORG_INFORMATION_CONTEXT='Accounting Information' 
    ) 
AND HOU.ORGANIZATION_ID <> $org_id $querywhere
ORDER BY HL.LOCATION_CODE 
";
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