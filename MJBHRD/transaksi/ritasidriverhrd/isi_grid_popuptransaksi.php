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
$queryfilter = "";
$tglSJ = "";
$HariIni = "";
$hdid = 0;
$nominalRitasi = 0;
$totalRitasi = 0;
$tglfrom = "";
$tglto = "";
$cb1 = "";
$cb2 = "";
if (isset($_GET['tfcari']))
{	
	$id_driver = $_GET['id_driver'];
	
	$tfcari = $_GET['tfcari'];
	if($tfcari != ''){
		$queryfilter .=" AND TRDD.NOMOR_LHO LIKE '%$tfcari%' ";
	}
	
} 

if (isset($_GET['kumpulanLho']))
{	
	$kumpulanLho = $_GET['kumpulanLho'];
	if($kumpulanLho != ''){
		$queryfilter .=" AND nvl(TRDD.NOMOR_LHO, 0) not in($kumpulanLho) ";
	}
} 
//echo $queryfilter;exit;

$query = "SELECT TRD.ID, TRDD.NOMOR_LHO, TRD.NAMA_DRIVER, count(trdd.id) as total_sj
FROM MJ.MJ_T_RITASI_DRIVER TRD
INNER JOIN MJ.MJ_T_RITASI_DRIVER_DETAIL TRDD ON TRD.ID = TRDD.MJ_T_RITASI_DRIVER_ID
where 1=1 
and TRD.EFFECTIVE_START_DATE is null
and TRD.EFFECTIVE_END_DATE is null
and nama_driver = $id_driver
 $queryfilter
 group by TRD.ID, TRDD.NOMOR_LHO, TRD.NAMA_DRIVER
ORDER BY TRD.ID";
 //echo $query;exit;
$result = oci_parse($con,$query);
oci_execute($result);

$count = 0;
while($row = oci_fetch_row($result))
{
	$record = array();
	$record['HD_ID']=$row[0];
	$record['DATA_LHO']=$row[1];
	$record['DATA_DRIVER']=$row[2];
	$record['DATA_TOTAL_SJ']=$row[3];
	
	if($row[1] == ''){
		$filterlho = ' is null';
	}else{
		$filterlho = " = '".$row[1]."'";
	}
	
	$query2 = "SELECT DISTINCT MSB.TRANSACTION_ID
		, NVL(MSB.SURAT_JALAN_NO, 'STANDBY')
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
		, TRDD.RITASI_KE
		, TRDD.id
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
    WHERE TRD.ID = ".$row[0]."
    AND TRDD.NOMOR_LHO ".$filterlho;
	 //echo $query2;exit;
	$result2 = oci_parse($con,$query2);
	oci_execute($result2);
	$record2 = array();
	$i = 0;
	while($row2 = oci_fetch_row($result2)){
		//$record2['DATA_NO_SJ']=$row2[0];
		$record2[$i]['DATA_SJ']=$row2[1];
		$record2[$i]['DATA_TGL']=$row2[2];
		$tglSJ=$row2[2];
	
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
		 //echo $HariIni . " dan " . $HariLibur . " dan " .  $row2[16];
		// echo $row[16];
		if($HariIni == 'SUNDAY' || $HariLibur != 0){
			$nominalRitasi = $row2[14] * 1.5;
			$totalRitasi = $row2[15] * 1.5;
			 //echo $row2[14] . " dan " . $nominalRitasi;
		} else {
			$nominalRitasi = $row2[14];
			$totalRitasi = $row2[15];
		}
		
		$record2[$i]['DATA_JAM']=$row2[3];
		$record2[$i]['DATA_PLANT']=$row2[4];
		$record2[$i]['DATA_TIPE']=$row2[5];
		$record2[$i]['DATA_CUST']=$row2[6];
		$record2[$i]['DATA_PROYEK']=$row2[7];
		$record2[$i]['DATA_VOL']=$row2[8];
		$record2[$i]['DATA_VOL_RETUR']=$row2[9];
		$record2[$i]['DATA_SOPIR']=$row2[10];
		$record2[$i]['DATA_TRUK']=$row2[11];
		$record2[$i]['DATA_PLANT_CODE']=$row2[12];
		$record2[$i]['DATA_NOTE']=$row2[13];
		$record2[$i]['DATA_NOMINAL']=$nominalRitasi;
		$record2[$i]['DATA_TOTAL']=$totalRitasi;
		$record2[$i]['DATA_NOMINAL_ID']=$row2[16];
		$record2[$i]['DATA_GROUP_ID']=$row2[17];
		$record2[$i]['DATA_LHO2']=$row2[19];
		$record2[$i]['DATA_KM_AWAL']=$row2[20];
		$record2[$i]['DATA_KM_AKHIR']=$row2[21];
		$record2[$i]['DATA_SOLAR']=$row2[22];
		$record2[$i]['DATA_VARIABEL']=$row2[23];
		$record2[$i]['DATA_KETERANGAN']=$row2[24];
		$record2[$i]['DATA_RITASI']=$row2[25];
		$record2[$i]['DATA_ID_DETAIL']=$row2[26];
		
		$i++;
	}
	//print_r($record2);exit;
	$record['DATA_DETIL']=$record2;
	$data[]=$record;
	$count++;
}
//print_r($record);exit;
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