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
$data = "";
$noDok = "";
$tingkat = 0;
$queryfilter = "";
if (isset($_GET['tgl_awal']))
{	
	$tgl_awal = $_GET['tgl_awal'];
	$tgl_akhir = $_GET['tgl_akhir'];
	$plant = $_GET['plant'];
	$nama = $_GET['nama'];
	$queryfilter .= " AND TO_CHAR(TRQ.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal'  AND TO_CHAR(TRQ.EFFECTIVE_END_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' ";
	if($nama!=''){
		$queryfilter .= " AND TRQ.NAMA_QC = '$nama'";
	}
	if($plant!=''){
		$queryfilter .= " AND PAF.LOCATION_ID = (SELECT LOCATION_ID FROM APPS.HR_ORGANIZATION_UNITS WHERE ORGANIZATION_ID = '$plant')";
	}
} 
$countid = 1;
$tglSJ = "";
$HariIni = "";
$nominalRitasi = 0;
$totalRitasi = 0;

	$query = "SELECT DISTINCT PPF.FULL_NAME
    , MSB.SURAT_JALAN_NO
    , TO_CHAR(MSB.TANGGAL_SJ, 'DD-MON-YYYY') TANGGL_SJ
    , MSB.JAM_BERANGKAT
    , HOU.NAME PLANT
    , MSB.NAMA_CUST
    , MSB.LOKASI_PROYEK
    , MSB.VOLUME_KIRIM
    , NVL(MRS.RETURN_QTY, 0) VOL_RETUR
    , MSB.SOPIR
    , MSB.NOMOR_TRUK
    , REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) PLANT_CODE
    , TRQD.NOMINAL
    , (MSB.VOLUME_KIRIM - NVL(MRS.RETURN_QTY, 0)) * TRQD.NOMINAL TOTAL
    , TRQD.NOMOR_LHO
	FROM MJ.MJ_T_RITASI_QC TRQ
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRQ.NAMA_QC AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN MJ.MJ_T_RITASI_QC_DETAIL TRQD ON TRQ.ID = TRQD.MJ_T_RITASI_QC_ID
	INNER JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRQD.SJ_ID
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = HOU.INTERNAL_ADDRESS_LINE
	LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
	WHERE MSB.ORG_ID = 81
	AND TRQ.STATUS = 'Submit'
	$queryfilter
	ORDER BY PPF.FULL_NAME, HOU.NAME, MSB.SURAT_JALAN_NO";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_NAMA']=$row[0];
		$record['DATA_SJ']=$row[1];
		$record['DATA_TGL']=$row[2];
		// $tglSJ=$row[2];
		
		// $queryHariIni = "SELECT TO_CHAR(TO_DATE('$tglSJ', 'DD-MON-YYYY'), 'DAY') FROM DUAL";
		// $resultHariIni = oci_parse($con,$queryHariIni);
		// oci_execute($resultHariIni);
		// $rowHariIni = oci_fetch_row($resultHariIni);
		// $HariIni = $rowHariIni[0]; 
		// $HariIni = trim($HariIni);

		// $queryHariLibur = "SELECT COUNT(-1)
		// FROM APPS.HXT_HOLIDAY_DAYS A
		// , APPS.HXT_HOLIDAY_CALENDARS B
		// WHERE A.HCL_ID=B.ID 
		// AND B.EFFECTIVE_END_DATE>SYSDATE 
		// AND TO_CHAR(A.HOLIDAY_DATE, 'DD-MON-YYYY')='$tglSJ'";
		// $resultHariLibur = oci_parse($con,$queryHariLibur);
		// oci_execute($resultHariLibur);
		// $rowHariLibur = oci_fetch_row($resultHariLibur);
		// $HariLibur = $rowHariLibur[0]; 
		
		// if($HariIni == 'SUNDAY' && $HariLibur != 0 && $row[15] == 0){
			// $nominalRitasi = $row[12] * 2;
			// $totalRitasi = $row[13] * 2;
		// } else {
			// $nominalRitasi = $row[12];
			// $totalRitasi = $row[13];
		// }
	
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
		$data[]=$record;
		$countid++;
	}		

echo json_encode($data); 
?>