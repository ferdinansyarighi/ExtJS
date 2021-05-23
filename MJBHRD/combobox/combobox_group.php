<?php
include '../main/koneksi.php';

$pjname = "";
//$companyid ='';
$data = "";
$querywhere = "";
if (isset($_GET['company_id']))
{	
	if($_GET['company_id']!='All'){
		$company_id = $_GET['company_id'];
		$querywhere .= " AND (company =  $company_id OR company IS NULL )";
	}
}
if (isset($_GET['periode']))
{	
	$periode = $_GET['periode'];
	$querywhere .= " AND periode_gaji =  '$periode'";
}
if (isset($_GET['person_id']))
{	
	if($_GET['person_id'] != 'null'){
		$person_id = $_GET['person_id'];
		$query = "select DISTINCT PG.GRADE_ID, PG.NAME GRADE_NAME  from per_assignments_f PAF
			inner join APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
			WHERE SYSDATE BETWEEN PG.DATE_FROM AND NVL(PG.DATE_TO, SYSDATE)
			AND PAF.PERSON_ID = $person_id";
		//echo $query;exit;
		$result = oci_parse($con, $query);
		oci_execute($result);
		$row = oci_fetch_row($result);
		$grade_id = $row[0];
		//if($grade_id != ''){ echo $grade_id.'a';exit;
			$querywhere .= " AND (grade = $grade_id OR GRADE IS NULL )";
		//}
	}
}
if (isset($_GET['query']))
{	
	$pjname = $_GET['query'];
	$pjname = strtoupper($pjname);
	$querywhere .= " AND upper(NAMA_GROUP) LIKE '%$pjname%' ";
}

$query = "select ID, NAMA_GROUP from mj.mj_m_group_element
where status = 'Y'
$querywhere
ORDER BY NAMA_GROUP ";
//echo $query;exit;
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