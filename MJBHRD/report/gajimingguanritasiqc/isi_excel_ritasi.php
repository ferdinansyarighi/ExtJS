<?PHP
include '../../main/koneksi.php';
$namaFile = 'Report_Ritasi_QC.xls';
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
		$queryfilter .= " AND TRL.PERSON_ID = '$nama'";
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
            ->setCellValue('A2', 'REKAP RITASI QC' . $buatPlant)
            ->setCellValue('A3', 'PERIODE ' . $tgl_awal . ' - ' . $tgl_akhir)
            ->setCellValue('A5', 'No.')
            ->setCellValue('B5', 'Values')
            ->setCellValue('B6', 'Row Labels')
            ->setCellValue('C6', 'Sum of Volume (m3)')
            ->setCellValue('D6', 'Sum of Uang Lembur')
            ->setCellValue('E6', 'Sum of Nominal Ritasi')
            ->setCellValue('F6', 'Sum of Kekurangan Periode Sebelumnya')
            ->setCellValue('G6', 'Sum of Jumlah Nominal QC');

$xlsRow = 7;
$countHeader = 0;
$sumTotal = 0;

$queryExcel = "SELECT PPF.PERSON_ID
, PPF.FULL_NAME
, SUM(SUM_VOLUM) VOLUM
, SUM(TRL.LEMBUR) LEMBUR
, NVL(N.NOMINAL, 0) NOMINAL
, NVL(K.KEKURANGAN, 0) KEKURANGAN
, (NVL(N.NOMINAL, 0) + SUM(TRL.LEMBUR) + NVL(K.KEKURANGAN, 0)) TOTAL 
FROM MJ.MJ_T_RITASI_LEMBUR TRL
INNER JOIN MJ.MJ_T_RITASI_QC TRQ ON TRQ.ID = TRL.TRANSAKSI_ID
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRL.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN 
(
    SELECT TRLQ.TRANSAKSI_ID, SUM((TRLQ.SUM_VOLUM * TRLQ.NOMINAL)) as NOMINAL 
    FROM MJ.MJ_T_RITASI_LEMBUR TRLQ 
        INNER JOIN MJ.MJ_T_RITASI_QC TRQ1 ON TRLQ.TRANSAKSI_ID = TRQ1.ID
    WHERE TRLQ.STATUS='Y' AND TRLQ.TGL >= TRQ1.EFFECTIVE_START_DATE
    --AND TRLQ.TGL <= TRQ1.EFFECTIVE_END_DATE
    GROUP BY TRLQ.TRANSAKSI_ID
) N ON N.TRANSAKSI_ID=TRQ.ID
LEFT JOIN 
(
    SELECT TRLQ.TRANSAKSI_ID, SUM((TRLQ.SUM_VOLUM * TRLQ.NOMINAL) + TRLQ.LEMBUR) as KEKURANGAN 
    FROM MJ.MJ_T_RITASI_LEMBUR TRLQ 
        INNER JOIN MJ.MJ_T_RITASI_QC TRQ1 ON TRLQ.TRANSAKSI_ID = TRQ1.ID
    WHERE TRLQ.STATUS='Y' AND TRLQ.TGL < TRQ1.EFFECTIVE_START_DATE
    GROUP BY TRLQ.TRANSAKSI_ID
) K ON K.TRANSAKSI_ID=TRQ.ID
WHERE TRL.STATUS = 'Y'
$queryfilter
GROUP BY PPF.FULL_NAME, N.NOMINAL, PPF.PERSON_ID, K.KEKURANGAN
ORDER BY PPF.FULL_NAME
";
//echo $queryExcel;
$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
while($rowExcel = oci_fetch_row($resultExcel))
{
		$vNama = $rowExcel[1];
		$vVolum = $rowExcel[2];
		$vLembur = $rowExcel[3];
		$vNominal = $rowExcel[4];
		$vKekurangan = $rowExcel[5];
		$vTotal = $rowExcel[6];
		$sumTotal = $sumTotal + $vTotal;
		$countHeader++;
		
		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $xlsRow, $countHeader)
						->setCellValue('B' . $xlsRow, $vNama)
						->setCellValue('C' . $xlsRow, $vVolum)
						->setCellValue('D' . $xlsRow, $vLembur)
						->setCellValue('E' . $xlsRow, $vNominal)
						->setCellValue('F' . $xlsRow, $vKekurangan)
						->setCellValue('G' . $xlsRow, $vTotal);
		$xlsRow++;
}

		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $xlsRow, '')
						->setCellValue('B' . $xlsRow, '')
						->setCellValue('C' . $xlsRow, '')
						->setCellValue('D' . $xlsRow, '')
						->setCellValue('E' . $xlsRow, '')
						->setCellValue('F' . $xlsRow, 'Jumlah')
						->setCellValue('G' . $xlsRow, $sumTotal);
						
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>