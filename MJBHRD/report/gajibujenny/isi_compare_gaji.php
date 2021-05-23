<?php
include '../../main/koneksi.php';


$nama=""; 
$plant=""; 
$periode=""; 
$vPeriode1="";
$vPeriode2="";
$queryfilter=""; 
$queryfilterDetail ="";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 
$bulanAngka="";
$bulanBesok="";
if (isset($_GET['bulan']) || isset($_GET['tahun']))
{	
	$revisi = trim($_GET['revisi']);
	$bulan = $_GET['bulan'];
	$tahun = $_GET['tahun'];
	
	if($bulan == 'January'){
		$bulanAngka = '01';
		$bulanBesok = '02';
	} elseif ($bulan == 'February'){
		$bulanAngka = '02';
		$bulanBesok = '03';
	} elseif ($bulan == 'March'){
		$bulanAngka = '03';
		$bulanBesok = '04';
	} elseif ($bulan == 'April'){
		$bulanAngka = '04';
		$bulanBesok = '05';
	} elseif ($bulan == 'May'){
		$bulanAngka = '05';
		$bulanBesok = '06';
	} elseif ($bulan == 'June'){
		$bulanAngka = '06';
		$bulanBesok = '07';
	} elseif ($bulan == 'July'){
		$bulanAngka = '07';
		$bulanBesok = '08';
	} elseif ($bulan == 'August'){
		$bulanAngka = '08';
		$bulanBesok = '09';
	} elseif ($bulan == 'September'){
		$bulanAngka = '09';
		$bulanBesok = '10';
	} elseif ($bulan == 'October'){
		$bulanAngka = '10';
		$bulanBesok = '11';
	} elseif ($bulan == 'November'){
		$bulanAngka = '11';
		$bulanBesok = '12';
	} else {
		$bulanAngka = '12';
		$bulanBesok = '01';
	}
	
	$queryPeriode = "SELECT TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1), 'YYYY-MM-DD') PERIODE1 
	, TO_CHAR(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), 'YYYY-MM-DD') PERIODE2
	FROM DUAL ";
	//echo $queryJumHari;
	$resultPeriode = oci_parse($conHR,$queryPeriode);
	oci_execute($resultPeriode);
	$rowPeriode = oci_fetch_row($resultPeriode);
	$vPeriode1 = $rowPeriode[0]; 
	$vPeriode2 = $rowPeriode[1]; 
}

$namaFile = 'Rincian_Revisi_Slip_Gaji.xls';

// header file excel
header("Status: 200");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header("Pragma: hack");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Type: application/download");
header("Content-Disposition: attachment; filename=\"".$namaFile."\""); 
header("Content-Transfer-Encoding: binary");

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/../../excel/PHPExcel.php';

// Create new PHPExcel object
//echo date('H:i:s') , " Create new PHPExcel object" , EOL;
$objPHPExcel = new PHPExcel();

// Set document properties
//echo date('H:i:s') , " Set document properties" , EOL;
$objPHPExcel->getProperties()->setCreator("MJB")
							 ->setLastModifiedBy("MJB")
							 ->setTitle("PHPExcel Test Document")
							 ->setSubject("PHPExcel Test Document")
							 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");

