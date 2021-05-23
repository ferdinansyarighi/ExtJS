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
if (isset($_GET['tffilter']) || isset($_GET['tglfrom']) || isset($_GET['cb1']))
{	
	$hari="";
	$bulan="";
	$tahun=""; 
	$queryfilter=""; 
	$tglskr=date('Y-m-d'); 
	$tahunbaru=substr($tglskr, 0, 2);
	$tffilter = $_GET['tffilter'];
	$tglfrom = $_GET['tglfrom'];
	$tglto = $_GET['tglto'];
	$cb1 = $_GET['cb1'];
	$cb2 = $_GET['cb2'];
	if ($cb1=='true'){
		$queryfilter .=" AND UPPER(NOMOR_SIK) LIKE '%$tffilter%' ";
	}
	if ($cb2=='true'){
		$hari=substr($tglfrom, 0, 2);
		$bulan=substr($tglfrom, 3, 2);
		$tahun=substr($tglfrom, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tglfrom = $tahun . "-" . $bulan . "-" . $hari;
		$hari=substr($tglto, 0, 2);
		$bulan=substr($tglto, 3, 2);
		$tahun=substr($tglto, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tglto = $tahun . "-" . $bulan . "-" . $hari;
		
		$queryfilter .=" AND TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	}
} 
$query = "SELECT COUNT(-1) 
FROM MJ.MJ_SYS_USER_RULE MSUR 
INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MSUR.ID_RULE 
WHERE MSUR.ID_USER=$user_id 
AND MSUR.AKTIF='Y'
AND MSR.APP_ID= " . APPCODE . " 
AND MSR.AKTIF='Y' 
AND NAMA_RULE='Administrator'";
$result = oci_parse($con, $query);
oci_execute($result);
$row = oci_fetch_row($result);
$countAdmin = $row[0];
//echo $countAdmin;
if ($countAdmin == 0){
	$queryfilter .="   AND CREATED_BY='$emp_name' ";
} 
$query = "SELECT DISTINCT ID AS hd_id
, NOMOR_SIK AS DATA_NOSIK
, PEMBUAT AS DATA_PEMBUAT
, PEMOHON AS DATA_PEMOHON
, DEPARTEMEN AS DATA_DEPT
, PLANT AS DATA_PLANT
, MANAGER AS DATA_MANAGER
, EMAIL_MANAGER AS DATA_EMAILMANAGER
, TO_CHAR(TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
, TO_CHAR(TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
, JAM_FROM AS DATA_JAM_FROM
, JAM_TO AS DATA_JAM_TO
, KETERANGAN AS DATA_KETERANGAN
, ALAMAT AS DATA_ALAMAT
, NO_TELP AS DATA_NOTELP
, NO_HP AS DATA_NOHP
, EMAIL AS DATA_EMAIL
, STATUS AS DATA_STATUS
, TINGKAT AS DATA_TINGKAT
, KATEGORI AS DATA_KATEGORI
, IJIN_KHUSUS AS DATA_IJIN
, SPV AS DATA_SPV
, EMAIL_SPV AS DATA_EMAILSPV 
FROM MJ.MJ_T_SIK WHERE 1=1 
AND STATUS=1
AND STATUS_DOK <> 'Approved' $queryfilter";
//echo $query;
$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$statusRecord = $row[17];
	if($statusRecord == 1){
		$statusRecord = "AKTIF";
	} else {
		$statusRecord = "NON AKTIF";
	}
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NOSIK']=$row[1];
	$record['DATA_PEMBUAT']=$row[2];
	$record['DATA_PEMOHON']=$row[3];
	$record['DATA_DEPT']=$row[4];
	$record['DATA_PLANT']=$row[5];
	$record['DATA_MANAGER']=$row[6];
	$record['DATA_EMAILMANAGER']=$row[7];
	$record['DATA_TGL_FROM']=$row[8];
	$record['DATA_TGL_TO']=$row[9];
	$record['DATA_JAM_FROM']=$row[10];
	$record['DATA_JAM_TO']=$row[11];
	$record['DATA_KETERANGAN']=$row[12];
	$record['DATA_ALAMAT']=$row[13];
	$record['DATA_NOTELP']=$row[14];
	$record['DATA_NOHP']=$row[15];
	$record['DATA_EMAIL']=$row[16];
	$record['DATA_STATUS']=$statusRecord;
	$record['DATA_TINGKAT']=$row[18];
	$record['DATA_KATEGORI']=$row[19];
	$record['DATA_IJIN']=$row[20];
	$record['DATA_SPV']=$row[21];
	$record['DATA_EMAILSPV']=$row[22];
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