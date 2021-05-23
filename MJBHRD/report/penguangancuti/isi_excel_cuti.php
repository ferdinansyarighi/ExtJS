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
$tingkat = 0;
$queryfilter = "";
if (isset($_GET['perusahaan']) || isset($_GET['nama']) || isset($_GET['dept']))
{	
	$perusahaan = $_GET['perusahaan'];
	$nama = $_GET['nama'];
	$dept = $_GET['dept'];
	if($perusahaan!=''){
		$queryfilter .= " AND TL.DESCRIPTION='$perusahaan'";
	}
	if($nama!=''){
		$queryfilter .= " AND PPF.FULL_NAME='$nama'";
	}
	if($dept!=''){
		$queryfilter .= " AND REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3)='$dept'";
	}
}
$namaFile = 'Report_Penguangan_Cuti.xls';

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
            ->setCellValue('A1', 'Report Penguangan Cuti Karyawan')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'Nama Karyawan')
            ->setCellValue('C3', 'Perusahaan')
            ->setCellValue('D3', 'Departemen')
            ->setCellValue('E3', 'Jabatan')
            ->setCellValue('F3', 'Saldo Cuti')
            ->setCellValue('G3', 'TRP')
            ->setCellValue('H3', 'Total Uang Cuti');

$xlsRow = 3;
$countHeader = 0;
	
$query = "SELECT MMUC.ID
, PPF.FULL_NAME
, TL.DESCRIPTION AS DATA_PERUSAHAAN
, REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3) AS DATA_DEPARTMENT
, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS DATA_JABATAN
, CASE WHEN MMC.CUTI_TAHUNAN < 0 THEN 0 ELSE MMC.CUTI_TAHUNAN END AS DATA_TAHUNAN
, MMUC.UANG_TRP
, MMUC.UANG_CUTI
FROM MJHR.MJ_M_UANG_CUTI MMUC
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MMUC.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN APPS.PER_JOBS J ON MMUC.JOB_ID=J.JOB_ID
LEFT JOIN APPS.FND_FLEX_VALUES_TL TL ON REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 1)=TL.FLEX_VALUE_MEANING
LEFT JOIN APPS.PER_POSITIONS POS ON MMUC.POSITION_ID=POS.POSITION_ID
INNER JOIN MJ.MJ_M_CUTI MMC ON MMC.PERSON_ID=MMUC.PERSON_ID
 $queryfilter";
//echo $query;
$result = oci_parse($conHR,$query);
oci_execute($result);
$count = 0;
while($row = oci_fetch_row($result))
{
	$vID=$row[0];
	$vNamaKaryawan=$row[1];
	$vPerusahaan=$row[2];
	$vDepartment=$row[3];
	$vJabatan=$row[4];
	$vJumlahCuti=$row[5];
	$vTRP=$row[6];
	$vTotalUangCuti=$row[7];
	
	$countHeader++;
		$xlsRow++;
		
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $xlsRow, $countHeader)
				->setCellValue('B' . $xlsRow, $vNamaKaryawan)
				->setCellValue('C' . $xlsRow, $vPerusahaan)
				->setCellValue('D' . $xlsRow, $vDepartment)
				->setCellValue('E' . $xlsRow, $vJabatan)
				->setCellValue('F' . $xlsRow, $vJumlahCuti)
				->setCellValue('G' . $xlsRow, $vTRP)
				->setCellValue('H' . $xlsRow, $vTotalUangCuti);
}

	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>