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

$query = "SELECT ID,NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD/MM/YY'),
				TO_CHAR(TGL_RESIGN, 'DD/MM/YY'),TAHUN_LAMA_KERJA, BULAN_LAMA_KERJA, HARI_LAMA_KERJA, MANAGER, KETERANGAN,
				STATUS, APPROVAL_MANAGER, APPROVAL_MANAGER_HRD, TGL_APP_TERAKHIR, CREATED_BY,CREATED_DATE, TO_CHAR(TGL_PENGAJUAN, 'DD/MM/YY')
			FROM MJ.MJ_T_RESIGN
			WHERE (CREATED_BY = $emp_id OR NAMA_KARYAWAN LIKE '$emp_name') AND STATUS = 1
				AND (VALIDASI = '' OR VALIDASI IS NULL)";

$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_NO_PENGAJUAN']=$row[1];
	$record['DATA_NAMA_KARYAWAN']=$row[2];
	$record['DATA_COMPANY']=$row[3];
	$record['DATA_DEPT']=$row[4];
	$record['DATA_POS']=$row[5];
	$record['DATA_GRADE']=$row[6];
	$record['DATA_LOCATION']=$row[7];
	$record['DATA_TGL_MASUK']=$row[8];
	$record['DATA_TGL_RESIGN']=$row[9];
	$record['DATA_TAHUN_LAMA_KERJA']=$row[10];
	$record['DATA_BULAN_LAMA_KERJA']=$row[11];
	$record['DATA_HARI_LAMA_KERJA']=$row[12];
	$record['DATA_MANAGER']=$row[13];
	$record['DATA_KETERANGAN']=$row[14];
	$record['DATA_STATUS']=$row[15];
	$record['DATA_APPROVAL_MANAGER']=$row[16];
	$record['DATA_APPROVAL_MANAGER_HRD']=$row[17];
	$record['DATA_TGL_APP_TERAKHIR']=$row[18];
	$record['DATA_CREATED_BY']=$row[19];
	$record['DATA_CREATED_DATE']=$row[20];
	$record['DATA_TGL_PENGAJUAN']=$row[21];
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