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

  $data = "";
  $idtrans="";
  if(isset($_POST['idtrans'])){
	$idtrans=$_POST['idtrans']; 
	$query = "SELECT TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE
	FROM MJ.MJ_M_UPLOAD
	WHERE APP_ID=" . APPCODE . " 
	AND TRANSAKSI_ID=$idtrans
	AND TRANSAKSI_KODE='PINJAMANKASIR'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$vTransID=$row[0];
		$vFilename=$row[1];
		$vFilesize=$row[2];
		$vFiletype=$row[3];
		$vFilekode=$row[6];
		
		$resultDetSeq = oci_parse($con,"SELECT MJ.MJ_TEMP_UPLOAD_SEQ.nextval FROM DUAL");
		oci_execute($resultDetSeq);
		$rowDSeq = oci_fetch_row($resultDetSeq);
		$seqD = $rowDSeq[0];
		
		$queryUpload = "INSERT INTO MJ.MJ_TEMP_UPLOAD (ID, APP_ID, TRANSAKSI_ID, FILENAME, FILESIZE, FILETYPE, USERNAME, CREATEDDATE, TRANSAKSI_KODE) 
		VALUES ($seqD, " . APPCODE . ", $vTransID, '$vFilename', '$vFilesize', '$vFiletype', '$user_id', SYSDATE, '$vFilekode')";
		//echo $query;
		$resultUpload = oci_parse($con,$queryUpload);
		oci_execute($resultUpload);
	}
  }
	
	
	
?>