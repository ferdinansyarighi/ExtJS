<?php
include '../../main/koneksi.php';
session_start();
$user_id = "";
$username = "";
$emp_id = "";
$emp_name = "";
$io_id = "";
$io_name = "";
$loc_id = "";
$loc_name = "";
$org_id = "";
$org_name = "";
 if(isset($_SESSION[APP]['user_id']))
  {
	$user_id = $_SESSION[APP]['user_id'];
	$username = $_SESSION[APP]['username'];
	$emp_id = $_SESSION[APP]['emp_id'];
	$emp_name = $_SESSION[APP]['emp_name'];
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$status = "";
$tglfrom = "";
$tglto = "";
$plant = "";
$noDok = "";
$tingkat = 0;
$queryfilter = "";
if (isset($_GET['perusahaan']) || isset($_GET['nama']) || isset($_GET['dept']))
{	
	$perusahaan = $_GET['perusahaan'];
	$nama = $_GET['nama'];
	$dept = $_GET['dept'];
	if($perusahaan!=''){
		$queryfilter .= " AND TL.DESCRIPTION='$perusahaan'";
	}
	if($nama!=''){
		$queryfilter .= " AND P.FULL_NAME='$nama'";
	}
	if($dept!=''){
		$queryfilter .= " AND REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3)='$dept'";
	}
} 

$query = "SELECT P.PERSON_ID
, P.FULL_NAME AS DATA_NAMA
, A.JOB_ID
, REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 1) AS KODE_PERUSAHAAN
, TL.DESCRIPTION AS DATA_PERUSAHAAN
, REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3) AS DATA_DEPARTMENT
, A.POSITION_ID
, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS DATA_JABATAN
, MMC.CUTI_TAHUNAN AS DATA_TAHUNAN
, MMC.CUTI_SAKIT AS DATA_SAKIT
FROM APPS.PER_PEOPLE_F P
INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F A ON P.PERSON_ID=A.PERSON_ID 
LEFT JOIN APPS.PER_JOBS J ON A.JOB_ID=J.JOB_ID
LEFT JOIN APPS.FND_FLEX_VALUES_TL TL ON REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 1)=TL.FLEX_VALUE_MEANING
LEFT JOIN APPS.PER_POSITIONS POS ON A.POSITION_ID=POS.POSITION_ID
INNER JOIN MJ.MJ_M_CUTI MMC ON MMC.PERSON_ID=P.PERSON_ID
WHERE P.EFFECTIVE_END_DATE > SYSDATE AND A.EFFECTIVE_END_DATE > SYSDATE $queryfilter";
//echo $query;
$result = oci_parse($con,$query);
oci_execute($result);
$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NAMA']=$row[1];
	$record['DATA_PERUSAHAAN']=$row[4];
	$record['DATA_DEPARTMENT']=$row[5];
	$record['DATA_JABATAN']=$row[7];
	$record['DATA_TAHUNAN']=$row[8];
	$record['DATA_SAKIT']=$row[9];
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}
 echo json_encode($data);
?>