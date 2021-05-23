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
if (isset($_GET['periodegaji']))
{	
	//$revisiKe = trim($_GET['revisi']);
	$periodegaji = $_GET['periodegaji'];
	$periode_start = $_GET['periode_start'];
	$periode_end = $_GET['periode_end'];
	$bulan = $_GET['bulan'];
	$tahun = $_GET['tahun'];
	$company = $_GET['company'];
	$plant = $_GET['plant'];
	//$dept = $_GET['dept'];
	$grade = $_GET['grade'];
	$revisiKe = $_GET['revisi'];
	
	if($company != 'All'){
		$queryfilter .= " AND PAF.ORGANIZATION_ID = $company";
	}
	if($plant != ''){
		$queryfilter .= " AND HL.LOCATION_ID = $plant";
	}
	// if($dept != ''){
		// $queryfilter .= " AND PJ.JOB_ID = $dept";
	// }
	if($grade != ''){
		$queryfilter .= " AND PG.GRADE_ID = $grade";
	}
	
	if($periodegaji == 'BULANAN'){
		if($bulan == 'January'){
			$bulanAngka = '01';
			$bulanBesok = '02';
		} elseif ($bulan == 'February'){
			$bulanAngka = '02';
			$bulanBesok = '03';
		} elseif ($bulan == 'March'){
			$bulanAngka = '03';
			$bulanBesok = '04';
		} elseif ($bulan == 'April'){
			$bulanAngka = '04';
			$bulanBesok = '05';
		} elseif ($bulan == 'May'){
			$bulanAngka = '05';
			$bulanBesok = '06';
		} elseif ($bulan == 'June'){
			$bulanAngka = '06';
			$bulanBesok = '07';
		} elseif ($bulan == 'July'){
			$bulanAngka = '07';
			$bulanBesok = '08';
		} elseif ($bulan == 'August'){
			$bulanAngka = '08';
			$bulanBesok = '09';
		} elseif ($bulan == 'September'){
			$bulanAngka = '09';
			$bulanBesok = '10';
		} elseif ($bulan == 'October'){
			$bulanAngka = '10';
			$bulanBesok = '11';
		} elseif ($bulan == 'November'){
			$bulanAngka = '11';
			$bulanBesok = '12';
		} else {
			$bulanAngka = '12';
			$bulanBesok = '01';
		}
		$queryfilter .= " and bulan = $bulanAngka and tahun = $tahun";
		//Set Periode Gajian
		$queryPeriode = "SELECT TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1), 'YYYY-MM-DD') PERIODE1 
		, TO_CHAR(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), 'YYYY-MM-DD') PERIODE2
		FROM DUAL ";
		//echo $queryJumHari;
		$resultPeriode = oci_parse($con,$queryPeriode);
		oci_execute($resultPeriode);
		$rowPeriode = oci_fetch_row($resultPeriode);
		$vPeriode1 = $rowPeriode[0]; 
		$vPeriode2 = $rowPeriode[1];
	}else{
		$vPeriode1 = $periode_start; 
		$vPeriode2 = $periode_end;
		$queryfilter .= " and TO_CHAR(mjgp.periode_start, 'YYYY-MM-DD') = '$periode_start' and TO_CHAR(mjgp.periode_end, 'YYYY-MM-DD') = '$periode_end'";
	}	
	
	$queryfilter .= " and mjgp.revisi = $revisiKe";
}
$namaFile = 'List_Gaji.xls';

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

//Untuk menghitung hari aktif pada periode tersebut
$vHariAktif=0;
$queryAktif = "SELECT ((SELECT COUNT(*) FROM 
(SELECT LEVEL AS DNUM FROM DUAL
CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
WHERE TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
(SELECT COUNT(-1)
FROM APPS.HXT_HOLIDAY_DAYS A
, APPS.HXT_HOLIDAY_CALENDARS B
WHERE A.HCL_ID=B.ID 
AND B.EFFECTIVE_END_DATE>SYSDATE 
AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1), 'YYYY-MM-DD') AND '$vPeriode2'
AND TRIM(TO_CHAR(HOLIDAY_DATE, 'DAY')) <> 'SUNDAY')) TES
FROM DUAL";
//echo $queryJumHari;
$resultAktif = oci_parse($con,$queryAktif);
oci_execute($resultAktif);
while($rowAktif = oci_fetch_row($resultAktif))
{
	$vHariAktif = $rowAktif[0]; 
}
		

