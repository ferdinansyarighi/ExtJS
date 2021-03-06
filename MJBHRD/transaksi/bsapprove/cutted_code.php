
<?php



	// Query tambahan jika Mgr Finance diset sebagai Checker
	
	$query .= "
		UNION
		SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
				, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
				, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
				, HL.LOCATION_CODE PLANT
				, PG.NAME GRADE
				, BS.NOMINAL
				, BS.KETERANGAN
				, BS.TIPE, HOU2.NAME
				--, TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
				, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
				, DECODE( BS.TINGKAT,
							0, 'Appr Mgr',
							1, 'Appr Mgr Fin',
							2, 'Checker',
							4, 'Appr Mgr Fin'
					) TINGKAT_DECODE
		FROM MJ.MJ_T_BS BS
		LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
			AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
		LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
			AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
			AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
		LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
		LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
		LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
		LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
		WHERE 1 = 1 
		AND BS.TINGKAT = 2 
		and (	BS.PERUSAHAAN_BS in 
				(	SELECT PERUSAHAAN_ID 
					FROM MJ.MJ_M_APPROVAL_PINJAMAN 
					WHERE TIPE = 'BS' AND STATUS = 'A' AND CHECKER_ID is not null AND CHECKER_ID = $emp_id
				) 
			OR BS.PERUSAHAAN_BS not in 
				(	SELECT PERUSAHAAN_ID 
					FROM MJ.MJ_M_APPROVAL_PINJAMAN 
					WHERE TIPE = 'BS' 
					AND STATUS = 'A' 
					AND CHECKER_ID is not null
				)
		) 
		AND EXISTS
			(
				SELECT  1
				FROM    APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP,
						APPS.PER_PEOPLE_F PPF, APPS.PER_GRADES PG
				WHERE PAF.JOB_ID=PJ.JOB_ID
				AND PAF.POSITION_ID=PP.POSITION_ID
				AND PAF.EFFECTIVE_END_DATE > SYSDATE
				AND PRIMARY_FLAG = 'Y'
				AND PAF.PERSON_ID = PPF.PERSON_ID 
				AND PAF.GRADE_ID = PG.GRADE_ID
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
				AND PJ.NAME LIKE '%FIN%' 
				AND PPF.PERSON_ID = $emp_id
			)
		$queryfilter
	";
	
	

	//Checker (Tingkat 2)

	$queryLogin = "
		SELECT CHECKER_ID
		FROM MJ.MJ_M_APPROVAL_PINJAMAN 
		WHERE TIPE = 'BS' 
		AND STATUS = 'A' 
		AND PERUSAHAAN_ID = 
		
	";
	//echo $queryLogin;exit;
	$resultLogin = oci_parse($con,$queryLogin);
	oci_execute($resultLogin);
	$rowLogin = oci_fetch_row($resultLogin);
	$checkerPerson = $rowLogin[0]; 

	// AND CHECKER_ID = $emp_id	
	
	
	$queryLoginPerusahaan = "
		SELECT PERUSAHAAN_ID 
		FROM MJ.MJ_M_APPROVAL_PINJAMAN 
		WHERE TIPE = 'BS' 
		AND STATUS = 'A' 
		AND CHECKER_ID = $emp_id
	";
	//echo $queryLogin;exit;
	$resultLogin = oci_parse( $con, $queryLoginPerusahaan );
	oci_execute( $resultLogin );
	$rowLogin = oci_fetch_row( $resultLogin );
	$checkerOrgId = $rowLogin[0];
	
	
	
	
	if ( $countChecker == 0 ) {
		
		echo ( '$countChecker == 0' );
		
		$queryLogin = "
				SELECT CASE WHEN PJ.name like '%FIN%' THEN '2'
						ELSE '0' END AS TINGKAT
					, PPF.FULL_NAME, PP.NAME
				FROM APPS.PER_ASSIGNMENTS_F PAF, APPS.PER_JOBS PJ, APPS.PER_POSITIONS PP,
				PER_PEOPLE_F PPF, APPS.PER_GRADES PG
				WHERE PAF.JOB_ID=PJ.JOB_ID
					AND PAF.POSITION_ID=PP.POSITION_ID
					AND PAF.EFFECTIVE_END_DATE > SYSDATE
					AND PRIMARY_FLAG = 'Y'
					AND PAF.PERSON_ID = PPF.PERSON_ID 
					AND PAF.GRADE_ID = PG.GRADE_ID
					AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
					AND PAF.ASSIGNMENT_ID = $assignment_id_login
					";
		$resultLogin = oci_parse($con,$queryLogin);
		oci_execute($resultLogin);
		$rowLogin = oci_fetch_row($resultLogin);
		$tingkat = $rowLogin[0]; 
		
		if($tingkat == 2){
		
			$query = "
			SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
					, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
					, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
					, HL.LOCATION_CODE PLANT
					, PG.NAME GRADE
					, BS.NOMINAL
					, BS.KETERANGAN
					, BS.TIPE, HOU2.NAME
					-- , TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
					, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
			FROM MJ.MJ_T_BS BS
			LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
				AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
			LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
				AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
				AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
			LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
			LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
			LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
			LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
			LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
			WHERE 1=1 AND BS.TINGKAT = 2 and BS.PERUSAHAAN_BS not in 
				(SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND CHECKER_ID is not null)
			$queryfilter
			";
		
		}
		
	} else 
	{
		
		echo ( '$countChecker != 0' );
		
		$query = "
		SELECT BS.ID, BS.NO_BS, PPF.FULL_NAME PEMOHON, HOU.NAME PERUSAHAAN
				, REGEXP_SUBSTR(PJ.NAME, '[^.]+', 1, 2) AS DEPT
				, REGEXP_SUBSTR(POS.NAME, '[^.]+', 1, 3) AS JABATAN
				, HL.LOCATION_CODE PLANT
				, PG.NAME GRADE
				, BS.NOMINAL
				, BS.KETERANGAN
				, BS.TIPE, HOU2.NAME
				--, TO_CHAR(BS.TGL_JT, 'DD-MON-YYYY')
				, TO_CHAR( BS.TGL_JT, 'YYYY-MM-DD' )
		FROM MJ.MJ_T_BS BS
		LEFT JOIN APPS.PER_ASSIGNMENTS_F PAF ON BS.ASSIGNMENT_ID =PAF.ASSIGNMENT_ID 
			AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
		LEFT JOIN APPS.PER_PEOPLE_F PPF ON PAF.PERSON_ID = PPF.PERSON_ID 
			AND PPF.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.PER_PEOPLE_F PPF2 ON BS.PENANGGUNG_JAWAB = PPF2.PERSON_ID 
			AND PPF2.CURRENT_EMPLOYEE_FLAG = 'Y' AND PPF2.EFFECTIVE_END_DATE > SYSDATE
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON PAF.ORGANIZATION_ID = HOU.ORGANIZATION_ID
		LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU2 ON BS.PERUSAHAAN_BS = HOU2.ORGANIZATION_ID
		LEFT JOIN APPS.PER_JOBS PJ ON PAF.JOB_ID = PJ.JOB_ID
		LEFT JOIN APPS.PER_POSITIONS POS ON PAF.POSITION_ID = POS.POSITION_ID
		LEFT JOIN APPS.HR_LOCATIONS HL ON PAF.LOCATION_ID = HL.LOCATION_ID
		LEFT JOIN APPS.per_grades PG ON PAF.GRADE_ID = PG.GRADE_ID
		WHERE 1=1 
		AND BS.TINGKAT = 2 
		and (	BS.PERUSAHAAN_BS = $checkerOrgId or
				BS.PERUSAHAAN_BS in 
				(	SELECT PERUSAHAAN_ID 
					FROM MJ.MJ_M_APPROVAL_PINJAMAN 
					WHERE TIPE = 'BS' AND STATUS = 'A' AND CHECKER_ID is not null AND CHECKER_ID = $emp_id
				) 
		or BS.PERUSAHAAN_BS not in (SELECT PERUSAHAAN_ID FROM MJ.MJ_M_APPROVAL_PINJAMAN WHERE TIPE = 'BS' AND STATUS = 'A' AND CHECKER_ID is not null)) 
		$queryfilter
		";
	}
	
?>


