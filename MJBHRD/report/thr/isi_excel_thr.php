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
$tahunbaru=substr($tglskr, 0, 4);
//echo $tahunbaru;
$hari="";
$bulan="";
$tahun=""; 
$bulanAngka="";
$bulanBesok="";
$vWhere ="";
$dept = "";
if (isset($_GET['dept']))
{	
	$dept = trim($_GET['dept']);
	
	if ($dept != ""){
		$vWhere = " AND CASE WHEN REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3) IS NULL THEN REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 2) ELSE REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3) END = '$dept'";	
	}
}
$namaFile = 'Report_THR_Karyawan.xls';

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
            ->setCellValue('A1', 'Report THR Karyawan')
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'NIK')
            ->setCellValue('C3', 'No Face Attd')
            ->setCellValue('D3', 'Nama Karyawan')
            ->setCellValue('E3', 'Departemen')
            ->setCellValue('F3', 'Jabatan')
            ->setCellValue('G3', 'Lokasi / Plant')
            ->setCellValue('H3', 'Tgl Masuk')
            ->setCellValue('I3', 'Masa Kerja')
            ->setCellValue('I4', 'Tahun')
            ->setCellValue('J4', 'Bulan')
            ->setCellValue('K4', 'Hari')
            ->setCellValue('L3', 'Gaji Pokok')
            ->setCellValue('M3', 'Tunjangan Jabatan')
            ->setCellValue('N3', 'Tunjangan Lokasi')
            ->setCellValue('O3', 'Tunjangan Grade')
            ->setCellValue('P3', 'Uang Makan')
            ->setCellValue('Q3', 'Uang Transport')
            ->setCellValue('R3', 'Premi Hadir')
            ->setCellValue('S3', 'BPJS Kesehatan')
            ->setCellValue('T3', 'BPJS TK')
            ->setCellValue('U3', 'Total Gaji');

