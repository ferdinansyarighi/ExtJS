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

$perusahaan = "";
$dept = "";
$tipe = "";
$nomor = "";
$nama = "";
$count=0;

$queryfilter=""; 

	
	$perusahaan = $_GET['perusahaan'];
	$dept = $_GET['dept'];
	$tipe = $_GET['tipe'];
	$nomor = $_GET['nomor'];
	$nama = $_GET['nama'];
	
	if ($perusahaan!=''){
		$queryfilter .=" AND PAF.ORGANIZATION_ID = $perusahaan ";
	}
	if ($dept!=''){
		$queryfilter .=" AND PAF.JOB_ID = $dept ";
	}
	if ($tipe!='- Pilih -'){
		$queryfilter .=" AND MTPP.TIPE_PELUNASAN = '$tipe' ";
	}
	if ($nomor!=''){
		$queryfilter .=" AND MTPP.NO_PELUNASAN LIKE '%$nomor%' ";
	}
	if ($nama!=''){
		$queryfilter .=" AND MTPP.PERSON_ID = $nama ";
	} 
$namaFile = 'Cetak_pelunasan.xls';

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
            ->setCellValue('A1', 'Report Pelunasan')
            //->setCellValue('A2', 'Jumlah Hari Aktif : ' . $vHariAktif)
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'No. Pelunasan')
            ->setCellValue('C3', 'Tipe Pelunasan')
            ->setCellValue('D3', 'Tgl Pelunasan')
            ->setCellValue('E3', 'No. Pinjaman')
            ->setCellValue('F3', 'Tipe Pinjaman')
            ->setCellValue('G3', 'Tgl Pinjaman')
            ->setCellValue('H3', 'Tgl Validasi')
            ->setCellValue('I3', 'Jumlah Pinjaman')
            ->setCellValue('J3', 'Jumlah Cicilan')
            ->setCellValue('K3', 'Nominal Cicilan')
            ->setCellValue('L3', 'Outstanding')
            ->setCellValue('M3', 'Cicilan Terakhir');

$xlsRow = 3;
$countHeader = 0;


	$queryGaji = "SELECT MTP.ID, MTPP.NO_PELUNASAN, MTPP.TIPE_PELUNASAN, MTPP.TGL_PELUNASAN
		, MTP.NOMOR_PINJAMAN, MTP.TIPE TIPE_PINJAMAN, TO_CHAR(MTP.TANGGAL_PINJAMAN, 'DD-MON-YYYY') TANGGAL_PINJAMAN
		, TO_CHAR(MTA.CREATED_DATE, 'DD-MON-YYYY') TGL_VALIDASI
		, MTP.JUMLAH_PINJAMAN
		, MTP.JUMLAH_CICILAN_AWAL JUMLAH_CICILAN
		, MTP.NOMINAL NOMINAL_CICILAN
        , ( MTP.NOMINAL * MTP.JUMLAH_CICILAN_AWAL )
             - NVL (
                  (SELECT   SUM (NOMINAL)
                     FROM   MJ_T_PINJAMAN_DETAIL
                    WHERE   MJ_T_PINJAMAN_ID = MTP.ID
                    AND     SOURCE = 'SCHEDULER'),
                  0)
                OUTSTANDING
        , (
                DECODE ( MTPD.BULAN,
                        1, 'Januari',
                        2, 'Februari',
                        3, 'Maret',
                        4, 'April',
                        5, 'Mei',
                        6, 'Juni',
                        7, 'Juli',
                        8, 'Agustus',
                        9, 'September',
                        10, 'Oktober',
                        11, 'November',
                        12, 'Desember')
                ||' '|| MTPD.TAHUN 
        ) CICILAN_TERAKHIR
FROM MJ.MJ_T_PELUNASAN_PINJAMAN MTPP
INNER JOIN MJ.MJ_T_PELUNASAN_PINJAMAN_DT MTPPD ON MTPP.ID = MTPPD.HDID
INNER JOIN MJ.MJ_T_PINJAMAN MTP ON MTPPD.ID_PINJAMAN = MTP.ID
LEFT JOIN (
        SELECT MAX(ID) ID, TRANSAKSI_ID FROM
        MJ.MJ_T_APPROVAL WHERE TRANSAKSI_KODE = 'PINJAMAN'
        GROUP BY TRANSAKSI_ID
    ) MTA2 ON MTA2.TRANSAKSI_ID = MTP.ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA ON MTA2.ID = MTA.ID AND TRANSAKSI_KODE = 'PINJAMAN'
LEFT JOIN (
        SELECT MAX(ID) ID, MJ_T_PINJAMAN_ID FROM
        MJ.MJ_T_PINJAMAN_DETAIL WHERE STATUS = 1
        GROUP BY MJ_T_PINJAMAN_ID 
    ) MTPD2 ON MTPD2.MJ_T_PINJAMAN_ID = MTP.ID  
LEFT JOIN MJ.MJ_T_PINJAMAN_DETAIL MTPD ON MTPD.ID = MTPD2.ID
INNER JOIN PER_ASSIGNMENTS_F PAF ON MTPP.PERSON_ID = PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
WHERE 1=1 
AND ((MTP.TIPE = 'PINJAMAN PERSONAL' and MTP.STATUS_DOKUMEN = 'Validate') 
or (MTP.TIPE = 'PINJAMAN PENGGANTI INVENTARIS' and MTP.STATUS_DOKUMEN = 'Approved'))
$queryfilter
ORDER BY MTPP.ID";
	//echo $queryGaji; //exit;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$TransID=$row[0];
		$record = array();
		$hdid=$row[0];
		$no_pelunasan=$row[1];
		$tipe_pelunasan=$row[2];
		$tgl_pelunasan=$row[3];
		$no_pinjaman=$row[4];
		$tipe_pinjaman=$row[5];
		$tgl_pinjaman=$row[6];
		$tgl_validasi=$row[7];
		$jumlah_pinjaman=$row[8];
		$jumlah_cicilan=$row[9];
		$nominal_cicilan=$row[10];
		$outstanding=$row[11];
		$cicilan_terakhir=$row[12];
		$countHeader++;
		$xlsRow++;
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $xlsRow, $countHeader)
					->setCellValue('B' . $xlsRow, $no_pelunasan)
					->setCellValue('C' . $xlsRow, $tipe_pelunasan)
					->setCellValue('D' . $xlsRow, $tgl_pelunasan)
					->setCellValue('E' . $xlsRow, $no_pinjaman)
					->setCellValue('F' . $xlsRow, $tipe_pinjaman)
					->setCellValue('G' . $xlsRow, $tgl_pinjaman)
					->setCellValue('H' . $xlsRow, $tgl_validasi)
					->setCellValue('I' . $xlsRow, $jumlah_pinjaman)
					->setCellValue('J' . $xlsRow, $jumlah_cicilan)
					->setCellValue('K' . $xlsRow, $nominal_cicilan)
					->setCellValue('L' . $xlsRow, $outstanding)
					->setCellValue('M' . $xlsRow, $cicilan_terakhir);
	}
	
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>