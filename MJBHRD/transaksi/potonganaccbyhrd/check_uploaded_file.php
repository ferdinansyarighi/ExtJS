<?PHP

include '../../main/koneksi.php';
date_default_timezone_set("Asia/Jakarta");

// deklarasi variable dan session
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
	
	$hasilcek = '';
	
	
	// Cek jumlah file yang telah di-upload oleh user dan tersimpan di MJ.MJ_TEMP_UPLOAD
	
	$countFile = oci_parse( $con, "	
		SELECT  COUNT( * )
		FROM    MJ.MJ_TEMP_UPLOAD
		WHERE   TRANSAKSI_KODE = 'PINJAMANHRD'
		AND     USERNAME = $user_id
		" );
	
	oci_execute( $countFile );
	$countResult = oci_fetch_row( $countFile );
	$result_count = $countResult[0];
	
	
	if ( $result_count > 0 ) {
		
		$hasilcek = 'Ada';
	
	} else {
		
		$hasilcek = 'Kosong';
		
	}
	
	// echo json_encode( $hasilcek );
	
	$result = array( 'results' => $hasilcek );
	
	echo json_encode($result);
	
}

	

?>