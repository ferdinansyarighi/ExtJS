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
  }
  


if ( isset( $_POST['hd_id'] ) )
{
	
	$hd_id = $_POST['hd_id'];
	$no_pinjaman = '';
	
	$result_valid = oci_parse( $con,"
		SELECT  COUNT( * )
		FROM    MJ_T_PELUNASAN_PINJAMAN_DT
		WHERE   ID_PINJAMAN = $hd_id
	");
	
	oci_execute( $result_valid );
	$row_valid = oci_fetch_row( $result_valid );
	$valid_flag = $row_valid[0];
	
	if ( $valid_flag == 0 ) {
		
		$valid_no_pinjaman = true;
		
	} else {
		
		
		// Jika data sudah pernah disimpan.
		
		$valid_no_pinjaman = false;
		
		
		$query_no_pinjaman = oci_parse( $con,"
			SELECT  NOMOR_PINJAMAN
			FROM    MJ_T_PINJAMAN
			WHERE   ID = $hd_id
		");
		
		oci_execute( $query_no_pinjaman );
		$row_no_pinjaman = oci_fetch_row( $query_no_pinjaman );
		$no_pinjaman = $row_no_pinjaman[0];
		
	}

	$result = array('success' => true,
					'results' => $valid_no_pinjaman,
					'no_pinjaman_param' => $no_pinjaman,
				);
	echo json_encode($result);
	
} else {
	
	$result = array('success' => false,
					'results' => $valid_no_pinjaman
				);
	echo json_encode($result);
	
}


?>