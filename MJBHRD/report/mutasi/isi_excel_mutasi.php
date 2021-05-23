<?PHP
include '../../main/koneksi.php';

$namaFile = 'Report_Summary_Mutasi.xls';

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
$queryfilter = ""; 


if ( isset( $_GET['param_cb_noreq'] ) )
{
	$v_cb_noreq = $_GET[ 'param_cb_noreq' ];
	
	if( $v_cb_noreq != '' ){
		$queryfilter .= " AND MTM.NO_REQUEST = '$v_cb_noreq'" ;
	}
} 


if ( isset( $_GET['param_dept'] ) )
{
	$v_dept = $_GET[ 'param_dept' ];
	
	if( $v_dept != '' ) {
		$queryfilter .= " AND PJ.NAME like '%$v_dept%'";
	}
}


if ( isset( $_GET['param_nama'] ) )
{
	$v_nama = $_GET[ 'param_nama' ];
	
	if( $v_nama != '' ) {
		$queryfilter .= " AND PPF.PERSON_ID like '%$v_nama%'";
	}
}


if ( isset( $_GET['param_tipe'] ) )
{
	$v_tipe = $_GET[ 'param_tipe' ];
	
	if( $v_tipe != '' ) {
		$queryfilter .= " AND MTM.TIPE = '$v_tipe'";
	}
}


if ( isset( $_GET['param_status'] ) )
{
	$status = $_GET[ 'param_status' ];
	
	if( $status != '' ) {
		$queryfilter .= " AND MTM.STATUS_DOK = '$status'";
	}
}


