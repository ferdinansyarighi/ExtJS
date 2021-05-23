<?php
include '../../main/koneksi.php';
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
	$emp_name = str_replace("'", "''", $_SESSION[APP]['emp_name']);
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
	
	
	$queryDelete = "
	DELETE 	MJ.MJ_T_PELUNASAN_PINJAMAN_TMP 
	WHERE	USER_ID = $user_id
	";
	
	$resultDelete = oci_parse( $con, $queryDelete );
	oci_execute( $resultDelete );
	
	
	$result = array('success' => true,
				);
				
	echo json_encode($result);
	
  }


?>