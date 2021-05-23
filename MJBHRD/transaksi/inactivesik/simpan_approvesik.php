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
	$emp_name = $_SESSION[APP]['emp_name'];
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
$data="gagal";
$attachment="";
$typeform="";
$Keputusan="";
$arrTransID=array();
$arrBA=array();
$emailSblmPengepprove='';

 if(isset($_POST['typeform']))
  {
	$typeform=$_POST['typeform'];
	$arrTransID=json_decode($_POST['arrTransID']);
	$arrBA=json_decode($_POST['arrBA']);
  }
  
$countID = count($arrTransID);
for ($x=0; $x<$countID; $x++){
	$TransID = $arrTransID[$x];
	$namaBA = $arrBA[$x];

	$querySIK = "UPDATE MJ.MJ_T_SIK SET STATUS_DOK='$typeform', ATTACHMENT_BA='$namaBA', LAST_UPDATED_BY='$emp_name', LAST_UPDATED_DATE=SYSDATE WHERE ID=$TransID";
	$resultSIK = oci_parse($con,$querySIK);
	oci_execute($resultSIK);

}
	
$data="sukses";

$result = array('success' => true,
				'results' => 0,
				'rows' => $data
			);
echo json_encode($result);

?>