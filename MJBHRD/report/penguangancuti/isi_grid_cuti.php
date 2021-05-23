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
		$queryfilter .= " AND PPF.FULL_NAME='$nama'";
	}
	if($dept!=''){
		$queryfilter .= " AND REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3)='$dept'";
	}
} 

$query = "SELECT MMUC.ID
, PPF.FULL_NAME
, TL.DESCRIPTION AS DATA_PERUSAHAAN
, REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3) AS DATA_DEPARTMENT
, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS DATA_JABATAN
, CASE WHEN MMC.CUTI_TAHUNAN < 0 THEN 0 ELSE MMC.CUTI_TAHUNAN END AS DATA_TAHUNAN
, MMUC.UANG_TRP
, MMUC.UANG_CUTI
FROM MJHR.MJ_M_UANG_CUTI MMUC
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMUC.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_JOBS J ON MMUC.JOB_ID=J.JOB_ID
LEFT JOIN APPS.FND_FLEX_VALUES_TL TL ON REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 1)=TL.FLEX_VALUE_MEANING
LEFT JOIN APPS.PER_POSITIONS POS ON MMUC.POSITION_ID=POS.POSITION_ID
INNER JOIN MJ.MJ_M_CUTI MMC ON MMC.PERSON_ID=MMUC.PERSON_ID
 $queryfilter";
//echo $query;
$result = oci_parse($conHR,$query);
oci_execute($result);
$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NAMA']=$row[1];
	$record['DATA_PERUSAHAAN']=$row[2];
	$record['DATA_DEPARTMENT']=$row[3];
	$record['DATA_JABATAN']=$row[4];
	$record['DATA_TAHUNAN']=$row[5];
	$record['DATA_TRP']=number_format($row[6], 2, ',', '.');
	$record['DATA_UANGCUTI']=number_format($row[7], 2, ',', '.');
	$data[]=$record;
	$count++;
}

if ($count==0)
{
	$data="";
}
 echo json_encode($data);
?>