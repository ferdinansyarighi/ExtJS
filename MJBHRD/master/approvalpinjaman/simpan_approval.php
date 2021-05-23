<?PHP
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
	$emp_name = $_SESSION[APP]['emp_name'];
	$io_id = $_SESSION[APP]['io_id'];
	$io_name = $_SESSION[APP]['io_name'];
	$loc_id = $_SESSION[APP]['loc_id'];
	$loc_name = $_SESSION[APP]['loc_name'];
	$org_id = $_SESSION[APP]['org_id'];
	$org_name = $_SESSION[APP]['org_name'];
  }
  
$roleid = 0;
$emp_id_user = 0;
$data = "gagal";
$IdUser = 0;
$IdUserRule = 0;
$hdid=0;
$tglskr=date('Y-m-d');


if(isset($_POST['typeform']) || isset($_POST['status'])){
	
	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
	$perusahaan=$_POST['perusahaan'];
	$hrd=$_POST['hrd'];
	$acc=$_POST['acc'];
	$kasir=$_POST['kasir'];
	$status=$_POST['status'];
	$tipe_trans=$_POST['tipe_trans'];
	
	if ( $tipe_trans == 'BS' ) {
	
		$checker=$_POST['checker'];
	
	}
	
	if($status=='ACTIVE'){
		$status='A';
	} else {
		$status='I';
	}
	
}
//end deklarasi variable


// Proses penambahan data baru

if($typeform=="tambah"){

	$result = oci_parse($con, "
				SELECT COUNT(-1)
				FROM MJ.MJ_M_APPROVAL_PINJAMAN
				WHERE PERUSAHAAN_ID = '$perusahaan'
				AND TIPE = '$tipe_trans'
				AND STATUS = 'A'
				");
				
	/*
	if ( $tipe_trans == 'BS' ) {
		
		$result = oci_parse($con, "
					SELECT COUNT(-1)
					FROM MJ.MJ_M_APPROVAL_PINJAMAN
					WHERE PERUSAHAAN_ID = '$perusahaan'
					AND TIPE = '$tipe_trans'
					AND STATUS = 'A'
					");
	} else {
		
		if ( $tipe_trans == 'Pinjaman' ) {
			
			$result = oci_parse($con, "
						SELECT COUNT(-1)
						FROM MJ.MJ_M_APPROVAL_PINJAMAN
						WHERE PERUSAHAAN_ID = '$perusahaan'
						AND TIPE = '$tipe_trans'
						AND STATUS = 'A'
						");
			
		}
	}
	*/
	
	
	oci_execute($result);
	$rowJum = oci_fetch_row($result);
	$jumlah = $rowJum[0];
	
	if ($jumlah>0)
	{
		$data = "Data dengan tipe $tipe_trans pada perusahaan tersebut sudah pernah diinput.";
	} else {
		
		//insert
		
		$resultSeq = oci_parse($con,"SELECT MJ.MJ_M_APPROVAL_PINJAMAN_SEQ.nextval FROM dual");
		oci_execute($resultSeq);
		$row = oci_fetch_row($resultSeq);
		$IdUser = $row[0];
		
		if ( $tipe_trans == 'BS' ) {
			
			$sqlQuery = "
			INSERT INTO MJ.MJ_M_APPROVAL_PINJAMAN ( ID, PERUSAHAAN_ID, HRD_ID, ACCOUNTING_ID, KASIR_ID, STATUS, CREATED_BY, CREATED_DATE, TIPE, CHECKER_ID ) 
			VALUES ( $IdUser, $perusahaan, '$hrd', '$acc', '$kasir', '$status', $emp_id, SYSDATE, '$tipe_trans', '$checker' )";
			
		} else {
			
			if ( $tipe_trans == 'Pinjaman' ) {
			
				$sqlQuery = "
				INSERT INTO MJ.MJ_M_APPROVAL_PINJAMAN ( ID, PERUSAHAAN_ID, HRD_ID, ACCOUNTING_ID, KASIR_ID, STATUS, CREATED_BY, CREATED_DATE, TIPE ) 
				VALUES ( $IdUser, $perusahaan, '$hrd', '$acc', '$kasir', '$status', $emp_id, SYSDATE, '$tipe_trans' )";
			
			}
		}

		
		//echo $sqlQuery;
		$result = oci_parse($con,$sqlQuery);
		oci_execute($result);
		
		$data = "sukses";
	}
} else {
	
	// Update process

	$result = oci_parse($con, "
				SELECT COUNT(-1)
				FROM MJ.MJ_M_APPROVAL_PINJAMAN
				WHERE PERUSAHAAN_ID = '$perusahaan'
				AND TIPE = '$tipe_trans'
				AND STATUS = 'A'
				and ID != '$hdid'
				");
	oci_execute($result);
	$rowJum = oci_fetch_row($result);
	$jumlah = $rowJum[0];
	
	if ($jumlah>0 && $status == 'A')
	{
		$data = "Data dengan tipe $tipe_trans pada perusahaan tersebut sudah pernah diinput.";
	} else {
		
		if ( $tipe_trans == 'BS') {

			$result = oci_parse($con,"
			UPDATE MJ.MJ_M_APPROVAL_PINJAMAN 
			SET PERUSAHAAN_ID='$perusahaan', 
				HRD_ID = '$hrd', 
				ACCOUNTING_ID = '$acc', 
				KASIR_ID = '$kasir', 
				TIPE = '$tipe_trans',
				CHECKER_ID = '$checker',
				STATUS = '$status',
				LAST_UPDATED_BY = $emp_id, 
				LAST_UPDATED_DATE = SYSDATE 
				WHERE ID = '$hdid'");
				oci_execute($result); 
				$data = "sukses";
			
		} else {
			
			if ( $tipe_trans == 'Pinjaman' ) {
				
				$result = oci_parse($con,"
				UPDATE MJ.MJ_M_APPROVAL_PINJAMAN 
				SET PERUSAHAAN_ID='$perusahaan', 
					HRD_ID = '$hrd', 
					ACCOUNTING_ID = '$acc', 
					KASIR_ID = '$kasir', 
					TIPE = '$tipe_trans',
					STATUS = '$status',
					LAST_UPDATED_BY = $emp_id, 
					LAST_UPDATED_DATE = SYSDATE 
					WHERE ID = '$hdid'");
					
				oci_execute($result); 
		
				$data = "sukses";
			}
		}
	}	
	
}

$result = array('success' => true,
				'results' => $hdid,
				'rows' => $data
			);
echo json_encode($result);

?>