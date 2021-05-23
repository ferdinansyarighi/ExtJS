<?PHP
 include '../../main/koneksi.php'; //Koneksi ke database

// deklarasi variable dan session
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
	//$rekening = trim($_GET['rekening']);
	$bulan = 'February';
	$tahun = '2018';
	
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

//Query Gaji Karyawan		
$queryGaji = "select  distinct P.PERSON_ID
	,p.employee_number
	,NVL(p.honors, 0) id_finger
	,P.FULL_NAME
	,REGEXP_SUBSTR(j.name, '[^.]+', 1, 3) as dept
	,REGEXP_SUBSTR(pos.name, '[^.]+', 1, 3) as jabatan
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
	,((nvl(gp.screen_entry_value, 0) + nvl(tj.screen_entry_value, 0) + nvl(tg.screen_entry_value, 0) + nvl(tl.screen_entry_value, 0)) / 25) AS PG 
	,nvl(pot.harga_cicilan_p,0) cicilan_pinjaman
	,nvl(pot.harga_cicilan_b,0) cicilan_bs
	,nvl(bpjsks.screen_entry_value,0)/100*FGFKS.GLOBAL_VALUE pot_bpjs_ks
	,nvl(bpjstk.screen_entry_value,0)/100*FGFTK.GLOBAL_VALUE pot_bpjs_tk
	,nvl(revgaji.screen_entry_value, 0) revisi_gaji
	,NVL(TO_CHAR(P.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '1990-01-01') AS TGL_RESIGN
	,NVL(TRIM(REGEXP_SUBSTR(P.PREVIOUS_LAST_NAME, '[^-]+', 1, 2)), '') AS NO_REKENING
	,NVL(TRIM(REGEXP_SUBSTR(P.PREVIOUS_LAST_NAME, '[^-]+', 1, 3)), '') AS ATAS_NAMA
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
					from mj_t_potongan
					where status='A')pot
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
	and     p.person_id=2014
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
	--and     p.middle_names IN ('WSH')
	--order by to_number(p.employee_number) ASC
	AND a.PAYROLL_ID IS NOT NULL
	AND a.PRIMARY_FLAG='Y'
	and p.person_id=pot.person_id(+)
	AND TRIM(REGEXP_SUBSTR(FGFTK.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjstk.element_name, '[^_]+', 1, 4))
	AND TRIM(REGEXP_SUBSTR(FGFKS.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjsks.element_name, '[^_]+', 1, 4))
	AND a.PEOPLE_GROUP_ID <> 3061";
		//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		//echo 'TES';
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
		$vBatasTotJamLembur = 0;
		// $vTotGajiPlus = 0;
		// $vTotGajiMinus = 0;
		
		$vPersonId = $row[0];
		$vEmpNumber = $row[1];
		$vIdFinger = $row[2];
		$vFullName = str_replace("'", "''", $row[3]);
		//$vFullName = $row[3];
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
		
		//Query Menghitung jumlah cuti
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
		$resultJumCuti = oci_parse($con,$queryJumCuti);
		oci_execute($resultJumCuti);
		while($rowJumCuti = oci_fetch_row($resultJumCuti))
		{
			$vHariCuti = $rowJumCuti[0]; 
		}
		
		//Query untuk menghitung jumlah ijin
		$queryHariIjin = "SELECT COUNT(-1) JumIjin FROM
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
		)";
		echo $queryHariIjin;
		$resultHariIjin = oci_parse($con,$queryHariIjin);
		oci_execute($resultHariIjin);
		while($rowHariIjin = oci_fetch_row($resultHariIjin))
		{
			$vHariIjin = $rowHariIjin[0]; 
		}
		echo $vHariIjin;exit;
		
		//Query untuk mengecek bahwa karyawan tersebut masuk tengah periode apa tidak
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
			CONNECT BY (TO_DATE('$tahun-$bulanAngka-20', 'YYYY-MM-DD') - (TO_DATE('$hireDate', 'YYYY-MM-DD') - 1)) - LEVEL >= 0) S
			WHERE TO_CHAR((TO_DATE('$hireDate', 'YYYY-MM-DD') - 1) + DNUM, 'DY', 'NLS_DATE_LANGUAGE=AMERICAN') NOT IN ('SUN')) -
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
			
			if($vHariAktif == $vHariMasuk){
				$vTotGajiPokok = $vGajiPokok;
				$vTotTunKer = $vTunKer;
			} else {
				$vTotGajiPokok = $vHariMasuk * ($vGajiPokok/25);
				$vTotTunKer = $vHariMasuk * ($vTunKer/25);
			}
		} else {
			//Query untuk mengecek karyawan resign tengah periode
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
				AND TO_CHAR((TO_DATE('$vTglResign', 'YYYY-MM-DD') - 1), 'YYYY-MM-DD'))) TES
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
		//Query menghitung hari masuk
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
		
		ECHO $vJumMasuk;EXIT;
		
		
		//Query untuk menghitung lembur
		$queryTotalJamLembur = "SELECT JUMLAH_LEMBUR 
		FROM MJ.MJ_M_JAMLEMBUR MMJ
		INNER JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID=MMJ.PLANT_ID
		WHERE HL.LOCATION_CODE = '$vLokasi'";
		//echo $queryJumHari;
		$resultTotalJamLembur = oci_parse($con,$queryTotalJamLembur);
		oci_execute($resultTotalJamLembur);
		$rowTotalJamLembur = oci_fetch_row($resultTotalJamLembur);
		$vBatasTotJamLembur = $rowTotalJamLembur[0]; 
		
		$queryJamLembur = "SELECT MTSD.TOTAL_JAM, NVL(MTT.JAM_MASUK, '16:00'), NVL(MTT.JAM_KELUAR, NVL(MTSI.JAM_TO, NVL(MTT2.JAM_KELUAR, '23:59'))), MTS.TANGGAL_SPL        
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
		
		$data = "sukses";
	}

$result = array('success' => true,
				'results' => '',
				'rows' => $data
			);
echo json_encode($result);







?>