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
if (isset($_GET['bulan']) || isset($_GET['tahun']))
{	
	//$rekening = trim($_GET['rekening']);
	$bulan = $_GET['bulan'];
	$tahun = $_GET['tahun'];
	$plant = $_GET['plant'];
	$revisi = $_GET['revisi'];
	//echo $plant;exit;
	
	if($plant!=''){
		$queryplant = "AND hl.location_code = '".$plant."' ";
		//echo $queryplant;
	}
	//echo $queryplant;exit;
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
	
	$queryPeriode = "SELECT TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1), 'YYYY-MM-DD') PERIODE1 
	, TO_CHAR(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), 'YYYY-MM-DD') PERIODE2
	FROM DUAL ";
	//echo $queryJumHari;
	$resultPeriode = oci_parse($con,$queryPeriode);
	oci_execute($resultPeriode);
	$rowPeriode = oci_fetch_row($resultPeriode);
	$vPeriode1 = $rowPeriode[0]; 
	$vPeriode2 = $rowPeriode[1]; 
}
$namaFile = 'Rincian_Slip_Gaji.xls';

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
AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD')), 'YYYY-MM-DD') AND '$vPeriode2'
AND TRIM(TO_CHAR(HOLIDAY_DATE, 'DAY')) <> 'SUNDAY')) TES
FROM DUAL";
//echo $queryAktif;
$resultAktif = oci_parse($con,$queryAktif);
oci_execute($resultAktif);
while($rowAktif = oci_fetch_row($resultAktif))
{
	$vHariAktif = $rowAktif[0]; 
}
		

// Add some data
//echo date('H:i:s') , " Add some data" , EOL;
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Report Rincian Slip Gaji Karyawan')
            //->setCellValue('A2', 'Jumlah Hari Aktif : ' . $vHariAktif)
            ->setCellValue('A3', 'No.')
            ->setCellValue('B3', 'NIK')
            ->setCellValue('C3', 'No Face Attd')
            ->setCellValue('D3', 'Nama Karyawan')
            ->setCellValue('E3', 'Perusahaan')
            ->setCellValue('F3', 'Departemen')
            ->setCellValue('G3', 'Jabatan')
            ->setCellValue('H3', 'Lokasi / Plant')
            ->setCellValue('I3', 'Tanggal Masuk')
            ->setCellValue('J3', 'Hari Masuk')
            ->setCellValue('K3', 'Hari Sakit')
            ->setCellValue('L3', 'Hari Cuti')
            ->setCellValue('M3', 'Hari Ijin')
            ->setCellValue('N3', 'Hari Alpha')
            ->setCellValue('O3', 'Terlambat 1')
            ->setCellValue('P3', 'Terlambat 2')
            ->setCellValue('Q3', 'Terlambat 3')
            ->setCellValue('R3', 'Terlambat 4')
            ->setCellValue('S3', 'Gaji Pokok')
            ->setCellValue('T3', 'Tunjangan Jabatan')
            ->setCellValue('U3', 'Tunjangan Lokasi')
            ->setCellValue('V3', 'Tunjangan Grade')
            ->setCellValue('W3', 'Uang Makan')
            ->setCellValue('X3', 'Uang Transport')
            ->setCellValue('Y3', 'Premi Hadir')
            ->setCellValue('Z3', 'Lembur')
            ->setCellValue('AA3', 'PPH21')
            ->setCellValue('AB3', 'BPJS Kesehatan')
            ->setCellValue('AC3', 'BPJS TK')
            ->setCellValue('AD3', 'Pinjaman')
            ->setCellValue('AE3', 'BS')
            ->setCellValue('AF3', 'Absen')
            ->setCellValue('AG3', 'Telat')
            ->setCellValue('AH3', 'Total Gaji')
            ->setCellValue('AI3', 'Revisi Gaji Bulan Lalu')
            ->setCellValue('AJ3', 'Pending Gaji')
            ->setCellValue('AK3', 'Total diTransfer')
            ->setCellValue('AL3', 'Shift')
            ->setCellValue('AM3', 'Tgl Input Shift');

