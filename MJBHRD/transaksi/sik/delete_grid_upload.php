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
  $idupload="";
  if(isset($_POST['idupload'])){
	$idupload=$_POST['idupload']; 
	$sqlQuery = "DELETE FROM MJ.MJ_TEMP_UPLOAD WHERE USERNAME='$user_id' AND id=$idupload AND TRANSAKSI_KODE='SIK' ";
	echo $sqlQuery;
	$result = oci_parse($con,$sqlQuery);
	oci_execute($result);
  }
	
	
	
?>