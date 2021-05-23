<?php
	include '../main/koneksi.php';
	session_start();
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
	
	if(isset($_SESSION[APP]['user_id']))
	{
		$user_id = $_SESSION[APP]['user_id'];
		$username = $_SESSION[APP]['username'];
		$emp_id = $_SESSION[APP]['emp_id'];
		$emp_name = $_SESSION[APP]['emp_name'];
		$io_id = $_SESSION[APP]['io_id'];
		$io_name = $_SESSION[APP]['io_name'];
		$loc_id = $_SESSION[APP]['loc_id'];
		$loc_name = $_SESSION[APP]['loc_name'];
		$org_id = $_SESSION[APP]['org_id'];
		$org_name = $_SESSION[APP]['org_name'];
	}
	
	$data = "";
	$name = "";
	$querywhere = "";
	$queryjob = "";
	if (isset($_GET['query']))
	{	
		$name = $_GET['query'];
		$name = strtoupper($name);
		$querywhere .= " AND UPPER(PPF.FULL_NAME) LIKE '%$name%' ";
	}
	
	if (isset($_GET['jobid']))
	{	
		$jobid = $_GET['jobid'];
		if ($jobid != '')
		{		
			$jobid = strtoupper($jobid);
			$queryjob .= " AND UPPER(NAME) LIKE '%$jobid%' ";
		}
	}
	
	if (isset($_GET['mgr_id']))
	{	
		$mgr_id = $_GET['mgr_id'];
		if ($mgr_id != '')
		{		
			$mgr_id = strtoupper($mgr_id);
			$queryjob .= " AND PAF.ASS_ATTRIBUTE2 = '".$mgr_id."' ";
		}
	}
	//echo $jobid;exit;
	$query = "SELECT DISTINCT PPF.PERSON_ID AS DATA_VALUE, PPF.FULL_NAME AS DATA_NAME 
				FROM APPS.PER_PEOPLE_F PPF
				INNER JOIN APPS.PER_ASSIGNMENTS_F PAF ON PAF.PERSON_ID=PPF.PERSON_ID
				LEFT JOIN APPS.PER_JOBS PJ ON PJ.JOB_ID = PAF.JOB_ID
				WHERE 1=1 
					AND PPF.EFFECTIVE_END_DATE > SYSDATE AND PAF.EFFECTIVE_END_DATE > SYSDATE 
					AND PAF.PRIMARY_FLAG='Y'
					$querywhere 
					$queryjob 
				ORDER BY PPF.FULL_NAME ASC";
	// echo $query;
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$record = array();
		$record['DATA_VALUE']=$row[0];
		$record['DATA_NAME']=$row[1];
		$data[]=$record;
	}

	echo json_encode($data);
?>