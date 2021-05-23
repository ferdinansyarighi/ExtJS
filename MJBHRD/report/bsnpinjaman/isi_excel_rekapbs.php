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
$plant=""; 
$bulanAngka="";
$bulanBesok="";
$queryplant="";
$queryfilter=""; 

	
	
	
	$tglfrom = $_GET['tglfrom'];
	$tglto = $_GET['tglto'];
	$bulanfrom = $_GET['bulanfrom'];
	$bulanto = $_GET['bulanto'];
	$tahunfrom = $_GET['tahunfrom'];
	$tahunto = $_GET['tahunto'];
	
	$perusahaan = $_GET['perusahaan'];
	$dept = $_GET['dept'];
	$namaKaryawan = $_GET['pemohon'];
	$status = $_GET['status'];
	
	
	$perusahaanRaw = $_GET['perusahaanRaw'];
	$deptRaw = $_GET['deptRaw'];
	$namaKaryawanRaw = $_GET['pemohonRaw'];
	$bulanfromRaw = $_GET['bulanfromRaw'];
	$bulantoRaw = $_GET['bulantoRaw'];
	$statusRaw = $_GET['statusRaw'];
	
	if($tglfrom!='' || $tglto!=''){
		if($tglfrom==''){
			$tglfrom='01-01-1990';
		}
		if($tglto==''){
			$tglto='31-12-2090';
		}
		$queryfilter .=" AND MTP.CREATED_DATE >= TO_DATE('$tglfrom', 'DD-MM-YYYY') AND MTP.CREATED_DATE <= TO_DATE('$tglto', 'DD-MM-YYYY') ";
	}
	
	if($bulanfrom!='' || $tahunfrom!='' || $bulanfrom!='' || $tahunto!=''){
		$queryfilter .=" AND MTPD.CREATED_DATE BETWEEN TO_DATE('01-".$bulanfrom."-".$tahunfrom."', 'DD-MM-YYYY') AND TO_DATE('25-".$bulanto."-".$tahunto."', 'DD-MM-YYYY') ";
	}
	
	if ($perusahaan!=''){
		$queryfilter .=" AND PAF.ORGANIZATION_ID = $perusahaan ";
	}
	if ($dept!=''){
		$queryfilter .=" AND PAF.JOB_ID = $dept ";
	}
	if ($namaKaryawan!=''){
		$queryfilter .=" AND MTP.PERSON_ID = $namaKaryawan ";
	} 
	if ($status!='ALL'){
		$queryfilter .=" AND UPPER(MTP.STATUS) = '$status' ";
	}
$namaFile = 'BS_dan_Pinjaman.xls';

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
$style = array(
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	)
);
$objPHPExcel->getActiveSheet()->getStyle("A1:M1")->applyFromArray($style);
		
$objPHPExcel->getActiveSheet()->mergeCells('A1:M1');
$objPHPExcel->getActiveSheet()->mergeCells('A2:M2');
$objPHPExcel->getActiveSheet()->mergeCells('A3:M3');
$objPHPExcel->getActiveSheet()->mergeCells('A4:M4');
$objPHPExcel->getActiveSheet()->mergeCells('A5:M5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:M6');
$objPHPExcel->getActiveSheet()->mergeCells('A7:M7');
$objPHPExcel->getActiveSheet()->mergeCells('A8:M8');
$objPHPExcel->getActiveSheet()->mergeCells('A9:M9');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);

$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Report Schedule Produksi MJP')
					->setCellValue('A3', 'Perusahaan  : '.$perusahaanRaw)
					->setCellValue('A4', 'Departemen  : '.$deptRaw)
					->setCellValue('A5', 'Nama Karyawan  : '.$namaKaryawanRaw)
					->setCellValue('A6', 'Periode Peminjaman :  '.$tglfrom.' s/d '.$tglto)
					->setCellValue('A7', 'periode Pemotongan :  '.$bulanfromRaw.' '.$tahunfrom.' s/d '.$bulantoRaw.' '.$tahunto)
					->setCellValue('A8', 'Status :  '.$statusRaw);

$objPHPExcel->setActiveSheetIndex(0)
            //->setCellValue('A2', 'Jumlah Hari Aktif : ' . $vHariAktif)
            ->setCellValue('A10', 'No.')
            ->setCellValue('B10', 'Id')
            ->setCellValue('C10', 'Perusahaan')
            ->setCellValue('D10', 'Departemen')
            ->setCellValue('E10', 'Nama Karyawan')
            ->setCellValue('F10', 'Pinjaman/BS')
            ->setCellValue('G10', 'Nominal')
            ->setCellValue('H10', 'Cicilan')
            ->setCellValue('I10', 'Nominal Cicilan')
            ->setCellValue('J10', 'Outstanding')
            ->setCellValue('K10', 'Status')
            ->setCellValue('L10', 'Tgl Input Pinjaman')
            ->setCellValue('M10', 'Tgl Potongan')
            ->setCellValue('N10', 'Nominal Potongan');

