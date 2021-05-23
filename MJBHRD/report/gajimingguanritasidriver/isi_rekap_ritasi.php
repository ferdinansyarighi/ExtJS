<?PHP
include '../../main/koneksi.php';
$namaFile = 'Rekap_SJ_Ritasi_Driver.xls';
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
	$queryfilter .= " AND TO_CHAR(TRD.EFFECTIVE_START_DATE, 'YYYY-MM-DD') >= '$tgl_awal'  AND TO_CHAR(TRD.EFFECTIVE_END_DATE, 'YYYY-MM-DD') <= '$tgl_akhir' ";
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
            ->setCellValue('A1', 'Rekap Gaji Ritasi Driver' . $buatPlant)
            ->setCellValue('A2', 'Periode ' . $tgl_awal . ' - ' . $tgl_akhir)
            ->setCellValue('A4', 'Nama Driver')
            ->setCellValue('B4', 'No Surat Jalan')
            ->setCellValue('C4', 'Nomor LHO')
            ->setCellValue('D4', 'KM Awal')
            ->setCellValue('E4', 'KM Akhir')
            ->setCellValue('F4', 'Solar')
            ->setCellValue('G4', 'Variabel')
            ->setCellValue('H4', 'Rit Ke-')
            ->setCellValue('I4', 'Nominal')
            ->setCellValue('J4', 'Total')
            ->setCellValue('K4', 'SJ Date')
            ->setCellValue('L4', 'Jam Berangkat')
            ->setCellValue('M4', 'Plant Code')
            ->setCellValue('N4', 'Plant')
            ->setCellValue('O4', 'Customer')
            ->setCellValue('P4', 'Project Location')
            ->setCellValue('Q4', 'Vol SJ')
            ->setCellValue('R4', 'Vol Retur')
            ->setCellValue('S4', 'Driver')
            ->setCellValue('T4', 'TM');
			
$xlsRow = 5;
$xNominal = 0;
$xTotal = 0;

$queryExcel = "SELECT DISTINCT PPF.FULL_NAME
, NVL(MSB.SURAT_JALAN_NO, 'STANDBY') SURAT_JALAN_NO
, TO_CHAR(NVL(MSB.TANGGAL_SJ, TRDD.TGL_STANDBY), 'DD-MON-YYYY') TANGGL_SJ
, MSB.JAM_BERANGKAT
, HOU.NAME PLANT
, MSB.NAMA_CUST
, MSB.LOKASI_PROYEK
, MSB.VOLUME_KIRIM
, NVL(MRS.RETURN_QTY, 0) VOL_RETUR
, MSB.SOPIR
, MSB.NOMOR_TRUK
, REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) PLANT_CODE
, TRDD.NOMINAL
, CASE WHEN HOU.ATTRIBUTE2 = 'BP' THEN ROUND((TRDD.NOMINAL * TRDD.VARIABEL), 2)
	ELSE ROUND(TRDD.NOMINAL, 2) END TOTAL
, TRDD.NOMOR_LHO
, TRDD.KM_AWAL
, TRDD.KM_AKHIR
, TRDD.SOLAR
, TRDD.VARIABEL
, TRDD.RITASI_KE
FROM MJ.MJ_T_RITASI_DRIVER TRD
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRD.NAMA_DRIVER AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
INNER JOIN MJ.MJ_T_RITASI_DRIVER_DETAIL TRDD ON TRD.ID = TRDD.MJ_T_RITASI_DRIVER_ID
LEFT JOIN MJ.MJ_SJ_BETON MSB ON MSB.TRANSACTION_ID = TRDD.SJ_ID AND MSB.ORG_ID = 81
INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON REGEXP_SUBSTR( MSB.SURAT_JALAN_NO, '[^/]+', 1, 2 ) = HOU.INTERNAL_ADDRESS_LINE
LEFT JOIN MJ.MJ_RETURN_SJ MRS ON MSB.TRANSACTION_ID = MRS.SJ_ID
WHERE TRD.STATUS = 'Submit'
$queryfilter
ORDER BY HOU.NAME, SURAT_JALAN_NO
";
//echo $queryExcel;exit;
$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
while($rowExcel = oci_fetch_row($resultExcel))
{
	$xNominal = $xNominal + $rowExcel[12]; 
	$xTotal = $xTotal + $rowExcel[13]; 
	

	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A' . $xlsRow, $rowExcel[0])
		->setCellValue('B' . $xlsRow, $rowExcel[1])
		->setCellValue('C' . $xlsRow, $rowExcel[14])
		->setCellValue('D' . $xlsRow, $rowExcel[15])
		->setCellValue('E' . $xlsRow, $rowExcel[16])
		->setCellValue('F' . $xlsRow, $rowExcel[17])
		->setCellValue('G' . $xlsRow, $rowExcel[18])
		->setCellValue('H' . $xlsRow, $rowExcel[19])
		->setCellValue('I' . $xlsRow, $rowExcel[12])
		->setCellValue('J' . $xlsRow, $rowExcel[13])
		->setCellValue('K' . $xlsRow, $rowExcel[2])
		->setCellValue('L' . $xlsRow, $rowExcel[3])
		->setCellValue('M' . $xlsRow, $rowExcel[11])
		->setCellValue('N' . $xlsRow, $rowExcel[4])
		->setCellValue('O' . $xlsRow, $rowExcel[5])
		->setCellValue('P' . $xlsRow, $rowExcel[6])
		->setCellValue('Q' . $xlsRow, $rowExcel[7])
		->setCellValue('R' . $xlsRow, $rowExcel[8])
		->setCellValue('S' . $xlsRow, $rowExcel[9])
		->setCellValue('T' . $xlsRow, $rowExcel[10]);
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
		->setCellValue('H' . $xlsRow, 'TOTAL')
		->setCellValue('I' . $xlsRow, $xNominal)
		->setCellValue('J' . $xlsRow, $xTotal)
		->setCellValue('K' . $xlsRow, '')
		->setCellValue('L' . $xlsRow, '')
		->setCellValue('M' . $xlsRow, '')
		->setCellValue('N' . $xlsRow, '')
		->setCellValue('O' . $xlsRow, '')
		->setCellValue('P' . $xlsRow, '')
		->setCellValue('Q' . $xlsRow, '')
		->setCellValue('R' . $xlsRow, '')
		->setCellValue('S' . $xlsRow, '')
		->setCellValue('T' . $xlsRow, '');
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>