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

 /*
$hari="";
$bulan="";
$tahun=""; 
$tglskr=date('Y-m-d'); 
$tahunbaru=substr($tglskr, 0, 2);
$tahunSkr=substr($tglskr, 0, 4);
$bulanSkr=substr($tglskr, 5, 2);
$hariSkr=substr($tglskr, 8, 2);
*/

$vBulanPotongan = 0;
$vBulanInsert = 0;

$vTahunPotongan = 0;
$vTahunInsert = 0;

$data="gagal";
 if(isset($_POST['hdid']))
  {
  	$hdid=$_POST['hdid'];
	$typeform=$_POST['typeform'];
  	$tgltf=$_POST['tgltf'];
  	$nominal=$_POST['nominal'];
  	$norek=$_POST['norek'];
  	$tipe=$_POST['tipe'];
	
	// $bank=$_POST['bank'];
  	// $an=$_POST['an'];
	
	
	$param_cb_rek_transfer = $_POST['param_cb_rek_transfer'];
	$param_cb_rek_transfer_nama = $_POST['param_cb_rek_transfer_nama'];
	$param_cb_rek_transfer_nomor = $_POST['param_cb_rek_transfer_nomor'];
	$param_cb_rek_transfer_id = $_POST['param_cb_rek_transfer_id'];
	
	if($tgltf != 'null' && $tgltf != ''){
		$tgl = "TO_DATE('$tgltf', 'YYYY-MM-DD')";
	}else{
		$tgl ="''";
	}
	//echo "UPDATE MJ.MJ_T_PINJAMAN SET TANGGAL_TRANSFER = $tgl, NOMINAL_TRANSFER = $nominal, BANK = '$bank', NOMOR_REKENING = '$norek', ATAS_NAMA = '$an', TINGKAT = 5, STATUS_DOKUMEN = 'Validate', TIPE_PENCAIRAN = '$tipe' WHERE ID = $hdid";exit;
	
	$resultSeqAPP = oci_parse($con,"SELECT MJ.MJ_T_APPROVAL_SEQ.nextval FROM dual"); 
	oci_execute($resultSeqAPP);
	$rowApp = oci_fetch_row($resultSeqAPP);
	$IdTransAPP = $rowApp[0];
	
	
	// DITUTUP SEMENTARA
	
	$sqlQueryApp = "INSERT INTO MJ.MJ_T_APPROVAL (ID, APP_ID, EMP_ID, TRANSAKSI_ID, TRANSAKSI_KODE, TINGKAT, STATUS, KETERANGAN, CREATED_BY, CREATED_DATE) 
	VALUES ( $IdTransAPP, " . APPCODE . ", '$emp_id', '$hdid', 'PINJAMAN', 5, '$typeform', '', $emp_id, SYSDATE)";
	$resultApp = oci_parse($con,$sqlQueryApp);
	oci_execute($resultApp);
	
	
	$sqlQueryA = "
	UPDATE MJ.MJ_T_PINJAMAN 
	SET TANGGAL_TRANSFER = $tgl, 
		NOMINAL_TRANSFER = $nominal, 
		NOMOR_REKENING = '$norek', 
		TINGKAT = 5, 
		STATUS_DOKUMEN = 'Validate', 
		TIPE_PENCAIRAN = '$tipe',
		REK_BANK_TRANSFER = '$param_cb_rek_transfer',
		NAME_BANK_TRANSFER = '$param_cb_rek_transfer_nama',
		BANK_ACCOUNT_ID = '$param_cb_rek_transfer_id',
		BANK_NAME = '$param_cb_rek_transfer_nomor'
	WHERE ID = $hdid";
	$resultA = oci_parse($con,$sqlQueryA);
	oci_execute($resultA);
	
	
	// BANK = '$bank', 
	// ATAS_NAMA = '$an', 
	
	
	
	// insert upload PINJAMAN
	$query = "SELECT FILENAME, FILESIZE, FILETYPE, TRANSAKSI_KODE
	FROM MJ.MJ_TEMP_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
	AND TRANSAKSI_KODE='PINJAMANKASIR'";
	
	//echo $query;
	
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$vFilename=$row[0];
		$vFilesize=$row[1];
		$vFiletype=$row[2];
		$vFilekode=$row[3];
		
		$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_M_UPLOAD_SEQ.nextval FROM DUAL");
		oci_execute($resultDetSeq);
		$rowDSeq = oci_fetch_row($resultDetSeq);
		$seqD = $rowDSeq[0];
		
		$queryUpload = "INSERT INTO MJ.MJ_M_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
		VALUES ($seqD, " . APPCODE . ", $hdid, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE, '$vFilekode')";
		//echo $query;
		$resultUpload = oci_parse($con,$queryUpload);
		oci_execute($resultUpload);
	}
	
	$query = "DELETE FROM MJ.MJ_TEMP_UPLOAD
	WHERE APP_ID=" . APPCODE . " AND USERNAME='$user_id'
	AND TRANSAKSI_KODE='PINJAMANKASIR'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	//end insert upload PINJAMAN

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