// Add some data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Report Rincian List Gaji Karyawan Plant')
            ->setCellValue('A2', 'Jumlah Hari Aktif : ' . $vHariAktif)
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'NIK')
            ->setCellValue('C3', 'No Face Attd')
            ->setCellValue('D3', 'Nama Karyawan')
            ->setCellValue('E3', 'Departemen')
            ->setCellValue('F3', 'Jabatan')
            ->setCellValue('G3', 'Lokasi / Plant')
            ->setCellValue('H3', 'Hari Masuk')
            ->setCellValue('I3', 'Hari Sakit')
            ->setCellValue('J3', 'Hari Cuti')
            ->setCellValue('K3', 'Hari Ijin')
            ->setCellValue('L3', 'Hari Alpha')
            ->setCellValue('M3', 'Terlambat 1')
            ->setCellValue('N3', 'Terlambat 2')
            ->setCellValue('O3', 'Terlambat 3')
            ->setCellValue('P3', 'Terlambat 4')
            ->setCellValue('Q3', 'Gaji Harian')
            ->setCellValue('R3', 'Gaji Mingguan')
            ->setCellValue('S3', 'Uang Makan')
            ->setCellValue('T3', 'Lembur')
            ->setCellValue('U3', 'Premi')
            ->setCellValue('V3', 'Tunj Hari Libur')
            ->setCellValue('W3', 'Tunj Hari Kerja')
            ->setCellValue('X3', 'Tunj Tidak Istirahat')
            ->setCellValue('Y3', 'Tunj Masuk Awal')
            ->setCellValue('Z3', 'Tunj Jabatan')
            ->setCellValue('AA3', 'BPJS Kesehatan')
            ->setCellValue('AB3', 'BPJS TK')
            ->setCellValue('AC3', 'Pinjaman')
            ->setCellValue('AD3', 'BS')
            ->setCellValue('AE3', 'Absen')
            ->setCellValue('AF3', 'Telat')
            ->setCellValue('AG3', 'Total Gaji')
            ->setCellValue('AH3', 'Revisi Gaji')
            ->setCellValue('AI3', 'Total diTransfer')
            ->setCellValue('AJ3', 'Transfer Tunai');

$xlsRow = 3;
$countHeader = 0;


	//Query Gaji Karyawan	
$queryPerson = "select DISTINCT mjgp.id, PPF.PERSON_ID, PPF.employee_number, NVL(PPF.honors, 0) id_finger, PPF.FULL_NAME
--, HOU.NAME org
, REGEXP_SUBSTR(pj.name, '[^.]+', 1, 2) as dept, REGEXP_SUBSTR(pps.name, '[^.]+', 1, 3) as jabatan, HL.LOCATION_code
--, PG.name grade
, mjgp.HARI_MASUK, mjgp.HARI_SAKIT, mjgp.HARI_CUTI, mjgp.HARI_IJIN, mjgp.HARI_ALPHA
, mjgp.TERLAMBAT1, mjgp.TERLAMBAT2, mjgp.TERLAMBAT3, mjgp.TERLAMBAT4, mjgp.PINJAMAN, MJGP.BS
, MJGP.ABSEN, MJGP.TELAT, MJGP.PENDING, MJGP.TOTAL_GAJI, DECODE(MJGP.TRANS_TUNAI, 0, 'TRANSFER', 'TUNAI') TRANSFER
from mjhr.mj_m_gaji_plant mjgp
    inner join APPS.PER_PEOPLE_F PPF on mjgp.person_id = ppf.person_id
    AND (TRUNC(SYSDATE) BETWEEN PPF.EFFECTIVE_START_DATE AND PPF.EFFECTIVE_END_DATE) AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y'
    inner JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
    AND (TRUNC(SYSDATE) BETWEEN PAF.EFFECTIVE_START_DATE AND PAF.EFFECTIVE_END_DATE) AND PAF.PRIMARY_FLAG='Y'
