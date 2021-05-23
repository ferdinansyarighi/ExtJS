<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(B.FULL_NAME) LIKE '%$pjname%' ";
}

$query = "SELECT  B.PERSON_ID, B.FULL_NAME, EMPLOYEE_NUMBER, HRL.LOCATION_CODE
FROM    APPS.PER_ASSIGNMENTS_F A
, APPS.PER_PEOPLE_F B
, APPS.PER_POSITIONS C
, APPS.HR_LOCATIONS HRL
WHERE   A.PERSON_ID = B.PERSON_ID 
AND     A.POSITION_ID = C.POSITION_ID
AND     A.LOCATION_ID = HRL.LOCATION_ID
AND     (C.NAME LIKE 'PPC.STF_13%' or C.NAME LIKE '%Driver%')
AND     B.EFFECTIVE_END_DATE > SYSDATE
AND     B.CURRENT_EMPLOYEE_FLAG = 'Y'
AND A.PRIMARY_FLAG = 'Y'
AND     A.EFFECTIVE_END_DATE > SYSDATE
$querywhere
ORDER BY B.FULL_NAME";

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