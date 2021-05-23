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
$tahunbaru=substr($tglskr, 0, 4);
//echo $tahunbaru;
$hari="";
$bulan="";
$tahun=""; 
$bulanAngka="";
$bulanBesok="";
$vWhere ="";
$dept = "";
if (isset($_GET['dept']))
{	
	$dept = trim($_GET['dept']);
	
	if ($dept != ""){
		$vWhere = " AND REGEXP_SUBSTR(j.name, '[^.]+', 1, 3) = '$dept'";	
	}
}
$namaFile = 'Report_THR_Karyawan.xls';

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


// Add some data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Report THR Karyawan')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'NIK')
            ->setCellValue('C3', 'No Face Attd')
            ->setCellValue('D3', 'Nama Karyawan')
            ->setCellValue('E3', 'Departemen')
            ->setCellValue('F3', 'Jabatan')
            ->setCellValue('G3', 'Lokasi / Plant')
            ->setCellValue('H3', 'Tgl Masuk')
            ->setCellValue('I3', 'Masa Kerja')
            ->setCellValue('I4', 'Tahun')
            ->setCellValue('J4', 'Bulan')
            ->setCellValue('K4', 'Hari')
            ->setCellValue('L3', 'Gaji Pokok')
            ->setCellValue('M3', 'Tunjangan Jabatan')
            ->setCellValue('N3', 'Tunjangan Lokasi')
            ->setCellValue('O3', 'Tunjangan Grade')
            ->setCellValue('P3', 'Uang Makan')
            ->setCellValue('Q3', 'Uang Transport')
            ->setCellValue('R3', 'Premi Hadir')
            ->setCellValue('S3', 'BPJS Kesehatan')
            ->setCellValue('T3', 'BPJS TK')
            ->setCellValue('U3', 'Total Gaji');

$xlsRow = 4;
$countHeader = 0;


	$queryGaji = "";
	//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$vTahun = 0;
		$vBulan = 0;
		$vHari = 0;
		$vTotGajiSkr = 0;
		
		$vPersonId = $row[0];
		$vEmpNumber = $row[1];
		$vIdFinger = $row[2];
		$vFullName = $row[3];
		$vDept = $row[4];
		$vJabatan = $row[5];
		$vLokasi = $row[6];
		$hireDate = $row[7];
		$vGajiPokok = $row[8];
		$vTunJab = $row[9];
		$vTunGrade = $row[10];
		$vTunLok = $row[11];
		$vTunKer = $row[12];
		$vUangMakan = $row[13];
		$vUangTrans = $row[14];
		$vTotalHari = $row[15];
		$vPG = $row[16];
		$vBPJSKS = $row[17];
		$vBPJSTK = $row[18];
		
		$vTahun = round($vTotalHari / 365);
		$vBulan = round(($vTotalHari % 365) / 30);
		$vHari =  round((($vTotalHari % 365) % 30));
		
		$vTotGajiSkr = ($vGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vUangMakan + $vUangTrans + $vTunKer) - ($vBPJSKS + $vBPJSTK);
		
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
					->setCellValue('H' . $xlsRow, $hireDate)
					->setCellValue('I' . $xlsRow, $vTahun)
					->setCellValue('J' . $xlsRow, $vBulan)
					->setCellValue('K' . $xlsRow, $vHari)
					->setCellValue('L' . $xlsRow, $vGajiPokok)
					->setCellValue('M' . $xlsRow, $vTunJab)
					->setCellValue('N' . $xlsRow, $vTunLok)
					->setCellValue('O' . $xlsRow, $vTunGrade)
					->setCellValue('P' . $xlsRow, $vUangMakan)
					->setCellValue('Q' . $xlsRow, $vUangTrans)
					->setCellValue('R' . $xlsRow, $vTunKer)
					->setCellValue('S' . $xlsRow, $vBPJSKS)
					->setCellValue('T' . $xlsRow, $vBPJSTK)
					->setCellValue('U' . $xlsRow, $vTotGajiSkr);
	}
	
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>