inner join APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
inner join APPS.HR_LOCATIONS HL ON PAF.location_id = hl.location_id
INNER JOIN APPS.per_positions pps ON paf.position_id = pps.position_id
INNER JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
inner join APPS.HR_ORGANIZATION_UNITS HOU on PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID  
where mjgp.periode_gaji = '$periodegaji' 
$queryfilter
order by mjgp.id asc";
	//echo $queryGaji;
$resultPerson = oci_parse($con,$queryPerson);
oci_execute($resultPerson);
while($row = oci_fetch_row($resultPerson)){
	
	$hdid = $row[0];
	$vPersonId = $row[1];
	$vEmpNumber = $row[2];
	$vIdFinger = $row[3];
	$vFullName = str_replace("'", "''", $row[4]);
	//$vOrg = $row[4];
	$vDept = $row[5];
	$vJabatan = $row[6];
	$vLokasi = $row[7];
	$vJumMasuk = $row[8];
	$vHariSakit = $row[9];
	$vHariCuti = $row[10];
	$vHariIjin = $row[11];
	$vHariAlpha = $row[12];
	$vTerlambat1 = $row[13];
	$vTerlambat2 = $row[14];
	$vTerlambat3 = $row[15];
	$vTerlambat4 = $row[16];
	$vPotongan = $row[17];
	$vBS = $row[18];
	$absen = $row[19];
	$telat = $row[20];
	$vPending = $row[21];
	$vTotGajiTransfer = $row[22];
	$vTransTunai = $row[23];
	//$vGrade = $row[8];
	//$hireDate = $row[9];
	//$vPotongan = $row[10];
	//$vBS = $row[11];
	//$vTglResign = $row[12];
	//$vNoReg = $row[13];
	//$vAtasNama = $row[14];
	
	//echo $vPersonId;exit;
	$gajiMingguan = 0;
	$gajiHarian = 0;
	$totalUangLembur = 0;
	$tunMasukAwal = 0;
	$uangMakan = 0;
	$tunHariLibur = 0;
	$tunHariKerja = 0;
	$premi = 0;
	$tunJabatan = 0;
	$tunTidakIstirahat = 0;
	$BPJSKS = 0;
	$BPJSTK = 0;
	$revisi = 0;
	
	//Query Perhitungan Element Gaji (Detail)
	$queryGaji = "select mjgp.person_id, egm.nama_element, egm.komponen, mjgpd.nominal from mjhr.mj_m_gaji_plant mjgp
    inner join mjhr.mj_m_gaji_plant_dt mjgpd on mjgp.id = mjgpd.hdid
    INNER JOIN MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM ON mjgpd.ELEMENT_gaji = EGM.ID
    where mjgp.id = ".$hdid."
	ORDER BY EGM.NAMA_ELEMENT";
		//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($rowGaji = oci_fetch_row($resultGaji)){
		$nominal = 0;
		//$id_element = $rowGaji[0];
		$element = $rowGaji[1];
		$komponen = $rowGaji[2];
		$value = $rowGaji[3];
		
		switch($element){
			case 'E_GAJI_MINGGUAN':
				$gajiMingguan = $value;
				break;
			case 'E_GAJI_HARIAN':
				$gajiHarian = $value;
				break;
			case 'E_LEMBUR':
				$totalUangLembur = $value;
				break;
			case 'E_TUNJANGAN_MASUK_AWAL':
				$tunMasukAwal = $value;
				break;
			case 'E_UANG_MAKAN':
				$uangMakan = $value;
				break;
			case 'E_TUNJANGAN_HARI_LIBUR':
				$tunHariLibur = $value;
				break;
			case 'E_TUNJANGAN_HARI_KERJA':
				$tunHariKerja = $value;
				break;
			case 'E_PREMI':
				$premi = $value;
				break;
			case 'E_BPJS_KESEHATAN':
				$BPJSKS = $value;
				break;
			case 'E_BPJS_KETENAGAKERJAAN':
				$BPJSTK = $value;
				break;
			case 'E_REVISI':
				$revisi = $komponen.$value;
				break;
			case 'E_TUNJANGAN_JABATAN':
				$tunJabatan = $value;
				break;
			case 'E_TUNJANGAN_TIDAK_ISTIRAHAT':
				$tunTidakIstirahat = 0;//$value;
				break;
		}
	}
	// echo 
	// 'eGajiHarian =  '.$eGajiHarian.'<br>'.
	// 'gajiMingguan = '.$gajiMingguan.'<br>'.
	// 'gajiHarian =  '.$gajiHarian.'<br>'.
	// 'totalUangLembur = '.$totalUangLembur.'<br>'.
	// 'tunMasukAwal = '.$tunMasukAwal.'<br>'.
	// 'uangMakan =  '.$uangMakan.'<br>'.
	// 'tunHariLibur =  '.$tunHariLibur.'<br>'.
	// 'tunHariKerja =  '.$tunHariKerja.'<br>'.
	// 'premi =  '.$premi.'<br>'.
	// 'tunJabatan =  '.$tunJabatan.'<br>'.
	// 'tunTidakIstirahat =  '.$tunTidakIstirahat.'<br>'.
	// 'BPJSKS =  '.$BPJSKS.'<br>'.
	// 'BPJSTK =  '.$BPJSTK.'<br>'.
	// 'revisi =  '.$revisi.'<br>';exit;
	//echo $gajiHarian.' - '.$uangLembur;exit;
	$vTotGajiPlus = $gajiMingguan + $gajiHarian + $totalUangLembur + $tunMasukAwal + $uangMakan + $tunHariLibur + $tunHariKerja + $premi + $tunJabatan + $tunTidakIstirahat;
	$vTotGajiMinus = $BPJSKS - $BPJSTK;
	$vTotGajiSkr = ($gajiMingguan+$gajiHarian+$totalUangLembur+$tunMasukAwal+$uangMakan+$tunHariLibur+$tunHariKerja+$premi+$tunJabatan+$tunTidakIstirahat)-($BPJSKS-$BPJSTK);
	//$vTotGajiTransfer = $vTotGajiSkr + ($revisi);
	
	$countHeader++;
	$xlsRow++;
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $xlsRow, $countHeader)
				->setCellValue('B' . $xlsRow, $vEmpNumber)
				->setCellValue('C' . $xlsRow, $vIdFinger)
				->setCellValue('D' . $xlsRow, $vFullName)
				->setCellValue('E' . $xlsRow, $vDept)
				->setCellValue('F' . $xlsRow, $vJabatan)
				->setCellValue('G' . $xlsRow, $vLokasi)
				->setCellValue('H' . $xlsRow, $vJumMasuk)
				->setCellValue('I' . $xlsRow, $vHariSakit)
				->setCellValue('J' . $xlsRow, $vHariCuti)
				->setCellValue('K' . $xlsRow, $vHariIjin)
				->setCellValue('L' . $xlsRow, $vHariAlpha)
				->setCellValue('M' . $xlsRow, $vTerlambat1)
				->setCellValue('N' . $xlsRow, $vTerlambat2)
				->setCellValue('O' . $xlsRow, $vTerlambat3)
				->setCellValue('P' . $xlsRow, $vTerlambat4)
				->setCellValue('Q' . $xlsRow, $gajiHarian)
				->setCellValue('R' . $xlsRow, $gajiMingguan)
				->setCellValue('S' . $xlsRow, $uangMakan)
				->setCellValue('T' . $xlsRow, $totalUangLembur)
				->setCellValue('U' . $xlsRow, $premi)
				->setCellValue('V' . $xlsRow, $tunHariLibur)
				->setCellValue('W' . $xlsRow, $tunHariKerja)
				->setCellValue('X' . $xlsRow, $tunTidakIstirahat)
				->setCellValue('Y' . $xlsRow, $tunMasukAwal)
				->setCellValue('Z' . $xlsRow, $tunJabatan)
				->setCellValue('AA' . $xlsRow, $BPJSKS)
				->setCellValue('AB' . $xlsRow, $BPJSTK)
				->setCellValue('AC' . $xlsRow, $vPotongan)
				->setCellValue('AD' . $xlsRow, $vBS)
				->setCellValue('AE' . $xlsRow, $absen)
				->setCellValue('AF' . $xlsRow, $telat)
				->setCellValue('AG' . $xlsRow, $vTotGajiSkr)
				->setCellValue('AH' . $xlsRow, $revisi)
				->setCellValue('AI' . $xlsRow, $vTotGajiTransfer)
				->setCellValue('AJ' . $xlsRow, $vTransTunai);
}
	
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>