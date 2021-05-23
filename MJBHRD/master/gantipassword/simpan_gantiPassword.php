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
if(isset($_POST['passwordLama']) || isset($_POST['passwordBaru'])){
	$passwordLama=$_POST['passwordLama'];
	$passwordBaru=$_POST['passwordBaru'];
	$passwordBaru2=$_POST['passwordBaru2'];
}

$result = oci_parse($con,"UPDATE MJ.MJ_M_USER SET USERPASS='$passwordBaru', LAST_UPDATED_BY='$user_id', LAST_UPDATED_DATE=SYSDATE WHERE ID='$user_id'");
oci_execute($result); 

$data = "sukses";

$result = array('success' => true,
				'results' => $user_id,
				'rows' => $data
			);
echo json_encode($result);

?>