<?php
include '../main/koneksi.php';

$pjname = "";
$querywhere = "";
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere = " AND UPPER(CBA.BANK_ACCOUNT_NUM|| ' - ' ||BB.BANK_NAME|| ' a/n ' ||CBA.BANK_ACCOUNT_NAME) LIKE '%$pjname%' ";
}

$query = "SELECT  
        CBA.BANK_ACCOUNT_ID, CBA.BANK_ACCOUNT_NUM|| ' - ' ||BB.BANK_NAME|| ' a/n ' ||CBA.BANK_ACCOUNT_NAME||' #'||CBA.BANK_ACCOUNT_ID, 
        BAU.ORG_ID
FROM    APPS.CE_BANK_ACCOUNTS CBA,
        APPS.CE_BANK_ACCT_USES_ALL BAU,
        APPS.CEFV_BANK_BRANCHES BB
WHERE   CBA.BANK_ACCOUNT_ID = BAU.BANK_ACCOUNT_ID
AND     CBA.BANK_BRANCH_ID = BB.BANK_BRANCH_ID
AND     ( CBA.END_DATE IS NULL OR CBA.END_DATE > TRUNC (SYSDATE) )
AND     CBA.BANK_ACCOUNT_ID
IN (
53008, 72007, 57007, 56007, 58007, 98008, 53010, 73007, 25002, 57009, 42002, 12001, 45002, 54008,31002,98007
)
$querywhere
ORDER   BY BB.BANK_NAME, CBA.BANK_ACCOUNT_NUM ";

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