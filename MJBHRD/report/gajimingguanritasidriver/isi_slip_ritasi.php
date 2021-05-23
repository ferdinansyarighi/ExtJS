<?PHP
include '../../main/koneksi.php';
$namaFile = 'Slip_Ritasi_Driver.xls';
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
            ->setCellValue('A2', 'Periode ' . $tgl_awal . ' - ' . $tgl_akhir);
			
$xlsRow = 4;

$queryExcel = "SELECT DISTINCT PPF.PERSON_ID
, PPF.FULL_NAME
FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD
INNER JOIN MJ.MJ_T_RITASI_DRIVER TRD ON TRD.ID = TRLD.TRANSAKSI_ID
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRLD.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
WHERE TRLD.STATUS = 'Y'
$queryfilter
";
// echo $queryExcel;
$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
while($rowExcel = oci_fetch_row($resultExcel))
{
		$vPerson = $rowExcel[0];
		$vNama = $rowExcel[1];
		$vSumTotal = 0;
		
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'. $xlsRow, 'NAMA DRIVER')
            ->setCellValue('B'. $xlsRow, $vNama);
		$xlsRow = $xlsRow + 2;	
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'. $xlsRow, 'Tanggal SJ')
            ->setCellValue('B'. $xlsRow, 'Ritasi Ke')
            ->setCellValue('C'. $xlsRow, 'Nomor LHO')
            ->setCellValue('D'. $xlsRow, 'Variabel Solar')
            ->setCellValue('E'. $xlsRow, 'Sum of Nominal Ritasi')
            ->setCellValue('F'. $xlsRow, 'Sum of Total Jam Kerja')
            ->setCellValue('G'. $xlsRow, 'Sum of Uang Lembur')
            ->setCellValue('H'. $xlsRow, 'Sum of Uang Makan')
            ->setCellValue('I'. $xlsRow, 'Jumlah');
		$xlsRow++;	
		$query = "SELECT PPF.PERSON_ID
		, PPF.FULL_NAME
		, TRLD.TGL
		, TRLD.RITASI_KE
		, TRLD.NOMOR_LHO
		, TRLD.VARIABEL
		, CASE WHEN TRLD.TIPE = 'BP' THEN SUM(TRLD.VARIABEL * TRLD.NOMINAL)
		ELSE SUM(TRLD.NOMINAL) END NOMINAL
		, NVL(TRLD.JAM_IN, '-') JAM_IN
		, NVL(TRLD.JAM_OUT, '-') JAM_OUT
		, SUM(TRLD.LEMBUR) LEMBUR
		, SUM(TRLD.UANG_MAKAN) UANG_MAKAN
		, CASE WHEN TRLD.TIPE = 'BP' THEN SUM(TRLD.VARIABEL * TRLD.NOMINAL)
		ELSE SUM(TRLD.NOMINAL) END + SUM(TRLD.LEMBUR) + SUM(TRLD.UANG_MAKAN) TOTAL
		FROM MJ.MJ_T_RITASI_LEMBUR_DRIVER TRLD
		INNER JOIN MJ.MJ_T_RITASI_DRIVER TRD ON TRD.ID = TRLD.TRANSAKSI_ID
		LEFT JOIN APPS.HZ_PARTIES HP ON HP.PARTY_ID = TRLD.CUSTOMER_ID
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRLD.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG = 'Y'
		WHERE TRLD.STATUS = 'Y' AND PPF.PERSON_ID = $vPerson
		$queryfilter
		GROUP BY PPF.FULL_NAME, TRLD.NOMINAL, PPF.PERSON_ID, TRLD.TGL, TRLD.RITASI_KE, TRLD.NOMOR_LHO, TRLD.VARIABEL
		, TRLD.TIPE, TRLD.JAM_IN, TRLD.JAM_OUT
		ORDER BY PPF.FULL_NAME, TRLD.TGL, TRLD.NOMOR_LHO
		";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);
		$count = 0;
		while($row = oci_fetch_row($result))
		{
			if($row[7] != '-'){
				$jamInSplit = explode(":",$row[7]);
				$jamOutSplit = explode(":",$row[8]);
				if($jamOutSplit[1] >= $jamInSplit[1]){
					$totalMenit = $jamOutSplit[1] - $jamInSplit[1];
					$totalJam = $jamOutSplit[0] - $jamInSplit[0];
				} else {
					$totalMenit = $jamOutSplit[1] - $jamInSplit[1] + 60;
					$totalJam = $jamOutSplit[0] - $jamInSplit[0] - 1;
				}
			} else {
				$totalJam = 0;
				$totalMenit = 0;
			}


			$jamTotal = $totalJam + ($totalMenit / 60);
			$jamTotal = round($jamTotal, 2);
			
			$xNama=$row[1];
			$xTgl=$row[2];
			$xRitasi=$row[3];
			$xLho=$row[4];
			$xVariabel=$row[5];
			$xNominal=$row[6];
			$xJam=$jamTotal;
			$xLembur=$row[9];
			$xUM=$row[10];
			$xTotal=$row[11];
			$vSumTotal = $vSumTotal + $xTotal;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $xlsRow, $xTgl)
				->setCellValue('B' . $xlsRow, $xRitasi)
				->setCellValue('C' . $xlsRow, $xLho)
				->setCellValue('D' . $xlsRow, $xVariabel)
				->setCellValue('E' . $xlsRow, $xNominal)
				->setCellValue('F' . $xlsRow, $xJam)
				->setCellValue('G' . $xlsRow, $xLembur)
				->setCellValue('H' . $xlsRow, $xUM)
				->setCellValue('I' . $xlsRow, $xTotal);
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
				->setCellValue('H' . $xlsRow, 'Total')
				->setCellValue('I' . $xlsRow, $vSumTotal);
			$xlsRow++;
		$xlsRow = $xlsRow + 2;	
}
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>