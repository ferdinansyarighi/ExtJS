<?php
require('../../pdf/pdfmctable.php');

include '../../main/koneksi.php';

$nama=""; 
$plant=""; 
$periode=""; 
$vPeriode1="";
$vPeriode2="";
$queryfilter=""; 
$queryfilterDetail ="";
$tglskr=date('Y-m-d'); 
$tglskrdipakai=date('d F Y'); 
$tahunbaru=substr($tglskr, 0, 2);
$hari="";
$bulan="";
$tahun=""; 
$bulanAngka="";
$bulanBesok="";
if (isset($_GET['bulan']) || isset($_GET['tahun']))
{	
	//$rekening = trim($_GET['rekening']);
	$bulan = $_GET['bulan'];
	$tahun = $_GET['tahun'];
	
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

class myPdf extends pdfmctable
{
	var $title;
	var $numtitle;
	
	function setTitle($title, $numtitle){
		$this->title	= $title;
		$this->numtitle	= $numtitle;
	}
		
	function Header()
	{
		$this->SetFont('Arial', '',9);
		$this->SetFillColor(255,255,255);
		$this->SetTextColor(0);
			
		if($this->PageNo() == 1)
		{
			
		}	

	}			

	function Footer()
	{
		$tglskr=date('d F Y'); 
	    $this->SetY(-15);
	    $this->SetFont('Arial','I',8);
	    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	    $this->Cell(0,10,'Tanggal Cetak : '.$tglskr,0,0,'R');
	}
}		

// Out of the class
// Legal paper size: 355.6 mm x 215.9 mm
$pdf=new myPdf('L','mm','legal');
$pdf->AliasNbPages();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage('L','A4','legal');				
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial', '',9);

$queryGaji = "select  distinct P.PERSON_ID
	,p.employee_number
	,p.honors id_finger
	,P.FULL_NAME
	,REGEXP_SUBSTR(j.name, '[^.]+', 1, 3) as dept
	,REGEXP_SUBSTR(pos.name, '[^.]+', 1, 3) as jabatan
	,hl.location_code
	,TO_CHAR(P.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS ORIGINAL_DATE_OF_HIRE
	,gp.screen_entry_value gaji_pokok
	,tj.screen_entry_value tunj_jab
	,tg.screen_entry_value tunj_grade
	,tl.screen_entry_value tunj_lok
	,tk.screen_entry_value tunj_ker
	,um.screen_entry_value uang_makan
	,ut.screen_entry_value uang_trans
	,ul.screen_entry_value uang_lembur
	,((gp.screen_entry_value + tj.screen_entry_value + tg.screen_entry_value + tl.screen_entry_value) / 25) AS PG 
	,nvl(pot.harga_cicilan_p,0) cicilan_pinjaman
	,nvl(pot.harga_cicilan_b,0) cicilan_bs
	,nvl(bpjsks.screen_entry_value,0)/100*FGFKS.GLOBAL_VALUE pot_bpjs_ks
	,nvl(bpjstk.screen_entry_value,0)/100*FGFTK.GLOBAL_VALUE pot_bpjs_tk
	,nvl(revgaji.screen_entry_value, 0) revisi_gaji
	,NVL(TO_CHAR(P.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '1990-01-01') AS TGL_RESIGN
	,TRIM(REGEXP_SUBSTR(P.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)) AS NO_REKENING
	,TRIM(REGEXP_SUBSTR(P.PREVIOUS_LAST_NAME, '[^-]+', 1, 3)) AS ATAS_NAMA
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
				,(select person_id, outstanding_p, outstanding_b, harga_cicilan_p, harga_cicilan_b
					from mj_t_potongan)pot
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
				order by peef.assignment_id)revgaji
	where   p.person_id=a.person_id
	--and     p.person_id=2908
	and     a.job_id=j.job_id(+)
	and     REGEXP_SUBSTR(j.name, '[^.]+', 1, 1)=tl.flex_value_meaning(+)
	and     a.position_id=pos.position_id(+)
	and     a.location_id=hl.location_id(+)
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
	--and     p.middle_names IN ('ADA')
	--order by to_number(p.employee_number) ASC
	AND a.PAYROLL_ID IS NOT NULL
	AND a.PRIMARY_FLAG='Y'
	and p.person_id=pot.person_id(+)
	AND TRIM(REGEXP_SUBSTR(FGFTK.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjstk.element_name, '[^_]+', 1, 4))
	AND TRIM(REGEXP_SUBSTR(FGFKS.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjsks.element_name, '[^_]+', 1, 4))";
		//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$vTotGajiPokok = 0;
		$vTotGajiPokokTRP = 0;
		$vTotTunKer = 0;
		$vTotUangMakan = 0;
		$vTotUangTrans = 0;
		$vTotUangLembur = 0;
		$vTotPotongan = 0;
		$vTotBS = 0;
		$vPotongan = 0; 
		$vBS = 0; 
		$vHariMasuk = 0;
		$vHariMasukStandart = 0;
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
		// $vTotGajiPlus = 0;
		// $vTotGajiMinus = 0;
		
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
		$vNoReg = $row[23];
		$vAtasNama = $row[24];
		
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
			$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
			(SELECT LEVEL AS DNUM FROM DUAL
			CONNECT BY (TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') - TO_DATE('$hireDate', 'YYYY-MM-DD') + 1) - LEVEL >= 0) S
			WHERE TO_CHAR(TO_DATE('$hireDate', 'YYYY-MM-DD') + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
			(SELECT COUNT(-1)
			FROM APPS.HXT_HOLIDAY_DAYS A
			, APPS.HXT_HOLIDAY_CALENDARS B
			WHERE A.HCL_ID=B.ID 
			AND B.EFFECTIVE_END_DATE>SYSDATE 
			AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$hireDate' AND '$tahun-$bulanAngka-21')) TES
			FROM DUAL";
			//echo $queryJumHari;
			$resultJumHari = oci_parse($con,$queryJumHari);
			oci_execute($resultJumHari);
			while($rowJumHari = oci_fetch_row($resultJumHari))
			{
				$vHariMasuk = $rowJumHari[0]; 
			}
			
			$vHariMasukStandart = $vHariMasuk;
			$vTotGajiPokokTRP = ($vGajiPokok/25);
			$vTotGajiPokok = $vHariMasuk * ($vGajiPokok/25);
			$vTotTunKer = $vHariMasuk * ($vTunKer/25);
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
				CONNECT BY (TO_DATE('$vTglResign', 'YYYY-MM-DD') - ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1) + 1) - LEVEL >= 0) S
				WHERE TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
				(SELECT COUNT(-1)
				FROM APPS.HXT_HOLIDAY_DAYS A
				, APPS.HXT_HOLIDAY_CALENDARS B
				WHERE A.HCL_ID=B.ID 
				AND B.EFFECTIVE_END_DATE>SYSDATE 
				AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN TO_CHAR(ADD_MONTHS(TO_DATE('$tahun-$bulanAngka-21', 'YYYY-MM-DD'), -1), 'YYYY-MM-DD') AND '$vTglResign')) TES
				FROM DUAL";
				//echo $queryJumHari;
				$resultJumHari = oci_parse($con,$queryJumHari);
				oci_execute($resultJumHari);
				while($rowJumHari = oci_fetch_row($resultJumHari))
				{
					$vHariMasuk = $rowJumHari[0]; 
				}
				//echo $vHariMasuk . " dan " . $vGajiPokok;
				$vHariMasukStandart = $vHariMasuk;
				$vTotGajiPokokTRP = ($vGajiPokok/25);
				$vTotGajiPokok = $vHariMasuk * ($vGajiPokok/25);
				$vTotTunKer = $vHariMasuk * ($vTunKer/25);
			} else {
				$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
				(SELECT LEVEL AS DNUM FROM DUAL
				CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - TO_DATE('$vPeriode1', 'YYYY-MM-DD')) - LEVEL >= 0) S
				WHERE TO_CHAR(TO_DATE('$vPeriode1', 'YYYY-MM-DD') + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) + 1 -
				(SELECT COUNT(-1)
				FROM APPS.HXT_HOLIDAY_DAYS A
				, APPS.HXT_HOLIDAY_CALENDARS B
				WHERE A.HCL_ID=B.ID 
				AND B.EFFECTIVE_END_DATE>SYSDATE 
				AND TO_CHAR(A.HOLIDAY_DATE, 'YYYY-MM-DD') BETWEEN '$vPeriode1' AND '$vPeriode2')) TES
				FROM DUAL";
				//echo $queryJumHari;
				$resultJumHari = oci_parse($con,$queryJumHari);
				oci_execute($resultJumHari);
				while($rowJumHari = oci_fetch_row($resultJumHari))
				{
					$vHariMasuk = $rowJumHari[0]; 
				}
				
				$vHariMasukStandart = 25;
				$vTotGajiPokokTRP = ($vGajiPokok/25);
				$vTotGajiPokok = $vGajiPokok;
				$vTotTunKer = $vTunKer;
			}
		}
		
		$queryJumMasuk = "SELECT SUM(MASUK) JumMasuk FROM
		(
			SELECT COUNT(-1) AS MASUK
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('MASUK')
			AND MTT.PERSON_ID=$vPersonId
			UNION ALL
			SELECT COUNT(-1) MASUK 
            FROM (
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI='Ijin' 
				AND MTS.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MME.ELEMENT_NAME IN ('ALPHA')
				AND MTT.PERSON_ID=$vPersonId
			)
		)";
		//echo $queryJumHari;
		$resultJumMasuk = oci_parse($con,$queryJumMasuk);
		oci_execute($resultJumMasuk);
		$rowJumMasuk = oci_fetch_row($resultJumMasuk);
		$vJumMasuk = $rowJumMasuk[0]; 
		
		$vTotUangMakan = $vJumMasuk * $vUangMakan;
		$vTotUangTrans = $vJumMasuk * $vUangTrans;
		
		if($vHariMasuk != $vJumMasuk){
			$vTotTunKer = 0;
		}
		
		$queryJumCuti = "SELECT SUM(CUTI) JumCuti FROM
        (
            SELECT COUNT(-1) AS CUTI
            FROM MJ.MJ_T_TIMECARD MTT
            INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
            WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
            AND MME.ELEMENT_NAME IN ('CUTI')
            AND MTT.PERSON_ID=$vPersonId
            UNION ALL
			SELECT COUNT(-1) CUTI 
            FROM (
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' 
				AND (MTS.KATEGORI='Cuti' OR (MTS.KATEGORI='Ijin' 
                AND NVL(MTS.IJIN_KHUSUS, 'Kosong') NOT IN ('Kosong', 'LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')))
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MME.ELEMENT_NAME IN ('ALPHA')
				AND MTT.PERSON_ID=$vPersonId
			)
        )";
		//echo $queryJumHari;
		$resultJumCuti = oci_parse($con,$queryJumCuti);
		oci_execute($resultJumCuti);
		while($rowJumCuti = oci_fetch_row($resultJumCuti))
		{
			$vHariCuti = $rowJumCuti[0]; 
		}
		
		$queryJumSakit = "SELECT SUM(SAKIT) JumSakit FROM
        (
            SELECT COUNT(-1) AS SAKIT
            FROM MJ.MJ_T_TIMECARD MTT
            INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
            WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
            AND MME.ELEMENT_NAME IN ('SAKIT')
            AND MTT.PERSON_ID=$vPersonId
            UNION ALL
			SELECT COUNT(-1) SAKIT 
            FROM (
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Sakit') 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MME.ELEMENT_NAME IN ('ALPHA')
				AND MTT.PERSON_ID=$vPersonId
			)
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
		
		$queryJamLembur = "SELECT MTSD.TOTAL_JAM, NVL(MTT.JAM_MASUK, '16:00'), NVL(MTT.JAM_KELUAR, NVL(MTSI.JAM_TO, NVL(MTT2.JAM_KELUAR, '23:59'))), MTS.TANGGAL_SPL        
		FROM MJ.MJ_T_SPL MTS
		INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
		LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253 AND MTT.PERSON_ID=$vPersonId
		LEFT JOIN MJ.MJ_T_TIMECARD MTT2 ON MTT2.TANGGAL=MTS.TANGGAL_SPL AND MTT2.STATUS=248 AND MTT2.PERSON_ID=$vPersonId
		LEFT JOIN MJ.MJ_T_SIK MTSI ON MTS.TANGGAL_SPL BETWEEN MTSI.TANGGAL_FROM AND MTSI.TANGGAL_TO
		AND MTSI.STATUS_DOK='Approved' AND MTSI.STATUS=1 
		AND MTSI.PEMOHON=MTSD.NAMA AND KATEGORI='Ijin' AND IJIN_KHUSUS IS NOT NULL
		AND MTSI.IJIN_KHUSUS IN ('LUPA ABSEN PULANG', 'LUPA ABSEN DATANG', 'TIDAK ABSEN KARENA URUSAN KANTOR', 'SETENGAH HARI')
		WHERE MTSD.STATUS_DOK='Approved' AND MTS.STATUS=1
		AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$vPeriode1'
		AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$vPeriode2'
		AND MTSD.NAMA=(SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE EFFECTIVE_END_DATE > SYSDATE AND PERSON_ID=$vPersonId)
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
			
			if($vTotJamLembur < 25){
				if($vTotSPL > $vTempJamMasuk){
					if($vTempJamMasuk > 0){
						$vTotJamLemburTemp = $vTotJamLembur + round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
						if($vTotJamLemburTemp > 25){
							$vTotJamLemburTemp = 25 - $vTotJamLembur;
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
						if($vTotJamLemburTemp > 25){
							$vTotJamLemburTemp = 25 - $vTotJamLembur;
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
		
		$queryHariIjin = "SELECT SUM(IJIN) JumIjin FROM
		(
			SELECT COUNT(-1) AS IJIN
			FROM MJ.MJ_T_TIMECARD MTT
			INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
			WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
			AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
			AND MME.ELEMENT_NAME IN ('IJIN')
			AND MTT.PERSON_ID=$vPersonId
			UNION ALL
			SELECT COUNT(-1) IJIN 
            FROM (
				SELECT DISTINCT MTT.TANGGAL
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' AND MTS.KATEGORI IN ('Ijin') AND IJIN_KHUSUS IS NULL 
				AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
				AND MTS.STATUS=1
				WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
				AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
				AND MME.ELEMENT_NAME IN ('ALPHA')
				AND MTT.PERSON_ID=$vPersonId
			)
		)";
		//echo $queryJumHari;
		$resultHariIjin = oci_parse($con,$queryHariIjin);
		oci_execute($resultHariIjin);
		while($rowHariIjin = oci_fetch_row($resultHariIjin))
		{
			$vHariIjin = $rowHariIjin[0]; 
		}
		
		$vTotUangIjin = $vHariIjin * $vPG;
		
		$queryHariAlpha = "SELECT COUNT(-1) AS ALPHA
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
		INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' 
		AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
		AND MTS.STATUS=1 AND KATEGORI<>'Terlambat'
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('ALPHA')
		AND MTS.ID IS NULL
		AND MTT.PERSON_ID=$vPersonId";
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
		
		$queryTerlambat1 = "SELECT COUNT(-1)
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
        INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
        LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' 
        AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
        AND MTS.STATUS=1
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('TERLAMBAT')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:06:00', 'HH24:MI:SS')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:21:59', 'HH24:MI:SS')
        AND MTS.ID IS NULL
		AND MTT.PERSON_ID=$vPersonId";
		//echo $queryJumHari;
		$resultTerlambat1 = oci_parse($con,$queryTerlambat1);
		oci_execute($resultTerlambat1);
		while($rowTerlambat1 = oci_fetch_row($resultTerlambat1))
		{
			$vTerlambat1 = $rowTerlambat1[0]; 
		}
		
		$queryTerlambat2 = "SELECT COUNT(-1)
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
        INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
        LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' 
        AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
        AND MTS.STATUS=1
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('TERLAMBAT')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:22:00', 'HH24:MI:SS')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:51:59', 'HH24:MI:SS')
        AND MTS.ID IS NULL
		AND MTT.PERSON_ID=$vPersonId";
		//echo $queryJumHari;
		$resultTerlambat2 = oci_parse($con,$queryTerlambat2);
		oci_execute($resultTerlambat2);
		while($rowTerlambat2 = oci_fetch_row($resultTerlambat2))
		{
			$vTerlambat2 = $rowTerlambat2[0]; 
		}
		
		$queryTerlambat3 = "SELECT COUNT(-1)
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
        INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
        LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' 
        AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
        AND MTS.STATUS=1
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('TERLAMBAT')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:52:00', 'HH24:MI:SS')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('09:21:59', 'HH24:MI:SS')
        AND MTS.ID IS NULL
		AND MTT.PERSON_ID=$vPersonId";
		//echo $queryJumHari;
		$resultTerlambat3 = oci_parse($con,$queryTerlambat3);
		oci_execute($resultTerlambat3);
		while($rowTerlambat3 = oci_fetch_row($resultTerlambat3))
		{
			$vTerlambat3 = $rowTerlambat3[0]; 
		}
		
		$queryTerlambat4 = "SELECT COUNT(-1)
		FROM MJ.MJ_T_TIMECARD MTT
		INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS 
        INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
        LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PEMOHON=PPF.FULL_NAME AND MTS.STATUS_DOK='Approved' 
        AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO 
        AND MTS.STATUS=1
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('TERLAMBAT')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('09:22:00', 'HH24:MI:SS')
        AND MTS.ID IS NULL
		AND MTT.PERSON_ID=$vPersonId";
		//echo $queryJumHari;
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
		
		$vTotGajiPlus = $vTotGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vTotUangMakan + $vTotUangTrans + $vTotTunKer + $vTotUangLembur;
		$vTotGajiMinus = $vPotongan + $vBS + $vTotUangAbsen + $vTotUangTerlambat + $vBPJSKS + $vBPJSTK;
		$vTotGajiSkr = ($vTotGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vTotUangMakan + $vTotUangTrans + $vTotTunKer + $vTotUangLembur) - ($vPotongan + $vBS + $vTotUangAbsen + $vTotUangTerlambat + $vBPJSKS + $vBPJSTK);
		$vTotGajiTransfer = $vTotGajiSkr + $vRevisiGaji;
		
// Add some data
//echo date('H:i:s') , " Add some data" , EOL;
		$pdf->SetFont('Arial','',11);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','U');
		$pdf->Cell(335, 11, 'PT. MERAK JAYA BETON', 0, 0, 'C', 0);					
		$pdf->Ln(1);
		$pdf->Cell(70, 16, 'Slip Gaji Karyawan', 0, 0, 'L', 0);	
		$pdf->Cell(198, 16, 'Readymix & Precast Concrete Product', 0, 0, 'C', 0);	
		$pdf->Ln(13);	
		$pdf->SetFont('Arial','B',11);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->Cell(100, 15, 'Data Karyawan', 1, 0, 'C', 0);	
		$pdf->Cell(130, 7.5, 'Penghasilan', 1, 0, 'C', 0);				
		$pdf->Cell(70, 7.5, 'Potongan', 1, 0, 'C', 0);					
		$pdf->Cell(35, 7.5, 'Total', 1, 0, 'C', 0);						
		$pdf->Ln(7.5);		
		$pdf->Cell(100, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Cell(50, 7.5, 'Uraian', 1, 0, 'C', 0);	
		$pdf->Cell(10, 7.5, 'Hr', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, 'Trp', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, 'Rp', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, 'Uraian', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, 'Rp', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, 'Gaji', 1, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(70, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(50, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(10, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Cell(35, 60.5, '', 1, 0, 'C', 0);	
		$pdf->Ln(0.5);	
		$pdf->SetFont('Arial','',11);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);	
		$pdf->Cell(30, 7.5, 'Nama', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vFullName, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Gaji Pokok', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, $vHariMasukStandart, 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiPokokTRP, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiPokok, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'PPH21', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, '0', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'NIK', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vEmpNumber, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Jab', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTunJab, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'BPJS Kesehatan', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vBPJSKS, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'No. Face Attd', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vIdFinger, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Lokasi', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTunLok, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'BPJS TK', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vBPJSTK, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Department', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vDept, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Tunj. Grade', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTunGrade, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Pinjaman', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vPotongan, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Jabatan', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vJabatan, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Uang Makan', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, $vJumMasuk, 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, number_format($vUangMakan, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotUangMakan, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'BS', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vBS, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Location / Plant', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, $vLokasi, 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Uang Transport', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, $vJumMasuk, 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, number_format($vUangTrans, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotUangTrans, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Absen', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotUangAbsen, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Premi Hadir', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotTunKer, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Telat', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotUangTerlambat, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(70, 7.5, '', 0, 0, 'L', 0);	
		$pdf->Cell(50, 7.5, 'Lembur', 0, 0, 'L', 0);	
		$pdf->Cell(10, 7.5, '', 0, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotUangLembur, 2, ',', '.'), 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, 'Lain-lain', 0, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, '', 1, 0, 'L', 0);	
		$pdf->Cell(120, 7.5, 'Total', 1, 0, 'C', 0);	
		$pdf->Cell(10, 7.5, '', 1, 0, 'C', 0);		
		$pdf->Cell(35, 7.5, '', 1, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiPlus, 2, ',', '.'), 1, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, '', 1, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiMinus, 2, ',', '.'), 1, 0, 'R', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiSkr, 2, ',', '.'), 1, 0, 'R', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(30, 7.5, 'Ditransfer', 1, 0, 'L', 0);	
		$pdf->Cell(270, 7.5, 'No. Rek : ' . $vNoReg . " - " . $vAtasNama, 1, 0, 'L', 0);		
		$pdf->Cell(35, 7.5, number_format($vTotGajiSkr, 2, ',', '.'), 1, 0, 'R', 0);	
		$pdf->Ln(7.5);	
		$pdf->Cell(40, 7.5, 'Surabaya, ', 0, 0, 'R', 0);	
		$pdf->Cell(40, 7.5, $tglskrdipakai, 0, 0, 'R', 0);	
		$pdf->Ln(2);	
		$pdf->Cell(150, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Cell(100, 7.5, 'Catatan :', 0, 0, 'L', 0);	
		$pdf->Ln(1);	
		$pdf->Cell(150, 33, '', 0, 0, 'C', 0);	
		$pdf->Cell(170, 33, '', 1, 0, 'L', 0);
		$pdf->Ln(5.5);	
		$pdf->Cell(20, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Cell(40, 7.5, 'Bagian Personalia', 1, 0, 'C', 0);	
		$pdf->Cell(40, 7.5, 'Karyawan :', 1, 0, 'C', 0);			
		$pdf->Ln(7.5);	
		$pdf->Cell(20, 20, '', 0, 0, 'C', 0);	
		$pdf->Cell(40, 20, '', 1, 0, 'C', 0);	
		$pdf->Cell(40, 20, '', 1, 0, 'C', 0);	
		$pdf->Ln(2);	
		$pdf->Cell(150, 7.5, '', 0, 0, 'C', 0);	
		$pdf->Cell(120, 7.5, 'Revisi Gaji Bulan Lalu : ', 0, 0, 'L', 0);
		$pdf->Cell(50, 7.5, number_format($vRevisiGaji, 2, ',', '.'), 0, 0, 'R', 0);	
		$pdf->Ln(2);
		$pdf->SetFont('Arial','B',16);
		$pdf->SetFillColor(255,255,255);
		$pdf->SetTextColor(0);
		$pdf->SetDrawColor(0,0,0);
		$pdf->SetLineWidth(.3);		
		$pdf->Cell(150, 15, '', 0, 0, 'C', 0);	
		$pdf->Cell(120, 15, 'Total ditransfer : ', 0, 0, 'L', 0);	
		$pdf->Cell(50, 15, number_format($vTotGajiTransfer, 2, ',', '.'), 0, 0, 'R', 0);
		$pdf->Ln(100);
	}
	
//$pdf->SetFont('','B');

$pdf->Output();
?>
