<?PHP
//require('smtpemailattachment.php');
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

$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$tahunGenNo=substr($tglskr, 0, 4);
$data="gagal";
 if(isset($_POST['hdid']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
  	$keterangan=str_replace("'","`",$_POST['keterangan']);
	
	
	$resultSeqAPP = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM dual"); 
	oci_execute($resultSeqAPP);
	$rowApp = oci_fetch_row($resultSeqAPP);
	$IdTransAPP = $rowApp[0];
	
	$sqlQueryApp = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
	VALUES ( $IdTransAPP, " . APPCODE . ", '$emp_id', '$hdid', 'PINJAMAN', 4, '$typeform', '$keterangan', $emp_id, SYSDATE)";
	
	// VALUES ( $IdTransAPP, " . APPCODE . ", '$emp_id', '$hdid', 'PINJAMAN', 3, '$typeform', '$keterangan', $emp_id, SYSDATE)";
	
	$resultApp = oci_parse($con,$sqlQueryApp);
	oci_execute($resultApp);
	
	if($typeform == "Approved"){
		$sqlQueryA = "UPDATE MJ.MJ_T_PINJAMAN SET TINGKAT = 4, STATUS_DOKUMEN = 'Approved', KETERANGAN_ACC = '$keterangan' WHERE ID = $hdid";
		$resultA = oci_parse($con,$sqlQueryA);
		oci_execute($resultA);
	} else {
		
		$resultbyhrd = oci_parse($con,"SELECT BYHRD FROM MJ.MJ_T_PINJAMAN WHERE ID = $hdid");
		oci_execute($resultbyhrd);
		$rowbyhrd = oci_fetch_row($resultbyhrd);
		$byhrd = $rowbyhrd[0];
		
		if ( $byhrd == 'N' ) {
			$sqlQueryA = "
			UPDATE MJ.MJ_T_PINJAMAN 
			SET KETERANGAN_ACC = '$keterangan', 
			TINGKAT = 0, 
			STATUS_DOKUMEN = '$typeform' 
			WHERE ID = $hdid";
			$resultA = oci_parse($con,$sqlQueryA);
			oci_execute($resultA);
		} else {
			$sqlQueryA = "
			UPDATE MJ.MJ_T_PINJAMAN 
			SET KETERANGAN_ACC = '$keterangan', 
			TINGKAT = 2, 
			STATUS_DOKUMEN = '$typeform' 
			WHERE ID = $hdid";
			$resultA = oci_parse($con,$sqlQueryA);
			oci_execute($resultA);
		}
		
		/*
		$sqlQueryA = "UPDATE MJ.MJ_T_PINJAMAN SET TINGKAT = 0, STATUS_DOKUMEN = '$typeform', KETERANGAN_ACC = '$keterangan' WHERE ID = $hdid";
		$resultA = oci_parse($con,$sqlQueryA);
		oci_execute($resultA);
		*/
		
	}
	
	
	$data="sukses";
	
	$resultNo = oci_parse($con,"SELECT NOMOR_PINJAMAN FROM MJ.MJ_T_PINJAMAN WHERE ID = $hdid");
	oci_execute($resultNo);
	$rowNo = oci_fetch_row($resultNo);
	$noPinjaman = $rowNo[0];
	
	$result = array('success' => true,
					'results' => $hdid .'|'. $noPinjaman,
					'rows' => $data
				);
	echo json_encode($result);
  }


?>