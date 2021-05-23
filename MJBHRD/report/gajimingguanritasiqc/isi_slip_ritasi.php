<?PHP
include '../../main/koneksi.php';
$namaFile = 'Slip_Ritasi_QC.xls';
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


$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Slip Gaji Ritasi QC' . $buatPlant)
            ->setCellValue('A2', 'Periode ' . $tgl_awal . ' - ' . $tgl_akhir);
			
$xlsRow = 4;

$queryExcel = "SELECT DISTINCT PPF.PERSON_ID
, PPF.FULL_NAME
FROM MJ.MJ_T_RITASI_LEMBUR TRL
INNER JOIN MJ.MJ_T_RITASI_QC TRQ ON TRQ.ID = TRL.TRANSAKSI_ID
INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRL.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
WHERE TRL.STATUS = 'Y'
$queryfilter
";
//echo $queryKaryawan;
$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
while($rowExcel = oci_fetch_row($resultExcel))
{
		$vPerson = $rowExcel[0];
		$vNama = $rowExcel[1];
		$vSumTotal = 0;
		
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'. $xlsRow, 'NAMA QC')
            ->setCellValue('B'. $xlsRow, $vNama);
		$xlsRow = $xlsRow + 2;	
		$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'. $xlsRow, 'Customer')
            ->setCellValue('B'. $xlsRow, 'Tanggal SJ')
            ->setCellValue('C'. $xlsRow, 'Nomor LHO')
            ->setCellValue('D'. $xlsRow, 'Volume (m3)')
            ->setCellValue('E'. $xlsRow, 'Total Jam Kerja (Jam)')
            ->setCellValue('F'. $xlsRow, 'Uang Lembur (Rp)')
            ->setCellValue('G'. $xlsRow, 'Nominal Ritasi (Rp)')
            ->setCellValue('H'. $xlsRow, 'Jumlah');
		$xlsRow++;	
		$query = "SELECT PPF.PERSON_ID
		, PPF.FULL_NAME
		, HP.PARTY_NAME
		, TRL.TGL
		, TRL.NOMOR_LHO
		, SUM(SUM_VOLUM) VOLUM
		, NVL(TRL.JAM_IN, '-') JAM_IN
		, NVL(TRL.JAM_OUT, '-') JAM_OUT
		, SUM(TRL.LEMBUR) LEMBUR
		, SUM(SUM_VOLUM * TRL.NOMINAL) NOMINAL
		, (SUM(TRL.LEMBUR) + SUM(SUM_VOLUM * TRL.NOMINAL)) TOTAL 
		FROM MJ.MJ_T_RITASI_LEMBUR TRL
		INNER JOIN MJ.MJ_T_RITASI_QC TRQ ON TRQ.ID = TRL.TRANSAKSI_ID
		INNER JOIN APPS.HZ_PARTIES HP ON HP.PARTY_ID = TRL.CUSTOMER_ID
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID = TRL.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
		INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE
		WHERE TRL.STATUS = 'Y' AND PPF.PERSON_ID = $vPerson
		$queryfilter
		GROUP BY PPF.FULL_NAME, TRL.NOMINAL, PPF.PERSON_ID, HP.PARTY_NAME
		, TRL.TGL, TRL.JAM_IN, TRL.JAM_OUT, TRL.NOMOR_LHO
		ORDER BY PPF.FULL_NAME, HP.PARTY_NAME, TRL.TGL, TRL.NOMOR_LHO
		";
		//echo $query;
		$result = oci_parse($con,$query);
		oci_execute($result);
		$count = 0;
		while($row = oci_fetch_row($result))
		{
			if($row[6] != '-'){
				$jamInSplit = explode(":",$row[6]);
				$jamOutSplit = explode(":",$row[7]);
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
			$xCust=$row[2];
			$xTgl=$row[3];
			$xLho=$row[4];
			$xVol=$row[5];
			$xJam=$jamTotal;
			$xLembur=$row[8];
			$xNominal=$row[9];
			$xTotal=$row[10];
			$vSumTotal = $vSumTotal + $xTotal;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $xlsRow, $xCust)
				->setCellValue('B' . $xlsRow, $xTgl)
				->setCellValue('C' . $xlsRow, $xLho)
				->setCellValue('D' . $xlsRow, $xVol)
				->setCellValue('E' . $xlsRow, $xJam)
				->setCellValue('F' . $xlsRow, $xLembur)
				->setCellValue('G' . $xlsRow, $xNominal)
				->setCellValue('H' . $xlsRow, $xTotal);
			$xlsRow++;
		}
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A' . $xlsRow, '')
			->setCellValue('B' . $xlsRow, '')
			->setCellValue('C' . $xlsRow, '')
			->setCellValue('D' . $xlsRow, '')
			->setCellValue('E' . $xlsRow, '')
			->setCellValue('F' . $xlsRow, '')
			->setCellValue('G' . $xlsRow, 'Total')
			->setCellValue('H' . $xlsRow, $vSumTotal);
			$xlsRow++;
		$xlsRow = $xlsRow + 2;	
}
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>