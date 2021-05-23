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
		
		$queryfilter .=" AND TO_CHAR(MSB.TANGGAL_SJ, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MSB.TANGGAL_SJ, 'YYYY-MM-DD') <= '$tglto' ";
	}
	if($tfcari != ''){
		$queryfilter .=" AND UPPER(MSB.SURAT_JALAN_NO || MSB.NOTES) LIKE '%$tfcari%' ";
	}
} 
if (isset($_GET['hdid'])){
	$hdid = $_GET['hdid'];
	if($hdid != ''){
		$queryTambahan = "
		UNION
		SELECT DISTINCT MSB.TRANSACTION_ID
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
		, DECODE(MNR.NOMINAL, NULL, TRQD.NOMINAL, MNR.NOMINAL) NOMINAL
		, (MSB.VOLUME_KIRIM - NVL(MRS.RETURN_QTY, 0)) * DECODE(MNR.NOMINAL, NULL, TRQD.NOMINAL, MNR.NOMINAL) TOTAL
		, DECODE(MNR.ID, NULL, TRQD.NOMINAL_ID, MNR.ID) NOMINAL_ID
		, DECODE(MGP.ID, NULL, TRQD.GROUP_ID, MGP.ID) GROUP_ID
		, TRQD.NOMOR_LHO
		, MSB.NOTES
		FROM MJ.MJ_T_RITASI_QC TRQ
		INNER JOIN MJ.MJ_T_RITASI_QC_DETAIL TRQD ON TRQ.ID = TRQD.MJ_T_RITASI_QC_ID
		INNER JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRQD.SJ_ID
		INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = INTERNAL_ADDRESS_LINE
		LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
		LEFT JOIN MJ.MJ_M_GROUP_PLANT MGP ON CASE WHEN REGEXP_SUBSTR( REPLACE(REPLACE(TRIM(MSB.JAM_BERANGKAT), ';', ':'), '.', ''), '[^:]+', 1, 1 ) < 7 THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END 
		BETWEEN TO_CHAR(MGP.EFFECTIVE_START_DATE, 'YYYYMMDD') AND NVL(TO_CHAR(MGP.EFFECTIVE_END_DATE, 'YYYYMMDD'), '47121231')
		AND MGP.PLANT_ID = HOU.ORGANIZATION_ID
		LEFT JOIN MJ.MJ_M_NOMINAL_RITASI MNR ON MNR.ID = MGP.LOCATION_GROUP_ID 
		AND CASE WHEN REGEXP_SUBSTR( REPLACE(REPLACE(TRIM(MSB.JAM_BERANGKAT), ';', ':'), '.', ''), '[^:]+', 1, 1 ) < 7 THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END 
		BETWEEN TO_CHAR(MNR.EFFECTIVE_START_DATE, 'YYYYMMDD') AND NVL(TO_CHAR(MNR.EFFECTIVE_END_DATE, 'YYYYMMDD'), '47121231')
		WHERE MSB.ORG_ID = 81
		AND TRQ.ID = $hdid
			--AND TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') BETWEEN '20170818' AND '20170818'
		$queryfilter
		";
	}
}

$query = "SELECT DISTINCT MSB.TRANSACTION_ID
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
, MNR.NOMINAL
, (MSB.VOLUME_KIRIM - NVL(MRS.RETURN_QTY, 0)) * MNR.NOMINAL TOTAL
, MNR.ID NOMINAL_ID
, MGP.ID GROUP_ID
, '-' NOMOR_LHO
, MSB.NOTES
FROM MJ.MJ_SJ_BETON MSB
   INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = INTERNAL_ADDRESS_LINE
   LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
   INNER JOIN MJ.MJ_M_GROUP_PLANT MGP ON CASE WHEN REGEXP_SUBSTR( REPLACE(REPLACE(TRIM(MSB.JAM_BERANGKAT), ';', ':'), '.', ''), '[^:]+', 1, 1 ) < 7 THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END 
   BETWEEN TO_CHAR(MGP.EFFECTIVE_START_DATE, 'YYYYMMDD') AND NVL(TO_CHAR(MGP.EFFECTIVE_END_DATE, 'YYYYMMDD'), '47121231')
   AND MGP.PLANT_ID = HOU.ORGANIZATION_ID
   INNER JOIN MJ.MJ_M_NOMINAL_RITASI MNR ON MNR.ID = MGP.LOCATION_GROUP_ID 
   AND CASE WHEN REGEXP_SUBSTR( REPLACE(REPLACE(TRIM(MSB.JAM_BERANGKAT), ';', ':'), '.', ''), '[^:]+', 1, 1 ) < 7 THEN TO_CHAR(MSB.TANGGAL_SJ-1, 'YYYYMMDD') ELSE TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') END 
   BETWEEN TO_CHAR(MNR.EFFECTIVE_START_DATE, 'YYYYMMDD') AND NVL(TO_CHAR(MNR.EFFECTIVE_END_DATE, 'YYYYMMDD'), '47121231')
   LEFT JOIN MJ.MJ_T_RITASI_QC_DETAIL TRQD ON TRQD.SJ_ID = MSB.TRANSACTION_ID
WHERE MSB.ORG_ID = 81
    AND TRQD.ID IS NULL
    --AND TO_CHAR(MSB.TANGGAL_SJ, 'YYYYMMDD') BETWEEN '20170818' AND '20170818'
    $queryfilter $queryTambahan
ORDER BY PLANT, SURAT_JALAN_NO";
 // echo $query;
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
	if(($HariIni == 'SUNDAY' || $HariLibur != 0) && $row[16] == '-'){
		$nominalRitasi = $row[12] * 2;
		$totalRitasi = $row[13] * 2;
		// echo $row[12] . " dan " . $nominalRitasi;
	} else {
		$nominalRitasi = $row[12];
		$totalRitasi = $row[13];
	}
	
	$record['DATA_JAM']=$row[3];
	$record['DATA_PLANT']=$row[4];
	$record['DATA_CUST']=$row[5];
	$record['DATA_PROYEK']=$row[6];
	$record['DATA_VOL']=$row[7];
	$record['DATA_VOL_RETUR']=$row[8];
	$record['DATA_SOPIR']=$row[9];
	$record['DATA_TRUK']=$row[10];
	$record['DATA_PLANT_CODE']=$row[11];
	$record['DATA_NOMINAL']=$nominalRitasi;
	$record['DATA_TOTAL']=$totalRitasi;
	$record['DATA_NOMINAL_ID']=$row[14];
	$record['DATA_GROUP_ID']=$row[15];
	$record['DATA_NOTE']=$row[17];
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