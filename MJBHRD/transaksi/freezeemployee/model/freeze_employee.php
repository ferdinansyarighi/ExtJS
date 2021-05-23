<?php
	//require_once('../../main/koneksi.php');
	define('VGBL_PROD_LEVEL', FALSE);
	
	function get_periode_gaji($group_name, $date_min=NULL, $date_max=NULL)
	{
		$cut_off_monthly_min = 21;
		$cut_off_monthly_max = 20;
		$cut_off_weekly_min = 'MONDAY';
		$cut_off_weekly_max = 'SUNDAY';
		$cut_off_n_weekly_min = 1;
		$cut_off_n_weekly_max = 7;
		
		/*
		* mencari periode gaji berdasarkan group gaji (cutoff freeze per periode penggajian)
		*/
		// mingguan
		if(strpos(strtoupper($group_name), 'MINGGU') !== FALSE){
			$ck_period_day_min = date('N', strtotime($date_min));
			
			if($ck_period_day_min > $cut_off_n_weekly_min)
				$subs_dt_min = $ck_period_day_min - 1;
			else
				$subs_dt_min = 0;
					
			$gn_period_dt_min = date('Y-m-d', strtotime('-'.$subs_dt_min.' day', strtotime($date_min)));
			$gn_period_dt_max = date('Y-m-d', strtotime('+'.($cut_off_n_weekly_max - 1).' day', strtotime($gn_period_dt_min)));
		/*
			echo "<br>=>ck_period_day_min=>".$ck_period_day_min."<=subs_dt_min=>".$subs_dt_min."<=>TGL=>".$date_min."<=dt_prd_gaji_min=>".$gn_period_dt_min."<=dt_prd_gaji_max=>".$gn_period_dt_max."<br>";	
		*/
		}
		// bulanan
		else{
			$dt_co_max = $cut_off_monthly_max - $cut_off_monthly_min;
			
			if(date('d', strtotime($date_min)) <= $cut_off_monthly_min)
			{
				$gn_period_dt_min = date('Y-m-d', strtotime("-1 month", strtotime(date('Y-m', strtotime($date_min)).'-'.$cut_off_monthly_min)));
			}
			else
			{
				$gn_period_dt_min = date('Y-m-d', strtotime(date('Y-m', strtotime($date_min)).'-'.$cut_off_monthly_min));
			}
		
			$gn_period_dt_max = date('Y-m-d', strtotime("+1 month", strtotime("+".$dt_co_max." day", strtotime($gn_period_dt_min))));
		}
		
		$dt['gn_period_dt_min'] = $gn_period_dt_min;
		$dt['gn_period_dt_max'] = $gn_period_dt_max;
		
		return $dt;
	}
	
	function get_alpha_employee($con, $dt_in){
	//	$dt_in['emp_id_freeze'] = 1669;
		$sql_where = "";
		$sql_where_src = "";
		$sql_where_dtl = "";
		$max_alpha = 5;
		$periode_min = "";
		$periode_max = "";
		$cut_off_monthly_min = 21;
		$cut_off_monthly_max = 20;
		$cut_off_weekly_min = 'MONDAY';
		$cut_off_weekly_max = 'SUNDAY';
		$cut_off_n_weekly_min = 1;
		$cut_off_n_weekly_max = 7;
		
		// $org_id = 141;
		
		if(array_key_exists('periode_min', $dt_in) && !empty($dt_in['periode_min'])){
			$periode_min = $dt_in['periode_min'];
			$sql_where .= " AND TANGGAL >= TO_DATE('".$periode_min."', 'YYYY-MM-DD') ";
			$sql_where_src .= " AND MTT.TANGGAL >= TO_DATE('".$periode_min."', 'YYYY-MM-DD') ";
		}
		if(array_key_exists('periode_max', $dt_in) && !empty($dt_in['periode_max'])){
			$periode_max = $dt_in['periode_max'];
			$sql_where_src .= " AND MTT.TANGGAL  <= TO_DATE('".$periode_max."', 'YYYY-MM-DD') ";
		}
		
		if(array_key_exists('org_id', $dt_in)){
			$org_id = $dt_in['org_id'];
			$sql_where .= " AND ORG_ID = '".$org_id."' ";
			$sql_where_dtl .= " AND HOU.ORGANIZATION_ID = '".$org_id."' ";
		}
	
		if(array_key_exists('org_name', $dt_in)){
			$org_name = $dt_in['org_name'];
			$sql_where .= " AND ORG_NAME = '".$org_name."' ";
			$sql_where_src .= " AND HOU.NAME = '".$org_name."' ";
			$sql_where_dtl .= " AND HOU.NAME = '".$org_name."' ";
		}
	
		if(array_key_exists('plant_id', $dt_in)){
			$plant_id = $dt_in['plant_id'];

			if($plant_id != ''){
				$sql_where .= " AND LOCATION_ID = '".$plant_id."' ";
				$sql_where_src .= " AND HL.LOCATION_ID = '".$plant_id."' ";
				$sql_where_dtl .= " AND HL.LOCATION_ID = '".$plant_id."' ";
			}
		}

		if(array_key_exists('plant_name', $dt_in)){
			$plant_name = $dt_in['plant_name'];

			if($plant_name != ''){
				$sql_where .= " AND LOCATION_CODE = '".$plant_name."' ";
				$sql_where_src .= " AND HL.LOCATION_CODE = '".$plant_name."' ";
				$sql_where_dtl .= " AND HL.LOCATION_CODE = '".$plant_name."' ";
			}
		}

		if(array_key_exists('dept_id', $dt_in)){
			
			if($dt_in['dept_id'] != ''){
				$sql_where .= " AND DEPT_ID = '".$dt_in['dept_id']."' ";
				$sql_where_src .= " AND J.JOB_ID = '".$dt_in['dept_id']."' ";
				$sql_where_dtl .= " AND J.JOB_ID = '".$dt_in['dept_id']."' ";
			}
		}
		
		if(array_key_exists('dept_name', $dt_in)){
			
			if($dt_in['dept_name'] != ''){
				$sql_where .= " AND DEPT LIKE '%".$dt_in['dept_name']."%' ";
				$sql_where_src .= " AND J.NAME LIKE '%".$dt_in['dept_name']."%' ";
			}
		}
		
		if(array_key_exists('emp_id_freeze', $dt_in)){
			
			if($dt_in['emp_id_freeze'] != ''){
				$sql_where .= " AND PERSON_ID = '".$dt_in['emp_id_freeze']."' ";
				$sql_where_src .= " AND PPF.PERSON_ID = '".$dt_in['emp_id_freeze']."' ";
				$sql_where_dtl .= " AND PPF.PERSON_ID = '".$dt_in['emp_id_freeze']."' ";
			}
		}
		
		if(array_key_exists('id_top_mgr', $dt_in)){
			
			if($dt_in['id_top_mgr'] != ''){
				$sql_where .= " AND TOP_MGR_ID = '".$dt_in['id_top_mgr']."' ";
				$sql_where_src .= " AND PAF.ASS_ATTRIBUTE2 = '".$dt_in['id_top_mgr']."' ";
				//$sql_where_dtl .= " AND PPF.PERSON_ID = '".$dt_in['emp_id_freeze']."' ";
			}
		}
		/**/
		//echo $sql_where ;
		$sql = "SELECT PERSON_ID, FULL_NAME, TO_CHAR(MIN(TANGGAL), 'YYYY-MM-DD') MIN_DATE,
					TO_CHAR(MAX(TANGGAL), 'YYYY-MM-DD') MAX_DATE,
					(MAX(TANGGAL) - MIN(TANGGAL)) DIFF_TGL, COUNT(PERSON_ID) TOT_ALPHA, ORG_ID,
					ORG_NAME, LOCATION_ID, LOCATION_CODE, GROUP_NAME, DEPT_ID, DEPT, TOP_MGR_ID
				FROM
				(
					SELECT DISTINCT PPF.PERSON_ID
						, PPF.FULL_NAME
						, MTT.TANGGAL
						/* , MTT.JAM_MASUK */
						/* , MTT.JAM_KELUAR */
						, MME.ELEMENT_NAME
						,
						CASE WHEN MME.ELEMENT_NAME<>'LEMBUR' THEN MTS.KATEGORI
							WHEN MME.ELEMENT_NAME='LEMBUR' THEN (CASE WHEN NVL(SPL.ID, 0)<>0 THEN 'ADA SPL' ELSE '' END)
						END AS SIK_SPL 
						, MTS.IJIN_KHUSUS
						/* ,
						CASE WHEN MME.ELEMENT_NAME<>'LEMBUR' THEN (
								CASE WHEN NVL(MTS.ID, 0)<>0 AND MTS.STATUS_DOK='Approved' THEN
									(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
									FROM MJ.MJ_T_APPROVAL MTA 
									WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=1 AND MTA.TRANSAKSI_KODE='SIK'
										AND MTA.ID = (
														SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL
														WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=1 AND TRANSAKSI_KODE='SIK'))
								ELSE ''
								END
							)
							WHEN MME.ELEMENT_NAME='LEMBUR' THEN (
								CASE WHEN NVL(SPL.ID, 0)<>0 AND SPL.STATUS_DOK='Approved' THEN (
									SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
									FROM MJ.MJ_T_APPROVAL MTA 
									WHERE MTA.TRANSAKSI_ID=SPL.ID AND MTA.APP_ID=1 AND MTA.TRANSAKSI_KODE='SPL'
									AND MTA.ID = (
													SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL
													WHERE TRANSAKSI_ID=SPL.ID AND APP_ID=1 AND TRANSAKSI_KODE='SPL')
								)
								ELSE ''
								END
							)
						ELSE ''
						END AS TGL_APPROVED
						*/
						, J.JOB_ID DEPT_ID, J.NAME DEPT, HOU.NAME ORG_NAME, HOU.ORGANIZATION_ID ORG_ID, HL.LOCATION_ID, HL.LOCATION_CODE,
						PPG.GROUP_NAME, PAF.ASS_ATTRIBUTE2 TOP_MGR_ID
					FROM MJ.MJ_T_TIMECARD MTT
					INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE >= MTT.TANGGAL /*SYSDATE*/
					INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PPF.PERSON_ID=PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE >= MTT.TANGGAL /*SYSDATE*/ AND PAF.PRIMARY_FLAG='Y'
					LEFT JOIN APPS.PER_JOBS J ON PAF.JOB_ID=J.JOB_ID
					INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
					LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO AND MTS.STATUS=1 AND STATUS_DOK <> 'Disapproved'
					LEFT JOIN 
					(
						SELECT MTSPD.ID, MTSP.TANGGAL_SPL, MTSPD.PERSON_ID, MTSPD.STATUS_DOK
						FROM MJ.MJ_T_SPL MTSP
						INNER JOIN MJ.MJ_T_SPL_DETAIL MTSPD ON MTSP.ID=MTSPD.MJ_T_SPL_ID
						WHERE MTSP.STATUS=1
					) SPL ON SPL.TANGGAL_SPL=MTT.TANGGAL AND SPL.PERSON_ID=PPF.PERSON_ID
					LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON HOU.ORGANIZATION_ID = PAF.ORGANIZATION_ID 
					LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = PAF.LOCATION_ID
					INNER JOIN APPS.PAY_PEOPLE_GROUPS PPG ON PPG.PEOPLE_GROUP_ID = PAF.PEOPLE_GROUP_ID
					WHERE  (MME.ELEMENT_NAME <> 'LEMBUR' OR NVL(SPL.ID, 0)<>0)
					".$sql_where_src."
				) TB
				WHERE ELEMENT_NAME LIKE '%ALPHA%' AND (SIK_SPL IS NULL OR SIK_SPL = '')
					".$sql_where."
				/*	AND PERSON_ID = 39130 */ 
				GROUP BY PERSON_ID, FULL_NAME, ORG_ID, ORG_NAME, LOCATION_ID, LOCATION_CODE, DEPT_ID, DEPT,
					GROUP_NAME, TOP_MGR_ID  
				HAVING COUNT(PERSON_ID) >= '".$max_alpha."'
				ORDER BY FULL_NAME,	ORG_NAME, LOCATION_CODE, DEPT, GROUP_NAME, TOP_MGR_ID ";
			//	echo "<br><br>".$sql."<br><br>";
			//	exit();
		$q = oci_parse($con, $sql);
		oci_execute($q);
		$dt_new = array();
		$dt_top_mgr = array();
		$no = 0;
		while($row = oci_fetch_assoc($q))
		{
			$delta_date = $row['MAX_DATE'] - $row['MIN_DATE']; 
		
			if(strpos(strtoupper($row['GROUP_NAME']), 'MINGGU') !== FALSE){
				$dt_sal_prd_hdr = get_periode_gaji($row['GROUP_NAME'], $row['MIN_DATE'], NULL);
				$dt_sal_prd_hdr_min = $dt_sal_prd_hdr['gn_period_dt_min'];
				$dt_sal_prd_hdr_max = $row['MAX_DATE'];
			}
			else
			{
				$dt_sal_prd_hdr_min = $row['MIN_DATE'];
				$dt_sal_prd_hdr_max = $row['MAX_DATE'];
			}			
			
			$sql_dtl = "SELECT DISTINCT  PPF.PERSON_ID
							, PPF.FULL_NAME
							, MTT.TANGGAL
							, TO_CHAR(MTT.TANGGAL, 'YYYY-MM-DD') FORMAT_TANGGAL
							/* , MTT.JAM_MASUK */
							/* , MTT.JAM_KELUAR */
							, MME.ELEMENT_NAME
							,
							CASE WHEN MME.ELEMENT_NAME<>'LEMBUR' THEN MTS.KATEGORI
								WHEN MME.ELEMENT_NAME='LEMBUR' THEN (CASE WHEN NVL(SPL.ID, 0)<>0 THEN 'ADA SPL' ELSE '' END)
							END AS SIK_SPL 
							, MTS.IJIN_KHUSUS
							, J.JOB_ID DEPT_ID, J.NAME DEPT, HOU.NAME ORG_NAME, HOU.ORGANIZATION_ID ORG_ID, HL.LOCATION_ID, HL.LOCATION_CODE,
							PPG.GROUP_NAME,
							PP.POSITION_ID POS_ID, PP.NAME POS_NAME, PAF.ASS_ATTRIBUTE2 TOP_MGR_ID
						FROM MJ.MJ_T_TIMECARD MTT
						INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE >= MTT.TANGGAL /*SYSDATE*/
						INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PPF.PERSON_ID=PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE >= MTT.TANGGAL /*SYSDATE*/ AND PAF.PRIMARY_FLAG='Y'
						LEFT JOIN APPS.PER_JOBS J ON PAF.JOB_ID=J.JOB_ID
						INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
						LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO AND MTS.STATUS=1 AND STATUS_DOK <> 'Disapproved'
						LEFT JOIN 
						(
							SELECT MTSPD.ID, MTSP.TANGGAL_SPL, MTSPD.PERSON_ID, MTSPD.STATUS_DOK
							FROM MJ.MJ_T_SPL MTSP
							INNER JOIN MJ.MJ_T_SPL_DETAIL MTSPD ON MTSP.ID=MTSPD.MJ_T_SPL_ID
							WHERE MTSP.STATUS=1
						) SPL ON SPL.TANGGAL_SPL=MTT.TANGGAL AND SPL.PERSON_ID=PPF.PERSON_ID
						LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON HOU.ORGANIZATION_ID = PAF.ORGANIZATION_ID 
						LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = PAF.LOCATION_ID
						INNER JOIN APPS.PAY_PEOPLE_GROUPS PPG ON PPG.PEOPLE_GROUP_ID = PAF.PEOPLE_GROUP_ID
						INNER JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID = PP.POSITION_ID
						WHERE  (TRIM(MME.ELEMENT_NAME) <> 'LEMBUR' OR NVL(SPL.ID, 0)<>0)
						/* AND MTT.TANGGAL >= TO_DATE('".$row['MIN_DATE']."', 'YYYY-MM-DD') */
						AND MTT.TANGGAL >= TO_DATE('".$dt_sal_prd_hdr_min."', 'YYYY-MM-DD')
						AND MTT.TANGGAL <= TO_DATE('".$dt_sal_prd_hdr_max."', 'YYYY-MM-DD')
						/* AND MTT.TANGGAL <= TO_DATE('".$row['MAX_DATE']."', 'YYYY-MM-DD') */
						AND PPF.PERSON_ID = '".$row['PERSON_ID']."'
						".$sql_where_dtl."
						ORDER BY MTT.TANGGAL ASC ";
		//				echo "<br><br>".$sql_dtl."<br><br>";
			$q_dtl = oci_parse($con, $sql_dtl);
			oci_execute($q_dtl);
			
			$old_person_id = NULL;
			$old_full_name = NULL;
			$old_date_min = NULL;
			$old_date_max = NULL;
			$old_element = NULL;
			$old_org_id = NULL;
			$old_org_name = NULL;
			$old_dept_id = NULL;
			$old_dept_name = NULL;
			$old_location_id = NULL;
			$old_location_name = NULL;
			$old_pos_id = NULL;
			$old_pos_name = NULL;
			$old_group_salary = NULL;
			$old_tot_alpha = 0;
			$old_period_min = NULL;
			$old_period_max = NULL;
			$old_top_mgr_id = NULL;
			$testing = 0;	
			$is_insert = FALSE;
			while($row_dtl = oci_fetch_assoc($q_dtl))
			{
				$dt_prd_gaji = get_periode_gaji($row_dtl['GROUP_NAME'], $row_dtl['FORMAT_TANGGAL'], NULL);
				$dt_prd_gaji_min = $dt_prd_gaji['gn_period_dt_min'];
				$dt_prd_gaji_max = $dt_prd_gaji['gn_period_dt_max'];
						
				// jika alpha
				if((strpos(strtoupper($row_dtl['ELEMENT_NAME']), 'ALPHA')) !== FALSE && (trim($row_dtl['SIK_SPL']) == NULL OR trim($row_dtl['SIK_SPL']) == ''))
				{
					if($old_person_id <> $row_dtl['PERSON_ID'])
					{
						if($old_person_id != NULL)
						{
							$dt['DATA_PERSON_ID'] = $old_person_id;
							$dt['DATA_EMPLOYEE_NAME'] = $old_full_name;
							$dt['DATA_ORGANIZATION_ID'] = $old_org_id;
							$dt['DATA_ORGANIZATION_UNITS'] = $old_org_name;
							$dt['DATA_DEPARTMENT_ID'] = $old_dept_id;
							$dt['DATA_DEPARTMENT'] = $old_dept_name;
							$dt['DATA_PLANT_ID'] = $old_location_id;
							$dt['DATA_PLANT'] = $old_location_name;
							$dt['DATA_POS_ID'] = $old_pos_id;
							$dt['DATA_POS_NAME'] = $old_pos_name;
							$dt['DATA_TOTAL_ALPHA'] = $old_tot_alpha;
							$dt['DATA_START_DATE_ALPHA'] = $old_date_min;
							$dt['DATA_END_DATE_ALPHA'] = $row_dtl['FORMAT_TANGGAL'];
							$dt['DATA_START_PERIOD'] = $old_period_min;
							$dt['DATA_END_PERIOD'] = $old_period_max;
							$dt['DATA_GROUP_SALARY'] = $old_group_salary;
							$dt['DATA_TOP_MGR_ID'] = $old_top_mgr_id;

							if ($old_tot_alpha >= $max_alpha)
							{
								$no = $no + 1;
								$dt['DATA_NO'] = $no;
								
								$dt_in_req['ID_EMP_FREEZE'] = $dt['DATA_PERSON_ID'];
								$dt_in_req['ID_ORGANIZATION'] = $dt['DATA_ORGANIZATION_ID'];
								$dt_in_req['ID_PLANT'] = $dt['DATA_PLANT_ID'];
								$dt_in_req['ID_DEPARTMENT'] = $dt['DATA_DEPARTMENT_ID'];
								$dt_in_req['START_PERIOD'] = $dt['DATA_START_PERIOD'];
								$dt_in_req['END_PERIOD'] = $dt['DATA_END_PERIOD'];
								$dt_in_req['TOT_ALPHA'] = $dt['DATA_TOTAL_ALPHA'];
								$dt_in_req['DATE_START_ALPHA'] = $dt['DATA_START_DATE_ALPHA'];
								$dt_in_req['DATE_END_ALPHA'] = $dt['DATA_END_DATE_ALPHA'];
								$dt_in_req['POSITION_ID'] = $dt['DATA_POS_ID'];
								$dt_in_req['POSITION_NAME'] = $dt['DATA_POS_NAME'];
								$dt_in_req['APP_ID'] = $dt_in['APP_ID'];
								$dt_in_req['TRANSAKSI_KODE'] = $dt_in['TRANSAKSI_KODE'];
								
								$data_req_unfreeze = get_id_req_unfreeze($con, $dt_in_req);
								$dt['DATA_HD_ID'] = $data_req_unfreeze['ID_TRANSAKSI'];
								$dt['DATA_ATTACHMENT'] = $data_req_unfreeze['FILE_ATTC'];
								$dt['DATA_MGR_NOTES'] = $data_req_unfreeze['MGR_NOTES'];
								$dt['DATA_APPROVAL_STATUS'] = $data_req_unfreeze['APPROVAL_STATUS'];
								$dt['DATA_APPROVAL_LEVEL'] = $data_req_unfreeze['APPROVAL_LEVEL'];
						
								if($dt['DATA_APPROVAL_LEVEL'] <= $dt_in['APPROVAL_LEVEL'])
								{
									$dt_new[] = $dt; 
									
									$dt_top_mgr[$dt['DATA_TOP_MGR_ID']][] = $dt;
								}
							}
							
							$testing .= " 1 Tanggal=> ".$row_dtl['FORMAT_TANGGAL']."<= ELEMENT_NAME =>".$row_dtl['ELEMENT_NAME']."<=SPL=>".$row_dtl['SIK_SPL']."<=Tot Alpha=>".$old_tot_alpha."<br>";
						}
						$old_person_id = $row_dtl['PERSON_ID'];
						$old_full_name = $row_dtl['FULL_NAME'];
						$old_date_min = $row_dtl['FORMAT_TANGGAL'];
						$old_date_max = $row_dtl['FORMAT_TANGGAL'];
						$old_org_id = $row_dtl['ORG_ID'];
						$old_org_name =  $row_dtl['ORG_NAME'];
						$old_dept_id = $row_dtl['DEPT_ID'];
						$old_dept_name = $row_dtl['DEPT'];
						$old_location_id = $row_dtl['LOCATION_ID'];
						$old_location_name = $row_dtl['LOCATION_CODE'];
						$old_pos_id = $row_dtl['POS_ID'];
						$old_pos_name = $row_dtl['POS_NAME'];
						$old_group_salary = $row_dtl['GROUP_NAME'];
						$old_tot_alpha = 1;
						$old_period_min = $dt_prd_gaji_min;
						$old_period_max = $dt_prd_gaji_max;
						$old_top_mgr_id =  $row_dtl['TOP_MGR_ID'];
	
					//	$testing .= " 2 Tanggal=> ".$row_dtl['FORMAT_TANGGAL']."<= ELEMENT_NAME =>".$row_dtl['ELEMENT_NAME']."<=SPL=>".$row_dtl['SIK_SPL']."<=Tot Alpha=>".$old_tot_alpha."<br>";
						
					}
					else
					{
						$dt['DATA_PERSON_ID'] = $old_person_id;
						$dt['DATA_EMPLOYEE_NAME'] = $old_full_name;
						$dt['DATA_ORGANIZATION_ID'] = $old_org_id;
						$dt['DATA_ORGANIZATION_UNITS'] = $old_org_name;
						$dt['DATA_DEPARTMENT_ID'] = $old_dept_id;
						$dt['DATA_DEPARTMENT'] = $old_dept_name;
						$dt['DATA_PLANT_ID'] = $old_location_id;
						$dt['DATA_PLANT'] = $old_location_name;
						$dt['DATA_POS_ID'] = $old_pos_id;
						$dt['DATA_POS_NAME'] = $old_pos_name;
						$dt['DATA_START_DATE_ALPHA'] = $old_date_min;
						$dt['DATA_END_DATE_ALPHA'] = $old_date_max;	
						$dt['DATA_GROUP_SALARY'] = $old_group_salary;
						$dt['DATA_TOP_MGR_ID'] = $old_top_mgr_id;

						if(($old_period_min == $dt_prd_gaji_min) && ($old_period_max == $dt_prd_gaji_max))
						{
							$old_tot_alpha = $old_tot_alpha + 1;
							$old_date_max = $row_dtl['FORMAT_TANGGAL'];
						
							$dt['DATA_TOTAL_ALPHA'] = $old_tot_alpha;
							$dt['DATA_START_PERIOD'] = $old_period_min;
							$dt['DATA_END_PERIOD'] = $old_period_max;
							
							$is_insert = TRUE;
					
							$testing .= " 3.1 Tanggal=> ".$row_dtl['FORMAT_TANGGAL']."<= ELEMENT_NAME =>".$row_dtl['ELEMENT_NAME']."<=SPL=>".$row_dtl['SIK_SPL']."<=Tot Alpha=>".$old_tot_alpha."<br>";
							
							$testing .= "old_period_min =>".$old_period_min."<=dt_prd_gaji_min=>".$dt_prd_gaji_min."<=old_period_max=>". $old_period_max."<=dt_prd_gaji_max=>".$dt_prd_gaji_max."<br>";
						}
						else
						{
							$dt['DATA_TOTAL_ALPHA'] = $old_tot_alpha;
							$dt['DATA_START_PERIOD'] = $old_period_min;
							$dt['DATA_END_PERIOD'] = $old_period_max;
							
							if ($old_tot_alpha >= $max_alpha)
							{
								$no = $no + 1;
								$dt['DATA_NO'] = $no;
																	
								$dt_in_req['ID_EMP_FREEZE'] = $dt['DATA_PERSON_ID'];
								$dt_in_req['ID_ORGANIZATION'] = $dt['DATA_ORGANIZATION_ID'];
								$dt_in_req['ID_PLANT'] = $dt['DATA_PLANT_ID'];
								$dt_in_req['ID_DEPARTMENT'] = $dt['DATA_DEPARTMENT_ID'];
								$dt_in_req['START_PERIOD'] = $dt['DATA_START_PERIOD'];
								$dt_in_req['END_PERIOD'] = $dt['DATA_END_PERIOD'];
								$dt_in_req['TOT_ALPHA'] = $dt['DATA_TOTAL_ALPHA'];
								$dt_in_req['DATE_START_ALPHA'] = $dt['DATA_START_DATE_ALPHA'];
								$dt_in_req['DATE_END_ALPHA'] = $dt['DATA_END_DATE_ALPHA'];
								$dt_in_req['POSITION_ID'] = $dt['DATA_POS_ID'];
								$dt_in_req['POSITION_NAME'] = $dt['DATA_POS_NAME'];
								$dt_in_req['APP_ID'] = $dt_in['APP_ID'];
								$dt_in_req['TRANSAKSI_KODE'] = $dt_in['TRANSAKSI_KODE'];
								
								// $dt_in['ID_MGR']
								$data_req_unfreeze = get_id_req_unfreeze($con, $dt_in_req);
								$dt['DATA_HD_ID'] = $data_req_unfreeze['ID_TRANSAKSI'];
								$dt['DATA_ATTACHMENT'] = $data_req_unfreeze['FILE_ATTC'];
								$dt['DATA_MGR_NOTES'] = $data_req_unfreeze['MGR_NOTES'];
								$dt['DATA_APPROVAL_STATUS'] = $data_req_unfreeze['APPROVAL_STATUS'];
								$dt['DATA_APPROVAL_LEVEL'] = $data_req_unfreeze['APPROVAL_LEVEL'];
						
								if($dt['DATA_APPROVAL_LEVEL'] <= $dt_in['APPROVAL_LEVEL'])
								{
									$dt_new[] = $dt; 
									$dt_top_mgr[$dt['DATA_TOP_MGR_ID']][] = $dt;
								}
							}
							
							$is_insert = FALSE;
							
							$old_person_id = $row_dtl['PERSON_ID'];
							$old_full_name = $row_dtl['FULL_NAME'];
							$old_date_min = $row_dtl['FORMAT_TANGGAL'];
							$old_date_max = $row_dtl['FORMAT_TANGGAL'];
							$old_org_id = $row_dtl['ORG_ID'];
							$old_org_name =  $row_dtl['ORG_NAME'];
							$old_dept_id = $row_dtl['DEPT_ID'];
							$old_dept_name = $row_dtl['DEPT'];
							$old_location_id = $row_dtl['LOCATION_ID'];
							$old_location_name = $row_dtl['LOCATION_CODE'];
							$old_pos_id = $row_dtl['POS_ID'];
							$old_pos_name = $row_dtl['POS_NAME'];			
							$old_group_salary = $row_dtl['GROUP_NAME'];
							$old_tot_alpha = 1;
							$old_period_min = $dt_prd_gaji_min;
							$old_period_max = $dt_prd_gaji_max;
							$old_top_mgr_id = $row_dtl['TOP_MGR_ID'];
						/*
							$testing .= " 3.2 Tanggal=> ".$row_dtl['FORMAT_TANGGAL']."<= ELEMENT_NAME =>".$row_dtl['ELEMENT_NAME']."<=SPL=>".$row_dtl['SIK_SPL']."<=Tot Alpha=>".$old_tot_alpha."<br>";
							$testing .= "old_period_min =>".$old_period_min."<=dt_prd_gaji_min=>".$dt_prd_gaji_min."<=old_period_max=>". $old_period_max."<=dt_prd_gaji_max=>".$dt_prd_gaji_max."<br>";
						*/
						}
					}
				}
				// jika selain alpha
				else
				{
					if($old_tot_alpha >= $max_alpha)
					{
						$dt['DATA_PERSON_ID'] = $old_person_id;
						$dt['DATA_EMPLOYEE_NAME'] = $old_full_name;
						$dt['DATA_ORGANIZATION_ID'] = $old_org_id;
						$dt['DATA_ORGANIZATION_UNITS'] = $old_org_name;
						$dt['DATA_DEPARTMENT_ID'] = $old_dept_id;
						$dt['DATA_DEPARTMENT'] = $old_dept_name;
						$dt['DATA_PLANT_ID'] = $old_location_id;
						$dt['DATA_PLANT'] = $old_location_name;
						$dt['DATA_TOTAL_ALPHA'] = $old_tot_alpha;
						$dt['DATA_START_DATE_ALPHA'] = $old_date_min;
						$dt['DATA_END_DATE_ALPHA'] = $old_date_max;
						$dt['DATA_GROUP_SALARY'] = $old_group_salary;
						$dt['DATA_START_PERIOD'] = $old_period_min;
						$dt['DATA_END_PERIOD'] = $old_period_max;
						$dt['DATA_POS_ID'] = $old_pos_id;
						$dt['DATA_POS_NAME'] = $old_pos_name;
						$dt['DATA_TOP_MGR_ID'] = $old_top_mgr_id;
							
						$no = $no + 1;
						$dt['DATA_NO'] = $no;
													
						$dt_in_req['ID_EMP_FREEZE'] = $dt['DATA_PERSON_ID'];
						$dt_in_req['ID_ORGANIZATION'] = $dt['DATA_ORGANIZATION_ID'];
						$dt_in_req['ID_PLANT'] = $dt['DATA_PLANT_ID'];
						$dt_in_req['ID_DEPARTMENT'] = $dt['DATA_DEPARTMENT_ID'];
						$dt_in_req['START_PERIOD'] = $dt['DATA_START_PERIOD'];
						$dt_in_req['END_PERIOD'] = $dt['DATA_END_PERIOD'];
						$dt_in_req['TOT_ALPHA'] = $dt['DATA_TOTAL_ALPHA'];
						$dt_in_req['DATE_START_ALPHA'] = $dt['DATA_START_DATE_ALPHA'];
						$dt_in_req['DATE_END_ALPHA'] = $dt['DATA_END_DATE_ALPHA'];
						$dt_in_req['POSITION_ID'] = $dt['DATA_POS_ID'];
						$dt_in_req['POSITION_NAME'] = $dt['DATA_POS_NAME'];
						$dt_in_req['APP_ID'] = $dt_in['APP_ID'];
						$dt_in_req['TRANSAKSI_KODE'] = $dt_in['TRANSAKSI_KODE'];
								
						// $dt_in['ID_MGR']
						$data_req_unfreeze = get_id_req_unfreeze($con, $dt_in_req);
						$dt['DATA_HD_ID'] = $data_req_unfreeze['ID_TRANSAKSI'];
						$dt['DATA_ATTACHMENT'] = $data_req_unfreeze['FILE_ATTC'];
						$dt['DATA_MGR_NOTES'] = $data_req_unfreeze['MGR_NOTES'];
						$dt['DATA_APPROVAL_STATUS'] = $data_req_unfreeze['APPROVAL_STATUS'];
						$dt['DATA_APPROVAL_LEVEL'] = $data_req_unfreeze['APPROVAL_LEVEL'];
					
						if($dt['DATA_APPROVAL_LEVEL'] <= $dt_in['APPROVAL_LEVEL'])
						{
							$dt_new[] = $dt; 
							$dt_top_mgr[$dt['DATA_TOP_MGR_ID']][] = $dt;
						}
						
						$old_person_id = NULL;
						$old_full_name = NULL;
						$old_date_min = NULL;
						$old_date_max = NULL;
						$old_element = NULL;
						$old_org_id = NULL;
						$old_org_name = NULL;
						$old_dept_id = NULL;
						$old_dept_name = NULL;
						$old_pos_id = NULL;
						$old_pos_name = NULL;
						$old_location_id = NULL;
						$old_location_name = NULL;
						$old_group_salary = NULL;
						$old_tot_alpha = 0;
						$old_period_min = NULL;
						$old_period_max = NULL;
						$old_top_mgr_id = NULL;
					/*		
						$testing .= " 4 Tanggal=> ".$row_dtl['FORMAT_TANGGAL']."<= ELEMENT_NAME =>".$row_dtl['ELEMENT_NAME']."<=SPL=>".$row_dtl['SIK_SPL']."<=Tot Alpha=>".$old_tot_alpha."<br>";
					*/	
					}
					else
					{
						$old_person_id = NULL;
						$old_full_name = NULL;
						$old_date_min = NULL;
						$old_date_max = NULL;
						$old_element = NULL;
						$old_org_id = NULL;
						$old_org_name = NULL;
						$old_dept_id = NULL;
						$old_dept_name = NULL;
						$old_location_id = NULL;
						$old_location_name = NULL;
						$old_pos_id = NULL;
						$old_pos_name = NULL;
						$old_group_salary = NULL;
						$old_tot_alpha = 0;
						$old_period_min = NULL;
						$old_period_max = NULL;
						$old_top_mgr_id = NULL;
						
						$testing .= " 5 Tanggal=> ".$row_dtl['FORMAT_TANGGAL']."<= ELEMENT_NAME =>".$row_dtl['ELEMENT_NAME']."<=SPL=>".$row_dtl['SIK_SPL']."<=Tot Alpha=>".$old_tot_alpha."<br>";
					
					}
				}
			}
			
			// jika record terakhir jumlah alpha > max_alpha maka insert
			if(($old_tot_alpha >= $max_alpha) && ($is_insert == TRUE)){
				$no = $no + 1;
				$dt['DATA_NO'] = $no;
				
				$dt_in_req['ID_EMP_FREEZE'] = $dt['DATA_PERSON_ID'];
				$dt_in_req['ID_ORGANIZATION'] = $dt['DATA_ORGANIZATION_ID'];
				$dt_in_req['ID_PLANT'] = $dt['DATA_PLANT_ID'];
				$dt_in_req['ID_DEPARTMENT'] = $dt['DATA_DEPARTMENT_ID'];
				$dt_in_req['START_PERIOD'] = $dt['DATA_START_PERIOD'];
				$dt_in_req['END_PERIOD'] = $dt['DATA_END_PERIOD'];
				$dt_in_req['TOT_ALPHA'] = $dt['DATA_TOTAL_ALPHA'];
				$dt_in_req['DATE_START_ALPHA'] = $dt['DATA_START_DATE_ALPHA'];
				$dt_in_req['DATE_END_ALPHA'] = $dt['DATA_END_DATE_ALPHA'];
				$dt_in_req['POSITION_ID'] = $dt['DATA_POS_ID'];
				$dt_in_req['POSITION_NAME'] = $dt['DATA_POS_NAME'];
				$dt_in_req['APP_ID'] = $dt_in['APP_ID'];
				$dt_in_req['TRANSAKSI_KODE'] = $dt_in['TRANSAKSI_KODE'];
												
				// $dt_in['ID_MGR']
				$data_req_unfreeze = get_id_req_unfreeze($con, $dt_in_req);
				$dt['DATA_HD_ID'] = $data_req_unfreeze['ID_TRANSAKSI'];
				$dt['DATA_ATTACHMENT'] = $data_req_unfreeze['FILE_ATTC'];
				$dt['DATA_MGR_NOTES'] = $data_req_unfreeze['MGR_NOTES'];
				$dt['DATA_APPROVAL_STATUS'] = $data_req_unfreeze['APPROVAL_STATUS'];
				$dt['DATA_APPROVAL_LEVEL'] = $data_req_unfreeze['APPROVAL_LEVEL'];
			
				if($dt['DATA_APPROVAL_LEVEL'] <= $dt_in['APPROVAL_LEVEL'])
				{	
					$dt_new[] = $dt;
					$dt_top_mgr[$dt['DATA_TOP_MGR_ID']][] = $dt;
				}							
			}
		}
		
	//	echo "<br><br>==>".$testing."<==<br><br>";
		// var_dump($dt_new);
		$dt_rtn['sql'] = $sql;
		$dt_rtn['data'] = $dt_new;
		$dt_rtn['data_mgr'] = $dt_top_mgr;
		
		return $dt_rtn;
	}
	
	/**
	* Get Count Work Day (Shift Employee)
	**/
	function count_wd_shift($con, $dt_in){
		
		$weekday = array('SUNDAY');
		$periode_min = NULL;
		$periode_max = NULL;
		$assigment_id = NULL;
		$sql_join = "";
		$sql_where = "";
		
		if(array_key_exists('periode_min', $dt_in) && !empty($dt_in['periode_min'])){
			$periode_min = $dt_in['periode_min'];
		}
		
		if(array_key_exists('periode_max', $dt_in) && !empty($dt_in['periode_max'])){
			$periode_max = $dt_in['periode_max'];
		}
		
		if(array_key_exists('assigment_id', $dt_in) && !empty($dt_in['assigment_id'])){
			$assigment_id = $dt_in['assigment_id'];
		}
		
		if($use_holiday == TRUE)
		{
			$sql_join = "LEFT JOIN (
							SELECT A.HOLIDAY_DATE
							FROM APPS.HXT_HOLIDAY_DAYS A, APPS.HXT_HOLIDAY_CALENDARS B
							WHERE A.HCL_ID=B.ID 
								AND B.EFFECTIVE_END_DATE>SYSDATE 
								AND A.HOLIDAY_DATE >= TO_DATE('".$periode_min."', 'YYYY-MM-DD')
								AND A.HOLIDAY_DATE <= TO_DATE('".$periode_max."', 'YYYY-MM-DD')
								AND UPPER(TO_CHAR(A.HOLIDAY_DATE, 'DAY')) <> UPPER('SUNDAY')
							UNION ALL
							SELECT
							  ( TO_DATE('".$periode_min."', 'YYYY-MM-DD') + level - 1) AS HOLIDAY_DATE
							FROM
							  dual
							WHERE TRIM(UPPER(TO_CHAR(( TO_DATE('".$periode_min."', 'YYYY-MM-DD') + level - 1), 'DAY'))) = TRIM(UPPER('SUNDAY'))
							CONNECT BY LEVEL <= ( TO_DATE('".$periode_max."', 'YYYY-MM-DD') -  TO_DATE('".$periode_min."', 'YYYY-MM-DD') + 1)
						) HLD ON HLD.HOLIDAY_DATE = CAL.DT";
			$sql_where .= " AND HLD.HOLIDAY_DATE IS NULL ";
		}
		
		$sql = "SELECT DT DATES, TO_CHAR(DT, 'DAY') DAY , HWW.NAME SHIFT, HS.STANDARD_START, 
					HS.STANDARD_STOP WORK_SCHEDULE, HS.HOURS WORK_HOUR
				FROM
					(
						SELECT TRUNC ((TO_DATE('".$periode_max."', 'DDMMYYYY')+1) - ROWNUM) DT
						FROM DUAL CONNECT BY ROWNUM <= (TO_DATE('".$periode_max."', 'DDMMYYYY')-(TO_DATE('".$periode_min."', 'DDMMYYYY')-1))
					) CAL
				LEFT JOIN MJ.MJ_M_SHIFT MMS ON CAL.DT BETWEEN MMS.DATE_FROM
					AND nvl(MMS.DATE_TO, to_date('12/31/4272','MM/DD/YYYY'))
					AND MMS.ASSIGNMENT_ID = '".$assigment_id."' AND MMS.STATUS = 'Y'
				LEFT JOIN HXT_WORK_SHIFTS_FMV HWS ON MMS.SHIFT_ID = HWS.TWS_ID
					AND trim(TO_CHAR(DT, 'DAY')) = UPPER(HWS.MEANING)
				LEFT JOIN HXT_SHIFTS HS ON HWS.SHT_ID = HS.ID 
				LEFT JOIN APPS.HXT_WEEKLY_WORK_SCHEDULES_FMV HWW ON MMS.SHIFT_ID = HWW.ID
				".$sql_join."
				".$sql_where."
				ORDER BY CAL.DT";
				
		$rslt = oci_parse($con, $sql);
		oci_execute($rslt);
		
		$tot = oci_num_rows($rslt);
		
		if($tot > 0){
			$dt_rtn['rslt'] = oci_fetch_assoc($rslt);
			$dt_rtn['tot'] = $tot;
		}
		else{
			$dt_rtn['tot'] = 0;
		}
		
		return $dt_rtn;
	}
	
	/**
	* function get holiday day
	**/
	function get_holiday_day($con, $dt_in){
		$periode_min = "";
		$periode_max = "";
		
		if(array_key_exists('periode_min', $dt_in) && !empty($dt_in['periode_min']))
			$sql_where .= " AND TANGGAL >= TO_DATE('".$periode_min."', 'YYYY-MM-DD') ";
		if(array_key_exists('periode_max', $dt_in) && !empty($dt_in['periode_max']))
			$sql_where .= " AND TANGGAL <= TO_DATE('".$periode_max."', 'YYYY-MM-DD') ";
		
		$sql = "SELECT A.HOLIDAY_DATE
				FROM APPS.HXT_HOLIDAY_DAYS A, APPS.HXT_HOLIDAY_CALENDARS B
				WHERE A.HCL_ID=B.ID 
					AND B.EFFECTIVE_END_DATE>SYSDATE 
					AND A.HOLIDAY_DATE >= TO_DATE('".$periode_min."', 'YYYY-MM-DD')
					AND A.HOLIDAY_DATE <= TO_DATE('".$periode_max."', 'YYYY-MM-DD')
					AND UPPER(TO_CHAR(A.HOLIDAY_DATE, 'DAY')) <> UPPER('SUNDAY')
				UNION ALL
				SELECT
				  ( TO_DATE('".$periode_min."', 'YYYY-MM-DD') + level - 1) AS day
				FROM
				  dual
				WHERE TRIM(UPPER(TO_CHAR(( TO_DATE('".$periode_min."', 'YYYY-MM-DD') + level - 1), 'DAY'))) = TRIM(UPPER('SUNDAY'))
				CONNECT BY LEVEL <= ( TO_DATE('".$periode_max."', 'YYYY-MM-DD') -  TO_DATE('".$periode_min."', 'YYYY-MM-DD') + 1)
				ORDER BY HOLIDAY_DATE";
		$rslt = oci_parse($con, $sql);
		oci_execute($rslt);
		
		$tot = oci_num_rows($rslt);
		
		if($tot > 0){
			$dt_rtn['rslt'] = oci_fetch_assoc($rslt);
			$dt_rtn['tot'] = $tot;
		}
		else{
			$dt_rtn['tot'] = 0;
		}
		
		return $dt_rtn;
	}
	/*
	function get_checklog($con, $dt_in){
		
		$sql_where = "";
		
		if(array_key_exists('periode_min', $dt_in) && !empty($dt_in['periode_min'])){
			$periode_min = $dt_in['periode_min'];
			$sql_where .= " AND MTT.TANGGAL >= TO_DATE('".$periode_min."', 'YYYY-MM-DD') ";
		}
		
		if(array_key_exists('periode_max', $dt_in) && !empty($dt_in['periode_max'])){
			$periode_max = $dt_in['periode_max'];
			$sql_where .= " AND MTT.TANGGAL <= TO_DATE('".$periode_max."', 'YYYY-MM-DD') ";
		}
		
		if(array_key_exists('person_id', $dt_in) && !empty($dt_in['person_id'])){
			$person_id = $dt_in['person_id'];
		}
		
		$sql = "SELECT DISTINCT PPF.PERSON_ID
					, PPF.FULL_NAME
					, MTT.TANGGAL
					, MTT.JAM_MASUK
					, MTT.JAM_KELUAR
					, MME.ELEMENT_NAME
					,
					CASE WHEN MME.ELEMENT_NAME<>'LEMBUR' THEN MTS.KATEGORI
						WHEN MME.ELEMENT_NAME='LEMBUR' THEN (CASE WHEN NVL(SPL.ID, 0)<>0 THEN 'ADA SPL' ELSE '' END)
					END AS SIK_SPL 
					, MTS.IJIN_KHUSUS
					,
					CASE WHEN MME.ELEMENT_NAME<>'LEMBUR' THEN (
							CASE WHEN NVL(MTS.ID, 0)<>0 AND MTS.STATUS_DOK='Approved' THEN
								(SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
								FROM MJ.MJ_T_APPROVAL MTA 
								WHERE MTA.TRANSAKSI_ID=MTS.ID AND MTA.APP_ID=1 AND MTA.TRANSAKSI_KODE='SIK'
									AND MTA.ID = (
													SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL
													WHERE TRANSAKSI_ID=MTS.ID AND APP_ID=1 AND TRANSAKSI_KODE='SIK'))
							ELSE ''
							END
						)
						WHEN MME.ELEMENT_NAME='LEMBUR' THEN (
							CASE WHEN NVL(SPL.ID, 0)<>0 AND SPL.STATUS_DOK='Approved' THEN (
								SELECT TO_CHAR(MTA.CREATED_DATE, 'YYYY-MM-DD')
								FROM MJ.MJ_T_APPROVAL MTA 
								WHERE MTA.TRANSAKSI_ID=SPL.ID AND MTA.APP_ID=1 AND MTA.TRANSAKSI_KODE='SPL'
								AND MTA.ID = (
												SELECT MAX(ID) FROM MJ.MJ_T_APPROVAL
												WHERE TRANSAKSI_ID=SPL.ID AND APP_ID=1 AND TRANSAKSI_KODE='SPL')
							)
							ELSE ''
							END
						)
					ELSE ''
					END AS TGL_APPROVED
					, J.NAME DEPT, HOU.NAME ORG_NAME, HOU.ORGANIZATION_ID, HL.LOCATION_ID, HL.LOCATION_CODE,
					PPG.GROUP_NAME
				FROM MJ.MJ_T_TIMECARD MTT
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTT.PERSON_ID AND PPF.EFFECTIVE_END_DATE > SYSDATE
				INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PPF.PERSON_ID=PAF.PERSON_ID AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
				LEFT JOIN APPS.PER_JOBS J ON PAF.JOB_ID=J.JOB_ID
				INNER JOIN MJ.MJ_M_ELEMENT MME ON MME.ELEMENT_ID=MTT.STATUS
				LEFT JOIN MJ.MJ_T_SIK MTS ON MTS.PERSON_ID=PPF.PERSON_ID AND MTT.TANGGAL BETWEEN MTS.TANGGAL_FROM AND MTS.TANGGAL_TO AND MTS.STATUS=1 AND STATUS_DOK <> 'Disapproved'
				LEFT JOIN 
				(
					SELECT MTSPD.ID, MTSP.TANGGAL_SPL, MTSPD.PERSON_ID, MTSPD.STATUS_DOK
					FROM MJ.MJ_T_SPL MTSP
					INNER JOIN MJ.MJ_T_SPL_DETAIL MTSPD ON MTSP.ID=MTSPD.MJ_T_SPL_ID
					WHERE MTSP.STATUS=1
				) SPL ON SPL.TANGGAL_SPL=MTT.TANGGAL AND SPL.PERSON_ID=PPF.PERSON_ID
				LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON HOU.ORGANIZATION_ID = PAF.ORGANIZATION_ID 
				LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = PAF.LOCATION_ID
				INNER JOIN APPS.PAY_PEOPLE_GROUPS PPG ON PPG.PEOPLE_GROUP_ID = PAF.PEOPLE_GROUP_ID
				WHERE (MME.ELEMENT_NAME <> 'LEMBUR' OR NVL(SPL.ID, 0)<>0)
					AND PPF.PERSON_ID = '".$person_id."'
					".$sql_where."
				";
				
		$rslt = oci_parse($con, $sql);
		oci_execute($rslt);
		
		$tot = oci_num_rows($rslt);
		
		if($tot > 0){
			$dt_rtn['rslt'] = oci_fetch_assoc($rslt);
			$dt_rtn['tot'] = $tot;
		}
		else{
			$dt_rtn['tot'] = 0;
		}
		
		return $dt_rtn;
	}
	*/
	function insert_freeze_employee($con, $dt_in)
	{
		if(!empty($dt_in['ID_TRANSAKSI']))
		{
			$dt = get_id_req_unfreeze($con, $dt_in);
			
			if($dt['ID_TRANSAKSI'] > 0)
			{
				$sql = "UPDATE MJ.MJ_T_FREEZE_EMPLOYEE
						SET MGR_NOTES = '".$dt_in['MGR_NOTES']."'
						WHERE ID = '".$dt['ID_TRANSAKSI']."' ";
				$rslt = oci_parse($con, $sql);
				oci_execute($rslt);
				
				insert_attachment($con, $dt_in, $state='UPDATE');
				$result = array('success' => true,
								'msg' 	  => 'Data Berhasil Disimpan',
								'status' => 'sukses',
							);
			}
			else
			{
				$result = array('success' => true,
								'msg' 	  => 'Data Berhasil Tidak Ditemukan',
								'status' => 'gagal',
							);
			}
		}
		else
		{	
			$q_seq = oci_parse($con,"SELECT MJ.MJ_T_FREEZE_EMPLOYEE_SEQ.nextval FROM dual");
			oci_execute($q_seq);
			$row_seq = oci_fetch_row($q_seq);
			$hd_id_seq = $row_seq[0];
			
			$sql = "INSERT INTO MJ.MJ_T_FREEZE_EMPLOYEE(ID, ID_EMP_FREEZE, ID_ORGANIZATION,
						ID_PLANT, ID_DEPARTMENT,
						START_PERIOD, END_PERIOD, TOT_ALPHA,
						DATE_START_ALPHA, DATE_END_ALPHA,
						DATE_FREEZE, FREEZE_STATUS, ACTIVE_STATUS, CREATE_DATE, CREATED_BY,
						ID_MGR, MGR_NOTES, APPROVAL_STATUS,
						APPROVAL_LEVEL, ID_POSITION)
					VALUES('".$hd_id_seq."', '".$dt_in['ID_EMP_FREEZE']."', '".$dt_in['ID_ORGANIZATION']."',
						'".$dt_in['ID_PLANT']."', '".$dt_in['ID_DEPARTMENT']."',
						TO_DATE('".$dt_in['START_PERIOD']."', 'YYYY-MM-DD'),
						TO_DATE('".$dt_in['END_PERIOD']."', 'YYYY-MM-DD'), '".$dt_in['TOT_ALPHA']."',
						TO_DATE('".$dt_in['DATE_START_ALPHA']."', 'YYYY-MM-DD'),
						TO_DATE('".$dt_in['DATE_END_ALPHA']."', 'YYYY-MM-DD'),
						SYSDATE, 'Y',
						'".$dt_in['ACTIVE_STATUS']."', SYSDATE, '".$dt_in['CREATED_BY']."',
						'".$dt_in['ID_MGR']."', '".$dt_in['MGR_NOTES']."', '".$dt_in['APPROVAL_STATUS']."',
						'".$dt_in['APPROVAL_LEVEL']."', '".$dt_in['ID_POS']."')
					";
			//echo $sql;
			$rslt = oci_parse($con, $sql);
			
			if(oci_execute($rslt))
			{
				$dt_in['ID_TRANSAKSI'] = $hd_id_seq;
				insert_attachment($con, $dt_in, $state='INSERT');
				
				$hrd_name = "";
				$dt_req = get_email_request($con, $dt_in['ID_MGR']);
				$eml_req = array($dt_req['EMAIL']);
				$name_req = $dt_req['FULL_NAME'];
				
				$dt_email_hrd = get_email_hrd($con, 'MGR', 'HRD', $dt_in['ID_PLANT']);
				
				if(!empty($dt_email_hrd['FULL']))
				{
					foreach($dt_email_hrd['FULL'] as $val_fn_hrd)
					{
						if($hrd_name != "")
							$hrd_name .= "; ";
						$hrd_name .= $val_fn_hrd['FULL_NAME'];
					}
				}
				
				$eml_sbj = "Auto Email Pengajuan Unfreeze Employee Salary";
				$eml_body = "Dear ".$hrd_name.", \n\n".
							"Mohon untuk melakukan Approve Pengajuan Unfreeze Employee Salary \n\n".
							"Manager yang mengajukan \t : ".$name_req." \n".
							"Nama Karyawan \t\t : ".$dt_in['NAME_EMP_FREEZE']." \n".
							"Location \t\t\t : ".$dt_in['PLANT_NAME']."  \n".
							"Department \t\t\t : ".$dt_in['DEPARTMENT_NAME']." \n".
							"Jabatan \t\t\t : ".$dt_in['POS_NAME']." \n".
							"Periode Absen \t\t\t : ".$dt_in['START_PERIOD']." s/d ".$dt_in['END_PERIOD']." \n".
							"Periode Alpha \t\t\t : ".$dt_in['DATE_START_ALPHA']." s/d ".$dt_in['DATE_END_ALPHA']." \n".
							"Total Alpha \t\t\t : ".$dt_in['TOT_ALPHA']."\n\n".
							"Terima kasih.";
				
				$dt_eml['subject'] = $eml_sbj;	
				$dt_eml['body'] = $eml_body;	
				$dt_eml['to'] = $dt_email_hrd['EMAIL'];
				$dt_eml['cc'] = $eml_req;
				mjb_send_mail($prod_level = FALSE, $dt_eml);
				
				$result = array('success' => true,
								'msg' 	  => 'Data Berhasil Disimpan',
								'status' => 'sukses',
							);
			}
			else
			{
				$result = array('success' => true,
								'msg' 	  => 'Data Gagal Disimpan',
								'status' => 'gagal',
							);
			}			
		}
			
		return $result;
	}
	
	function apv_unfreeze_employee($con, $dt_in)
	{
		$s_set_updt_unfreeze = "";
		$dt_in['TRANSAKSI_KODE'] = 'UNFREEZE EMPLOYEE';
		
		if ($dt_in['STATUS'] == 'A')
		{
			$dt_in['FREEZE_STATUS'] = 'N';
			$dt_in['APPROVAL_STATUS'] = 'A';
			$s_set_updt_unfreeze .= " , APPROVAL_STATUS = '".$dt_in['APPROVAL_STATUS']."', DATE_UNFREEZE = SYSDATE ";
		}
		elseif ($dt_in['STATUS'] == 'D')
		{
			$dt_in['FREEZE_STATUS'] = 'Y';
			$dt_in['APPROVAL_STATUS'] = 'D';
			$s_set_updt_unfreeze .= " , APPROVAL_STATUS = '".$dt_in['APPROVAL_STATUS']."' ";
		}
		else
		{
			$dt_in['FREEZE_STATUS'] = 'Y';
		}		
		
		// insert approval
		$rst_seq = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM DUAL");
		oci_execute($rst_seq);
		$row_seq = oci_fetch_row($rst_seq);
		$seq_id = $row_seq[0];
		//$seq_id = 0;
		$s_apv = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE,
					TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE)
					VALUES ('".$seq_id."', '".$dt_in['APP_ID']."', '".$dt_in['EMP_ID']."', '".$dt_in['ID_TRANSAKSI']."',
						'".$dt_in['TRANSAKSI_KODE']."', '".$dt_in['TINGKAT']."', '".$dt_in['STATUS']."', '".$dt_in['KETERANGAN']."', '".$dt_in['NAME_CREATED_BY']."', SYSDATE)";
		// echo "<br><br>".$s_apv."<br><br>";
		$q_apv = oci_parse($con, $s_apv);
		oci_execute($q_apv);
		
		$s_updt_unfreeze = "UPDATE MJ.MJ_T_FREEZE_EMPLOYEE SET FREEZE_STATUS = '".$dt_in['FREEZE_STATUS']."',
								ACTIVE_STATUS = '".$dt_in['ACTIVE_STATUS']."', 
								LAST_UPDATED_DATE = SYSDATE, LAST_UPDATED_BY = '".$dt_in['LAST_UPDATED_BY']."',
								HRD_NOTES = '".$dt_in['KETERANGAN']."', APPROVAL_LEVEL = '".$dt_in['TINGKAT']."'
								".$s_set_updt_unfreeze."
							WHERE ID = '".$dt_in['ID_TRANSAKSI']."' ";
		// echo "<br><br>".$s_updt_unfreeze."<br><br>";
		$rslt = oci_parse($con,$s_updt_unfreeze);
		//oci_execute($rslt);
		
		if(oci_execute($rslt))
		{
			$hrd_name = "";
			$dt_mgr = get_email_request($con, $dt_in['ID_MGR']);
			$eml_mgr = array($dt_mgr['EMAIL']);
			$name_mgr = $dt_mgr['FULL_NAME'];
			
			$dt_hrd = get_email_request($con, $dt_in['EMP_ID']);
			$eml_hrd = array($dt_hrd['EMAIL']);
			$hrd_name = $dt_hrd['FULL_NAME'];
			
			$eml_body = "Dear ".$name_mgr.", \n\n";
			
			if ($dt_in['STATUS'] == 'A')
			{
				$eml_sbj = "Auto Email Approve Unfreeze Karyawan";
				$eml_body .= "Pengajuan Unfreeze Employee Salary Telah disetujui Oleh ".$hrd_name." \n\n";
						
			}
			elseif ($dt_in['STATUS'] == 'D')
			{
				$eml_sbj = "Auto Email Disapprove Unfreeze Karyawan";
				$eml_body .= "Untuk Pengajuan Unfreeze Employee Salary tidak disetujui Oleh ".$hrd_name." \n\n";
						
			}
			else
			{
				$eml_sbj = "Auto Email [unknown status] Unfreeze Karyawan";
				$eml_body .= "Untuk Pengajuan Unfreeze Employee Salary [unknown status] Oleh ".$hrd_name." \n\n";
			}

			$eml_body .= "Manager yang mengajukan \t : ".$name_mgr." \n".
						"Nama Karyawan \t\t : ".$dt_in['NAME_EMP_FREEZE']." \n".
						"Location \t\t\t : ".$dt_in['PLANT_NAME']." \n".
						"Department \t\t\t : ".$dt_in['DEPARTMENT_NAME']." \n".
						"Jabatan \t\t\t : ".$dt_in['POS_NAME']." \n".
						"Periode Absen \t\t\t : ".$dt_in['START_PERIOD']." s/d ".$dt_in['END_PERIOD']." \n".
						"Periode Alpha \t\t\t : ".$dt_in['DATE_START_ALPHA']." s/d ".$dt_in['DATE_END_ALPHA']." \n".
						"Total Alpha \t\t\t : ".$dt_in['TOT_ALPHA']."\n".
						"Keterangan Manager HRD \t : ".$dt_in['KETERANGAN']." \n\n".
						"Terima kasih.";
			
			$dt_eml['subject'] = $eml_sbj;	
			$dt_eml['body'] = $eml_body;	
			$dt_eml['to'] = $eml_mgr;
			$dt_eml['cc'] = $eml_hrd;
			mjb_send_mail($prod_level = FALSE, $dt_eml);
		}		
	}
	
	function get_request_unfreeze($con, $dt_in)
	{		
		$sql_where = "";
		$periode_min = "";
		$periode_max = "";
		
		// $org_id = 141;
		if(array_key_exists('periode_min', $dt_in) && !empty($dt_in['periode_min'])){
			$periode_min = $dt_in['periode_min'];
			$sql_where .= " AND MTFE.START_PERIOD >= TO_DATE('".$periode_min."', 'YYYY-MM-DD') ";
		}
		if(array_key_exists('periode_max', $dt_in) && !empty($dt_in['periode_max'])){
			$periode_max = $dt_in['periode_max'];
			$sql_where .= " AND MTFE.END_PERIOD <= TO_DATE('".$periode_max."', 'YYYY-MM-DD') ";
		}
		
		if(array_key_exists('org_id', $dt_in)){
			$org_id = $dt_in['org_id'];
			$sql_where .= " AND HOU.ORGANIZATION_ID = '".$org_id."' ";
		}
	
		if(array_key_exists('org_name', $dt_in)){
			$org_name = $dt_in['org_name'];
			$sql_where .= " AND HOU.NAME = '".$org_name."' ";
		}
		
		if(array_key_exists('plant_id', $dt_in)){
			$plant_id = $dt_in['plant_id'];

			if($plant_id != ''){
				$sql_where .= " AND HL.LOCATION_ID = '".$plant_id."' ";
			}
		}

		if(array_key_exists('plant_name', $dt_in)){
			$plant_name = $dt_in['plant_name'];

			if($plant_name != ''){
				$sql_where .= " AND HL.LOCATION_CODE = '".$plant_name."' ";
			}
		}

		if(array_key_exists('dept_id', $dt_in)){
			
			if($dt_in['dept_id'] != ''){
				$sql_where .= " AND J.JOB_ID = '".$dt_in['dept_id']."' ";
			}
		}
		
		if(array_key_exists('dept_name', $dt_in)){
			
			if($dt_in['dept_name'] != ''){
				$sql_where .= " AND J.NAME LIKE '%".$dt_in['dept_name']."%' ";
			}
		}
		
		if(array_key_exists('emp_id_freeze', $dt_in)){
			
			if($dt_in['emp_id_freeze'] != ''){
				$sql_where .= " AND PPF.PERSON_ID = '".$dt_in['emp_id_freeze']."' ";
			}
		}

		//echo $sql_where;

		$sql = "SELECT MTFE.ID, MTFE.ID_EMP_FREEZE, MTFE.ID_ORGANIZATION, MTFE.ID_PLANT, MTFE.ID_DEPARTMENT,
					TO_CHAR(MTFE.START_PERIOD, 'YYYY-MM-DD') START_PERIOD,
					TO_CHAR(MTFE.END_PERIOD, 'YYYY-MM-DD') END_PERIOD,
					MTFE.TOT_ALPHA, TO_CHAR(MTFE.DATE_START_ALPHA, 'YYYY-MM-DD') DATE_START_ALPHA,
					TO_CHAR(MTFE.DATE_END_ALPHA, 'YYYY-MM-DD') DATE_END_ALPHA, TO_CHAR(MTFE.DATE_FREEZE, 'YYYY-MM-DD') DATE_FREEZE,
					MTFE.FREEZE_STATUS, MTFE.ACTIVE_STATUS,
					TO_CHAR(MTFE.DATE_UNFREEZE, 'YYYY-MM-DD'), MTFE.ID_MGR, MTFE.MGR_NOTES, MTFE.HRD_NOTES,
					MTFE.APPROVAL_STATUS, MTFE.APPROVAL_LEVEL,
					J.JOB_ID DEPT_ID, J.NAME DEPT, HOU.NAME ORG_NAME, HOU.ORGANIZATION_ID ORG_ID, HL.LOCATION_ID,
					HL.LOCATION_CODE, PPG.GROUP_NAME, PPF.FULL_NAME, PPF_MGR.FULL_NAME MGR_FN,
					PP.POSITION_ID POS_ID, PP.NAME POS_NAME
				FROM MJ.MJ_T_FREEZE_EMPLOYEE MTFE
				INNER JOIN APPS.PER_PEOPLE_F PPF ON PPF.PERSON_ID=MTFE.ID_EMP_FREEZE AND PPF.EFFECTIVE_END_DATE >= MTFE.START_PERIOD /*SYSDATE*/
				INNER JOIN APPS.PER_PEOPLE_F PPF_MGR ON PPF_MGR.PERSON_ID=MTFE.ID_MGR AND PPF_MGR.EFFECTIVE_END_DATE >= MTFE.START_PERIOD /*SYSDATE*/
				INNER JOIN APPS.PER_ALL_ASSIGNMENTS_F PAF ON PPF.PERSON_ID=PAF.PERSON_ID
					AND PAF.EFFECTIVE_END_DATE > SYSDATE AND PAF.PRIMARY_FLAG='Y'
				LEFT JOIN APPS.PER_JOBS J ON PAF.JOB_ID=J.JOB_ID
				LEFT JOIN APPS.HR_ORGANIZATION_UNITS HOU ON HOU.ORGANIZATION_ID = PAF.ORGANIZATION_ID 
				LEFT JOIN APPS.HR_LOCATIONS HL ON HL.LOCATION_ID = PAF.LOCATION_ID
				INNER JOIN APPS.PAY_PEOPLE_GROUPS PPG ON PPG.PEOPLE_GROUP_ID = PAF.PEOPLE_GROUP_ID
				INNER JOIN APPS.PER_POSITIONS PP ON PAF.POSITION_ID = PP.POSITION_ID
				WHERE MTFE.ACTIVE_STATUS = 'Y' AND MTFE.APPROVAL_LEVEL < '".$dt_in['APPROVAL_LEVEL']."' AND MTFE.APPROVAL_STATUS != 'D' ".$sql_where;
			//	echo $sql;
		$dt['sql_get_req_unfreeze'] = $sql;
		
		return $dt;
	}
	
	/*
	* function untuk mengambil email HRD
	*/
	function get_email_hrd($con, $pos_name=NULL, $dept_name=NULL, $location_id=NULL)
	{
		$dt=array();
		$dt['FULL'][0]['EMAIL'] = "";
		$dt['FULL'][0]['FULL_NAME'] = "";
		$dt['EMAIL'][0] = "";
			
		$sql = "SELECT DISTINCT REGEXP_SUBSTR(NAME,'[^.]+', 1, 1) DEPT,
					REGEXP_SUBSTR(NAME,'[^.]+', 1, 2) POS,
					REGEXP_SUBSTR(NAME,'[^.]+', 1, 3) FULL_POS,
					ppf.EMAIL_ADDRESS, ppf.FULL_NAME
				FROM mj.mj_m_user mu
				INNER JOIN APPS.PER_PEOPLE_F ppf ON ppf.PERSON_ID = mu.EMP_ID
				INNER JOIN APPS.PER_ASSIGNMENTS_F paf ON  mu.EMP_ID = paf.PERSON_ID
				INNER JOIN APPS.PER_POSITIONS PP ON PP.POSITION_ID=PAF.POSITION_ID
				INNER JOIN MJ.MJ_M_USERAPPROVAL MMUA ON MMUA.EMP_ID = ppf.PERSON_ID
				INNER JOIN MJ.MJ_M_AREA MMA ON MMA.NAMA_AREA = MMUA.NAMA_AREA AND MMA.STATUS = 'A'
				AND MMA.APP_ID=MMUA.APP_ID
				INNER JOIN MJ.MJ_M_AREA_DETAIL MMAD ON MMAD.AREA_ID = MMA.ID
				WHERE MMUA.TINGKAT = 3 AND MMUA.STATUS = 'A' AND MMUA.EMP_ID = mu.EMP_ID
					AND MMUA.APP_ID = '".APPCODE."'
					AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE
					AND REGEXP_SUBSTR(NAME,'[^.]+', 1, 2) = '".$pos_name."'
					AND REGEXP_SUBSTR(NAME,'[^.]+', 1, 1) = '".$dept_name."'
					AND MMAD.LOCATION_ID = '".$location_id."' ";
					
		//			echo $sql;
		$q = oci_parse($con, $sql);
		oci_execute($q);
		$i=0;
		while($row = oci_fetch_assoc($q))
		{
			$data['EMAIL'] = $row['EMAIL_ADDRESS'];
			$data['FULL_NAME'] = $row['FULL_NAME'];
			$dt['FULL'][$i] = $data; 
			$dt['EMAIL'][$i] = $data['EMAIL']; 
			$i++;
		}
		
		return $dt;
	}
	
	/*
	* function untuk mengambil email emp request
	*/
	function get_email_request($con, $person_id=NULL)
	{
		$dt = array();
		$dt['EMAIL'] = "";
		$dt['FULL_NAME'] = "";
		
		$s_eml_mgr = "SELECT DISTINCT ppf.EMAIL_ADDRESS, ppf.FULL_NAME
						FROM APPS.PER_PEOPLE_F ppf
						WHERE ppf.PERSON_ID = '".$person_id."' ";
		$q_eml_mgr = oci_parse($con, $s_eml_mgr);
		oci_execute($q_eml_mgr);
		$r_eml_mgr = oci_fetch_assoc($q_eml_mgr);
		$dt['EMAIL'] = $r_eml_mgr['EMAIL_ADDRESS'];
		$dt['FULL_NAME'] = $r_eml_mgr['FULL_NAME'];
		
		return $dt;
	}
	
	function get_id_req_unfreeze($con, $dt_in)
	{
		$sql_where = "";
		
		if (array_key_exists('ID_TRANSAKSI', $dt_in))
		{
			$sql_where .= " AND MTFE.ID='".$dt_in['ID_TRANSAKSI']."' ";
		}
		
		if (array_key_exists('ID_EMP_FREEZE', $dt_in))
		{
			$sql_where .= " AND MTFE.ID_EMP_FREEZE='".$dt_in['ID_EMP_FREEZE']."' ";
		}
		
		if (array_key_exists('ID_ORGANIZATION', $dt_in))
		{
			$sql_where .= " AND MTFE.ID_ORGANIZATION='".$dt_in['ID_ORGANIZATION']."' ";
		}
		
		if (array_key_exists('ID_PLANT', $dt_in))
		{
			$sql_where .= " AND MTFE.ID_PLANT='".$dt_in['ID_PLANT']."' ";
		}
		
		if (array_key_exists('ID_DEPARTMENT', $dt_in))
		{
			$sql_where .= " AND MTFE.ID_DEPARTMENT='".$dt_in['ID_DEPARTMENT']."' ";
		}
		
		if (array_key_exists('START_PERIOD', $dt_in))
		{
			$sql_where .= " AND MTFE.START_PERIOD=TO_DATE('".$dt_in['START_PERIOD']."', 'YYYY-MM-DD') ";
		}
		
		if (array_key_exists('END_PERIOD', $dt_in))
		{
			$sql_where .= " AND MTFE.END_PERIOD=TO_DATE('".$dt_in['END_PERIOD']."', 'YYYY-MM-DD') ";
		}
		
		if (array_key_exists('TOT_ALPHA', $dt_in))
		{
			$sql_where .= " AND MTFE.TOT_ALPHA='".$dt_in['TOT_ALPHA']."' ";
		}
		
		if (array_key_exists('DATE_START_ALPHA', $dt_in))
		{
			$sql_where .= " AND MTFE.DATE_START_ALPHA=TO_DATE('".$dt_in['DATE_START_ALPHA']."', 'YYYY-MM-DD') ";
		}
		
		if (array_key_exists('DATE_END_ALPHA', $dt_in))
		{
			$sql_where .= " AND MTFE.DATE_END_ALPHA=TO_DATE('".$dt_in['DATE_END_ALPHA']."', 'YYYY-MM-DD') ";
		}
		
		if (array_key_exists('ID_MGR', $dt_in))
		{
			$sql_where .= " AND MTFE.ID_MGR='".$dt_in['ID_MGR']."' ";
		}
		$dt['ID_TRANSAKSI'] = 0;
		$dt['MGR_NOTES'] = "";
		$dt['HRD_NOTES'] = "";
		$dt['APPROVAL_STATUS'] = "";
		$dt['APPROVAL_LEVEL'] = 0;
		
		$sql = "SELECT *
				FROM MJ.MJ_T_FREEZE_EMPLOYEE MTFE
				WHERE 1=1 ".$sql_where."
				ORDER BY ID DESC";
				
		$q = oci_parse($con, $sql);
		oci_execute($q);
		$r = oci_fetch_assoc($q);
		$dt['ID_TRANSAKSI'] = $r['ID'];
		$dt['MGR_NOTES'] = $r['MGR_NOTES'];
		$dt['HRD_NOTES'] = $r['HRD_NOTES'];
		$dt['APPROVAL_STATUS'] = $r['APPROVAL_STATUS'];
		$dt['APPROVAL_LEVEL'] = $r['APPROVAL_LEVEL'];
		
		$dt_in_attc['APP_ID'] = $dt_in['APP_ID'];
		$dt_in_attc['ID_TRANSAKSI'] = $dt['ID_TRANSAKSI'];
		$dt_in_attc['TRANSAKSI_KODE'] = $dt_in['TRANSAKSI_KODE'];
		$dt['FILE_ATTC'] = get_attachment($con, $dt_in_attc)['FILE_ATTC'];
		
		return $dt;
	}
	
	function insert_attachment($con, $dt_in, $state='INSERT')
	{
		if($state == 'UPDATE'){
			$queryUpload = "DELETE FROM MJ.MJ_M_UPLOAD WHERE APP_ID= '".$dt_in['APP_ID']."'
								AND TRANSAKSI_KODE = '".$dt_in['TRANSAKSI_KODE']."'
								AND TRANSAKSI_ID = '".$dt_in['ID_TRANSAKSI']."'";
			//echo $query;
			$resultUpload = oci_parse($con,$queryUpload);
			oci_execute($resultUpload);
		}
		
		// insert attachment 
		$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
					FROM MJ.MJ_TEMP_UPLOAD
					WHERE APP_ID = '".$dt_in['APP_ID']."' AND USERNAME='".$dt_in['ID_USER']."'
						AND TRANSAKSI_KODE='".$dt_in['TRANSAKSI_KODE']."'";
		$result = oci_parse($con, $query);
		oci_execute($result);
		while($row = oci_fetch_row($result))
		{
			$vFilename=$row[0];
			$vFilesize=$row[1];
			$vFiletype=$row[2];
			$vFilekode=$row[3];
			
			$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_M_UPLOAD_SEQ.nextval FROM DUAL");
			oci_execute($resultDetSeq);
			$rowDSeq = oci_fetch_row($resultDetSeq);
			$seqD = $rowDSeq[0];
			
			$queryUpload = "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID,
								FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE)
							VALUES ('".$seqD."', '".$dt_in['APP_ID']."', '".$dt_in['ID_TRANSAKSI']."',
								'".$vFilename."', '".$vFilesize."', '".$vFiletype."',
								'".$dt_in['ID_USER']."', SYSDATE, '".$vFilekode."' )";
			$resultUpload = oci_parse($con,$queryUpload);
			oci_execute($resultUpload);
		}
		$query = "DELETE FROM MJ.MJ_TEMP_UPLOAD WHERE APP_ID='".$dt_in['APP_ID']."'
					AND USERNAME='".$dt_in['ID_USER']."'
					AND TRANSAKSI_KODE='".$dt_in['TRANSAKSI_KODE']."'";
		$result = oci_parse($con, $query);
		oci_execute($result);
	}
	
	function get_attachment($con, $dt_in)
	{
		$s_attc = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME,
						TO_CHAR(CREATEDDATE, 'YYYY-MM-DD') CREATE_DATE, TRANSAKSI_KODE
					FROM MJ.MJ_M_UPLOAD
					WHERE APP_ID ='".$dt_in['APP_ID']."' AND TRANSAKSI_ID = '".$dt_in['ID_TRANSAKSI']."'
						AND TRANSAKSI_KODE='".$dt_in['TRANSAKSI_KODE']."' ";
		//					echo $s_attc;
		$q_attc = oci_parse($con, $s_attc);
		oci_execute($q_attc);
		$fl_upd_attc = "";
		while (($r_attc = oci_fetch_assoc($q_attc)) != false)
		{
			$fl_attc = md5($r_attc['CREATE_DATE']).$r_attc['FILESIZE'].md5($r_attc['FILENAME']).".".end(explode(".", $r_attc['FILENAME']))." target=_blank>".$r_attc['FILENAME']. "</a>";
						
			if ($fl_upd_attc != '')
				$fl_upd_attc .= ", ";
			$fl_upd_attc .= "<a href= ".PATHAPP."/upload/freeze_employee/".$fl_attc;
		}
		
		$dt['FILE_ATTC'] = $fl_upd_attc;	
		
		return $dt;
	}
	
	function auto_mail_freeze($con, $dt_in)
	{
		$dt_rtn = get_alpha_employee($con, $dt_in);
		
		// var_dump($dt_rtn['data']);
		
		foreach($dt_rtn['data_mgr'] as $key_alpha => $val_alpha)
		{
			$dt_mgr = get_email_request($con, $key_alpha);
			$eml_req = array($dt_mgr['EMAIL']);
			$name_req = $dt_mgr['FULL_NAME'];
			
			$eml_sbj = "Auto Email Freeze Freeze Employee Salary";
			$eml_body = "Dear ".$name_req.", \n\n".
					"Untuk Nama Karyawan dibawah ini sudah tidak melakukan absen selama 5 hari berturut-turut \n\n";
			$r_alpha = $val_alpha;
			foreach($r_alpha as $val_alpha_dtl)
			{
				$eml_body .= "Organization Units \t : ".$val_alpha_dtl['DATA_ORGANIZATION_UNITS']." \n".
									"Location \t\t : ".$val_alpha_dtl['DATA_PLANT']." \n".
									"Department \t\t : ".$val_alpha_dtl['DATA_DEPARTMENT']." \n".
									"Nama Karyawan \t : ".$val_alpha_dtl['DATA_EMPLOYEE_NAME']." \n".
									"Jabatan \t\t : ".$val_alpha_dtl['DATA_POS_NAME']." \n".
									"Periode Absen \t\t : ".$val_alpha_dtl['DATA_START_PERIOD']." s/d ".$val_alpha_dtl['DATA_END_PERIOD']." \n".
									"Periode Alpha \t\t : ".$val_alpha_dtl['DATA_START_DATE_ALPHA']." s/d ".$val_alpha_dtl['DATA_END_DATE_ALPHA']." \n".
									"Total Alpha \t\t : ".$val_alpha_dtl['DATA_TOTAL_ALPHA']."\n\n";
			}
			
			$eml_body .="Terima kasih.";
			$dt_eml['subject'] = $eml_sbj;	
			$dt_eml['body'] = $eml_body;	
			$dt_eml['to'] = $eml_req;
			mjb_send_mail($prod_level = FALSE, $dt_eml);
		}			
	}
?>