if ( isset( $_GET['param_tglfrom'] ) && isset( $_GET['param_tglto'] ) )
{
	$v_tglfrom = $_GET[ 'param_tglfrom' ];
	$v_tglto = $_GET[ 'param_tglto' ];
	
	if( $v_tglfrom != '' && $v_tglto != '' ) {
		
		$queryfilter .= " AND MTM.TGL_EFFECTIVE BETWEEN TO_DATE( '$v_tglfrom', 'DD/MM/YY' ) AND TO_DATE( '$v_tglto', 'DD/MM/YY' )";
		
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
			->setCellValue('A2', 'Report Summary Mutasi Promosi Demosi Karyawan')
            ->setCellValue('A5', 'No.')
            ->setCellValue('B5', 'No Request')
            ->setCellValue('C5', 'Tipe')
            ->setCellValue('D5', 'Status Karyawan')
            ->setCellValue('E5', 'Sifat Perubahan')
            ->setCellValue('F5', 'Nama Karyawan')
            ->setCellValue('G5', 'Departemen')
            ->setCellValue('H5', 'Manager')
            ->setCellValue('I5', 'Grade')
            ->setCellValue('J5', 'Posisi')
            ->setCellValue('K5', 'Lokasi')
            ->setCellValue('L5', 'Departemen Baru')
            ->setCellValue('M5', 'MGR Baru')
            ->setCellValue('N5', 'Grade Baru')
            ->setCellValue('O5', 'Posisi Baru')
            ->setCellValue('P5', 'Lokasi Baru')
			->setCellValue('Q5', 'Alasan')
			->setCellValue('R5', 'Keterangan')
			->setCellValue('S5', 'Tgl Efektif')
			->setCellValue('T5', 'Status');
			
			
$xlsRow = 7;
$countHeader = 0;
$sumTotal = 0;


$queryExcel = "
SELECT  MTM.NO_REQUEST, MTM.TIPE, MTM.STATUS_KARYAWAN, MTM.SIFAT_PERUBAHAN
        , PPF.FULL_NAME EMP_NAME, PJ.NAME DEPT_NAME_OLD
        , PPF1.FIRST_NAME || ' ' || PPF1.LAST_NAME NAMA_MGR_LAMA
        , PVG.NAME GRADE, PP.NAME POSITION_OLD, HL.LOCATION_CODE LOCATION
        , PJ1.NAME DEPT_BARU
        , PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME NAMA_MGR_BARU
        ,   (
                SELECT  DISTINCT NAME
                FROM    APPS.PER_GRADES
                WHERE   GRADE_ID = MTM.GRADE_BARU_ID
            ) GRADE_BARU
        , PP2.NAME POSITION_BARU
        , HL2.LOCATION_CODE LOCATION_BARU
        , MTM.ALASAN, MTM.KETERANGAN, MTM.TGL_EFFECTIVE, MTM.STATUS_DOK
        , MTM.ID
        , PPF.EMAIL_ADDRESS, PPF.PERSON_ID
        , PJ.JOB_ID, PP.POSITION_ID, HL.LOCATION_ID, PVG.GRADE_ID
FROM    APPS.PER_PEOPLE_F PPF
INNER JOIN  MJ_T_MUTASI MTM ON KARYAWAN_ID = PPF.PERSON_ID
INNER JOIN  APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
LEFT JOIN   APPS.PER_JOBS PJ ON PJ.JOB_ID = MTM.DEPT_LAMA_ID
LEFT JOIN   APPS.PER_POSITIONS PP ON PP.POSITION_ID = MTM.POSISI_LAMA_ID
LEFT JOIN   APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = MTM.LOKASI_LAMA_ID
LEFT JOIN   APPS.PER_GRADES PVG ON PVG.GRADE_ID = MTM.GRADE_LAMA_ID
INNER JOIN  APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.MGR_LAMA AND PPF1.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN   APPS.PER_JOBS PJ1 ON PJ1.JOB_ID = MTM.DEPT_BARU_ID
INNER JOIN  APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.MGR_BARU AND PPF2.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN   APPS.PER_POSITIONS PP2 ON PP2.POSITION_ID = MTM.POSISI_BARU_ID
LEFT JOIN   APPS.HR_LOCATIONS HL2 ON HL2.LOCATION_ID = MTM.LOKASI_BARU_ID
WHERE   PPF.EFFECTIVE_END_DATE > SYSDATE
AND     PAF.EFFECTIVE_END_DATE > SYSDATE
AND     PAF.PRIMARY_FLAG = 'Y'
$queryfilter
ORDER BY MTM.NO_REQUEST
";


/*
$queryExcel = "
SELECT  MTM.NO_REQUEST, MTM.TIPE, MTM.STATUS_KARYAWAN, MTM.SIFAT_PERUBAHAN
        , PPF.FULL_NAME EMP_NAME, PJ.NAME DEPT_NAME_OLD
        , PPF1.FIRST_NAME || ' ' || PPF1.LAST_NAME NAMA_MGR_LAMA
        , PVG.NAME GRADE, PP.NAME POSITION_OLD, HL.LOCATION_CODE LOCATION
        , PJ1.NAME DEPT_BARU
        , PPF2.FIRST_NAME || ' ' || PPF2.LAST_NAME NAMA_MGR_BARU
        ,   (
                SELECT  DISTINCT NAME
                FROM    APPS.PER_VALID_GRADES_V
                WHERE   GRADE_ID = MTM.GRADE_BARU_ID
            ) GRADE_BARU
        , PP2.NAME POSITION_BARU
        , HL2.LOCATION_CODE LOCATION_BARU
        , MTM.ALASAN, MTM.KETERANGAN, MTM.TGL_EFFECTIVE, MTM.STATUS_DOK
		, MTM.ID
        , PPF.EMAIL_ADDRESS, PPF.PERSON_ID
        , PJ.JOB_ID, PP.POSITION_ID, HL.LOCATION_ID, PVG.GRADE_ID
FROM    APPS.PER_PEOPLE_F PPF
INNER JOIN  MJ_T_MUTASI MTM ON KARYAWAN_ID = PPF.PERSON_ID
INNER JOIN  APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID = PPF.PERSON_ID
LEFT JOIN   APPS.PER_JOBS PJ ON PJ.JOB_ID = PAF.JOB_ID AND PJ.JOB_ID = MTM.DEPT_LAMA_ID
LEFT JOIN   APPS.PER_POSITIONS PP ON PP.POSITION_ID = PAF.POSITION_ID AND PP.POSITION_ID = MTM.POSISI_LAMA_ID
LEFT JOIN   APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = PAF.LOCATION_ID
LEFT JOIN   APPS.PER_VALID_GRADES_V PVG  ON PVG.POSITION_ID = PP.POSITION_ID AND PVG.GRADE_ID = MTM.GRADE_LAMA_ID
INNER JOIN  APPS.PER_PEOPLE_F PPF1 ON PPF1.PERSON_ID = MTM.MGR_LAMA AND PPF1.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN   APPS.PER_JOBS PJ1 ON PJ1.JOB_ID = MTM.DEPT_BARU_ID
INNER JOIN  APPS.PER_PEOPLE_F PPF2 ON PPF2.PERSON_ID = MTM.MGR_BARU AND PPF2.EFFECTIVE_END_DATE > SYSDATE
LEFT JOIN   APPS.PER_POSITIONS PP2 ON PP2.POSITION_ID = MTM.POSISI_BARU_ID
LEFT JOIN   APPS.HR_LOCATIONS HL2 ON HL2.LOCATION_ID = MTM.LOKASI_BARU_ID
WHERE   PPF.EFFECTIVE_END_DATE > SYSDATE
AND     PAF.PRIMARY_FLAG = 'Y'
$queryfilter
ORDER BY MTM.NO_REQUEST
";
*/



// echo $queryExcel;

$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);

while($rowExcel = oci_fetch_row($resultExcel))
{
		$vNoRequest = $rowExcel[0];
		$vTipe = $rowExcel[1];
		$vStatus_Karyawan = $rowExcel[2];
		$vSifat_Perubahan = $rowExcel[3];
		$vNama_Karyawan = $rowExcel[4];
		$vDepartemen = $rowExcel[5];
		$vManager = $rowExcel[6];
		$vGrade = $rowExcel[7];
		$vPosisi = $rowExcel[8];
		$vLokasi = $rowExcel[9];
		$vDepartemen_Baru = $rowExcel[10];
		$vMGR_Baru = $rowExcel[11];
		$vGrade_Baru = $rowExcel[12];
		$vPosisi_Baru = $rowExcel[13];
		$vLokasi_Baru = $rowExcel[14];
		$vAlasan = $rowExcel[15];
		$vKeterangan = $rowExcel[16];
		$vTgl_Efektif = $rowExcel[17];
		$vStatus = $rowExcel[18];
		
		$countHeader++;
		
		$objPHPExcel->setActiveSheetIndex(0)
						
						-> setCellValue( 'A' . $xlsRow, $countHeader )
						-> setCellValue( 'B' . $xlsRow, $vNoRequest )
						-> setCellValue( 'C' . $xlsRow, $vTipe )
						-> setCellValue( 'D' . $xlsRow, $vStatus_Karyawan )
						-> setCellValue( 'E' . $xlsRow, $vSifat_Perubahan )
						-> setCellValue( 'F' . $xlsRow, $vNama_Karyawan )
						-> setCellValue( 'G' . $xlsRow, $vDepartemen )
						-> setCellValue( 'H' . $xlsRow, $vManager )
						-> setCellValue( 'I' . $xlsRow, $vGrade )
						-> setCellValue( 'J' . $xlsRow, $vPosisi )
						-> setCellValue( 'K' . $xlsRow, $vLokasi )
						-> setCellValue( 'L' . $xlsRow, $vDepartemen_Baru )
						-> setCellValue( 'M' . $xlsRow, $vMGR_Baru )
						-> setCellValue( 'N' . $xlsRow, $vGrade_Baru )
						-> setCellValue( 'O' . $xlsRow, $vPosisi_Baru )
						-> setCellValue( 'P' . $xlsRow, $vLokasi_Baru )
						-> setCellValue( 'Q' . $xlsRow, $vAlasan )
						-> setCellValue( 'R' . $xlsRow, $vKeterangan )
						-> setCellValue( 'S' . $xlsRow, $vTgl_Efektif )
						-> setCellValue( 'T' . $xlsRow, $vStatus );
												
		$xlsRow++;
}
						
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>