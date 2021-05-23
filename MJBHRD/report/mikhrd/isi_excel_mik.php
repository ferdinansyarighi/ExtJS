<?PHP
include '../../main/koneksi.php';
$namaFile = 'Report_Monitoring_Ijin_HRD.xls';
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
$nodok = "";
$dept = "";
$tingkat = 0;
if (isset($_GET['status']) || isset($_GET['periodedar']) || isset($_GET['periodesamp']))
{	
	$queryfilter=""; 
	$tglskr=date('Y-m-d'); 
	$tahunbaru=substr($tglskr, 0, 2);
	$status = $_GET['status'];
	$plant = $_GET['plant'];
	$nodok = $_GET['nodok'];
	$dept = $_GET['dept'];
	$tglfrom = $_GET['periodedar'];
	$tglto = $_GET['periodesamp'];
	if ($tglfrom==""){	
		$tglfrom = "01/01/15";
	}


	$queryfilter .=" AND TO_CHAR(MTS.TANGGAL_FROM, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MTS.TANGGAL_FROM, 'YYYY-MM-DD') <= '$tglto'  ";
	if($status!='All'){
		if($status!='Inactive'){
			$queryfilter .= " AND MTS.STATUS_DOK='$status'";
		} else {
			$queryfilter .= " AND MTS.STATUS=0";
		}
	}
	if($plant!=''){
		$queryfilter .= " AND MTS.PLANT='$plant'";
	}
	if($nodok!=''){
		$queryfilter .= " AND MTS.NOMOR_SIK='$nodok'";
	}
	if($dept!=''){
		$queryfilter .= " AND MTS.DEPARTEMEN='$dept'";
	}
} 

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
            ->setCellValue('A1', 'PT. MERAK JAYA BETON')
            ->setCellValue('A2', 'MONITORING IJIN KARYAWAN HRD')
           //->setCellValue('A3', 'PERIODE ' . $tgl_awal . ' - ' . $tgl_akhir)
            ->setCellValue('A5', 'No.')
            ->setCellValue('B5', 'No Dokumen')
            ->setCellValue('C5', 'Pemohon')
            ->setCellValue('D5', 'Departemen')
            ->setCellValue('E5', 'Kategori')
            ->setCellValue('F5', 'Ijin Khusus')
            ->setCellValue('G5', 'Tanggal Pembuatan')
            ->setCellValue('H5', 'Tanggal Awal')
            ->setCellValue('I5', 'Tanggal Akhir')
            ->setCellValue('J5', 'Jam Awal')
            ->setCellValue('K5', 'Jam Akhir')
            ->setCellValue('L5', 'Keterangan')
            ->setCellValue('M5', 'Status')
            ->setCellValue('N5', 'Tanggal Approval Terakhir')
            ->setCellValue('O5', 'User Approval Terakhir')
            ->setCellValue('P5', 'Approval Selanjutnya')
            ->setCellValue('Q5', 'PIC Disapprove')
            ->setCellValue('R5', 'Alasan');

$xlsRow = 7;
$countHeader = 0;
$sumTotal = 0;

