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
	
}
$namaFile = 'Compare_Gaji.xls';

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
            ->setCellValue('A1', 'Report Compare Gaji Karyawan Plant')
            ->setCellValue('A2', 'Periode : ' . $vPeriode1 ." s/d ". $vPeriode2)
            ->setCellValue('A3', 'Jumlah Hari Aktif : ' . $vHariAktif)
            ->setCellValue('A4', 'No.')
            ->setCellValue('B4', 'NIK')
            ->setCellValue('C4', 'No Face Attd')
            ->setCellValue('D4', 'Nama Karyawan')
            ->setCellValue('E4', 'Perusahaan')
            ->setCellValue('F4', 'Departemen')
            ->setCellValue('G4', 'Jabatan')
            ->setCellValue('H4', 'Lokasi / Plant')
            ->setCellValue('I4', 'Tanggal Masuk')
            ->setCellValue('J4', 'Gaji Harian Rev 0')
            ->setCellValue('K4', 'Gaji Harian Rev 1')
            ->setCellValue('L4', 'Hari Masuk Rev 0')
            ->setCellValue('M4', 'Hari Masuk Rev 1')
            ->setCellValue('N4', 'Hari Sakit Rev 0')
            ->setCellValue('O4', 'Hari Sakit Rev 1')
            ->setCellValue('P4', 'Hari Cuti Rev 0')
            ->setCellValue('Q4', 'Hari Cuti Rev 1')
            ->setCellValue('R4', 'Hari Ijin Rev 0')
            ->setCellValue('S4', 'Hari Ijin Rev 1')
            ->setCellValue('T4', 'Hari Alpha Rev 0')
            ->setCellValue('U4', 'Hari Alpha Rev 1')
            ->setCellValue('V4', 'Terlambat 1 Rev 0')
            ->setCellValue('W4', 'Terlambat 1 Rev 1')
            ->setCellValue('X4', 'Terlambat 2 Rev 0')
            ->setCellValue('Y4', 'Terlambat 2 Rev 1')
            ->setCellValue('Z4', 'Terlambat 3 Rev 0')
            ->setCellValue('AA4', 'Terlambat 3 Rev 1')
            ->setCellValue('AB4', 'Terlambat 4 Rev 0')
            ->setCellValue('AC4', 'Terlambat 4 Rev 1')
            ->setCellValue('AD4', 'Total Gaji Harian Rev 0')
            ->setCellValue('AE4', 'Total Gaji Harian Rev 1')
            ->setCellValue('AF4', 'Gaji Mingguan Rev 0')
            ->setCellValue('AG4', 'Gaji Mingguan Rev 1')
            ->setCellValue('AH4', 'Uang Makan Rev 0')
            ->setCellValue('AI4', 'Uang Makan Rev 1')
            ->setCellValue('AJ4', 'Lembur Rev 0')
            ->setCellValue('AK4', 'Lembur Rev 1')
            ->setCellValue('AL4', 'Premi Rev 0')
            ->setCellValue('AM4', 'Premi Rev 1')
            ->setCellValue('AN4', 'Tunj Hari Libur Rev 0')
            ->setCellValue('AO4', 'Tunj Hari Libur Rev 1')
            ->setCellValue('AP4', 'Tunj Hari Kerja Rev 0')
            ->setCellValue('AQ4', 'Tunj Hari Kerja Rev 1')
            ->setCellValue('AR4', 'Tunj Tidak Istirahat Rev 0')
            ->setCellValue('AS4', 'Tunj Tidak Istirahat Rev 1')
            ->setCellValue('AT4', 'Tunj Masuk Awal Rev 0')
            ->setCellValue('AU4', 'Tunj Masuk Awal Rev 1')
            ->setCellValue('AV4', 'Tunj Jabatan Rev 0')
            ->setCellValue('AW4', 'Tunj Jabatan Rev 1')
            ->setCellValue('AX4', 'BPJS Kesehatan Rev 0')
            ->setCellValue('AY4', 'BPJS Kesehatan Rev 1')
            ->setCellValue('AZ4', 'BPJS TK Rev 0')
            ->setCellValue('BA4', 'BPJS TK Rev 1')
            ->setCellValue('BB4', 'Pinjaman Rev 0')
            ->setCellValue('BC4', 'Pinjaman Rev 1')
            ->setCellValue('BD4', 'BS Rev 0')
            ->setCellValue('BE4', 'BS Rev 1')
            ->setCellValue('BF4', 'Absen Rev 0')
            ->setCellValue('BG4', 'Absen Rev 1')
            ->setCellValue('BH4', 'Telat Rev 0')
            ->setCellValue('BI4', 'Telat Rev 1')
            ->setCellValue('BJ4', 'Total Gaji Rev 0')
            ->setCellValue('BK4', 'Total Gaji Rev 1')
            ->setCellValue('BL4', 'Revisi Gaji Rev 0')
            ->setCellValue('BM4', 'Revisi Gaji Rev 1')
            ->setCellValue('BN4', 'Total diTransfer Rev 0')
            ->setCellValue('BO4', 'Total diTransfer Rev 1');
            //->setCellValue('BL4', 'Transfer Tunai Rev 0')
            //->setCellValue('BM4', 'Transfer Tunai Rev 1');

