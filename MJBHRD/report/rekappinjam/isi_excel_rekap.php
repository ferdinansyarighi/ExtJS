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
$kategori = "";
$dokstat = "";
$tglfrom = "";
$tglto = "";
$plant = "";
$noDok = "";
$Dept = "";
$tingkat = 0;
$count = 0;
$countSpv = 0;
$countMan = 0;
$rangeDate = 0;
$posDocText=""; 

$queryfilter="";

$noDok = $_GET['noPinjaman'];
$tipePinjaman = $_GET['tipePinjaman'];
$pemohon = $_GET['pemohon'];
$perusahaan = $_GET['perusahaan'];
$dept = $_GET['dept'];
$plant = $_GET['plant'];
$tglfrom = $_GET['tglfrom'];
$tglto = $_GET['tglto'];
	$posDoc = $_GET['posDoc'];

$hari="";
$bulan="";
$tahun=""; 
$queryfilter=""; 
$queryfilterRange= "";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);

if ($tglfrom != '')
{
	$hari=substr($tglfrom, 0, 2);
	$bulan=substr($tglfrom, 3, 2);
	$tahun=substr($tglfrom, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglfrom = $tahun . "-" . $bulan . "-" . $hari;
}

if ($tglto != '')
{
	$hari=substr($tglto, 0, 2);
	$bulan=substr($tglto, 3, 2);
	$tahun=substr($tglto, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglto = $tahun . "-" . $bulan . "-" . $hari;
}

if ($noDok!=''){
	$queryfilter .=" AND MTP.NOMOR_PINJAMAN LIKE '%$noDok%' ";
}
if ($tipePinjaman!=''){
	$queryfilter .=" AND MTP.TIPE LIKE '%$tipePinjaman%'";
}
if ($pemohon!=''){
	$queryfilter .=" AND MTP.PERSON_ID = $pemohon";
}
if ($perusahaan!=''){
	$queryfilter .=" AND PAF.ORGANIZATION_ID LIKE '%$perusahaan%' ";
}
if ($dept!='null'){
	$queryfilter .=" AND PAF.JOB_ID LIKE '%$dept%' ";
}
if ($plant!='null'){
	$queryfilter .=" AND PAF.LOCATION_ID LIKE '%$plant%' ";
}
if ($posDoc!='- Pilih -'){
	if($posDoc == 0){
		$posDocText = 'Pengajuan Karyawan';
	} else if($posDoc == 1){
		$posDocText = 'Approved Manager';
	} else if($posDoc == 2){
		$posDocText = 'Approved HRD / Pengajuan HRD';
	} else if($posDoc == 3){
		$posDocText = 'Approved Direksi';
	} else if($posDoc == 4){
		$posDocText = 'Approved MGR FIN';
	} else if($posDoc == 5){
		$posDocText = 'Validasi Kasir';
	} else{
		$posDocText = ' - ';
	}
	
	$queryfilter .=" AND MTP.TINGKAT = '$posDoc' ";
}

if ($tglfrom!='' && $tglto!=''){
	$queryfilter .=" AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') <= '$tglto' ";
}
else if ($tglfrom=='' && $tglto!=''){
	$queryfilter .=" AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') >= '1994-11-27' AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') <= '$tglto' ";
}
else if ($tglfrom!='' && $tglto==''){
	$queryfilter .=" AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MTP.TANGGAL_PINJAMAN, 'YYYY-MM-DD') <= '9999-11-27' ";
}
$namaFile = 'RekapPinjaman.xls';

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
            ->setCellValue('A1', 'Rekap Pinjaman')
            ->setCellValue('A2', 'Periode : ' . $tglfrom .'-'. $tglto)
            ->setCellValue('B2', 'Pemohon : ' . $pemohon)
            ->setCellValue('A3', 'Nomor Pinjaman : '. $noDok)
            ->setCellValue('B3', 'Tipe Pinjaman : '. $tipePinjaman)
            ->setCellValue('C3', 'Perusahaan : '. $perusahaan)
            ->setCellValue('A4', 'Departemen : '. $dept)
            ->setCellValue('B4', 'Plant : '. $plant)            
            ->setCellValue('F4', 'Posisi Document : '. $posDocText)            
            ->setCellValue('A6', 'No.')
            ->setCellValue('B6', 'Nomor Pinjaman')
            ->setCellValue('C6', 'Pemohon')
            ->setCellValue('D6', 'Manager')
            ->setCellValue('E6', 'Tipe Pinjaman')
            ->setCellValue('F6', 'Perusahaan')
            ->setCellValue('G6', 'Departemen')
            ->setCellValue('H6', 'Jabatan')
            ->setCellValue('I6', 'Grade')
            ->setCellValue('J6', 'Location')
            ->setCellValue('K6', 'Tgl Pengajuan')
            ->setCellValue('L6', 'Start Potongan')
            ->setCellValue('M6', 'Jumlah Pinjaman')
            ->setCellValue('N6', 'Jumlah Cicilan (Bln)')
            ->setCellValue('O6', 'Cicilan per Periode')
            ->setCellValue('P6', 'Outstanding (Rp)')
            ->setCellValue('Q6', 'Outstanding (Bln)')
            ->setCellValue('R6', 'Potongan Terakhir')
            ->setCellValue('S6', 'Status') 
            ->setCellValue('T6', 'Tgl Pencairan')
            ->setCellValue('U6', 'Approval Terakhir')
            ->setCellValue('V6', 'Tgl Approval Terakhir')
            ->setCellValue('W6', 'Approval Selanjutnya')
            ->setCellValue('X6', 'Ket Appr/Disappr');

$xlsRow = 6;
$countHeader = 0;

	$queryGaji = "SELECT DISTINCT MTP.ID, MTP.NOMOR_PINJAMAN, PPF.FULL_NAME PEMOHON, PPF2.FULL_NAME MANAGER, MTP.TIPE, HOU.NAME PERUSAHAAN
	, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
	, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
	, PG.NAME GRADE
	, HL.LOCATION_CODE LOCATION
	, MTP.TANGGAL_PINJAMAN TGL_PENGAJUAN
	, DECODE (MTP.START_POTONGAN_BULAN,
	    1, 'Januari',
	    2, 'Februari',
	    3, 'Maret',
	    4, 'April',
	    5, 'Mei',
	    6, 'Juni',
	    7, 'Juli',
	    8, 'Agustus',
	    9, 'September',
	    10,'Oktober',
	    11,'November',
	    12,'Desember')
	|| ' ' || MTP.START_POTONGAN_TAHUN AS START_POTONGAN
	, MTP.JUMLAH_PINJAMAN NOMINAL_PINJAMAN
	, MTP.JUMLAH_CICILAN_AWAL CICILAN
	, MTP.NOMINAL NOMINAL_CICILAN
	, MTP.TUJUAN_PINJAMAN
	, MTP.STATUS_DOKUMEN
	, CASE WHEN MTP.TINGKAT = 0 AND MTP.STATUS_DOKUMEN = 'Disapproved' THEN 
			(
				SELECT PPF.FULL_NAME
	            FROM MJ.MJ_T_APPROVAL MTA, PER_PEOPLE_F PPF, MJ.MJ_T_PINJAMAN MTP
	            WHERE TRANSAKSI_KODE = 'PINJAMAN'
	            AND TRANSAKSI_ID = MTP.ID
	            AND PPF.PERSON_ID = MTA.EMP_ID
	            AND MTA.STATUS = MTP.STATUS_DOKUMEN
	            AND MTP.STATUS_DOKUMEN = 'Disapproved'
	            AND MTP.ID = 2
	        )
	  ELSE PPF3.FULL_NAME END AS APPR_TERAKHIR
	, CASE WHEN MTP.TINGKAT = 0 AND MTP.STATUS_DOKUMEN = 'Disapproved' THEN 
			(
				SELECT MTA.CREATED_DATE
	            FROM MJ.MJ_T_APPROVAL MTA, PER_PEOPLE_F PPF, MJ.MJ_T_PINJAMAN MTP
	            WHERE TRANSAKSI_KODE = 'PINJAMAN'
	            AND TRANSAKSI_ID = MTP.ID
	            AND PPF.PERSON_ID = MTA.EMP_ID
	            AND MTA.STATUS = MTP.STATUS_DOKUMEN
	            AND MTP.STATUS_DOKUMEN = 'Disapproved'
	            AND MTP.ID = 2
	        )
	  ELSE MTA.CREATED_DATE END AS TGL_APPR_TERAKHIR
	, CASE WHEN MTP.TINGKAT = 0 AND MTP.STATUS_DOKUMEN = 'In process' THEN PPF2.FULL_NAME
	    WHEN MTP.TINGKAT = 1 THEN 'MGR HRD' 
	    WHEN MTP.TINGKAT = 2  THEN 'Direksi' 
	    WHEN MTP.TINGKAT = 3  THEN 'MGR FINANCE' 
	    WHEN MTP.TINGKAT = 4  THEN 'Validasi Kasir' 
	  ELSE '-' END AS NEXT_APPR
	, CASE WHEN MTP.TINGKAT = 0 AND MTP.STATUS_DOKUMEN = 'Disapproved' THEN 
			(
				SELECT MTA.KETERANGAN
	            FROM MJ.MJ_T_APPROVAL MTA, PER_PEOPLE_F PPF, MJ.MJ_T_PINJAMAN MTP
	            WHERE TRANSAKSI_KODE = 'PINJAMAN'
	            AND TRANSAKSI_ID = MTP.ID
	            AND PPF.PERSON_ID = MTA.EMP_ID
	            AND MTA.STATUS = MTP.STATUS_DOKUMEN
	            AND MTP.STATUS_DOKUMEN = 'Disapproved'
	            AND MTP.ID = 2
	        )
	  ELSE MTA.KETERANGAN END AS KETERANGAN
	, MTP.TINGKAT
	, MTP.NOMINAL * MTP.JUMLAH_CICILAN OUTSTANDINGRP
	, MTP.JUMLAH_CICILAN OUTSTANDINGBLN
	, DECODE(MTPD.CREATED_DATE, '', '-', TO_CHAR(MTPD.CREATED_DATE, 'Month-YYYY')) POTONGANTERAKHIR
	, MTP.TANGGAL_TRANSFER TGLPENCAIRAN
	FROM MJ.MJ_T_PINJAMAN MTP
	INNER JOIN PER_PEOPLE_F PPF ON PPF.PERSON_ID = MTP.PERSON_ID AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
	LEFT JOIN MJ.MJ_T_PINJAMAN_DETAIL MTPD ON MTPD.MJ_T_PINJAMAN_ID = MTP.ID
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PPF.PERSON_ID = PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
	INNER JOIN PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTP.MANAGER AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
	LEFT JOIN  MJ.MJ_T_APPROVAL MTA ON MTA.TRANSAKSI_ID = MTP.ID AND TRANSAKSI_KODE = 'PINJAMAN' AND DECODE(MTP.TINGKAT,5,MTP.TINGKAT-1,MTP.TINGKAT) = MTA.TINGKAT
	LEFT JOIN  PER_PEOPLE_F PPF3 ON MTA.CREATED_BY = PPF3.PERSON_ID AND PPF3.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF3.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
	INNER JOIN APPS.PER_GRADES PG ON PAF.GRADE_ID = PG.GRADE_ID
	INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
	INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
	WHERE 1=1
	$queryfilter
	ORDER BY MTP.ID";

	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$TransID=$row[0];
		$dokstat=$row[16];
		$record = array();
		$hdid=$row[0];
		$nopinjaman=$row[1];
		$pemohon=$row[2];
		$manager=$row[3];
		$tipe=$row[4];
		$perusahaan=$row[5];
		$dept=$row[6];
		$jabatan=$row[7];
		$grade=$row[8];
		$location=$row[9];
		$tglpengajuan=$row[10];
		$startpotongan=$row[11];
		$nominalpinjaman=$row[12];
		$cicilan=$row[13];
		$nominalcicilan=$row[14];
		$tujuan=$row[15];
		$status=$row[16];
		$appr_terakhir=$row[17];
		$tgl_appr=$row[18];
		$nextappr=$row[19];
		$keterangan=$row[20];
		$tingkat=$row[21];
		$outstandrp=$row[22];
		$outstandbln=$row[23];
		$potakhir=$row[24];
		$tglcair=$row[25];
		$countHeader++;
		$xlsRow++;

		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $xlsRow, $countHeader)
					->setCellValue('B' . $xlsRow, $nopinjaman)
					->setCellValue('C' . $xlsRow, $pemohon)
					->setCellValue('D' . $xlsRow, $manager)
					->setCellValue('E' . $xlsRow, $tipe)
					->setCellValue('F' . $xlsRow, $perusahaan)
					->setCellValue('G' . $xlsRow, $dept)
					->setCellValue('H' . $xlsRow, $jabatan)
					->setCellValue('I' . $xlsRow, $grade)
					->setCellValue('J' . $xlsRow, $location)
					->setCellValue('K' . $xlsRow, $tglpengajuan)
					->setCellValue('L' . $xlsRow, $startpotongan)
					->setCellValue('M' . $xlsRow, $nominalpinjaman)
					->setCellValue('N' . $xlsRow, $cicilan)
					->setCellValue('O' . $xlsRow, $nominalcicilan)
					->setCellValue('P' . $xlsRow, $outstandrp)
					->setCellValue('Q' . $xlsRow, $outstandbln)
					->setCellValue('R' . $xlsRow, $potakhir)
					->setCellValue('S' . $xlsRow, $status)
					->setCellValue('T' . $xlsRow, $tglcair)
					->setCellValue('U' . $xlsRow, $appr_terakhir)
					->setCellValue('V' . $xlsRow, $tgl_appr)
					->setCellValue('W' . $xlsRow, $nextappr)
					->setCellValue('X' . $xlsRow, $keterangan);
	}
	
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>