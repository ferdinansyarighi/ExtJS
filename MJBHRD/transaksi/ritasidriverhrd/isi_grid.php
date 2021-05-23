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

$hdid=$_GET['hd_id']; //11302 '10 ± 2 //74518 $_GET['hd_id']

// $filterLHO="";
// $no_lho="";
 // if(isset($_GET['lho'])){
	// $lho=json_decode($_GET['lho']);
	// //$lho=$_GET['lho']; 
	// //echo $lho[0];exit;
	// if($lho[0] != ''){
		// $countID = count($lho);
		// for ($x=0; $x<$countID; $x++){
			// if($no_lho == ""){
				// $no_lho=$lho[$x];
			// }else{
				// $no_lho=$no_lho.", ".$lho[$x];
			// }
		// }
		// $filterLHO = " AND TRDD.NOMOR_LHO IN($no_lho)";
	// }
 // }
 //echo $filterLHO;exit;
$countid = 1;
$tglSJ = "";
$HariIni = "";
$nominalRitasi = 0;
$totalRitasi = 0;

	$query = "SELECT DISTINCT NVL(MSB.TRANSACTION_ID, 0) TRANSACTION_ID
	, NVL(MSB.SURAT_JALAN_NO, 'STANDBY') SURAT_JALAN_NO
	, TO_CHAR(NVL(CASE WHEN REGEXP_SUBSTR( REPLACE(MSB.JAM_BERANGKAT, ';', ':'), '[^:]+', 1, 1 ) < 7 THEN MSB.TANGGAL_SJ-1 ELSE MSB.TANGGAL_SJ END , TRDD.TGL_STANDBY), 'DD-MON-YYYY') TANGGAL_SJ
	, MSB.JAM_BERANGKAT
	, HOU.NAME PLANT
	, MSB.NAMA_CUST
	, MSB.LOKASI_PROYEK
	, MSB.VOLUME_KIRIM
	, NVL(MRS.RETURN_QTY, 0) VOL_RETUR
	, MSB.SOPIR
	, MSB.NOMOR_TRUK
	, MSB.NOTES
	, TRDD.NOMOR_LHO
	, TRDD.KM_AWAL
	, TRDD.KM_AKHIR
	, TRDD.SOLAR
	, ROUND(TRDD.VARIABEL, 2) VARIABEL
	, REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) PLANT_CODE
	, CASE WHEN HOU.ATTRIBUTE2 = 'BP' THEN DECODE(RITASI.NOMINAL, NULL, ROUND(TRDD.NOMINAL, 2), ROUND(RITASI.NOMINAL, 2)) 
	ELSE DECODE(JARAK.NOMINAL, NULL, ROUND(TRDD.NOMINAL, 2), ROUND(JARAK.NOMINAL, 2)) END NOMINAL
	, CASE WHEN HOU.ATTRIBUTE2 = 'BP' THEN ROUND(TRDD.VARIABEL, 2) * DECODE(RITASI.NOMINAL, NULL, ROUND(TRDD.NOMINAL, 2), ROUND(RITASI.NOMINAL, 2)) 
	ELSE DECODE(JARAK.NOMINAL, NULL, ROUND(TRDD.NOMINAL, 2), ROUND(JARAK.NOMINAL, 2)) END TOTAL
	, CASE WHEN HOU.ATTRIBUTE2 = 'BP' THEN DECODE(RITASI.DETAIL_ID, NULL, TRDD.NOMINAL_ID, RITASI.DETAIL_ID) 
	ELSE DECODE(JARAK.DETAIL_ID, NULL, TRDD.NOMINAL_ID, JARAK.DETAIL_ID) END NOMINAL_ID
	, DECODE(MPAD.ID, NULL, TRDD.GROUP_ID, MPAD.ID) GROUP_ID
	, NVL(RITASI.ID, NVL(JARAK.ID, 0)) CEK_ID
	, TRDD.RITASI_KE
	, TRDD.TIPE
	, TRDD.KETERANGAN
	, TRDD.ID
	FROM MJ.MJ_T_RITASI_DRIVER TRD
	INNER JOIN MJ.MJ_T_RITASI_DRIVER_DETAIL TRDD ON TRD.ID = TRDD.MJ_T_RITASI_DRIVER_ID
	LEFT JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRDD.SJ_ID AND MSB.ORG_ID = 81
	LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = INTERNAL_ADDRESS_LINE
	LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
	LEFT JOIN MJ.MJ_M_PLANT_AREA_DRIVER MPAD ON CASE WHEN REGEXP_SUBSTR( REPLACE(MSB.JAM_BERANGKAT, ';', ':'), '[^:]+', 1, 1 ) < 7 THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END 
	BETWEEN TO_CHAR(MPAD.EFFECTIVE_START_DATE, 'YYYYMMDD') AND NVL(TO_CHAR(MPAD.EFFECTIVE_END_DATE, 'YYYYMMDD'), '47121231')
	AND MPAD.PLANT_ID = HOU.ORGANIZATION_ID
	LEFT JOIN 
	(
		SELECT MNRD.ID
		, MNRDD.ID DETAIL_ID
		, MNRDD.RITASI_KE
		, MNRDD.NOMINAL
		, MNRD.EFFECTIVE_START_DATE
		, MNRD.EFFECTIVE_END_DATE
		FROM MJ.MJ_M_NOMINAL_RITASI_DRIVER MNRD 
		INNER JOIN MJ.MJ_M_NOMINAL_RD_DETAIL MNRDD ON MNRDD.MJ_M_NOMINAL_RITASI_DRIVER_ID = MNRD.ID
		WHERE MNRDD.RITASI_KE <> 0
	) RITASI ON RITASI.ID = MPAD.MJ_M_NOMINAL_RITASI_DRIVER_ID AND RITASI.DETAIL_ID = TRDD.NOMINAL_ID
	AND CASE WHEN REGEXP_SUBSTR( REPLACE(MSB.JAM_BERANGKAT, ';', ':'), '[^:]+', 1, 1 ) < 7 THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END 
	BETWEEN TO_CHAR(RITASI.EFFECTIVE_START_DATE, 'YYYYMMDD') AND NVL(TO_CHAR(RITASI.EFFECTIVE_END_DATE, 'YYYYMMDD'), '47121231')
	LEFT JOIN 
	(
		SELECT MNRD.ID
		, MNRDD.ID DETAIL_ID
		, MNRDD.JARAK_FROM
		, MNRDD.JARAK_TO
		, MNRDD.NOMINAL
		, MNRD.EFFECTIVE_START_DATE
		, MNRD.EFFECTIVE_END_DATE
		FROM MJ.MJ_M_NOMINAL_RITASI_DRIVER MNRD 
		INNER JOIN MJ.MJ_M_NOMINAL_RD_DETAIL MNRDD ON MNRDD.MJ_M_NOMINAL_RITASI_DRIVER_ID = MNRD.ID
		WHERE MNRDD.RITASI_KE = 0 AND MNRDD.STATUS = 'Y'
	) JARAK ON JARAK.ID = MPAD.MJ_M_NOMINAL_RITASI_DRIVER_ID AND (TRDD.KM_AKHIR - TRDD.KM_AWAL) BETWEEN JARAK.JARAK_FROM AND JARAK.JARAK_TO
	AND CASE WHEN REGEXP_SUBSTR( REPLACE(MSB.JAM_BERANGKAT, ';', ':'), '[^:]+', 1, 1 ) < 7 THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END 
	BETWEEN TO_CHAR(JARAK.EFFECTIVE_START_DATE, 'YYYYMMDD') AND NVL(TO_CHAR(JARAK.EFFECTIVE_END_DATE, 'YYYYMMDD'), '47121231')
	LEFT JOIN MJ.MJ_T_RITASI_DRIVER_DETAIL TRDD ON TRDD.SJ_ID = MSB.TRANSACTION_ID
	WHERE TRD.ID = $hdid
		--AND TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') BETWEEN '20170818' AND '20170818'
	ORDER BY HOU.NAME, SURAT_JALAN_NO";
	 //echo $query;exit;
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['HD_ID']=$row[0];
		$record['DATA_SJ']=$row[1];
		$record['DATA_TGL']=$row[2];
		$tglSJ=$row[2];
		
		$queryHariIni = "SELECT TO_CHAR(TO_DATE('$tglSJ', 'DD-MON-YYYY'), 'DAY') FROM DUAL";
		$resultHariIni = oci_parse($con,$queryHariIni);
		oci_execute($resultHariIni);
		$rowHariIni = oci_fetch_row($resultHariIni);
		$HariIni = $rowHariIni[0]; 
		$HariIni = trim($HariIni);

		$queryHariLibur = "SELECT COUNT(-1)
		FROM APPS.HXT_HOLIDAY_DAYS A
		, APPS.HXT_HOLIDAY_CALENDARS B
		WHERE A.HCL_ID=B.ID 
		AND B.EFFECTIVE_END_DATE>SYSDATE 
		AND TO_CHAR(A.HOLIDAY_DATE, 'DD-MON-YYYY')='$tglSJ'";
		$resultHariLibur = oci_parse($con,$queryHariLibur);
		oci_execute($resultHariLibur);
		$rowHariLibur = oci_fetch_row($resultHariLibur);
		$HariLibur = $rowHariLibur[0]; 
		
		if(($HariIni == 'SUNDAY' || $HariLibur != 0)){
			$nominalRitasi = $row[18] * 1.5;
			$totalRitasi = $row[19] * 1.5;
		} else {
			$nominalRitasi = $row[18];
			$totalRitasi = $row[19];
		}
	
		$record['DATA_JAM']=$row[3];
		$record['DATA_PLANT']=$row[4];
		$record['DATA_CUST']=$row[5];
		$record['DATA_PROYEK']=$row[6];
		$record['DATA_VOL']=$row[7];
		$record['DATA_VOL_RETUR']=$row[8];
		$record['DATA_SOPIR']=$row[9];
		$record['DATA_TRUK']=$row[10];
		$record['DATA_NOTE']=$row[11];
		$record['DATA_LHO']=$row[12];
		$record['DATA_KM_AWAL']=$row[13];
		$record['DATA_KM_AKHIR']=$row[14];
		$record['DATA_SOLAR']=$row[15];
		$record['DATA_VARIABEL']=$row[16];
		$record['DATA_PLANT_CODE']=$row[17];
		$record['DATA_NOMINAL']=$nominalRitasi;
		$record['DATA_TOTAL']=round($totalRitasi, 2);
		$record['DATA_NOMINAL_ID']=$row[20];
		$record['DATA_GROUP_ID']=$row[21];
		$record['DATA_RITASI_KE']=$row[23];
		$record['DATA_TIPE']=$row[24];
		$record['DATA_KETERANGAN']=$row[25];
		$record['DATA_ID_DETAIL']=$row[26];
		$data[]=$record;
		$countid++;
	}		

echo json_encode($data); 
?>