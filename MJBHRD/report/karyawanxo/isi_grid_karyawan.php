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
$periode1="";
$periode2="";
$queryfilter = "";
if (isset($_GET['periode']) || isset($_GET['nama']))
{	
	$periode = $_GET['periode'];
	$periode1 = substr($periode, 0, 10);
	$periode2 = substr($periode, 15, 10);
	$nama = $_GET['nama'];
	if($nama!=''){
		$queryfilter .= " AND PPF.FULL_NAME='$nama'";
	}
} 

$query = "SELECT DISTINCT PPF.FULL_NAME AS NAMA_KARYAWAN
, TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') AS TANGGAL_ABSEN
, MTT.JAM_MASUK
, MTT.JAM_KELUAR
, MME.ELEMENT_NAME AS KETERANGAN
, MTS.KATEGORI AS SIK_SPL 
, MTS.IJIN_KHUSUS
, CASE WHEN NVL(MTS.ID, 0)<>0 AND MTS.STATUS_DOK='Approved' THEN (SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=1 AND MTA.TRANSAKSI_KODE='SIK'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=1 AND TRANSAKSI_KODE='SIK')) 
WHEN NVL(MTSPD.ID, 0)<>0 AND MTSPD.STATUS_DOK='Approved' THEN (SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=1 AND MTA.TRANSAKSI_KODE='SPL'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTSPD.ID AND APP_ID=1 AND TRANSAKSI_KODE='SPL')) ELSE '' END TGL_APPROVED
FROM MJ_T_TIMECARD MTT
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
LEFT JOIN MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO
LEFT JOIN MJ_T_SPL_DETAIL MTSPD ON MTSPD.PERSON_ID=PPF.PERSON_ID
LEFT JOIN MJ_T_SPL MTSP ON MTSP.ID=MTSPD.MJ_T_SPL_ID AND MTSP.TANGGAL_SPL=MTT.TANGGAL
WHERE  TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') >= '$periode1'
AND  TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') <= '$periode2'
$queryfilter
ORDER BY PPF.FULL_NAME, TANGGAL_ABSEN";
//echo $query;exit;
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