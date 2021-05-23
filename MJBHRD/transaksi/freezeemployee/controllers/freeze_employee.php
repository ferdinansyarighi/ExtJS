<?php
	session_start();
	require_once('../../../main/koneksi.php');
	require_once('../model/freeze_employee.php');
	require_once('../helper/define_session.php');
	require_once('../helper/acl.php');
	require_once('../helper/auto_email.php');
	
	$arr_pos = explode('.', $pos_name);
	$pos = $arr_pos[1];
	$dept = $arr_pos[0];
	
	$dt_tingkat = get_tingkat($dept, $pos);
	$tingkat = $dt_tingkat['tingkat'];
	$apv_status = $dt_tingkat['apv_status'];
	
	if(isset($_POST['formType']))
		$formType = $_POST['formType'];
	else	
		$formType = $_GET['formType'];
	
	$dt_in['ID_USER'] = $user_id;
	$dt_in['TRANSAKSI_KODE'] = 'UNFREEZE EMPLOYEE';
	$dt_in['APP_ID'] = APPCODE;
		
	if($formType == 'ReqUnfreeze')
	{	
		$arr_trans_id = json_decode($_POST['arrTransID']);
		$arr_person_id = json_decode($_POST['arrPersonID']);
		$arr_person_name = json_decode($_POST['arrPersonName']);
		$arr_org_id = json_decode($_POST['arrOrgID']);
		$arr_dept_id = json_decode($_POST['arrDeptID']);
		$arr_dept_name = json_decode($_POST['arrDeptName']);
		$arr_plant_id = json_decode($_POST['arrPlantID']);
		$arr_plant_name = json_decode($_POST['arrPlantName']);
		$arr_pos_id = json_decode($_POST['arrPosID']);
		$arr_pos_name = json_decode($_POST['arrPosName']);
		$arr_tot_alpha = json_decode($_POST['arrTotAlpha']);
		$arr_start_date_alpha = json_decode($_POST['arrStartDateAlpha']);
		$arr_end_date_alpha = json_decode($_POST['arrEndDateAlpha']);
		$arr_start_period = json_decode($_POST['arrStartPeriod']);
		$arr_end_period = json_decode($_POST['arrEndPeriod']);
		$arr_mgr_notes = json_decode($_POST['arrMgrNotes']);
		$active_status = $_POST['activeStatus'];
		
		$dt_in['FREEZE_STATUS'] = "";
		$dt_in['ACTIVE_STATUS'] = $active_status;
		$dt_in['DATE_UNFREEZE'] = "";
		$dt_in['CREATED_BY'] = $emp_id;
		$dt_in['ID_MGR'] = $emp_id;
		$dt_in['LAST_UPDATED_BY'] = $emp_id;
		$dt_in['ID_MGR'] = $emp_id;
		$dt_in['HRD_NOTES'] = "";
		$dt_in['APPROVAL_STATUS'] = $apv_status;
		$dt_in['APPROVAL_LEVEL'] = $tingkat;
		
		$i=0;
		foreach($arr_person_id as $dt_person_id){
			$dt_in['ID_EMP_FREEZE'] = $dt_person_id;
			$dt_in['ID_TRANSAKSI'] = $arr_trans_id[$i];
			$dt_in['NAME_EMP_FREEZE'] = $arr_person_name[$i];
			$dt_in['ID_ORGANIZATION'] = $arr_org_id[$i];
			$dt_in['ID_PLANT'] = $arr_plant_id[$i];
			$dt_in['PLANT_NAME'] = $arr_plant_name[$i];
			$dt_in['ID_POS'] = $arr_pos_id[$i];
			$dt_in['POS_NAME'] = $arr_pos_name[$i];
			$dt_in['ID_DEPARTMENT'] = $arr_dept_id[$i];
			$dt_in['DEPARTMENT_NAME'] = $arr_dept_name[$i];
			$dt_in['START_PERIOD'] = $arr_start_period[$i];
			$dt_in['END_PERIOD'] = $arr_end_period[$i];
			$dt_in['TOT_ALPHA'] = $arr_tot_alpha[$i];
			$dt_in['DATE_START_ALPHA'] = $arr_start_date_alpha[$i];
			$dt_in['DATE_END_ALPHA'] = $arr_end_date_alpha[$i];
			$dt_in['MGR_NOTES'] = $arr_mgr_notes[$i];
			
			insert_freeze_employee($con, $dt_in);
			$i++;
		}
		
		$totalrow = $i;
		$status = "sukses";
		$data = "sukses";
 
		$result = array('success' => true,
						'results' => $totalrow,
						'rows' 	  => $data,
						'status' => $status,
					);
		echo json_encode($result);
	}
	elseif($formType == 'ApvReqUnfreeze')
	{
		//var_dump(json_decode($_POST['arrPlantName']));
		$arr_trans_id = json_decode($_POST['arrTransID']);
		$arr_person_id = json_decode($_POST['arrPersonID']);
		$arr_person_name = json_decode($_POST['arrPersonName']);
		$arr_hrd_notes = json_decode($_POST['arrHrdNotes']);
		$arr_dept_id = json_decode($_POST['arrDeptID']);
		$arr_dept_name = json_decode($_POST['arrDeptName']);
		$arr_mgr_id = json_decode($_POST['arrMgrID']);
		$arr_mgr_name = json_decode($_POST['arrMgrName']);
		$arr_plant_id = json_decode($_POST['arrPlantID']);
		$arr_plant_name = json_decode($_POST['arrPlantName']);
		$arr_pos_id = json_decode($_POST['arrPosID']);
		$arr_pos_name = json_decode($_POST['arrPosName']);
		$arr_tot_alpha = json_decode($_POST['arrTotAlpha']);
		$arr_start_date_alpha = json_decode($_POST['arrStartDateAlpha']);
		$arr_end_date_alpha = json_decode($_POST['arrEndDateAlpha']);
		$arr_start_period = json_decode($_POST['arrStartPeriod']);
		$arr_end_period = json_decode($_POST['arrEndPeriod']);
		$arr_mgr_notes = json_decode($_POST['arrMgrNotes']);
		$active_status = $_POST['activeStatus'];
		$apv_status = $_POST['apvStatus'];
		
		$dt_in['APP_ID'] = APPCODE;
		$dt_in['EMP_ID'] = $emp_id;
		$dt_in['TINGKAT'] = $tingkat;
		$dt_in['STATUS'] = $apv_status;
		$dt_in['NAME_CREATED_BY'] = $emp_name;
		$dt_in['LAST_UPDATED_BY'] = $emp_id;
		$dt_in['ACTIVE_STATUS'] = 'Y';
		$dt_in['APPROVAL_STATUS'] = $apv_status;
		$dt_in['APPROVAL_LEVEL'] = $tingkat;
			
		$i=0;
		foreach($arr_trans_id as $dt_trans_id){
			$dt_in['ID_TRANSAKSI'] = $arr_trans_id[$i];
			$dt_in['ID_EMP_FREEZE'] = $arr_person_id[$i];
			$dt_in['NAME_EMP_FREEZE'] = $arr_person_name[$i];
			$dt_in['ID_MGR'] = $arr_mgr_id[$i];
			$dt_in['MGR_NAME'] = $arr_mgr_name[$i];
			$dt_in['ID_PLANT'] = $arr_plant_id[$i];
			$dt_in['PLANT_NAME'] = $arr_plant_name[$i];
			$dt_in['ID_POS'] = $arr_pos_id[$i];
			$dt_in['POS_NAME'] = $arr_pos_name[$i];
			$dt_in['ID_DEPARTMENT'] = $arr_dept_id[$i];
			$dt_in['DEPARTMENT_NAME'] = $arr_dept_name[$i];
			$dt_in['KETERANGAN'] = $arr_hrd_notes[$i];
			$dt_in['START_PERIOD'] = $arr_start_period[$i];
			$dt_in['END_PERIOD'] = $arr_end_period[$i];
			$dt_in['TOT_ALPHA'] = $arr_tot_alpha[$i];
			$dt_in['DATE_START_ALPHA'] = $arr_start_date_alpha[$i];
			$dt_in['DATE_END_ALPHA'] = $arr_end_date_alpha[$i];
			$dt_in['MGR_NOTES'] = $arr_mgr_notes[$i];
			
			apv_unfreeze_employee($con, $dt_in);
			$i++;
		}
		
		$totalrow = $i;
		$status = "sukses";
		$data = "sukses";
 
		$result = array('success' => true,
						'results' => $totalrow,
						'rows' 	  => $data,
						'status' => $status,
					);
		echo json_encode($result);
	}
	elseif($formType == 'UpdateReqUnfreeze')
	{
		$dt_in['FREEZE_STATUS'] = "";
		$dt_in['ACTIVE_STATUS'] = $_POST['activeStatus'];
		$dt_in['DATE_UNFREEZE'] = "";
		$dt_in['CREATED_BY'] = $emp_id;
		$dt_in['ID_MGR'] = $emp_id;
		$dt_in['LAST_UPDATED_BY'] = $emp_id;
		$dt_in['ID_MGR'] = $emp_id;
		$dt_in['HRD_NOTES'] = "";
		$dt_in['APPROVAL_STATUS'] = $apv_status;
		$dt_in['APPROVAL_LEVEL'] = $tingkat;
		$dt_in['APP_ID'] = APPCODE;
		
		$dt_in['ID_EMP_FREEZE'] = $_POST['emp_id'];
		$dt_in['ID_TRANSAKSI'] = $_POST['trans_id'];
		$dt_in['NAME_EMP_FREEZE'] = $_POST['emp_name'];
		$dt_in['ID_ORGANIZATION'] = $_POST['org_id'];
		$dt_in['ID_PLANT'] = $_POST['plant_id'];
		$dt_in['PLANT_NAME'] = $_POST['plant_name'];
		$dt_in['ID_DEPARTMENT'] = $_POST['dept_id'];
		$dt_in['DEPARTMENT_NAME'] = $_POST['dept_name'];
		$dt_in['ID_POS'] = $_POST['pos_id'];
		$dt_in['POS_NAME'] = $_POST['pos_name'];
		$dt_in['START_PERIOD'] = $_POST['start_date_absn'];
		$dt_in['END_PERIOD'] = $_POST['end_date_absn'];
		$dt_in['TOT_ALPHA'] = $_POST['tot_alpha'];
		$dt_in['DATE_START_ALPHA'] = $_POST['start_date_alpha'];
		$dt_in['DATE_END_ALPHA'] = $_POST['end_date_alpha'];
		$dt_in['MGR_NOTES'] = $_POST['mgr_notes'];
		
		insert_freeze_employee($con, $dt_in);
		
		$totalrow = 1;
		$status = "sukses";
		$data = "sukses";
 
		$result = array('success' => true,
						'results' => $totalrow,
						'rows' 	  => $data,
						'status' => $status,
					);
		echo json_encode($result);
	}	
	elseif($formType == 'CronReminderAlpha')
	{
		$dt_in['periode_max'] = date('Y-m-d');
		$dt_in['periode_min'] = date('Y-m-d', strtotime('-1 week'));
		$dt_in['APPROVAL_STATUS'] = 'P';
		$dt_in['APPROVAL_LEVEL'] = 0;
		auto_mail_freeze($con, $dt_in);
	}
?>