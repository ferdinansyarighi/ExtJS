<?PHP
include '../../main/koneksi.php';
$msgError="";
$data="";
$hasil = "";

if(isset($_POST['person_id'])){
	$person_id=str_replace("'", "''", $_POST['person_id']);

	$NIK="";
	$Attd="";
	$Dept="";
	$Jab="";
	$Plant="";
	$GP="";
	$TJ="";
	$TG="";
	$TL="";
	$TK="";
	$UM="";
	$UT="";
	$UL="";
	$CP="";
	$CB="";
	$BPJS_KS="";
	$BPJS_TK="";
	
	$query = "select  distinct P.PERSON_ID
	,P.FULL_NAME
	,p.employee_number
	,p.honors id_finger
	,REGEXP_SUBSTR(j.name, '[^.]+', 1, 3) as dept
	,REGEXP_SUBSTR(pos.name, '[^.]+', 1, 3) as jabatan
	,hl.location_code
    ,nvl(gp.screen_entry_value,0) gaji_pokok
    ,nvl(tj.screen_entry_value,0) tunj_jab
    ,nvl(tg.screen_entry_value,0) tunj_grade
    ,nvl(tl.screen_entry_value,0) tunj_lok
    ,nvl(tk.screen_entry_value,0) tunj_ker
    ,nvl(um.screen_entry_value,0) uang_makan
    ,nvl(ut.screen_entry_value,0) uang_trans
    ,nvl(ul.screen_entry_value,0) uang_lembur
	,nvl(pot.harga_cicilan_p,0) cicilan_pinjaman
	,nvl(pot.harga_cicilan_b,0) cicilan_bs
	,nvl(bpjsks.screen_entry_value,0)/100*FGFKS.GLOBAL_VALUE pot_bpjs_ks
	,nvl(bpjstk.screen_entry_value,0)/100*FGFTK.GLOBAL_VALUE pot_bpjs_tk
	,NVL(TO_CHAR(P.DATE_EMPLOYEE_DATA_VERIFIED, 'YYYY-MM-DD'), '1990-01-01') AS TGL_RESIGN
	,TO_CHAR(P.ORIGINAL_DATE_OF_HIRE, 'YYYY-MM-DD') AS ORIGINAL_DATE_OF_HIRE
	,((gp.screen_entry_value + tj.screen_entry_value + tg.screen_entry_value + tl.screen_entry_value) / 25) AS PG 
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
				,(select person_id, outstanding_p, outstanding_b, harga_cicilan_p, harga_cicilan_b
					from mj_t_potongan
					where status='A')pot
				, FF_GLOBALS_F FGFTK
				, FF_GLOBALS_F FGFKS
	where   p.person_id=a.person_id
	and     p.person_id=$person_id
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
	and p.person_id=pot.person_id(+)
	AND TRIM(REGEXP_SUBSTR(FGFTK.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjstk.element_name, '[^_]+', 1, 4))
	AND TRIM(REGEXP_SUBSTR(FGFKS.GLOBAL_DESCRIPTION, '[^ ]+', 1, 2))=TRIM(REGEXP_SUBSTR(bpjsks.element_name, '[^_]+', 1, 4))
	AND a.PEOPLE_GROUP_ID <> 3061
    --AND REGEXP_SUBSTR(j.name, '[^.]+', 1, 3) LIKE '%Information Technology%'
	ORDER BY P.PERSON_ID";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$NIK=$row[2];
		$Attd=$row[3];
		$Dept=$row[4];
		$Jab=$row[5];
		$Plant=$row[6];
		$GP=number_format($row[7], 2, ',', '.');
		$TJ=number_format($row[8], 2, ',', '.');
		$TG=number_format($row[9], 2, ',', '.');
		$TL=number_format($row[10], 2, ',', '.');
		$TK=number_format($row[11], 2, ',', '.');
		$UM=number_format($row[12], 2, ',', '.');
		$UT=number_format($row[13], 2, ',', '.');
		$UL=number_format($row[14], 2, ',', '.');
		$CP=number_format($row[15], 2, ',', '.');
		$CB=number_format($row[16], 2, ',', '.');
		$BPJS_KS=number_format($row[17], 2, ',', '.');
		$BPJS_TK=number_format($row[18], 2, ',', '.');
	}
	
	$hasil = $NIK . "|" . $Attd . "|" . $Dept . "|" . $Jab . "|" . $Plant . "|" . $GP . "|" . $TJ . "|" . $TL . "|" . $TG . "|" . $UM . "|" . $UT . "|" . $TK . "|0|" . $BPJS_KS . "|" . $BPJS_TK . "|" . $CP . "|" . $CB;
}

$result = array('success' => true,
			'results' => $hasil,
			'rows' => ''
		);
echo json_encode($result);

?>