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


$tmpname = $_FILES['arsipfile']['tmp_name'];
$arsipType	= '';
$arsipSize	= '';
$arsipFile	= '';
$userid 	= $user_id;
$date1	= date('Y-m-d');
$date		= md5($date1);
if (is_uploaded_file($tmpname))
{
	$file_name = $_FILES['arsipfile']['name'];
	$file_size = $_FILES['arsipfile']['size'];
	$file_type = $_FILES['arsipfile']['type'];
	//echo $tmpname . " - " . $file_name;
	if(!move_uploaded_file($tmpname, $file_name)){ //success not upload
		$result = false;
	}
	else {
		$arsipType	= $file_type;
		$arsipSize	= $file_size;
		$arsipFile	= $file_name;
		$result = true;
	} 
}	 
//$output = array();
// $output = array('success' => true,
	// 'results' => $file_name,
	// 'rows' => 0
// );
$output['success']= true;
echo json_encode($output);
?>