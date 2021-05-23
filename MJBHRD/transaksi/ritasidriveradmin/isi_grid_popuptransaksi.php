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
$queryTambahan = "";
$tglSJ = "";
$HariIni = "";
$hdid = 0;
$nominalRitasi = 0;
$totalRitasi = 0;
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
	$tfcari = $_GET['tfcari'];
	$tglfrom = $_GET['tglfrom'];
	$tglto = $_GET['tglto'];
	$cb1 = $_GET['cb1'];
	$cb2 = $_GET['cb2'];
	if ($cb1=='true'){
		if($tffilter != ''){
			$queryfilter .=" AND HOU.ORGANIZATION_ID = $tffilter ";
		}
	}
	// if ($cb2=='true'){
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
	
	$queryfilter .=" AND TO_CHAR(MSB.TANGGAL_SJ, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MSB.TANGGAL_SJ, 'YYYY-MM-DD') <= '$tglto' ";
	
	if($tfcari != ''){
		$queryfilter .=" AND UPPER(MSB.SURAT_JALAN_NO || MSB.NOTES || MSB.SOPIR) LIKE '%$tfcari%' ";
	}
	
	$selisih=round((strtotime($tglto)-strtotime($tglfrom))/(60*60*24));
	// }
	for($i=0; $i<=$selisih; $i++){
		$queryTambahan .= "
		UNION
		SELECT DISTINCT 0 TRANSACTION_ID
		, 'STANDBY' SURAT_JALAN_NO
		, TO_CHAR((TO_DATE('$tglfrom', 'YYYY-MM-DD') + $i), 'DD-MON-YYYY') TANGGL_SJ
		, '00:00' JAM_BERANGKAT
		, '' PLANT
		, '' TIPE
		, '' NAMA_CUST
		, '' LOKASI_PROYEK
		, 0 VOLUME_KIRIM
		, 0 VOL_RETUR
		, '' SOPIR
		, '' NOMOR_TRUK
		, '' PLANT_CODE
		, '' NOTES
		, 0 NOMINAL
		, 0 TOTAL
		, 0 NOMINAL_ID
		, 0 GROUP_ID
		, '-' CEK
		, '' NOMOR_LHO
		, 0 KM_AWAL
		, 0 KM_AKHIR
		, 0 SOLAR
		, 0 VARIABEL
		, '' KETERANGAN
		, 0 id_detail
		FROM DUAL
		";
	}
} 
if (isset($_GET['hdid'])){
	$hdid = $_GET['hdid'];
	if($hdid != ''){
		$queryTambahan .= "
		UNION
		SELECT DISTINCT MSB.TRANSACTION_ID
		, MSB.SURAT_JALAN_NO
		, TO_CHAR(NVL(CASE WHEN REGEXP_SUBSTR( REPLACE(MSB.JAM_BERANGKAT, ';', ':'), '[^:]+', 1, 1 ) < 7 THEN MSB.TANGGAL_SJ-1 ELSE MSB.TANGGAL_SJ END , TRDD.TGL_STANDBY), 'DD-MON-YYYY') TANGGAL_SJ
		, MSB.JAM_BERANGKAT
		, HOU.NAME PLANT
		, HOU.ATTRIBUTE2 TIPE
		, MSB.NAMA_CUST
		, MSB.LOKASI_PROYEK
		, MSB.VOLUME_KIRIM
		, NVL(MRS.RETURN_QTY, 0) VOL_RETUR
		, MSB.SOPIR
		, MSB.NOMOR_TRUK
		, REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) PLANT_CODE
		, MSB.NOTES
		, CASE WHEN HOU.ATTRIBUTE2 = 'BP' THEN DECODE(RITASI.NOMINAL, NULL, TRDD.NOMINAL, RITASI.NOMINAL) 
		ELSE DECODE(JARAK.NOMINAL, NULL, TRDD.NOMINAL, JARAK.NOMINAL) END NOMINAL
		, CASE WHEN HOU.ATTRIBUTE2 = 'BP' THEN TRDD.VARIABEL * DECODE(RITASI.NOMINAL, NULL, TRDD.NOMINAL, RITASI.NOMINAL) 
		ELSE DECODE(JARAK.NOMINAL, NULL, TRDD.NOMINAL, JARAK.NOMINAL) END TOTAL
		, CASE WHEN HOU.ATTRIBUTE2 = 'BP' THEN DECODE(RITASI.DETAIL_ID, NULL, TRDD.NOMINAL_ID, RITASI.DETAIL_ID) 
		ELSE DECODE(JARAK.DETAIL_ID, NULL, TRDD.NOMINAL_ID, JARAK.DETAIL_ID) END NOMINAL_ID
		, DECODE(MPAD.ID, NULL, TRDD.GROUP_ID, MPAD.ID) GROUP_ID
		, TRDD.NOMOR_LHO CEK
		, TRDD.NOMOR_LHO
		, TRDD.KM_AWAL
		, TRDD.KM_AKHIR
		, TRDD.SOLAR
		, TRDD.VARIABEL
		, TRDD.KETERANGAN
		, TRDD.ID
		FROM MJ.MJ_T_RITASI_DRIVER TRD
		INNER JOIN MJ.MJ_T_RITASI_DRIVER_DETAIL TRDD ON TRD.ID = TRDD.MJ_T_RITASI_DRIVER_ID
		INNER JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRDD.SJ_ID
		INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = INTERNAL_ADDRESS_LINE
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
		WHERE MSB.ORG_ID = 81
		AND TRD.ID = $hdid
			--AND TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') BETWEEN '20170818' AND '20170818'
		$queryfilter
		";
	}
}

