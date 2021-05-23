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
  


if ( isset($_POST['hd_id']) && isset($_POST['outstanding']) && isset($_POST['process_type']) )
{

	$hd_id=$_POST['hd_id'];
	$outstanding=$_POST['outstanding'];
	$process_type=$_POST['process_type'];
	
	// echo $user_id . ' - ' . $hd_id . ' - ' . $outstanding . ' - ' . $user_id; exit;
	
	if ( $process_type == 'select' ) {
		
		$queryInsert = "
		INSERT INTO MJ.MJ_T_PELUNASAN_PINJAMAN_TMP ( USER_ID, ID_PINJAMAN, NOMINAL_OUTSTANDING, CREATED_BY, CREATED_DATE ) 
		VALUES ( '$user_id', $hd_id, $outstanding, '$user_id', SYSDATE )
		";
		
		$resultInsert = oci_parse( $con, $queryInsert );
		oci_execute( $resultInsert );
	
	} else {
		
		$queryDelete = "
		DELETE 	MJ.MJ_T_PELUNASAN_PINJAMAN_TMP 
		WHERE	USER_ID = $user_id
		AND 	ID_PINJAMAN = $hd_id
		";
		
		$resultDelete = oci_parse( $con, $queryDelete );
		oci_execute( $resultDelete );
		
	}
	
	// sleep( 0.5 );
	
	$result_outstanding = oci_parse( $con,"
		SELECT  NVL( SUM( NOMINAL_OUTSTANDING ), 0 )
		FROM    MJ_T_PELUNASAN_PINJAMAN_TMP
		WHERE   USER_ID = $user_id
	");
	
	oci_execute( $result_outstanding );
	$row_outstanding = oci_fetch_row( $result_outstanding );
	$nominal_outstanding = $row_outstanding[0];
	
	// echo json_encode( $nominal_outstanding ); 

	$result = array('success' => true,
					'results' => $nominal_outstanding
				);
	echo json_encode($result);
	
}


?>