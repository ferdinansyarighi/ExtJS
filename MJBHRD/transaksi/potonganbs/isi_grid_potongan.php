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
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$kategori = "";
$tglfrom = "";
$tglto = "";
$plant = "";
$noDok = "";
$Dept = "";
$data = "";
$tingkat = 0;
$count = 0;
$countSpv = 0;
$countMan = 0;
$queryfilter="";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$tahunSkr=substr($tglskr, 0, 4);
$bulanSkr=substr($tglskr, 5, 2);
$hariSkr=substr($tglskr, 8, 2); 
if (isset($_GET['nama_pem']))
{	
	$nama_pem=$_GET['nama_pem'];
	$bulanSkr = str_replace("0", "", $bulanSkr);
	
	$query = "SELECT DISTINCT MTP.ID
	, MTP.PERSON_ID
	, MTP.JENIS_PINJAMAN
	, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'DD-MM-YYYY') TGL_PINJAMAN
	, (MTP.NOMINAL*MTP.JUMLAH_CICILAN) NOMINAL
	, MTP.JUMLAH_CICILAN
	, MTP.NOMINAL JML_CICILAN_P
	, (MTP.NOMINAL*MTP.JUMLAH_CICILAN) - (SELECT SUM(NOMINAL) FROM MJ_T_PINJAMAN_DETAIL
		WHERE MJ_T_PINJAMAN_ID=MTP.ID
		AND TAHUN <= '$tahunSkr'
		AND BULAN <= '$bulanSkr') OUTSTANDING
	, MTP.JUMLAH_CICILAN - (SELECT COUNT(-1) FROM MJ_T_PINJAMAN_DETAIL
		WHERE MJ_T_PINJAMAN_ID=MTP.ID
		AND TAHUN <= '$tahunSkr'
		AND BULAN <= '$bulanSkr') OUTSTANDING_BLN
	, TO_CHAR(SYSDATE, 'MM') BULAN
	, TO_CHAR(MTP.CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
	FROM MJ_T_PINJAMAN MTP
	WHERE 1=1
	AND MTP.STATUS_DOKUMEN = 'Validasi'
	AND MTP.PERSON_ID=$nama_pem
	ORDER BY MTP.ID";
	//echo $query;
	$result = oci_parse($con,$query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_JENIS']=$row[2];
		$record['DATA_TGL']=$row[3];
		$record['DATA_CICILAN']=$row[4];
		$record['DATA_JML_C']=$row[5];
		$record['DATA_JML_P']=$row[6];
		$record['DATA_OUTSTANDING_ASLI']=$row[7];
		$record['DATA_OUTSTANDING_RP']=$row[7];
		$record['DATA_OUTSTANDING_BLN']=$row[8];
		$record['DATA_CICILAN_BLN']=$row[9];
		$data[]=$record;
		$countSpv++;
	}

	echo json_encode($data); 
} 


?>