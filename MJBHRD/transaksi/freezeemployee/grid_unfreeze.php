<?php
	session_start();
	require_once('../../main/koneksi.php');
	require_once('helper/define_session.php');
	require_once('model/freeze_employee.php');
	require_once('helper/acl.php');
	
	$dt_in = array();
	$dt_in['APP_ID'] = APPCODE;
	$dt_in['TRANSAKSI_KODE'] = 'UNFREEZE EMPLOYEE';
	
	$arr_pos = explode('.', $pos_name);
	$pos = $arr_pos[1];
	$dept = $arr_pos[0];
	
	$dt_tingkat = get_tingkat($dept, $pos);
	$tingkat = $dt_tingkat['tingkat'];
	$apv_status = $dt_tingkat['apv_status'];
	$dt_in['ID_USER'] = $user_id;
	$dt_in['TRANSAKSI_KODE'] = 'UNFREEZE EMPLOYEE';
	$dt_in['APPROVAL_STATUS'] = $apv_status;
	$dt_in['APPROVAL_LEVEL'] = $tingkat;
	$dt_in['id_top_mgr'] = $emp_id;
	
	if(isset($_GET['absn_period']))
	{
		if(!empty($_GET['absn_period']))
		{
			$absn_period = explode(' s/d ',$_GET['absn_period']);
		
			$dt_in['periode_min'] = trim($absn_period[0]);
			$dt_in['periode_max'] = trim($absn_period[1]);
		}
	}
	
	if(isset($_GET['org_id']))
	{
		$dt_in['org_id'] = $_GET['org_id'];
	}
	
	if(isset($_GET['plant']))
	{
		$dt_in['plant_name'] = $_GET['plant'];
	}
	
	if(isset($_GET['dept']))
	{
		$dt_in['dept_name'] = $_GET['dept'];
	}
	
	if(isset($_GET['emp_freeze']))
	{
		$dt_in['emp_id_freeze'] = trim($_GET['emp_freeze']);
	}
	
	if ($_GET['formType']=='apv')
	{
		$dt_rtn = get_request_unfreeze($con, $dt_in);
		$q = oci_parse($con, $dt_rtn['sql_get_req_unfreeze']);
		oci_execute($q);
		
		$data = NULL;
		$totalrow = 0;
		
		while (($row = oci_fetch_assoc($q)) != false) {
			
			$dt['DATA_NO'] = $totalrow + 1;
			$dt['DATA_HD_ID'] = $row['ID'];
			$dt['DATA_PERSON_ID'] = $row['ID_EMP_FREEZE'];
			$dt['DATA_EMPLOYEE_NAME'] = $row['FULL_NAME'];
			$dt['DATA_ID_MGR'] = $row['ID_MGR'];
			$dt['DATA_MGR_NAME'] = $row['MGR_FN'];
			$dt['DATA_ORGANIZATION_ID'] = $row['ORG_ID'];
			$dt['DATA_ORGANIZATION_UNITS'] = $row['ORG_NAME'];
			$dt['DATA_DEPARTMENT_ID'] = $row['DEPT_ID'];
			$dt['DATA_DEPARTMENT'] = $row['DEPT'];
			$dt['DATA_PLANT_ID'] = $row['LOCATION_ID'];
			$dt['DATA_PLANT'] = $row['LOCATION_CODE'];
			$dt['DATA_POS_ID'] = $row['POS_ID'];
			$dt['DATA_POS_NAME'] = $row['POS_NAME'];
			$dt['DATA_TOTAL_ALPHA'] = $row['TOT_ALPHA'];
			$dt['DATA_START_DATE_ALPHA'] = $row['DATE_START_ALPHA'];
			$dt['DATA_END_DATE_ALPHA'] = $row['DATE_END_ALPHA'];
			$dt['DATA_GROUP_SALARY'] = $row['GROUP_NAME'];
			$dt['DATA_START_PERIOD'] = $row['START_PERIOD'];
			$dt['DATA_END_PERIOD'] = $row['END_PERIOD'];
			$dt['DATA_MGR_NOTES'] = $row['MGR_NOTES'];
			$dt['DATA_HRD_NOTES'] = $row['HRD_NOTES'];
	
			$dt_in_attc['APP_ID'] = $dt_in['APP_ID'];
			$dt_in_attc['ID_TRANSAKSI'] = $dt['DATA_HD_ID'];
			$dt_in_attc['TRANSAKSI_KODE'] = $dt_in['TRANSAKSI_KODE'];
			$dt['DATA_ATTACHMENT'] = get_attachment($con, $dt_in_attc)['FILE_ATTC'];
			
			$data[] = $dt;
			
			$totalrow++;
		}
	}
	else
	{
		$dt_rtn = get_alpha_employee($con, $dt_in);
		$data = NULL;
		$totalrow = 0;
		
		if($dt_rtn['sql'])
		{
			$totalrow = 0;
			$data=$dt_rtn['data'];
		}
	}	
	$status = "sukses";

	$result = array('success' => true,
					'results' => $totalrow,
					'rows' 	  => $data,
					'status' => $status,
				);
	echo json_encode($result);
?>