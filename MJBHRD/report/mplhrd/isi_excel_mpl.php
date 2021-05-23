<?PHP
include '../../main/koneksi.php';
$namaFile = 'Report_Monitoring_Lembur_HRD.xls';
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
	$pemohon = $_GET['pemohon'];

	$queryfilter .=" AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$tglto'  ";
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
		$queryfilter .= " AND MTS.NOMOR_SPL='$nodok'";
	}
	if($dept!=''){
		$queryfilter .= " AND MTSD.DEPARTEMEN LIKE '%$dept%'";
	}
	if($pemohon!=''){
		$queryfilter .= " AND MTSD.PERSON_ID='$pemohon'";
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
            ->setCellValue('A2', 'MONITORING LEMBUR KARYAWAN HRD')
           //->setCellValue('A3', 'PERIODE ' . $tgl_awal . ' - ' . $tgl_akhir)
            ->setCellValue('A5', 'No.')
            ->setCellValue('B5', 'No Dokumen')
            ->setCellValue('C5', 'Nama')
            ->setCellValue('D5', 'Departemen')
            ->setCellValue('E5', 'Plant')
            ->setCellValue('F5', 'Tanggal Pembuatan')
            ->setCellValue('G5', 'Tanggal SPL')
            ->setCellValue('H5', 'Jam Awal')
            ->setCellValue('I5', 'Jam Akhir')
            ->setCellValue('J5', 'Pekerjaan')
            ->setCellValue('K5', 'Status')
            ->setCellValue('L5', 'Tanggal Approved Terakhir')
            ->setCellValue('M5', 'User Approved Terakhir')
            ->setCellValue('N5', 'Approval Selanjutnya')
            ->setCellValue('O5', 'PIC Disapprove')
            ->setCellValue('P5', 'Alasan');

$xlsRow = 7;
$countHeader = 0;
$sumTotal = 0;

$queryExcel = "SELECT MTS.ID AS hd_id
, MTS.NOMOR_SPL AS DATA_NO_SPL
, MTSD.NAMA AS DATA_NAMA
, MTS.PLANT AS DATA_PLANT
, TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') AS DATA_TGL_SPL
, MTSD.JAM_FROM AS DATA_JAM_FROM
, MTSD.JAM_TO AS DATA_JAM_TO
, MTSD.PEKERJAAN AS DATA_PEKERJAAN 
, MTSD.STATUS_DOK AS DATA_STATUS
,(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTSD.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SPL'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTSD.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SPL')) AS DATA_TANGGAL_APPROVED
,(SELECT MTA.CREATED_BY
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTSD.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SPL'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTSD.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SPL')) AS DATA_USER_APPROVED
, CASE MTSD.STATUS_DOK 
WHEN 'Approved' THEN '-' 
WHEN 'Disapproved' THEN '-' 
ELSE (CASE MTSD.TINGKAT WHEN 0 THEN MTS.SPV WHEN 1 THEN MTS.MANAGER ELSE NVL(MMUP.FULL_NAME, '-') END) END AS DATA_PIC_APPROVED
, CASE MTSD.STATUS_DOK 
WHEN 'Disapproved' THEN (SELECT MTA.CREATED_BY 
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTSD.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SPL'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTSD.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SPL')) ELSE '-' END AS DATA_PIC_DISAPPROVED
, CASE MTSD.STATUS_DOK 
WHEN 'Disapproved' THEN (SELECT MTA.KETERANGAN
FROM MJ.MJ_T_APPROVAL MTA 
WHERE MTA.TRANSAKSI_ID=MTSD.ID AND MTA.APP_ID=" . APPCODE . " AND MTA.TRANSAKSI_KODE='SPL'
AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=MTSD.ID AND APP_ID=" . APPCODE . " AND TRANSAKSI_KODE='SPL')) ELSE '-' END AS DATA_ALASAN
, MTSD.DEPARTEMEN
, to_char (mts.created_date, 'YYYY-MM-DD') tgl_buat
FROM MJ.MJ_T_SPL MTS
INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
LEFT JOIN MJ.MJ_M_USERAPPROVAL MMUP ON MMUP.STATUS='A' AND MMUP.TINGKAT=MTSD.TINGKAT AND MMUP.APP_ID=" . APPCODE . "
        AND MTS.PLANT IN (SELECT HL.LOCATION_CODE 
        FROM MJ.MJ_M_AREA MMA
        INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID=MMA.ID
        INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMAD.LOCATION_ID
        WHERE MMA.APP_ID=MMUP.APP_ID AND MMA.NAMA_AREA=MMUP.NAMA_AREA  )
WHERE 1=1 $queryfilter
";
//echo $queryExcel;EXIT;
$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
while($rowExcel = oci_fetch_row($resultExcel))
{
		$vNospl = $rowExcel[1];
		$vNama = $rowExcel[2];
		$vPlant = $rowExcel[3];
		$vTglSpl = $rowExcel[4];
		$vJamFrom = $rowExcel[5];
		$vJamTo = $rowExcel[6];
		$vPekerjaan = $rowExcel[7];
		$vStatus = $rowExcel[8];
		$vTanggal = $rowExcel[9];
		$vUserApp = $rowExcel[10];
		$vPicApproved = $rowExcel[11];
		$vPicDisapproved = $rowExcel[12];
		$vAlasan = $rowExcel[13];
		$vDept = $rowExcel[14];
		$vCreateDate = $rowExcel[15];

		$countHeader++;
		
		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $xlsRow, $countHeader)
						->setCellValue('B' . $xlsRow, $vNospl)
						->setCellValue('C' . $xlsRow, $vNama)
						->setCellValue('D' . $xlsRow, $vDept)
						->setCellValue('E' . $xlsRow, $vPlant)
						->setCellValue('F' . $xlsRow, $vCreateDate)
						->setCellValue('G' . $xlsRow, $vTglSpl)
						->setCellValue('H' . $xlsRow, $vJamFrom)
						->setCellValue('I' . $xlsRow, $vJamTo)
						->setCellValue('J' . $xlsRow, $vPekerjaan)
						->setCellValue('K' . $xlsRow, $vStatus)
						->setCellValue('L' . $xlsRow, $vTanggal)
						->setCellValue('M' . $xlsRow, $vUserApp)
						->setCellValue('N' . $xlsRow, $vPicApproved)
						->setCellValue('O' . $xlsRow, $vPicDisapproved)
						->setCellValue('P' . $xlsRow, $vAlasan);
		$xlsRow++;
}
						
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>