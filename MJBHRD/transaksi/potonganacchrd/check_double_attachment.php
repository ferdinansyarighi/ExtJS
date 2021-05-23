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
	}
	
	
	if(isset($_POST['hdid']))
	{
		$hdid=$_POST['hdid'];
		
		
		// Proses edit di Existing saat hdid sudah terbentuk
		
		if ($hdid != '') 
		{
			// FROM    MJ.MJ_M_UPLOAD
			
			$countFile = oci_parse( $con, 
			"	SELECT  COUNT( * )
				FROM    MJ.MJ_TEMP_UPLOAD
				WHERE   TRANSAKSI_KODE = 'PINJAMANHRD'
				AND     TRANSAKSI_ID = $hdid
				GROUP   BY FILENAME
				HAVING  COUNT( * ) > 1"
			);
			
				
			oci_execute( $countFile );
			$countResult = oci_fetch_row( $countFile );
			$result_count = $countResult[0];
			
			// echo $result_count; exit;
			
			$result = array('success' => true,
							'results' => $result_count
						);
			
			echo json_encode($result);
			
		}
		
		
		// Proses entry new record saat hdid belum terbentuk
		
		else {
			$result_count = 1;
			
			$result = array('success' => true,
							'results' => $result_count
						);
			
			echo json_encode($result);
		}
			
  }


?>