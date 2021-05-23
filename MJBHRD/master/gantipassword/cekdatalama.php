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
  
$hasil = 0;
$data = "Gagal";
if(isset($_POST['passwordLama'])){
	$passwordLama=$_POST['passwordLama'];

	$query = "SELECT COUNT(-1)
	FROM MJ.MJ_M_USER
	WHERE UPPER(USERNAME) = '$username' AND UPPER(USERPASS) = '$passwordLama' AND STATUS = 'A'";
	$result = oci_parse($con, $query);
	oci_execute($result);
	while($row = oci_fetch_row($result))
	{
		$hasil=$row[0];
	}
	
	if($hasil >= 1){
		$data = "sukses";
	} else {
		$data = "Gagal";
	}
}

$result = array('success' => true,
			'results' => $hasil,
			'rows' => $data
		);
echo json_encode($result);

?>