<?php
include '../../main/koneksi.php';

$data = "gagal";
if (isset($_POST['bulan']) || isset($_POST['tahun']))
{	
	$bulan = $_POST['bulan'];
	$tahun = $_POST['tahun'];
	
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
	
	$vCount = 0;
	$vTotalNama = '';
	$vCheckData = '';
	$data = '';
	
	$queryGaji = "SELECT X.FULL_NAME, COUNT(-1) JUMLAH
	FROM (
		select  distinct P.PERSON_ID
		,p.employee_number
		,p.honors id_finger
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
		,((gp.screen_entry_value + tj.screen_entry_value + tg.screen_entry_value + tl.screen_entry_value) / 25) AS PG 
		,nvl(pot.harga_cicilan_p,0) cicilan_pinjaman
		,nvl(pot.harga_cicilan_b,0) cicilan_bs
		,nvl(bpjsks.screen_entry_value,0)/100*FGFKS.GLOBAL_VALUE pot_bpjs_ks
		,nvl(bpjstk.screen_entry_value,0)/100*FGFTK.GLOBAL_VALUE pot_bpjs_tk
		,nvl(revgaji.screen_entry_value, 0) revisi_gaji
		,NVL(TO_CHAR(P.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '1990-01-01') AS TGL_RESIGN
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
		--and     p.person_id=13985
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
		--and     p.middle_names='ARM'
		--order by to_number(p.employee_number) ASC
		AND a.PAYROLL_ID IS NOT NULL
		AND a.PRIMARY_FLAG='Y'
		and p.person_id=pot.person_id(+)
		AND TRIM(REGEXP_SUBSTR(FGFTK.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjstk.element_name, '[^_]+', 1, 4))
		AND TRIM(REGEXP_SUBSTR(FGFKS.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjsks.element_name, '[^_]+', 1, 4))
		AND a.PEOPLE_GROUP_ID <> 3061
		ORDER BY P.PERSON_ID) X
		GROUP BY X.FULL_NAME";
	//echo $queryGaji;
	$resultGaji = oci_parse($con,$queryGaji);
	oci_execute($resultGaji);
	while($row = oci_fetch_row($resultGaji))
	{
		$vFullName = $row[0];
		$vJumlah = $row[1];
		
		if($vJumlah >= 2) {
			if ($vCount == 0){
				$vTotalNama = $vFullName;
			} else {
				$vTotalNama = $vTotalNama . '<BR> ' . $vFullName;
			}
			$vCheckData = 'error';
			$vCount++;
		}
	}
}

$result = array('success' => true,
				'results' => $vCount,
				'rows' => $vTotalNama
			);
echo json_encode($result);

?>