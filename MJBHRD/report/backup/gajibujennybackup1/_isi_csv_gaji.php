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
$bulanAngka="";
$bulanBesok="";
if (isset($_GET['rekening']) || isset($_GET['bulan']) || isset($_GET['tahun']))
{	
	$rekening = trim($_GET['rekening']);
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
$namaFile = 'Rincian_Slip_Gaji.xls';

// function xlsBOF() {
// echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
// return;
// }

// // Function penanda akhir file (End Of File) Excel

// function xlsEOF() {
// echo pack("ss", 0x0A, 0x00);
// return;
// }

// // Function untuk menulis data (angka) ke cell excel

// function xlsWriteNumber($Row, $Col, $Value) {
// echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
// echo pack("d", $Value);
// return;
// }

// // Function untuk menulis data (text) ke cell excel

// function xlsWriteLabel($Row, $Col, $Value ) {
// $L = strlen($Value);
// echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
// echo $Value;
// return;
// }

// // header file excel
// header("Status: 200");
	// header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	// header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	// header("Pragma: hack");
	// header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	// header("Cache-Control: private", false);
	// header("Content-Description: File Transfer");
	// header("Content-Type: application/force-download");
	// header("Content-Type: application/download");
	// header("Content-Disposition: attachment; filename=\"".$namaFile."\""); 
	// header("Content-Transfer-Encoding: binary");

	// xlsBOF();
	
	// xlsWriteLabel(0,0,"Report Rincian Slip Gaji Karyawan");
	
	// xlsWriteLabel(2,0,"No.");
	// xlsWriteLabel(2,1,"NIK");
	// xlsWriteLabel(2,2,"No Face Attd");
	// xlsWriteLabel(2,3,"Nama Karyawan");
	// xlsWriteLabel(2,4,"Departemen");
	// xlsWriteLabel(2,5,"Jabatan");
	// xlsWriteLabel(2,6,"Lokasi / Plant");
	// xlsWriteLabel(2,7,"Hari Masuk");
	// xlsWriteLabel(2,8,"Hari Ijin");
	// xlsWriteLabel(2,9,"Hari Absen");
	// xlsWriteLabel(2,10,"Terlambat 1");
	// xlsWriteLabel(2,11,"Terlambat 2");
	// xlsWriteLabel(2,12,"Terlambat 3");
	// xlsWriteLabel(2,13,"Terlambat 4");
	// xlsWriteLabel(2,14,"Gaji Pokok Trp");
	// xlsWriteLabel(2,15,"Uang Makan Trp");
	// xlsWriteLabel(2,16,"Uang Transport Trp");
	// xlsWriteLabel(2,17,"Gaji Pokok");
	// xlsWriteLabel(2,18,"Tunjangan Jabatan");
	// xlsWriteLabel(2,19,"Tunjangan Lokasi");
	// xlsWriteLabel(2,20,"Tunjangan Grade");
	// xlsWriteLabel(2,21,"Uang Makan");
	// xlsWriteLabel(2,22,"Uang Transport");
	// xlsWriteLabel(2,23,"Premi Hadir");
	// xlsWriteLabel(2,24,"Lembur");
	// xlsWriteLabel(2,25,"PPH21");
	// xlsWriteLabel(2,26,"BPJS Kesehatan");
	// xlsWriteLabel(2,27,"BPJS TK");
	// xlsWriteLabel(2,28,"Pinjaman");
	// xlsWriteLabel(2,29,"BS");
	// xlsWriteLabel(2,30,"Absen");
	// xlsWriteLabel(2,31,"Telat");
	// xlsWriteLabel(2,32,"Total Gaji");
	// xlsWriteLabel(2,33,"Revisi Gaji Bulan Lalu");
	// xlsWriteLabel(2,34,"Total diTransfer");
	
// // $isiExcel = "<table border=\"0\">
// // <tr>
    // // <td colspan=\"7\"><div align=\"center\">Report Rincian Slip Gaji Karyawan</div></td>
// // </tr>
// // <tr>
    // // <td><div align=\"center\">No.</div></td>
    // // <td><div align=\"center\">NIK</div></td>
    // // <td><div align=\"center\">No Face Attd</div></td>
    // // <td><div align=\"center\">Nama Karyawan</div></td>
    // // <td><div align=\"center\">Departemen</div></td>
    // // <td><div align=\"center\">Jabatan</div></td>
    // // <td><div align=\"center\">Lokasi / Plant</div></td>
    // // <td><div align=\"center\">Hari Masuk</div></td>
    // // <td><div align=\"center\">Hari Ijin</div></td>
    // // <td><div align=\"center\">Hari Absen</div></td>
    // // <td><div align=\"center\">Terlambat 1</div></td>
    // // <td><div align=\"center\">Terlambat 2</div></td>
    // // <td><div align=\"center\">Terlambat 3</div></td>
    // // <td><div align=\"center\">Terlambat 4</div></td>
    // // <td><div align=\"center\">Gaji Pokok Trp</div></td>
    // // <td><div align=\"center\">Uang Makan Trp</div></td>
    // // <td><div align=\"center\">Uang Transport Trp</div></td>
    // // <td><div align=\"center\">Gaji Pokok</div></td>
    // // <td><div align=\"center\">Tunjangan Jabatan</div></td>
    // // <td><div align=\"center\">Tunjangan Lokasi</div></td>
    // // <td><div align=\"center\">Tunjangan Grade</div></td>
    // // <td><div align=\"center\">Uang Makan</div></td>
    // // <td><div align=\"center\">Uang Transport</div></td>
    // // <td><div align=\"center\">Premi Hadir</div></td>
    // // <td><div align=\"center\">Lembur</div></td>
    // // <td><div align=\"center\">PPH21</div></td>
    // // <td><div align=\"center\">BPJS Kesehatan</div></td>
    // // <td><div align=\"center\">BPJS TK</div></td>
    // // <td><div align=\"center\">Pinjaman</div></td>
    // // <td><div align=\"center\">BS</div></td>
    // // <td><div align=\"center\">Absen</div></td>
    // // <td><div align=\"center\">Telat</div></td>
    // // <td><div align=\"center\">Total Gaji</div></td>
    // // <td><div align=\"center\">Revisi Gaji Bulan Lalu</div></td>
    // // <td><div align=\"center\">Total diTransfer</div></td>
 // // </tr>
 
 // // ";
 
$xlsRow = 3;
$countHeader = 0;


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
	and     p.person_id IN (4456)--,2416,1948,3248,4456
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
	--and     p.middle_names='RAN'
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
			WHERE TO_CHAR(SYSDATE + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
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
			
			$vTotGajiPokok = $vHariMasuk * ($vGajiPokok/25);
			$vTotTunKer = $vHariMasuk * ($vTunKer/25);
		} else {
			$queryJumHari = "SELECT ((SELECT COUNT(*) FROM 
			(SELECT LEVEL AS DNUM FROM DUAL
			CONNECT BY (TO_DATE('$vPeriode2', 'YYYY-MM-DD') - TO_DATE('$vPeriode1', 'YYYY-MM-DD')) - LEVEL >= 0) S
			WHERE TO_CHAR(SYSDATE + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
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
			
			$vTotGajiPokok = $vGajiPokok;
			$vTotTunKer = $vTunKer;
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
			SELECT COUNT(-1) AS MASUK
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
		
		$queryJamLembur = "SELECT MTSD.TOTAL_JAM, NVL(MTT.JAM_MASUK, '16:00'), NVL(MTT.JAM_KELUAR, '23:59'), MTS.TANGGAL_SPL
		FROM MJ.MJ_T_SPL MTS
		INNER JOIN MJ.MJ_T_SPL_DETAIL MTSD ON MTSD.MJ_T_SPL_ID=MTS.ID
		LEFT JOIN MJ.MJ_T_TIMECARD MTT ON MTT.TANGGAL=MTS.TANGGAL_SPL AND MTT.STATUS=253 AND PERSON_ID=$vPersonId
		WHERE MTSD.STATUS_DOK='Approved' AND MTS.STATUS=1
		AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') >= '$vPeriode1'
		AND TO_CHAR(MTS.TANGGAL_SPL, 'YYYY-MM-DD') <= '$vPeriode2'
		AND MTSD.NAMA=(SELECT FULL_NAME FROM APPS.PER_PEOPLE_F WHERE EFFECTIVE_END_DATE > SYSDATE AND PERSON_ID=$vPersonId)
		ORDER BY MTS.TANGGAL_SPL
		";
		//echo $queryJumHari;
		$resultJamLembur = oci_parse($con,$queryJamLembur);
		oci_execute($resultJamLembur);
		while($rowJamLembur = oci_fetch_row($resultJamLembur))
		{
			$vTotSPL = 0;
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
			
			if($vTotSPL > $vTempJamMasuk){
				$vTotUangLembur = $vTotUangLembur + (round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
				//echo '<br> 1 : ' . round($vTempJamMasuk, 2, PHP_ROUND_HALF_DOWN);
			} else {
				$vTotUangLembur = $vTotUangLembur + (round($vTotSPL, 2, PHP_ROUND_HALF_DOWN) * $vUangLembur);
				//echo '<br> 2: ' . round($vTotSPL, 2, PHP_ROUND_HALF_DOWN);
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
			SELECT COUNT(-1) AS IJIN
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
		AND MTS.STATUS=1
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
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('TERLAMBAT')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:06:00', 'HH24:MI:SS')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:21:59', 'HH24:MI:SS')
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
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('TERLAMBAT')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:22:00', 'HH24:MI:SS')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('08:51:59', 'HH24:MI:SS')
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
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('TERLAMBAT')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('08:52:00', 'HH24:MI:SS')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') <= TO_DATE('09:21:59', 'HH24:MI:SS')
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
		WHERE TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')>='$vPeriode1'
		AND TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD')<='$vPeriode2'
		AND MME.ELEMENT_NAME IN ('TERLAMBAT')
		AND TO_DATE(JAM_KELUAR, 'HH24:MI:SS') >= TO_DATE('09:22:00', 'HH24:MI:SS')
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
		
		// $vTotGajiPlus = $vTotGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vTotUangMakan + $vTotUangTrans + $vTotTunKer + $vTotUangLembur + $vRevisiGaji;
		// $vTotGajiMinus = $vPotongan + $vBS + $vTotUangIjin + $vTotUangAlpha + $vTotUangAbsen + $vTotUangTerlambat + $vBPJSKS + $vBPJSTK;
		$vTotGajiSkr = ($vTotGajiPokok + $vTunJab + $vTunGrade + $vTunLok + $vTotUangMakan + $vTotUangTrans + $vTotTunKer + $vTotUangLembur + $vRevisiGaji) - ($vPotongan + $vBS + $vTotUangIjin + $vTotUangAlpha + $vTotUangAbsen + $vTotUangTerlambat + $vBPJSKS + $vBPJSTK);
		$vTotGajiTransfer = $vTotGajiSkr + $vRevisiGaji;
		
		echo 'Full Name : ' . $vFullName ;
		echo '<br>Gaji Pokok 25: ' . $vGajiPokok;
		echo '<br>Gaji Pokok : ' . $vTotGajiPokok;
		echo '<br>Tunjangan Jab : ' . $vTunJab;
		echo '<br>Tunjangan Grade : ' . $vTunGrade;
		echo '<br>Tunjangan Lokasi : ' . $vTunLok;
		echo '<br>Hari Masuk All : ' . $vHariMasuk;
		echo '<br>Hari Masuk : ' . $vJumMasuk;
		echo '<br>Uang Makan : ' . $vUangMakan;
		echo '<br>Uang Trans : ' . $vUangTrans;
		echo '<br>Total Uang Makan : ' . $vTotUangMakan;
		echo '<br>Total Uang Trans : ' . $vTotUangTrans;
		echo '<br>Total Tunjangan Kerajinan : ' . $vTotTunKer;
		echo '<br>Uang Lembur : ' . $vTotUangLembur;
		echo '<br>PPH21 : ' . '0';
		echo '<br>Pinjaman : ' . $vPotongan;
		echo '<br>BS : ' . $vBS;
		echo '<br>Total Uang Ijin : ' . $vTotUangIjin;
		echo '<br>Total Uang Alpha : ' . $vTotUangAlpha;
		echo '<br>Total Uang Absen : ' . $vTotUangAbsen;
		echo '<br>TD : ' . $vTD;
		echo '<br>Uang Terlambat1 : ' . $vUangTerlambat1;
		echo '<br>Uang Terlambat2 : ' . $vUangTerlambat2;
		echo '<br>Uang Terlambat3 : ' . $vUangTerlambat3;
		echo '<br>Uang Terlambat4 : ' . $vUangTerlambat4;
		echo '<br>Total Uang Terlambat : ' . $vTotUangTerlambat;
		echo '<br>BPJS KS : ' . $vBPJSKS;
		echo '<br>BPJS TK : ' . $vBPJSTK;
		echo '<br>Revisi Gaji : ' . $vRevisiGaji;
		echo '<br>Total Transfer : ' . $vTotGajiTransfer;
		// $countHeader++;
		// $xlsRow++;
		
		// xlsWriteLabel($xlsRow,0,$countHeader);
		// xlsWriteLabel($xlsRow,1,$vEmpNumber);
		// xlsWriteLabel($xlsRow,2,$vIdFinger);
		// xlsWriteLabel($xlsRow,3,$vFullName);
		// xlsWriteLabel($xlsRow,4,$vDept);
		// xlsWriteLabel($xlsRow,5,$vJabatan);
		// xlsWriteLabel($xlsRow,6,$vLokasi);
		// xlsWriteNumber($xlsRow,7,$vJumMasuk);
		// xlsWriteNumber($xlsRow,8,$vHariIjin);
		// xlsWriteNumber($xlsRow,9,$vHariAlpha);
		// xlsWriteNumber($xlsRow,10,$vTerlambat1);
		// xlsWriteNumber($xlsRow,11,$vTerlambat2);
		// xlsWriteNumber($xlsRow,12,$vTerlambat3);
		// xlsWriteNumber($xlsRow,13,$vTerlambat4);
		// xlsWriteNumber($xlsRow,14,$vGajiPokok);
		// xlsWriteNumber($xlsRow,15,$vUangMakan);
		// xlsWriteNumber($xlsRow,16,$vUangTrans);
		// xlsWriteNumber($xlsRow,17,$vTotGajiPokok);
		// xlsWriteNumber($xlsRow,18,$vTunJab);
		// xlsWriteNumber($xlsRow,19,$vTunLok);
		// xlsWriteNumber($xlsRow,20,$vTunGrade);
		// xlsWriteNumber($xlsRow,21,$vTotUangMakan);
		// xlsWriteNumber($xlsRow,22,$vTotUangTrans);
		// xlsWriteNumber($xlsRow,23,$vTotTunKer);
		// xlsWriteNumber($xlsRow,24,$vTotUangLembur);
		// xlsWriteNumber($xlsRow,25,0);
		// xlsWriteNumber($xlsRow,26,$vBPJSKS);
		// xlsWriteNumber($xlsRow,27,$vBPJSTK);
		// xlsWriteNumber($xlsRow,28,$vPotongan);
		// xlsWriteNumber($xlsRow,29,$vBS);
		// xlsWriteNumber($xlsRow,30,$vTotUangAbsen);
		// xlsWriteNumber($xlsRow,31,$vTotUangTerlambat);
		// xlsWriteNumber($xlsRow,32,$vTotGajiSkr);
		// xlsWriteNumber($xlsRow,33,$vRevisiGaji);
		// xlsWriteNumber($xlsRow,34,$vTotGajiTransfer);
		
	}
	
// xlsEOF();
	
// header('Content-Length: ' . filesize($namaFile));
// readfile($namaFile);
// $fp = fopen($namaFile, "w");
// fwrite($fp, $isiExcel);

// fclose($fp);
// exit();
?>