$queryExcel = "SELECT DISTINCT MTS.ID AS hd_id
, MTS.NOMOR_SIK AS DATA_NO_SIK
, MTS.PEMOHON AS DATA_PEMOHON
, MTS.KATEGORI AS DATA_KATEGORI
, TO_CHAR(MTS.TANGGAL_FROM, 'YYYY-MM-DD') AS DATA_TGL_FROM
, TO_CHAR(MTS.TANGGAL_TO, 'YYYY-MM-DD') AS DATA_TGL_TO
, MTS.JAM_FROM AS DATA_JAM_FROM
, MTS.JAM_TO AS DATA_JAM_TO
, MTS.KETERANGAN AS DATA_KETERANGAN 
, MTS.STATUS_DOK AS DATA_STATUS
,(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_TANGGAL_APPROVED
,(SELECT MTA.CREATED_BY
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) AS DATA_USER_APPROVED
, CASE MTS.STATUS_DOK 
WHEN 'Approved' THEN '-' 
WHEN 'Disapproved' THEN '-' 
ELSE (CASE MTS.TINGKAT WHEN 0 THEN MTS.SPV WHEN 1 THEN MTS.MANAGER ELSE (CASE MTS.TINGKAT WHEN 2 THEN NVL(MMUP.FULL_NAME, '-') ELSE NVL(MMUP2.FULL_NAME, '-') END) END) END AS DATA_PIC_APPROVED
, CASE MTS.STATUS_DOK 
WHEN 'Disapproved' THEN (SELECT MTA.CREATED_BY
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) ELSE '-' END AS DATA_PIC_DISAPPROVED
, CASE MTS.STATUS_DOK 
WHEN 'Disapproved' THEN (SELECT MTA.KETERANGAN
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SIK'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SIK')) ELSE '-' END AS DATA_ALASAN
, NVL(MTS.IJIN_KHUSUS, '') AS IJIN_KHUSUS
, MTS.DEPARTEMEN
, to_char (mts.created_date, 'YYYY-MM-DD') TGL_BUAT
FROM MJ.MJ_T_SIK MTS
LEFT JOIN MJ.MJ_M_USERAPPROVAL MMUP ON MMUP.STATUS='A' AND MMUP.TINGKAT=MTS.TINGKAT AND MMUP.APP_ID=" . APPCODE . "
        AND MTS.PLANT IN (SELECT HL.LOCATION_CODE 
        FROM MJ.MJ_M_AREA MMA
        INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
        INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
        WHERE MMA.APP_ID=MMUP.APP_ID AND MMA.NAMA_AREA=MMUP.NAMA_AREA )
LEFT JOIN MJ.MJ_M_USERAPPROVAL MMUP2 ON MMUP2.STATUS='A' AND MMUP2.TINGKAT=MTS.TINGKAT AND MMUP2.APP_ID=" . APPCODE . "
		AND MTS.PLANT IN (SELECT HL.LOCATION_CODE 
		FROM MJ.MJ_M_AREA MMA
		INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
		INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
		WHERE MMA.APP_ID=MMUP2.APP_ID AND MMA.NAMA_AREA=MMUP2.NAMA_AREA )
WHERE 1=1 $queryfilter
";
//echo $queryExcel;
$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
while($rowExcel = oci_fetch_row($resultExcel))
{
		$vNodok = $rowExcel[1];
		$vPemohon = $rowExcel[2];
		$vKategori = $rowExcel[3];
		$vTglFrom = $rowExcel[4];
		$vTglTo = $rowExcel[5];
		$vJamFrom = $rowExcel[6];
		$vJamTo = $rowExcel[7];
		$vKeterangan = $rowExcel[8];
		$vStatus = $rowExcel[9];
		$vTanggal = $rowExcel[10];
		$vUserApp = $rowExcel[11];
		$vNextApp = $rowExcel[12];
		$vPicDisapproved = $rowExcel[13];
		$vAlasan = $rowExcel[14];
		$vKhusus = $rowExcel[15];
		$vDepartemen = $rowExcel[16];
		$vCreateDate = $rowExcel[17];
		
		$countHeader++;
		
		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $xlsRow, $countHeader)
						->setCellValue('B' . $xlsRow, $vNodok)
						->setCellValue('C' . $xlsRow, $vPemohon)
						->setCellValue('D' . $xlsRow, $vDepartemen)
						->setCellValue('E' . $xlsRow, $vKategori)
						->setCellValue('F' . $xlsRow, $vKhusus)
						->setCellValue('G' . $xlsRow, $vCreateDate)
						->setCellValue('H' . $xlsRow, $vTglFrom)
						->setCellValue('I' . $xlsRow, $vTglTo)
						->setCellValue('J' . $xlsRow, $vJamFrom)
						->setCellValue('K' . $xlsRow, $vJamTo)
						->setCellValue('L' . $xlsRow, $vKeterangan)
						->setCellValue('M' . $xlsRow, $vStatus)
						->setCellValue('N' . $xlsRow, $vTanggal)
						->setCellValue('O' . $xlsRow, $vUserApp)
						->setCellValue('P' . $xlsRow, $vNextApp)
						->setCellValue('Q' . $xlsRow, $vPicDisapproved)
						->setCellValue('R' . $xlsRow, $vAlasan);
		$xlsRow++;
}
						
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>