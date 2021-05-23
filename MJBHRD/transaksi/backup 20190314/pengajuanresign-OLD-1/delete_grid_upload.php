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
  $fiupload="";
  if(isset($_POST['idupload'])){
	$idupload=$_POST['idupload']; 
	$fiupload=$_POST['fiupload']; 

	$sqlQuery = "DELETE FROM MJ.MJ_TEMP_UPLOAD WHERE USERNAME='$user_id' AND id=$idupload AND TRANSAKSI_KODE='RESIGN' ";
	echo $sqlQuery;
	$result = oci_parse($con,$sqlQuery);
	oci_execute($result);

/*	$queryhehe = "SELECT COUNT(-1) FROM MJ.MJ_M_UPLOAD WHERE USERNAME='$user_id' AND FILENAME = '$fiupload' AND TRANSAKSI_KODE='RESIGN'";
	$resulthehe = oci_parse($con,$queryhehe);
	oci_execute($resulthehe);
	$rowhehe = oci_fetch_row($resulthehe);
	$hehe = $rowhehe[0];

	if($hehe >= 1)
	{
		$sqlQuerya = "DELETE FROM MJ.MJ_M_UPLOAD WHERE USERNAME='$user_id' AND FILENAME = '$fiupload' AND TRANSAKSI_KODE='RESIGN' ";
		echo $sqlQuerya;
		$resulta = oci_parse($con,$sqlQuerya);
		oci_execute($resulta);
	}*/

  }
	
	
	
?>