$xlsRow = 4;
$countHeader = 0;


	$queryGaji = "select  distinct P.PERSON_ID
    ,p.employee_number
    ,p.honors id_finger
    ,P.FULL_NAME
    ,CASE WHEN REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3) IS NULL THEN REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 2) ELSE REGEXP_SUBSTR(J.NAME, '[^.]+', 1, 3) END as dept
    ,REGEXP_SUBSTR(pos.name, '[^.]+', 1, 3) as jabatan
    ,hl.location_code
    ,TO_CHAR(P.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS ORIGINAL_DATE_OF_HIRE
    ,nvl(gp.screen_entry_value,0) gaji_pokok
    ,nvl(tj.screen_entry_value,0) tunj_jab
    ,nvl(tg.screen_entry_value,0) tunj_grade
    ,nvl(tl.screen_entry_value,0) tunj_lok
    ,nvl(tk.screen_entry_value,0) tunj_ker
    ,nvl(um.screen_entry_value,0) * 25 uang_makan
    ,nvl(ut.screen_entry_value,0) * 25 uang_trans
    ,TO_DATE(TO_CHAR(SYSDATE, 'YYYY-MM-DD'), 'YYYY-MM-DD') - TO_DATE(TO_CHAR(P.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD'), 'YYYY-MM-DD') AS TOTAL_HARI
    ,((gp.screen_entry_value + tj.screen_entry_value + tg.screen_entry_value + tl.screen_entry_value) / 25) AS PG   
    ,nvl(bpjsks.screen_entry_value,0)/100*FGFKS.GLOBAL_VALUE pot_bpjs_ks
    ,nvl(bpjstk.screen_entry_value,0)/100*FGFTK.GLOBAL_VALUE pot_bpjs_tk
    from    per_people_f p, PER_ALL_ASSIGNMENTS_F a, per_jobs j, FND_FLEX_VALUES_TL tl, per_positions pos, hr_locations hl
            ,fnd_user fu, per_grades pg,PER_ADDRESSES pa,FND_LOOKUP_VALUES flv, PER_PERSON_TYPES ppt
            ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name='E_Gaji_Pokok'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)gp
                ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name='E_Tunjangan_Jabatan'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)tj
                ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name='E_Tunjangan_Grade'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)tg
                ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name='E_Tunjangan_Kerajinan'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)tk
                ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name='E_Uang_Lembur'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)ul
                ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name='E_Uang_Makan'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)um
                ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name='E_Uang_Transport'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)ut
                ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name LIKE 'E_Bpjs_Ks%'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)bpjsks
                ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name LIKE 'E_Bpjs_Tk%'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)bpjstk
                ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name='E_PPH_21'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)pph
                ,(select  peef.assignment_id
                        ,peef.element_type_id
                        ,pet.element_name
                        ,pivf.name
                        ,peevf.screen_entry_value
                from    pay_element_entries_f peef
                        ,paybv_element_type pet
                        ,pay_element_entry_values_f peevf
                        ,pay_input_values_f pivf
                where   peef.element_type_id=pet.element_type_id
                AND     peevf.element_entry_id = peef.element_entry_id
                and     pivf.input_value_id=peevf.input_value_id
                and     pivf.name<>'Pay Value'
                and     pet.element_name='E_Tunjangan_Lokasi'
                and     pet.processing_type='Recurring'
                and     (sysdate between peevf.effective_start_date and peevf.effective_end_date)
                order by peef.assignment_id)tl
                , FF_GLOBALS_F FGFTK
                , FF_GLOBALS_F FGFKS
    where   p.person_id=a.person_id
    --and     p.person_id=2129
    and     a.job_id=j.job_id(+)
    and     REGEXP_SUBSTR(j.name, '[^.]+', 1, 1)=tl.flex_value_meaning(+)
    and     a.position_id=pos.position_id(+)
    and     a.location_id=hl.location_id(+)
    and     sysdate between p.effective_start_date and p.effective_end_date
    and     (sysdate-5) between nvl(a.effective_start_date,sysdate) and nvl(a.effective_end_date,sysdate)
    and     lower(p.full_name) not like '%salah%'
    and     lower(p.last_name) not like '%trial%'
    and     fu.employee_id(+)=p.person_id
    and     a.grade_id=pg.grade_id(+)
    and     p.person_id=pa.person_id(+)
    and     flv.lookup_type(+)='MAR_STATUS'
    and     flv.lookup_code=p.marital_status(+)
    and     ppt.person_type_id(+)=p.person_type_id
    and     gp.assignment_id(+)=a.assignment_id
    and     tj.assignment_id(+)=a.assignment_id
    and     tg.assignment_id(+)=a.assignment_id
    and     tk.assignment_id(+)=a.assignment_id
    and     ul.assignment_id(+)=a.assignment_id
    and     um.assignment_id(+)=a.assignment_id
    and     ut.assignment_id(+)=a.assignment_id
    and     bpjsks.assignment_id(+)=a.assignment_id
    and     bpjstk.assignment_id(+)=a.assignment_id
    and     pph.assignment_id(+)=a.assignment_id
    and     tl.assignment_id(+)=a.assignment_id
    and     ppt.user_person_type<>'Ex-employee'
    and     nvl(gp.screen_entry_value,0)<>0
    AND a.PAYROLL_ID IS NOT NULL
    AND a.PRIMARY_FLAG='Y'
    AND TRIM(REGEXP_SUBSTR(FGFTK.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjstk.element_name, '[^_]+', 1, 4))
    AND TRIM(REGEXP_SUBSTR(FGFKS.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjsks.element_name, '[^_]+', 1, 4))
	AND a.PEOPLE_GROUP_ID <> 3061
	$vWhere
    ORDER BY P.PERSON_ID";
	//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$vTahun = 0;
		$vBulan = 0;
		$vHari = 0;
		$vTotGajiSkr = 0;
		
		$vPersonId = $row[0];
		$vEmpNumber = $row[1];
		$vIdFinger = $row[2];
		$vFullName = $row[3];
		$vDept = $row[4];
		$vJabatan = $row[5];
		$vLokasi = $row[6];
		$hireDate = $row[7];
		$vGajiPokok = $row[8];
		$vTunJab = $row[9];
		$vTunGrade = $row[10];
		$vTunLok = $row[11];
		$vTunKer = $row[12];
		$vUangMakan = $row[13];
		$vUangTrans = $row[14];
		$vTotalHari = $row[15];
		$vPG = $row[16];
		$vBPJSKS = $row[17];
		$vBPJSTK = $row[18];
		
		$vTahun = round($vTotalHari / 365);
		$vBulan = round(($vTotalHari % 365) / 30);
		$vHari =  round((($vTotalHari % 365) % 30));
		
		$vTotGajiSkr = ($vGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vUangMakan + $vUangTrans + $vTunKer) - ($vBPJSKS + $vBPJSTK);
		
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
					->setCellValue('H' . $xlsRow, $hireDate)
					->setCellValue('I' . $xlsRow, $vTahun)
					->setCellValue('J' . $xlsRow, $vBulan)
					->setCellValue('K' . $xlsRow, $vHari)
					->setCellValue('L' . $xlsRow, $vGajiPokok)
					->setCellValue('M' . $xlsRow, $vTunJab)
					->setCellValue('N' . $xlsRow, $vTunLok)
					->setCellValue('O' . $xlsRow, $vTunGrade)
					->setCellValue('P' . $xlsRow, $vUangMakan)
					->setCellValue('Q' . $xlsRow, $vUangTrans)
					->setCellValue('R' . $xlsRow, $vTunKer)
					->setCellValue('S' . $xlsRow, $vBPJSKS)
					->setCellValue('T' . $xlsRow, $vBPJSTK)
					->setCellValue('U' . $xlsRow, $vTotGajiSkr);
	}
	
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>