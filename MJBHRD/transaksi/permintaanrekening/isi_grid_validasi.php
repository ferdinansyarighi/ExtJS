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
  
$tffilter = "";
$tglfrom = "";
$tglto = "";
$cb1 = "";
$cb2 = "";

$query = "SELECT DISTINCT PPR.ID AS HD_ID
, PPR.NO_REQUEST AS DATA_NOREQ
, PPF2.FULL_NAME AS DATA_PEMBUAT
, PPF.FULL_NAME AS DATA_NAMAKARYAWAN
, PJ.NAME AS DATA_DEPT
, PP.NAME AS DATA_POS
, PL.LOCATION_CODE AS DATA_LOKASI
, PPR.AKTIF AS DATA_STATUS
, PPR.ALASAN AS ALASAN
, PPR.STATUS_REQUEST AS DATA_STATUSREQUEST
FROM MJ.MJ_PERMINTAAN_REKENING PPR
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPR.EMP_ID = PPF.PERSON_ID
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID and PAF.PRIMARY_FLAG = 'Y' and PAF.effective_end_date > sysdate
INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
INNER JOIN APPS.HR_LOCATIONS PL ON PL.LOCATION_ID=PAF.LOCATION_ID
INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID  
INNER JOIN MJ.MJ_M_USER MMU ON PPR.CREATED_BY = MMU.ID
INNER JOIN APPS.PER_PEOPLE_F PPF2 ON MMU.EMP_ID = PPF2.PERSON_ID
WHERE PPR.STATUS_REQUEST = '0'
	AND PPR.AKTIF != 'N'
	AND SYSDATE BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE
	AND SYSDATE BETWEEN PPF2.EFFECTIVE_START_DATE AND PPF2.EFFECTIVE_END_DATE";
//AND STATUS_DOK <> 'Approved' $queryfilter";
//echo $query;
$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NOREQ']=$row[1];
	$record['DATA_PEMBUAT']=$row[2];
	$record['DATA_NAMAKARYAWAN']=$row[3];
	$record['DATA_DEPT']=$row[4];
	$record['DATA_POS']=$row[5];
	$record['DATA_LOKASI']=$row[6];
	$record['DATA_STATUS']=$row[7];
	$record['DATA_ALASAN']=$row[8];
	$record['DATA_STATUSREQUEST']=$row[9];
	$data[]=$record;
	$count++;
}
if ($count==0)
{
	$record = array();
	$data[]="";
}
 $totalrow = 0;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' => $data
				);
	echo json_encode($result);
?>