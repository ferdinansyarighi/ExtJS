<?PHP
include '../../main/koneksi.php';
$namaFile = 'Report_Ritasi_Driver.xls';
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

// Add some data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'PT. MERAK JAYA BETON')
            ->setCellValue('A2', 'REKAP RITASI DRIVER' . $buatPlant)
            ->setCellValue('A3', 'PERIODE ' . $tgl_awal . ' - ' . $tgl_akhir)
            ->setCellValue('A5', 'No.')
            ->setCellValue('B5', 'Values')
            ->setCellValue('B6', 'Row Labels')
            ->setCellValue('C6', 'Count of Ritasi Ke-')
            ->setCellValue('D6', 'Sum of Nominal Ritasi')
            ->setCellValue('E6', 'Sum of Uang Lembur')
            ->setCellValue('F6', 'Sum of Uang Makan')
            ->setCellValue('G6', 'Sum of Kekurangan atau Kelebihan')
            ->setCellValue('H6', 'Sum of Jumlah Nominal Driver');

$xlsRow = 7;
$countHeader = 0;
$sumTotal = 0;

$queryExcel = "SELECT PPF.PERSON_ID
, PPF.FULL_NAME
, COUNT(TRLD.RITASI_KE) RITASI_KE
, SUM(TRLD.LEMBUR) LEMBUR
, SUM(TRLD.UANG_MAKAN) UANG_MAKAN
, NVL(K.KEKURANGAN, 0) KEKURANGAN
, NVL(N.NOMINAL, 0) NOMINAL
, NVL(N.NOMINAL, 0) + SUM(TRLD.LEMBUR) + SUM(TRLD.UANG_MAKAN) + NVL(K.KEKURANGAN, 0) TOTAL
FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD
INNER JOIN MJ.MJ_T_RITASI_DRIVER TRD ON TRD.ID = TRLD.TRANSAKSI_ID
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRLD.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
LEFT JOIN 
(
    SELECT TRLD1.TRANSAKSI_ID, CASE WHEN TRLD1.TIPE = 'BP' THEN SUM((TRLD1.VARIABEL * TRLD1.NOMINAL))
    ELSE SUM(TRLD1.NOMINAL) END as NOMINAL 
    FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD1 
    INNER JOIN MJ.MJ_T_RITASI_DRIVER TRD1 ON TRLD1.TRANSAKSI_ID = TRD1.ID
    WHERE TRLD1.STATUS='Y' AND TRLD1.TGL >= TRD1.EFFECTIVE_START_DATE
    AND TRLD1.TGL <= TRD1.EFFECTIVE_END_DATE
    GROUP BY TRLD1.TRANSAKSI_ID, TRLD1.TIPE
) N ON N.TRANSAKSI_ID=TRD.ID
LEFT JOIN 
(
    SELECT TRLD1.TRANSAKSI_ID, CASE WHEN TRLD1.TIPE = 'BP' THEN SUM((TRLD1.VARIABEL * TRLD1.NOMINAL) + TRLD1.LEMBUR + TRLD1.UANG_MAKAN)
    ELSE SUM(TRLD1.NOMINAL + TRLD1.LEMBUR + TRLD1.UANG_MAKAN) END as KEKURANGAN 
    FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD1 
    INNER JOIN MJ.MJ_T_RITASI_DRIVER TRD1 ON TRLD1.TRANSAKSI_ID = TRD1.ID
    WHERE TRLD1.STATUS='Y' AND TRLD1.TGL < TRD1.EFFECTIVE_START_DATE
    GROUP BY TRLD1.TRANSAKSI_ID, TRLD1.TIPE
) K ON K.TRANSAKSI_ID=TRD.ID
WHERE TRLD.STATUS = 'Y'
$queryfilter
GROUP BY PPF.FULL_NAME, PPF.PERSON_ID, TRLD.TIPE, K.KEKURANGAN, N.NOMINAL
ORDER BY PPF.FULL_NAME
";
//echo $queryExcel;
$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
while($rowExcel = oci_fetch_row($resultExcel))
{
		$vNama = $rowExcel[1];
		$vRitasi = $rowExcel[2];
		$vLembur = $rowExcel[3];
		$vMakan = $rowExcel[4];
		$vKekurangan = $rowExcel[5];
		$vNominal = $rowExcel[6];
		$vTotal = $rowExcel[7];
		$sumTotal = $sumTotal + $vTotal;
		$countHeader++;
		
		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $xlsRow, $countHeader)
						->setCellValue('B' . $xlsRow, $vNama)
						->setCellValue('C' . $xlsRow, $vRitasi)
						->setCellValue('D' . $xlsRow, $vLembur)
						->setCellValue('E' . $xlsRow, $vMakan)
						->setCellValue('F' . $xlsRow, $vKekurangan)
						->setCellValue('G' . $xlsRow, $vNominal)
						->setCellValue('H' . $xlsRow, $vTotal);
		$xlsRow++;
}

		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $xlsRow, '')
						->setCellValue('B' . $xlsRow, '')
						->setCellValue('C' . $xlsRow, '')
						->setCellValue('D' . $xlsRow, '')
						->setCellValue('E' . $xlsRow, '')
						->setCellValue('F' . $xlsRow, '')
						->setCellValue('G' . $xlsRow, 'Jumlah')
						->setCellValue('H' . $xlsRow, $sumTotal);
						
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>