$vHariAktif=0;
$queryAktif = "SELECT ((SELECT COUNT(*) FROM 
(SELECT LEVEL AS DNUM FROM DUAL
CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - TO_DATE('$vPeriode1', 'YYYY-MM-DD')) - LEVEL >= 0) S
WHERE TO_CHAR(TO_DATE('$vPeriode1', 'YYYY-MM-DD') + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
(SELECT COUNT(-1)
FROM APPS.HXT_HOLIDAY_DAYS A
, APPS.HXT_HOLIDAY_CALENDARS B
WHERE A.HCL_ID=B.ID 
AND B.EFFECTIVE_END_DATE>SYSDATE 
AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$vPeriode1' AND '$vPeriode2')) + 1 TES
FROM DUAL";
//echo $queryJumHari;
$resultAktif = oci_parse($conHR,$queryAktif);
oci_execute($resultAktif);
while($rowAktif = oci_fetch_row($resultAktif))
{
	$vHariAktif = $rowAktif[0]; 
}
		

// Add some data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'Report Rincian Slip Gaji Karyawan')
			->setCellValue('A2', 'Jumlah Hari Aktif : ' . $vHariAktif)
			->setCellValue('A3', 'No.')
			->setCellValue('B3', 'NIK')
			->setCellValue('C3', 'No Face Attd')
			->setCellValue('D3', 'Nama Karyawan')
			->setCellValue('E3', 'Departemen')
			->setCellValue('F3', 'Jabatan')
			->setCellValue('G3', 'Lokasi / Plant')
			->setCellValue('H3', 'Hari Masuk Revisi 0')
			->setCellValue('I3', 'Hari Masuk Revisi 1')
			->setCellValue('J3', 'Hari Sakit Revisi 0')
			->setCellValue('K3', 'Hari Sakit Revisi 1')
			->setCellValue('L3', 'Hari Cuti Revisi 0')
			->setCellValue('M3', 'Hari Cuti Revisi 1')
			->setCellValue('N3', 'Hari Ijin Revisi 0')
			->setCellValue('O3', 'Hari Ijin Revisi 1')
			->setCellValue('P3', 'Hari Alpha Revisi 0')
			->setCellValue('Q3', 'Hari Alpha Revisi 1')
			->setCellValue('R3', 'Terlambat 1 Revisi 0')
			->setCellValue('S3', 'Terlambat 1 Revisi 1')
			->setCellValue('T3', 'Terlambat 2 Revisi 0')
			->setCellValue('U3', 'Terlambat 2 Revisi 1')
			->setCellValue('V3', 'Terlambat 3 Revisi 0')
			->setCellValue('W3', 'Terlambat 3 Revisi 1')
			->setCellValue('X3', 'Terlambat 4 Revisi 0')
			->setCellValue('Y3', 'Terlambat 4 Revisi 1')
			->setCellValue('Z3', 'Gaji Pokok Revisi 0')
			->setCellValue('AA3', 'Gaji Pokok Revisi 1')
			->setCellValue('AB3', 'Tunjangan Jabatan Revisi 0')
			->setCellValue('AC3', 'Tunjangan Jabatan Revisi 1')
			->setCellValue('AD3', 'Tunjangan Lokasi Revisi 0')
			->setCellValue('AE3', 'Tunjangan Lokasi Revisi 1')
			->setCellValue('AF3', 'Tunjangan Grade Revisi 0')
			->setCellValue('AG3', 'Tunjangan Grade Revisi 1')
			->setCellValue('AH3', 'Uang Makan Revisi 0')
			->setCellValue('AI3', 'Uang Makan Revisi 1')
			->setCellValue('AJ3', 'Uang Transport Revisi 0')
			->setCellValue('AK3', 'Uang Transport Revisi 1')
			->setCellValue('AL3', 'Premi Hadir Revisi 0')
			->setCellValue('AM3', 'Premi Hadir Revisi 1')
			->setCellValue('AN3', 'Lembur Revisi 0')
			->setCellValue('AO3', 'Lembur Revisi 1')
			->setCellValue('AP3', 'PPH21 Revisi 0')
			->setCellValue('AQ3', 'PPH21 Revisi 1')
			->setCellValue('AR3', 'BPJS Kesehatan Revisi 0')
			->setCellValue('AS3', 'BPJS Kesehatan Revisi 1')
			->setCellValue('AT3', 'BPJS TK Revisi 0')
			->setCellValue('AU3', 'BPJS TK Revisi 1')
			->setCellValue('AV3', 'Pinjaman Revisi 0')
			->setCellValue('AW3', 'Pinjaman Revisi 1')
			->setCellValue('AX3', 'BS Revisi 0')
			->setCellValue('AY3', 'BS Revisi 1')
			->setCellValue('AZ3', 'Absen Revisi 0')
			->setCellValue('BA3', 'Absen Revisi 1')
			->setCellValue('BB3', 'Telat Revisi 0')
			->setCellValue('BC3', 'Telat Revisi 1')
			->setCellValue('BD3', 'Total Gaji Revisi 0')
			->setCellValue('BE3', 'Total Gaji Revisi 1')
			->setCellValue('BF3', 'Revisi Gaji Bulan Lalu Revisi 0')
			->setCellValue('BG3', 'Revisi Gaji Bulan Lalu Revisi 1')
			->setCellValue('BH3', 'Total diTransfer Revisi 0')
			->setCellValue('BI3', 'Total diTransfer Revisi 1');

$xlsRow = 3;
$countHeader = 0;


	$queryGajiRev1 = "SELECT PPF.PERSON_ID
, PPF.EMPLOYEE_NUMBER
, PPF.HONORS
, PPF.FULL_NAME
, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) as dept
, REGEXP_SUBSTR(PP.NAME, '[^.]+', 1, 3) as jabatan
, HL.LOCATION_CODE
, TO_CHAR(PPF.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS ORIGINAL_DATE_OF_HIRE
, NVL(TO_CHAR(PPF.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '1990-01-01') AS TGL_RESIGN
, NVL(REV1.HARI_MASUK, 0)
, NVL(REV1.HARI_SAKIT, 0)
, NVL(REV1.HARI_CUTI, 0)
, NVL(REV1.HARI_IJIN, 0)
, NVL(REV1.HARI_ALPHA, 0)
, NVL(REV1.TERLAMBAT1, 0)
, NVL(REV1.TERLAMBAT2, 0)
, NVL(REV1.TERLAMBAT3, 0)
, NVL(REV1.TERLAMBAT4, 0)
, NVL(REV1.GAJI_POKOK, 0)
, NVL(REV1.TUNJ_JABATAN, 0)
, NVL(REV1.TUNJ_LOKASI, 0)
, NVL(REV1.TUNJ_GRADE, 0)
, NVL(REV1.UANG_MASUK, 0)
, NVL(REV1.UANG_TRANSPORT, 0)
, NVL(REV1.PREMI_HADIR, 0)
, NVL(REV1.PPH21, 0)
, NVL(REV1.LEMBUR, 0)
, NVL(REV1.BPJS_KESEHATAN, 0)
, NVL(REV1.BPJS_TK, 0)
, NVL(REV1.PINJAMAN, 0)
, NVL(REV1.BS, 0)
, NVL(REV1.ABSEN, 0)
, NVL(REV1.TELAT, 0)
, NVL(REV1.TOTAL_GAJIAN, 0)
, NVL(REV1.REVISI_GAJI, 0)
, NVL(REV1.TOTAL_DITRANSFER, 0)
, NVL(REV2.HARI_MASUK, 0)
, NVL(REV2.HARI_SAKIT, 0)
, NVL(REV2.HARI_CUTI, 0)
, NVL(REV2.HARI_IJIN, 0)
, NVL(REV2.HARI_ALPHA, 0)
, NVL(REV2.TERLAMBAT1, 0)
, NVL(REV2.TERLAMBAT2, 0)
, NVL(REV2.TERLAMBAT3, 0)
, NVL(REV2.TERLAMBAT4, 0)
, NVL(REV2.GAJI_POKOK, 0)
, NVL(REV2.TUNJ_JABATAN, 0)
, NVL(REV2.TUNJ_LOKASI, 0)
, NVL(REV2.TUNJ_GRADE, 0)
, NVL(REV2.UANG_MASUK, 0)
, NVL(REV2.UANG_TRANSPORT, 0)
, NVL(REV2.PREMI_HADIR, 0)
, NVL(REV2.PPH21, 0)
, NVL(REV2.LEMBUR, 0)
, NVL(REV2.BPJS_KESEHATAN, 0)
, NVL(REV2.BPJS_TK, 0)
, NVL(REV2.PINJAMAN, 0)
, NVL(REV2.BS, 0)
, NVL(REV2.ABSEN, 0)
, NVL(REV2.TELAT, 0)
, NVL(REV2.TOTAL_GAJIAN, 0)
, NVL(REV2.REVISI_GAJI, 0)
, NVL(REV2.TOTAL_DITRANSFER, 0) 
FROM
(
    SELECT DISTINCT PERSON_ID
    FROM MJHR.MJ_M_GAJI
    WHERE BULAN='$bulan' AND TAHUN='$tahun'
) PERSON
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=PERSON.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID AND PAF.PAYROLL_ID IS NOT NULL AND PAF.PRIMARY_FLAG='Y'
AND PAF.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID 
LEFT JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=PAF.LOCATION_ID
LEFT JOIN 
(
    SELECT MMG.PERSON_ID
    , PPF.EMPLOYEE_NUMBER
    , MMG.FINGER_ID
    , PPF.FULL_NAME
    , MMG.DEPARTMENT
    , MMG.JABATAN
    , MMG.LOKASI
    , TO_CHAR(PPF.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS ORIGINAL_DATE_OF_HIRE
    , NVL(TO_CHAR(PPF.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '1990-01-01') AS TGL_RESIGN
    , MMG.HARI_MASUK
    , MMG.HARI_SAKIT
    , MMG.HARI_CUTI
    , MMG.HARI_IJIN
    , MMG.HARI_ALPHA
    , MMG.TERLAMBAT1
    , MMG.TERLAMBAT2
    , MMG.TERLAMBAT3
    , MMG.TERLAMBAT4
    , MMG.GAJI_POKOK
    , MMG.TUNJ_JABATAN
    , MMG.TUNJ_LOKASI
    , MMG.TUNJ_GRADE
    , MMG.UANG_MASUK
    , MMG.UANG_TRANSPORT
    , MMG.PREMI_HADIR
    , MMG.PPH21
    , MMG.LEMBUR
    , MMG.BPJS_KESEHATAN
    , MMG.BPJS_TK
    , MMG.PINJAMAN
    , MMG.BS
    , MMG.ABSEN
    , MMG.TELAT
    , MMG.TOTAL_GAJIAN
    , MMG.REVISI_GAJI
    , MMG.TOTAL_DITRANSFER
    FROM MJHR.MJ_M_GAJI MMG
    INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMG.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
    WHERE REVISI=0 AND BULAN='$bulan' AND TAHUN='$tahun' --AND MMG.PERSON_ID=4276 
) REV1 ON REV1.PERSON_ID=PERSON.PERSON_ID
LEFT JOIN 
(
    SELECT MMG.PERSON_ID
    , PPF.EMPLOYEE_NUMBER
    , MMG.FINGER_ID
    , PPF.FULL_NAME
    , MMG.DEPARTMENT
    , MMG.JABATAN
    , MMG.LOKASI
    , TO_CHAR(PPF.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS ORIGINAL_DATE_OF_HIRE
    , NVL(TO_CHAR(PPF.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '1990-01-01') AS TGL_RESIGN
    , MMG.HARI_MASUK
    , MMG.HARI_SAKIT
    , MMG.HARI_CUTI
    , MMG.HARI_IJIN
    , MMG.HARI_ALPHA
    , MMG.TERLAMBAT1
    , MMG.TERLAMBAT2
    , MMG.TERLAMBAT3
    , MMG.TERLAMBAT4
    , MMG.GAJI_POKOK
    , MMG.TUNJ_JABATAN
    , MMG.TUNJ_LOKASI
    , MMG.TUNJ_GRADE
    , MMG.UANG_MASUK
    , MMG.UANG_TRANSPORT
    , MMG.PREMI_HADIR
    , MMG.PPH21
    , MMG.LEMBUR
    , MMG.BPJS_KESEHATAN
    , MMG.BPJS_TK
    , MMG.PINJAMAN
    , MMG.BS
    , MMG.ABSEN
    , MMG.TELAT
    , MMG.TOTAL_GAJIAN
    , MMG.REVISI_GAJI
    , MMG.TOTAL_DITRANSFER
    FROM MJHR.MJ_M_GAJI MMG
    INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMG.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
    WHERE REVISI=1 AND BULAN='$bulan' AND TAHUN='$tahun' --AND MMG.PERSON_ID=4276 
) REV2 ON REV2.PERSON_ID=PERSON.PERSON_ID
ORDER BY PPF.PERSON_ID";
	//echo $queryGaji;
	$resultGajiRev1 = oci_parse($conHR,$queryGajiRev1);
	oci_execute($resultGajiRev1);
	while($rowRev1 = oci_fetch_row($resultGajiRev1))
	{
		$vPersonId = $rowRev1[0];
		$vEmpNumber = $rowRev1[1];
		$vIdFinger = $rowRev1[2];
		$vFullName = $rowRev1[3];
		$vDept = $rowRev1[4];
		$vJabatan = $rowRev1[5];
		$vLokasi = $rowRev1[6];
		$hireDate = $rowRev1[7];
		$vTglResign = $rowRev1[8];
		$vJumMasuk = $rowRev1[9];
		$vHariSakit = $rowRev1[10];
		$vHariCuti = $rowRev1[11]; 
		$vHariIjin = $rowRev1[12]; 
		$vHariAlpha = $rowRev1[13]; 
		$vTerlambat1 = $rowRev1[14]; 
		$vTerlambat2 = $rowRev1[15];
		$vTerlambat3 = $rowRev1[16];
		$vTerlambat4 = $rowRev1[17];
		$vTotGajiPokok = $rowRev1[18];
		$vTunJab = $rowRev1[19];
		$vTunLok = $rowRev1[20];
		$vTunGrade = $rowRev1[21];
		$vTotUangMakan = $rowRev1[22];
		$vTotUangTrans = $rowRev1[23];
		$vTotTunKer = $rowRev1[24];
		$vPPH21 = $rowRev1[25];
		$vTotUangLembur = $rowRev1[26];
		$vBPJSKS = $rowRev1[27];
		$vBPJSTK = $rowRev1[28];
		$vPotongan = $rowRev1[29];
		$vBS = $rowRev1[30];
		$vTotUangAbsen = $rowRev1[31];
		$vTotUangTerlambat = $rowRev1[32];
		$vTotGajiSkr = $rowRev1[33];
		$vRevisiGaji = $rowRev1[34];
		$vTotGajiTransfer = $rowRev1[35];
		$vJumMasukRev = $rowRev1[36];
		$vHariSakitRev = $rowRev1[37];
		$vHariCutiRev = $rowRev1[38]; 
		$vHariIjinRev = $rowRev1[39]; 
		$vHariAlphaRev = $rowRev1[40]; 
		$vTerlambat1Rev = $rowRev1[41]; 
		$vTerlambat2Rev = $rowRev1[42];
		$vTerlambat3Rev = $rowRev1[43];
		$vTerlambat4Rev = $rowRev1[44];
		$vTotGajiPokokRev = $rowRev1[45];
		$vTunJabRev = $rowRev1[46];
		$vTunLokRev = $rowRev1[47];
		$vTunGradeRev = $rowRev1[48];
		$vTotUangMakanRev = $rowRev1[49];
		$vTotUangTransRev = $rowRev1[50];
		$vTotTunKerRev = $rowRev1[51];
		$vPPH21Rev = $rowRev1[52];
		$vTotUangLemburRev = $rowRev1[53];
		$vBPJSKSRev = $rowRev1[54];
		$vBPJSTKRev = $rowRev1[55];
		$vPotonganRev = $rowRev1[56];
		$vBSRev = $rowRev1[57];
		$vTotUangAbsenRev = $rowRev1[58];
		$vTotUangTerlambatRev = $rowRev1[59];
		$vTotGajiSkrRev = $rowRev1[60];
		$vRevisiGajiRev = $rowRev1[61];
		$vTotGajiTransferRev = $rowRev1[62];
		
		$countHeader++;
		$xlsRow++;
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $xlsRow, $countHeader)
					->setCellValue('B' . $xlsRow, $vEmpNumber)
					->setCellValue('C' . $xlsRow, $vIdFinger)
					->setCellValue('D' . $xlsRow, $vFullName)
					->setCellValue('E' . $xlsRow, $vDept)
					->setCellValue('F' . $xlsRow, $vJabatan)
					->setCellValue('G' . $xlsRow, $vLokasi)
					->setCellValue('H' . $xlsRow, $vJumMasuk)
					->setCellValue('I' . $xlsRow, $vJumMasukRev)
					->setCellValue('J' . $xlsRow, $vHariSakit)
					->setCellValue('K' . $xlsRow, $vHariSakitRev)
					->setCellValue('L' . $xlsRow, $vHariCuti)
					->setCellValue('M' . $xlsRow, $vHariCutiRev)
					->setCellValue('N' . $xlsRow, $vHariIjin)
					->setCellValue('O' . $xlsRow, $vHariIjinRev)
					->setCellValue('P' . $xlsRow, $vHariAlpha)
					->setCellValue('Q' . $xlsRow, $vHariAlphaRev)
					->setCellValue('R' . $xlsRow, $vTerlambat1)
					->setCellValue('S' . $xlsRow, $vTerlambat1Rev)
					->setCellValue('T' . $xlsRow, $vTerlambat2)
					->setCellValue('U' . $xlsRow, $vTerlambat2Rev)
					->setCellValue('V' . $xlsRow, $vTerlambat3)
					->setCellValue('W' . $xlsRow, $vTerlambat3Rev)
					->setCellValue('X' . $xlsRow, $vTerlambat4)
					->setCellValue('Y' . $xlsRow, $vTerlambat4Rev)
					->setCellValue('Z' . $xlsRow, $vTotGajiPokok)
					->setCellValue('AA' . $xlsRow, $vTotGajiPokokRev)
					->setCellValue('AB' . $xlsRow, $vTunJab)
					->setCellValue('AC' . $xlsRow, $vTunJabRev)
					->setCellValue('AD' . $xlsRow, $vTunLok)
					->setCellValue('AE' . $xlsRow, $vTunLokRev)
					->setCellValue('AF' . $xlsRow, $vTunGrade)
					->setCellValue('AG' . $xlsRow, $vTunGradeRev)
					->setCellValue('AH' . $xlsRow, $vTotUangMakan)
					->setCellValue('AI' . $xlsRow, $vTotUangMakanRev)
					->setCellValue('AJ' . $xlsRow, $vTotUangTrans)
					->setCellValue('AK' . $xlsRow, $vTotUangTransRev)
					->setCellValue('AL' . $xlsRow, $vTotTunKer)
					->setCellValue('AM' . $xlsRow, $vTotTunKerRev)
					->setCellValue('AN' . $xlsRow, $vTotUangLembur)
					->setCellValue('AO' . $xlsRow, $vTotUangLemburRev)
					->setCellValue('AP' . $xlsRow, $vPPH21)
					->setCellValue('AQ' . $xlsRow, $vPPH21Rev)
					->setCellValue('AR' . $xlsRow, $vBPJSKS)
					->setCellValue('AS' . $xlsRow, $vBPJSKSRev)
					->setCellValue('AT' . $xlsRow, $vBPJSTK)
					->setCellValue('AU' . $xlsRow, $vBPJSTKRev)
					->setCellValue('AV' . $xlsRow, $vPotongan)
					->setCellValue('AW' . $xlsRow, $vPotonganRev)
					->setCellValue('AX' . $xlsRow, $vBS)
					->setCellValue('AY' . $xlsRow, $vBSRev)
					->setCellValue('AZ' . $xlsRow, $vTotUangAbsen)
					->setCellValue('BA' . $xlsRow, $vTotUangAbsenRev)
					->setCellValue('BB' . $xlsRow, $vTotUangTerlambat)
					->setCellValue('BC' . $xlsRow, $vTotUangTerlambatRev)
					->setCellValue('BD' . $xlsRow, $vTotGajiSkr)
					->setCellValue('BE' . $xlsRow, $vTotGajiSkrRev)
					->setCellValue('BF' . $xlsRow, $vRevisiGaji)
					->setCellValue('BG' . $xlsRow, $vRevisiGajiRev)
					->setCellValue('BH' . $xlsRow, $vTotGajiTransfer)
					->setCellValue('BI' . $xlsRow, $vTotGajiTransferRev);
		
	}

	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);

?>