<?PHP
include '../../main/koneksi.php';
$namaFile = 'Report_Range_Shift.xls';
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
            ->setCellValue('A2', 'MONITORING RANGE SHIFT KARYAWAN')
           //->setCellValue('A3', 'PERIODE ' . $tgl_awal . ' - ' . $tgl_akhir)
            ->setCellValue('A5', 'No.')
            ->setCellValue('B5', 'Shift')
            ->setCellValue('C5', 'Range Early In')
            ->setCellValue('D5', 'Range Late In')
            ->setCellValue('E5', 'Range Early Out')
            ->setCellValue('F5', 'Range Late Out')
            ->setCellValue('G5', 'Active')
            ->setCellValue('H5', 'Update By')
            ->setCellValue('I5', 'Update Date');

$xlsRow = 6;
$countHeader = 0;
$sumTotal = 0;

$querycount = "SELECT COUNT(-1) FROM MJ.MJ_M_RANGE_SHIFT";
$resultcount = oci_parse($con,$querycount);
oci_execute($resultcount);
$rowcount = oci_fetch_row($resultcount);
$jumgen = $rowcount[0];

if ($jumgen >= 1)
{
	$queryExcel = "SELECT MMS.SHIFT_ID,MMS.SHIFT_NAME, MMS.RANGE_EARLY_IN, MMS.RANGE_LATE_IN, MMS.RANGE_EARLY_OUT, MMS.RANGE_LATE_OUT, AKTIF, MMS.CREATED_BY, MMS.CREATED_DATE, PPF.FULL_NAME, TO_CHAR(NVL(MMS.LAST_UPDATED_DATE,MMS.CREATED_DATE), 'DD-MON-YYYY HH24:MM:SS'), ID FROM MJ.MJ_M_RANGE_SHIFT MMS, PER_PEOPLE_F PPF WHERE PPF.PERSON_ID = NVL(MMS.LAST_UPDATED_BY,MMS.CREATED_BY) ORDER BY SHIFT_NAME";
}
else {

}

$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
while($rowExcel = oci_fetch_row($resultExcel))
{
		$vShift_id 	 	 = $rowExcel[0];
		$vShift_name 	 = $rowExcel[1];
		$vEarly_in 		 = $rowExcel[2];
		$vLate_in 	 	 = $rowExcel[3];
		$vEarly_out		 = $rowExcel[4];
		$vLate_out 		 = $rowExcel[5];
		$vAktif 		 = $rowExcel[6];
		$vCreated_by 	 = $rowExcel[7];
		$vCreated_date 	 = $rowExcel[8];
		$vUpdate_by 	 = $rowExcel[9];
		$vUpdate_date  	 = $rowExcel[10];
		
		$countHeader++;
		
		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $xlsRow, $countHeader)
						->setCellValue('B' . $xlsRow, $vShift_name)
						->setCellValue('C' . $xlsRow, $vEarly_in)
						->setCellValue('D' . $xlsRow, $vLate_in)
						->setCellValue('E' . $xlsRow, $vEarly_out)
						->setCellValue('F' . $xlsRow, $vLate_out)
						->setCellValue('G' . $xlsRow, $vAktif)
						->setCellValue('H' . $xlsRow, $vUpdate_by)
						->setCellValue('I' . $xlsRow, $vUpdate_date);
		$xlsRow++;
}
						
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>