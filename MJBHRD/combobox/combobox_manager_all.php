<?php
include '../main/koneksi.php';

$name = "";
$pemohon = "";
$querywhere = "";

$record = array();
$record['DATA_VALUE']='- Pilih -';
$record['DATA_VALUE_ID']='- Pilih -';
$record['DATA_NAME']='- Pilih -';
$data[]=$record;

if (isset($_GET['query']))
{	
	$name = $_GET['query'];
	$name = strtoupper($name);
	$querywhere .= " AND UPPER(PPF.FULL_NAME) LIKE '%$name%' ";
}
$query = "SELECT DISTINCT --PPF.PERSON_ID ,PPF.FULL_NAME || ' - ' || REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) AS DATA_VALUE
		PPF.PERSON_ID , PPF.FULL_NAME
		FROM APPS.PER_PEOPLE_F PPF
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
		INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
		WHERE 1=1 --PPF.DATE_EMPLOYEE_DATA_VERIFIED is null
		AND PAF.PRIMARY_FLAG = 'Y'		
		AND (PP.NAME LIKE '0.%' OR PP.NAME LIKE '%MGR%' )  $querywhere 
--AND PPF.PERSON_ID=9879";
	//echo $query; 
$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_VALUE']=$row[0];
		$record['DATA_NAME']=$row[1];
		// $record['DATA_VALUE_ID']=$row[2];
		$data[]=$record;
	}

	echo json_encode($data);
?>