$xlsRow = 11;
$countHeader = 0;


$queryGaji = "SELECT HD_ID, MTP.PERSON_ID, HOU.NAME PERUSAHAAN, PJ.NAME DEPT, PPF.FULL_NAME NAMA,  NAMA_PINJAMAN, NOMINAL, CICILAN, NOMINAL_CICILAN, OUTSTANDING, MTP.STATUS_NAME, TGL_INPUT_PINJAMAN
, TO_CHAR(MTPD.CREATED_DATE, 'DD-MON-YYYY') TGL_POTONGAN, TO_CHAR(MTPD.CREATED_DATE, 'MM-YYYY') TGL_POTONGAN
FROM (SELECT ID AS HD_ID
        , PERSON_ID
        , 'PINJAMAN' AS NAMA_PINJAMAN
        , PINJAMAN_P AS NOMINAL
        , CICILAN_P AS CICILAN
        , HARGA_CICILAN_P AS NOMINAL_CICILAN
        , OUTSTANDING_P AS OUTSTANDING 
        , CASE WHEN STATUS = 'A' THEN 'Active'
            WHEN STATUS = 'I' THEN 'Inactive'
            END STATUS_NAME
		, STATUS
        , TO_CHAR(CREATED_DATE, 'DD-MON-YYYY') TGL_INPUT_PINJAMAN
		, CREATED_DATE
        FROM MJ.MJ_T_POTONGAN 
        WHERE APP_ID=1 
        UNION
        SELECT ID AS HD_ID
        , PERSON_ID
        , 'BS' AS NAMA_PINJAMAN
        , PINJAMAN_B AS NOMINAL
        , CICILAN_B AS CICILAN
        , HARGA_CICILAN_B AS NOMINAL_CICILAN
        , OUTSTANDING_B AS OUTSTANDING 
        , CASE WHEN STATUS = 'A' THEN 'Active'
            WHEN STATUS = 'I' THEN 'Inactive'
            END STATUS_NAME
		, STATUS
        , TO_CHAR(CREATED_DATE, 'DD-MON-YYYY') TGL_INPUT_PINJAMAN
		, CREATED_DATE
        FROM MJ.MJ_T_POTONGAN 
        WHERE APP_ID=1 
        ORDER BY NAMA_PINJAMAN DESC) MTP
INNER JOIN MJ.MJ_T_POTONGAN_DETAIL MTPD ON MTP.HD_ID = MTPD.MJ_T_POTONGAN_ID AND MTP.NAMA_PINJAMAN = DECODE(MTPD.TIPE, 'POTONGAN', 'PINJAMAN', 'BS') 
INNER JOIN PER_ASSIGNMENTS_F PAF ON MTP.PERSON_ID = PAF.PERSON_ID AND PAF.PRIMARY_FLAG = 'Y' AND PAF.EFFECTIVE_END_DATE > TRUNC(SYSDATE)
INNER JOIN APPS.HR_OPERATING_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID AND SYSDATE BETWEEN NVL(DATE_FROM, SYSDATE) AND NVL(DATE_TO, SYSDATE)
INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
INNER JOIN PER_PEOPLE_F PPF ON MTP.PERSON_ID = PPF.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
WHERE NOMINAL > 0 AND MTPD.JUMLAH_POTONGAN != 0 
$queryfilter
ORDER BY HD_ID, MTP.PERSON_ID,MTPD.ID";
	//echo $queryGaji; //exit;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$record = array();
		$hdid=$row[0];
		$person_id=$row[1];
		$perusahaan=$row[2];
		$dept=$row[3];
		$nama=$row[4];
		$pinjaman=$row[5];
		$nominal=$row[6];
		$cicilan=$row[7];
		$nominal_cicilan=$row[8];
		$outstanding=$row[9];
		$status=$row[10];
		$tgl_input=$row[11];
		$tgl_potongan=$row[12];
		$jumlah_potongan=$row[13];
		$countHeader++;
		$xlsRow++;
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $xlsRow, $countHeader)
					->setCellValue('B' . $xlsRow, $hdid)
					->setCellValue('C' . $xlsRow, $perusahaan)
					->setCellValue('D' . $xlsRow, $dept)
					->setCellValue('E' . $xlsRow, $nama)
					->setCellValue('F' . $xlsRow, $pinjaman)
					->setCellValue('G' . $xlsRow, $nominal)
					->setCellValue('H' . $xlsRow, $cicilan)
					->setCellValue('I' . $xlsRow, $nominal_cicilan)
					->setCellValue('J' . $xlsRow, $outstanding)
					->setCellValue('K' . $xlsRow, $status)
					->setCellValue('L' . $xlsRow, $tgl_input)
					->setCellValue('M' . $xlsRow, $tgl_potongan)
					->setCellValue('N' . $xlsRow, $jumlah_potongan);
	}
	
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>