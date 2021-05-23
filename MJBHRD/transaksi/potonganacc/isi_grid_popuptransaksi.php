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
	$person_id = $_GET['person_id'];
	if ($cb1=='true'){
		$queryfilter .=" AND UPPER(NOMOR_PINJAMAN) LIKE '%$tffilter%' ";
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
	if ($person_id!=''){
		$queryfilter .=" AND PERSON_ID = $person_id ";
	}
} 
// $query = "SELECT COUNT(-1) 
// FROM MJ.MJ_SYS_USER_RULE MSUR 
// INNER JOIN MJ.MJ_SYS_RULE MSR ON MSR.ID_RULE=MSUR.ID_RULE 
// WHERE MSUR.ID_USER=$user_id 
// AND MSUR.AKTIF='Y'
// AND MSR.APP_ID= " . APPCODE . " 
// AND MSR.AKTIF='Y' 
// AND NAMA_RULE='Administrator'";
// $result = oci_parse($con, $query);
// oci_execute($result);
// $row = oci_fetch_row($result);
// $countAdmin = $row[0];
// //echo $countAdmin;
// if ($countAdmin == 0){
	// $queryfilter .="   AND CREATED_BY='$emp_name' ";
// } 
$query = "
SELECT DISTINCT ID
		, NOMOR_PINJAMAN
		, PERSON_ID
		, TO_CHAR(TANGGAL_PINJAMAN, 'YYYY-MM-DD') TGL_PINJAMAN
		, NOMINAL * JUMLAH_CICILAN NOMINAL
		, JUMLAH_CICILAN
		, TUJUAN_PINJAMAN
		, TO_CHAR(CREATED_DATE, 'YYYY-MM-DD') TGL_PEMBUATAN
		, STATUS
		, TIPE
		, MANAGER
		, START_POTONGAN_BULAN
		, START_POTONGAN_TAHUN
		, DECODE( START_POTONGAN_BULAN, 1, 'Januari', 2, 'Februari', 3, 'Maret', 4, 'April', 5, 'Mei', 6, 'Juni',
										7, 'Juli', 8, 'Agustus', 9, 'September', 10, 'Oktober', 11, 'November', 12, 'Desember' ) 
			||  ' ' || START_POTONGAN_TAHUN AS START_POTONGAN
		, MTP.TIPE_PENCAIRAN, MTP.NOMOR_REKENING
FROM MJ_T_PINJAMAN MTP
WHERE 1 = 1
AND JENIS_PINJAMAN = 'PINJAMAN'
$queryfilter
";

// AND MTP.TINGKAT = 0
// AND MTP.STATUS_DOKUMEN <> 'Approved'

//echo $query;

$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$statusRecord = $row[8];
	if($statusRecord == 1){
		$statusRecord = "AKTIF";
	} else {
		$statusRecord = "NON AKTIF";
	}
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_PINJAMAN']=$row[1];
	$record['DATA_PERSON']=$row[2];
	$record['DATA_TGL']=$row[3];
	$record['DATA_NOMINAL']=$row[4];
	$record['DATA_JML_C']=$row[5];
	$record['DATA_TUJUAN']=$row[6];
	$record['DATA_TGL_BUAT']=$row[7];
	$record['DATA_STATUS']=$statusRecord;
	$record['DATA_TIPE']=$row[9];
	$record['DATA_MGR']=$row[10];
	$record['START_POTONGAN_BULAN']=$row[11];
	$record['START_POTONGAN_TAHUN']=$row[12];
	$record['START_POTONGAN']=$row[13];
	
	$record['DATA_TIPE_PENCAIRAN']=$row[14];
	$record['DATA_NOMOR_REKENING']=$row[15];
	
	$data[]=$record;
	$count++;
}
if ($count==0)
{;
	$data="";
}
 $totalrow = 0;

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' => $data
				);
	echo json_encode($result);
?>