<?php
include '../../main/koneksi.php';


$nama=""; 
$plant=""; 
$periode=""; 
$vPeriode1="";
$vPeriode2="";
$queryfilter=""; 
$queryfilterDetail ="";
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 
$plant=""; 
$bulanAngka="";
$bulanBesok="";
$queryplant="";
$queryfilter=""; 

if (isset($_GET['tglfrom']) || isset($_GET['tglto']))
{	
	$hari="";
	$bulan="";
	$tahun=""; 
	$posDocText=""; 
	$queryfilter=""; 
	$queryfilterRange= "";
	$tglskr=date('Y-m-d'); 
	
	$namaKaryawan = $_GET['pemohon'];
	$noDok = $_GET['noBS'];
	$tipeBS = $_GET['tipeBS'];
	$posDoc = $_GET['posDoc'];
	$perusahaan = $_GET['perusahaan'];
	$dept = $_GET['dept'];
	$plant = $_GET['plant'];
	$status = $_GET['status'];
	$tahunbaru=substr($tglskr, 0, 2);
	$tglfrom = $_GET['tglfrom'];
	$hari=substr($tglfrom, 0, 2);
	$bulan=substr($tglfrom, 3, 2);
	$tahun=substr($tglfrom, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglfrom = $tahun . "-" . $bulan . "-" . $hari;
	$tglto = $_GET['tglto'];
	
	$hari=substr($tglto, 0, 2);
	$bulan=substr($tglto, 3, 2);
	$tahun=substr($tglto, 6, 2);
	if (strlen($tahun)==2)
	{
		$tahun = $tahunbaru . "" . $tahun;
	}
	$tglto = $tahun . "-" . $bulan . "-" . $hari;
	$queryfilter .=" AND TO_CHAR(BS.CREATED_DATE, 'YYYY-MM-DD') >= '$tglfrom' AND TO_CHAR(BS.CREATED_DATE, 'YYYY-MM-DD') <= '$tglto' ";
	
	
	$tgljtfrom = $_GET['tgljtfrom'];
	if($tgljtfrom != '' ){
		$hari=substr($tgljtfrom, 0, 2);
		$bulan=substr($tgljtfrom, 3, 2);
		$tahun=substr($tgljtfrom, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tgljtfrom = $tahun . "-" . $bulan . "-" . $hari;
	}else{
		$tgljtfrom = '2000-01-01';
		$tgljtfromjudul = '';
	}
	$tgljtto = $_GET['tgljtto'];
	if($tgljtto != '' ){
		$hari=substr($tgljtto, 0, 2);
		$bulan=substr($tgljtto, 3, 2);
		$tahun=substr($tgljtto, 6, 2);
		if (strlen($tahun)==2)
		{
			$tahun = $tahunbaru . "" . $tahun;
		}
		$tgljtto = $tahun . "-" . $bulan . "-" . $hari;
	}else{
		$tgljtto = '2030-12-31';
		$tgljttojudul = '';
	}
	$queryfilter .=" AND TO_CHAR(BS.TGL_JT, 'YYYY-MM-DD') >= '$tgljtfrom' AND TO_CHAR(BS.TGL_JT, 'YYYY-MM-DD') <= '$tgljtto' ";
	
	
	if ($namaKaryawan!=''){
		$queryAssign = "SELECT ASSIGNMENT_ID 
		FROM APPS.PER_ASSIGNMENTS_F
		WHERE PERSON_ID = $namaKaryawan AND EFFECTIVE_END_DATE > SYSDATE AND PRIMARY_FLAG='Y'";
		$resultAssign = oci_parse($con,$queryAssign);
		oci_execute($resultAssign);
		$rowAssign = oci_fetch_row($resultAssign);
		$assignment_id = $rowAssign[0]; 

		$querypemohon = "SELECT PPF.FULL_NAME 
		FROM APPS.PER_PEOPLE_F PPF
		WHERE PERSON_ID = $namaKaryawan AND EFFECTIVE_END_DATE > SYSDATE";
		$resultpemohon = oci_parse($con,$querypemohon);
		oci_execute($resultpemohon);
		$rowpemohon = oci_fetch_row($resultpemohon);
		$pemohonnama = $rowpemohon[0]; 

		$queryfilter .=" AND BS.ASSIGNMENT_ID = $assignment_id ";
	} 
	else {
		$pemohonnama = '';
	}
	if ($noDok!=''){
		$queryfilter .=" AND BS.NO_BS LIKE '%$noDok%' ";
	}
	if ($tipeBS!='- Pilih -'){
		$queryfilter .=" AND BS.TIPE = '$tipeBS' ";
	}
	if ($perusahaan!=''){
		$queryperusahaan = "SELECT HOU.NAME
		FROM HR_ORGANIZATION_UNITS HOU
		WHERE HOU.ORGANIZATION_ID = $perusahaan";
		$resultperusahaan = oci_parse($con,$queryperusahaan);
		oci_execute($resultperusahaan);
		$rowperusahaan = oci_fetch_row($resultperusahaan);
		$perusahaannama = $rowperusahaan[0]; 

		$queryfilter .=" AND BS.PERUSAHAAN_BS = $perusahaan ";
	}
	else {
		$perusahaannama = '';
	}
	if ($dept!=''){
		$querydept = "SELECT PJ.NAME
		FROM PER_JOBS PJ
		WHERE PJ.JOB_ID = $dept";
		$resultdept = oci_parse($con,$querydept);
		oci_execute($resultdept);
		$rowdept = oci_fetch_row($resultdept);
		$deptnama = $rowdept[0]; 

		$queryfilter .=" AND PAF.JOB_ID = $dept ";
	}
	else {
		$deptnama = '';
	}
	if ($plant!=''){
		$queryplanta = "SELECT HL.LOCATION_CODE
		FROM HR_LOCATIONS HL
		WHERE HL.LOCATION_ID = $plant";
		$resultplanta = oci_parse($con,$queryplanta);
		oci_execute($resultplanta);
		$rowplanta = oci_fetch_row($resultplanta);
		$planta = $rowplanta[0]; 

		$queryfilter .=" AND PAF.LOCATION_ID = $plant ";
	}
	else {
		$planta = '';
	}
	if ($status!='- Pilih -'){
		$queryfilter .=" AND upper(BS.STATUS) = '$status' ";
	}
	
	if ($posDoc!='- Pilih -'){
		if($posDoc == 0){
			$posDocText = 'Pengajuan Karyawan';
		} else if($posDoc == 1){
			$posDocText = 'Pengajuan HRD';
		} else if($posDoc == 2){
			$posDocText = 'APP Penanggung Jawab / MGR Department';
		} else if($posDoc == 3){
			$posDocText = 'App Checker';
		} else if($posDoc == 4){
			$posDocText = 'App MGR HRD';
		} else if($posDoc == 5){
			$posDocText = 'App MGR FIN';
		} else if($posDoc == 6){
			$posDocText = 'Validasi Kasir';
		} else{
			$posDocText = ' - ';
		}
		
		$queryfilter .=" AND BS.TINGKAT = '$posDoc' ";
	}
} 
$namaFile = 'RekapBS_Nol.xls';

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
            ->setCellValue('A1', 'Rekap Pengenolan Bon Sementara (BS)')
			->setCellValue('A3', 'Periode : ' . $tglfrom .'-'. $tglto)
			->setCellValue('A4', 'Tgl Jatuh Tempo : ' . $tgljtfromjudul .'-'. $tgljttojudul)
            ->setCellValue('A5', 'Pemohon : ' . $pemohonnama)
            ->setCellValue('A6', 'Nomor BS : '. $noDok)
            ->setCellValue('A7', 'Tipe BS : '. $tipeBS)
            ->setCellValue('A8', 'Perusahaan BS : '. $perusahaannama)
            ->setCellValue('A9', 'Departemen : '. $deptnama)
            ->setCellValue('A10', 'Status : '. $status)
            ->setCellValue('A11', 'Posisi Document : '. $posDocText)
			        
            //->setCellValue('A2', 'Jumlah Hari Aktif : ' . $vHariAktif)
            ->setCellValue('A13', 'No.')
            ->setCellValue('B13', 'No. BS')
            ->setCellValue('C13', 'Pemohon')
            ->setCellValue('D13', 'Perusahaan')
            ->setCellValue('E13', 'Perusahaan BS')
            ->setCellValue('F13', 'Departemen')
            ->setCellValue('G13', 'Jabatan')
            ->setCellValue('H13', 'Grade')
            ->setCellValue('I13', 'Lokasi')
            ->setCellValue('J13', 'Tipe BS')
            ->setCellValue('K13', 'Jatuh Tempo')
            ->setCellValue('L13', 'Tgl Pencairan')
            ->setCellValue('M13', 'Tgl Close')
            ->setCellValue('N13', 'Status')
            ->setCellValue('O13', 'Keterangan')
            ->setCellValue('P13', 'Nominal BS')
            ->setCellValue('Q13', 'Outstanding BS')
            ->setCellValue('R13', 'No CM')
            ->setCellValue('S13', 'Keterangan Pengenolan')
            ->setCellValue('T13', 'Tipe Pengenolan')
            ->setCellValue('U13', 'Nominal Pengenolan')
            ->setCellValue('V13', 'Account Bank Penerima')
            ->setCellValue('W13', 'Tgl Pengenolan')
            ->setCellValue('X13', 'Tgl Action');

$xlsRow = 13;
$countHeader = 0;


	$queryGaji = "SELECT DISTINCT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
--, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
--, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
, PJ.NAME
, POS.NAME
, HL.LOCATION_CODE PLANT
, PG.NAME GRADE
, BS.NOMINAL
, BS.KETERANGAN
, BS.STATUS
, BS.TINGKAT
, CASE WHEN BS.STATUS = 'PROCESS' THEN '-'
    ELSE PPF3.FULL_NAME END AS APP_TERAKHIR
, NVL(BS.LAST_UPDATED_DATE, BS.CREATED_DATE) AS TGL_TERAKHIR
, CASE BS.STATUS
    WHEN 'Disapproved' THEN (SELECT MTA.KETERANGAN
    FROM MJ.MJ_T_APPROVAL MTA 
    WHERE MTA.TRANSAKSI_ID=BS.ID AND MTA.APP_ID= 1 AND MTA.TRANSAKSI_KODE='BON'
    AND MTA.ID = (SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL WHERE TRANSAKSI_ID=BS.ID AND APP_ID= 1 AND TRANSAKSI_KODE='BON')) ELSE '-' 
    END AS KET_DISAPP
, CASE WHEN BS.TINGKAT = 0 AND BS.STATUS = 'PROCESS' THEN PPF2.FULL_NAME
    WHEN (BS.TINGKAT = 4 or (BS.TINGKAT = 1 AND BS.STATUS = 'PROCESS')) THEN 'MGR FINANCE' 
    WHEN BS.TINGKAT = 2 THEN 'Tim Checker' 
    WHEN BS.TINGKAT = 3 THEN 'MGR HRD' 
    WHEN BS.TINGKAT = 5 AND BS.STATUS = 'Approved' and BS.TGL_PENCAIRAN IS NULL THEN 'Validasi Kasir' 
    ELSE '-' END AS NEXT_APPR
    ,BS.TIPE
    ,BS.TGL_PENCAIRAN
    ,
         CASE
            WHEN BSD.TIPE_EXPENSE IN ('CLOSE','POTONGAN') AND BS.ID = BSD.BS_HD_ID
            THEN
               TGL_EXPENSE
            WHEN BSD.TIPE_EXPENSE = 'EXPENSE CLAIM' AND BS.ID = BSD.BS_HD_ID AND  BS.NOMINAL - (SELECT SUM(BSD.NOMINAL) FROM MJ.MJ_T_BS_DT BSD WHERE BSD.BS_HD_ID = BS.ID) = 0
            THEN
               (SELECT MAX(BSD.TGL_EXPENSE) FROM MJ.MJ_T_BS_DT BSD WHERE BSD.BS_HD_ID = BS.ID)
            ELSE
               TO_DATE('','DD/MM/YY')
         END AS TANGGAL_CLOSE
    ,
         CASE
            WHEN BSD.TIPE_EXPENSE IN ('CLOSE','POTONGAN') AND BS.ID = BSD.BS_HD_ID
            THEN
               0
            WHEN BSD.TIPE_EXPENSE = 'EXPENSE CLAIM' AND BS.ID = BSD.BS_HD_ID
            THEN
               BS.NOMINAL - (SELECT SUM(BSD.NOMINAL) FROM MJ.MJ_T_BS_DT BSD WHERE BSD.BS_HD_ID = BS.ID)
            ELSE
               BS.NOMINAL
         END AS NOMINAL_OUTSTANDING
    ,BS.TGL_JT
    ,HOU2.NAME PERUSAHAAN_BS
    , BSD.NO_BBK
    , BSD.KETERANGAN
    , BSD.TIPE_EXPENSE
    , BSD.NOMINAL
    , DECODE(CBA.BANK_ACCOUNT_NUM, '', '', CBA.BANK_ACCOUNT_NUM|| ' - ' ||BB.BANK_NAME|| ' a/n ' ||CBA.BANK_ACCOUNT_NAME) BANK_PENERIMA
    , TO_CHAR(BSD.TGL_EXPENSE, 'DD-MON-YYYY')
    , TO_CHAR(BSD.CREATED_DATE, 'DD-MON-YYYY')
	, CASE
            WHEN BS.TINGKAT = 0 THEN 'Pengajuan Karyawan'
            WHEN BS.TINGKAT = 1 THEN 'Pengajuan HRD'
            WHEN BS.TINGKAT = 2 THEN 'APP Penanggung Jawab / MGR Department'
            WHEN BS.TINGKAT = 3 THEN 'App Checker'
            WHEN BS.TINGKAT = 4 THEN 'App MGR HRD'
            WHEN BS.TINGKAT = 5 THEN 'App MGR FIN'
            WHEN BS.TINGKAT = 6 THEN 'Validasi Kasir'
			ELSE '-'
         END AS POS_DOC
FROM MJ.MJ_T_BS BS
INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
LEFT JOIN MJ.MJ_T_BS_DT BSD ON BS.ID = BSD.BS_HD_ID 
INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
INNER JOIN PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
INNER JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
INNER JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
INNER JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
INNER JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
LEFT JOIN PER_PEOPLE_F PPF3 ON BS.LAST_UPDATED_BY = PPF3.PERSON_ID
LEFT JOIN MJ.MJ_T_APPROVAL MTA ON BS.ID = MTA.TRANSAKSI_ID AND MTA.TRANSAKSI_KODE = 'BON' AND BS.TINGKAT = MTA.TINGKAT
LEFT JOIN APPS.CE_BANK_ACCOUNTS CBA ON BSD.BANK_ACCOUNT_ID = CBA.BANK_ACCOUNT_ID
LEFT JOIN APPS.CEFV_BANK_BRANCHES BB ON CBA.BANK_BRANCH_ID = BB.BANK_BRANCH_ID
WHERE 1=1 AND BS.AKTIF != 'N' --AND BSD.STATUS != 'N'
	$queryfilter
	ORDER BY BS.ID";
	//echo $queryGaji; //exit;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$TransID=$row[0];
		$record = array();
		$hdid=$row[0];
		$nobs=$row[1];
		$pemohon=$row[2];
		$perusahaan=$row[3];
		$dept=$row[4];
		$jabatan=$row[5];
		$plant=$row[6];
		$grade=$row[7];
		$nominal=$row[8];
		$keterangan=$row[9];
		$status=$row[10];
		$tingkat=$row[11];
		$app_terakhir=$row[12];
		$tgl_terakhir=$row[13];
		$ket_dis=$row[14];
		$next_app=$row[15];
		$tipe=$row[16];
		$tanggal_pencairan=$row[17];
		$tanggal_close=$row[18];
		$nominal_outstanding=$row[19];
		$tgl_jatuh_tempo=$row[20];
		$perusahaan_bs=$row[21];
		$no_cm=$row[22];
		$ket_nol=$row[23];
		$tipe_nol=$row[24];
		$nominal_nol=$row[25];
		$bank_penerima=$row[26];
		$tgl_nol=$row[27];
		$tgl_action_nol=$row[28];
		$posdoc=$row[29];
		$countHeader++;
		$xlsRow++;
		
		$objPHPExcel->getActiveSheet()
			->getStyle('P')
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()
			->getStyle('Q')
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()
			->getStyle('U')
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$objPHPExcel->getActiveSheet()
			->getStyle('A1')
			->getAlignment()
			->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
			
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A' . $xlsRow, $countHeader)
					->setCellValue('B' . $xlsRow, $nobs)
					->setCellValue('C' . $xlsRow, $pemohon)
					->setCellValue('D' . $xlsRow, $perusahaan)
					->setCellValue('E' . $xlsRow, $perusahaan_bs)
					->setCellValue('F' . $xlsRow, $dept)
					->setCellValue('G' . $xlsRow, $jabatan)
					->setCellValue('H' . $xlsRow, $grade)
					->setCellValue('I' . $xlsRow, $plant)
					->setCellValue('J' . $xlsRow, $tipe)
					->setCellValue('K' . $xlsRow, $tgl_jatuh_tempo)
					->setCellValue('L' . $xlsRow, $tanggal_pencairan)
					->setCellValue('M' . $xlsRow, $tanggal_close)
					->setCellValue('N' . $xlsRow, $status)
					->setCellValue('O' . $xlsRow, $keterangan)
					->setCellValue('P' . $xlsRow, $nominal)
					->setCellValue('Q' . $xlsRow, $nominal_outstanding)
					->setCellValue('R' . $xlsRow, $no_cm)
					->setCellValue('S' . $xlsRow, $ket_nol)
					->setCellValue('T' . $xlsRow, $tipe_nol)
					->setCellValue('U' . $xlsRow, $nominal_nol)
					->setCellValue('V' . $xlsRow, $bank_penerima)
					->setCellValue('W' . $xlsRow, $tgl_nol)
					->setCellValue('X' . $xlsRow, $tgl_action_nol);
	}
	
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>