$xlsRow = 3;
$countHeader = 0;


	$queryGaji = "select  distinct P.PERSON_ID
	,p.employee_number
	,p.honors id_finger
	,P.FULL_NAME
	--,REGEXP_SUBSTR(j.name, '[^.]+', 1, 2) as dept
	--,REGEXP_SUBSTR(pos.name, '[^.]+', 1, 3) as jabatan
    ,j.name as dept
    ,pos.name as jabatan
	,hl.location_code
	,TO_CHAR(P.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS ORIGINAL_DATE_OF_HIRE
    ,nvl(gp.screen_entry_value,0) gaji_pokok
    ,nvl(tj.screen_entry_value,0) tunj_jab
    ,nvl(tg.screen_entry_value,0) tunj_grade
    ,nvl(tl.screen_entry_value,0) tunj_lok
    ,nvl(tk.screen_entry_value,0) tunj_ker
    ,nvl(um.screen_entry_value,0) uang_makan
    ,nvl(ut.screen_entry_value,0) uang_trans
    ,nvl(ul.screen_entry_value,0) uang_lembur
	,((nvl(gp.screen_entry_value,0) + nvl(tj.screen_entry_value,0) + nvl(tg.screen_entry_value,0) + nvl(tl.screen_entry_value,0)) / 25) AS PG
	,nvl(pot.harga_cicilan_p,0) cicilan_pinjaman
	,nvl(pot.harga_cicilan_b,0) cicilan_bs
	,nvl(bpjsks.screen_entry_value,0)/100*FGFKS.GLOBAL_VALUE pot_bpjs_ks
	,nvl(bpjstk.screen_entry_value,0)/100*FGFTK.GLOBAL_VALUE pot_bpjs_tk
	,nvl(revgaji.screen_entry_value, 0) revisi_gaji
	,NVL(TO_CHAR(P.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '4201-01-01') AS TGL_RESIGN
	, pos.position_id
    , hou.name perusahaan
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
	--             ,(select  peef.assignment_id
	--                    ,peef.element_type_id
	--                    ,pet.element_name
	--                    ,pivf.name
	--                    ,peevf.screen_entry_value
	--            from    pay_element_entries_f peef
	--                    ,paybv_element_type pet
	--                    ,pay_element_entry_values_f peevf
	--                    ,pay_input_values_f pivf
	--            where   peef.element_type_id=pet.element_type_id
	--            AND     peevf.element_entry_id = peef.element_entry_id
	--            and     pivf.input_value_id=peevf.input_value_id
	--            and     pivf.name='Pay Value'
	--            and     pet.element_name='E_Revisi_Gaji'
	--            --and     pet.processing_type='Recurring'
	--            order by peef.assignment_id)rg
                , (
                    SELECT  PERSON_ID
                            , NVL( SUM( OUTSTANDING_P ), 0 ) OUTSTANDING_P
                            , 0 OUTSTANDING_B
                            , NVL( SUM( HARGA_CICILAN_P ), 0 ) HARGA_CICILAN_P
                            , 0 HARGA_CICILAN_B
                    FROM 
                    (
                        (               
                            SELECT  MTP.PERSON_ID,
                                    SUM( NVL( MTP.NOMINAL, 0 ) * NVL( MTP.JUMLAH_CICILAN, 0 ) ) OUTSTANDING_P,
                                    0 OUTSTANDING_B,
                                    SUM( NVL( MTP.NOMINAL, 0 ) ) HARGA_CICILAN_P,
                                    0 HARGA_CICILAN_B
                            FROM    MJ.MJ_T_PINJAMAN MTP
                            WHERE   JUMLAH_CICILAN > 0
                            AND     TIPE = 'PINJAMAN PERSONAL'
                            AND     TINGKAT = 5
                            AND     STATUS_DOKUMEN = 'Validate'
                            AND     ADD_MONTHS( TRUNC( 
                                        TO_DATE( MTP.START_POTONGAN_TAHUN || LPAD( MTP.START_POTONGAN_BULAN, 2, '00' ) || '01', 'YYYYMMDD' )
                                        , 'MM' ), -1 ) <= TO_DATE( SUBSTR( '$vPeriode2', 1, 4 ) || SUBSTR( '$vPeriode2', 6, 2 ) || '01', 'YYYYMMDD' )
                            GROUP   BY MTP.PERSON_ID
                        )
                        UNION
                        (
                            SELECT  MTP.PERSON_ID,
                                    SUM( NVL( MTP.NOMINAL, 0 ) * NVL( MTP.JUMLAH_CICILAN, 0 ) ) OUTSTANDING_P,
                                    0 OUTSTANDING_B,
                                    SUM( NVL( MTP.NOMINAL, 0 ) ) HARGA_CICILAN_P,
                                    0 HARGA_CICILAN_B
                            FROM    MJ.MJ_T_PINJAMAN MTP
                            WHERE   JUMLAH_CICILAN > 0
                            AND     TIPE = 'PINJAMAN PENGGANTI INVENTARIS'
                            AND     TINGKAT = 4
                            AND     STATUS_DOKUMEN = 'Approved'
                            AND     ADD_MONTHS( TRUNC( 
                                        TO_DATE( MTP.START_POTONGAN_TAHUN || LPAD( MTP.START_POTONGAN_BULAN, 2, '00' ) || '01', 'YYYYMMDD' )
                                        , 'MM' ), -1 ) <= TO_DATE( SUBSTR( '$vPeriode2', 1, 4 ) || SUBSTR( '$vPeriode2', 6, 2 ) || '01', 'YYYYMMDD' )
                            GROUP   BY MTP.PERSON_ID
                        )
                    )
                    GROUP BY PERSON_ID
                ) POT
				, FF_GLOBALS_F FGFTK
				, FF_GLOBALS_F FGFKS
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
				and     pivf.name='Pay Value'
				and     pet.element_name='E_Revisi_Gaji'
				and     pet.processing_type='Nonrecurring'
				and     TO_CHAR(peevf.effective_end_date, 'YYYY-MM-DD') >= '$vPeriode1'
				and     TO_CHAR(peevf.effective_end_date, 'YYYY-MM-DD') <= '$vPeriode2'
				order by peef.assignment_id)revgaji, hr_organization_units hou
	where   p.person_id=a.person_id
	--and     p.person_id = 43134--in(13985,37870,37250, 40930)--33617
	and     a.job_id=j.job_id(+)
	and     REGEXP_SUBSTR(j.name, '[^.]+', 1, 1)=tl.flex_value_meaning(+)
	and     a.position_id=pos.position_id(+)
	and     a.location_id=hl.location_id(+)
    and     a.organization_id = hou.organization_id(+)
	and     sysdate between p.effective_start_date and p.effective_end_date
	and     (sysdate-5) between nvl(a.effective_start_date,sysdate) and nvl(a.effective_end_date,sysdate)
	and     lower(p.full_name) not like '%salah%'
	--and     hl.location_code in ('Barata 46', 'Pataya', 'Ruko Kuning','Jakarta','Sanur')
	and     lower(p.last_name) not like '%trial%'
	and     fu.employee_id(+)=p.person_id
	and     a.grade_id=pg.grade_id(+)
	and     p.person_id=pa.person_id(+)
	and     flv.lookup_type(+)='MAR_STATUS'
	and     flv.lookup_code=p.marital_status(+)
	and     ppt.person_type_id(+)=p.person_type_id
	--and     p.person_id=g.person_id(+)
	and     gp.assignment_id(+)=a.assignment_id
	and     tj.assignment_id(+)=a.assignment_id
	and     tg.assignment_id(+)=a.assignment_id
	and     tk.assignment_id(+)=a.assignment_id
	and     ul.assignment_id(+)=a.assignment_id
	and     um.assignment_id(+)=a.assignment_id
	and     ut.assignment_id(+)=a.assignment_id
	and     revgaji.assignment_id(+)=a.assignment_id
	and     bpjsks.assignment_id(+)=a.assignment_id
	and     bpjstk.assignment_id(+)=a.assignment_id
	and     pph.assignment_id(+)=a.assignment_id
	and     tl.assignment_id(+)=a.assignment_id
	--and     rg.assignment_id(+)=a.assignment_id
	and     ppt.user_person_type<>'Ex-employee'
	and     nvl(gp.screen_entry_value,0)<>0
	--and     p.middle_names='ARM'
	--order by to_number(p.employee_number) ASC
	AND a.PAYROLL_ID IS NOT NULL
	AND a.PRIMARY_FLAG='Y'
	AND p.ATTRIBUTE2 = 'Ya'
	and p.person_id=pot.person_id(+)
	AND TRIM(REGEXP_SUBSTR(FGFTK.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjstk.element_name, '[^_]+', 1, 4))
	AND TRIM(REGEXP_SUBSTR(FGFKS.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjsks.element_name, '[^_]+', 1, 4))
	AND a.PEOPLE_GROUP_ID <> 3061
	AND TO_DATE(NVL(TO_CHAR(P.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '4201-01-01'), 'YYYY-MM-DD') > LAST_DAY(TO_DATE('$vPeriode1', 'YYYY-MM-DD'))
    --AND REGEXP_SUBSTR(j.name, '[^.]+', 1, 3) LIKE '%Information Technology%'
	$queryplant
	ORDER BY P.PERSON_ID";
	// echo $queryGaji; exit;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$vTotGajiPokok = 0;
		$vTotTunKer = 0;
		$vTotUangMakan = 0;
		$vTotUangTrans = 0;
		$vTotUangLembur = 0;
		$vTotPotongan = 0;
		$vTotBS = 0;
		$vPotongan = 0; 
		$vBS = 0; 
		$vHariMasuk = 0;
		$vHariIjin = 0; 
		$vHariAlpha = 0; 
		$vHariCuti = 0; 
		$vHariSakit = 0; 
		$vTotUangIjin = 0; 
		$vTotUangAlpha = 0;
		$vTotUangAbsen = 0;
		$vTD = 0;
		$vTerlambat1 = 0; 
		$vTerlambat2 = 0;
		$vTerlambat3 = 0;
		$vTerlambat4 = 0;
		$vUangTerlambat1 = 0;
		$vUangTerlambat2 = 0;
		$vUangTerlambat3 = 0;
		$vUangTerlambat4 = 0;
		$vTotUangTerlambat = 0;
		$vTotGajiSkr = 0;
		$vTotGajiTransfer = 0;
		$vTotJamLembur = 0;
		$vBatasTotJamLembur = 0;
		// $vTotGajiPlus = 0;
		// $vTotGajiMinus = 0;
		$vHariAktif2 = 0;
		$vJumMasuk2 = 0;
		$vTotGajiPokok2 = 0;
		$vTotGajiPokokTRP2 = 0;
		$vTotTunKer2 = 0;
		$vTotUangMakan2 = 0;
		$vTotUangTrans2 = 0;
		$vTotUangLembur2 = 0;
		$vHariMasuk2 = 0;
		$vHariMasukStandart2 = 0;
		$vHariIjin2 = 0; 
		$vHariAlpha2 = 0; 
		$vHariCuti2 = 0; 
		$vHariSakit2 = 0; 
		$vTotUangIjin2 = 0; 
		$vTotUangAlpha2 = 0;
		$vTotUangAbsen2 = 0;
		$vTD2 = 0;
		$vTerlambat12 = 0; 
		$vTerlambat22 = 0;
		$vTerlambat32 = 0;
		$vTerlambat42 = 0;
		$vUangTerlambat12 = 0;
		$vUangTerlambat22 = 0;
		$vUangTerlambat32 = 0;
		$vUangTerlambat42 = 0;
		$vTotUangTerlambat2 = 0;
		$vTotJamLembur2 = 0;
		$vBatasTotJamLembur2 = 0;
		$vTotGajiPlus2 = 0;
		$vTotGajiMinus2 = 0;
		$vTotGajiSkr2 = 0;
		$vTotGajiTransfer2 = 0;
		
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
		$vUangLembur = $row[15];
		$vPG = $row[16];
		$vPotongan = $row[17];
		$vBS = $row[18];
		$vBPJSKS = $row[19];
		$vBPJSTK = $row[20];
		$vRevisiGaji = $row[21];
		$vTglResign = $row[22];
		$vPositionId = $row[23];
		$vOrg = $row[24];
		
		// $queryJumHari = "SELECT CASE WHEN TO_DATE('$hireDate', 'YYYY-MM-DD') BETWEEN ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1) AND TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') 
		// THEN (((SELECT COUNT(*) FROM 
		// (SELECT LEVEL AS DNUM FROM DUAL
		// CONNECT BY (TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') - TO_DATE('$hireDate', 'YYYY-MM-DD') + 1) - LEVEL >= 0) S
		// WHERE TO_CHAR(SYSDATE + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
		// (SELECT COUNT(-1)
		// FROM APPS.HXT_HOLIDAY_DAYS A
		// , APPS.HXT_HOLIDAY_CALENDARS B
		// WHERE A.HCL_ID=B.ID 
		// AND B.EFFECTIVE_END_DATE>SYSDATE 
		// AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$hireDate' AND '$tahun-$bulanAngka-21')) * ($vGajiPokok/25))
		// ELSE $vGajiPokok 
		// END
		// FROM DUAL";
		// //echo $queryJumHari;
		// $resultJumHari = oci_parse($con,$queryJumHari);
		// oci_execute($resultJumHari);
		// $rowJumHari = oci_fetch_row($resultJumHari);
		// $JumHari = $rowJumHari[0]; 
		
		$queryNew = "SELECT CASE WHEN TO_DATE('$hireDate', 'YYYY-MM-DD') BETWEEN ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1) AND TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') 
		THEN 'YES' ELSE 'NO' END FROM DUAL";
		//echo $queryJumHari;
		$resultNew = oci_parse($con,$queryNew);
		oci_execute($resultNew);
		$rowNew = oci_fetch_row($resultNew);
		$vNew = $rowNew[0]; 
		
		if($vNew=='YES'){
			/* $queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
			(SELECT LEVEL AS DNUM FROM DUAL
			CONNECT BY (TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') - (TO_DATE('$hireDate', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
			WHERE TO_CHAR((TO_DATE('$hireDate', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
			(SELECT COUNT(-1)
			FROM APPS.HXT_HOLIDAY_DAYS A
			, APPS.HXT_HOLIDAY_CALENDARS B
			WHERE A.HCL_ID=B.ID 
			AND B.EFFECTIVE_END_DATE>SYSDATE 
			AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$hireDate' AND '$tahun-$bulanAngka-21'
			AND TO_CHAR(A.HOLIDAY_DATE, 'DY') NOT IN ('SUN') )) TES
			FROM DUAL"; */
			
			// Update by DWP 31-12-2018 dengan penambahan (decode) pengecekan tgl resign untuk karyawan baru. Karena ada case karyawan masuk dan resign pada periode yang sama
			$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
			(SELECT LEVEL AS DNUM FROM DUAL
			CONNECT BY (TO_DATE(DECODE('$vTglResign', '4201-01-01', '$tahun-$bulanAngka-20', to_char(to_date('$vTglResign', 'YYYY-MM-DD')-1, 'YYYY-MM-DD')), 'YYYY-MM-DD') - (TO_DATE('$hireDate', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
			WHERE TO_CHAR((TO_DATE('$hireDate', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
			(SELECT COUNT(-1)
			FROM APPS.HXT_HOLIDAY_DAYS A
			, APPS.HXT_HOLIDAY_CALENDARS B
			WHERE A.HCL_ID=B.ID 
			AND B.EFFECTIVE_END_DATE>SYSDATE 
			AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$hireDate' AND '$tahun-$bulanAngka-21'
			AND TO_CHAR(A.HOLIDAY_DATE, 'DY') NOT IN ('SUN') )) TES
			FROM DUAL";
			//echo $queryJumHari;
			$resultJumHari = oci_parse($con,$queryJumHari);
			oci_execute($resultJumHari);
			while($rowJumHari = oci_fetch_row($resultJumHari))
			{
				$vHariMasuk = $rowJumHari[0]; 
			}
			//echo $vHariMasuk .' dan '. $vGajiPokok .' dan '. $hireDate;
			if($vHariAktif == $vHariMasuk){
				$vTotGajiPokok = $vGajiPokok;
				$vTotTunKer = $vTunKer;
			} else {
				$vTotGajiPokok = $vHariMasuk * ($vGajiPokok/25);
				$vTotTunKer = $vHariMasuk * ($vTunKer/25);
			}
		} else {
			$queryNew = "SELECT CASE WHEN TO_DATE('$vTglResign', 'YYYY-MM-DD') BETWEEN ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1) AND TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') 
			THEN 'YES' ELSE 'NO' END FROM DUAL";
			//echo $queryJumHari;
			$resultNew = oci_parse($con,$queryNew);
			oci_execute($resultNew);
			$rowNew = oci_fetch_row($resultNew);
			$vNew = $rowNew[0]; 
			
			if($vNew=='YES'){
				$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
				(SELECT LEVEL AS DNUM FROM DUAL
				CONNECT BY ((TO_DATE('$vTglResign', 'YYYY-MM-DD') - 1) - ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), -1)) - LEVEL >= 0) S
				WHERE TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), -1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
				(SELECT COUNT(-1)
				FROM APPS.HXT_HOLIDAY_DAYS A
				, APPS.HXT_HOLIDAY_CALENDARS B
				WHERE A.HCL_ID=B.ID 
				AND B.EFFECTIVE_END_DATE>SYSDATE 
				AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), -1), 'YYYY-MM-DD') 
				AND TO_CHAR((TO_DATE('$vTglResign', 'YYYY-MM-DD') - 1), 'YYYY-MM-DD')
				AND TO_CHAR(A.HOLIDAY_DATE, 'DY') NOT IN ('SUN') )) TES
				FROM DUAL";
				//echo $queryJumHari;
				$resultJumHari = oci_parse($con,$queryJumHari);
				oci_execute($resultJumHari);
				while($rowJumHari = oci_fetch_row($resultJumHari))
				{
					$vHariMasuk = $rowJumHari[0]; 
				}
				//echo $vHariMasuk . " dan " . $vGajiPokok;
				if($vHariAktif == $vHariMasuk){
					$vTotGajiPokok = $vGajiPokok;
					$vTotTunKer = $vTunKer;
				} else {
					$vTotGajiPokok = $vHariMasuk * ($vGajiPokok/25);
					$vTotTunKer = $vHariMasuk * ($vTunKer/25);
				}
			} else {
				$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
				(SELECT LEVEL AS DNUM FROM DUAL
				CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
				WHERE TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
				(SELECT COUNT(-1)
				FROM APPS.HXT_HOLIDAY_DAYS A
				, APPS.HXT_HOLIDAY_CALENDARS B
				WHERE A.HCL_ID=B.ID 
				AND B.EFFECTIVE_END_DATE>SYSDATE 
				AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$vPeriode1' AND '$vPeriode2'
				AND TO_CHAR(A.HOLIDAY_DATE, 'DY') NOT IN ('SUN') )) TES
				FROM DUAL";
				//echo $queryJumHari;
				$resultJumHari = oci_parse($con,$queryJumHari);
				oci_execute($resultJumHari);
				while($rowJumHari = oci_fetch_row($resultJumHari))
				{
					$vHariMasuk = $rowJumHari[0]; 
				}
				
				$vTotGajiPokok = $vGajiPokok;
				$vTotTunKer = $vTunKer;
			}
		}
		
		$queryJumMasuk = "SELECT COUNT(-1) JumMasuk FROM
		(
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('MASUK')
			AND MTT.PERSON_ID=$vPersonId
			UNION 
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI='Ijin' 
			AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('ALPHA')
			AND MTT.PERSON_ID=$vPersonId
		)";
		//echo $queryJumHari;
		$resultJumMasuk = oci_parse($con,$queryJumMasuk);
		oci_execute($resultJumMasuk);
		$rowJumMasuk = oci_fetch_row($resultJumMasuk);
		$vJumMasuk = $rowJumMasuk[0]; 
		
		$vTotUangMakan = $vJumMasuk * $vUangMakan;
		$vTotUangTrans = $vJumMasuk * $vUangTrans;
		
		
		//echo $vHariMasuk .' dan '. $vGajiPokok .' dan '. $vTotGajiPokok .'dan'. $vJumMasuk;
		if($vHariMasuk != $vJumMasuk){
			$vTotTunKer = 0; 
		}
		//echo $vHariMasuk.' - '.$vJumMasuk;exit;
		$queryJumCuti = "SELECT COUNT(-1) JumCuti FROM
        (
            SELECT DISTINCT MTT.TANGGAL
            FROM MJ.MJ_T_TIMECARD MTT
            INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
            WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
            AND MME.ELEMENT_NAME IN ('CUTI')
            AND MTT.PERSON_ID=$vPersonId
            UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND (MTS.KATEGORI='Cuti' OR (MTS.KATEGORI='Ijin' 
			AND NVL(MTS.IJIN_KHUSUS, 'Kosong') NOT IN ('Kosong', 'LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')))
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('ALPHA', 'IJIN KHUSUS')
			AND MTT.PERSON_ID=$vPersonId
        )";
		//echo $queryJumHari;
		$resultJumCuti = oci_parse($con,$queryJumCuti);
		oci_execute($resultJumCuti);
		while($rowJumCuti = oci_fetch_row($resultJumCuti))
		{
			$vHariCuti = $rowJumCuti[0]; 
		}
		
		$queryJumSakit = "SELECT COUNT(-1) JumSakit FROM
        (
            SELECT DISTINCT MTT.TANGGAL
            FROM MJ.MJ_T_TIMECARD MTT
            INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
            WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
            AND MME.ELEMENT_NAME IN ('SAKIT')
            AND MTT.PERSON_ID=$vPersonId
            UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Sakit') 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('ALPHA')
			AND MTT.PERSON_ID=$vPersonId
        )";
		//echo $queryJumHari;
		$resultJumSakit = oci_parse($con,$queryJumSakit);
		oci_execute($resultJumSakit);
		while($rowJumSakit = oci_fetch_row($resultJumSakit))
		{
			$vHariSakit = $rowJumSakit[0]; 
		}
		
		$vTotUangMakan = $vJumMasuk * $vUangMakan;
		$vTotUangTrans = $vJumMasuk * $vUangTrans;
		
		if($vHariMasuk != $vJumMasuk){
			$vTotTunKer = 0;
		}
		
		//backup
		/* $queryTotalJamLembur = "SELECT JUMLAH_LEMBUR 
		FROM MJ.MJ_M_JAMLEMBUR MMJ
		INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMJ.PLANT_ID
		WHERE HL.LOCATION_CODE = '$vLokasi'"; */
		
		$queryTotalJamLembur = "SELECT JUMLAH_LEMBUR 
		FROM MJ.MJ_M_JAMLEMBUR MMJ
		INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMJ.PLANT_ID
		WHERE HL.LOCATION_CODE = '$vLokasi' 
		AND to_char(MMJ.POSITION_ID) = CASE WHEN ( SELECT COUNT(1)
			FROM MJ.MJ_M_JAMLEMBUR MMJ1
			INNER JOIN APPS.HR_LOCATIONS HL1 ON HL1.LOCATION_ID=MMJ1.PLANT_ID
			WHERE HL1.LOCATION_CODE = '$vLokasi' 
			AND to_char(MMJ1.POSITION_ID) = '$vPositionId'
			AND MMJ1.STATUS = 'A'
		) > 0 THEN '$vPositionId' ELSE '0' END";
		//echo $queryTotalJamLembur;exit;
		$resultTotalJamLembur = oci_parse($con,$queryTotalJamLembur);
		oci_execute($resultTotalJamLembur);
		$rowTotalJamLembur = oci_fetch_row($resultTotalJamLembur);
		$vBatasTotJamLembur = $rowTotalJamLembur[0]; 
		
		
		$queryJamLembur = "SELECT DISTINCT MTSD.TOTAL_JAM, NVL(MTT.JAM_MASUK, '16:00'), NVL(MTT.JAM_KELUAR, NVL(MTSI.JAM_TO, NVL(MTT2.JAM_KELUAR, '23:59'))), MTS.TANGGAL_SPL        
		FROM MJ.MJ_T_SPL MTS
		INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
		LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253 AND MTT.PERSON_ID=$vPersonId
		LEFT JOIN MJ.MJ_T_TIMECARD MTT2 ON MTT2.TANGGAL=MTS.TANGGAL_SPL AND MTT2.STATUS=248 AND MTT2.PERSON_ID=$vPersonId
		LEFT JOIN MJ.MJ_T_SIK MTSI ON MTS.TANGGAL_SPL BETWEEN MTSI.TANGGAL_FROM AND MTSI.TANGGAL_TO
		AND MTSI.STATUS_DOK='Approved' AND MTSI.STATUS=1 
		AND MTSI.PERSON_ID=MTSD.PERSON_ID AND KATEGORI='Ijin' AND IJIN_KHUSUS IS NOT NULL
		AND MTSI.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
		WHERE MTSD.STATUS_DOK='Approved' AND MTS.STATUS=1
		AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$vPeriode1'
		AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$vPeriode2'
		AND MTSD.PERSON_ID=$vPersonId
		AND MTT.ID IS NOT NULL
		ORDER BY MTS.TANGGAL_SPL
		";
		//echo $queryJamLembur;
		$resultJamLembur = oci_parse($con,$queryJamLembur);
		oci_execute($resultJamLembur);
		while($rowJamLembur = oci_fetch_row($resultJamLembur))
		{
			$vTotSPL = 0;
			$vTotJamLemburTemp = 0;
			$vJamLembur = $rowJamLembur[0]; 
			$ArrTempMasuk = explode(":", $vJamLembur);
			$vTempJamMasuk = $ArrTempMasuk[0];
			$vTempMenitMasuk = $ArrTempMasuk[1];
			$vTempJamMasuk = $vTempJamMasuk + ($vTempMenitMasuk/60);
			
			$vJamLemburMasuk = $rowJamLembur[1]; 
			$ArrTempMasukSpl = explode(":", $vJamLemburMasuk);
			$vTempJamMasukSpl = $ArrTempMasukSpl[0];
			$vTempMenitMasukSpl = $ArrTempMasukSpl[1];
			$vTempJamMasukSpl = $vTempJamMasukSpl + ($vTempMenitMasukSpl/60) + 1;
			
			$vJamLemburKeluar = $rowJamLembur[2]; 
			$ArrTempKeluarSpl = explode(":", $vJamLemburKeluar);
			$vTempJamKeluarSpl = $ArrTempKeluarSpl[0];
			$vTempMenitKeluarSpl = $ArrTempKeluarSpl[1];
			$vTempJamKeluarSpl = $vTempJamKeluarSpl + ($vTempMenitKeluarSpl/60);
			
			$vTotSPL = $vTempJamKeluarSpl - $vTempJamMasukSpl;
			//echo $vTotJamLembur;
			if($vTotJamLembur < $vBatasTotJamLembur){
				if($vTotSPL > $vTempJamMasuk){
					if($vTempJamMasuk > 0){
						$vTotJamLemburTemp = $vTotJamLembur + round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						if($vTotJamLemburTemp > $vBatasTotJamLembur){
							$vTotJamLemburTemp = $vBatasTotJamLembur - $vTotJamLembur;
							$vTotUangLembur = $vTotUangLembur + ($vTotJamLemburTemp * $vUangLembur);
							$vTotJamLembur = $vTotJamLembur + $vTotJamLemburTemp;
							//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						} else {
							$vTotUangLembur = $vTotUangLembur + (round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
							$vTotJamLembur = $vTotJamLembur + round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
							//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						}
					}
				} else {
					if($vTotSPL > 0){
						$vTotJamLemburTemp = $vTotJamLembur + round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
						if($vTotJamLemburTemp > $vBatasTotJamLembur){
							$vTotJamLemburTemp = $vBatasTotJamLembur - $vTotJamLembur;
							$vTotUangLembur = $vTotUangLembur + ($vTotJamLemburTemp * $vUangLembur);
							$vTotJamLembur = $vTotJamLembur + $vTotJamLemburTemp;
							//echo '<br> 1 : ' . round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
						} else {
							$vTotUangLembur = $vTotUangLembur + (round($vTotSPL, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
							$vTotJamLembur = $vTotJamLembur + round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
							//echo '<br> 1 : ' . round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
						}
					}
				}
			}
		}
		
		$queryJamLembur1 = "SELECT DISTINCT MTSD.TOTAL_JAM, MTS.TANGGAL_SPL, REPLACE(MTSD.JAM_FROM, ':', '') JAM_FROM        
		FROM MJ.MJ_T_SPL MTS
		INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
		LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253 AND MTT.PERSON_ID=$vPersonId
		INNER JOIN MJ.MJ_T_SIK MTSI ON MTS.TANGGAL_SPL BETWEEN MTSI.TANGGAL_FROM AND MTSI.TANGGAL_TO
		AND MTSI.STATUS_DOK='Approved' AND MTSI.STATUS=1 
		AND MTSI.PERSON_ID=MTSD.PERSON_ID AND KATEGORI='Ijin' AND IJIN_KHUSUS IS NOT NULL
		AND MTSI.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
		WHERE MTSD.STATUS_DOK='Approved' AND MTS.STATUS=1
		AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$vPeriode1'
		AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$vPeriode2'
		AND MTSD.PERSON_ID=$vPersonId
		AND MTT.ID IS NULL
		ORDER BY MTS.TANGGAL_SPL
		";
		//echo $queryJamLembur;
		$resultJamLembur1 = oci_parse($con,$queryJamLembur1);
		oci_execute($resultJamLembur1);
		while($rowJamLembur1 = oci_fetch_row($resultJamLembur1))
		{
			$vHours = 0;
			$vHoursSPL = $rowJamLembur1[2];
			$vHoursPenguranan = 0;
			$vTotSPL = 0;
			$vTotJamLemburTemp = 0;
			
			$queryHours = "SELECT F.STANDARD_STOP
				FROM APPS.PER_PEOPLE_F A
				, APPS.PER_ALL_ASSIGNMENTS_F B
				, APPS.HXT_ADD_ASSIGN_INFO_F C
				, APPS.HXT_ROTATION_SCHEDULES D
				, APPS.HXT_WORK_SHIFTS_FMV E
				, APPS.HXT_SHIFTS F
				WHERE A.PERSON_ID=4456 AND A. EFFECTIVE_END_DATE>SYSDATE
				AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE>SYSDATE
				AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. EFFECTIVE_END_DATE>SYSDATE
				AND C.ROTATION_PLAN=D.RTP_ID AND D.START_DATE < SYSDATE 
				AND D.START_DATE=(SELECT MAX(START_DATE) FROM APPS.HXT_ROTATION_SCHEDULES WHERE START_DATE < SYSDATE)
				AND D.TWS_ID=E.TWS_ID 
				AND UPPER(E.MEANING)=(SELECT TRIM(TO_CHAR(TO_DATE('".$rowJamLembur1[1]."', 'YYYY-MM-DD'), 'DAY')) FROM DUAL)-- Hari absen
				AND E.SHT_ID=F.ID
			";
			$resultHours = oci_parse($con,$queryHours);
			oci_execute($resultHours);
			while($rowHours = oci_fetch_row($resultHours))
			{
				$vHours = $rowHours[0];
				if(strlen($vHours) == 3){
					$vHours = $vHours . "0";
				}
				if(strlen($vHoursSPL) == 3){
					$vHoursSPL = $vHoursSPL . "0";
				}
				//echo $vHours ." dan ". $vHoursSPL;
				if($vHours == $vHoursSPL){
					$vHoursPenguranan = 1;
				}
			}
			//echo $vHoursPenguranan;
			$vJamLembur = $rowJamLembur1[0]; 
			$ArrTempMasuk = explode(":", $vJamLembur);
			$vTempJamMasuk = $ArrTempMasuk[0];
			$vTempMenitMasuk = $ArrTempMasuk[1];
			$vTempJamMasuk = $vTempJamMasuk + ($vTempMenitMasuk/60) - $vHoursPenguranan;
			
			if($vTotJamLembur < $vBatasTotJamLembur){
				if($vTempJamMasuk > 0){
					$vTotJamLemburTemp = $vTotJamLembur + round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
					if($vTotJamLemburTemp > $vBatasTotJamLembur){
						$vTotJamLemburTemp = $vBatasTotJamLembur - $vTotJamLembur;
						$vTotUangLembur = $vTotUangLembur + ($vTotJamLemburTemp * $vUangLembur);
						$vTotJamLembur = $vTotJamLembur + $vTotJamLemburTemp;
						//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
					} else {
						$vTotUangLembur = $vTotUangLembur + (round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
						$vTotJamLembur = $vTotJamLembur + round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
					}
				}
			}
			
			
		}
		
		/* $queryHariIjin = "SELECT COUNT(-1) JumIjin FROM
		(
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('IJIN')
			AND MTT.PERSON_ID=$vPersonId
			UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Ijin') AND IJIN_KHUSUS IS NULL 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('ALPHA')
			AND MTT.PERSON_ID=$vPersonId
		)"; */
		$queryHariIjin = "SELECT COUNT(-1) JumIjin FROM
		(
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
            INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
            INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Ijin') AND IJIN_KHUSUS IS NOT NULL AND TANGGAL_FROM = MTT.TANGGAL
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('IJIN')
            AND IJIN_KHUSUS IS NULL
			AND MTT.PERSON_ID=$vPersonId
			UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Ijin') AND IJIN_KHUSUS IS NULL 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('ALPHA')
			AND MTT.PERSON_ID=$vPersonId
		)";
		//echo $queryJumHari;
		$resultHariIjin = oci_parse($con,$queryHariIjin);
		oci_execute($resultHariIjin);
		while($rowHariIjin = oci_fetch_row($resultHariIjin))
		{
			$vHariIjin = $rowHariIjin[0]; 
		}
		
		$vTotUangIjin = $vHariIjin * $vPG;
		
		$queryHariAlpha = "SELECT COUNT(-1) JumIjin FROM
		(
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1 AND KATEGORI<>'Terlambat'
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('ALPHA')
			AND MTS.ID IS NULL
			AND MTT.PERSON_ID=$vPersonId
		)";
		//echo $queryJumHari;
		$resultHariAlpha = oci_parse($con,$queryHariAlpha);
		oci_execute($resultHariAlpha);
		while($rowHariAlpha = oci_fetch_row($resultHariAlpha))
		{
			$vHariAlpha = $rowHariAlpha[0]; 
		}
		
		$vTotUangAlpha = 2 * $vHariAlpha * $vPG;
		
		$vTotUangAbsen = $vTotUangAlpha + $vTotUangIjin;
		
		$vTD = ((($vGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vTotUangMakan + $vTotUangTrans) * 0.2) / 25);
		
		//Query untuk menghitung terlambat1
		// backup query sebelumnya
		/* $queryTerlambat1 = "SELECT COUNT(-1)
		FROM (
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1 AND (KATEGORI = 'Terlambat' OR IJIN_KHUSUS = 'SETENGAH HARI')
			LEFT JOIN 
			(   
				SELECT MTT2.PERSON_ID, MTT2.TANGGAL, MTS.ID ID_SIK, MTT2.STATUS
				FROM MJ.MJ_T_TIMECARD MTT2 
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=MTT2.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT2.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG')
				WHERE TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MTT2.STATUS IN (247, 248)
				AND MTT2.PERSON_ID=$vPersonId
			) SIK ON SIK.TANGGAL = MTT.TANGGAL AND SIK.PERSON_ID = MTT.PERSON_ID
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
			AND MTT.STATUS = 252
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:06:00', 'HH24:MI:SS')
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:21:59', 'HH24:MI:SS')
			AND MTS.ID IS NULL
			AND (SIK.ID_SIK IS NOT NULL OR SIK.STATUS = 248)
			AND MTT.PERSON_ID=$vPersonId
			UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1 AND (KATEGORI = 'Terlambat' OR IJIN_KHUSUS = 'SETENGAH HARI')
			LEFT JOIN 
			(   
				SELECT MTT2.PERSON_ID, MTT2.TANGGAL, MTS.ID ID_SIK, MTT2.STATUS
				FROM MJ.MJ_T_TIMECARD MTT2 
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=MTT2.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT2.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG')
				WHERE TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MTT2.STATUS IN (247, 248)
				AND MTT2.PERSON_ID=$vPersonId
			) SIK ON SIK.TANGGAL = MTT.TANGGAL AND SIK.PERSON_ID = MTT.PERSON_ID
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
			AND MTT.STATUS = 252
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:06:00', 'HH24:MI:SS')
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:21:59', 'HH24:MI:SS')
			AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI'))
			--AND MTS.ID IS NULL
			AND (SIK.ID_SIK IS NOT NULL OR SIK.STATUS = 248)
			AND MTT.PERSON_ID=$vPersonId
		)"; */
		
		$queryTerlambat1 = "SELECT COUNT(-1)
		FROM (
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
			AND MME.ELEMENT_NAME IN ('TERLAMBAT')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:06:00', 'HH24:MI:SS')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:21:59', 'HH24:MI:SS')
			AND MTS.ID IS NULL
			AND MTT.PERSON_ID=$vPersonId
			UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
			AND MME.ELEMENT_NAME IN ('TERLAMBAT')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:06:00', 'HH24:MI:SS')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:21:59', 'HH24:MI:SS')
			AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')) 
			--AND MTS.ID IS NULL
			AND MTT.PERSON_ID=$vPersonId
		)";
		//echo $queryJumHari;
		$resultTerlambat1 = oci_parse($con,$queryTerlambat1);
		oci_execute($resultTerlambat1);
		while($rowTerlambat1 = oci_fetch_row($resultTerlambat1))
		{
			$vTerlambat1 = $rowTerlambat1[0]; 
		}
		
		//Query untuk menghitung terlambat2
		// backup query sebelumnya
		/* $queryTerlambat2 = "SELECT COUNT(-1)
		FROM (
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1 AND (KATEGORI = 'Terlambat' OR IJIN_KHUSUS = 'SETENGAH HARI')
			LEFT JOIN 
			(   
				SELECT MTT2.PERSON_ID, MTT2.TANGGAL, MTS.ID ID_SIK, MTT2.STATUS
				FROM MJ.MJ_T_TIMECARD MTT2 
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=MTT2.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT2.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG')
				WHERE TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MTT2.STATUS IN (247, 248)
				AND MTT2.PERSON_ID=$vPersonId
			) SIK ON SIK.TANGGAL = MTT.TANGGAL AND SIK.PERSON_ID = MTT.PERSON_ID
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
			AND MTT.STATUS = 252
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:22:00', 'HH24:MI:SS')
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:51:59', 'HH24:MI:SS')
			AND MTS.ID IS NULL
			AND (SIK.ID_SIK IS NOT NULL OR SIK.STATUS = 248)
			AND MTT.PERSON_ID=$vPersonId
			UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1 AND (KATEGORI = 'Terlambat' OR IJIN_KHUSUS = 'SETENGAH HARI')
			LEFT JOIN 
			(   
				SELECT MTT2.PERSON_ID, MTT2.TANGGAL, MTS.ID ID_SIK, MTT2.STATUS
				FROM MJ.MJ_T_TIMECARD MTT2 
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=MTT2.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT2.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG')
				WHERE TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MTT2.STATUS IN (247, 248)
				AND MTT2.PERSON_ID=$vPersonId
			) SIK ON SIK.TANGGAL = MTT.TANGGAL AND SIK.PERSON_ID = MTT.PERSON_ID
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
			AND MTT.STATUS = 252
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:22:00', 'HH24:MI:SS')
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:51:59', 'HH24:MI:SS')
			AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI'))
			--AND MTS.ID IS NULL
			AND (SIK.ID_SIK IS NOT NULL OR SIK.STATUS = 248)
			AND MTT.PERSON_ID=$vPersonId
		)"; */
		$queryTerlambat2 = "SELECT COUNT(-1)
		FROM (
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
			AND MME.ELEMENT_NAME IN ('TERLAMBAT')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:22:00', 'HH24:MI:SS')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:51:59', 'HH24:MI:SS')
			AND MTS.ID IS NULL
			AND MTT.PERSON_ID=$vPersonId
			UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
			AND MME.ELEMENT_NAME IN ('TERLAMBAT')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:22:00', 'HH24:MI:SS')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:51:59', 'HH24:MI:SS')
			AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')) 
			--AND MTS.ID IS NULL
			AND MTT.PERSON_ID=$vPersonId
		)";
		//echo $queryJumHari;
		$resultTerlambat2 = oci_parse($con,$queryTerlambat2);
		oci_execute($resultTerlambat2);
		while($rowTerlambat2 = oci_fetch_row($resultTerlambat2))
		{
			$vTerlambat2 = $rowTerlambat2[0]; 
		}
		
		//Query untuk menghitung terlambat3
		// backup query sebelumnya
		/* $queryTerlambat3 = "SELECT COUNT(-1)
		FROM (
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1 AND (KATEGORI = 'Terlambat' OR IJIN_KHUSUS = 'SETENGAH HARI')
			LEFT JOIN 
			(   
				SELECT MTT2.PERSON_ID, MTT2.TANGGAL, MTS.ID ID_SIK, MTT2.STATUS
				FROM MJ.MJ_T_TIMECARD MTT2 
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=MTT2.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT2.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG')
				WHERE TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MTT2.STATUS IN (247, 248)
				AND MTT2.PERSON_ID=$vPersonId
			) SIK ON SIK.TANGGAL = MTT.TANGGAL AND SIK.PERSON_ID = MTT.PERSON_ID
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
			AND MTT.STATUS = 252
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:52:00', 'HH24:MI:SS')
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('09:21:59', 'HH24:MI:SS')
			AND MTS.ID IS NULL
			AND (SIK.ID_SIK IS NOT NULL OR SIK.STATUS = 248)
			AND MTT.PERSON_ID=$vPersonId
			UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1 AND (KATEGORI = 'Terlambat' OR IJIN_KHUSUS = 'SETENGAH HARI')
			LEFT JOIN 
			(   
				SELECT MTT2.PERSON_ID, MTT2.TANGGAL, MTS.ID ID_SIK, MTT2.STATUS
				FROM MJ.MJ_T_TIMECARD MTT2 
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=MTT2.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT2.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG')
				WHERE TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MTT2.STATUS IN (247, 248)
				AND MTT2.PERSON_ID=$vPersonId
			) SIK ON SIK.TANGGAL = MTT.TANGGAL AND SIK.PERSON_ID = MTT.PERSON_ID
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
			AND MTT.STATUS = 252
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:52:00', 'HH24:MI:SS')
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('09:21:59', 'HH24:MI:SS')
			AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI'))
			--AND MTS.ID IS NULL
			AND (SIK.ID_SIK IS NOT NULL OR SIK.STATUS = 248)
			AND MTT.PERSON_ID=$vPersonId
		)"; */
		$queryTerlambat3 = "SELECT COUNT(-1)
		FROM (
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
			AND MME.ELEMENT_NAME IN ('TERLAMBAT')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:52:00', 'HH24:MI:SS')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('09:21:59', 'HH24:MI:SS')
			AND MTS.ID IS NULL
			AND MTT.PERSON_ID=$vPersonId
			UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
			AND MME.ELEMENT_NAME IN ('TERLAMBAT')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:52:00', 'HH24:MI:SS')
			AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('09:21:59', 'HH24:MI:SS')
			AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')) 
			--AND MTS.ID IS NULL
			AND MTT.PERSON_ID=$vPersonId
		)";
		//echo $queryJumHari;
		$resultTerlambat3 = oci_parse($con,$queryTerlambat3);
		oci_execute($resultTerlambat3);
		while($rowTerlambat3 = oci_fetch_row($resultTerlambat3))
		{
			$vTerlambat3 = $rowTerlambat3[0]; 
		}
		
		//Query untuk menghitung terlambat4
		
		// BACKUP SEBELUM QUERY DI PERSINGKAT
		/* $queryTerlambat4 = "SELECT COUNT(-1)
		FROM (
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1 AND (KATEGORI = 'Terlambat' OR IJIN_KHUSUS = 'SETENGAH HARI')
			LEFT JOIN 
			(   
				SELECT MTT2.PERSON_ID, MTT2.TANGGAL, MTS.ID ID_SIK, MTT2.STATUS
				FROM MJ.MJ_T_TIMECARD MTT2 
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=MTT2.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT2.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG')
				WHERE TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MTT2.STATUS IN (247, 248)
				AND MTT2.PERSON_ID=$vPersonId
			) SIK ON SIK.TANGGAL = MTT.TANGGAL AND SIK.PERSON_ID = MTT.PERSON_ID
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
			AND MTT.STATUS = 252
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('09:22:00', 'HH24:MI:SS')
			AND MTS.ID IS NULL
			AND (SIK.ID_SIK IS NOT NULL OR SIK.STATUS = 248)
			AND MTT.PERSON_ID=$vPersonId
			UNION
			SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1 AND (KATEGORI = 'Terlambat' OR IJIN_KHUSUS = 'SETENGAH HARI')
			LEFT JOIN 
			(   
				SELECT MTT2.PERSON_ID, MTT2.TANGGAL, MTS.ID ID_SIK, MTT2.STATUS
				FROM MJ.MJ_T_TIMECARD MTT2 
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=MTT2.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT2.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG')
				WHERE TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MTT2.STATUS IN (247, 248)
				AND MTT2.PERSON_ID=$vPersonId
			) SIK ON SIK.TANGGAL = MTT.TANGGAL AND SIK.PERSON_ID = MTT.PERSON_ID
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
			AND MTT.STATUS = 252
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('09:22:00', 'HH24:MI:SS')
			AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')) 
			--AND MTS.ID IS NULL
			AND (SIK.ID_SIK IS NOT NULL OR SIK.STATUS = 248)
			AND MTT.PERSON_ID=$vPersonId
		)"; */
		
		//echo $queryJumHari;
		
		$queryTerlambat4 = "SELECT COUNT(-1)
		FROM (SELECT DISTINCT MTT.TANGGAL
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
			AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
			AND MTS.STATUS=1
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
			AND MME.ELEMENT_NAME IN ('TERLAMBAT')
			AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('09:22:00', 'HH24:MI:SS')
			AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')) 
			--AND MTS.ID IS NULL
			AND MTT.PERSON_ID=$vPersonId
		)";
		
		$resultTerlambat4 = oci_parse($con,$queryTerlambat4);
		oci_execute($resultTerlambat4);
		while($rowTerlambat4 = oci_fetch_row($resultTerlambat4))
		{
			$vTerlambat4 = $rowTerlambat4[0]; 
		}
		
		$vUangTerlambat1 = $vTD * $vTerlambat1;
		$vUangTerlambat2 = 2 * $vTD * $vTerlambat2;
		$vUangTerlambat3 = 3 * $vTD * $vTerlambat3;
		$vUangTerlambat4 = $vPG * $vTerlambat4;
		$vTotUangTerlambat = $vUangTerlambat1 + $vUangTerlambat2 + $vUangTerlambat3 + $vUangTerlambat4;
		
		$CountMasuk = 0;
		$tglHours = "";
		$queryCountMasuk = "SELECT COUNT(-1), C.CREATION_DATE
		FROM APPS.PER_PEOPLE_F A
		, APPS.PER_ALL_ASSIGNMENTS_F B
		, APPS.HXT_ADD_ASSIGN_INFO_F C
		, APPS.HXT_ROTATION_SCHEDULES D
		, APPS.HXT_WORK_SHIFTS_FMV E
		, APPS.HXT_SHIFTS F
		WHERE A.PERSON_ID=$vPersonId AND A. EFFECTIVE_END_DATE > SYSDATE
		AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE > SYSDATE
		AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. EFFECTIVE_END_DATE > SYSDATE
		AND C.ROTATION_PLAN=D.RTP_ID AND D.START_DATE < SYSDATE 
		AND D.START_DATE=(SELECT MAX(START_DATE) FROM APPS.HXT_ROTATION_SCHEDULES WHERE START_DATE < SYSDATE)
		AND D.TWS_ID=E.TWS_ID AND UPPER(E.MEANING)='MONDAY'-- Hari absen
		AND E.SHT_ID=F.ID
		GROUP BY C.CREATION_DATE";
		//echo $queryCountMasuk;
		$resultCountMasuk = oci_parse($con,$queryCountMasuk);
		oci_execute($resultCountMasuk);
		while($rowCountMasuk = oci_fetch_row($resultCountMasuk))
		{
			$CountMasuk = $rowCountMasuk[0]; 
			$tglHours = $rowCountMasuk[1]; 
		}
		
		
		if ($CountMasuk >= 1) {
			$Hours = 'Ada';
		} else {
			$Hours = 'Tidak Ada';
		}
		
		
		//Query untuk mengecek karyawan resign akhir bulan
		$queryCekResign = "SELECT CASE WHEN TO_DATE('$vTglResign', 'YYYY-MM-DD') BETWEEN TO_DATE('$tahun-$bulanAngka-22', 'YYYY-MM-DD') AND LAST_DAY(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD')) 
		THEN 'YES' ELSE 'NO' END FROM DUAL";
		//echo $queryJumHari;
		$resultCekResign = oci_parse($con,$queryCekResign);
		oci_execute($resultCekResign);
		$rowCekResign = oci_fetch_row($resultCekResign);
		$vResign = $rowCekResign[0]; 
		
		$queryAktif2 = "SELECT ((SELECT COUNT(*) FROM 
			(SELECT LEVEL AS DNUM FROM DUAL
			CONNECT BY (LAST_DAY(TO_DATE('$vPeriode2', 'YYYY-MM-DD')) - (TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
			WHERE TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
			(SELECT COUNT(-1)
			FROM APPS.HXT_HOLIDAY_DAYS A
			, APPS.HXT_HOLIDAY_CALENDARS B
			WHERE A.HCL_ID=B.ID 
			AND B.EFFECTIVE_END_DATE>SYSDATE 
			AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN TO_CHAR((TO_DATE('$vPeriode1', 'YYYY-MM-DD') - 1), 'YYYY-MM-DD') AND TO_CHAR(LAST_DAY(TO_DATE('$vPeriode2','YYYY-MM-DD')),'YYYY-MM-DD')
			AND TRIM(TO_CHAR(HOLIDAY_DATE, 'DAY')) <> 'SUNDAY')) TES
			FROM DUAL";
			//echo $queryAktif2;exit;
		$resultAktif2 = oci_parse($con,$queryAktif2);
		oci_execute($resultAktif2);
		while($rowAktif2 = oci_fetch_row($resultAktif2))
		{
			$vHariAktif2 = $rowAktif2[0]; 
		}
		
		if($vResign=='YES' && $revisi == 1){
			$queryJumHari2 = "SELECT ((SELECT COUNT(*) FROM 
			(SELECT LEVEL AS DNUM FROM DUAL
			CONNECT BY ((TO_DATE('$vTglResign', 'YYYY-MM-DD') - 1) - ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), 0)) - LEVEL >= 0) S
			WHERE TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), 0) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
			(SELECT COUNT(-1)
			FROM APPS.HXT_HOLIDAY_DAYS A
			, APPS.HXT_HOLIDAY_CALENDARS B
			WHERE A.HCL_ID=B.ID 
			AND B.EFFECTIVE_END_DATE>SYSDATE 
			AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD'), 0), 'YYYY-MM-DD') 
			AND TO_CHAR((TO_DATE('$vTglResign', 'YYYY-MM-DD') - 1), 'YYYY-MM-DD')
			AND TO_CHAR(A.HOLIDAY_DATE, 'DY') NOT IN ('SUN') )) TES
			FROM DUAL";
			//echo $queryJumHari2;
			$resultJumHari2 = oci_parse($con,$queryJumHari2);
			oci_execute($resultJumHari2);
			while($rowJumHari2 = oci_fetch_row($resultJumHari2))
			{
				$vHariMasuk2 = $rowJumHari2[0]; 
			}
			
			$vHariMasukStandart2 = $vHariMasuk2;
			$vTotGajiPokokTRP2 = $vGajiPokok/$vHariMasuk2;
			$vTotGajiPokok2 = $vHariMasuk2 * ($vGajiPokok/25);
			$vTotTunKer2 = $vHariMasuk2 * ($vTunKer/25);
			
			//Query menghitung hari masuk
			$queryJumMasuk2 = "SELECT COUNT(-1) JumMasuk FROM
			(
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('MASUK')
				AND MTT.PERSON_ID=$vPersonId
				UNION 
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI='Ijin' 
				AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('ALPHA')
				AND MTT.PERSON_ID=$vPersonId
			)";
			//echo $queryJumHari;
			$resultJumMasuk2 = oci_parse($con,$queryJumMasuk2);
			oci_execute($resultJumMasuk2);
			$rowJumMasuk2 = oci_fetch_row($resultJumMasuk2);
			$vJumMasuk2 = $rowJumMasuk2[0]; 
			
			$vTotUangMakan2 = $vJumMasuk2 * $vUangMakan;
			$vTotUangTrans2 = $vJumMasuk2 * $vUangTrans;
			
			if($vHariMasuk2 != $vJumMasuk2){
				$vTotTunKer2 = 0;
			}
			
			//Query Menghitung jumlah cuti
			$queryJumCuti2 = "SELECT COUNT(-1) JumCuti FROM
			(
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('CUTI')
				AND MTT.PERSON_ID=$vPersonId
				UNION
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND (MTS.KATEGORI='Cuti' OR (MTS.KATEGORI='Ijin' 
				AND NVL(MTS.IJIN_KHUSUS, 'Kosong') NOT IN ('Kosong', 'LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')))
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('ALPHA', 'IJIN KHUSUS')
				AND MTT.PERSON_ID=$vPersonId
			)";
			//echo $queryJumHari;
			$resultJumCuti2 = oci_parse($con,$queryJumCuti2);
			oci_execute($resultJumCuti2);
			while($rowJumCuti2 = oci_fetch_row($resultJumCuti2))
			{
				$vHariCuti2 = $rowJumCuti2[0]; 
			}
			//Query menghitung jumlah sakit
			$queryJumSakit2 = "SELECT COUNT(-1) JumSakit FROM
			(
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('SAKIT')
				AND MTT.PERSON_ID=$vPersonId
				UNION
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Sakit') 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('ALPHA')
				AND MTT.PERSON_ID=$vPersonId
			)";
			//echo $queryJumHari;
			$resultJumSakit2 = oci_parse($con,$queryJumSakit2);
			oci_execute($resultJumSakit2);
			while($rowJumSakit2 = oci_fetch_row($resultJumSakit2))
			{
				$vHariSakit2 = $rowJumSakit2[0]; 
			}
			
			//Query untuk menghitung lembur
			$queryTotalJamLembur2 = "SELECT JUMLAH_LEMBUR 
			FROM MJ.MJ_M_JAMLEMBUR MMJ
			INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMJ.PLANT_ID
			WHERE HL.LOCATION_CODE = '$vLokasi' 
			AND to_char(MMJ.POSITION_ID) = CASE WHEN ( SELECT COUNT(1)
				FROM MJ.MJ_M_JAMLEMBUR MMJ1
				INNER JOIN APPS.HR_LOCATIONS HL1 ON HL1.LOCATION_ID=MMJ1.PLANT_ID
				WHERE HL1.LOCATION_CODE = '$vLokasi' 
				AND to_char(MMJ1.POSITION_ID) = '$vPositionId'
				AND MMJ1.STATUS = 'A'
			) > 0 THEN '$vPositionId' ELSE '0' END";
			//echo $queryJumHari;
			$resultTotalJamLembur2 = oci_parse($con,$queryTotalJamLembur2);
			oci_execute($resultTotalJamLembur2);
			$rowTotalJamLembur2 = oci_fetch_row($resultTotalJamLembur2);
			$vBatasTotJamLembur2 = $rowTotalJamLembur2[0]; 
			
			$queryJamLembur2 = "SELECT DISTINCT MTSD.TOTAL_JAM, NVL(MTT.JAM_MASUK, '16:00'), NVL(MTT.JAM_KELUAR, NVL(MTSI.JAM_TO, NVL(MTT2.JAM_KELUAR, '23:59'))), MTS.TANGGAL_SPL        
			FROM MJ.MJ_T_SPL MTS
			INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
			LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253 AND MTT.PERSON_ID=$vPersonId
			LEFT JOIN MJ.MJ_T_TIMECARD MTT2 ON MTT2.TANGGAL=MTS.TANGGAL_SPL AND MTT2.STATUS=248 AND MTT2.PERSON_ID=$vPersonId
			LEFT JOIN MJ.MJ_T_SIK MTSI ON MTS.TANGGAL_SPL BETWEEN MTSI.TANGGAL_FROM AND MTSI.TANGGAL_TO
			AND MTSI.STATUS_DOK='Approved' AND MTSI.STATUS=1 
			AND MTSI.PERSON_ID=MTSD.PERSON_ID AND KATEGORI='Ijin' AND IJIN_KHUSUS IS NOT NULL
			AND MTSI.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
			WHERE MTSD.STATUS_DOK='Approved' AND MTS.STATUS=1
			AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$tahun-$bulanAngka-21'
			AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$vTglResign'
			AND MTSD.PERSON_ID=$vPersonId
			AND MTT.ID IS NOT NULL
			ORDER BY MTS.TANGGAL_SPL
			";
			//echo $queryJamLembur;
			$resultJamLembur2 = oci_parse($con,$queryJamLembur2);
			oci_execute($resultJamLembur2);
			while($rowJamLembur2 = oci_fetch_row($resultJamLembur2))
			{
				$vTotSPL2 = 0;
				$vTotJamLemburTemp2 = 0;
				$vJamLembur2 = $rowJamLembur2[0]; 
				$ArrTempMasuk2 = explode(":", $vJamLembur2);
				$vTempJamMasuk2 = $ArrTempMasuk2[0];
				$vTempMenitMasuk2 = $ArrTempMasuk2[1];
				$vTempJamMasuk2 = $vTempJamMasuk2 + ($vTempMenitMasuk2/60);
				
				$vJamLemburMasuk2 = $rowJamLembur2[1]; 
				$ArrTempMasukSpl2 = explode(":", $vJamLemburMasuk2);
				$vTempJamMasukSpl2 = $ArrTempMasukSpl2[0];
				$vTempMenitMasukSpl2 = $ArrTempMasukSpl2[1];
				$vTempJamMasukSpl2 = $vTempJamMasukSpl2 + ($vTempMenitMasukSpl2/60) + 1;
				
				$vJamLemburKeluar2 = $rowJamLembur2[2]; 
				$ArrTempKeluarSpl2 = explode(":", $vJamLemburKeluar2);
				$vTempJamKeluarSpl2 = $ArrTempKeluarSpl2[0];
				$vTempMenitKeluarSpl2 = $ArrTempKeluarSpl2[1];
				$vTempJamKeluarSpl2 = $vTempJamKeluarSpl2 + ($vTempMenitKeluarSpl2/60);
				
				$vTotSPL2 = $vTempJamKeluarSpl2 - $vTempJamMasukSpl2;
				
				if($vTotJamLembur2 < $vBatasTotJamLembur2){
					if($vTotSPL2 > $vTempJamMasuk2){
						if($vTempJamMasuk2 > 0){
							$vTotJamLemburTemp2 = $vTotJamLembur2 + round($vTempJamMasuk2, 2, PHP_ROUND_HALF_DOWN);
							if($vTotJamLemburTemp2 > $vBatasTotJamLembur2){
								$vTotJamLemburTemp2 = $vBatasTotJamLembur2 - $vTotJamLembur2;
								$vTotUangLembur2 = $vTotUangLembur2 + ($vTotJamLemburTemp2 * $vUangLembur);
								$vTotJamLembur2 = $vTotJamLembur2 + $vTotJamLemburTemp2;
								//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
							} else {
								$vTotUangLembur2 = $vTotUangLembur2 + (round($vTempJamMasuk2, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
								$vTotJamLembur2 = $vTotJamLembur2 + round($vTempJamMasuk2, 2, PHP_ROUND_HALF_DOWN);
								//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
							}
						}
					} else {
						if($vTotSPL2 > 0){
							$vTotJamLemburTemp2 = $vTotJamLembur2 + round($vTotSPL2, 2, PHP_ROUND_HALF_DOWN);
							if($vTotJamLemburTemp2 > $vBatasTotJamLembur2){
								$vTotJamLemburTemp2 = $vBatasTotJamLembur2 - $vTotJamLembur2;
								$vTotUangLembur2 = $vTotUangLembur2 + ($vTotJamLemburTemp2 * $vUangLembur);
								$vTotJamLembur2 = $vTotJamLembur2 + $vTotJamLemburTemp2;
								//echo '<br> 1 : ' . round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
							} else {
								$vTotUangLembur2 = $vTotUangLembur2 + (round($vTotSPL2, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
								$vTotJamLembur2 = $vTotJamLembur2 + round($vTotSPL2, 2, PHP_ROUND_HALF_DOWN);
								//echo '<br> 1 : ' . round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
							}
						}
					}
				}
			}
			
			$queryJamLembur12 = "SELECT DISTINCT MTSD.TOTAL_JAM, MTS.TANGGAL_SPL, REPLACE(MTSD.JAM_FROM, ':', '') JAM_FROM        
			FROM MJ.MJ_T_SPL MTS
			INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
			LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253 AND MTT.PERSON_ID=$vPersonId
			INNER JOIN MJ.MJ_T_SIK MTSI ON MTS.TANGGAL_SPL BETWEEN MTSI.TANGGAL_FROM AND MTSI.TANGGAL_TO
			AND MTSI.STATUS_DOK='Approved' AND MTSI.STATUS=1 
			AND MTSI.PERSON_ID=MTSD.PERSON_ID AND KATEGORI='Ijin' AND IJIN_KHUSUS IS NOT NULL
			AND MTSI.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
			WHERE MTSD.STATUS_DOK='Approved' AND MTS.STATUS=1
			AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$tahun-$bulanAngka-21'
			AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$vTglResign'
			AND MTSD.PERSON_ID=$vPersonId
			AND MTT.ID IS NULL
			ORDER BY MTS.TANGGAL_SPL
			";
			//echo $queryJamLembur;
			$resultJamLembur12 = oci_parse($con,$queryJamLembur12);
			oci_execute($resultJamLembur12);
			while($rowJamLembur12 = oci_fetch_row($resultJamLembur12))
			{
				$vHours2 = 0;
				$vHoursSPL2 = $rowJamLembur12[2];
				$vHoursPenguranan2 = 0;
				$vTotSPL2 = 0;
				$vTotJamLemburTemp2 = 0;
				
				$queryHours2 = "SELECT F.STANDARD_STOP
					FROM APPS.PER_PEOPLE_F A
					, APPS.PER_ALL_ASSIGNMENTS_F B
					, APPS.HXT_ADD_ASSIGN_INFO_F C
					, APPS.HXT_ROTATION_SCHEDULES D
					, APPS.HXT_WORK_SHIFTS_FMV E
					, APPS.HXT_SHIFTS F
					WHERE A.PERSON_ID=4456 AND A. EFFECTIVE_END_DATE>SYSDATE
					AND A.PERSON_ID=B.PERSON_ID AND B. EFFECTIVE_END_DATE>SYSDATE
					AND B.ASSIGNMENT_ID=C.ASSIGNMENT_ID AND C. EFFECTIVE_END_DATE>SYSDATE
					AND C.ROTATION_PLAN=D.RTP_ID AND D.START_DATE < SYSDATE 
					AND D.START_DATE=(SELECT MAX(START_DATE) FROM APPS.HXT_ROTATION_SCHEDULES WHERE START_DATE < SYSDATE)
					AND D.TWS_ID=E.TWS_ID 
					AND UPPER(E.MEANING)=(SELECT TRIM(TO_CHAR(TO_DATE('".$rowJamLembur12[1]."', 'YYYY-MM-DD'), 'DAY')) FROM DUAL)-- Hari absen
					AND E.SHT_ID=F.ID
				";
				$resultHours2 = oci_parse($con,$queryHours2);
				oci_execute($resultHours2);
				while($rowHours2 = oci_fetch_row($resultHours2))
				{
					$vHours2 = $rowHours2[0];
					if(strlen($vHours2) == 3){
						$vHours2 = $vHours2 . "0";
					}
					if(strlen($vHoursSPL2) == 3){
						$vHoursSPL2 = $vHoursSPL2 . "0";
					}
					//echo $vHours ." dan ". $vHoursSPL;
					if($vHours2 == $vHoursSPL2){
						$vHoursPenguranan2 = 1;
					}
				}
				//echo $vHoursPenguranan;
				$vJamLembur2 = $rowJamLembur12[0]; 
				$ArrTempMasuk2 = explode(":", $vJamLembur2);
				$vTempJamMasuk2 = $ArrTempMasuk2[0];
				$vTempMenitMasuk2 = $ArrTempMasuk2[1];
				$vTempJamMasuk2 = $vTempJamMasuk2 + ($vTempMenitMasuk2/60) - $vHoursPenguranan2;
				
				if($vTotJamLembur2 < $vBatasTotJamLembur2){
					if($vTempJamMasuk2 > 0){
						$vTotJamLemburTemp2 = $vTotJamLembur2 + round($vTempJamMasuk2, 2, PHP_ROUND_HALF_DOWN);
						if($vTotJamLemburTemp2 > $vBatasTotJamLembur2){
							$vTotJamLemburTemp2 = $vBatasTotJamLembur2 - $vTotJamLembur2;
							$vTotUangLembur2 = $vTotUangLembur2 + ($vTotJamLemburTemp2 * $vUangLembur);
							$vTotJamLembur2 = $vTotJamLembur2 + $vTotJamLemburTemp2;
							//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						} else {
							$vTotUangLembur2 = $vTotUangLembur2 + (round($vTempJamMasuk2, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
							$vTotJamLembur2 = $vTotJamLembur2 + round($vTempJamMasuk2, 2, PHP_ROUND_HALF_DOWN);
							//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						}
					}
				}
			}
			
			//Query untuk menghitung jumlah ijin
			/* $queryHariIjin2 = "SELECT COUNT(-1) JumIjin FROM
			(
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('IJIN')
				AND MTT.PERSON_ID=$vPersonId
				UNION
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Ijin') AND IJIN_KHUSUS IS NULL 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('ALPHA')
				AND MTT.PERSON_ID=$vPersonId
			)"; */
			$queryHariIjin2 = "SELECT COUNT(-1) JumIjin FROM
			(
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Ijin') AND IJIN_KHUSUS IS NOT NULL AND TANGGAL_FROM = MTT.TANGGAL
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('IJIN')
				AND IJIN_KHUSUS IS NULL
				AND MTT.PERSON_ID=$vPersonId
				UNION
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Ijin') AND IJIN_KHUSUS IS NULL 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('ALPHA')
				AND MTT.PERSON_ID=$vPersonId
			)";
			
			//echo $queryJumHari;
			$resultHariIjin2 = oci_parse($con,$queryHariIjin2);
			oci_execute($resultHariIjin2);
			while($rowHariIjin2 = oci_fetch_row($resultHariIjin2))
			{
				$vHariIjin2 = $rowHariIjin2[0]; 
			}
			
			$vTotUangIjin2 = $vHariIjin2 * $vPG;
			//Query untuk menghitung jumlah alpha
			$queryHariAlpha2 = "SELECT COUNT(-1) JumIjin FROM
			(
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1 AND KATEGORI<>'Terlambat'
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND MME.ELEMENT_NAME IN ('ALPHA')
				AND MTS.ID IS NULL
				AND MTT.PERSON_ID=$vPersonId
			)";
			//echo $queryJumHari;
			$resultHariAlpha2 = oci_parse($con,$queryHariAlpha2);
			oci_execute($resultHariAlpha2);
			while($rowHariAlpha2 = oci_fetch_row($resultHariAlpha2))
			{
				$vHariAlpha2 = $rowHariAlpha2[0]; 
			}
			
			$vTotUangAlpha2 = 2 * $vHariAlpha2 * $vPG;
			
			$vTotUangAbsen2 = $vTotUangAlpha2 + $vTotUangIjin2;
			
			$vTD2 = ((($vGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vTotUangMakan2 + $vTotUangTrans2) * 0.2) / 25);
			
			//Query untuk menghitung terlambat1
			$queryTerlambat12 = "SELECT COUNT(-1)
			FROM (
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
				AND MME.ELEMENT_NAME IN ('TERLAMBAT')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:06:00', 'HH24:MI:SS')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:21:59', 'HH24:MI:SS')
				AND MTS.ID IS NULL
				AND MTT.PERSON_ID=$vPersonId
				UNION
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
				AND MME.ELEMENT_NAME IN ('TERLAMBAT')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:06:00', 'HH24:MI:SS')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:21:59', 'HH24:MI:SS')
				AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI'))
				--AND MTS.ID IS NULL
				AND MTT.PERSON_ID=$vPersonId
			)";
			//echo $queryJumHari;
			$resultTerlambat12 = oci_parse($con,$queryTerlambat12);
			oci_execute($resultTerlambat12);
			while($rowTerlambat12 = oci_fetch_row($resultTerlambat12))
			{
				$vTerlambat12 = $rowTerlambat12[0]; 
			}
			
			//Query untuk menghitung terlambat2
			$queryTerlambat22 = "SELECT COUNT(-1)
			FROM (
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
				AND MME.ELEMENT_NAME IN ('TERLAMBAT')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:22:00', 'HH24:MI:SS')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:51:59', 'HH24:MI:SS')
				AND MTS.ID IS NULL
				AND MTT.PERSON_ID=$vPersonId
				UNION
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
				AND MME.ELEMENT_NAME IN ('TERLAMBAT')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:22:00', 'HH24:MI:SS')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:51:59', 'HH24:MI:SS')
				AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI'))
				--AND MTS.ID IS NULL
				AND MTT.PERSON_ID=$vPersonId
			)";
			//echo $queryJumHari;
			$resultTerlambat22 = oci_parse($con,$queryTerlambat22);
			oci_execute($resultTerlambat22);
			while($rowTerlambat22 = oci_fetch_row($resultTerlambat22))
			{
				$vTerlambat22 = $rowTerlambat22[0]; 
			}
			
			//Query untuk menghitung terlambat3
			$queryTerlambat32 = "SELECT COUNT(-1)
			FROM (
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
				AND MME.ELEMENT_NAME IN ('TERLAMBAT')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:52:00', 'HH24:MI:SS')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('09:21:59', 'HH24:MI:SS')
				AND MTS.ID IS NULL
				AND MTT.PERSON_ID=$vPersonId
				UNION
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
				AND MME.ELEMENT_NAME IN ('TERLAMBAT')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:52:00', 'HH24:MI:SS')
				AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('09:21:59', 'HH24:MI:SS')
				AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI'))
				--AND MTS.ID IS NULL
				AND MTT.PERSON_ID=$vPersonId
			)";
			//echo $queryJumHari;
			$resultTerlambat32 = oci_parse($con,$queryTerlambat32);
			oci_execute($resultTerlambat32);
			while($rowTerlambat32 = oci_fetch_row($resultTerlambat32))
			{
				$vTerlambat32 = $rowTerlambat32[0]; 
			}
			
			//Query untuk menghitung terlambat4
			/* Backup 
			$queryTerlambat42 = "SELECT COUNT(-1)
			FROM (
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1 AND (KATEGORI = 'Terlambat' OR IJIN_KHUSUS = 'SETENGAH HARI')
				LEFT JOIN 
				(   
					SELECT MTT2.PERSON_ID, MTT2.TANGGAL, MTS.ID ID_SIK, MTT2.STATUS
					FROM MJ.MJ_T_TIMECARD MTT2 
					LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=MTT2.PERSON_ID AND MTS.STATUS_DOK='Approved' 
					AND MTT2.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
					AND MTS.STATUS=1
					AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG')
					WHERE TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
					AND TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
					AND MTT2.STATUS IN (247, 248)
					AND MTT2.PERSON_ID=$vPersonId
				) SIK ON SIK.TANGGAL = MTT.TANGGAL AND SIK.PERSON_ID = MTT.PERSON_ID
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<'2018-05-01'
				AND MTT.STATUS = 252
				AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('09:22:00', 'HH24:MI:SS')
				AND MTS.ID IS NULL
				AND (SIK.ID_SIK IS NOT NULL OR SIK.STATUS = 248)
				AND MTT.PERSON_ID=$vPersonId
				UNION
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1 AND (KATEGORI = 'Terlambat' OR IJIN_KHUSUS = 'SETENGAH HARI')
				LEFT JOIN 
				(   
					SELECT MTT2.PERSON_ID, MTT2.TANGGAL, MTS.ID ID_SIK, MTT2.STATUS
					FROM MJ.MJ_T_TIMECARD MTT2 
					LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=MTT2.PERSON_ID AND MTS.STATUS_DOK='Approved' 
					AND MTT2.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
					AND MTS.STATUS=1
					AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG')
					WHERE TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
					AND TO_CHAR(MTT2.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
					AND MTT2.STATUS IN (247, 248)
					AND MTT2.PERSON_ID=$vPersonId
				) SIK ON SIK.TANGGAL = MTT.TANGGAL AND SIK.PERSON_ID = MTT.PERSON_ID
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
				AND MTT.STATUS = 252
				AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('09:22:00', 'HH24:MI:SS')
				AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')) 
				--AND MTS.ID IS NULL
				AND (SIK.ID_SIK IS NOT NULL OR SIK.STATUS = 248)
				AND MTT.PERSON_ID=$vPersonId
			)"; */
			
			$queryTerlambat42 = "SELECT COUNT(-1)
			FROM (SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTS.STATUS_DOK='Approved' 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$tahun-$bulanAngka-21'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vTglResign'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='2018-05-01'
				AND MME.ELEMENT_NAME IN ('TERLAMBAT')
				AND TO_DATE(MTT.JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('09:22:00', 'HH24:MI:SS')
				AND (IJIN_KHUSUS IS NULL OR IJIN_KHUSUS NOT IN ('TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')) 
				--AND MTS.ID IS NULL
				AND MTT.PERSON_ID=$vPersonId
			)";
			//echo $queryJumHari;
			$resultTerlambat42 = oci_parse($con,$queryTerlambat42);
			oci_execute($resultTerlambat42);
			while($rowTerlambat42 = oci_fetch_row($resultTerlambat42))
			{
				$vTerlambat42 = $rowTerlambat42[0]; 
			}
			
			$vUangTerlambat12 = $vTD2 * $vTerlambat12;
			$vUangTerlambat22 = 2 * $vTD2 * $vTerlambat22;
			$vUangTerlambat32 = 3 * $vTD2 * $vTerlambat32;
			$vUangTerlambat42 = $vPG * $vTerlambat42;
			$vTotUangTerlambat2 = $vUangTerlambat12 + $vUangTerlambat22 + $vUangTerlambat32 + $vUangTerlambat42;
			
			//$vTotGajiPlus2 = $vTotGajiPokok2 + $vTotUangMakan2 + $vTotUangTrans2 + $vTotTunKer2 + $vTotUangLembur2;
			//$vTotGajiMinus2 = $vTotUangAbsen2 + $vTotUangTerlambat2;
			$vTotGajiSkr2 = ($vTotGajiPokok2 + $vTotUangMakan2 + $vTotUangTrans2 + $vTotTunKer2 + $vTotUangLembur2) - ($vTotUangAbsen2 + $vTotUangTerlambat2);
			$vTotGajiTransfer2 = $vTotGajiSkr2;
		}
		
		// $vTotGajiPlus = $vTotGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vTotUangMakan + $vTotUangTrans + $vTotTunKer + $vTotUangLembur + $vRevisiGaji;
		// $vTotGajiMinus = $vPotongan + $vBS + $vTotUangIjin + $vTotUangAlpha + $vTotUangAbsen + $vTotUangTerlambat + $vBPJSKS + $vBPJSTK;
		$vTotGajiSkr = ($vTotGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vTotUangMakan + $vTotUangTrans + $vTotTunKer + $vTotUangLembur) - ($vPotongan + $vBS + $vTotUangAbsen + $vTotUangTerlambat + $vBPJSKS + $vBPJSTK);
		$vTotGajiTransfer = $vTotGajiSkr + $vRevisiGaji;
		
		//$vHariAktifResign = $vHariAktif + $vHariAktif2; 
		$vJumMasuk = $vJumMasuk + $vJumMasuk2; 
		$vHariSakit = $vHariSakit + $vHariSakit2;
		$vHariCuti = $vHariCuti + $vHariCuti2;
		$vHariIjin = $vHariIjin + $vHariIjin2;
		$vHariAlpha = $vHariAlpha + $vHariAlpha2;
		$vTerlambat1 = $vTerlambat1 + $vTerlambat12;
		$vTerlambat2 = $vTerlambat2 + $vTerlambat22;
		$vTerlambat3 = $vTerlambat3 + $vTerlambat32;
		$vTerlambat4 = $vTerlambat4 + $vTerlambat42;
		$vTotGajiPokok = $vTotGajiPokok + $vTotGajiPokok2;
		$vTotUangMakan = $vTotUangMakan + $vTotUangMakan2;
		$vTotUangTrans = $vTotUangTrans + $vTotUangTrans2;
		$vTotTunKer = $vTotTunKer + $vTotTunKer2;
		$vTotUangLembur = $vTotUangLembur + $vTotUangLembur2;
		$vTotUangAbsen = $vTotUangAbsen + $vTotUangAbsen2;
		$vTotUangTerlambat = $vTotUangTerlambat + $vTotUangTerlambat2;
		
		//$vTotGajiPlus = $vTotGajiPlus + $vTotGajiPlus2;
		//$vTotGajiMinus = $vTotGajiMinus + $vTotGajiMinus2;
		$vTotGajiSkr = $vTotGajiSkr + $vTotGajiSkr2;
		$vTotGajiTransfer = $vTotGajiTransfer + $vTotGajiTransfer2;
		
		$pending_gaji = 0;
		$queryPending = "select pg.assignment_id, upper(pg.periode_gaji), pg.satuan, pg.nominal
			, pg.periode_awal, pg.periode_akhir, pg.keterangan
			from mj.mj_t_pending_gaji pg
			inner join per_assignments_f paf on pg.assignment_id = paf.assignment_id 
			AND (TRUNC(SYSDATE) BETWEEN PAF.EFFECTIVE_START_DATE AND PAF.EFFECTIVE_END_DATE) AND PAF.PRIMARY_FLAG='Y' 
			WHERE periode_awal <= to_date('$vPeriode2', 'YYYY-MM-DD')
				AND nvl(periode_akhir, to_date('$vPeriode1','YYYY-MM-DD')) >= to_date('$vPeriode1','YYYY-MM-DD')
				and paf.person_id = $vPersonId";
		//echo $queryCountMasuk;
		$resultPending = oci_parse($con,$queryPending);
		oci_execute($resultPending);
		while($rowPending = oci_fetch_row($resultPending))
		{
			$assign_id = $rowPending[0]; 
			$periode_gaji = $rowPending[1]; 
			$satuan = $rowPending[2]; 
			$nominal = $rowPending[3]; 
			$tgl_pending_dari = $rowPending[4]; 
			$tgl_pending_sampai = $rowPending[5]; 
			$ket_pending = $rowPending[6]; 
			
			if($periode_gaji == 'BULANAN'){
				if($satuan == 'RP'){
					$pending_gaji = $nominal;
				}else{
					$pending_gaji = $vTotGajiTransfer * ($nominal/100);
				}
			}
		}
		
		$vTotGajiTransfer = $vTotGajiSkr + $vRevisiGaji - $pending_gaji;
		
		// echo 'Full Name : ' . $vFullName ;
		// echo '<br>Gaji Pokok 25: ' . $vGajiPokok;
		// echo '<br>Gaji Pokok : ' . $vTotGajiPokok;
		// echo '<br>Tunjangan Jab : ' . $vTunJab;
		// echo '<br>Tunjangan Grade : ' . $vTunGrade;
		// echo '<br>Tunjangan Lokasi : ' . $vTunLok;
		// echo '<br>Hari Masuk All : ' . $vHariMasuk;
		// echo '<br>Hari Masuk : ' . $vJumMasuk;
		// echo '<br>Uang Makan : ' . $vUangMakan;
		// echo '<br>Uang Trans : ' . $vUangTrans;
		// echo '<br>Total Uang Makan : ' . $vTotUangMakan;
		// echo '<br>Total Uang Trans : ' . $vTotUangTrans;
		// echo '<br>Total Tunjangan Kerajinan : ' . $vTotTunKer;
		// echo '<br>Uang Lembur : ' . $vTotUangLembur;
		// echo '<br>PPH21 : ' . '0';
		// echo '<br>Pinjaman : ' . $vPotongan;
		// echo '<br>BS : ' . $vBS;
		// echo '<br>Total Uang Ijin : ' . $vTotUangIjin;
		// echo '<br>Total Uang Alpha : ' . $vTotUangAlpha;
		// echo '<br>Total Uang Absen : ' . $vTotUangAbsen;
		// echo '<br>TD : ' . $vTD;
		// echo '<br>Uang Terlambat1 : ' . $vUangTerlambat1;
		// echo '<br>Uang Terlambat2 : ' . $vUangTerlambat2;
		// echo '<br>Uang Terlambat3 : ' . $vUangTerlambat3;
		// echo '<br>Uang Terlambat4 : ' . $vUangTerlambat4;
		// echo '<br>Total Uang Terlambat : ' . $vTotUangTerlambat;
		// echo '<br>BPJS KS : ' . $vBPJSKS;
		// echo '<br>BPJS TK : ' . $vBPJSTK;
		// echo '<br>Revisi Gaji : ' . $vRevisiGaji;
		// echo '<br>Total Transfer : ' . $vTotGajiTransfer;
		$countHeader++;
		$xlsRow++;
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A2', 'Jumlah Hari Aktif : ' . $vHariAktif)
					->setCellValue('D2', 'Jumlah Hari Aktif Resign : ' . $vHariAktif2)
					->setCellValue('H2', 'Periode Gaji : ' . $vPeriode1." s/d ".$vPeriode2)
					->setCellValue('A' . $xlsRow, $countHeader)
					->setCellValue('B' . $xlsRow, $vEmpNumber)
					->setCellValue('C' . $xlsRow, $vIdFinger)
					->setCellValue('D' . $xlsRow, $vFullName)
					->setCellValue('E' . $xlsRow, $vOrg)
					->setCellValue('F' . $xlsRow, $vDept)
					->setCellValue('G' . $xlsRow, $vJabatan)
					->setCellValue('H' . $xlsRow, $vLokasi)
					->setCellValue('I' . $xlsRow, $hireDate)
					->setCellValue('J' . $xlsRow, $vJumMasuk)
					->setCellValue('K' . $xlsRow, $vHariSakit)
					->setCellValue('L' . $xlsRow, $vHariCuti)
					->setCellValue('M' . $xlsRow, $vHariIjin)
					->setCellValue('N' . $xlsRow, $vHariAlpha)
					->setCellValue('O' . $xlsRow, $vTerlambat1)
					->setCellValue('P' . $xlsRow, $vTerlambat2)
					->setCellValue('Q' . $xlsRow, $vTerlambat3)
					->setCellValue('R' . $xlsRow, $vTerlambat4)
					->setCellValue('S' . $xlsRow, $vTotGajiPokok)
					->setCellValue('T' . $xlsRow, $vTunJab)
					->setCellValue('U' . $xlsRow, $vTunLok)
					->setCellValue('V' . $xlsRow, $vTunGrade)
					->setCellValue('W' . $xlsRow, $vTotUangMakan)
					->setCellValue('X' . $xlsRow, $vTotUangTrans)
					->setCellValue('Y' . $xlsRow, $vTotTunKer)
					->setCellValue('Z' . $xlsRow, $vTotUangLembur)
					->setCellValue('AA' . $xlsRow, 0)
					->setCellValue('AB' . $xlsRow, $vBPJSKS)
					->setCellValue('AC' . $xlsRow, $vBPJSTK)
					->setCellValue('AD' . $xlsRow, $vPotongan)
					->setCellValue('AE' . $xlsRow, $vBS)
					->setCellValue('AF' . $xlsRow, $vTotUangAbsen)
					->setCellValue('AG' . $xlsRow, $vTotUangTerlambat)
					->setCellValue('AH' . $xlsRow, $vTotGajiSkr)
					->setCellValue('AI' . $xlsRow, $vRevisiGaji)
					->setCellValue('AJ' . $xlsRow, $pending_gaji)
					->setCellValue('AK' . $xlsRow, $vTotGajiTransfer)
					->setCellValue('AL' . $xlsRow, $Hours)
					->setCellValue('AM' . $xlsRow, $tglHours);
	}
	
	
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save($namaFile);

header('Content-Length: ' . filesize($namaFile));
readfile($namaFile);
?>