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
$data = "";
$tingkat = 0;
$queryfilter = "";
if (isset($_GET['tgl_awal']))
{	
	$tgl_awal = $_GET['tgl_awal'];
	$tgl_akhir = $_GET['tgl_akhir'];
	$plant = $_GET['plant'];
	$nama = $_GET['nama'];
	$queryfilter .= " AND TO_CHAR(TRD.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal'  AND TO_CHAR(TRD.EFFECTIVE_END_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' ";
	if($nama!=''){
		$queryfilter .= " AND TRD.NAMA_DRIVER = '$nama'";
	}
	if($plant!=''){
		//$queryfilter .= " AND HOU.ORGANIZATION_ID = '$plant'";
		$queryfilter .= " AND PAF.LOCATION_ID = (SELECT LOCATION_ID FROM APPS.HR_ORGANIZATION_UNITS WHERE ORGANIZATION_ID = '".$plant."')";
	}
} 
$countid = 1;
$tglSJ = "";
$HariIni = "";
$nominalRitasi = 0;
$totalRitasi = 0;

	$query = "SELECT DISTINCT PPF.FULL_NAME
	, NVL(MSB.SURAT_JALAN_NO, 'STANDBY') SURAT_JALAN_NO
	, TO_CHAR(NVL(MSB.TANGGAL_SJ, TRDD.TGL_STANDBY), 'DD-MON-YYYY') TANGGL_SJ
	, MSB.JAM_BERANGKAT
	, HOU.NAME PLANT
	, MSB.NAMA_CUST
	, MSB.LOKASI_PROYEK
	, MSB.VOLUME_KIRIM
	, NVL(MRS.RETURN_QTY, 0) VOL_RETUR
	, MSB.SOPIR
	, MSB.NOMOR_TRUK
	, REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) PLANT_CODE
	, TRDD.NOMINAL
	, CASE WHEN HOU.ATTRIBUTE2 = 'BP' THEN ROUND((TRDD.NOMINAL * TRDD.VARIABEL), 2)
		ELSE ROUND(TRDD.NOMINAL, 2) END TOTAL
	, TRDD.NOMOR_LHO
	, TRDD.KM_AWAL
	, TRDD.KM_AKHIR
	, TRDD.SOLAR
	, TRDD.VARIABEL
	, TRDD.RITASI_KE
	FROM MJ.MJ_T_RITASI_DRIVER TRD
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRD.NAMA_DRIVER AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
	INNER JOIN MJ.MJ_T_RITASI_DRIVER_DETAIL TRDD ON TRD.ID = TRDD.MJ_T_RITASI_DRIVER_ID
	LEFT JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRDD.SJ_ID AND MSB.ORG_ID = 81
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = HOU.INTERNAL_ADDRESS_LINE
	LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
	WHERE TRD.STATUS = 'Submit'
	$queryfilter
	ORDER BY HOU.NAME, SURAT_JALAN_NO";
	//echo $query; exit;
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_NAMA']=$row[0];
		$record['DATA_SJ']=$row[1];
		$record['DATA_TGL']=$row[2];
		$record['DATA_JAM']=$row[3];
		$record['DATA_PLANT']=$row[4];
		$record['DATA_CUST']=$row[5];
		$record['DATA_PROYEK']=$row[6];
		$record['DATA_VOL']=$row[7];
		$record['DATA_VOL_RETUR']=$row[8];
		$record['DATA_SOPIR']=$row[9];
		$record['DATA_TRUK']=$row[10];
		$record['DATA_PLANT_CODE']=$row[11];
		$record['DATA_NOMINAL']=$row[12];
		$record['DATA_TOTAL']=$row[13];
		$record['DATA_LHO']=$row[14];
		$record['DATA_KM_AWAL']=$row[15];
		$record['DATA_KM_AKHIR']=$row[16];
		$record['DATA_SOLAR']=$row[17];
		$record['DATA_VARIABEL']=$row[18];
		$record['DATA_RITASI_KE']=$row[19];
		$data[]=$record;
		$countid++;
	}		

echo json_encode($data); 
?>