<?PHP
include '../../main/koneksi.php';
$namaFile = 'Rekap_SJ_Ritasi_QC.xls';
$vPeriode1="";
$vPeriode2="";
$queryfilter = "";
$buatPlant = "";
if (isset($_GET['tgl_awal']))
{	
	$tgl_awal = $_GET['tgl_awal'];
	$tgl_akhir = $_GET['tgl_akhir'];
	$plant = $_GET['plant'];
	$nama = $_GET['nama'];
	$queryfilter .= " AND TO_CHAR(TRQ.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal'  AND TO_CHAR(TRQ.EFFECTIVE_END_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' ";
	if($nama!=''){
		$queryfilter .= " AND PPF.PERSON_ID = '$nama'";
	}
	if($plant!=''){
		$queryfilter .= " AND PAF.LOCATION_ID = (SELECT LOCATION_ID FROM APPS.HR_ORGANIZATION_UNITS WHERE ORGANIZATION_ID = '$plant')";
		
		$queryHariIni = "SELECT  DISTINCT HOU.NAME LOC_NAME
		FROM    APPS.HR_ORGANIZATION_UNITS HOU
		WHERE   HOU.ORGANIZATION_ID = '$plant' ";
		$resultHariIni = oci_parse($con,$queryHariIni);
		oci_execute($resultHariIni);
		$rowPlant = oci_fetch_row($resultHariIni);
		$namaPlant = $rowPlant[0]; 
		$namaPlant = strtoupper($namaPlant);

		$buatPlant = " PLANT ".$namaPlant;
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


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Rekap Gaji Ritasi QC' . $buatPlant)
            ->setCellValue('A2', 'Periode ' . $tgl_awal . ' - ' . $tgl_akhir)
            ->setCellValue('A4', 'Nama Driver')
            ->setCellValue('B4', 'No Surat Jalan')
            ->setCellValue('C4', 'SJ Date')
            ->setCellValue('D4', 'Jam Berangkat')
            ->setCellValue('E4', 'Plant Code')
            ->setCellValue('F4', 'Plant')
            ->setCellValue('G4', 'Customer')
            ->setCellValue('H4', 'Project Location')
            ->setCellValue('I4', 'Vol SJ')
            ->setCellValue('J4', 'Vol Retur')
            ->setCellValue('K4', 'Driver')
            ->setCellValue('L4', 'TM')
            ->setCellValue('M4', 'Nomor LHO')
            ->setCellValue('N4', 'Nominal')
            ->setCellValue('O4', 'Total');
			
$xlsRow = 5;
$xNominal = 0;
$xTotal = 0;

$queryExcel = "SELECT DISTINCT PPF.FULL_NAME
    , MSB.SURAT_JALAN_NO
    , TO_CHAR(MSB.TANGGAL_SJ, 'DD-MON-YYYY') TANGGL_SJ
    , MSB.JAM_BERANGKAT
    , REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) PLANT_CODE
    , HOU.NAME PLANT
    , MSB.NAMA_CUST
    , MSB.LOKASI_PROYEK
    , MSB.VOLUME_KIRIM
    , NVL(MRS.RETURN_QTY, 0) VOL_RETUR
    , MSB.SOPIR
    , MSB.NOMOR_TRUK
    , TRQD.NOMOR_LHO
    , TRQD.NOMINAL
    , (MSB.VOLUME_KIRIM - NVL(MRS.RETURN_QTY, 0)) * TRQD.NOMINAL TOTAL
	FROM MJ.MJ_T_RITASI_QC TRQ
	INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRQ.NAMA_QC AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
	INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
	INNER JOIN MJ.MJ_T_RITASI_QC_DETAIL TRQD ON TRQ.ID = TRQD.MJ_T_RITASI_QC_ID
	INNER JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRQD.SJ_ID
	INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = HOU.INTERNAL_ADDRESS_LINE
	LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
	WHERE MSB.ORG_ID = 81
	AND TRQ.STATUS = 'Submit'
	$queryfilter
	ORDER BY HOU.NAME, MSB.SURAT_JALAN_NO
";
// echo $queryExcel;
$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
while($rowExcel = oci_fetch_row($resultExcel))
{
	$xNominal = $xNominal + $rowExcel[13]; 
	$xTotal = $xTotal + $rowExcel[14]; 
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A' . $xlsRow, $rowExcel[0])
		->setCellValue('B' . $xlsRow, $rowExcel[1])
		->setCellValue('C' . $xlsRow, $rowExcel[2])
		->setCellValue('D' . $xlsRow, $rowExcel[3])
		->setCellValue('E' . $xlsRow, $rowExcel[4])
		->setCellValue('F' . $xlsRow, $rowExcel[5])
		->setCellValue('G' . $xlsRow, $rowExcel[6])
		->setCellValue('H' . $xlsRow, $rowExcel[7])
		->setCellValue('I' . $xlsRow, $rowExcel[8])
		->setCellValue('J' . $xlsRow, $rowExcel[9])
		->setCellValue('K' . $xlsRow, $rowExcel[10])
		->setCellValue('L' . $xlsRow, $rowExcel[11])
		->setCellValue('M' . $xlsRow, $rowExcel[12])
		->setCellValue('N' . $xlsRow, $rowExcel[13])
		->setCellValue('O' . $xlsRow, $rowExcel[14]);
	$xlsRow++;
}
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A' . $xlsRow, '')
		->setCellValue('B' . $xlsRow, '')
		->setCellValue('C' . $xlsRow, '')
		->setCellValue('D' . $xlsRow, '')
		->setCellValue('E' . $xlsRow, '')
		->setCellValue('F' . $xlsRow, '')
		->setCellValue('G' . $xlsRow, '')
		->setCellValue('H' . $xlsRow, '')
		->setCellValue('I' . $xlsRow, '')
		->setCellValue('J' . $xlsRow, '')
		->setCellValue('K' . $xlsRow, '')
		->setCellValue('L' . $xlsRow, '')
		->setCellValue('M' . $xlsRow, 'TOTAL')
		->setCellValue('N' . $xlsRow, $xNominal)
		->setCellValue('O' . $xlsRow, $xTotal);
		
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>