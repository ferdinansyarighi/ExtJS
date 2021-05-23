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
	$resultPeriode = oci_parse($con,$queryPeriode);
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
$resultAktif = oci_parse($con,$queryAktif);
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
			->setCellValue('H3', 'Hari Masuk')
			->setCellValue('I3', 'Hari Sakit')
			->setCellValue('J3', 'Hari Cuti')
			->setCellValue('K3', 'Hari Ijin')
			->setCellValue('L3', 'Hari Alpha')
			->setCellValue('M3', 'Terlambat 1')
			->setCellValue('N3', 'Terlambat 2')
			->setCellValue('O3', 'Terlambat 3')
			->setCellValue('P3', 'Terlambat 4')
			->setCellValue('Q3', 'Gaji Pokok')
			->setCellValue('R3', 'Tunjangan Jabatan')
			->setCellValue('S3', 'Tunjangan Lokasi')
			->setCellValue('T3', 'Tunjangan Grade')
			->setCellValue('U3', 'Uang Makan')
			->setCellValue('V3', 'Uang Transport')
			->setCellValue('W3', 'Premi Hadir')
			->setCellValue('X3', 'Lembur')
			->setCellValue('Y3', 'PPH21')
			->setCellValue('Z3', 'BPJS Kesehatan')
			->setCellValue('AA3', 'BPJS TK')
			->setCellValue('AB3', 'Pinjaman')
			->setCellValue('AC3', 'BS')
			->setCellValue('AD3', 'Absen')
			->setCellValue('AE3', 'Telat')
			->setCellValue('AF3', 'Total Gaji')
			->setCellValue('AG3', 'Revisi Gaji Bulan Lalu')
			->setCellValue('AH3', 'Total diTransfer');

$xlsRow = 3;
$countHeader = 0;


	$queryGaji = "SELECT MMG.PERSON_ID
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
	FROM MJ.MJ_M_GAJI MMG
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMG.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
	WHERE REVISI=$revisi AND BULAN='$bulan' AND TAHUN='$tahun'";
	//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$vPersonId = $row[0];
		$vEmpNumber = $row[1];
		$vIdFinger = $row[2];
		$vFullName = $row[3];
		$vDept = $row[4];
		$vJabatan = $row[5];
		$vLokasi = $row[6];
		$hireDate = $row[7];
		$vTglResign = $row[8];
		$vJumMasuk = $row[9];
		$vHariSakit = $row[10];
		$vHariCuti = $row[11]; 
		$vHariIjin = $row[12]; 
		$vHariAlpha = $row[13]; 
		$vTerlambat1 = $row[14]; 
		$vTerlambat2 = $row[15];
		$vTerlambat3 = $row[16];
		$vTerlambat4 = $row[17];
		$vTotGajiPokok = $row[18];
		$vTunJab = $row[19];
		$vTunLok = $row[20];
		$vTunGrade = $row[21];
		$vTotUangMakan = $row[22];
		$vTotUangTrans = $row[23];
		$vTotTunKer = $row[24];
		$vPPH21 = $row[25];
		$vTotUangLembur = $row[26];
		$vBPJSKS = $row[27];
		$vBPJSTK = $row[28];
		$vPotongan = $row[29];
		$vBS = $row[30];
		$vTotUangAbsen = $row[31];
		$vTotUangTerlambat = $row[32];
		$vTotGajiSkr = $row[33];
		$vRevisiGaji = $row[34];
		$vTotGajiTransfer = $row[35];
		
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
					->setCellValue('I' . $xlsRow, $vHariSakit)
					->setCellValue('J' . $xlsRow, $vHariCuti)
					->setCellValue('K' . $xlsRow, $vHariIjin)
					->setCellValue('L' . $xlsRow, $vHariAlpha)
					->setCellValue('M' . $xlsRow, $vTerlambat1)
					->setCellValue('N' . $xlsRow, $vTerlambat2)
					->setCellValue('O' . $xlsRow, $vTerlambat3)
					->setCellValue('P' . $xlsRow, $vTerlambat4)
					->setCellValue('Q' . $xlsRow, $vTotGajiPokok)
					->setCellValue('R' . $xlsRow, $vTunJab)
					->setCellValue('S' . $xlsRow, $vTunLok)
					->setCellValue('T' . $xlsRow, $vTunGrade)
					->setCellValue('U' . $xlsRow, $vTotUangMakan)
					->setCellValue('V' . $xlsRow, $vTotUangTrans)
					->setCellValue('W' . $xlsRow, $vTotTunKer)
					->setCellValue('X' . $xlsRow, $vTotUangLembur)
					->setCellValue('Y' . $xlsRow, $vPPH21)
					->setCellValue('Z' . $xlsRow, $vBPJSKS)
					->setCellValue('AA' . $xlsRow, $vBPJSTK)
					->setCellValue('AB' . $xlsRow, $vPotongan)
					->setCellValue('AC' . $xlsRow, $vBS)
					->setCellValue('AD' . $xlsRow, $vTotUangAbsen)
					->setCellValue('AE' . $xlsRow, $vTotUangTerlambat)
					->setCellValue('AF' . $xlsRow, $vTotGajiSkr)
					->setCellValue('AG' . $xlsRow, $vRevisiGaji)
					->setCellValue('AH' . $xlsRow, $vTotGajiTransfer);
	}
	
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);

?>