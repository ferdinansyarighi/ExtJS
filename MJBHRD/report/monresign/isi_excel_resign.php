<?PHP
include '../../main/koneksi.php';
$namaFile = 'Report_Monitoring_Resign.xls';
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
  
$tglfrom     = "";
$namapemohon = "";
$tglto       = "";
$nopengajuan = "";
$company     = "";
$departemen  = "";
$location    = "";
$manager     = "";
$queryfilter = "";

if (isset($_GET['tglfrom']) || isset($_GET['tglto']))
{	
	$tglskr      = date('Y-m-d');
	$tglfrom     = $_GET['tglfrom'];
	$tglto       = $_GET['tglto'];
	$namapemohon = $_GET['pemohon'];
	$nopengajuan = $_GET['nopengajuan'];
	$company	 = $_GET['company'];
	$dept   	 = $_GET['dept'];
	$location	 = $_GET['location'];
	$manager	 = $_GET['manager'];

	if ($namapemohon!=''){
		$queryfilter .=" AND NAMA_KARYAWAN = '$namapemohon' ";
	}
	if ($tglfrom!=''){
		$queryfilter .=" AND TGL_RESIGN >= TO_DATE('$tglfrom', 'DD/MM/YY') ";
	}
	if ($tglto!=''){
		$queryfilter .=" AND TGL_RESIGN <= TO_DATE('$tglto', 'DD/MM/YY') ";
	}
	if ($nopengajuan!=''){
		$queryfilter .=" AND NO_PENGAJUAN LIKE '%$nopengajuan%' ";
	}
	if ($company!=''){
		$queryfilter .=" AND COMPANY LIKE '%$company%' ";
	}
	if ($dept!=''){
		$queryfilter .=" AND DEPARTMENT LIKE '%$dept%' ";
	}
	if ($location!=''){
		$queryfilter .=" AND LOCATION LIKE '%$location%' ";
	}
	if ($manager!=''){
		$queryfilter .=" AND MANAGER LIKE '%$manager%' ";
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
            ->setCellValue('A2', 'MONITORING RESIGN KARYAWAN')
           //->setCellValue('A3', 'PERIODE ' . $tgl_awal . ' - ' . $tgl_akhir)
            ->setCellValue('A5', 'No.')
            ->setCellValue('B5', 'Nomor Pengajuan')
            ->setCellValue('C5', 'Pemohon')
            ->setCellValue('D5', 'Perusahaan')
            ->setCellValue('E5', 'Departemen')
            ->setCellValue('F5', 'Jabatan')
            ->setCellValue('G5', 'Grade')
            ->setCellValue('H5', 'Location')
            ->setCellValue('I5', 'Tanggal Masuk')
            ->setCellValue('J5', 'Tanggal Resign')
            ->setCellValue('K5', 'Lama Kerja')
            ->setCellValue('L5', 'Keterangan')
            ->setCellValue('M5', 'Tgl Pengajuan')
            ->setCellValue('N5', 'Dept Head')
            ->setCellValue('O5', 'App Dept Head')
            ->setCellValue('P5', 'HRD MGR')
            ->setCellValue('Q5', 'App HRD MGR')
            ->setCellValue('R5', 'PIC Disapprove')
            ->setCellValue('S5', 'Ket Disapprove');

$xlsRow = 7;
$countHeader = 0;
$sumTotal = 0;

$querycount = "SELECT COUNT(-1) FROM PER_ASSIGNMENTS_F PAF, PER_PEOPLE_F PPF WHERE PAF.JOB_ID = 26066 AND PAF.PERSON_ID = PPF.PERSON_ID AND PPF.PERSON_ID = $emp_id";
$resultcount = oci_parse($con,$querycount);
oci_execute($resultcount);
$rowcount = oci_fetch_row($resultcount);
$jumgen = $rowcount[0];

$querycountMan = "SELECT COUNT(-1) FROM PER_PEOPLE_F PPF, PER_POSITIONS PP, PER_ASSIGNMENTS_F PAF 
WHERE PPF.FULL_NAME LIKE '%$emp_name%' AND PAF.PERSON_ID = PPF.PERSON_ID AND PAF.POSITION_ID = PP.POSITION_ID AND PP.NAME LIKE '%MGR%' AND PAF.JOB_ID != 26066";
$resultcountman = oci_parse($con,$querycountMan);
oci_execute($resultcountman);
$rowcountman = oci_fetch_row($resultcountman);
$jumman = $rowcountman[0];

$queryPemohon = "SELECT PJ.JOB_ID 
FROM APPS.PER_PEOPLE_F PPF
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
INNER JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID=PAF.JOB_ID
WHERE PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y' AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PPF.FULL_NAME LIKE '%$emp_name%'";
$resultPemohon = oci_parse($con,$queryPemohon);
oci_execute($resultPemohon);
$rowPemohon = oci_fetch_row($resultPemohon);
$jobPemohon = $rowPemohon[0];

if ($jumgen >= 1)
{
	$queryExcel = "SELECT NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD-MON-YYYY') TGL_MASUK ,TO_CHAR(TGL_RESIGN, 'DD-MON-YYYY') TGL_RESIGN,
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN, TO_CHAR(CREATED_DATE, 'DD-MON-YYYY') TGL_PENGAJUAN,
	MANAGER, APPROVAL_MANAGER, HRD_MGR, APPROVAL_MANAGER_HRD, PIC_DISAPPROVE, KETERANGAN_DISAPPROVE
	FROM MJ.MJ_T_RESIGN $queryfilter
	";
}
else if ($jumman >= 1)
{
	$queryExcel = "SELECT NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD-MON-YYYY') TGL_MASUK ,TO_CHAR(TGL_RESIGN, 'DD-MON-YYYY') TGL_RESIGN,
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN, TO_CHAR(CREATED_DATE, 'DD-MON-YYYY') TGL_PENGAJUAN,
	MANAGER, APPROVAL_MANAGER, HRD_MGR, APPROVAL_MANAGER_HRD, PIC_DISAPPROVE, KETERANGAN_DISAPPROVE
	FROM MJ.MJ_T_RESIGN WHERE NAMA_KARYAWAN LIKE '%$emp_name%' $queryfilter
	UNION
	SELECT NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD-MON-YYYY') TGL_MASUK ,TO_CHAR(TGL_RESIGN, 'DD-MON-YYYY') TGL_RESIGN,
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN, TO_CHAR(CREATED_DATE, 'DD-MON-YYYY') TGL_PENGAJUAN,
	MANAGER, APPROVAL_MANAGER, HRD_MGR, APPROVAL_MANAGER_HRD, PIC_DISAPPROVE, KETERANGAN_DISAPPROVE
	FROM MJ.MJ_T_RESIGN WHERE MANAGER LIKE '%$emp_name%' $queryfilter
	";
}
else {
	$queryExcel = "SELECT NO_PENGAJUAN,NAMA_KARYAWAN,COMPANY,DEPARTMENT,POSITION,GRADE,LOCATION,TO_CHAR(TGL_MASUK, 'DD-MON-YYYY') TGL_MASUK ,TO_CHAR(TGL_RESIGN, 'DD-MON-YYYY') TGL_RESIGN,
	TAHUN_LAMA_KERJA || ' Tahun ' || BULAN_LAMA_KERJA || ' Bulan ' || HARI_LAMA_KERJA || ' Hari' LAMA_KERJA, KETERANGAN, TO_CHAR(CREATED_DATE, 'DD-MON-YYYY') TGL_PENGAJUAN,
	MANAGER, APPROVAL_MANAGER, HRD_MGR, APPROVAL_MANAGER_HRD, PIC_DISAPPROVE, KETERANGAN_DISAPPROVE
	FROM MJ.MJ_T_RESIGN WHERE NAMA_KARYAWAN LIKE '%$emp_name%' $queryfilter";
}


$resultExcel = oci_parse($con,$queryExcel);
oci_execute($resultExcel);
while($rowExcel = oci_fetch_row($resultExcel))
{
		$vNopengajuan 	 = $rowExcel[0];
		$vPemohon 		 = $rowExcel[1];
		$vCompany 		 = $rowExcel[2];
		$vDepartemen 	 = $rowExcel[3];
		$vPosition		 = $rowExcel[4];
		$vGrade 		 = $rowExcel[5];
		$vLocation 		 = $rowExcel[6];
		$vTglMasuk 		 = $rowExcel[7];
		$vTglResign 	 = $rowExcel[8];
		$vLamaKerja 	 = $rowExcel[9];
		$vKeterangan  	 = $rowExcel[10];
		$vTglPengajuan	 = $rowExcel[11];
		$vManager 		 = $rowExcel[12];
		$vAppManager 	 = $rowExcel[13];
		$vHrdMgr         = $rowExcel[14];
		$vAppHrd         = $rowExcel[15];
		$vPicDisapproved = $rowExcel[16];
		$vKetDisapproved = $rowExcel[17];
		
		$countHeader++;
		
		$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $xlsRow, $countHeader)
						->setCellValue('B' . $xlsRow, $vNopengajuan)
						->setCellValue('C' . $xlsRow, $vPemohon)
						->setCellValue('D' . $xlsRow, $vCompany)
						->setCellValue('E' . $xlsRow, $vDepartemen)
						->setCellValue('F' . $xlsRow, $vPosition)
						->setCellValue('G' . $xlsRow, $vGrade)
						->setCellValue('H' . $xlsRow, $vLocation)
						->setCellValue('I' . $xlsRow, $vTglMasuk)
						->setCellValue('J' . $xlsRow, $vTglResign)
						->setCellValue('K' . $xlsRow, $vLamaKerja)
						->setCellValue('L' . $xlsRow, $vKeterangan)
						->setCellValue('M' . $xlsRow, $vTglPengajuan)
						->setCellValue('N' . $xlsRow, $vManager)
						->setCellValue('O' . $xlsRow, $vAppManager)
						->setCellValue('P' . $xlsRow, $vHrdMgr)
						->setCellValue('Q' . $xlsRow, $vAppHrd)
						->setCellValue('R' . $xlsRow, $vPicDisapproved)
						->setCellValue('S' . $xlsRow, $vKetDisapproved);
		$xlsRow++;
}
						
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>