$query = "SELECT DISTINCT MSB.TRANSACTION_ID
, MSB.SURAT_JALAN_NO
, TO_CHAR(NVL(CASE WHEN REGEXP_SUBSTR( REPLACE(MSB.JAM_BERANGKAT, ';', ':'), '[^:]+', 1, 1 ) < 7 THEN MSB.TANGGAL_SJ-1 ELSE MSB.TANGGAL_SJ END , TRDD.TGL_STANDBY), 'DD-MON-YYYY') TANGGAL_SJ
, MSB.JAM_BERANGKAT
, HOU.NAME PLANT
, HOU.ATTRIBUTE2 TIPE
, MSB.NAMA_CUST
, MSB.LOKASI_PROYEK
, MSB.VOLUME_KIRIM
, NVL(MRS.RETURN_QTY, 0) VOL_RETUR
, MSB.SOPIR
, MSB.NOMOR_TRUK
, REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) PLANT_CODE
, MSB.NOTES
, 0 NOMINAL
, 0 TOTAL
, 0 NOMINAL_ID
, MPAD.ID GROUP_ID
, '-' CEK
, '' NOMOR_LHO
, 0 KM_AWAL
, 0 KM_AKHIR
, 0 SOLAR
, 0 VARIABEL
, '' KETERANGAN
, 0 id_detail
FROM MJ.MJ_SJ_BETON MSB
   INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = INTERNAL_ADDRESS_LINE
   LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
   INNER JOIN MJ.MJ_M_PLANT_AREA_DRIVER MPAD ON CASE WHEN REGEXP_SUBSTR( REPLACE(REPLACE(TRIM(MSB.JAM_BERANGKAT), ';', ':'), '.', ''), '[^:]+', 1, 1 ) < 7 THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END 
   BETWEEN TO_CHAR(MPAD.EFFECTIVE_START_DATE, 'YYYYMMDD') AND NVL(TO_CHAR(MPAD.EFFECTIVE_END_DATE, 'YYYYMMDD'), '47121231')
   AND MPAD.PLANT_ID = HOU.ORGANIZATION_ID
   INNER JOIN MJ.MJ_M_NOMINAL_RITASI_DRIVER MNRD ON MNRD.ID = MPAD.MJ_M_NOMINAL_RITASI_DRIVER_ID 
   AND CASE WHEN REGEXP_SUBSTR( REPLACE(REPLACE(TRIM(MSB.JAM_BERANGKAT), ';', ':'), '.', ''), '[^:]+', 1, 1 ) < 7 THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END 
   BETWEEN TO_CHAR(MNRD.EFFECTIVE_START_DATE, 'YYYYMMDD') AND NVL(TO_CHAR(MNRD.EFFECTIVE_END_DATE, 'YYYYMMDD'), '47121231')
   LEFT JOIN MJ.MJ_T_RITASI_DRIVER_DETAIL TRDD ON TRDD.SJ_ID = MSB.TRANSACTION_ID
WHERE MSB.ORG_ID = 81
    AND TRDD.ID IS NULL
    --AND TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') BETWEEN '20170818' AND '20170818'
    $queryfilter $queryTambahan
ORDER BY PLANT DESC, SURAT_JALAN_NO";
 //echo $query;EXIT;
$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
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
	// echo $HariIni . " dan " . $HariLibur . " dan " .  $row[16];
	// echo $row[16];
	if(($HariIni == 'SUNDAY' || $HariLibur != 0) && $row[18] == '-'){
		$nominalRitasi = $row[14] * 1.5;
		$totalRitasi = $row[15] * 1.5;
		// echo $row[12] . " dan " . $nominalRitasi;
	} else {
		$nominalRitasi = $row[14];
		$totalRitasi = $row[15];
	}
	
	$record['DATA_JAM']=$row[3];
	$record['DATA_PLANT']=$row[4];
	$record['DATA_TIPE']=$row[5];
	$record['DATA_CUST']=$row[6];
	$record['DATA_PROYEK']=$row[7];
	$record['DATA_VOL']=$row[8];
	$record['DATA_VOL_RETUR']=$row[9];
	$record['DATA_SOPIR']=$row[10];
	$record['DATA_TRUK']=$row[11];
	$record['DATA_PLANT_CODE']=$row[12];
	$record['DATA_NOTE']=$row[13];
	$record['DATA_NOMINAL']=$nominalRitasi;
	$record['DATA_TOTAL']=$totalRitasi;
	$record['DATA_NOMINAL_ID']=$row[16];
	$record['DATA_GROUP_ID']=$row[17];
	$record['DATA_LHO']=$row[19];
	$record['DATA_KM_AWAL']=$row[20];
	$record['DATA_KM_AKHIR']=$row[21];
	$record['DATA_SOLAR']=$row[22];
	$record['DATA_VARIABEL']=$row[23];
	$record['DATA_KETERANGAN']=$row[24];
	$record['DATA_ID_DETAIL']=$row[25];
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