$xlsRow = 4;
$countHeader = 0;


	//Query Gaji Karyawan	
$queryPerson = "SELECT DISTINCT NVL(REV0.id,0), NVL(REV1.id,0), PERSON.PERSON_ID, REV0.employee_number, REV0.id_finger, REV0.FULL_NAME
--, HOU.NAME org
, REV0.dept, REV0.jabatan, REV0.LOCATION_code
--, PG.name grade
, NVL(REV0.HARI_MASUK, 0), NVL(REV0.HARI_SAKIT, 0), NVL(REV0.HARI_CUTI, 0), NVL(REV0.HARI_IJIN, 0), NVL(REV0.HARI_ALPHA, 0)
, NVL(REV0.TERLAMBAT1, 0), NVL(REV0.TERLAMBAT2, 0), NVL(REV0.TERLAMBAT3, 0), NVL(REV0.TERLAMBAT4, 0), NVL(REV0.PINJAMAN, 0), NVL(REV0.BS, 0)
, NVL(REV0.ABSEN, 0), NVL(REV0.TELAT, 0), NVL(REV0.PENDING, 0), NVL(REV0.TOTAL_GAJI, 0)
, NVL(REV1.HARI_MASUK, 0), NVL(REV1.HARI_SAKIT, 0), NVL(REV1.HARI_CUTI, 0), NVL(REV1.HARI_IJIN, 0), NVL(REV1.HARI_ALPHA, 0)
, NVL(REV1.TERLAMBAT1, 0), NVL(REV1.TERLAMBAT2, 0), NVL(REV1.TERLAMBAT3, 0), NVL(REV1.TERLAMBAT4, 0), NVL(REV1.PINJAMAN, 0), NVL(REV1.BS, 0)
, NVL(REV1.ABSEN, 0), NVL(REV1.TELAT, 0), NVL(REV1.PENDING, 0), NVL(REV1.TOTAL_GAJI, 0)
, HOU3.NAME, TO_CHAR(PPF3.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS DATE_OF_HIRE
FROM  (
    SELECT DISTINCT PERSON_ID
    FROM MJHR.MJ_M_GAJI_PLANT
    WHERE PERIODE_GAJI = 'MINGGUAN'
) PERSON LEFT JOIN 
(select DISTINCT mjgp.id, PPF.PERSON_ID, PPF.employee_number, NVL(PPF.honors, 0) id_finger, PPF.FULL_NAME
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
where mjgp.revisi = 0 and mjgp.periode_gaji = '$periodegaji'
$queryfilter) REV0 ON REV0.PERSON_ID=PERSON.PERSON_ID
LEFT JOIN 
(select DISTINCT mjgp.id, PPF.PERSON_ID, PPF.employee_number, NVL(PPF.honors, 0) id_finger, PPF.FULL_NAME
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
where mjgp.revisi = 1 and mjgp.periode_gaji = '$periodegaji'
$queryfilter) REV1 ON REV1.PERSON_ID=PERSON.PERSON_ID
inner join APPS.PER_PEOPLE_F PPF3 on PERSON.PERSON_ID = PPF3.PERSON_ID
    AND (TRUNC(SYSDATE) BETWEEN PPF3.EFFECTIVE_START_DATE AND PPF3.EFFECTIVE_END_DATE) AND PPF3.CURRENT_EMPLOYEE_FLAG = 'Y'
inner JOIN APPS.PER_ASSIGNMENTS_F PAF3 ON PAF3.PERSON_ID=PERSON.PERSON_ID
	AND (TRUNC(SYSDATE) BETWEEN PAF3.EFFECTIVE_START_DATE AND PAF3.EFFECTIVE_END_DATE) AND PAF3.PRIMARY_FLAG='Y'
inner join APPS.HR_ORGANIZATION_UNITS HOU3 on PAF3.ORGANIZATION_ID = HOU3.ORGANIZATION_ID  
ORDER BY PERSON.PERSON_ID";
	//echo $queryGaji;
$resultPerson = oci_parse($con,$queryPerson);
oci_execute($resultPerson);
while($row = oci_fetch_row($resultPerson)){
	
	$hdid = $row[0];
	$hdid1 = $row[1];
	$vPersonId = $row[2];
	$vEmpNumber = $row[3];
	$vIdFinger = $row[4];
	$vFullName = str_replace("'", "''", $row[5]);
	//$vOrg = $row[4];
	$vDept = $row[6];
	$vJabatan = $row[7];
	$vLokasi = $row[8];
	$vJumMasuk = $row[9];
	$vHariSakit = $row[10];
	$vHariCuti = $row[11];
	$vHariIjin = $row[12];
	$vHariAlpha = $row[13];
	$vTerlambat1 = $row[14];
	$vTerlambat2 = $row[15];
	$vTerlambat3 = $row[16];
	$vTerlambat4 = $row[17];
	$vPotongan = $row[18];
	$vBS = $row[19];
	$absen = $row[20];
	$telat = $row[21];
	$vPending = $row[22];
	$vTotGajiTransfer = $row[23];
	$vJumMasuk1 = $row[24];
	$vHariSakit1 = $row[25];
	$vHariCuti1 = $row[26];
	$vHariIjin1 = $row[27];
	$vHariAlpha1 = $row[28];
	$vTerlambat11 = $row[29];
	$vTerlambat21 = $row[30];
	$vTerlambat31 = $row[31];
	$vTerlambat41 = $row[32];
	$vPotongan1 = $row[33];
	$vBS1 = $row[34];
	$absen1 = $row[35];
	$telat1 = $row[36];
	$vPending1 = $row[37];
	$vTotGajiTransfer1 = $row[38];
	$vOrg = $row[39];
	$hireDate = $row[40];
	//$vTransTunai1 = $row[23];
	
	//$vGrade = $row[8];
	//$hireDate = $row[9];
	//$vPotongan = $row[10];
	//$vBS = $row[11];
	//$vTglResign = $row[12];
	//$vNoReg = $row[13];
	//$vAtasNama = $row[14];
	
	//echo $vPersonId;exit;
	$gajiMingguan = 0;
	$gajiPerhari = 0;
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
	
	$gajiMingguan1 = 0;
	$gajiPerhari1 = 0;
	$gajiHarian1 = 0;
	$totalUangLembur1 = 0;
	$tunMasukAwal1 = 0;
	$uangMakan1 = 0;
	$tunHariLibur1 = 0;
	$tunHariKerja1 = 0;
	$premi1 = 0;
	$tunJabatan1 = 0;
	$tunTidakIstirahat1 = 0;
	$BPJSKS1 = 0;
	$BPJSTK1 = 0;
	$revisi1 = 0;
	
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
				$gajiPerhari = $value / $vHariAktif;
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
				if($komponen == '-'){
					$revisi = $komponen.$value;
				}else{
					$revisi = $value;
				}
				//$revisi = $komponen.$value;
				break;
			case 'E_TUNJANGAN_JABATAN':
				$tunJabatan = $value;
				break;
			case 'E_TUNJANGAN_TIDAK_ISTIRAHAT':
				$tunTidakIstirahat = 0;//$value;
				break;
		}
	}
	
	$queryGaji = "select mjgp.person_id, egm.nama_element, egm.komponen, mjgpd.nominal from mjhr.mj_m_gaji_plant mjgp
    inner join mjhr.mj_m_gaji_plant_dt mjgpd on mjgp.id = mjgpd.hdid
    INNER JOIN MJ.MJ_M_ELEMENT_GAJI_MINGGUAN EGM ON mjgpd.ELEMENT_gaji = EGM.ID
    where mjgp.id = ".$hdid1."
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
				$gajiMingguan1 = $value;
				break;
			case 'E_GAJI_HARIAN':
				$gajiPerhari1 = $value / $vHariAktif;
				$gajiHarian1 = $value;
				break;
			case 'E_LEMBUR':
				$totalUangLembur1 = $value;
				break;
			case 'E_TUNJANGAN_MASUK_AWAL':
				$tunMasukAwal1 = $value;
				break;
			case 'E_UANG_MAKAN':
				$uangMakan1 = $value;
				break;
			case 'E_TUNJANGAN_HARI_LIBUR':
				$tunHariLibur1 = $value;
				break;
			case 'E_TUNJANGAN_HARI_KERJA':
				$tunHariKerja1 = $value;
				break;
			case 'E_PREMI':
				$premi1 = $value;
				break;
			case 'E_BPJS_KESEHATAN':
				$BPJSKS1 = $value;
				break;
			case 'E_BPJS_KETENAGAKERJAAN':
				$BPJSTK1 = $value;
				break;
			case 'E_REVISI':
				if($komponen == '-'){
					$revisi1 = $komponen.$value;
				}else{
					$revisi1 = $value;
				}
				//$revisi1 = $komponen.$value;
				break;
			case 'E_TUNJANGAN_JABATAN':
				$tunJabatan1 = $value;
				break;
			case 'E_TUNJANGAN_TIDAK_ISTIRAHAT':
				$tunTidakIstirahat1 = 0;//$value;
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
	$vTotGajiSkr1 = ($gajiMingguan1+$gajiHarian1+$totalUangLembur1+$tunMasukAwal1+$uangMakan1+$tunHariLibur1+$tunHariKerja1+$premi1+$tunJabatan1+$tunTidakIstirahat1)-($BPJSKS1-$BPJSTK1);
	//$vTotGajiTransfer1 = $vTotGajiSkr1 + ($revisi1);
	
	$countHeader++;
	$xlsRow++;
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A' . $xlsRow, $countHeader)
				->setCellValue('B' . $xlsRow, $vEmpNumber)
				->setCellValue('C' . $xlsRow, $vIdFinger)
				->setCellValue('D' . $xlsRow, $vFullName)
				->setCellValue('E' . $xlsRow, $vOrg)
				->setCellValue('F' . $xlsRow, $vDept)
				->setCellValue('G' . $xlsRow, $vJabatan)
				->setCellValue('H' . $xlsRow, $vLokasi)
				->setCellValue('I' . $xlsRow, $hireDate)
				->setCellValue('J' . $xlsRow, $gajiPerhari)
				->setCellValue('K' . $xlsRow, $gajiPerhari1)
				->setCellValue('L' . $xlsRow, $vJumMasuk)
				->setCellValue('M' . $xlsRow, $vJumMasuk1)
				->setCellValue('N' . $xlsRow, $vHariSakit)
				->setCellValue('O' . $xlsRow, $vHariSakit1)
				->setCellValue('P' . $xlsRow, $vHariCuti)
				->setCellValue('Q' . $xlsRow, $vHariCuti1)
				->setCellValue('R' . $xlsRow, $vHariIjin)
				->setCellValue('S' . $xlsRow, $vHariIjin1)
				->setCellValue('T' . $xlsRow, $vHariAlpha)
				->setCellValue('U' . $xlsRow, $vHariAlpha1)
				->setCellValue('V' . $xlsRow, $vTerlambat1)
				->setCellValue('W' . $xlsRow, $vTerlambat11)
				->setCellValue('X' . $xlsRow, $vTerlambat2)
				->setCellValue('Y' . $xlsRow, $vTerlambat21)
				->setCellValue('Z' . $xlsRow, $vTerlambat3)
				->setCellValue('AA' . $xlsRow, $vTerlambat31)
				->setCellValue('AB' . $xlsRow, $vTerlambat4)
				->setCellValue('AC' . $xlsRow, $vTerlambat41)
				->setCellValue('AD' . $xlsRow, $gajiHarian)
				->setCellValue('AE' . $xlsRow, $gajiHarian1)
				->setCellValue('AF' . $xlsRow, $gajiMingguan)
				->setCellValue('AG' . $xlsRow, $gajiMingguan1)
				->setCellValue('AH' . $xlsRow, $uangMakan)
				->setCellValue('AI' . $xlsRow, $uangMakan1)
				->setCellValue('AJ' . $xlsRow, $totalUangLembur)
				->setCellValue('AK' . $xlsRow, $totalUangLembur1)
				->setCellValue('AL' . $xlsRow, $premi)
				->setCellValue('AM' . $xlsRow, $premi1)
				->setCellValue('AN' . $xlsRow, $tunHariLibur)
				->setCellValue('AO' . $xlsRow, $tunHariLibur1)
				->setCellValue('AP' . $xlsRow, $tunHariKerja)
				->setCellValue('AQ' . $xlsRow, $tunHariKerja1)
				->setCellValue('AR' . $xlsRow, $tunTidakIstirahat)
				->setCellValue('AS' . $xlsRow, $tunTidakIstirahat1)
				->setCellValue('AT' . $xlsRow, $tunMasukAwal)
				->setCellValue('AU' . $xlsRow, $tunMasukAwal1)
				->setCellValue('AV' . $xlsRow, $tunJabatan)
				->setCellValue('AW' . $xlsRow, $tunJabatan1)
				->setCellValue('AX' . $xlsRow, $BPJSKS)
				->setCellValue('AY' . $xlsRow, $BPJSKS1)
				->setCellValue('AZ' . $xlsRow, $BPJSTK)
				->setCellValue('BA' . $xlsRow, $BPJSTK1)
				->setCellValue('AB' . $xlsRow, $vPotongan)
				->setCellValue('BC' . $xlsRow, $vPotongan1)
				->setCellValue('BD' . $xlsRow, $vBS)
				->setCellValue('BE' . $xlsRow, $vBS1)
				->setCellValue('BF' . $xlsRow, $absen)
				->setCellValue('BG' . $xlsRow, $absen1)
				->setCellValue('BH' . $xlsRow, $telat)
				->setCellValue('BI' . $xlsRow, $telat1)
				->setCellValue('BJ' . $xlsRow, $vTotGajiSkr)
				->setCellValue('BK' . $xlsRow, $vTotGajiSkr1)
				->setCellValue('BL' . $xlsRow, $revisi)
				->setCellValue('BM' . $xlsRow, $revisi1)
				->setCellValue('BN' . $xlsRow, $vTotGajiTransfer)
				->setCellValue('BO' . $xlsRow, $vTotGajiTransfer1);
				//->setCellValue('BL' . $xlsRow, $vTransTunai);
				//->setCellValue('BM' . $xlsRow, $vTransTunai1);
}
	
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>